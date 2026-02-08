<?php
require_once __DIR__ . '/../PHP/dbConfig.php';

$events = [];
$obituariesHasClient = false;
$obituariesHasBooking = false;

$checkClient = $conn->query("SHOW COLUMNS FROM obituaries LIKE 'client_id'");
if ($checkClient && $checkClient->num_rows > 0) {
  $obituariesHasClient = true;
}
if ($checkClient) {
  $checkClient->free();
}

$checkBooking = $conn->query("SHOW COLUMNS FROM obituaries LIKE 'booking_id'");
if ($checkBooking && $checkBooking->num_rows > 0) {
  $obituariesHasBooking = true;
}
if ($checkBooking) {
  $checkBooking->free();
}

if ($obituariesHasBooking) {
  $sql = "
    SELECT
      b.booking_id,
      b.client_id,
      b.service_date,
      b.service_time,
      u.full_name,
      o.interment AS interment_text
    FROM bookings b
    LEFT JOIN users u ON u.user_id = b.client_id
    LEFT JOIN (
      SELECT o1.booking_id, o1.interment
      FROM obituaries o1
      INNER JOIN (
        SELECT booking_id, MAX(created_at) AS max_created
        FROM obituaries
        GROUP BY booking_id
      ) latest ON latest.booking_id = o1.booking_id AND latest.max_created = o1.created_at
    ) o ON o.booking_id = b.booking_id
    WHERE (
      b.payment_status = 'paid'
      OR EXISTS (
        SELECT 1
        FROM booking_payments bp
        WHERE bp.booking_id = b.booking_id AND bp.status = 'paid'
      )
    )
    ORDER BY b.service_date ASC, b.service_time ASC
  ";
} elseif ($obituariesHasClient) {
  $sql = "
    SELECT
      b.booking_id,
      b.client_id,
      b.service_date,
      b.service_time,
      u.full_name,
      o.interment AS interment_text
    FROM bookings b
    LEFT JOIN users u ON u.user_id = b.client_id
    LEFT JOIN (
      SELECT o1.client_id, o1.interment
      FROM obituaries o1
      INNER JOIN (
        SELECT client_id, MAX(created_at) AS max_created
        FROM obituaries
        GROUP BY client_id
      ) latest ON latest.client_id = o1.client_id AND latest.max_created = o1.created_at
    ) o ON o.client_id = b.client_id
    WHERE (
      b.payment_status = 'paid'
      OR EXISTS (
        SELECT 1
        FROM booking_payments bp
        WHERE bp.booking_id = b.booking_id AND bp.status = 'paid'
      )
    )
    ORDER BY b.service_date ASC, b.service_time ASC
  ";
} else {
  $sql = "
    SELECT
      b.booking_id,
      b.client_id,
      b.service_date,
      b.service_time,
      u.full_name,
      NULL AS interment_text
    FROM bookings b
    LEFT JOIN users u ON u.user_id = b.client_id
    WHERE (
      b.payment_status = 'paid'
      OR EXISTS (
        SELECT 1
        FROM booking_payments bp
        WHERE bp.booking_id = b.booking_id AND bp.status = 'paid'
      )
    )
    ORDER BY b.service_date ASC, b.service_time ASC
  ";
}

function extractIntermentDate($intermentText) {
  if (!$intermentText) return null;
  $intermentText = trim($intermentText);

  if (preg_match('/\b(\d{4}-\d{2}-\d{2})\b/', $intermentText, $matches)) {
    return $matches[1];
  }

  if (preg_match('/\b([A-Za-z]+)\s+(\d{1,2}),\s*(\d{4})\b/', $intermentText, $matches)) {
    $date = DateTime::createFromFormat('F j, Y', $matches[1] . ' ' . $matches[2] . ', ' . $matches[3]);
    if ($date) return $date->format('Y-m-d');
  }

  return null;
}

if ($result = $conn->query($sql)) {
  while ($row = $result->fetch_assoc()) {
    $clientName = $row['full_name'] ? $row['full_name'] : ('Client #' . $row['client_id']);
    $intermentDate = extractIntermentDate($row['interment_text']);
    $events[] = [
      'id' => (int)$row['booking_id'],
      'date' => $row['service_date'],
      'time' => substr($row['service_time'], 0, 5),
      'client' => $clientName,
      'intermentDate' => $intermentDate
    ];
  }
  $result->free();
}

$conn->close();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Booking Calendar - DULCE</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    />
    <link rel="stylesheet" href="../CSS/style.css" />
    <style>
      .calendar-layout {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
        gap: 1.5rem;
      }

      .calendar-card,
      .detail-card {
        background: #ffffff;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        padding: 1.25rem;
      }

      .calendar-card {
        display: flex;
        flex-direction: column;
        min-height: 560px;
        max-height: 560px;
      }

      .calendar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
      }

      .calendar-header h3 {
        margin: 0;
        font-size: 1.25rem;
        color: #0f172a;
      }

      .calendar-nav {
        display: flex;
        gap: 0.5rem;
      }

      .calendar-btn {
        border: 1px solid #e5e7eb;
        background: #f8fafc;
        color: #1f2937;
        padding: 0.4rem 0.75rem;
        border-radius: 0.5rem;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.2s ease;
      }

      .calendar-btn:hover {
        background: #e2e8f0;
      }

      .range-controls {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
      }

      .range-controls label {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
      }

      .range-controls input[type="date"] {
        padding: 0.35rem 0.5rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        font-size: 0.85rem;
        color: #1f2937;
        background: #ffffff;
      }

      .range-controls .calendar-btn {
        padding: 0.35rem 0.6rem;
      }

      .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.5rem;
      }

      .calendar-wrapper {
        flex: 1;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 0.5rem;
        background: #f8fafc;
        overflow: auto;
      }

      .calendar-cell {
        min-height: 80px;
        padding: 0.5rem;
        border-radius: 0.6rem;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        position: relative;
        cursor: pointer;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        height: auto;
      }

      .calendar-cell:hover {
        border-color: #94a3b8;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
      }

      .calendar-cell.empty {
        background: transparent;
        border: none;
        cursor: default;
        box-shadow: none;
      }

      .calendar-cell.active {
        border-color: #2563eb;
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
      }

      .calendar-cell.today {
        border-color: #10b981;
      }

      .calendar-weekday {
        font-size: 0.7rem;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 2px;
      }

      .calendar-day {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1f2937;
      }

      .event-dots {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-top: 0.5rem;
      }

      .event-bar {
        display: flex;
        align-items: center;
        gap: 6px;
        width: 100%;
        min-height: 18px;
        padding: 2px 6px;
        border-radius: 999px;
        color: #ffffff;
        font-size: 10px;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.16);
      }

      .event-bar .event-label {
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
      }

      .event-bar.more {
        background: #e2e8f0;
        color: #1f2937;
        cursor: default;
        box-shadow: none;
      }

      .detail-card h4 {
        margin: 0 0 0.5rem;
        font-size: 1.1rem;
        color: #0f172a;
      }

      .detail-meta {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 1rem;
      }

      .detail-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
      }

      .detail-item {
        padding: 0.75rem;
        border-radius: 0.6rem;
        border: 1px solid #e5e7eb;
        background: #f8fafc;
      }

      .detail-item strong {
        display: block;
        color: #0f172a;
        margin-bottom: 0.25rem;
      }

      .empty-detail {
        padding: 1rem;
        border-radius: 0.6rem;
        border: 1px dashed #e2e8f0;
        color: #94a3b8;
        text-align: center;
      }

      @media (max-width: 980px) {
        .calendar-layout {
          grid-template-columns: 1fr;
        }
      }

      @media (max-width: 820px) {
        .calendar-header {
          align-items: flex-start;
        }

        .calendar-nav {
          width: 100%;
          justify-content: flex-start;
          flex-wrap: wrap;
        }

        .range-controls {
          width: 100%;
        }

        .calendar-grid {
          gap: 0.35rem;
        }

        .calendar-cell {
          min-height: 74px;
          padding: 0.4rem;
        }

        .calendar-day {
          font-size: 0.85rem;
        }
      }

      @media (max-width: 640px) {
        .calendar-card,
        .detail-card {
          padding: 1rem;
        }

        .calendar-header h3 {
          font-size: 1.1rem;
        }

        .range-controls input[type="date"] {
          width: 140px;
        }

        .calendar-grid {
          gap: 0.25rem;
        }

        .calendar-cell {
          min-height: 66px;
          padding: 0.35rem;
        }

        .calendar-weekday {
          font-size: 0.6rem;
        }

        .event-bar {
          font-size: 9px;
          min-height: 16px;
          padding: 2px 4px;
        }
      }

      @media (max-width: 480px) {
        .calendar-cell {
          min-height: 60px;
        }

        .event-bar {
          font-size: 8px;
        }
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
      <div class="container">
        <a class="navbar-brand" href="Index.html">
          <i class="bi bi-heart-pulse-fill"></i> DULCE
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item">
              <a class="nav-link" href="Index.html">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="chapel-services.html">Home Services</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="announcements.html">Announcements</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="obituaries.html">Obituaries</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="booking-calendar.php">Calendar</a>
            </li>
            <!-- User dropdown will be inserted here by session.js -->
          </ul>
        </div>
      </div>
    </nav>

    <section class="page-header">
      <div class="container text-center">
        <h1 class="page-title">Booking Calendar</h1>
        <p class="page-subtitle">
          View the current confirmed schedules from service date to interment.
        </p>
      </div>
    </section>

    <section class="dashboard-content">
      <div class="container">
        <div class="calendar-layout">
          <section class="calendar-card">
            <div class="calendar-header">
              <h3 id="calendarTitle">Month</h3>
              <div class="range-controls">
                <label for="rangeStart">From</label>
                <input type="date" id="rangeStart" />
                <label for="rangeEnd">To</label>
                <input type="date" id="rangeEnd" />
                <button class="calendar-btn" id="applyRange">Apply</button>
                <button class="calendar-btn" id="clearRange">Clear</button>
              </div>
              <div class="calendar-nav">
                <button class="calendar-btn" id="prevMonth">Prev</button>
                <button class="calendar-btn" id="todayBtn">Today</button>
                <button class="calendar-btn" id="nextMonth">Next</button>
              </div>
            </div>
            <div class="calendar-wrapper">
              <div class="calendar-grid" id="calendarGrid"></div>
            </div>
          </section>

          <aside class="detail-card">
            <h4 id="detailTitle">Schedule Details</h4>
            <div class="detail-meta" id="detailMeta">
              Select a date to see paid bookings.
            </div>
            <div class="detail-list" id="detailList">
              <div class="empty-detail">No date selected.</div>
            </div>
          </aside>
        </div>
      </div>
    </section>

    <script>
      const bookingEvents = <?php echo json_encode($events, JSON_UNESCAPED_SLASHES); ?>;

      const bookingColors = [
        "#2563eb",
        "#10b981",
        "#f59e0b",
        "#ef4444",
        "#8b5cf6",
        "#06b6d4",
        "#22c55e",
        "#f97316",
        "#ec4899",
        "#14b8a6",
        "#6366f1",
        "#84cc16"
      ];

      function colorForBooking(id) {
        const safeId = Number(id) || 0;
        const index = Math.abs(safeId) % bookingColors.length;
        return bookingColors[index];
      }

      const weekdayLabels = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

      function toDateKey(dateObj) {
        const pad = (num) => String(num).padStart(2, "0");
        return `${dateObj.getFullYear()}-${pad(dateObj.getMonth() + 1)}-${pad(
          dateObj.getDate(),
        )}`;
      }

      function toDateOnly(dateValue) {
        const date = new Date(dateValue + "T00:00:00");
        return isNaN(date.getTime()) ? null : date;
      }

      function addDays(dateObj, days) {
        const next = new Date(dateObj);
        next.setDate(next.getDate() + days);
        return next;
      }

      function buildEventsByDate(events) {
        const map = {};

        events.forEach((event) => {
          const start = toDateOnly(event.date);
          if (!start) return;
          const end = event.intermentDate
            ? toDateOnly(event.intermentDate)
            : start;
          const endDate = end && end >= start ? end : start;

          event.color = colorForBooking(event.id);

          let cursor = new Date(start);
          while (cursor <= endDate) {
            const key = toDateKey(cursor);
            if (!map[key]) map[key] = [];
            map[key].push(event);
            cursor = addDays(cursor, 1);
          }
        });

        return map;
      }

      let eventsByDate = buildEventsByDate(bookingEvents);

      const calendarGrid = document.getElementById("calendarGrid");
      const calendarTitle = document.getElementById("calendarTitle");
      const detailTitle = document.getElementById("detailTitle");
      const detailMeta = document.getElementById("detailMeta");
      const detailList = document.getElementById("detailList");

      let currentDate = new Date();
      let selectedDate = null;

      function formatMonthTitle(dateObj) {
        return dateObj.toLocaleString("en-US", { month: "long", year: "numeric" });
      }

      function formatTime(time24) {
        if (!time24) return "";
        const [h, m] = time24.split(":").map(Number);
        const period = h >= 12 ? "PM" : "AM";
        const hour = h % 12 || 12;
        const pad = (num) => String(num).padStart(2, "0");
        return `${hour}:${pad(m)} ${period}`;
      }

      function renderDetail(dateKey) {
        const events = eventsByDate[dateKey] || [];
        detailTitle.textContent = "Schedule Details";
        detailMeta.textContent = dateKey
          ? `Paid bookings for ${dateKey}`
          : "Select a date to see paid bookings.";
        detailList.innerHTML = "";

        if (!dateKey) {
          detailList.innerHTML = `<div class="empty-detail">No date selected.</div>`;
          return;
        }

        if (!events.length) {
          detailList.innerHTML = `<div class="empty-detail">No paid bookings on this date.</div>`;
          return;
        }

        events.forEach((event) => {
          const item = document.createElement("div");
          item.className = "detail-item";
          item.innerHTML = `
            <strong>${event.client}</strong>
            <div>Service: ${event.date}</div>
            <div>Interment: ${event.intermentDate || "N/A"}</div>
            <div>Time: ${formatTime(event.time)}</div>
            <div>Booking ID: #${event.id}</div>
          `;
          detailList.appendChild(item);
        });
      }

      function renderCalendar(dateObj) {
        const year = dateObj.getFullYear();
        const month = dateObj.getMonth();
        const firstDay = new Date(year, month, 1);
        const startDay = firstDay.getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const todayKey = toDateKey(new Date());

        calendarTitle.textContent = formatMonthTitle(dateObj);
        calendarGrid.innerHTML = "";

        for (let i = 0; i < startDay; i++) {
          const emptyCell = document.createElement("div");
          emptyCell.className = "calendar-cell empty";
          calendarGrid.appendChild(emptyCell);
        }

        for (let day = 1; day <= daysInMonth; day++) {
          const cellDate = new Date(year, month, day);
          const dateKey = toDateKey(cellDate);
          const events = eventsByDate[dateKey] || [];

          const cell = document.createElement("div");
          cell.className = "calendar-cell";
          if (dateKey === todayKey) cell.classList.add("today");
          if (selectedDate === dateKey) cell.classList.add("active");

          const weekdayLabel = document.createElement("div");
          weekdayLabel.className = "calendar-weekday";
          weekdayLabel.textContent = weekdayLabels[cellDate.getDay()];

          const dayLabel = document.createElement("div");
          dayLabel.className = "calendar-day";
          dayLabel.textContent = day;

          const bars = document.createElement("div");
          bars.className = "event-dots";

          if (events.length) {
            const maxBars = 3;
            events.slice(0, maxBars).forEach((event) => {
              const bar = document.createElement("div");
              bar.className = "event-bar";
              bar.style.background = event.color;
              bar.innerHTML = `<span class="event-label">${event.client}</span>`;
              bar.addEventListener("click", (e) => {
                e.stopPropagation();
                selectedDate = dateKey;
                renderCalendar(currentDate);
                renderDetail(dateKey);
              });
              bars.appendChild(bar);
            });

            if (events.length > maxBars) {
              const more = document.createElement("div");
              more.className = "event-bar more";
              more.textContent = `+${events.length - maxBars} more`;
              bars.appendChild(more);
            }
          }

          cell.appendChild(weekdayLabel);
          cell.appendChild(dayLabel);
          cell.appendChild(bars);

          cell.addEventListener("click", () => {
            selectedDate = dateKey;
            renderCalendar(currentDate);
            renderDetail(dateKey);
          });

          calendarGrid.appendChild(cell);
        }
      }

      function applyRangeFilter() {
        const startInput = document.getElementById("rangeStart").value;
        const endInput = document.getElementById("rangeEnd").value;

        if (!startInput || !endInput) {
          eventsByDate = buildEventsByDate(bookingEvents);
          renderCalendar(currentDate);
          if (selectedDate) renderDetail(selectedDate);
          return;
        }

        const start = toDateOnly(startInput);
        const end = toDateOnly(endInput);
        if (!start || !end || end < start) {
          alert("Please select a valid date range.");
          return;
        }

        const filtered = bookingEvents.filter((event) => {
          const serviceDate = toDateOnly(event.date);
          if (!serviceDate) return false;
          const intermentDate = event.intermentDate
            ? toDateOnly(event.intermentDate)
            : serviceDate;
          const rangeEnd = intermentDate && intermentDate >= serviceDate
            ? intermentDate
            : serviceDate;
          return rangeEnd >= start && serviceDate <= end;
        });

        eventsByDate = buildEventsByDate(filtered);
        renderCalendar(currentDate);
        if (selectedDate) renderDetail(selectedDate);
      }

      document.getElementById("applyRange").addEventListener("click", applyRangeFilter);
      document.getElementById("clearRange").addEventListener("click", () => {
        document.getElementById("rangeStart").value = "";
        document.getElementById("rangeEnd").value = "";
        eventsByDate = buildEventsByDate(bookingEvents);
        renderCalendar(currentDate);
        if (selectedDate) renderDetail(selectedDate);
      });

      document.getElementById("prevMonth").addEventListener("click", () => {
        currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1);
        renderCalendar(currentDate);
      });

      document.getElementById("nextMonth").addEventListener("click", () => {
        currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 1);
        renderCalendar(currentDate);
      });

      document.getElementById("todayBtn").addEventListener("click", () => {
        currentDate = new Date();
        selectedDate = toDateKey(currentDate);
        renderCalendar(currentDate);
        renderDetail(selectedDate);
      });

      renderCalendar(currentDate);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../JavaScript/session.js"></script>
  </body>
</html>

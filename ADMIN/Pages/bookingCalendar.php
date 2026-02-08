<?php
$currentPage = 'Calendar';
require_once __DIR__ . '/../PHP/dbConfig.php';

$events = [];
$obituariesHasClient = false;
$obituariesHasBooking = false;

// Ensure columns exist for linking obituaries to bookings/clients.
// Uses best-effort ALTER TABLE to avoid breaking the page if privileges are limited.
$checkClient = $conn->query("SHOW COLUMNS FROM obituaries LIKE 'client_id'");
if ($checkClient && $checkClient->num_rows > 0) {
  $obituariesHasClient = true;
}
if ($checkClient) {
  $checkClient->free();
}
if (!$obituariesHasClient) {
  $conn->query("ALTER TABLE obituaries ADD COLUMN client_id INT(11) NULL");
  $checkClient = $conn->query("SHOW COLUMNS FROM obituaries LIKE 'client_id'");
  if ($checkClient && $checkClient->num_rows > 0) {
    $obituariesHasClient = true;
  }
  if ($checkClient) {
    $checkClient->free();
  }
}

$checkBooking = $conn->query("SHOW COLUMNS FROM obituaries LIKE 'booking_id'");
if ($checkBooking && $checkBooking->num_rows > 0) {
  $obituariesHasBooking = true;
}
if ($checkBooking) {
  $checkBooking->free();
}
if (!$obituariesHasBooking) {
  $conn->query("ALTER TABLE obituaries ADD COLUMN booking_id INT(11) NULL");
  $checkBooking = $conn->query("SHOW COLUMNS FROM obituaries LIKE 'booking_id'");
  if ($checkBooking && $checkBooking->num_rows > 0) {
    $obituariesHasBooking = true;
  }
  if ($checkBooking) {
    $checkBooking->free();
  }
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
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Booking Calendar</title>
  <link rel="stylesheet" href="../CSS/adminPage.css">
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
      max-height: 80px;
      padding: 0.5rem;
      border-radius: 0.6rem;
      border: 1px solid #e5e7eb;
      background: #ffffff;
      position: relative;
      cursor: pointer;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
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

    .calendar-day {
      font-size: 0.95rem;
      font-weight: 600;
      color: #1f2937;
    }

    .calendar-weekday {
      font-size: 0.7rem;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      margin-bottom: 2px;
    }

    .event-dots {
      display: flex;
      flex-direction: column;
      gap: 4px;
      margin-top: 0.5rem;
      max-height: 60px;
      overflow: hidden;
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
      .content-area {
        padding: 1rem;
      }

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
  <div class="container">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
      <!-- Sidebar Header -->
      <div class="sidebar-header">
        <h1 class="sidebar-title">Admin Panel</h1>
        <button class="toggle-btn" onclick="toggleSidebar()">
          <svg id="menuIcon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <!-- Menu Items -->
      <div class="menu">
        <button class="menu-item <?php echo $currentPage == 'Dashboard' ? 'active' : ''; ?>"
          onclick="location.href='adminInitialPage.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
            </path>
          </svg>
          <span class="menu-label">Dashboard</span>
        </button>

        <button class="menu-item <?php echo $currentPage == 'Users' ? 'active' : ''; ?>"
          onclick="location.href='funeralPackage.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
            </path>
          </svg>
          <span class="menu-label">Funeral Services</span>
        </button>

        <button class="menu-item <?php echo $currentPage == 'Products' ? 'active' : ''; ?>"
          onclick="location.href='chapelServices.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
          </svg>
          <span class="menu-label">Home Services</span>
        </button>

        <button class="menu-item <?php echo $currentPage == 'Announcements' ? 'active' : ''; ?>"
          onclick="location.href='announcementCrud.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M11 5h2m-1-1v2m7 4V9a5 5 0 00-10 0v1m-2 0a2 2 0 00-2 2v1a2 2 0 002 2h12a2 2 0 002-2v-1a2 2 0 00-2-2H7z">
            </path>
          </svg>
          <span class="menu-label">Announcements</span>
        </button>

        <button class="menu-item billing-toggle" onclick="toggleSubmenu('billingSubmenu')">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 7h18M5 11h14M5 15h6m-8-8h18v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z">
            </path>
          </svg>
          <span class="menu-label">Billing</span>
        </button>
        <div class="submenu" id="billingSubmenu">
          <button class="submenu-item" onclick="location.href='paymentMethod.php'">Payment Method</button>
          <button class="submenu-item" onclick="location.href='transactions.php'">Transactions</button>
        </div>

        <button class="menu-item <?php echo $currentPage == 'Calendar' ? 'active' : ''; ?>"
          onclick="location.href='bookingCalendar.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z">
            </path>
          </svg>
          <span class="menu-label">Booking Calendar</span>
        </button>

        <button class="menu-item <?php echo $currentPage == 'Orders' ? 'active' : ''; ?>"
          onclick="location.href='orders.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
            </path>
          </svg>
          <span class="menu-label">Orders</span>
        </button>

        <button class="menu-item <?php echo $currentPage == 'Reports' ? 'active' : ''; ?>"
          onclick="location.href='reports.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
            </path>
          </svg>
          <span class="menu-label">Reports</span>
        </button>

        <button class="menu-item <?php echo $currentPage == 'Documents' ? 'active' : ''; ?>"
          onclick="location.href='documents.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
            </path>
          </svg>
          <span class="menu-label">Documents</span>
        </button>

        <button class="menu-item <?php echo $currentPage == 'Settings' ? 'active' : ''; ?>"
          onclick="location.href='settings.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
            </path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
            </path>
          </svg>
          <span class="menu-label">Settings</span>
        </button>
      </div>

      <!-- Sidebar Footer -->
      <div class="sidebar-footer">
        <button class="logout-btn" onclick="logout()">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
          </svg>
          <span class="menu-label">Logout</span>
        </button>
      </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Header -->
      <header class="header">
        <div class="header-content">
          <div class="header-title">
            <h2 id="pageTitle">Booking Calendar</h2>
            <p>Paid bookings are marked from service date to interment date.</p>
          </div>
          <div class="user-info">
            <div class="user-details">
              <p class="user-name">John Doe</p>
              <p class="user-role">Administrator</p>
            </div>
            <div class="user-avatar">JD</div>
          </div>
        </div>
      </header>

      <!-- Content Area -->
      <main class="content-area">
        <div class="content-container">
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
              <div class="detail-meta" id="detailMeta">Select a date to see paid bookings.</div>
              <div class="detail-list" id="detailList">
                <div class="empty-detail">No date selected.</div>
              </div>
            </aside>
          </div>
        </div>
      </main>
    </div>
  </div>

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

    function toDateKey(dateObj) {
      const pad = (num) => String(num).padStart(2, "0");
      return `${dateObj.getFullYear()}-${pad(dateObj.getMonth() + 1)}-${pad(dateObj.getDate())}`;
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

      events.forEach(event => {
        const start = toDateOnly(event.date);
        if (!start) return;
        const end = event.intermentDate ? toDateOnly(event.intermentDate) : start;
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
      detailMeta.textContent = dateKey ? `Paid bookings for ${dateKey}` : "Select a date to see paid bookings.";
      detailList.innerHTML = "";

      if (!dateKey) {
        detailList.innerHTML = `<div class="empty-detail">No date selected.</div>`;
        return;
      }

      if (!events.length) {
        detailList.innerHTML = `<div class="empty-detail">No paid bookings on this date.</div>`;
        return;
      }

      events.forEach(event => {
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

    const weekdayLabels = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

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

        const dots = document.createElement("div");
        dots.className = "event-dots";

        if (events.length) {
          const maxBars = 3;
          events.slice(0, maxBars).forEach(event => {
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
            dots.appendChild(bar);
          });

          if (events.length > maxBars) {
            const more = document.createElement("div");
            more.className = "event-bar more";
            more.textContent = `+${events.length - maxBars} more`;
            dots.appendChild(more);
          }
        }

        cell.appendChild(weekdayLabel);
        cell.appendChild(dayLabel);
        cell.appendChild(dots);

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

      const filtered = bookingEvents.filter(event => {
        const serviceDate = toDateOnly(event.date);
        if (!serviceDate) return false;
        const intermentDate = event.intermentDate ? toDateOnly(event.intermentDate) : serviceDate;
        const rangeEnd = intermentDate && intermentDate >= serviceDate ? intermentDate : serviceDate;
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

  <script>
    // Toggle sidebar
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const menuIcon = document.getElementById('menuIcon');
      sidebar.classList.toggle('collapsed');
      menuIcon.innerHTML = sidebar.classList.contains('collapsed') ?
        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>' :
        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
    }

    function toggleSubmenu(id) {
      const submenu = document.getElementById(id);
      if (!submenu) return;
      submenu.classList.toggle('open');
    }

    // Logout function
    function logout() {
      if (confirm('Are you sure you want to logout?')) {
        alert('Logged out successfully!');
      }
    }
  </script>
</body>

</html>

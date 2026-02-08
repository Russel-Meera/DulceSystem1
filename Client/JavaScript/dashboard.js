// DULCE - Dashboard JavaScript

document.addEventListener("DOMContentLoaded", function () {
  loadDashboardData();
  setupDashboardModalFocus();
});

// Load dashboard statistics and recent bookings
function loadDashboardData() {
  // Fetch dashboard statistics
  fetch("http://localhost/DULCESYSTEM1/Client/api/get-dashboard-stats.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        document.getElementById("totalBookings").textContent =
          data.stats.totalBookings;
        document.getElementById("pendingBookings").textContent =
          data.stats.pendingBookings;
        document.getElementById("totalDocuments").textContent =
          data.stats.totalDocuments;
        const totalPayments = Number(data.stats.totalPayments || 0);
        document.getElementById("totalPayments").textContent =
          "₱" + totalPayments.toFixed(2);
      }
    })
    .catch((error) => {
      console.error("Error loading dashboard stats:", error);
    });

  // Fetch recent bookings
  fetch("http://localhost/DULCESYSTEM1/Client/api/get-bookings.php?limit=5")
    .then((response) => response.json())
    .then((data) => {
      if (data.success && data.bookings.length > 0) {
        displayRecentBookings(data.bookings);
      } else {
        // Show empty state (already in HTML)
      }
    })
    .catch((error) => {
      console.error("Error loading recent bookings:", error);
    });

  fetch("http://localhost/DULCESYSTEM1/Client/PHP/getAnnouncements.php")
    .then((response) => response.json())
    .then((data) => {
      if (Array.isArray(data)) {
        displayRecentAnnouncements(data.slice(0, 3));
      }
    })
    .catch((error) => {
      console.error("Error loading announcements:", error);
    });
}

// Display recent bookings
function displayRecentBookings(bookings) {
  const container = document.getElementById("recentBookings");
  container.innerHTML = "";

  bookings.forEach((booking) => {
    const bookingCard = `
            <div class="booking-item-small mb-3 booking-clickable"
                 data-booking-id="${booking.booking_id}">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <strong>${booking.chapel_name || "Home Service"}</strong>
                        <small class="text-muted">${formatDate(booking.service_date)}</small>
                    </div>
                    <span class="badge bg-${getStatusColor(booking.booking_status)}">
                        ${booking.booking_status}
                    </span>
                </div>
            </div>
        `;
    container.innerHTML += bookingCard;
  });

  attachBookingClickHandlers();
}

function attachBookingClickHandlers() {
  const items = document.querySelectorAll(".booking-clickable");
  if (!items.length) return;

  const modalEl = document.getElementById("bookingDetailModal");
  if (!modalEl || !window.bootstrap) return;
  const modal = new bootstrap.Modal(modalEl);

  const detailService = document.getElementById("bookingDetailService");
  const detailDate = document.getElementById("bookingDetailDate");
  const detailTime = document.getElementById("bookingDetailTime");
  const detailStatus = document.getElementById("bookingDetailStatus");
  const detailAmount = document.getElementById("bookingDetailAmount");
  const detailFeatures = document.getElementById("bookingDetailFeatures");
  const detailDeceasedName = document.getElementById("bookingDetailDeceasedName");
  const detailDeceasedDates = document.getElementById("bookingDetailDeceasedDates");
  const detailWake = document.getElementById("bookingDetailWakeSchedule");
  const detailViewing = document.getElementById("bookingDetailViewingHours");
  const detailInterment = document.getElementById("bookingDetailInterment");

  items.forEach((item) => {
    item.addEventListener("click", () => {
      modalEl._lastFocusElement = document.activeElement;
      const bookingId = item.dataset.bookingId;
      if (!bookingId) return;

      detailService.textContent = "Loading...";
      detailDate.textContent = "";
      detailTime.textContent = "";
      detailStatus.textContent = "";
      detailAmount.textContent = "";
      detailFeatures.textContent = "Loading...";
      detailDeceasedName.textContent = "Loading...";
      detailDeceasedDates.textContent = "";
      detailWake.textContent = "";
      detailViewing.textContent = "";
      detailInterment.textContent = "";
      modal.show();

      fetch(`http://localhost/DULCESYSTEM1/Client/api/get-bookings.php?id=${bookingId}`)
        .then((response) => response.json())
        .then((data) => {
          if (!data.success || !data.booking) return;
          const booking = data.booking;
          const service = booking.chapel_name || "Home Service";
          const date = booking.service_date ? formatDate(booking.service_date) : "";
          const time = booking.service_time ? formatTime(booking.service_time) : "";
          const status = booking.booking_status || "";
          const amount = Number(booking.total_amount || 0);
          const features = Array.isArray(booking.chapel_features)
            ? booking.chapel_features
            : [];
          const deceasedName = booking.deceased_name || "";
          const deceasedBirth = booking.birth_date || "";
          const deceasedDeath = booking.death_date || "";
          const deceasedAge = booking.age || "";
          const wakeSchedule = booking.wake_schedule || "";
          const viewingHours = booking.viewing_hours || "";
          const interment = booking.interment || "";

          detailService.textContent = service;
          detailDate.textContent = date || "N/A";
          detailTime.textContent = time || "N/A";
          detailStatus.textContent = status ? `Status: ${status}` : "Status: N/A";
          detailAmount.textContent = `₱${amount.toFixed(2)}`;
          detailFeatures.textContent = features.length
            ? features.join(", ")
            : "No features listed.";
          detailDeceasedName.textContent = deceasedName || "N/A";
          if (deceasedBirth || deceasedDeath) {
            detailDeceasedDates.textContent = `${formatDate(deceasedBirth)} - ${formatDate(deceasedDeath)}${deceasedAge ? ` (Age ${deceasedAge})` : ""}`;
          } else {
            detailDeceasedDates.textContent = "Birth/Death dates not provided.";
          }
          detailWake.textContent = wakeSchedule || "Wake schedule not provided.";
          detailViewing.textContent = viewingHours || "Viewing hours not provided.";
          detailInterment.textContent = interment || "Interment not provided.";
        })
        .catch((error) => {
          console.error("Failed to load booking details:", error);
        });
    });
  });
}

function setupDashboardModalFocus() {
  const modalEl = document.getElementById("bookingDetailModal");
  if (!modalEl) return;

  modalEl.addEventListener("hidden.bs.modal", () => {
    const last = modalEl._lastFocusElement;
    if (last && typeof last.focus === "function") {
      last.focus();
    }
    modalEl._lastFocusElement = null;
  });
}

function escapeHtml(value) {
  return String(value)
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#39;");
}

function formatTime(time24) {
  if (!time24) return "";
  const [h, m] = time24.split(":").map(Number);
  const period = h >= 12 ? "PM" : "AM";
  const hour = h % 12 || 12;
  return `${hour}:${String(m).padStart(2, "0")} ${period}`;
}

function displayRecentAnnouncements(items) {
  const container = document.getElementById("recentAnnouncements");
  if (!container) return;

  if (!items.length) {
    container.innerHTML = `
      <div class="text-center py-3 text-muted">
        <i class="bi bi-megaphone" style="font-size: 2rem"></i>
        <p class="mt-2">No announcements yet</p>
      </div>
    `;
    return;
  }

  container.innerHTML = items
    .map(
      (item) => `
        <div class="announcement-item-small">
          <div class="announcement-date-small">${item.dateFormatted || ""}</div>
          <div class="announcement-title-small">${item.title}</div>
        </div>
      `,
    )
    .join("");

  container.innerHTML += `
    <a href="announcements.html" class="view-all-link">
      View All Announcements <i class="bi bi-arrow-right"></i>
    </a>
  `;
}

// Helper functions
function formatDate(dateString) {
  const options = { year: "numeric", month: "long", day: "numeric" };
  return new Date(dateString).toLocaleDateString("en-US", options);
}

function getStatusColor(status) {
  const colors = {
    Pending: "warning",
    Confirmed: "info",
    Completed: "success",
    Cancelled: "danger",
    Approved: "info",
  };
  return colors[status] || "secondary";
}

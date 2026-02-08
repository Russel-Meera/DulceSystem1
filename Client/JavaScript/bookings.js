// DULCE - Bookings Page JavaScript

document.addEventListener("DOMContentLoaded", function () {
  setupBookingsFilter();
  loadBookings("all");
  setupBookingModalFocus();
});

// Setup filter functionality
function setupBookingsFilter() {
  const filterLinks = document.querySelectorAll(".bookings-filter .nav-link");

  filterLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();

      // Update active state
      filterLinks.forEach((l) => l.classList.remove("active"));
      this.classList.add("active");

      // Filter bookings
      const filter = this.getAttribute("data-filter");
      loadBookings(filter);
    });
  });
}

// Load bookings from server
function loadBookings(filter = "all") {
  fetch(
    `http://localhost/DULCESYSTEM1/Client/api/get-bookings.php?filter=${filter}`,
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        if (data.bookings.length > 0) {
          displayBookings(data.bookings);
        } else {
          showEmptyState();
        }
      }
    })
    .catch((error) => {
      console.error("Error loading bookings:", error);
    });
}

// Display bookings
function displayBookings(bookings) {
  const container = document.getElementById("bookingsList");
  const emptyState = document.getElementById("emptyState");

  // Hide sample cards
  const sampleCards = container.querySelectorAll(".booking-item");
  sampleCards.forEach((card) => (card.style.display = "none"));

  emptyState.style.display = "none";

  // Clear previously rendered bookings (keep empty state node)
  const rendered = container.querySelectorAll(".booking-item.rendered");
  rendered.forEach((card) => card.remove());

  // Display fetched bookings
  bookings.forEach((booking) => {
    const bookingCard = createBookingCard(booking);
    container.insertAdjacentHTML("beforeend", bookingCard);
  });
}

// Create booking card HTML
function createBookingCard(booking) {
  return `
        <div class="col-lg-6 booking-item rendered" data-status="${booking.booking_status.toLowerCase()}">
            <div class="booking-card">
                <div class="booking-header">
                    <div class="booking-id">#DULCE-${booking.booking_id}</div>
                    <span class="booking-status ${booking.booking_status.toLowerCase()}">
                        <i class="bi ${getStatusIcon(booking.booking_status)}"></i> ${booking.booking_status}
                    </span>
                </div>
                <div class="booking-body">
                    <div class="booking-detail">
                        <i class="bi bi-building"></i>
                        <div>
                            <strong>Home Service:</strong>
                            <span>${booking.chapel_name || "N/A"}</span>
                        </div>
                    </div>
                    ${
                      booking.total_amount
                        ? `<div class="booking-detail">
                             <i class="bi bi-cash-stack"></i>
                             <div>
                               <strong>Amount:</strong>
                               <span>₱${Number(booking.total_amount).toFixed(2)}</span>
                             </div>
                           </div>`
                        : ""
                    }
                    <div class="booking-detail">
                        <i class="bi bi-calendar-event"></i>
                        <div>
                            <strong>Service Date:</strong>
                            <span>${formatDate(booking.service_date)}</span>
                        </div>
                    </div>
                    <div class="booking-detail">
                        <i class="bi bi-clock"></i>
                        <div>
                            <strong>Service Time:</strong>
                            <span>${booking.service_time}</span>
                        </div>
                    </div>
                    <div class="booking-detail">
                        <i class="bi bi-calendar-plus"></i>
                        <div>
                            <strong>Booked On:</strong>
                            <span>${formatDate(booking.created_at)}</span>
                        </div>
                    </div>
                </div>
                <div class="booking-footer">
                    <button class="btn btn-outline-primary btn-sm" onclick="viewBookingDetails(${booking.booking_id})">
                        <i class="bi bi-eye"></i> View Details
                    </button>
                    ${
                      booking.booking_status === "Pending"
                        ? '<button class="btn btn-outline-danger btn-sm"><i class="bi bi-x-circle"></i> Cancel</button>'
                        : '<button class="btn btn-primary-custom btn-sm"><i class="bi bi-file-earmark-arrow-up"></i> Upload Documents</button>'
                    }
                </div>
            </div>
        </div>
    `;
}

// Show empty state
function showEmptyState() {
  const emptyState = document.getElementById("emptyState");
  emptyState.style.display = "block";
}

// View booking details
function viewBookingDetails(bookingId) {
  const modalBody = document.getElementById("modalBookingDetails");
  const modalEl = document.getElementById("bookingModal");
  if (modalEl) {
    modalEl.dataset.lastFocus = document.activeElement
      ? document.activeElement.getAttribute("data-focus-id") ||
        document.activeElement.id ||
        ""
      : "";
    modalEl._lastFocusElement = document.activeElement;
  }

  modalBody.innerHTML = `
        <div class="booking-details-view">
            <h5 class="text-primary mb-3">Booking Details</h5>
            <p class="text-muted">Loading details...</p>
        </div>
    `;

  const modal = new bootstrap.Modal(modalEl);
  modal.show();

  fetch(`http://localhost/DULCESYSTEM1/Client/api/get-bookings.php?id=${bookingId}`)
    .then((response) => response.json())
    .then((data) => {
      if (!data.success || !data.booking) {
        modalBody.innerHTML = `<p class="text-danger">Unable to load booking details.</p>`;
        return;
      }

      const booking = data.booking;
      const features = Array.isArray(booking.chapel_features)
        ? booking.chapel_features
        : [];
      const serviceDate = booking.service_date ? formatDate(booking.service_date) : "N/A";
      const serviceTime = booking.service_time || "N/A";
      const status = booking.booking_status || "N/A";
      const amount = booking.total_amount
        ? `₱${Number(booking.total_amount).toFixed(2)}`
        : "N/A";

      const deceasedName = booking.deceased_name || "N/A";
      const deceasedDates = (booking.birth_date || booking.death_date)
        ? `${formatDate(booking.birth_date)} - ${formatDate(booking.death_date)}${booking.age ? ` (Age ${booking.age})` : ""}`
        : "Birth/Death dates not provided.";

      modalBody.innerHTML = `
        <div class="booking-details-view">
          <div class="mb-3">
            <strong>${booking.chapel_name || "Home Service"}</strong>
            <div class="text-muted">Status: ${status}</div>
          </div>

          <div class="mb-3">
            <h6>Service Schedule</h6>
            <div><i class="bi bi-calendar-event me-1"></i>${serviceDate}</div>
            <div><i class="bi bi-clock me-1"></i>${serviceTime}</div>
          </div>

          <div class="mb-3">
            <h6>Features</h6>
            <div>${features.length ? features.join(", ") : "No features listed."}</div>
          </div>

          <div class="mb-3">
            <h6>Deceased Information</h6>
            <div><strong>${deceasedName}</strong></div>
            <div class="text-muted">${deceasedDates}</div>
            <div><i class="bi bi-calendar2-week me-1"></i>${booking.wake_schedule || "Wake schedule not provided."}</div>
            <div><i class="bi bi-clock-history me-1"></i>${booking.viewing_hours || "Viewing hours not provided."}</div>
            <div><i class="bi bi-pin-map me-1"></i>${booking.interment || "Interment not provided."}</div>
          </div>

          <div><i class="bi bi-cash-coin me-1"></i>${amount}</div>
        </div>
      `;
    })
    .catch((error) => {
      console.error("Error loading booking details:", error);
      modalBody.innerHTML = `<p class="text-danger">An error occurred while loading details.</p>`;
    });
}

function setupBookingModalFocus() {
  const modalEl = document.getElementById("bookingModal");
  if (!modalEl) return;

  modalEl.addEventListener("hidden.bs.modal", () => {
    const last = modalEl._lastFocusElement;
    if (last && typeof last.focus === "function") {
      last.focus();
    }
    modalEl._lastFocusElement = null;
  });
}

// Helper functions
function formatDate(dateString) {
  const options = { year: "numeric", month: "long", day: "numeric" };
  return new Date(dateString).toLocaleDateString("en-US", options);
}

function getStatusIcon(status) {
  const icons = {
    Pending: "bi-clock-history",
    Approved: "bi-check-circle",
    Confirmed: "bi-check-circle",
    Completed: "bi-check-circle-fill",
    Cancelled: "bi-x-circle",
  };
  return icons[status] || "bi-circle";
}

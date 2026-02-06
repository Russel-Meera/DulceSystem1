// DULCE - Bookings Page JavaScript

document.addEventListener("DOMContentLoaded", function () {
  setupBookingsFilter();
  loadBookings("all");
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

  // In production, fetch from API
  modalBody.innerHTML = `
        <div class="booking-details-view">
            <h5 class="text-primary mb-3">Booking #DULCE-${bookingId}</h5>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <strong>Package:</strong>
                    <p>Loading...</p>
                </div>
                <div class="col-md-6">
                    <strong>Chapel:</strong>
                    <p>Loading...</p>
                </div>
            </div>
        </div>
    `;

  const modal = new bootstrap.Modal(document.getElementById("bookingModal"));
  modal.show();
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

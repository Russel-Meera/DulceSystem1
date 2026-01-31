// DULCE - Bookings Page JavaScript

document.addEventListener("DOMContentLoaded", function () {
  setupBookingsFilter();
  loadBookings();
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
      filterBookingsByStatus(filter);
    });
  });
}

// Filter bookings by status
function filterBookingsByStatus(status) {
  const bookingItems = document.querySelectorAll(".booking-item");
  let visibleCount = 0;

  bookingItems.forEach((item) => {
    const itemStatus = item.getAttribute("data-status");

    if (status === "all" || itemStatus === status) {
      item.style.display = "block";
      visibleCount++;
    } else {
      item.style.display = "none";
    }
  });

  // Show empty state if no bookings visible
  const emptyState = document.getElementById("emptyState");
  if (visibleCount === 0) {
    emptyState.style.display = "block";
  } else {
    emptyState.style.display = "none";
  }
}

// Load bookings from server
function loadBookings() {
  // In production, fetch from API
  // For now, using sample data in HTML
}

// View booking details
function viewBookingDetails(bookingId) {
  const modalBody = document.getElementById("modalBookingDetails");

  // In production, fetch from API
  // For now, showing sample details
  modalBody.innerHTML = `
        <div class="booking-details-view">
            <h5 class="text-primary mb-3">Booking #DULCE-2025-00${bookingId}</h5>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <strong>Package:</strong>
                    <p>Serenity Standard</p>
                </div>
                <div class="col-md-6">
                    <strong>Chapel:</strong>
                    <p>Harmony Hall</p>
                </div>
                <div class="col-md-6">
                    <strong>Service Date:</strong>
                    <p>February 15, 2025</p>
                </div>
                <div class="col-md-6">
                    <strong>Service Time:</strong>
                    <p>10:00 AM</p>
                </div>
                <div class="col-md-6">
                    <strong>Status:</strong>
                    <p><span class="badge bg-warning">Pending</span></p>
                </div>
                <div class="col-md-6">
                    <strong>Booking Date:</strong>
                    <p>January 30, 2025</p>
                </div>
            </div>
            
            <hr>
            
            <h6 class="mb-3">Deceased Information</h6>
            <p><strong>Name:</strong> Sample Name</p>
            <p><strong>Date of Death:</strong> February 10, 2025</p>
            
            <hr>
            
            <h6 class="mb-3">Next Steps</h6>
            <ol>
                <li>Wait for admin approval</li>
                <li>Upload required documents</li>
                <li>Complete payment</li>
            </ol>
        </div>
    `;

  const modal = new bootstrap.Modal(document.getElementById("bookingModal"));
  modal.show();
}

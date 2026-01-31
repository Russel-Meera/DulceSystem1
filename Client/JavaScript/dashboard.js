// DULCE - Dashboard JavaScript

document.addEventListener("DOMContentLoaded", function () {
  loadDashboardData();
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
        document.getElementById("totalPayments").textContent =
          "₱" + data.stats.totalPayments;
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
}

// Display recent bookings
function displayRecentBookings(bookings) {
  const container = document.getElementById("recentBookings");
  container.innerHTML = "";

  bookings.forEach((booking) => {
    const bookingCard = `
            <div class="booking-item-small mb-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <strong>${booking.package_name}</strong>
                        <p class="mb-1 text-muted">${booking.chapel_name}</p>
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
}

// Helper functions
function formatDate(dateString) {
  const options = { year: "numeric", month: "long", day: "numeric" };
  return new Date(dateString).toLocaleDateString("en-US", options);
}

function getStatusColor(status) {
  const colors = {
    Pending: "warning",
    Approved: "info",
    Completed: "success",
    Cancelled: "danger",
  };
  return colors[status] || "secondary";
}

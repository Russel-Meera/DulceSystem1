// DULCE - Dashboard JavaScript

document.addEventListener("DOMContentLoaded", function () {
  loadDashboardData();
});

// Load dashboard statistics and recent bookings
function loadDashboardData() {
  // In production, this will fetch from API
  // For now, using sample data

  // Update statistics (will be replaced with actual API calls)
  document.getElementById("totalBookings").textContent = "2";
  document.getElementById("pendingBookings").textContent = "1";
  document.getElementById("totalDocuments").textContent = "3";
  document.getElementById("totalPayments").textContent = "₱85,000";

  // Load recent bookings
  // This will be replaced with actual API call
  // For now, showing empty state
}

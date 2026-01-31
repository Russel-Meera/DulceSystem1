// DULCE - Session Management for Client Pages

// Check if user is logged in
function checkUserSession() {
  fetch("../api/check-session.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.logged_in) {
        // User is logged in
        updateNavbarForLoggedInUser(data.user);

        // Disable back button to prevent going back to login
        disableBackButton();
      } else {
        // User is not logged in - redirect to login for protected pages
        const protectedPages = [
          "client-dashboard.html",
          "bookings.html",
          "documents.html",
          "payments.html",
          "profile.html",
        ];

        const currentPage = window.location.pathname.split("/").pop();

        if (protectedPages.includes(currentPage)) {
          window.location.href = "../../SignUp&Login/Pages/login.html";
        }
      }
    })
    .catch((error) => {
      console.error("Session check error:", error);
    });
}

// Update navbar to show user name instead of login/register buttons
function updateNavbarForLoggedInUser(user) {
  const navbarNav = document.querySelector(".navbar-nav");

  if (navbarNav) {
    // Remove login and register buttons
    const loginBtn = navbarNav.querySelector(".btn-login");
    const registerBtn = navbarNav.querySelector(".btn-register");

    if (loginBtn) loginBtn.parentElement.remove();
    if (registerBtn) registerBtn.parentElement.remove();

    // Add user dropdown
    const userDropdown = document.createElement("li");
    userDropdown.className = "nav-item dropdown";
    userDropdown.innerHTML = `
            <a class="nav-link dropdown-toggle user-dropdown" href="#" id="userDropdown" role="button" 
               data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i> ${user.name}
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="client-dashboard.html">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a></li>
                <li><a class="dropdown-item" href="profile.html">
                    <i class="bi bi-person"></i> My Profile
                </a></li>
                <li><a class="dropdown-item" href="bookings.html">
                    <i class="bi bi-calendar-check"></i> My Bookings
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#" onclick="logout()">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a></li>
            </ul>
        `;

    navbarNav.appendChild(userDropdown);
  }
}

// Logout function
function logout() {
  if (confirm("Are you sure you want to logout?")) {
    fetch("../api/logout.php")
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          window.location.href = "../../SignUp&Login/Pages/login.html";
        }
      })
      .catch((error) => {
        console.error("Logout error:", error);
        // Force redirect even if error
        window.location.href = "../../SignUp&Login/Pages/login.html";
      });
  }
}

// Disable back button after login
function disableBackButton() {
  history.pushState(null, null, location.href);
  window.onpopstate = function () {
    history.go(1);
  };
}

// Check session on page load
document.addEventListener("DOMContentLoaded", checkUserSession);

// Prevent session hijacking - check every 5 minutes
setInterval(checkUserSession, 5 * 60 * 1000);

// DULCE - Session Management for Client Pages

function checkUserSession() {
  fetch("http://localhost/DULCESYSTEM1/Client/api/check-session.php", {
    credentials: "include", // required for PHP session cookies
  })
    .then((response) => {
      if (!response.ok) {
        console.error("Session: Unknown (HTTP error " + response.status + ")");
        throw new Error("HTTP " + response.status);
      }
      return response.json();
    })
    .then((data) => {
      const hasSession = data.logged_in === true;

      // 🔹 The line you asked for
      console.log("Session:", hasSession);

      if (hasSession) {
        updateNavbarForLoggedInUser(data.user);
        hideRegistrationCTAs();
        disableBackButton();
      } else {
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
    .catch((err) => {
      console.error("Session: Unknown (network/runtime error)", err.message);
    });
}

// Hide registration CTAs when user is logged in
function hideRegistrationCTAs() {
  // Hide "Get Started" button in hero section
  const heroGetStartedBtn = document.querySelector(".hero-section .btn-light");
  if (
    heroGetStartedBtn &&
    heroGetStartedBtn.textContent.includes("Get Started")
  ) {
    heroGetStartedBtn.style.display = "none";
  }

  // Hide entire Call to Action section
  const ctaSection = document.querySelector(".cta-section");
  if (ctaSection) {
    ctaSection.style.display = "none";
  }

  // Hide any "Register Now" buttons
  const registerButtons = document.querySelectorAll('a[href*="register.html"]');
  registerButtons.forEach((btn) => {
    // Don't hide navbar register button (it will be replaced by user dropdown)
    if (!btn.classList.contains("btn-register")) {
      const parentSection = btn.closest("section");
      if (parentSection && parentSection.classList.contains("cta-section")) {
        // Already handled above
      } else {
        btn.style.display = "none";
      }
    }
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

    // Check if user dropdown already exists
    if (navbarNav.querySelector("#userDropdown")) {
      return; // Already added
    }

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
                <li><a class="dropdown-item" href="#" onclick="logout(); return false;">
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
    fetch("http://localhost/DULCESYSTEM1/Client/api/logout.php")
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

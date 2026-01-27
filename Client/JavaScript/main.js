// DULCE Funeral Service Management System - Client Side JavaScript

// Smooth scrolling for anchor links
document.addEventListener("DOMContentLoaded", function () {
  // Smooth scrolling for anchor links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
        });
      }
    });
  });

  // Navbar background change on scroll
  window.addEventListener("scroll", function () {
    const navbar = document.querySelector(".navbar");
    if (window.scrollY > 50) {
      navbar.style.backgroundColor = "rgba(44, 62, 80, 0.98)";
    } else {
      navbar.style.backgroundColor = "var(--primary-color)";
    }
  });

  // Active nav link highlight
  const currentLocation = location.pathname.split("/").pop() || "Index.html";
  const navLinks = document.querySelectorAll(".navbar-nav .nav-link");

  navLinks.forEach((link) => {
    const href = link.getAttribute("href");
    const hrefFile = href.split("/").pop();

    if (hrefFile === currentLocation) {
      link.classList.add("active");
    } else {
      link.classList.remove("active");
    }
  });

  // Add animation on scroll for feature cards
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  };

  const observer = new IntersectionObserver(function (entries) {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = "1";
        entry.target.style.transform = "translateY(0)";
      }
    });
  }, observerOptions);

  // Observe all feature and service cards
  document.querySelectorAll(".feature-card, .service-card").forEach((card) => {
    card.style.opacity = "0";
    card.style.transform = "translateY(20px)";
    card.style.transition = "opacity 0.6s ease, transform 0.6s ease";
    observer.observe(card);
  });
});

// Form validation helper (can be used in other pages)
function validateForm(formId) {
  const form = document.getElementById(formId);
  if (!form) return false;

  let isValid = true;
  const inputs = form.querySelectorAll(
    "input[required], select[required], textarea[required]",
  );

  inputs.forEach((input) => {
    if (!input.value.trim()) {
      isValid = false;
      input.classList.add("is-invalid");
    } else {
      input.classList.remove("is-invalid");
    }
  });

  return isValid;
}

// Email validation helper
function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

// Phone number validation helper
function validatePhone(phone) {
  const re = /^[\d\s\-\+\(\)]+$/;
  return re.test(phone) && phone.replace(/\D/g, "").length >= 10;
}

// Show alert message
function showAlert(message, type = "info") {
  const alertDiv = document.createElement("div");
  alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
  alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

  const container = document.querySelector(".container");
  if (container) {
    container.insertBefore(alertDiv, container.firstChild);

    // Auto dismiss after 5 seconds
    setTimeout(() => {
      alertDiv.classList.remove("show");
      setTimeout(() => alertDiv.remove(), 150);
    }, 5000);
  }
}

// Loading spinner helper
function showLoading(show = true) {
  let spinner = document.getElementById("loading-spinner");

  if (show) {
    if (!spinner) {
      spinner = document.createElement("div");
      spinner.id = "loading-spinner";
      spinner.className = "position-fixed top-50 start-50 translate-middle";
      spinner.style.zIndex = "9999";
      spinner.innerHTML = `
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
            `;
      document.body.appendChild(spinner);
    }
    spinner.style.display = "block";
  } else {
    if (spinner) {
      spinner.style.display = "none";
    }
  }
}

// Format currency
function formatCurrency(amount) {
  return (
    "₱" +
    parseFloat(amount).toLocaleString("en-PH", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    })
  );
}

// Format date
function formatDate(dateString) {
  const options = { year: "numeric", month: "long", day: "numeric" };
  return new Date(dateString).toLocaleDateString("en-US", options);
}

// Confirm dialog helper
function confirmAction(message, callback) {
  if (confirm(message)) {
    callback();
  }
}

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
// DULCE - Funeral Packages Page JavaScript

// Sample package data (In production, this will come from database via admin panel)
const packagesData = {
  1: {
    name: "Serenity Basic",
    price: "₱45,000",
    type: "Basic",
    description:
      "Essential funeral services with dignified care for your loved one.",
    inclusions: [
      "Basic Casket",
      "Embalming Service",
      "2 Days Wake",
      "Chapel Usage (2 days)",
      "Funeral Car",
      "Basic Flower Arrangement",
      "Guest Book",
      "50 Memorial Cards",
    ],
    details:
      "Perfect for families seeking quality essential services. Includes all necessary arrangements for a dignified farewell.",
  },
  2: {
    name: "Serenity Standard",
    price: "₱85,000",
    type: "Standard",
    description:
      "Comprehensive funeral services with enhanced amenities and comfort.",
    inclusions: [
      "Premium Casket",
      "Complete Embalming",
      "3 Days Wake",
      "Air-conditioned Chapel (3 days)",
      "Funeral Car with Escort",
      "Premium Flower Arrangements",
      "Memorial Video Tribute",
      "100 Memorial Cards",
      "Coffee & Snacks Service",
      "Photography Service",
    ],
    details:
      "Our most popular package offering comprehensive services with added comfort and memorable tributes.",
  },
  3: {
    name: "Serenity Premium",
    price: "₱150,000",
    type: "Premium",
    description:
      "Premium services with luxury amenities and personalized care.",
    inclusions: [
      "Luxury Casket",
      "Premium Embalming & Cosmetics",
      "4 Days Wake",
      "Deluxe Chapel with Lounge (4 days)",
      "Premium Funeral Fleet",
      "Designer Flower Arrangements",
      "Professional Video & Photo Coverage",
      "200 Premium Memorial Cards",
      "Full Catering Service",
      "Live Streaming Service",
      "Memorial Website",
      "24/7 Concierge Service",
    ],
    details:
      "Exceptional service with premium amenities and personalized attention throughout the entire process.",
  },
  4: {
    name: "Serenity Deluxe",
    price: "₱250,000",
    type: "Deluxe",
    description:
      "Ultimate luxury service with exclusive amenities and bespoke arrangements.",
    inclusions: [
      "Imported Luxury Casket",
      "Advanced Embalming & Restoration",
      "5 Days Wake (Flexible)",
      "Presidential Chapel Suite (5 days)",
      "Luxury Funeral Convoy",
      "Bespoke Floral Designs",
      "Cinematic Video Production",
      "300 Custom Memorial Cards",
      "Premium Catering & Bar Service",
      "Multi-platform Live Streaming",
      "Personalized Memorial Website",
      "Dedicated Event Coordinator",
      "VIP Guest Services",
      "Custom Music & Tribute Performance",
    ],
    details:
      "The ultimate tribute with luxury amenities, bespoke arrangements, and dedicated concierge service.",
  },
};

// Filter packages
function filterPackages() {
  const priceRange = document.getElementById("priceFilter").value;
  const packageType = document.getElementById("typeFilter").value;
  const packages = document.querySelectorAll(".package-item");
  let visibleCount = 0;

  packages.forEach((package) => {
    const price = parseInt(package.getAttribute("data-price"));
    const type = package.getAttribute("data-type");
    let showPackage = true;

    // Price filter
    if (priceRange !== "all") {
      const [min, max] = priceRange.split("-").map(Number);
      if (price < min || price > max) {
        showPackage = false;
      }
    }

    // Type filter
    if (packageType !== "all" && type !== packageType) {
      showPackage = false;
    }

    // Show/hide package
    if (showPackage) {
      package.style.display = "block";
      visibleCount++;
    } else {
      package.style.display = "none";
    }
  });

  // Show/hide no results message
  const noResults = document.getElementById("no-results");
  if (visibleCount === 0) {
    noResults.style.display = "block";
  } else {
    noResults.style.display = "none";
  }
}

// Reset filters
function resetFilters() {
  document.getElementById("priceFilter").value = "all";
  document.getElementById("typeFilter").value = "all";
  filterPackages();
}

// View package details in modal
function viewPackageDetails(packageId) {
  const package = packagesData[packageId];

  if (!package) return;

  const modalBody = document.getElementById("modalPackageDetails");

  let inclusionsHTML = "";
  package.inclusions.forEach((item) => {
    inclusionsHTML += `<li><i class="bi bi-check-circle-fill text-success"></i> ${item}</li>`;
  });

  modalBody.innerHTML = `
        <div class="package-detail-view">
            <div class="text-center mb-4">
                <h3 class="text-primary">${package.name}</h3>
                <div class="display-4 text-primary fw-bold">${package.price}</div>
                <span class="badge bg-primary mt-2">${package.type} Package</span>
            </div>
            
            <div class="mb-4">
                <h5 class="text-secondary mb-3">Description</h5>
                <p class="text-muted">${package.description}</p>
                <p class="text-muted">${package.details}</p>
            </div>
            
            <div>
                <h5 class="text-secondary mb-3">Package Inclusions</h5>
                <ul class="list-unstyled package-inclusions">
                    ${inclusionsHTML}
                </ul>
            </div>
        </div>
    `;

  const modal = new bootstrap.Modal(document.getElementById("packageModal"));
  modal.show();
}

// Initialize on page load
document.addEventListener("DOMContentLoaded", function () {
  // Add enter key support for filters
  document
    .getElementById("priceFilter")
    .addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        filterPackages();
      }
    });

  document
    .getElementById("typeFilter")
    .addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        filterPackages();
      }
    });
});

// DULCE - Chapel Services Page JavaScript

// Sample chapel data (In production, this will come from database via admin panel)
const chapelsData = {
  1: {
    name: "Serenity Chapel",
    capacity: "80 people",
    capacityCategory: "medium",
    image:
      "https://images.unsplash.com/photo-1438032005730-c779502df39b?w=800&h=500&fit=crop",
    description:
      "A peaceful and comfortable air-conditioned chapel perfect for intimate memorial services. Features modern amenities and elegant interiors designed to provide comfort during difficult times.",
    features: [
      { icon: "bi-snow", name: "Air-conditioned", category: "airconditioned" },
      { icon: "bi-car-front-fill", name: "Parking Space", category: "parking" },
      {
        icon: "bi-speaker-fill",
        name: "Sound System",
        category: "audiovisual",
      },
      { icon: "bi-display", name: "LED Screen", category: "audiovisual" },
      { icon: "bi-wifi", name: "WiFi Available", category: "wifi" },
      { icon: "bi-cup-hot-fill", name: "Coffee Area", category: "amenity" },
    ],
    details:
      "Perfect for families seeking a comfortable and serene environment. The Serenity Chapel combines modern technology with traditional comfort, ensuring your memorial service is conducted with dignity and respect.",
  },
  2: {
    name: "Harmony Hall",
    capacity: "150 people",
    capacityCategory: "large",
    image:
      "https://images.unsplash.com/photo-1519167758804-f8bcda37c6a0?w=800&h=500&fit=crop",
    description:
      "Our largest and most elegant chapel featuring premium amenities and spacious interiors. Ideal for larger gatherings with state-of-the-art facilities and comfortable seating arrangements.",
    features: [
      { icon: "bi-snow", name: "Premium AC", category: "airconditioned" },
      { icon: "bi-car-front-fill", name: "Large Parking", category: "parking" },
      {
        icon: "bi-speaker-fill",
        name: "Pro Audio System",
        category: "audiovisual",
      },
      { icon: "bi-tv", name: "Multiple Screens", category: "audiovisual" },
      { icon: "bi-wifi", name: "High-Speed WiFi", category: "wifi" },
      {
        icon: "bi-reception-4",
        name: "Live Streaming",
        category: "audiovisual",
      },
      { icon: "bi-cup-straw", name: "Refreshment Area", category: "amenity" },
      { icon: "bi-door-open", name: "Private Lounge", category: "amenity" },
    ],
    details:
      "Our premium chapel designed for larger memorial services. Harmony Hall offers exceptional comfort and advanced technology, including live streaming capabilities for remote attendees.",
  },
  3: {
    name: "Grace Chapel",
    capacity: "50 people",
    capacityCategory: "small",
    image:
      "https://images.unsplash.com/photo-1519741497674-611481863552?w=800&h=500&fit=crop",
    description:
      "An intimate and cozy chapel perfect for small family gatherings. Features essential amenities in a warm and comforting environment designed for privacy and solace.",
    features: [
      { icon: "bi-snow", name: "Air-conditioned", category: "airconditioned" },
      {
        icon: "bi-speaker-fill",
        name: "Sound System",
        category: "audiovisual",
      },
      { icon: "bi-display", name: "TV Screen", category: "audiovisual" },
      { icon: "bi-cup-hot-fill", name: "Coffee Station", category: "amenity" },
    ],
    details:
      "Ideal for intimate family services. Grace Chapel provides a warm, private setting where families can gather in comfort and peace.",
  },
  4: {
    name: "Tranquility Suite",
    capacity: "250 people",
    capacityCategory: "xlarge",
    image:
      "https://images.unsplash.com/photo-1478860409698-8707f313ee8b?w=800&h=500&fit=crop",
    description:
      "Our most prestigious chapel suite offering luxurious facilities and exceptional amenities. Perfect for large memorial services with VIP accommodations and comprehensive media capabilities.",
    features: [
      { icon: "bi-snow", name: "Climate Control", category: "airconditioned" },
      { icon: "bi-car-front-fill", name: "VIP Parking", category: "parking" },
      {
        icon: "bi-speaker-fill",
        name: "Premium Sound",
        category: "audiovisual",
      },
      {
        icon: "bi-projector",
        name: "Projector System",
        category: "audiovisual",
      },
      { icon: "bi-wifi", name: "Premium WiFi", category: "wifi" },
      {
        icon: "bi-camera-video",
        name: "Video Recording",
        category: "audiovisual",
      },
      { icon: "bi-broadcast", name: "Live Streaming", category: "audiovisual" },
      { icon: "bi-door-closed", name: "Multiple Lounges", category: "amenity" },
      { icon: "bi-cup-straw", name: "Catering Area", category: "amenity" },
      {
        icon: "bi-person-badge",
        name: "Concierge Service",
        category: "amenity",
      },
    ],
    details:
      "The ultimate chapel experience with luxury amenities and comprehensive services. Tranquility Suite is designed for prestigious memorial services requiring the finest facilities.",
  },
};

// Filter chapels
function filterChapels() {
  const capacityFilter = document.getElementById("capacityFilter").value;
  const featureFilter = document.getElementById("featureFilter").value;
  const chapels = document.querySelectorAll(".chapel-item");
  let visibleCount = 0;

  chapels.forEach((chapel) => {
    const capacity = chapel.getAttribute("data-capacity");
    const features = chapel.getAttribute("data-features").split(",");
    let showChapel = true;

    // Capacity filter
    if (capacityFilter !== "all" && capacity !== capacityFilter) {
      showChapel = false;
    }

    // Feature filter
    if (featureFilter !== "all" && !features.includes(featureFilter)) {
      showChapel = false;
    }

    // Show/hide chapel
    if (showChapel) {
      chapel.style.display = "block";
      visibleCount++;
    } else {
      chapel.style.display = "none";
    }
  });

  // Show/hide no results message
  const noResults = document.getElementById("no-results-chapel");
  if (visibleCount === 0) {
    noResults.style.display = "block";
  } else {
    noResults.style.display = "none";
  }
}

// Reset filters
function resetChapelFilters() {
  document.getElementById("capacityFilter").value = "all";
  document.getElementById("featureFilter").value = "all";
  filterChapels();
}

// View chapel details in modal
function viewChapelDetails(chapelId) {
  const chapel = chapelsData[chapelId];

  if (!chapel) return;

  const modalBody = document.getElementById("modalChapelDetails");

  let featuresHTML = "";
  chapel.features.forEach((feature) => {
    featuresHTML += `
            <div class="col-md-4 col-sm-6">
                <div class="detail-feature-card">
                    <i class="bi ${feature.icon}"></i>
                    <div>${feature.name}</div>
                </div>
            </div>
        `;
  });

  modalBody.innerHTML = `
        <div class="chapel-detail-view">
            <img src="${chapel.image}" 
                 alt="${chapel.name}" 
                 class="modal-chapel-image"
                 onerror="this.src='https://via.placeholder.com/800x400/2c3e50/ffffff?text=${encodeURIComponent(chapel.name)}'">
            
            <div class="text-center mb-4">
                <h3 class="text-primary mb-2">${chapel.name}</h3>
                <div class="h5 text-secondary">
                    <i class="bi bi-people-fill"></i> Capacity: ${chapel.capacity}
                </div>
            </div>
            
            <div class="mb-4">
                <h5 class="text-secondary mb-3">Description</h5>
                <p class="text-muted">${chapel.description}</p>
                <p class="text-muted">${chapel.details}</p>
            </div>
            
            <div>
                <h5 class="text-secondary mb-3">Chapel Features & Amenities</h5>
                <div class="row g-3 chapel-detail-grid">
                    ${featuresHTML}
                </div>
            </div>
        </div>
    `;

  const modal = new bootstrap.Modal(document.getElementById("chapelModal"));
  modal.show();
}

// Initialize on page load
document.addEventListener("DOMContentLoaded", function () {
  // Add enter key support for filters
  document
    .getElementById("capacityFilter")
    .addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        filterChapels();
      }
    });

  document
    .getElementById("featureFilter")
    .addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        filterChapels();
      }
    });
});

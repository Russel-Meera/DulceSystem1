const packagesData = {
  1: {
    name: "Serenity Basic",
    price: "₱45,000",
    type: "Basic",
    image:
      "https://images.unsplash.com/photo-1455849318743-b2233052fcff?w=800&h=600&fit=crop",
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
  }
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

  // Check if image exists, if not use placeholder
  const imageUrl =
    package.image ||
    "https://via.placeholder.com/800x600/2c3e50/ffffff?text=Package+Image";

  modalBody.innerHTML = `
        <div class="package-detail-view">
            <div class="row g-4">
                <!-- Left Side - Package Image -->
                <div class="col-lg-5">
                    <div class="package-modal-image-wrapper">
                        <img src="${imageUrl}" 
                             alt="${package.name}" 
                             class="package-modal-image img-fluid"
                             onerror="this.src='https://via.placeholder.com/800x600/2c3e50/ffffff?text=No+Image'">
                        <div class="package-modal-badge">
                            <span class="badge bg-primary">${package.type} Package</span>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Package Details -->
                <div class="col-lg-7">
                    <div class="package-modal-info">
                        <h3 class="package-modal-title">${package.name}</h3>
                        <div class="package-modal-price">${package.price}</div>
                        
                        <div class="package-modal-description">
                            <h5 class="text-secondary mb-3">
                                <i class="bi bi-info-circle"></i> Description
                            </h5>
                            <p class="text-muted">${package.description}</p>
                            <p class="text-muted">${package.details}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Package Inclusions - Full Width -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="package-modal-inclusions">
                        <h5 class="text-secondary mb-3">
                            <i class="bi bi-check-square"></i> Package Inclusions
                        </h5>
                        <ul class="list-unstyled package-inclusions-list">
                            ${inclusionsHTML}
                        </ul>
                    </div>
                </div>
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

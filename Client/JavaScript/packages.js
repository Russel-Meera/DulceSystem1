let packagesData = {}; // Will hold packages fetched from DB

// Fetch packages from server
async function loadPackages() {
  try {
    const res = await fetch('../PHP/fetchPackages.php');
    const data = await res.json();

    if (data.status === 'success') {
      packagesData = data.packages;
      renderPackages(); // Render cards dynamically
    } else {
      console.error('Error fetching packages:', data.message);
    }
  } catch (err) {
    console.error('Fetch error:', err);
  }
}

// Render package cards dynamically
function renderPackages() {
  const container = document.getElementById('packages-container');
  container.innerHTML = ''; // clear existing content

  for (const id in packagesData) {
    const pkg = packagesData[id];
    const price = pkg.price ? `₱${Number(pkg.price).toLocaleString()}` : 'Personalized';
    const typeLower = pkg.type.toLowerCase();

    const detailsArray = Array.isArray(pkg.details) ? pkg.details : [pkg.details || ''];
    const cardImage =
      pkg.image || "https://via.placeholder.com/800x600/2c3e50/ffffff?text=No+Image";

    container.innerHTML += `
      <div class="col-md-6 col-lg-4 package-item" data-price="${pkg.price}" data-type="${typeLower}">
        <div class="package-card ${typeLower}">
          <div class="package-badge">${pkg.type}</div>
          <div
            class="package-image-wrapper"
            style="--package-bg-image: url('${cardImage}')"
            aria-label="${pkg.name}"
          ></div>
          <div class="package-header">
            <h3 class="package-name">${pkg.name}</h3>
            <div class="package-price">${price}</div>
          </div>
          <div class="package-body">
            <p class="package-description">${pkg.description}</p>
            <ul class="package-inclusions">
              ${detailsArray.map(item => `<li><i class="bi bi-check-circle-fill"></i> ${item}</li>`).join('')}
            </ul>
          </div>
          <div class="package-footer">
            <a href="login.html" class="btn btn-select-package"><i class="bi bi-calendar-check"></i> Select Package</a>
            <button class="btn btn-view-details" onclick="viewPackageDetails(${id})"><i class="bi bi-info-circle"></i> View Details</button>
          </div>
        </div>
      </div>
    `;
  }
}


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
  const pkg = packagesData[packageId];

  if (!pkg) return;

  const modalBody = document.getElementById("modalPackageDetails");

  let inclusionsHTML = "";
  pkg.details.forEach((item) => {
    inclusionsHTML += `<li><i class="bi bi-check-circle-fill text-success"></i> ${item}</li>`;
  });

  // Check if image exists, if not use placeholder
  const imageUrl = pkg.image || "https://via.placeholder.com/800x600/2c3e50/ffffff?text=No+Image";

  modalBody.innerHTML = `
    <div class="package-detail-view">
      <div class="row g-4">
        <div class="col-12">
          <div class="package-modal-image-wrapper package-modal-image-wide">
            <img src="${imageUrl}" 
                 alt="${pkg.name}" 
                 class="package-modal-image img-fluid"
                 onerror="this.src='https://via.placeholder.com/1200x600/2c3e50/ffffff?text=No+Image'">
            <div class="package-modal-badge">
              <span class="badge bg-primary">${pkg.type} Package</span>
            </div>
          </div>
        </div>

        <div class="col-12">
          <div class="package-modal-info">
            <h3 class="package-modal-title">${pkg.name}</h3>
            <div class="package-modal-price">${pkg.price}</div>
            <div class="package-modal-description">
              <h5 class="text-secondary mb-3">
                <i class="bi bi-info-circle"></i> Description
              </h5>
              <p class="text-muted">${pkg.description}</p>
              <p class="text-muted">${pkg.details.join(", ")}</p>
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
  loadPackages();

  const modalEl = document.getElementById("packageModal");
  if (modalEl) {
    modalEl.addEventListener("hidden.bs.modal", function () {
      const active = document.activeElement;
      if (active && modalEl.contains(active)) {
        active.blur();
      }
    });
  }

  // Add enter key support for filters
  document.getElementById("priceFilter").addEventListener("keypress", function (e) {
    if (e.key === "Enter") filterPackages();
  });

  document.getElementById("typeFilter").addEventListener("keypress", function (e) {
    if (e.key === "Enter") filterPackages();
  });
});

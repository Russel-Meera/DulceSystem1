<?php
$currentPage = 'Users'; // Set the current page
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../CSS/adminPage.css">
  <link rel="stylesheet" href="../CSS/funeralService.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>

<body>
  <div class="container">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
      <!-- Sidebar Header -->
      <div class="sidebar-header">
        <h1 class="sidebar-title">Admin Panel</h1>
        <button class="toggle-btn" onclick="toggleSidebar()">
          <svg id="menuIcon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <!-- Menu Items -->
      <div class="menu">
        <button class="menu-item <?php echo $currentPage == 'Dashboard' ? 'active' : ''; ?>"
          onclick="location.href='adminInitialPage.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
            </path>
          </svg>
          <span class="menu-label">Dashboard</span>
        </button>

        <button class="menu-item <?php echo $currentPage == 'Users' ? 'active' : ''; ?>" onclick="location.href='#'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
            </path>
          </svg>
          <span class="menu-label">Funeral Services</span>
        </button>

        <button class="menu-item <?php echo $currentPage == 'Products' ? 'active' : ''; ?>"
          onclick="location.href='chapelServices.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
          </svg>
          <span class="menu-label">Chapel Services</span>
        </button>

        <button class="menu-item <?php echo $currentPage == 'Orders' ? 'active' : ''; ?>"
          onclick="location.href='orders.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
            </path>
          </svg>
          <span class="menu-label">Orders</span>
        </button>

        <button class="menu-item <?php echo $currentPage == 'Reports' ? 'active' : ''; ?>"
          onclick="location.href='reports.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
            </path>
          </svg>
          <span class="menu-label">Reports</span>
        </button>

        <button class="menu-item <?php echo $currentPage == 'Documents' ? 'active' : ''; ?>"
          onclick="location.href='documents.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
            </path>
          </svg>
          <span class="menu-label">Documents</span>
        </button>

        <button class="menu-item <?php echo $currentPage == 'Settings' ? 'active' : ''; ?>"
          onclick="location.href='settings.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
            </path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
            </path>
          </svg>
          <span class="menu-label">Settings</span>
        </button>
      </div>

      <!-- Sidebar Footer -->
      <div class="sidebar-footer">
        <button class="logout-btn" onclick="logout()">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
          </svg>
          <span class="menu-label">Logout</span>
        </button>
      </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Header -->
      <header class="header">
        <div class="header-content">
          <div class="header-title">
            <h2 id="pageTitle">Funeral Services</h2>
            <p>Manage your Funeral Services Contents</p>
          </div>
          <div class="user-info">
            <div class="user-details">
              <p class="user-name">John Doe</p>
              <p class="user-role">Administrator</p>
            </div>
            <div class="user-avatar">JD</div>
          </div>
        </div>
      </header>

      <!-- Content Area -->
      <main class="content-area">
        <div class="content-container">
          <!-- Package Controls -->
          <div class="package-controls">
            <button class="add-package-btn" id="openPackageModal">+ Add Package</button>
            <input type="text" class="search-box" placeholder="Search packages..." id="searchInput" />
            <button class="add-package-btn" id="reloadTable" style="background:#555;">⟳ Reload Table</button>
          </div>

          <!-- Packages Table -->
          <table id="packagesTable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Description</th>
                <th>Details</th>
                <th>Price</th>
                <th>Image</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>

          <!-- Add/Edit Package Modal -->
          <div class="unique-package-modal" id="packageModal">
            <div class="modal-content">
              <span class="close-modal">&times;</span>
              <h2>Add Package</h2>
              <form id="addPackageForm">
                <input type="text" name="name" placeholder="Package Name" required />

                <select name="type" required>
                  <option value="">Select Package Type</option>
                  <option value="Basic">Basic</option>
                  <option value="Standard">Standard</option>
                  <option value="Premium">Premium</option>
                </select>

                <textarea name="description" placeholder="Description" required></textarea>

                <!-- Dynamic Additional Details -->
                <div id="detailsContainer">
                  <label>Package Inclusions / Additional Details:</label>

                  <div class="detail-item">
                    <input type="text" name="details[]" placeholder="Enter item" />
                    <button type="button" class="removeDetail" title="Remove item">−</button>
                  </div>

                  <button type="button" id="addDetail" class="add-detail-btn">+ Add Item</button>
                </div>

                <input type="number" name="price" placeholder="Price" required />
                <input type="file" name="image" accept="image/*" />
                <button type="submit" class="submit-btn">Add Package</button>
              </form>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script>
    // Toggle sidebar
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const menuIcon = document.getElementById('menuIcon');

      sidebar.classList.toggle('collapsed');

      // Change icon
      if (sidebar.classList.contains('collapsed')) {
        menuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>';
      } else {
        menuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
      }
    }

    // Change active menu
    function changeMenu(element, pageName) {
      // Remove active class from all menu items
      const menuItems = document.querySelectorAll('.menu-item');
      menuItems.forEach(item => item.classList.remove('active'));

      // Add active class to clicked item
      element.classList.add('active');

      // Update page title
      document.getElementById('pageTitle').textContent = pageName;
      document.getElementById('emptyTitle').textContent = pageName + ' Page';
      document.getElementById('emptyDescription').textContent = `This is where your ${pageName.toLowerCase()} content will appear.`;

      // Update icon in empty state (copy from menu item)
      const iconSvg = element.querySelector('svg').cloneNode(true);
      iconSvg.setAttribute('width', '48');
      iconSvg.setAttribute('height', '48');
      document.getElementById('pageIcon').replaceWith(iconSvg);
      iconSvg.id = 'pageIcon';
    }

    // Logout function
    function logout() {
      if (confirm('Are you sure you want to logout?')) {
        alert('Logged out successfully!');
        // Add your logout logic here
      }
    }

    function initPackagePage() {
      const packageModal = document.getElementById("packageModal");
      const addForm = document.getElementById("addPackageForm");
      const reloadBtn = document.getElementById("reloadTable");
      const searchInput = document.getElementById("searchInput");
      const tableBody = document.querySelector("#packagesTable tbody");

      // Open modal
      document.getElementById("openPackageModal").addEventListener("click", () => {
        packageModal.style.display = "block";
      });

      // Close modal
      packageModal.querySelector(".close-modal").addEventListener("click", () => {
        packageModal.style.display = "none";
        addForm.reset();
        resetDetailsInputs();
      });

      window.addEventListener("click", e => {
        if (e.target === packageModal) {
          packageModal.style.display = "none";
          addForm.reset();
          resetDetailsInputs();
        }
      });

      // Dynamic Additional Details
      const detailsContainer = document.getElementById("detailsContainer");
      document.getElementById("addDetail").addEventListener("click", () => {
        const div = document.createElement("div");
        div.classList.add("detail-item");
        div.innerHTML = `
      <input type="text" name="details[]" placeholder="Enter item" />
      <button type="button" class="removeDetail" title="Remove item">−</button>
    `;
        detailsContainer.insertBefore(div, document.getElementById("addDetail"));
      });

      detailsContainer.addEventListener("click", e => {
        if (e.target.classList.contains("removeDetail")) {
          e.target.parentElement.remove();
        }
      });

      function resetDetailsInputs() {
        // Keep one empty input
        detailsContainer.querySelectorAll(".detail-item").forEach((div, index) => {
          if (index === 0) div.querySelector("input").value = "";
          else div.remove();
        });
      }

      // Load packages
      function loadPackages() {
        fetch("../PHP/fetchPackages.php")
          .then(res => res.json())
          .then(data => {
            tableBody.innerHTML = "";
            if (data.length === 0) {
              tableBody.innerHTML = `<tr><td colspan="8" style="text-align:center; color:#888;">No packages found</td></tr>`;
              return;
            }

            data.forEach(pkg => {
              const imageHTML = pkg.image ? `<img src="../uploads/packages/${pkg.image}" alt="${pkg.name}" />` : '';

              const actionHTML = `
            <button class="icon-btn edit-btn" title="Edit" onclick="editPackage(${pkg.id})">✏️</button>
            <button class="icon-btn delete-btn" title="Delete" onclick="deletePackage(this, ${pkg.id})">🗑️</button>
          `;

              // Convert details JSON array into a list
              let detailsList = '-';
              try {
                const detailsArr = Array.isArray(pkg.details) ? pkg.details : JSON.parse(pkg.details);
                if (detailsArr.length) {
                  detailsList = '<ul>' + detailsArr.map(d => `<li>${d}</li>`).join('') + '</ul>';
                }
              } catch {
                if (pkg.details) detailsList = pkg.details;
              }

              const row = tableBody.insertRow();
              row.innerHTML = `
            <td>${pkg.id}</td>
            <td>${pkg.name}</td>
            <td>${pkg.type}</td>
            <td class="details" title="${pkg.description}">${pkg.description}</td>
            <td class="details">${detailsList}</td>
            <td>₱${Number(pkg.price).toLocaleString()}</td>
            <td>${imageHTML}</td>
            <td>${actionHTML}</td>
          `;
            });
          })
          .catch(err => console.error("Error loading packages:", err));
      }

      loadPackages();
      reloadBtn.addEventListener("click", loadPackages);

      // Search filter
      searchInput.addEventListener("input", () => {
        const filter = searchInput.value.toLowerCase();
        tableBody.querySelectorAll("tr").forEach(row => {
          row.style.display = row.textContent.toLowerCase().includes(filter) ? "" : "none";
        });
      });

      // Add package
      addForm.addEventListener("submit", e => {
        e.preventDefault();
        const formData = new FormData(addForm);
        fetch("../PHP/addPackage.php", { method: "POST", body: formData })
          .then(res => res.json())
          .then(data => {
            if (data.status === "success") {
              loadPackages();
              packageModal.style.display = "none";
              addForm.reset();
              resetDetailsInputs();
            } else alert("Error: " + data.message);
          });
      });
    }

    window.addEventListener("DOMContentLoaded", initPackagePage);

    // Dummy edit/delete functions
    function editPackage(id) { alert("Edit package ID: " + id); }
    function deletePackage(btn, id) {
      if (!confirm("Are you sure you want to delete this package?")) return;
      fetch(`../PHP/deletePackage.php?id=${id}`)
        .then(res => res.json())
        .then(data => {
          if (data.status === "success") btn.closest("tr").remove();
          else alert("Error: " + data.message);
        });
    }

  </script>
</body>

</html>
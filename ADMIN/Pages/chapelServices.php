<?php
$currentPage = 'Products'; // Set the current page
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Chapel Services</title>
  <link rel="stylesheet" href="../CSS/adminPage.css">
  <link rel="stylesheet" href="../CSS/funeralService.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css">
  <script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        <button class="menu-item <?php echo $currentPage == 'Users' ? 'active' : ''; ?>"
          onclick="location.href='funeralPackage.php'">
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

        <button class="menu-item <?php echo $currentPage == 'Announcements' ? 'active' : ''; ?>"
          onclick="location.href='announcementCrud.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M11 5h2m-1-1v2m7 4V9a5 5 0 00-10 0v1m-2 0a2 2 0 00-2 2v1a2 2 0 002 2h12a2 2 0 002-2v-1a2 2 0 00-2-2H7z">
            </path>
          </svg>
          <span class="menu-label">Announcements</span>
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
            <h2 id="pageTitle">Chapel Services</h2>
            <p>Manage your Chapel Services Contents</p>
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
          <!-- Controls -->
                    <div class="package-controls">
            <button class="add-package-btn" id="openChapelModal">+ Add Home Service Package</button>
            <input type="text" class="search-box" placeholder="Search chapels..." id="searchInput" />
            
            <button class="add-package-btn" id="reloadTable" style="background:#555;">⟳ Reload Table</button>
          </div>

          <section class="admin-announcements">
            <div class="admin-announcements-header">
              <h3>Chapel Services</h3>
              <p>Manage chapel listings in a clean grid view.</p>
            </div>
            <div class="admin-chapels-grid" id="chapelGrid"></div>
            <div id="no-results-chapels" class="text-center mt-4" style="display: none">
              <i class="bi bi-search" style="font-size: 3rem; color: #7f8c8d"></i>
              <h3 class="mt-3">No Chapels Found</h3>
              <p class="text-muted">Try adjusting your search to see more results.</p>
            </div>
          </section>

          <!-- Add/Edit Chapel Modal -->
          <div class="unique-package-modal" id="chapelModal">
            <div class="modal-content">
              <span class="close-modal">&times;</span>
              <h2 id="chapelModalTitle">Add Package</h2>
              <form id="addChapelForm">
                <input type="hidden" name="id" id="chapelId" />
                <input type="text" name="name" placeholder="Chapel Name" required />

                <input type="hidden" name="description" id="chapelDescriptionInput" />
                <div class="quill-toolbar" id="chapelDescriptionToolbar"></div>
                <div id="chapelDescriptionEditor" class="quill-editor"></div>

                <input type="number" name="capacity" placeholder="Capacity" required />
                <select name="capacity_type" required>
                  <option value="">Select Capacity Type</option>
                  <option value="small">Small</option>
                  <option value="medium">Medium</option>
                  <option value="large">Large</option>
                  <option value="xlarge">X-Large</option>
                </select>

                <!-- Dynamic Features -->
                <div id="featuresContainer">
                  <label>Features / Additional Details:</label>

                  <div class="detail-item">
                    <input type="text" name="features[]" placeholder="Enter feature" />
                    <button type="button" class="removeDetail" title="Remove item">−</button>
                  </div>

                  <button type="button" id="addFeature" class="add-detail-btn">+ Add Feature</button>
                </div>

                <input type="text" name="badge" placeholder="Badge / Special Label" />
                <input type="file" name="image" accept="image/*" />
                <button type="submit" class="submit-btn" id="chapelSubmitBtn">Add Chapel</button>
              </form>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script>
    let chapelDescriptionEditor;
    // Toggle sidebar
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const menuIcon = document.getElementById('menuIcon');
      sidebar.classList.toggle('collapsed');
      menuIcon.innerHTML = sidebar.classList.contains('collapsed') ?
        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>' :
        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
    }
    // Logout
    function logout() {
      if (confirm('Are you sure you want to logout?')) {
        alert('Logged out successfully!');
      }
    }
    // Init page
    function initChapelPage() {
      const chapelModal = document.getElementById("chapelModal");
      const form = document.getElementById("addChapelForm");
      const reloadBtn = document.getElementById("reloadTable");
      const searchInput = document.getElementById("searchInput");
      const chapelGrid = document.getElementById("chapelGrid");
      const noResults = document.getElementById("no-results-chapels");
      const descriptionInput = document.getElementById("chapelDescriptionInput");
      const modalTitle = document.getElementById("chapelModalTitle");
      const submitBtn = document.getElementById("chapelSubmitBtn");
      const idInput = document.getElementById("chapelId");
      chapelDescriptionEditor = new Quill("#chapelDescriptionEditor", {
        theme: "snow",
        placeholder: "Write chapel description...",
        modules: {
          toolbar: [
            [{ header: [1, 2, 3, false] }],
            ["bold", "italic", "underline"],
            [{ list: "ordered" }, { list: "bullet" }],
            ["link", "clean"]
          ]
        }
      });
      // Open modal
      document.getElementById("openChapelModal").addEventListener("click", () => {
        if (idInput) idInput.value = "";
        if (modalTitle) modalTitle.textContent = "Add Package";
        if (submitBtn) submitBtn.textContent = "Add Chapel";
        if (chapelDescriptionEditor) chapelDescriptionEditor.setText("");
        chapelModal.style.display = "block";
      });
      // Close modal
      chapelModal.querySelector(".close-modal").addEventListener("click", () => {
        chapelModal.style.display = "none";
        form.reset();
        resetFeatureInputs();
        if (chapelDescriptionEditor) chapelDescriptionEditor.setText("");
      });

      window.addEventListener("click", e => {
        if (e.target === chapelModal) {
          chapelModal.style.display = "none";
          form.reset();
          resetFeatureInputs();
          if (chapelDescriptionEditor) chapelDescriptionEditor.setText("");
        }
      });

       // ======================
      // Dynamic Features Logic
        // ======================
      const featuresContainer = document.getElementById("featuresContainer");
      document.getElementById("addFeature").addEventListener("click", () => {
        const div = document.createElement("div");
        div.classList.add("detail-item"); // same class as funeral for consistent CSS
        div.innerHTML = `
      <input type="text" name="features[]" placeholder="Enter feature" />
      <button type="button" class="removeDetail" title="Remove item">−</button>
    `;
        featuresContainer.insertBefore(div, document.getElementById("addFeature"));
      });
      // Remove dynamically added feature
      featuresContainer.addEventListener("click", e => {
        if (e.target.classList.contains("removeDetail")) {
          e.target.parentElement.remove();
        }
      });

      function resetFeatureInputs() {
        // Keep one empty input
        featuresContainer.querySelectorAll(".detail-item").forEach((div, index) => {
          if (index === 0) div.querySelector("input").value = "";
          else div.remove();
        });
      }

      // ======================
      // Load Chapels
      // ======================
      function loadChapels() {
        fetch("../PHP/fetchChapels.php")
          .then(res => res.json())
          .then(data => {
            chapelGrid.innerHTML = "";
            if (!data.length) {
              noResults.style.display = "block";
              return;
            }

            noResults.style.display = "none";

            data.forEach(chapel => {
              let featuresList = "";
              try {
                const arr = Array.isArray(chapel.features) ? chapel.features : JSON.parse(chapel.features);
                if (arr.length) {
                  featuresList = arr.map(f => `<span class="admin-chip">${f}</span>`).join("");
                }
              } catch {
                if (chapel.features) featuresList = `<span class="admin-chip">${chapel.features}</span>`;
              }

              const imageHtml = chapel.image
                ? `<div class="admin-chapel-image" style="background-image:url('../uploads/packages/${chapel.image}')"></div>`
                : `<div class="admin-chapel-image placeholder"></div>`;

              const badgeHtml = chapel.badge ? `<span class="admin-announce-badge service">${chapel.badge}</span>` : "";

              const card = document.createElement("div");
              card.className = "admin-chapel-card";
              card.innerHTML = `
                ${imageHtml}
                <div class="admin-chapel-body">
                  <div class="admin-announce-top">
                    <span class="admin-announce-badge general">${chapel.capacity_type}</span>
                    ${badgeHtml}
                  </div>
                  <h4 class="admin-announce-title">${chapel.name}</h4>
                  <div class="admin-announce-meta">
                    <span><i class="bi bi-people"></i> ${chapel.capacity}</span>
                  </div>
                  <p class="admin-announce-content">${chapel.description}</p>
                  <div class="admin-chip-row">${featuresList}</div>
                  <div class="admin-announce-actions">
                    <button class="icon-btn edit-btn" title="Edit" onclick="editChapel(${chapel.id})"><i class="bi bi-pencil"></i></button>
                    <button class="icon-btn delete-btn" title="Delete" onclick="deleteChapel(${chapel.id})"><i class="bi bi-trash"></i></button>
                  </div>
                </div>
              `;
              chapelGrid.appendChild(card);
            });
          })
          .catch(err => console.error("Error loading chapels:", err));
      }


      loadChapels();
      reloadBtn.addEventListener("click", loadChapels);
      // Search filter
      searchInput.addEventListener("input", () => {
        const filter = searchInput.value.toLowerCase();
        const items = document.querySelectorAll(".admin-chapel-card");
        let visibleCount = 0;
        items.forEach(item => {
          const matches = item.textContent.toLowerCase().includes(filter);
          item.style.display = matches ? "" : "none";
          if (matches) visibleCount++;
        });
        noResults.style.display = visibleCount === 0 ? "block" : "none";
      });
      // Submit form
      form.addEventListener("submit", e => {
        e.preventDefault();
        if (chapelDescriptionEditor && descriptionInput) {
          descriptionInput.value = chapelDescriptionEditor.root.innerHTML.trim();
        }
        const formData = new FormData(form);
        const isEdit = Boolean(idInput && idInput.value);
        const endpoint = isEdit ? "../PHP/updateChapel.php" : "../PHP/addChapel.php";
        fetch(endpoint, { method: "POST", body: formData })
          .then(res => res.json())
          .then(data => {
            if (data.status === "success") {
              loadChapels();
              chapelModal.style.display = "none";
              form.reset();
              resetFeatureInputs();
              if (chapelDescriptionEditor) chapelDescriptionEditor.setText("");
              Swal.fire({
                icon: "success",
                title: isEdit ? "Chapel Updated" : "Chapel Added",
                timer: 1200,
                showConfirmButton: false
              });
            } else alert("Error: " + data.message);
          });
      });
    }
    // Initialize
    window.addEventListener("DOMContentLoaded", initChapelPage);

    // Edit / delete
    function editChapel(id) {
      fetch("../PHP/fetchChapels.php")
        .then(res => res.json())
        .then(data => {
          const chapel = data.find(item => Number(item.id) === Number(id));
          if (!chapel) return;

          const form = document.getElementById("addChapelForm");
          const idInput = document.getElementById("chapelId");
          const modalTitle = document.getElementById("chapelModalTitle");
          const submitBtn = document.getElementById("chapelSubmitBtn");

          if (idInput) idInput.value = chapel.id;
          if (form.name) form.name.value = chapel.name || "";
          if (form.capacity) form.capacity.value = chapel.capacity || "";
          if (form.capacity_type) form.capacity_type.value = chapel.capacity_type || "";
          if (form.badge) form.badge.value = chapel.badge || "";

          if (chapelDescriptionEditor) {
            chapelDescriptionEditor.root.innerHTML = chapel.description || "";
          }

          const featuresContainer = document.getElementById("featuresContainer");
          if (featuresContainer) {
            const items = Array.from(featuresContainer.querySelectorAll(".detail-item"));
            items.forEach((item, index) => {
              if (index === 0) {
                const input = item.querySelector("input");
                if (input) input.value = "";
              } else {
                item.remove();
              }
            });

            let features = [];
            try {
              features = Array.isArray(chapel.features) ? chapel.features : JSON.parse(chapel.features || "[]");
            } catch {
              features = chapel.features ? [chapel.features] : [];
            }

            if (features.length) {
              const firstInput = featuresContainer.querySelector(".detail-item input");
              if (firstInput) firstInput.value = features[0];
              for (let i = 1; i < features.length; i++) {
                const div = document.createElement("div");
                div.classList.add("detail-item");
                div.innerHTML = `
      <input type="text" name="features[]" placeholder="Enter feature" value="${features[i]}" />
      <button type="button" class="removeDetail" title="Remove item">−</button>
    `;
                featuresContainer.insertBefore(div, document.getElementById("addFeature"));
              }
            }
          }

          if (modalTitle) modalTitle.textContent = "Edit Chapel";
          if (submitBtn) submitBtn.textContent = "Save Changes";
          document.getElementById("chapelModal").style.display = "block";
        });
    }

    function deleteChapel(id) {
      Swal.fire({
        title: "Delete this chapel?",
        text: "This action cannot be undone.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        confirmButtonText: "Delete",
        cancelButtonText: "Cancel"
      }).then(result => {
        if (!result.isConfirmed) return;
        fetch(`../PHP/deleteChapel.php?id=${id}`)
          .then(res => res.json())
          .then(data => {
            if (data.status === "success") {
              Swal.fire({
                icon: "success",
                title: "Deleted",
                timer: 1000,
                showConfirmButton: false
              });
              window.location.reload();
            } else {
              Swal.fire("Error", data.message || "Delete failed.", "error");
            }
          });
      });
    }


  </script>
</body>

</html>










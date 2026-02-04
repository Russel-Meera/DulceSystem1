<?php
$currentPage = 'Announcements';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Announcements</title>
  <link rel="stylesheet" href="../CSS/adminPage.css">
  <link rel="stylesheet" href="../CSS/funeralService.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
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
            <h2 id="pageTitle">Announcements</h2>
            <p>Manage your Announcements Content</p>
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
          <div class="package-controls">
            <button class="add-package-btn" id="openAnnouncementModal">+ Add Announcement</button>
            <input type="text" class="search-box" placeholder="Search announcements..." id="searchInput" />
            <select class="search-box" id="categoryFilter" style="max-width: 180px;">
              <option value="all">All Categories</option>
              <option value="general">General</option>
              <option value="service">Service Update</option>
              <option value="holiday">Holiday Notice</option>
              <option value="important">Important</option>
            </select>
            <select class="search-box" id="pinnedFilter" style="max-width: 140px;">
              <option value="all">All</option>
              <option value="pinned">Pinned</option>
              <option value="regular">Regular</option>
            </select>
            <button class="add-package-btn" id="reloadTable" style="background:#555;">⟳ Reload Table</button>
          </div>

          <section class="admin-announcements">

            <div class="admin-announcements-grid" id="announcementGrid"></div>

            <div id="no-results-announcements" class="text-center mt-4" style="display: none">
              <i class="bi bi-search" style="font-size: 3rem; color: #7f8c8d"></i>
              <h3 class="mt-3">No Announcements Found</h3>
              <p class="text-muted">Try adjusting your search to see more results.</p>
            </div>
          </section>

          <div class="unique-package-modal" id="announcementModal">
            <div class="modal-content">
              <span class="close-modal">&times;</span>
              <h2>Add Announcement</h2>
              <form id="addAnnouncementForm">
                <input type="hidden" name="id" id="announcementId" />
                <input type="text" name="title" placeholder="Title" required />
                <select name="category" required>
                  <option value="">Select Category</option>
                  <option value="general">General</option>
                  <option value="service">Service Update</option>
                  <option value="holiday">Holiday Notice</option>
                  <option value="important">Important</option>
                </select>
                <input type="date" name="announcement_date" required />
                <label class="checkbox-row">
                  <input type="checkbox" name="is_pinned" value="1" />
                  <span>Pin Announcement</span>
                </label>
                <input type="hidden" name="content" id="announcementContentInput" />
                <div class="quill-toolbar" id="announcementEditorToolbar"></div>
                <div id="announcementEditor" class="quill-editor"></div>
                <button type="submit" class="submit-btn">Add Announcement</button>
              </form>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script>
    let quill;
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const menuIcon = document.getElementById('menuIcon');
      sidebar.classList.toggle('collapsed');
      menuIcon.innerHTML = sidebar.classList.contains('collapsed') ?
        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>' :
        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
    }

    function logout() {
      if (confirm('Are you sure you want to logout?')) {
        alert('Logged out successfully!');
      }
    }

    function initAnnouncementPage() {
      const announcementModal = document.getElementById("announcementModal");
      const form = document.getElementById("addAnnouncementForm");
      const reloadBtn = document.getElementById("reloadTable");
      const searchInput = document.getElementById("searchInput");
      const categoryFilter = document.getElementById("categoryFilter");
      const pinnedFilter = document.getElementById("pinnedFilter");
      const announcementGrid = document.getElementById("announcementGrid");
      const noResults = document.getElementById("no-results-announcements");
      const modalTitle = announcementModal.querySelector("h2");
      const submitBtn = form.querySelector(".submit-btn");
      const idInput = document.getElementById("announcementId");
      const contentInput = document.getElementById("announcementContentInput");
      quill = new Quill("#announcementEditor", {
        theme: "snow",
        placeholder: "Write announcement content...",
        modules: {
          toolbar: [
            [{ header: [1, 2, 3, false] }],
            ["bold", "italic", "underline"],
            [{ list: "ordered" }, { list: "bullet" }],
            ["link", "clean"]
          ]
        }
      });

      document.getElementById("openAnnouncementModal").addEventListener("click", () => {
        form.reset();
        idInput.value = "";
        modalTitle.textContent = "Add Announcement";
        submitBtn.textContent = "Add Announcement";
        quill.setText("");
        announcementModal.style.display = "block";
      });

      announcementModal.querySelector(".close-modal").addEventListener("click", () => {
        announcementModal.style.display = "none";
        form.reset();
        idInput.value = "";
        quill.setText("");
      });

      window.addEventListener("click", e => {
        if (e.target === announcementModal) {
          announcementModal.style.display = "none";
          form.reset();
          idInput.value = "";
          quill.setText("");
        }
      });

      function loadAnnouncements() {
        fetch("../PHP/fetchAnnouncements.php")
          .then(res => res.json())
          .then(data => {
            if (announcementGrid) {
              announcementGrid.innerHTML = "";
            }

            if (!data.length) {
              noResults.style.display = "block";
              return;
            }

            noResults.style.display = "none";

            data.forEach(item => {
              const card = document.createElement("div");
              card.className = "admin-announcement-card announcement-item";
              card.setAttribute("data-category", item.category);
              card.setAttribute("data-date", item.announcement_date);
              card.setAttribute("data-pinned", item.is_pinned == 1 ? "pinned" : "regular");

              const categoryLabel = {
                general: "General",
                service: "Service Update",
                holiday: "Holiday Notice",
                important: "Important"
              }[item.category] || "General";

              const pinnedBadge = item.is_pinned == 1
                ? `<span class="admin-announce-badge pinned"><i class="bi bi-pin-angle-fill"></i> Pinned</span>`
                : "";

              card.innerHTML = `
                <div class="admin-announce-top">
                  <span class="admin-announce-badge ${item.category}">${categoryLabel}</span>
                  ${pinnedBadge}
                </div>
                <h4 class="admin-announce-title">${item.title}</h4>
                <div class="admin-announce-meta">
                  <span><i class="bi bi-calendar-event"></i> ${item.announcement_date}</span>
                  <span><i class="bi bi-eye"></i> ${item.views} views</span>
                </div>
                <p class="admin-announce-content">${item.content}</p>
                <div class="admin-announce-actions">
                  <button class="icon-btn edit-btn" title="Edit" onclick="editAnnouncement(${item.id})">✏️</button>
                  <button class="icon-btn delete-btn" title="Delete" onclick="deleteAnnouncement(${item.id})">🗑️</button>
                </div>
              `;

              if (announcementGrid) {
                announcementGrid.appendChild(card);
              }
            });
          })
          .catch(err => console.error("Error loading announcements:", err));
      }

      loadAnnouncements();
      reloadBtn.addEventListener("click", loadAnnouncements);

      function applyFilters() {
        const filter = searchInput.value.toLowerCase();
        const categoryValue = categoryFilter.value;
        const pinnedValue = pinnedFilter.value;
        const items = document.querySelectorAll(".admin-announcement-card");
        let visibleCount = 0;

        items.forEach(item => {
          const matchesText = item.textContent.toLowerCase().includes(filter);
          const matchesCategory =
            categoryValue === "all" || item.getAttribute("data-category") === categoryValue;
          const matchesPinned =
            pinnedValue === "all" || item.getAttribute("data-pinned") === pinnedValue;

          const show = matchesText && matchesCategory && matchesPinned;
          item.style.display = show ? "" : "none";
          if (show) visibleCount++;
        });

        noResults.style.display = visibleCount === 0 ? "block" : "none";
      }

      searchInput.addEventListener("input", applyFilters);
      categoryFilter.addEventListener("change", applyFilters);
      pinnedFilter.addEventListener("change", applyFilters);

      form.addEventListener("submit", e => {
        e.preventDefault();
        contentInput.value = quill.root.innerHTML.trim();
        const formData = new FormData(form);
        const isEdit = Boolean(idInput.value);
        const endpoint = isEdit ? "../PHP/updateAnnouncement.php" : "../PHP/addAnnouncement.php";
        fetch(endpoint, { method: "POST", body: formData })
          .then(res => res.json())
          .then(data => {
            if (data.status === "success") {
              loadAnnouncements();
              announcementModal.style.display = "none";
              form.reset();
              idInput.value = "";
              Swal.fire({
                icon: "success",
                title: isEdit ? "Announcement Updated" : "Announcement Added",
                timer: 1200,
                showConfirmButton: false
              });
            } else alert("Error: " + data.message);
          });
      });
    }

    window.addEventListener("DOMContentLoaded", initAnnouncementPage);

    window.editAnnouncement = function (id) {
      fetch("../PHP/fetchAnnouncements.php")
        .then(res => res.json())
        .then(data => {
          const item = data.find(a => Number(a.id) === Number(id));
          if (!item) return;

          const form = document.getElementById("addAnnouncementForm");
          document.getElementById("announcementId").value = item.id;
          form.title.value = item.title;
          form.category.value = item.category;
          form.announcement_date.value = item.announcement_date;
          form.is_pinned.checked = item.is_pinned == 1;
          if (quill) {
            quill.root.innerHTML = item.content || "";
          }

          const modal = document.getElementById("announcementModal");
          modal.querySelector("h2").textContent = "Edit Announcement";
          modal.querySelector(".submit-btn").textContent = "Save Changes";
          modal.style.display = "block";
        });
    }

    function deleteAnnouncement(id) {
      Swal.fire({
        title: "Delete this announcement?",
        text: "This action cannot be undone.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        confirmButtonText: "Delete",
        cancelButtonText: "Cancel"
      }).then(result => {
        if (!result.isConfirmed) return;
        fetch(`../PHP/deleteAnnouncement.php?id=${id}`)
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
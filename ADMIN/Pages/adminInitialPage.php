<?php
$currentPage = 'Dashboard'; // Set the current page
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../CSS/adminPage.css">
  <style>
    .stats-card {
      background: linear-gradient(135deg, #ffffff, #f8fafc);
      border: 1px solid #e5e7eb;
      border-radius: 14px;
      padding: 20px;
      box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    }

    .stats-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 16px;
    }

    .stats-header h4 {
      margin: 0;
      font-size: 18px;
      color: #0f172a;
    }

    .stats-header span {
      font-size: 12px;
      color: #64748b;
      background: #eef2ff;
      padding: 4px 10px;
      border-radius: 999px;
    }

    .bar-chart {
      display: grid;
      grid-template-columns: repeat(6, 1fr);
      gap: 12px;
      align-items: end;
      height: 180px;
      padding: 10px 0;
    }

    .bar {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 6px;
    }

    .bar-value {
      width: 100%;
      border-radius: 10px;
      background: linear-gradient(180deg, #60a5fa, #2563eb);
      box-shadow: 0 6px 14px rgba(37, 99, 235, 0.25);
    }

    .bar-label {
      font-size: 12px;
      color: #64748b;
    }

    .stats-legend {
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
      margin-top: 12px;
      color: #64748b;
      font-size: 12px;
    }

    .legend-dot {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: #2563eb;
      display: inline-block;
      margin-right: 6px;
    }
  </style>
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
          <span class="menu-label">Home Services</span>
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

        <button class="menu-item billing-toggle" onclick="toggleSubmenu('billingSubmenu')">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 7h18M5 11h14M5 15h6m-8-8h18v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z">
            </path>
          </svg>
          <span class="menu-label">Billing</span>
        </button>
        <div class="submenu" id="billingSubmenu">
          <button class="submenu-item" onclick="location.href='paymentMethod.php'">Payment Method</button>
          <button class="submenu-item" onclick="location.href='transactions.php'">Transactions</button>
        </div>

        <button class="menu-item <?php echo $currentPage == 'Calendar' ? 'active' : ''; ?>"
          onclick="location.href='bookingCalendar.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z">
            </path>
          </svg>
          <span class="menu-label">Booking Calendar</span>
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
            <h2 id="pageTitle">Dashboard</h2>
            <p>Welcome back, Admin</p>
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
          <!-- Empty State -->
          <div class="empty-state">
            <div class="empty-icon">
              <svg id="pageIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                </path>
              </svg>
            </div>
            <h3 id="emptyTitle">Dashboard Page</h3>
            <p id="emptyDescription">This is where your dashboard content will appear.</p>
            <small>Click on different menu items to navigate between pages.</small>

            <div class="stats-card" style="margin-top: 24px;">
              <div class="stats-header">
                <h4>Monthly Service Overview</h4>
                <span>Static Demo</span>
              </div>
              <div class="bar-chart">
                <div class="bar">
                  <div class="bar-value" style="height: 60%;"></div>
                  <div class="bar-label">Jan</div>
                </div>
                <div class="bar">
                  <div class="bar-value" style="height: 75%;"></div>
                  <div class="bar-label">Feb</div>
                </div>
                <div class="bar">
                  <div class="bar-value" style="height: 45%;"></div>
                  <div class="bar-label">Mar</div>
                </div>
                <div class="bar">
                  <div class="bar-value" style="height: 90%;"></div>
                  <div class="bar-label">Apr</div>
                </div>
                <div class="bar">
                  <div class="bar-value" style="height: 70%;"></div>
                  <div class="bar-label">May</div>
                </div>
                <div class="bar">
                  <div class="bar-value" style="height: 55%;"></div>
                  <div class="bar-label">Jun</div>
                </div>
              </div>
              <div class="stats-legend">
                <div><span class="legend-dot"></span>Completed Services</div>
                <div>Peak Month: Apr</div>
                <div>Avg Growth: 8%</div>
              </div>
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

    function toggleSubmenu(id) {
      const submenu = document.getElementById(id);
      if (!submenu) return;
      submenu.classList.toggle('open');
    }

    // Logout function
    function logout() {
      if (confirm('Are you sure you want to logout?')) {
        alert('Logged out successfully!');
        // Add your logout logic here
      }
    }
  </script>
</body>

</html>


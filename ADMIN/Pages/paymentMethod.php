<?php
$currentPage = 'PaymentMethod';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Payment Method</title>
  <link rel="stylesheet" href="../CSS/adminPage.css">
  <link rel="stylesheet" href="../CSS/funeralService.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    .toolbar {
      display: flex;
      gap: 12px;
      align-items: center;
      margin: 16px 0 20px;
    }

    .toolbar .search-box {
      flex: 1;
      padding: 10px 12px;
      border-radius: 8px;
      border: 1px solid #e5e7eb;
      background: #ffffff;
    }

    .toolbar .btn-primary {
      background: #2563eb;
      color: #fff;
      border: none;
      padding: 10px 16px;
      border-radius: 999px;
      cursor: pointer;
      box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
      font-weight: 600;
    }

    .toolbar .btn-primary:hover {
      background: #1d4ed8;
    }

    .payment-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 16px;
    }

    @media (max-width: 1024px) {
      .payment-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    @media (max-width: 720px) {
      .payment-grid {
        grid-template-columns: 1fr;
      }
    }

    .payment-card {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 12px;
      padding: 16px;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
      position: relative;
    }

    .payment-card.enabled {
      background: rgba(22, 163, 74, 0.08);
      border-color: rgba(22, 163, 74, 0.25);
    }

    .payment-card.disabled {
      background: rgba(239, 68, 68, 0.08);
      border-color: rgba(239, 68, 68, 0.25);
    }

    .payment-card h4 {
      margin-bottom: 8px;
      color: #111827;
    }

    .qr-preview {
      width: 100%;
      max-width: 160px;
      height: 160px;
      object-fit: cover;
      border-radius: 12px;
      border: 1px solid #e5e7eb;
      margin: 10px 0;
    }

    .status-badge {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 600;
      position: absolute;
      top: 12px;
      right: 12px;
    }

    .status-enabled {
      background: #dcfce7;
      color: #166534;
    }

    .status-disabled {
      background: #fee2e2;
      color: #991b1b;
    }

    .card-actions {
      display: flex;
      gap: 8px;
      margin-top: 12px;
    }

    .btn-outline {
      border: 1px solid #d1d5db;
      background: #fff;
      color: #111827;
      padding: 8px 10px;
      border-radius: 999px;
      cursor: pointer;
    }

    .btn-danger {
      border: 1px solid #ef4444;
      background: #ef4444;
      color: #fff;
      padding: 8px 10px;
      border-radius: 999px;
      cursor: pointer;
    }

    .modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.4);
      z-index: 1000;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .modal-content {
      background: #fff;
      width: 100%;
      max-width: 640px;
      border-radius: 12px;
      padding: 22px;
      max-height: 80vh;
      overflow: auto;
      box-shadow: 0 20px 60px rgba(15, 23, 42, 0.35);
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 12px;
    }

    .modal-header h3 {
      margin: 0;
    }

    .close-modal {
      border: none;
      background: transparent;
      font-size: 20px;
      cursor: pointer;
    }

    .form-group {
      margin-bottom: 12px;
    }

    .form-group label {
      font-weight: 600;
      display: block;
      margin-bottom: 6px;
    }

    .form-group input,
    .form-group select {
      width: 100%;
      padding: 10px 12px;
      border-radius: 12px;
      border: 1px solid #e5e7eb;
      background: #f9fafb;
      transition: border 0.2s ease, box-shadow 0.2s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
      background: #fff;
    }

    .form-group select {
      appearance: none;
      background-image: linear-gradient(45deg, transparent 50%, #64748b 50%),
        linear-gradient(135deg, #64748b 50%, transparent 50%);
      background-position: calc(100% - 18px) calc(1em + 2px),
        calc(100% - 13px) calc(1em + 2px);
      background-size: 5px 5px, 5px 5px;
      background-repeat: no-repeat;
      padding-right: 36px;
    }

    .file-input {
      position: relative;
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px;
      border-radius: 12px;
      border: 1px dashed #cbd5f5;
      background: #f8fafc;
    }

    .file-input input[type="file"] {
      position: absolute;
      inset: 0;
      opacity: 0;
      cursor: pointer;
    }

    .file-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 12px;
      border-radius: 999px;
      background: #e0e7ff;
      color: #3730a3;
      font-size: 12px;
      font-weight: 600;
    }

    .file-hint {
      color: #64748b;
      font-size: 12px;
    }

    .card-number-grid {
      display: grid;
      grid-template-columns: repeat(16, 1fr);
      gap: 6px;
    }

    .card-digit {
      text-align: center;
      padding: 0;
      height: 38px;
      line-height: 38px;
      width: 100%;
      box-sizing: border-box;
      border-radius: 10px;
      border: 1px solid #e5e7eb;
      background: rgba(15, 23, 42, 0.04);
      box-shadow: 0 4px 10px rgba(15, 23, 42, 0.08);
      font-size: 13px;
      font-weight: 600;
    }

    .card-digit:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
      background: #fff;
    }

    @media (max-width: 900px) {
      .card-number-grid {
        grid-template-columns: repeat(8, 1fr);
      }
    }

    @media (max-width: 520px) {
      .card-number-grid {
        grid-template-columns: repeat(4, 1fr);
      }
    }

    .modal-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 14px;
    }
  </style>
</head>

<body>
  <div class="container">
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-header">
        <h1 class="sidebar-title">DULCE ADMIN</h1>
        <button class="toggle-btn" onclick="toggleSidebar()">
          <svg class="menu-icon" id="menuIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <nav class="menu">
        <button class="menu-item" onclick="location.href='adminInitialPage.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" />
          </svg>
          <span class="menu-label">Dashboard</span>
        </button>

        <button class="menu-item" onclick="location.href='funeralPackage.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4m16 0H4" />
          </svg>
          <span class="menu-label">Funeral Services</span>
        </button>

        <button class="menu-item" onclick="location.href='chapelServices.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 21h8m-4-4v4m5-6V5a1 1 0 00-1-1H8a1 1 0 00-1 1v10m10 0H7m10 0l-1 5H8l-1-5" />
          </svg>
          <span class="menu-label">Home Services</span>
        </button>

        <button class="menu-item" onclick="location.href='announcementCrud.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M11 5h2m-1-1v2m7 4V9a5 5 0 00-10 0v1m-2 0a2 2 0 00-2 2v1a2 2 0 002 2h12a2 2 0 002-2v-1a2 2 0 00-2-2H7z" />
          </svg>
          <span class="menu-label">Announcements</span>
        </button>

        <button class="menu-item billing-toggle active" onclick="toggleSubmenu('billingSubmenu')">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 7h18M5 11h14M5 15h6m-8-8h18v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
          </svg>
          <span class="menu-label">Billing</span>
        </button>
        <div class="submenu open" id="billingSubmenu">
          <button class="submenu-item" onclick="location.href='paymentMethod.php'">Payment Method</button>
          <button class="submenu-item" onclick="location.href='transactions.php'">Transactions</button>
        </div>

        <button class="menu-item" onclick="location.href='orders.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
            </path>
          </svg>
          <span class="menu-label">Orders</span>
        </button>

        <button class="menu-item" onclick="location.href='reports.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
            </path>
          </svg>
          <span class="menu-label">Reports</span>
        </button>

        <button class="menu-item" onclick="location.href='documents.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
            </path>
          </svg>
          <span class="menu-label">Documents</span>
        </button>

        <button class="menu-item" onclick="location.href='settings.php'">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
            </path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
            </path>
          </svg>
          <span class="menu-label">Settings</span>
        </button>
      </nav>

      <div class="sidebar-footer">
        <button class="logout-btn" onclick="logout()">
          <svg class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
          </svg>
          <span class="menu-label">Logout</span>
        </button>
      </div>
    </aside>

    <main class="main-content">
      <header class="header">
        <div class="header-content">
          <div class="header-title">
            <h2>Payment Method</h2>
            <p>Manage bank accounts and card details used for payments.</p>
          </div>
        </div>
      </header>

      <div class="content-area">
        <div class="content-container">
          <div class="toolbar">
            <input type="text" class="search-box" id="searchInput" placeholder="Search by bank name or card number">
            <button class="btn-primary" id="openModalBtn"><i class="bi bi-plus-circle"></i> Add Payment Method</button>
          </div>

          <div class="payment-grid" id="paymentGrid"></div>
          <div id="noResults" class="text-center" style="display:none;margin-top:20px;color:#6b7280;">
            No payment methods found.
          </div>
        </div>
      </div>
    </main>
  </div>

  <div class="modal" id="paymentModal">
    <div class="modal-content">
      <div class="modal-header">
        <h3 id="modalTitle">Add Payment Method</h3>
        <button class="close-modal" id="closeModalBtn">&times;</button>
      </div>
      <form id="paymentForm">
        <input type="hidden" id="paymentId">
        <div class="form-group">
          <label for="bankName">Bank Name</label>
          <input type="text" id="bankName" required>
        </div>
        <div class="form-group">
          <label for="cardNumber">Card Number</label>
          <input type="hidden" id="cardNumber" required>
          <div class="card-number-grid" id="cardNumberGrid" aria-label="Card number digits">
            <input class="card-digit" inputmode="numeric" maxlength="1" aria-label="Digit 1">
            <input class="card-digit" inputmode="numeric" maxlength="1" aria-label="Digit 2">
            <input class="card-digit" inputmode="numeric" maxlength="1" aria-label="Digit 3">
            <input class="card-digit" inputmode="numeric" maxlength="1" aria-label="Digit 4">
            <input class="card-digit" inputmode="numeric" maxlength="1" aria-label="Digit 5">
            <input class="card-digit" inputmode="numeric" maxlength="1" aria-label="Digit 6">
            <input class="card-digit" inputmode="numeric" maxlength="1" aria-label="Digit 7">
            <input class="card-digit" inputmode="numeric" maxlength="1" aria-label="Digit 8">
            <input class="card-digit" inputmode="numeric" maxlength="1" aria-label="Digit 9">
            <input class="card-digit" inputmode="numeric" maxlength="1" aria-label="Digit 10">
            <input class="card-digit" inputmode="numeric" maxlength="1" aria-label="Digit 11">
            <input class="card-digit" inputmode="numeric" maxlength="1" aria-label="Digit 12">
            <input class="card-digit" inputmode="numeric" maxlength="1" aria-label="Digit 13">
            <input class="card-digit" inputmode="numeric" maxlength="1" aria-label="Digit 14">
            <input class="card-digit" inputmode="numeric" maxlength="1" aria-label="Digit 15">
            <input class="card-digit" inputmode="numeric" maxlength="1" aria-label="Digit 16">
          </div>
          <small style="color:#64748b;display:block;margin-top:6px;">
            16 digits standard, 15 digits for AmEx (leave last box empty).
          </small>
        </div>
        <div class="form-group">
          <label for="status">Status</label>
          <select id="status">
            <option value="enabled">Enable</option>
            <option value="disabled">Disable</option>
          </select>
        </div>
        <div class="form-group">
          <label for="qrImage">QR Image</label>
          <div class="file-input">
            <span class="file-badge"><i class="bi bi-cloud-arrow-up"></i> Upload</span>
            <span class="file-hint" id="qrFileName">No file selected</span>
            <input type="file" id="qrImage" accept="image/*">
          </div>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn-outline" id="cancelBtn">Cancel</button>
          <button type="submit" class="submit-btn" id="saveBtn">Save</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const paymentGrid = document.getElementById("paymentGrid");
    const noResults = document.getElementById("noResults");
    const searchInput = document.getElementById("searchInput");
    const modal = document.getElementById("paymentModal");
    const modalTitle = document.getElementById("modalTitle");
    const paymentForm = document.getElementById("paymentForm");
    const paymentId = document.getElementById("paymentId");
    const bankName = document.getElementById("bankName");
    const cardNumber = document.getElementById("cardNumber");
    const status = document.getElementById("status");
    const qrImage = document.getElementById("qrImage");
    const qrFileName = document.getElementById("qrFileName");
    const cardDigitInputs = Array.from(document.querySelectorAll("#cardNumberGrid .card-digit"));

    let paymentMethods = [];

    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const menuIcon = document.getElementById('menuIcon');
      sidebar.classList.toggle('collapsed');
      menuIcon.innerHTML = sidebar.classList.contains('collapsed') ?
        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>' :
        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
    }

    function toggleSubmenu(id) {
      const submenu = document.getElementById(id);
      if (!submenu) return;
      submenu.classList.toggle("open");
    }

    function logout() {
      if (confirm('Are you sure you want to logout?')) {
        alert('Logged out successfully!');
      }
    }

    function maskCard(num) {
      const last4 = num.replace(/\s/g, "").slice(-4);
      return "**** **** **** " + last4;
    }

    function renderPayments(list) {
      paymentGrid.innerHTML = "";
      if (!list.length) {
        noResults.style.display = "block";
        return;
      }
      noResults.style.display = "none";
      list.forEach(item => {
        const badgeClass = item.status === "enabled" ? "status-enabled" : "status-disabled";
        const badgeText = item.status === "enabled" ? "Enabled" : "Disabled";
        const card = document.createElement("div");
        card.className = `payment-card ${item.status === "enabled" ? "enabled" : "disabled"}`;
        card.innerHTML = `
          <span class="status-badge ${badgeClass}">${badgeText}</span>
          <h4>${item.bankName}</h4>
          <p>Card: ${maskCard(item.cardNumber)}</p>
          ${item.qrImage ? `<img class="qr-preview" src="../../${item.qrImage}" alt="QR for ${item.bankName}">` : ""}
          <div class="card-actions">
            <button class="btn-outline" onclick="openEdit(${item.id})">Edit</button>
            <button class="btn-danger" onclick="deletePayment(${item.id})">Delete</button>
          </div>
        `;
        paymentGrid.appendChild(card);
      });
    }

    function openAdd() {
      paymentForm.reset();
      paymentId.value = "";
      status.value = "enabled";
      qrImage.value = "";
      qrFileName.textContent = "No file selected";
      cardDigitInputs.forEach(input => input.value = "");
      cardDigitInputs[0]?.focus();
      modalTitle.textContent = "Add Payment Method";
      modal.style.display = "flex";
    }

    function openEdit(id) {
      const item = paymentMethods.find(p => p.id === id);
      if (!item) return;
      paymentId.value = item.id;
      bankName.value = item.bankName;
      cardNumber.value = item.cardNumber;
      status.value = item.status;
      qrImage.value = "";
      qrFileName.textContent = "No file selected";
      const digits = (item.cardNumber || "").replace(/\D/g, "").split("");
      cardDigitInputs.forEach((input, idx) => {
        input.value = digits[idx] || "";
      });
      modalTitle.textContent = "Edit Payment Method";
      modal.style.display = "flex";
    }

    function deletePayment(id) {
      Swal.fire({
        title: "Delete payment method?",
        text: "This action cannot be undone.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Delete",
        cancelButtonText: "Cancel"
      }).then(result => {
        if (!result.isConfirmed) return;
        fetch("../PHP/deletePaymentMethod.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id })
        })
          .then(res => res.json())
          .then(data => {
            if (!data.success) throw new Error(data.message || "Delete failed");
            Swal.fire("Deleted", "Payment method removed.", "success");
            loadPayments();
          })
          .catch(err => Swal.fire("Error", err.message, "error"));
      });
    }

    function filterList() {
      const term = searchInput.value.toLowerCase().trim();
      if (!term) return paymentMethods;
      return paymentMethods.filter(p =>
        p.bankName.toLowerCase().includes(term) ||
        p.cardNumber.toLowerCase().includes(term)
      );
    }

    paymentForm.addEventListener("submit", (e) => {
      e.preventDefault();
      cardNumber.value = cardDigitInputs.map(i => i.value).join("");
      const formData = new FormData();
      if (paymentId.value) {
        formData.append("id", paymentId.value);
      }
      formData.append("bankName", bankName.value.trim());
      formData.append("cardNumber", cardNumber.value.trim());
      formData.append("status", status.value);
      if (qrImage.files[0]) {
        formData.append("qrImage", qrImage.files[0]);
      }
      const url = paymentId.value ? "../PHP/updatePaymentMethod.php" : "../PHP/addPaymentMethod.php";
      Swal.fire({
        title: paymentId.value ? "Update payment method?" : "Add payment method?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: paymentId.value ? "Update" : "Add",
        cancelButtonText: "Cancel"
      }).then(result => {
        if (!result.isConfirmed) return;
        fetch(url, {
          method: "POST",
          body: formData
        })
          .then(res => res.json())
          .then(data => {
            if (!data.success) throw new Error(data.message || "Save failed");
            Swal.fire("Success", paymentId.value ? "Payment method updated." : "Payment method added.", "success");
            modal.style.display = "none";
            loadPayments();
          })
          .catch(err => Swal.fire("Error", err.message, "error"));
      });
    });

    document.getElementById("openModalBtn").addEventListener("click", openAdd);
    document.getElementById("closeModalBtn").addEventListener("click", () => modal.style.display = "none");
    document.getElementById("cancelBtn").addEventListener("click", () => modal.style.display = "none");
    searchInput.addEventListener("input", () => renderPayments(filterList()));
    qrImage.addEventListener("change", () => {
      qrFileName.textContent = qrImage.files[0] ? qrImage.files[0].name : "No file selected";
    });
    cardDigitInputs.forEach((input, idx) => {
      input.addEventListener("input", (e) => {
        const val = e.target.value.replace(/\D/g, "").slice(0, 1);
        e.target.value = val;
        if (val && idx < cardDigitInputs.length - 1) {
          cardDigitInputs[idx + 1].focus();
        }
      });
      input.addEventListener("keydown", (e) => {
        if (e.key === "Backspace" && !input.value && idx > 0) {
          cardDigitInputs[idx - 1].focus();
        }
      });
    });

    function loadPayments() {
      fetch("../PHP/fetchPaymentMethods.php")
        .then(res => res.json())
        .then(data => {
          paymentMethods = Array.isArray(data) ? data : [];
          renderPayments(filterList());
        })
        .catch(err => {
          console.error("Load error:", err);
          paymentMethods = [];
          renderPayments([]);
        });
    }

    loadPayments();
  </script>
</body>

</html>


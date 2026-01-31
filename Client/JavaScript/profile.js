// DULCE - Profile Page JavaScript

document.addEventListener("DOMContentLoaded", function () {
  loadProfileData();
  setupProfileForm();
  setupPasswordForm();
});

// Load user profile data
function loadProfileData() {
  fetch("http://localhost/DULCESYSTEM1/Client/api/get-profile.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        // Populate profile fields
        document.getElementById("profileName").textContent =
          data.user.full_name;
        document.getElementById("profileEmail").textContent = data.user.email;
        document.getElementById("fullName").value = data.user.full_name;
        document.getElementById("email").value = data.user.email;
        document.getElementById("contactNumber").value =
          data.user.contact_number;
        document.getElementById("address").value = data.user.address;
        document.getElementById("memberSince").value = formatDate(
          data.user.created_at,
        );

        // Update stats (will be replaced with actual data)
        document.getElementById("profileBookings").textContent = "2";
        document.getElementById("profileDocs").textContent = "3";
      }
    })
    .catch((error) => {
      console.error("Error loading profile:", error);
    });
}

// Setup profile update form
function setupProfileForm() {
  const form = document.getElementById("profileForm");

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML =
      '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    submitBtn.disabled = true;

    const formData = {
      fullName: document.getElementById("fullName").value,
      contactNumber: document.getElementById("contactNumber").value,
      address: document.getElementById("address").value,
    };

    fetch("http://localhost/DULCESYSTEM1/Client/api/update-profile.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(formData),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          showProfileAlert("Profile updated successfully!", "success");
          loadProfileData(); // Reload profile data
        } else {
          showProfileAlert(data.message, "danger");
        }
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      })
      .catch((error) => {
        console.error("Error:", error);
        showProfileAlert("An error occurred. Please try again.", "danger");
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      });
  });
}

// Setup password change form
function setupPasswordForm() {
  const form = document.getElementById("passwordForm");

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const currentPassword = document.getElementById("currentPassword").value;
    const newPassword = document.getElementById("newPassword").value;
    const confirmNewPassword =
      document.getElementById("confirmNewPassword").value;

    // Validate passwords
    if (newPassword !== confirmNewPassword) {
      showPasswordAlert("New passwords do not match", "danger");
      return;
    }

    if (newPassword.length < 8) {
      showPasswordAlert("Password must be at least 8 characters", "danger");
      return;
    }

    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML =
      '<span class="spinner-border spinner-border-sm me-2"></span>Updating...';
    submitBtn.disabled = true;

    fetch("http://localhost/DULCESYSTEM1/Client/api/change-password.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        currentPassword: currentPassword,
        newPassword: newPassword,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          showPasswordAlert("Password updated successfully!", "success");
          form.reset();
        } else {
          showPasswordAlert(data.message, "danger");
        }
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      })
      .catch((error) => {
        console.error("Error:", error);
        showPasswordAlert("An error occurred. Please try again.", "danger");
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      });
  });
}

// Helper functions
function showProfileAlert(message, type) {
  const alert = document.getElementById("profileAlert");
  const alertMessage = document.getElementById("profileAlertMessage");

  alert.className = `alert alert-${type} alert-dismissible fade show`;
  alertMessage.textContent = message;
  alert.style.display = "block";

  setTimeout(() => {
    alert.classList.remove("show");
    setTimeout(() => {
      alert.style.display = "none";
    }, 150);
  }, 5000);
}

function showPasswordAlert(message, type) {
  const alert = document.getElementById("passwordAlert");
  const alertMessage = document.getElementById("passwordAlertMessage");

  alert.className = `alert alert-${type} alert-dismissible fade show`;
  alertMessage.textContent = message;
  alert.style.display = "block";

  setTimeout(() => {
    alert.classList.remove("show");
    setTimeout(() => {
      alert.style.display = "none";
    }, 150);
  }, 5000);
}

function formatDate(dateString) {
  const options = { year: "numeric", month: "long", day: "numeric" };
  return new Date(dateString).toLocaleDateString("en-US", options);
}

// Password toggle function (reuse from auth.js)
function togglePassword(inputId) {
  const input = document.getElementById(inputId);
  const icon = document.getElementById(inputId + "-icon");

  if (input.type === "password") {
    input.type = "text";
    icon.classList.remove("bi-eye");
    icon.classList.add("bi-eye-slash");
  } else {
    input.type = "password";
    icon.classList.remove("bi-eye-slash");
    icon.classList.add("bi-eye");
  }
}

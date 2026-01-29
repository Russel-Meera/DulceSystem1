// DULCE - Authentication JavaScript (Login, Register, Forgot Password)

// ============================================
// LOGIN FUNCTIONALITY
// ============================================

document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.getElementById("loginForm");
  const registerForm = document.getElementById("registerForm");
  const forgotPasswordForm = document.getElementById("forgotPasswordForm");

  // Login Form Submission
  if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
      e.preventDefault();

      if (!validateLoginForm()) {
        return;
      }

      const email = document.getElementById("loginEmail").value;
      const password = document.getElementById("loginPassword").value;
      const rememberMe = document.getElementById("rememberMe").checked;

      // Show loading state
      const submitBtn = loginForm.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML =
        '<span class="spinner-border spinner-border-sm me-2"></span>Logging in...';
      submitBtn.disabled = true;

      // Simulate API call (In production, this will be an actual API request)
      setTimeout(() => {
        // For demo purposes, check if email exists
        // In production, this will validate against database
        if (email && password) {
          // Success - redirect to dashboard
          showAlert(
            "loginAlert",
            "Login successful! Redirecting...",
            "success",
          );

          setTimeout(() => {
            // In production, redirect to client dashboard
            window.location.href = "../../Client/Pages/client-dashboard.html";
          }, 1500);
        } else {
          // Error
          showAlert(
            "loginAlert",
            "Invalid email or password. Please try again.",
            "danger",
          );
          submitBtn.innerHTML = originalText;
          submitBtn.disabled = false;
        }
      }, 1500);
    });
  }

  // Register Form Submission
  if (registerForm) {
    registerForm.addEventListener("submit", function (e) {
      e.preventDefault();

      if (!validateRegisterForm()) {
        return;
      }

      const formData = {
        firstName: document.getElementById("firstName").value,
        lastName: document.getElementById("lastName").value,
        email: document.getElementById("registerEmail").value,
        contactNumber: document.getElementById("contactNumber").value,
        address: document.getElementById("address").value,
        password: document.getElementById("registerPassword").value,
      };

      // Show loading state
      const submitBtn = registerForm.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML =
        '<span class="spinner-border spinner-border-sm me-2"></span>Creating Account...';
      submitBtn.disabled = true;

      // Simulate API call (In production, this will save to database)
      setTimeout(() => {
        // Success
        showAlert(
          "registerAlert",
          "Account created successfully! Redirecting to login...",
          "success",
        );

        setTimeout(() => {
          window.location.href = "login.html";
        }, 2000);
      }, 1500);
    });

    // Real-time password strength checker
    const passwordInput = document.getElementById("registerPassword");
    if (passwordInput) {
      passwordInput.addEventListener("input", function () {
        checkPasswordStrength(this.value);
      });
    }

    // Real-time password match checker
    const confirmPasswordInput = document.getElementById("confirmPassword");
    if (confirmPasswordInput) {
      confirmPasswordInput.addEventListener("input", function () {
        const password = document.getElementById("registerPassword").value;
        if (this.value && this.value !== password) {
          this.classList.add("is-invalid");
          this.classList.remove("is-valid");
        } else if (this.value === password && this.value !== "") {
          this.classList.remove("is-invalid");
          this.classList.add("is-valid");
        }
      });
    }
  }

  // Forgot Password Form Submission
  if (forgotPasswordForm) {
    forgotPasswordForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const email = document.getElementById("forgotEmail").value;

      if (!validateEmail(email)) {
        document.getElementById("forgotEmail").classList.add("is-invalid");
        return;
      }

      // Show loading state
      const submitBtn = forgotPasswordForm.querySelector(
        'button[type="submit"]',
      );
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML =
        '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
      submitBtn.disabled = true;

      // Simulate API call (In production, this will send reset email)
      setTimeout(() => {
        // Hide form and show success message
        document.getElementById("forgotPasswordForm").style.display = "none";
        document.getElementById("resetEmailSent").style.display = "block";
        document.getElementById("sentEmailAddress").textContent = email;
      }, 1500);
    });
  }
});

// ============================================
// VALIDATION FUNCTIONS
// ============================================

function validateLoginForm() {
  const email = document.getElementById("loginEmail");
  const password = document.getElementById("loginPassword");
  let isValid = true;

  // Reset validation states
  email.classList.remove("is-invalid");
  password.classList.remove("is-invalid");

  // Validate email
  if (!email.value || !validateEmail(email.value)) {
    email.classList.add("is-invalid");
    isValid = false;
  }

  // Validate password
  if (!password.value) {
    password.classList.add("is-invalid");
    isValid = false;
  }

  return isValid;
}

function validateRegisterForm() {
  const firstName = document.getElementById("firstName");
  const lastName = document.getElementById("lastName");
  const email = document.getElementById("registerEmail");
  const contactNumber = document.getElementById("contactNumber");
  const address = document.getElementById("address");
  const password = document.getElementById("registerPassword");
  const confirmPassword = document.getElementById("confirmPassword");
  const agreeTerms = document.getElementById("agreeTerms");

  let isValid = true;

  // Reset validation states
  [
    firstName,
    lastName,
    email,
    contactNumber,
    address,
    password,
    confirmPassword,
  ].forEach((field) => {
    field.classList.remove("is-invalid");
  });

  // Validate first name
  if (!firstName.value.trim()) {
    firstName.classList.add("is-invalid");
    isValid = false;
  }

  // Validate last name
  if (!lastName.value.trim()) {
    lastName.classList.add("is-invalid");
    isValid = false;
  }

  // Validate email
  if (!email.value || !validateEmail(email.value)) {
    email.classList.add("is-invalid");
    isValid = false;
  }

  // Validate contact number
  if (!contactNumber.value || !validatePhone(contactNumber.value)) {
    contactNumber.classList.add("is-invalid");
    isValid = false;
  }

  // Validate address
  if (!address.value.trim()) {
    address.classList.add("is-invalid");
    isValid = false;
  }

  // Validate password
  if (!password.value || password.value.length < 8) {
    password.classList.add("is-invalid");
    isValid = false;
  }

  // Validate confirm password
  if (!confirmPassword.value || confirmPassword.value !== password.value) {
    confirmPassword.classList.add("is-invalid");
    isValid = false;
  }

  // Validate terms agreement
  if (!agreeTerms.checked) {
    agreeTerms.classList.add("is-invalid");
    isValid = false;
  }

  return isValid;
}

// ============================================
// HELPER FUNCTIONS
// ============================================

function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

function validatePhone(phone) {
  const re = /^[\d\s\-\+\(\)]+$/;
  return re.test(phone) && phone.replace(/\D/g, "").length >= 10;
}

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

function checkPasswordStrength(password) {
  const strengthDiv = document.getElementById("passwordStrength");

  if (!password) {
    strengthDiv.className = "password-strength";
    return;
  }

  let strength = 0;

  // Check length
  if (password.length >= 8) strength++;
  if (password.length >= 12) strength++;

  // Check for lowercase and uppercase
  if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;

  // Check for numbers
  if (/\d/.test(password)) strength++;

  // Check for special characters
  if (/[^A-Za-z0-9]/.test(password)) strength++;

  // Set strength class
  strengthDiv.className = "password-strength";
  if (strength <= 2) {
    strengthDiv.classList.add("weak");
  } else if (strength <= 4) {
    strengthDiv.classList.add("medium");
  } else {
    strengthDiv.classList.add("strong");
  }
}

function showAlert(alertId, message, type) {
  const alert = document.getElementById(alertId);
  const alertMessage = document.getElementById(alertId + "Message");

  alert.className = `alert alert-${type} alert-dismissible fade show`;
  alertMessage.textContent = message;
  alert.style.display = "block";

  // Auto hide after 5 seconds
  setTimeout(() => {
    alert.classList.remove("show");
    setTimeout(() => {
      alert.style.display = "none";
    }, 150);
  }, 5000);
}

function resendResetEmail() {
  const email = document.getElementById("forgotEmail").value;
  showAlert(
    "forgotPasswordAlert",
    "Reset link sent again to " + email,
    "success",
  );
}

// DULCE - Chapel Booking Page JavaScript

const chapelServiceNameById = new Map();
const chapelServiceFeaturesById = new Map();

document.addEventListener("DOMContentLoaded", () => {
  loadChapelServices();
  setupDateRestrictions();
  setupTimePickers();
  setupBookingForm();
});

function loadChapelServices() {
  const chapelInput = document.getElementById("chapel");
  const hiddenChapelId = document.getElementById("chapelServiceId");
  if (!hiddenChapelId) return;

  const selectedId = getSelectedChapelId();
  if (selectedId) {
    hiddenChapelId.value = selectedId;
  }

  fetch("http://localhost/DULCESYSTEM1/Client/api/get-chapels.php")
    .then((response) => response.json())
    .then((data) => {
      data.forEach((chapel) => {
        chapelServiceNameById.set(String(chapel.id), chapel.name);
        chapelServiceFeaturesById.set(
          String(chapel.id),
          Array.isArray(chapel.features) ? chapel.features : [],
        );
      });

      syncChapelName();
      renderHomeServiceFeatures();
    })
    .catch((error) => {
      console.error("Error loading chapel services:", error);
      if (chapelInput) {
        chapelInput.value = "";
      }
      renderHomeServiceFeatures();
    });
}

function syncChapelName() {
  const select = document.getElementById("chapelServiceId");
  const chapelInput = document.getElementById("chapel");
  if (!chapelInput || !select) return;

  const selectedId = select.value;
  const selectedName = chapelServiceNameById.get(selectedId) || "";
  chapelInput.value = selectedName;
}

function getSelectedChapelId() {
  const params = new URLSearchParams(window.location.search);
  return params.get("chapel_id");
}

function setupBookingForm() {
  const form = document.getElementById("chapelBookingForm");
  if (!form) return;

  form.addEventListener("submit", (event) => {
    event.preventDefault();

    syncTimeTarget("serviceTime");
    syncTimeTarget("viewingStartTime");
    syncTimeTarget("viewingEndTime");
    syncTimeTarget("intermentTime");
    validateViewingRange();

    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML =
      '<span class="spinner-border spinner-border-sm me-2"></span>Submitting...';
    submitBtn.disabled = true;

    const formData = buildBookingFormData();

    fetch("http://localhost/DULCESYSTEM1/Client/api/create-booking.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          const bookingId = data.booking_id;
          const clientId = data.client_id;
          if (bookingId && clientId) {
            const modalEl = document.getElementById("selectPaymentModal");
            const proceedBtn = document.getElementById("proceedToPayment");
            if (modalEl && proceedBtn && window.bootstrap) {
              const modal = new bootstrap.Modal(modalEl);
              proceedBtn.onclick = () => {
                window.location.href = `selectPayment.php?booking_id=${bookingId}&client_id=${clientId}`;
              };
              modal.show();
              return;
            }
            window.location.href = `selectPayment.php?booking_id=${bookingId}&client_id=${clientId}`;
            return;
          }
          alert("Booking saved, but payment selection could not start.");
        } else {
          alert(data.message || "Failed to save booking.");
        }
      })
      .catch((error) => {
        console.error("Booking error:", error);
        alert("An error occurred while saving your booking.");
      })
      .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      });
  });
}

function setupDateRestrictions() {
  const today = new Date();
  const todayStr = today.toISOString().split("T")[0];

  const birthDate = document.getElementById("birthDate");
  const deathDate = document.getElementById("deathDate");
  const wakeStart = document.getElementById("wakeStartDate");
  const wakeEnd = document.getElementById("wakeEndDate");
  const intermentDate = document.getElementById("intermentDate");

  if (birthDate) birthDate.max = todayStr;
  if (deathDate) deathDate.min = todayStr;
  if (wakeStart) wakeStart.min = todayStr;
  if (wakeEnd) wakeEnd.min = todayStr;
  if (intermentDate) intermentDate.min = todayStr;

  if (wakeStart && wakeEnd) {
    wakeStart.addEventListener("change", () => {
      if (wakeStart.value) {
        wakeEnd.min = wakeStart.value;
      }
    });
  }
}

function setupTimePickers() {
  const hourSelects = Array.from(document.querySelectorAll(".time-hour"));
  const minuteSelects = Array.from(document.querySelectorAll(".time-minute"));
  const periodSelects = Array.from(document.querySelectorAll(".time-period"));

  if (hourSelects.length === 0) return;

  hourSelects.forEach((select) => {
    if (select.options.length > 1) return;
    for (let hour = 1; hour <= 12; hour += 1) {
      const value = String(hour);
      const option = document.createElement("option");
      option.value = value;
      option.textContent = value.padStart(2, "0");
      select.appendChild(option);
    }
  });

  const updateTargets = new Set();
  [...hourSelects, ...minuteSelects, ...periodSelects].forEach((select) => {
    const targetId = select.dataset.timeTarget;
    if (!targetId) return;
    updateTargets.add(targetId);
    select.addEventListener("change", () => {
      syncTimeTarget(targetId);
      validateViewingRange();
    });
    select.addEventListener("blur", () => {
      syncTimeTarget(targetId);
      validateViewingRange();
    });
  });

  updateTargets.forEach((targetId) => syncTimeTarget(targetId));
  validateViewingRange();
}

function syncTimeTarget(targetId) {
  const hourSelect = document.getElementById(`${targetId}Hour`);
  const minuteSelect = document.getElementById(`${targetId}Minute`);
  const periodSelect = document.getElementById(`${targetId}Period`);
  const targetInput = document.getElementById(targetId);

  if (!hourSelect || !minuteSelect || !periodSelect || !targetInput) return;

  const hourValue = hourSelect.value;
  const minuteValue = minuteSelect.value;
  const periodValue = periodSelect.value;

  if (!hourValue || !minuteValue || !periodValue) {
    targetInput.value = "";
    return;
  }

  let hour24 = Number(hourValue) % 12;
  if (periodValue === "PM") {
    hour24 += 12;
  }
  targetInput.value = `${String(hour24).padStart(2, "0")}:${minuteValue}`;
}

function validateViewingRange() {
  const startValue = document.getElementById("viewingStartTime")?.value || "";
  const endValue = document.getElementById("viewingEndTime")?.value || "";
  const endPeriod = document.getElementById("viewingEndTimePeriod");

  if (!endPeriod) return;

  if (!startValue || !endValue) {
    endPeriod.setCustomValidity("");
    return;
  }

  if (endValue < startValue) {
    endPeriod.setCustomValidity("Viewing end time must be later than start time.");
    endPeriod.reportValidity();
  } else {
    endPeriod.setCustomValidity("");
  }
}

function renderHomeServiceFeatures() {
  const container = document.getElementById("homeServiceFeatures");
  const hiddenChapelId = document.getElementById("chapelServiceId");
  if (!container || !hiddenChapelId) return;

  const selectedId = hiddenChapelId.value;
  const features = chapelServiceFeaturesById.get(selectedId) || [];

  if (!selectedId || features.length === 0) {
    container.innerHTML =
      '<div class="col-12 text-muted">Features will load once the home service is selected.</div>';
    return;
  }

  container.innerHTML = features
    .map(
      (item) =>
        `<div class="col-6"><div class="feature-item">${item}</div></div>`,
    )
    .join("");
}

function buildBookingFormData() {
  const chapelServiceId = document.getElementById("chapelServiceId").value;
  const chapelName = chapelServiceNameById.get(chapelServiceId) ||
    document.getElementById("chapel").value ||
    "";

  const formData = new FormData();
  formData.append("contact_name", document.getElementById("contactName").value.trim());
  formData.append("contact_mobile", document.getElementById("contactMobile").value.trim());
  formData.append("session_name", document.getElementById("sessionName").value.trim());
  formData.append("session_number", document.getElementById("sessionNumber").value.trim());
  formData.append("session_email", document.getElementById("sessionEmail").value.trim());
  formData.append("session_address", document.getElementById("sessionAddress").value.trim());

  formData.append("chapel_id", chapelServiceId);
  formData.append("service_date", document.getElementById("serviceDate").value);
  formData.append("service_time", document.getElementById("serviceTime").value);

  formData.append("obituary_name", document.getElementById("obituaryName").value.trim());
  formData.append("birth_date", document.getElementById("birthDate").value);
  formData.append("death_date", document.getElementById("deathDate").value);
  formData.append("excerpt", document.getElementById("excerpt").value.trim());
  formData.append("full_biography", document.getElementById("fullBiography").value.trim());
  const wakeStartDate = document.getElementById("wakeStartDate").value;
  const wakeEndDate = document.getElementById("wakeEndDate").value;
  const wakeSchedule = wakeStartDate && wakeEndDate
    ? `${wakeStartDate} to ${wakeEndDate}`
    : "";
  formData.append("wake_schedule", wakeSchedule);
  formData.append("chapel", chapelName);
  const viewingStart = document.getElementById("viewingStartTime").value;
  const viewingEnd = document.getElementById("viewingEndTime").value;
  const viewingHours = viewingStart && viewingEnd
    ? `${viewingStart} - ${viewingEnd}`
    : "";
  formData.append("viewing_hours", viewingHours);

  const intermentDate = document.getElementById("intermentDate").value;
  const intermentTime = document.getElementById("intermentTime").value;
  const intermentValue = intermentDate && intermentTime
    ? `${intermentDate} ${intermentTime}`
    : intermentDate || "";
  formData.append("interment", intermentValue);

  const imageFile = document.getElementById("imageFile").files[0];
  if (imageFile) {
    formData.append("image_file", imageFile);
  }

  return formData;
}

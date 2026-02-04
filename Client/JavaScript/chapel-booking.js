// DULCE - Chapel Booking Page JavaScript

const chapelServiceNameById = new Map();
const chapelServiceFeaturesById = new Map();

document.addEventListener("DOMContentLoaded", () => {
  loadChapelServices();
  setupDateRestrictions();
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
          alert("Booking and obituary saved successfully.");
          form.reset();
          document.getElementById("chapelServiceId").value = "";
          document.getElementById("chapel").value = "";
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

  const viewingStart = document.getElementById("viewingStartTime");
  const viewingEnd = document.getElementById("viewingEndTime");
  if (viewingStart && viewingEnd) {
    viewingStart.addEventListener("change", () => {
      if (viewingStart.value) {
        viewingEnd.min = viewingStart.value;
      }
    });
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

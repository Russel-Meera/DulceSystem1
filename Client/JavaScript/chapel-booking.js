// DULCE - Chapel Booking Page JavaScript

const chapelServiceNameById = new Map();
const funeralPackageNameById = new Map();
const funeralPackageDetailsById = new Map();

document.addEventListener("DOMContentLoaded", () => {
  loadChapelServices();
  loadFuneralPackages();
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
      });

      syncChapelName();
    })
    .catch((error) => {
      console.error("Error loading chapel services:", error);
      if (chapelInput) {
        chapelInput.value = "";
      }
    });
}

function loadFuneralPackages() {
  const select = document.getElementById("funeralPackageId");
  if (!select) return;

  fetch("http://localhost/DULCESYSTEM1/Client/PHP/fetchPackages.php")
    .then((response) => response.json())
    .then((data) => {
      select.innerHTML = "";
      const placeholder = document.createElement("option");
      placeholder.value = "";
      placeholder.textContent = "Select a package";
      select.appendChild(placeholder);

      if (data.status !== "success" || !data.packages) {
        return;
      }

      Object.values(data.packages).forEach((pkg) => {
        const option = document.createElement("option");
        option.value = pkg.id;
        option.textContent = pkg.name;
        funeralPackageNameById.set(String(pkg.id), pkg.name);
        funeralPackageDetailsById.set(
          String(pkg.id),
          Array.isArray(pkg.details) ? pkg.details : [],
        );
        select.appendChild(option);
      });

      select.addEventListener("change", renderPackageInclusions);
      renderPackageInclusions();
    })
    .catch((error) => {
      console.error("Error loading funeral packages:", error);
      select.innerHTML = "";
      const fallback = document.createElement("option");
      fallback.value = "";
      fallback.textContent = "Unable to load packages";
      select.appendChild(fallback);
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

function renderPackageInclusions() {
  const select = document.getElementById("funeralPackageId");
  const list = document.getElementById("packageInclusions");
  if (!select || !list) return;

  const selectedId = select.value;
  const inclusions = funeralPackageDetailsById.get(selectedId) || [];

  if (!selectedId || inclusions.length === 0) {
    list.innerHTML =
      '<li class="text-muted">Select a package to view inclusions.</li>';
    return;
  }

  list.innerHTML = inclusions
    .map(
      (item) =>
        `<li><i class="bi bi-check-circle-fill"></i> ${item}</li>`,
    )
    .join("");
}

function buildBookingFormData() {
  const chapelServiceId = document.getElementById("chapelServiceId").value;
  const funeralPackageId = document.getElementById("funeralPackageId").value;
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
  formData.append("package_id", funeralPackageId);
  formData.append("service_date", document.getElementById("serviceDate").value);
  formData.append("service_time", document.getElementById("serviceTime").value);

  formData.append("obituary_name", document.getElementById("obituaryName").value.trim());
  formData.append("birth_date", document.getElementById("birthDate").value);
  formData.append("death_date", document.getElementById("deathDate").value);
  formData.append("excerpt", document.getElementById("excerpt").value.trim());
  formData.append("full_biography", document.getElementById("fullBiography").value.trim());
  formData.append("wake_schedule", document.getElementById("wakeSchedule").value.trim());
  formData.append("chapel", chapelName);
  formData.append("viewing_hours", document.getElementById("viewingHours").value.trim());
  formData.append("interment", document.getElementById("interment").value.trim());

  const imageFile = document.getElementById("imageFile").files[0];
  if (imageFile) {
    formData.append("image_file", imageFile);
  }

  return formData;
}

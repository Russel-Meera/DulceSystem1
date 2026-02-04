// DULCE - Obituaries Page JavaScript

let obituariesData = [];

function escapeHtml(text) {
  const div = document.createElement("div");
  div.textContent = text ?? "";
  return div.innerHTML;
}

function getInitials(name) {
  if (!name) return "N/A";
  return name
    .split(" ")
    .filter(Boolean)
    .map((n) => n[0])
    .join("");
}

function renderObituaries(items) {
  const container = document.querySelector("#obituaries-container");
  if (!container) return;

  container.innerHTML = "";

  items.forEach((obituary) => {
    const card = document.createElement("div");
    card.className = "col-lg-4 col-md-6 obituary-item";
    card.setAttribute("data-name", obituary.name || "");
    card.setAttribute("data-date", obituary.deathDateISO || "");

    const initials = encodeURIComponent(getInitials(obituary.name));
    const imageUrl = obituary.image
      ? obituary.image
      : `https://via.placeholder.com/400x400/2c3e50/ffffff?text=${initials}`;

    card.innerHTML = `
      <div class="obituary-card">
        <div class="obituary-ribbon">
          <i class="bi bi-heart-fill"></i>
        </div>
        <div class="obituary-image-wrapper">
          <img
            src="${escapeHtml(imageUrl)}"
            alt="${escapeHtml(obituary.name)}"
            class="obituary-image"
            onerror="this.src='https://via.placeholder.com/400x400/2c3e50/ffffff?text=${initials}'"
          />
          <div class="obituary-overlay">
            <button class="btn btn-view-obituary" onclick="viewObituaryDetails(${obituary.id})">
              <i class="bi bi-book"></i> View Full Obituary
            </button>
          </div>
        </div>
        <div class="obituary-content">
          <h3 class="obituary-name">${escapeHtml(obituary.name)}</h3>
          <div class="obituary-dates">
            <span class="birth-date">${escapeHtml(obituary.birthDate)}</span>
            <span class="date-separator">—</span>
            <span class="death-date">${escapeHtml(obituary.deathDate)}</span>
          </div>
          <div class="obituary-age">${obituary.age ? `${obituary.age} years old` : ""}</div>
          <p class="obituary-excerpt">${escapeHtml(obituary.excerpt)}</p>
          <div class="obituary-service-info">
            <div class="service-detail">
              <i class="bi bi-calendar-event"></i>
              <div>
                <strong>Wake:</strong>
                <span>${escapeHtml(obituary.wakeSchedule)}</span>
              </div>
            </div>
            <div class="service-detail">
              <i class="bi bi-building"></i>
              <div>
                <strong>Chapel:</strong>
                <span>${escapeHtml(obituary.chapel)}</span>
              </div>
            </div>
            <div class="service-detail">
              <i class="bi bi-clock"></i>
              <div>
                <strong>Viewing:</strong>
                <span>${escapeHtml(obituary.viewingHours)}</span>
              </div>
            </div>
          </div>
        </div>
        <div class="obituary-footer">
          <button class="btn btn-condolence" onclick="sendCondolence(${obituary.id})">
            <i class="bi bi-envelope-heart"></i> Send Condolences
          </button>
        </div>
      </div>
    `;

    container.appendChild(card);
  });
}

function getObituaryById(obituaryId) {
  const id = Number(obituaryId);
  return obituariesData.find((item) => Number(item.id) === id);
}

// Filter and search obituaries
function filterObituaries() {
  const searchTerm = document
    .getElementById("searchObituary")
    .value.toLowerCase();
  const sortFilter = document.getElementById("sortFilter").value;
  let visibleObituaries = obituariesData.filter((obituary) => {
    if (!searchTerm) return true;
    return (obituary.name || "").toLowerCase().includes(searchTerm);
  });

  // Sort obituaries
  if (sortFilter === "newest") {
    visibleObituaries.sort(
      (a, b) =>
        new Date(b.deathDateISO || b.deathDate || 0) -
        new Date(a.deathDateISO || a.deathDate || 0),
    );
  } else if (sortFilter === "oldest") {
    visibleObituaries.sort(
      (a, b) =>
        new Date(a.deathDateISO || a.deathDate || 0) -
        new Date(b.deathDateISO || b.deathDate || 0),
    );
  } else if (sortFilter === "name-asc") {
    visibleObituaries.sort((a, b) => a.name.localeCompare(b.name));
  } else if (sortFilter === "name-desc") {
    visibleObituaries.sort((a, b) => b.name.localeCompare(a.name));
  }

  renderObituaries(visibleObituaries);

  // Show/hide no results message
  const noResults = document.getElementById("no-results-obituaries");
  if (visibleObituaries.length === 0) {
    noResults.style.display = "block";
  } else {
    noResults.style.display = "none";
  }
}

// Reset filters
function resetObituaryFilters() {
  document.getElementById("searchObituary").value = "";
  document.getElementById("sortFilter").value = "newest";
  filterObituaries();
}

// View obituary details in modal
function viewObituaryDetails(obituaryId) {
  const obituary = getObituaryById(obituaryId);

  if (!obituary) return;

  const modalBody = document.getElementById("modalObituaryDetails");

  const survivedBy = Array.isArray(obituary.survivedBy)
    ? obituary.survivedBy
    : [];
  let survivedByHTML = "";
  if (survivedBy.length > 0) {
    survivedBy.forEach((person) => {
      survivedByHTML += `<li>${escapeHtml(person)}</li>`;
    });
    survivedByHTML = `
      <h3 class="obituary-section-title">Survived By</h3>
      <ul class="survived-by-list">
        ${survivedByHTML}
      </ul>
    `;
  }

  const biographyRaw = obituary.fullBiography || "";
  const biographyHtml = biographyRaw.trim().startsWith("<")
    ? biographyRaw
    : `<p>${escapeHtml(biographyRaw)}</p>`;
  const modalInitials = encodeURIComponent(getInitials(obituary.name));

  modalBody.innerHTML = `
        <div class="obituary-detail-view">
            <img src="${escapeHtml(obituary.image)}" 
                 alt="${escapeHtml(obituary.name)}" 
                 class="obituary-modal-image"
                 onerror="this.src='https://via.placeholder.com/300x300/2c3e50/ffffff?text=${modalInitials}'">
            
            <div class="obituary-modal-header">
                <h2 class="obituary-modal-name">${escapeHtml(obituary.name)}</h2>
                <div class="obituary-modal-dates">
                    ${escapeHtml(obituary.birthDate)} — ${escapeHtml(
                      obituary.deathDate,
                    )}
                </div>
                <div class="obituary-modal-age">${
                  obituary.age ? `${obituary.age} years old` : ""
                }</div>
            </div>
            
            <div class="obituary-full-content">
                ${biographyHtml}
                ${survivedByHTML}
                
                <div class="service-schedule-box">
                    <h4><i class="bi bi-calendar-event"></i> Service Information</h4>
                    <p><strong>Wake Schedule:</strong> ${escapeHtml(
                      obituary.wakeSchedule,
                    )}</p>
                    <p><strong>Chapel:</strong> ${escapeHtml(
                      obituary.chapel,
                    )}</p>
                    <p><strong>Viewing Hours:</strong> ${escapeHtml(
                      obituary.viewingHours,
                    )}</p>
                    <p><strong>Interment:</strong> ${escapeHtml(
                      obituary.interment,
                    )}</p>
                </div>
            </div>
        </div>
    `;

  const modal = new bootstrap.Modal(document.getElementById("obituaryModal"));
  modal.show();
}

// Send condolence (requires login)
function sendCondolence(obituaryId) {
  const modal = new bootstrap.Modal(document.getElementById("condolenceModal"));
  modal.show();
}

// Print obituary
function printObituary() {
  window.print();
}

// Real-time search
document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchObituary");
  const noResults = document.getElementById("no-results-obituaries");

  const relativeUrl = new URL("../PHP/get_obituaries.php", window.location.href);
  const absoluteUrl = `${window.location.origin}/DulceSystem1/Client/PHP/get_obituaries.php`;
  const urlCandidates = [relativeUrl.toString(), absoluteUrl];

  const fetchWithFallback = (urls) => {
    if (urls.length === 0) {
      throw new Error("No valid URL candidates for obituaries data.");
    }

    const [current, ...rest] = urls;

    return fetch(current)
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Failed to load obituaries from ${current}`);
        }
        return response.json();
      })
      .catch(() => {
        if (rest.length === 0) {
          throw new Error("All obituary data endpoints failed.");
        }
        return fetchWithFallback(rest);
      });
  };

  fetchWithFallback(urlCandidates)
    .then((data) => {
      obituariesData = Array.isArray(data) ? data : [];
      filterObituaries();
    })
    .catch((error) => {
      console.error(error);
      obituariesData = [];
      filterObituaries();
      if (noResults) {
        noResults.style.display = "block";
        noResults.querySelector("h3").textContent = "Unable to Load Obituaries";
        noResults.querySelector("p").textContent =
          "Please check your server URL or database connection.";
      }
    });

  // Debounce search
  let searchTimeout;
  searchInput.addEventListener("input", function () {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      filterObituaries();
    }, 300);
  });

  // Enter key support
  searchInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      filterObituaries();
    }
  });

  document
    .getElementById("sortFilter")
    .addEventListener("change", filterObituaries);
});

// DULCE - Announcements Page JavaScript

let announcementsData = [];

function escapeHtml(text) {
  const div = document.createElement("div");
  div.textContent = text ?? "";
  return div.innerHTML;
}

function getAnnouncementById(announcementId) {
  const id = Number(announcementId);
  return announcementsData.find((item) => Number(item.id) === id);
}

function renderAnnouncements() {
  const pinnedContainer = document.getElementById("pinned-container");
  const regularContainer = document.getElementById("regular-container");
  if (!pinnedContainer || !regularContainer) return;

  pinnedContainer.innerHTML = "";
  regularContainer.innerHTML = "";

  const pinnedItems = announcementsData.filter(
    (item) => (item.isPinned ?? item.is_pinned) == 1,
  );
  const regularItems = announcementsData.filter(
    (item) => (item.isPinned ?? item.is_pinned) != 1,
  );

  pinnedItems.forEach((item) => {
    const card = document.createElement("div");
    card.className = "col-12 announcement-item pinned";
    card.setAttribute("data-category", item.category);
    card.setAttribute("data-date", item.date || "");
    card.innerHTML = buildAnnouncementCard(item, true);
    pinnedContainer.appendChild(card);
  });

  regularItems.forEach((item) => {
    const card = document.createElement("div");
    card.className = "col-lg-6 announcement-item";
    card.setAttribute("data-category", item.category);
    card.setAttribute("data-date", item.date || "");
    card.innerHTML = buildAnnouncementCard(item, false);
    regularContainer.appendChild(card);
  });
}

function buildAnnouncementCard(item, isPinned) {
  const categoryLabel =
    item.category.charAt(0).toUpperCase() + item.category.slice(1);
  const categoryIcon = getCategoryIcon(item.category);
  const dateFormatted = item.dateFormatted || item.date;

  return `
    <div class="announcement-card ${isPinned ? "pinned-card" : ""}">
      ${isPinned ? `<div class="announcement-pin"><i class="bi bi-pin-fill"></i></div>` : ""}
      <div class="announcement-header">
        <div class="announcement-meta">
          <span class="announcement-category ${item.category}">
            <i class="bi ${categoryIcon}"></i> ${escapeHtml(categoryLabel)}
          </span>
          <span class="announcement-date">
            <i class="bi bi-calendar-event"></i> ${escapeHtml(dateFormatted)}
          </span>
        </div>
        <h3 class="announcement-title">${escapeHtml(item.title)}</h3>
      </div>
      <div class="announcement-content">
        <p>${item.excerpt || item.content || ""}</p>
      </div>
      <div class="announcement-footer">
        <button class="btn btn-read-more" onclick="viewAnnouncementDetails(${item.id})">
          <i class="bi bi-chevron-right"></i> Read More
        </button>
        <div class="announcement-actions">
          <span class="text-muted">
            <i class="bi bi-eye"></i> ${item.views || 0} views
          </span>
        </div>
      </div>
    </div>
  `;
}

// Filter and search announcements
function filterAnnouncements() {
  const searchTerm = document
    .getElementById("searchAnnouncement")
    .value.toLowerCase();
  const categoryFilter = document.getElementById("categoryFilter").value;
  const sortFilter = document.getElementById("sortFilter").value;
  const regularItems = announcementsData.filter(
    (a) => (a.isPinned ?? a.is_pinned) != 1,
  );

  let visibleAnnouncements = regularItems.filter((announcement) => {
    const category = announcement.category;
    const title = (announcement.title || "").toLowerCase();
    const content = (announcement.content || "").toLowerCase();

    if (categoryFilter !== "all" && category !== categoryFilter) {
      return false;
    }

    if (
      searchTerm &&
      !title.includes(searchTerm) &&
      !content.includes(searchTerm)
    ) {
      return false;
    }

    return true;
  });

  if (sortFilter === "newest") {
    visibleAnnouncements.sort((a, b) => new Date(b.date) - new Date(a.date));
  } else {
    visibleAnnouncements.sort((a, b) => new Date(a.date) - new Date(b.date));
  }

  const regularContainer = document.getElementById("regular-container");
  if (regularContainer) {
    regularContainer.innerHTML = "";
    visibleAnnouncements.forEach((item) => {
      const card = document.createElement("div");
      card.className = "col-lg-6 announcement-item";
      card.setAttribute("data-category", item.category);
      card.setAttribute("data-date", item.date || "");
      card.innerHTML = buildAnnouncementCard(item, false);
      regularContainer.appendChild(card);
    });
  }

  const noResults = document.getElementById("no-results-announcements");
  if (noResults) {
    noResults.style.display = visibleAnnouncements.length === 0 ? "block" : "none";
  }
}

// Reset filters
function resetAnnouncementFilters() {
  document.getElementById("searchAnnouncement").value = "";
  document.getElementById("categoryFilter").value = "all";
  document.getElementById("sortFilter").value = "newest";
  filterAnnouncements();
}

// View announcement details in modal
function viewAnnouncementDetails(announcementId) {
  const announcement = getAnnouncementById(announcementId);

  if (!announcement) return;

  const modalBody = document.getElementById("modalAnnouncementDetails");

  const categoryClass = announcement.category;
  const categoryIcon = getCategoryIcon(announcement.category);
  const categoryLabel =
    announcement.category.charAt(0).toUpperCase() +
    announcement.category.slice(1);

  modalBody.innerHTML = `
        <div class="announcement-detail-view">
            <div class="announcement-modal-header">
                <div class="announcement-meta mb-3">
                    <span class="announcement-category ${categoryClass}">
                        <i class="bi ${categoryIcon}"></i> ${categoryLabel}
                    </span>
                    <span class="announcement-date">
                        <i class="bi bi-calendar-event"></i> ${announcement.dateFormatted}
                    </span>
                </div>
                <h3 class="text-primary mb-2">${announcement.title}</h3>
                <div class="text-muted">
                    <small>
                        <i class="bi bi-person-circle"></i> Posted by ${announcement.author} | 
                        <i class="bi bi-eye"></i> ${announcement.views} views
                    </small>
                </div>
            </div>
            
            <div class="announcement-modal-content">
                ${announcement.content}
            </div>
            
            <div class="announcement-modal-meta">
                <small class="text-muted">
                    <i class="bi bi-clock-history"></i> Last updated: ${announcement.dateFormatted}
                </small>
            </div>
        </div>
    `;

  const modal = new bootstrap.Modal(
    document.getElementById("announcementModal"),
  );
  modal.show();

  // Increment view count in database
  fetch("../PHP/incrementAnnouncementViews.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `id=${encodeURIComponent(announcement.id)}`,
  }).then(() => {
    announcement.views = (announcement.views || 0) + 1;
    renderAnnouncements();
  });
}

// Get category icon
function getCategoryIcon(category) {
  const icons = {
    general: "bi-info-circle-fill",
    service: "bi-gear-fill",
    holiday: "bi-calendar-heart-fill",
    important: "bi-exclamation-circle-fill",
  };
  return icons[category] || "bi-info-circle-fill";
}

// Real-time search
document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchAnnouncement");

  fetch("../PHP/getAnnouncements.php")
    .then((response) => response.json())
    .then((data) => {
      announcementsData = Array.isArray(data) ? data : [];
      renderAnnouncements();
      filterAnnouncements();
    })
    .catch(() => {
      announcementsData = [];
      renderAnnouncements();
      filterAnnouncements();
    });

  // Debounce search
  let searchTimeout;
  searchInput.addEventListener("input", function () {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      filterAnnouncements();
    }, 300);
  });

  // Enter key support for filters
  searchInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      filterAnnouncements();
    }
  });

  document
    .getElementById("categoryFilter")
    .addEventListener("change", filterAnnouncements);
  document
    .getElementById("sortFilter")
    .addEventListener("change", filterAnnouncements);
});

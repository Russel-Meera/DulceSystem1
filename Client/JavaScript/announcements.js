// DULCE - Announcements Page JavaScript

// Sample announcement data (In production, this will come from database via admin panel)
const announcementsData = {
  1: {
    id: 1,
    title: "Extended Operating Hours for the Holiday Season",
    category: "important",
    date: "2025-01-25",
    dateFormatted: "January 25, 2025",
    views: 245,
    isPinned: true,
    excerpt:
      "We are pleased to announce that DULCE Funeral Services will be extending our operating hours during the upcoming holiday season to better serve our clients.",
    content: `
            <p>We are pleased to announce that DULCE Funeral Services will be extending our operating hours during the upcoming holiday season to better serve our clients. Our dedicated team will be available 24/7 to provide compassionate support and professional services when you need us most.</p>
            
            <h5 class="mt-4 mb-3">Extended Hours Schedule:</h5>
            <ul>
                <li><strong>December 20 - January 5:</strong> 24/7 Operations</li>
                <li><strong>Emergency Services:</strong> Always Available</li>
                <li><strong>Regular Office Hours:</strong> 8:00 AM - 10:00 PM</li>
            </ul>
            
            <p class="mt-3">For any inquiries or immediate assistance, please don't hesitate to contact us at any time. We remain committed to serving you with dignity and respect during this season and always.</p>
            
            <p class="mt-3"><strong>Contact Information:</strong><br>
            Phone: (049) 123-4567<br>
            Email: info@dulcefuneral.com<br>
            24/7 Hotline: 0917-123-4567</p>
        `,
    author: "DULCE Admin",
  },
  2: {
    id: 2,
    title: "New Online Payment Options Available",
    category: "service",
    date: "2025-01-22",
    dateFormatted: "January 22, 2025",
    views: 189,
    isPinned: false,
    excerpt:
      "We're excited to announce new convenient payment options for our services.",
    content: `
            <p>We're excited to announce new convenient payment options for our services. Clients can now pay through multiple channels including GCash, PayMaya, bank transfer, and credit/debit cards through our secure online payment system.</p>
            
            <h5 class="mt-4 mb-3">Available Payment Methods:</h5>
            <ul>
                <li><strong>GCash:</strong> Instant payment processing</li>
                <li><strong>PayMaya:</strong> Quick and secure transactions</li>
                <li><strong>Bank Transfer:</strong> Direct bank-to-bank payments</li>
                <li><strong>Credit/Debit Cards:</strong> Visa, Mastercard, and more</li>
                <li><strong>Over-the-Counter:</strong> Payment centers nationwide</li>
            </ul>
            
            <p class="mt-3">All online transactions are secured with industry-standard encryption to protect your financial information. You will receive instant confirmation and digital receipts for all payments made.</p>
            
            <p class="mt-3">To use these new payment options, simply log in to your account and navigate to the billing section. For assistance, our support team is available 24/7.</p>
        `,
    author: "DULCE Admin",
  },
  3: {
    id: 3,
    title: "Virtual Memorial Services Now Available",
    category: "general",
    date: "2025-01-20",
    dateFormatted: "January 20, 2025",
    views: 156,
    isPinned: false,
    excerpt:
      "In response to client needs, we now offer live streaming services for memorial ceremonies.",
    content: `
            <p>In response to client needs, we now offer live streaming services for memorial ceremonies. Family and friends who cannot attend in person can participate remotely through our secure online platform.</p>
            
            <h5 class="mt-4 mb-3">Virtual Service Features:</h5>
            <ul>
                <li><strong>HD Live Streaming:</strong> Crystal clear video and audio</li>
                <li><strong>Interactive Participation:</strong> Virtual condolence messages</li>
                <li><strong>Recording Access:</strong> View the service later</li>
                <li><strong>Multi-device Support:</strong> Watch on any device</li>
                <li><strong>Private Links:</strong> Secure access for invited guests only</li>
            </ul>
            
            <p class="mt-3">This service is available for all our chapel facilities and can be added to any funeral package. Our technical team ensures smooth streaming and provides support throughout the service.</p>
            
            <p class="mt-3">For more information about virtual memorial services, please contact our team or inquire during your booking process.</p>
        `,
    author: "DULCE Admin",
  },
  4: {
    id: 4,
    title: "Schedule Updates for Upcoming Holidays",
    category: "holiday",
    date: "2025-01-15",
    dateFormatted: "January 15, 2025",
    views: 312,
    isPinned: false,
    excerpt:
      "Please be informed of our adjusted operating schedules for the upcoming national holidays.",
    content: `
            <p>Please be informed of our adjusted operating schedules for the upcoming national holidays. Emergency services remain available 24/7. Regular services will resume on the specified dates.</p>
            
            <h5 class="mt-4 mb-3">Holiday Schedule:</h5>
            <ul>
                <li><strong>Chinese New Year (January 29):</strong> Limited office hours (9 AM - 5 PM)</li>
                <li><strong>EDSA Revolution (February 25):</strong> Regular hours</li>
                <li><strong>Good Friday (April 18):</strong> Emergency services only</li>
                <li><strong>Easter Sunday (April 20):</strong> Limited services</li>
            </ul>
            
            <p class="mt-3"><strong>Important Notes:</strong></p>
            <ul>
                <li>Emergency funeral services are available 24/7 during all holidays</li>
                <li>Chapel bookings can be made online anytime</li>
                <li>Our hotline (0917-123-4567) is always active</li>
                <li>Online payment processing continues without interruption</li>
            </ul>
            
            <p class="mt-3">We appreciate your understanding and wish you and your families a peaceful holiday season.</p>
        `,
    author: "DULCE Admin",
  },
  5: {
    id: 5,
    title: "New Chapel Renovation Completed",
    category: "service",
    date: "2025-01-10",
    dateFormatted: "January 10, 2025",
    views: 278,
    isPinned: false,
    excerpt:
      "We're proud to announce the completion of our Harmony Hall renovation.",
    content: `
            <p>We're proud to announce the completion of our Harmony Hall renovation. The chapel now features enhanced audio-visual equipment, improved seating, and upgraded air conditioning for your comfort.</p>
            
            <h5 class="mt-4 mb-3">New Features Include:</h5>
            <ul>
                <li><strong>4K Display Screens:</strong> High-definition memorial presentations</li>
                <li><strong>Premium Sound System:</strong> Crystal clear audio throughout</li>
                <li><strong>Comfortable Seating:</strong> Ergonomic chairs for 150+ guests</li>
                <li><strong>Enhanced AC System:</strong> Optimal temperature control</li>
                <li><strong>LED Lighting:</strong> Adjustable ambient lighting</li>
                <li><strong>Modern Interiors:</strong> Elegant and peaceful design</li>
            </ul>
            
            <p class="mt-3">The renovation was completed with your comfort and the dignity of memorial services in mind. Harmony Hall is now available for booking and offers one of the finest chapel experiences in the region.</p>
            
            <p class="mt-3">We invite you to visit and experience the improvements firsthand. Schedule a viewing through our online booking system or contact us for a personal tour.</p>
        `,
    author: "DULCE Admin",
  },
  6: {
    id: 6,
    title: "Community Outreach Program",
    category: "general",
    date: "2025-01-05",
    dateFormatted: "January 5, 2025",
    views: 198,
    isPinned: false,
    excerpt:
      "DULCE Funeral Services is launching a community outreach program.",
    content: `
            <p>DULCE Funeral Services is launching a community outreach program to provide grief counseling and support services to families in need. We believe in giving back to the community that has trusted us over the years.</p>
            
            <h5 class="mt-4 mb-3">Program Highlights:</h5>
            <ul>
                <li><strong>Free Grief Counseling:</strong> Professional support for bereaved families</li>
                <li><strong>Support Groups:</strong> Connect with others experiencing loss</li>
                <li><strong>Educational Workshops:</strong> Understanding the grieving process</li>
                <li><strong>Community Events:</strong> Memorial gatherings and remembrance activities</li>
                <li><strong>Resource Library:</strong> Books and materials on coping with loss</li>
            </ul>
            
            <p class="mt-3">All services under this program are provided free of charge. Our team of licensed counselors and grief specialists are here to help you navigate through difficult times.</p>
            
            <p class="mt-3"><strong>How to Participate:</strong><br>
            Contact our office to schedule a counseling session or join a support group. Sessions are available both in-person and online to accommodate your needs.</p>
            
            <p class="mt-3">More details will be shared in the coming weeks. Stay tuned for updates.</p>
        `,
    author: "DULCE Admin",
  },
  7: {
    id: 7,
    title: "Happy New Year from DULCE",
    category: "general",
    date: "2025-01-01",
    dateFormatted: "January 1, 2025",
    views: 421,
    isPinned: false,
    excerpt:
      "As we welcome the new year, DULCE Funeral Services extends warm wishes to all our clients and their families.",
    content: `
            <p>As we welcome the new year, DULCE Funeral Services extends warm wishes to all our clients and their families. Thank you for trusting us with your needs during life's most difficult moments. We remain committed to serving you with compassion, dignity, and respect.</p>
            
            <h5 class="mt-4 mb-3">Looking Forward to 2025:</h5>
            <p>This year, we are committed to:</p>
            <ul>
                <li><strong>Enhanced Services:</strong> Continued improvements in all our offerings</li>
                <li><strong>Technology Integration:</strong> Making services more accessible online</li>
                <li><strong>Community Support:</strong> Expanding our outreach programs</li>
                <li><strong>Staff Training:</strong> Ensuring the highest level of care</li>
                <li><strong>Facility Upgrades:</strong> Maintaining excellent chapel conditions</li>
            </ul>
            
            <p class="mt-3">We are honored to serve our community and grateful for the trust you place in us. Here's to a year of healing, remembrance, and support for all families we serve.</p>
            
            <p class="mt-3">May this new year bring peace and comfort to all who have experienced loss. We are here for you, every step of the way.</p>
            
            <p class="mt-4"><em>With deepest gratitude,<br>
            The DULCE Funeral Services Team</em></p>
        `,
    author: "DULCE Admin",
  },
};

// Filter and search announcements
function filterAnnouncements() {
  const searchTerm = document
    .getElementById("searchAnnouncement")
    .value.toLowerCase();
  const categoryFilter = document.getElementById("categoryFilter").value;
  const sortFilter = document.getElementById("sortFilter").value;
  const announcements = document.querySelectorAll(
    ".announcement-item:not(.pinned)",
  );

  let visibleAnnouncements = [];

  announcements.forEach((announcement) => {
    const category = announcement.getAttribute("data-category");
    const title = announcement
      .querySelector(".announcement-title")
      .textContent.toLowerCase();
    const content = announcement
      .querySelector(".announcement-content")
      .textContent.toLowerCase();

    let showAnnouncement = true;

    // Category filter
    if (categoryFilter !== "all" && category !== categoryFilter) {
      showAnnouncement = false;
    }

    // Search filter
    if (
      searchTerm &&
      !title.includes(searchTerm) &&
      !content.includes(searchTerm)
    ) {
      showAnnouncement = false;
    }

    if (showAnnouncement) {
      announcement.style.display = "block";
      visibleAnnouncements.push({
        element: announcement,
        date: announcement.getAttribute("data-date"),
      });
    } else {
      announcement.style.display = "none";
    }
  });

  // Sort announcements
  if (sortFilter === "newest") {
    visibleAnnouncements.sort((a, b) => new Date(b.date) - new Date(a.date));
  } else {
    visibleAnnouncements.sort((a, b) => new Date(a.date) - new Date(b.date));
  }

  // Reorder in DOM
  const container = document.querySelector("#announcements-container .row");
  visibleAnnouncements.forEach((item) => {
    container.appendChild(item.element);
  });

  // Show/hide no results message
  const noResults = document.getElementById("no-results-announcements");
  if (visibleAnnouncements.length === 0) {
    noResults.style.display = "block";
  } else {
    noResults.style.display = "none";
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
  const announcement = announcementsData[announcementId];

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

  // Increment view count (in production, this would update the database)
  announcement.views++;
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

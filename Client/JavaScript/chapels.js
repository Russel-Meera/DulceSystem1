// DULCE - Chapel Services Page JavaScript

// Sample chapel data (In production, this will come from database via admin panel)
const chapelsData = {
  1: {
    name: "Serenity Chapel",
    capacity: "80 people",
    capacityCategory: "medium",
    image:
      "https://images.unsplash.com/photo-1438032005730-c779502df39b?w=800&h=500&fit=crop",
    description:
      "A peaceful and comfortable air-conditioned chapel perfect for intimate memorial services. Features modern amenities and elegant interiors designed to provide comfort during difficult times.",
    features: [
      { icon: "bi-snow", name: "Air-conditioned", category: "airconditioned" },
      { icon: "bi-car-front-fill", name: "Parking Space", category: "parking" },
      {
        icon: "bi-speaker-fill",
        name: "Sound System",
        category: "audiovisual",
      },
      { icon: "bi-display", name: "LED Screen", category: "audiovisual" },
      { icon: "bi-wifi", name: "WiFi Available", category: "wifi" },
      { icon: "bi-cup-hot-fill", name: "Coffee Area", category: "amenity" },
    ],
    details:
      "Perfect for families seeking a comfortable and serene environment. The Serenity Chapel combines modern technology with traditional comfort, ensuring your memorial service is conducted with dignity and respect.",
  },
  2: {
    name: "Harmony Hall",
    capacity: "150 people",
    capacityCategory: "large",
    image:
      "https://images.unsplash.com/photo-1438032005730-c779502df39b?w=800&h=500&fit=crop",
    description:
      "Our largest and most elegant chapel featuring premium amenities and spacious interiors. Ideal for larger gatherings with state-of-the-art facilities and comfortable seating arrangements.",
    features: [
      { icon: "bi-snow", name: "Premium AC", category: "airconditioned" },
      { icon: "bi-car-front-fill", name: "Large Parking", category: "parking" },
      {
        icon: "bi-speaker-fill",
        name: "Pro Audio System",
        category: "audiovisual",
      },
      { icon: "bi-tv", name: "Multiple Screens", category: "audiovisual" },
      { icon: "bi-wifi", name: "High-Speed WiFi", category: "wifi" },
      {
        icon: "bi-reception-4",
        name: "Live Streaming",
        category: "audiovisual",
      },
      { icon: "bi-cup-straw", name: "Refreshment Area", category: "amenity" },
      { icon: "bi-door-open", name: "Private Lounge", category: "amenity" },
    ],
    details:
      "Our premium chapel designed for larger memorial services. Harmony Hall offers exceptional comfort and advanced technology, including live streaming capabilities for remote attendees.",
  },
  3: {
    name: "Grace Chapel",
    capacity: "50 people",
    capacityCategory: "small",
    image:
      "https://images.unsplash.com/photo-1519741497674-611481863552?w=800&h=500&fit=crop",
    description:
      "An intimate and cozy chapel perfect for small family gatherings. Features essential amenities in a warm and comforting environment designed for privacy and solace.",
    features: [
      { icon: "bi-snow", name: "Air-conditioned", category: "airconditioned" },
      {
        icon: "bi-speaker-fill",
        name: "Sound System",
        category: "audiovisual",
      },
      { icon: "bi-display", name: "TV Screen", category: "audiovisual" },
      { icon: "bi-cup-hot-fill", name: "Coffee Station", category: "amenity" },
    ],
    details:
      "Ideal for intimate family services. Grace Chapel provides a warm, private setting where families can gather in comfort and peace.",
  },
  4: {
    name: "Tranquility Suite",
    capacity: "250 people",
    capacityCategory: "xlarge",
    image:
      "https://images.unsplash.com/photo-1478860409698-8707f313ee8b?w=800&h=500&fit=crop",
    description:
      "Our most prestigious chapel suite offering luxurious facilities and exceptional amenities. Perfect for large memorial services with VIP accommodations and comprehensive media capabilities.",
    features: [
      { icon: "bi-snow", name: "Climate Control", category: "airconditioned" },
      { icon: "bi-car-front-fill", name: "VIP Parking", category: "parking" },
      {
        icon: "bi-speaker-fill",
        name: "Premium Sound",
        category: "audiovisual",
      },
      {
        icon: "bi-projector",
        name: "Projector System",
        category: "audiovisual",
      },
      { icon: "bi-wifi", name: "Premium WiFi", category: "wifi" },
      {
        icon: "bi-camera-video",
        name: "Video Recording",
        category: "audiovisual",
      },
      { icon: "bi-broadcast", name: "Live Streaming", category: "audiovisual" },
      { icon: "bi-door-closed", name: "Multiple Lounges", category: "amenity" },
      { icon: "bi-cup-straw", name: "Catering Area", category: "amenity" },
      {
        icon: "bi-person-badge",
        name: "Concierge Service",
        category: "amenity",
      },
    ],
    details:
      "The ultimate chapel experience with luxury amenities and comprehensive services. Tranquility Suite is designed for prestigious memorial services requiring the finest facilities.",
  },
};

// Filter chapels
function filterChapels() {
  const capacityFilter = document.getElementById("capacityFilter").value;
  const featureFilter = document.getElementById("featureFilter").value;
  const chapels = document.querySelectorAll(".chapel-item");
  let visibleCount = 0;

  chapels.forEach((chapel) => {
    const capacity = chapel.getAttribute("data-capacity");
    const features = chapel.getAttribute("data-features").split(",");
    let showChapel = true;

    // Capacity filter
    if (capacityFilter !== "all" && capacity !== capacityFilter) {
      showChapel = false;
    }

    // Feature filter
    if (featureFilter !== "all" && !features.includes(featureFilter)) {
      showChapel = false;
    }

    // Show/hide chapel
    if (showChapel) {
      chapel.style.display = "block";
      visibleCount++;
    } else {
      chapel.style.display = "none";
    }
  });

  // Show/hide no results message
  const noResults = document.getElementById("no-results-chapel");
  if (visibleCount === 0) {
    noResults.style.display = "block";
  } else {
    noResults.style.display = "none";
  }
}

// Reset filters
function resetChapelFilters() {
  document.getElementById("capacityFilter").value = "all";
  document.getElementById("featureFilter").value = "all";
  filterChapels();
}

// View chapel details in modal
function viewChapelDetails(chapelId) {
  const chapel = chapelsData[chapelId];

  if (!chapel) return;

  const modalBody = document.getElementById("modalChapelDetails");

  let featuresHTML = "";
  chapel.features.forEach((feature) => {
    featuresHTML += `
            <div class="col-md-4 col-sm-6">
                <div class="detail-feature-card">
                    <i class="bi ${feature.icon}"></i>
                    <div>${feature.name}</div>
                </div>
            </div>
        `;
  });

  modalBody.innerHTML = `
        <div class="chapel-detail-view">
            <img src="${chapel.image}" 
                 alt="${chapel.name}" 
                 class="modal-chapel-image"
                 onerror="this.src='https://via.placeholder.com/800x400/2c3e50/ffffff?text=${encodeURIComponent(chapel.name)}'">
            
            <div class="text-center mb-4">
                <h3 class="text-primary mb-2">${chapel.name}</h3>
                <div class="h5 text-secondary">
                    <i class="bi bi-people-fill"></i> Capacity: ${chapel.capacity}
                </div>
            </div>
            
            <div class="mb-4">
                <h5 class="text-secondary mb-3">Description</h5>
                <p class="text-muted">${chapel.description}</p>
                <p class="text-muted">${chapel.details}</p>
            </div>
            
            <div>
                <h5 class="text-secondary mb-3">Chapel Features & Amenities</h5>
                <div class="row g-3 chapel-detail-grid">
                    ${featuresHTML}
                </div>
            </div>
        </div>
    `;

  const modal = new bootstrap.Modal(document.getElementById("chapelModal"));
  modal.show();
}

// Initialize on page load
document.addEventListener("DOMContentLoaded", function () {
  // Add enter key support for filters
  document
    .getElementById("capacityFilter")
    .addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        filterChapels();
      }
    });

  document
    .getElementById("featureFilter")
    .addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        filterChapels();
      }
    });
});

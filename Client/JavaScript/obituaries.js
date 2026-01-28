// DULCE - Obituaries Page JavaScript

// Sample obituary data (In production, this will come from database via admin panel)
const obituariesData = {
  1: {
    id: 1,
    name: "Maria Santos Cruz",
    birthDate: "March 15, 1945",
    deathDate: "January 18, 2025",
    age: 79,
    image:
      "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400&h=400&fit=crop",
    excerpt:
      "Beloved mother, grandmother, and friend. Maria dedicated her life to her family and community, touching countless lives with her warmth and kindness.",
    fullBiography: `
            <p>Maria Santos Cruz, 79, passed away peacefully on January 18, 2025, surrounded by her loving family. Born on March 15, 1945, in Alaminos, Laguna, Maria was a pillar of strength and compassion in her community.</p>
            
            <p>Throughout her life, Maria dedicated herself to her family and community service. She was known for her warm smile, generous heart, and unwavering faith. As a devoted mother and grandmother, she created a home filled with love, laughter, and cherished memories.</p>
            
            <p>Maria was an active member of her local church for over 50 years, where she led various charitable initiatives and touched countless lives through her volunteer work. Her legacy of kindness and compassion will continue to inspire all who knew her.</p>
            
            <div class="memorial-quote">
                <p>"A life lived with love, faith, and service to others is a life well-lived. Maria embodied these values every single day, and her memory will forever remain in our hearts."</p>
            </div>
        `,
    survivedBy: [
      "Husband: Ricardo Cruz",
      "Children: Ana Maria Cruz-Torres, Jose Santos Cruz Jr., Elena Cruz-Mendoza",
      "Grandchildren: 8 beloved grandchildren",
      "Siblings: Rosa Santos-Garcia, Pedro Santos",
    ],
    wakeSchedule: "January 20-22, 2025",
    chapel: "Serenity Chapel",
    viewingHours: "9:00 AM - 9:00 PM",
    interment: "January 23, 2025, 10:00 AM at Alaminos Memorial Park",
  },
  2: {
    id: 2,
    name: "Roberto Miguel Reyes",
    birthDate: "July 8, 1960",
    deathDate: "January 16, 2025",
    age: 64,
    image:
      "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=400&fit=crop",
    excerpt:
      "Loving husband, devoted father, and respected educator. Roberto's passion for teaching and dedication to his students made a lasting impact on the community.",
    fullBiography: `
            <p>Roberto Miguel Reyes, 64, beloved educator and family man, passed away on January 16, 2025. Born on July 8, 1960, Roberto dedicated over 40 years to education, shaping the minds and hearts of countless students.</p>
            
            <p>As a high school mathematics teacher and later as principal, Roberto was known for his innovative teaching methods and genuine care for his students' success. He believed that education was the key to unlocking potential and worked tirelessly to ensure every student had the opportunity to excel.</p>
            
            <p>Beyond his professional achievements, Roberto was a devoted husband, loving father, and proud grandfather. He enjoyed playing guitar, reading poetry, and spending quality time with his family. His warmth, wisdom, and sense of humor will be deeply missed.</p>
            
            <div class="memorial-quote">
                <p>"Education is not the filling of a pail, but the lighting of a fire." - This was Roberto's guiding philosophy, and he lit countless fires in the hearts of his students.</p>
            </div>
        `,
    survivedBy: [
      "Wife: Patricia Reyes",
      "Children: Miguel Antonio Reyes, Sofia Reyes-Santos, Lucas Roberto Reyes",
      "Grandchildren: 5 grandchildren",
      "Siblings: Carmen Reyes-Lopez, Fernando Reyes",
    ],
    wakeSchedule: "January 18-20, 2025",
    chapel: "Harmony Hall",
    viewingHours: "8:00 AM - 10:00 PM",
    interment: "January 21, 2025, 9:00 AM at Holy Cross Cemetery",
  },
  3: {
    id: 3,
    name: "Elena Beatriz Hernandez",
    birthDate: "November 22, 1952",
    deathDate: "January 13, 2025",
    age: 72,
    image:
      "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&h=400&fit=crop",
    excerpt:
      "Cherished wife, mother of three, and devoted grandmother. Elena's love for cooking and hospitality brought joy to everyone who knew her.",
    fullBiography: `
            <p>Elena Beatriz Hernandez, 72, passed away peacefully on January 13, 2025, after a courageous battle with illness. Born on November 22, 1952, Elena was known throughout the community for her incredible cooking and warm hospitality.</p>
            
            <p>Elena's kitchen was the heart of her home, where family and friends gathered to share meals and create memories. Her famous recipes and generous spirit made every occasion special. She took pride in passing down her culinary traditions to her children and grandchildren.</p>
            
            <p>A devoted wife for 50 years and a loving mother, Elena's greatest joy was her family. She created a home filled with love, laughter, and the irresistible aroma of home-cooked meals. Her legacy lives on in the hearts of all who experienced her warmth and generosity.</p>
            
            <div class="memorial-quote">
                <p>"The fondest memories are made when gathered around the table." - Elena lived by these words, bringing people together through her love and cooking.</p>
            </div>
        `,
    survivedBy: [
      "Husband: Carlos Hernandez",
      "Children: Maria Elena Hernandez-Cruz, Carlos Jr. Hernandez, Isabel Hernandez-Diaz",
      "Grandchildren: 7 grandchildren",
      "Siblings: Rosa Beatriz Santos, Antonio Beatriz",
    ],
    wakeSchedule: "January 15-17, 2025",
    chapel: "Grace Chapel",
    viewingHours: "10:00 AM - 8:00 PM",
    interment: "January 18, 2025, 2:00 PM at St. Mary's Cemetery",
  },
  4: {
    id: 4,
    name: "Jose Antonio Diaz",
    birthDate: "April 3, 1938",
    deathDate: "January 10, 2025",
    age: 86,
    image:
      "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=400&fit=crop",
    excerpt:
      "Beloved patriarch, war veteran, and community leader. Jose's wisdom, strength, and generosity inspired generations of family and friends.",
    fullBiography: `
            <p>Jose Antonio Diaz, 86, patriarch of the Diaz family, passed away on January 10, 2025. Born on April 3, 1938, Jose was a man of honor, courage, and unwavering integrity who served his country and community with distinction.</p>
            
            <p>As a decorated war veteran, Jose exemplified bravery and sacrifice. After his military service, he dedicated himself to community development, serving as a barangay captain for 20 years and helping to transform his community through various infrastructure and education projects.</p>
            
            <p>Jose was a loving husband for 60 years, a devoted father, and a cherished grandfather. His wisdom, strength, and generosity inspired everyone around him. He was known for his sharp wit, storytelling abilities, and the life lessons he shared with younger generations.</p>
            
            <div class="memorial-quote">
                <p>"A man's true wealth is measured not by what he accumulates, but by what he gives." - Jose lived a life of service and generosity that enriched countless others.</p>
            </div>
        `,
    survivedBy: [
      "Wife: Remedios Diaz",
      "Children: 6 children",
      "Grandchildren: 15 grandchildren",
      "Great-grandchildren: 8 great-grandchildren",
      "Siblings: 3 siblings",
    ],
    wakeSchedule: "January 12-15, 2025",
    chapel: "Tranquility Suite",
    viewingHours: "24 Hours",
    interment: "January 16, 2025, 10:00 AM at Veterans Memorial Cemetery",
  },
  5: {
    id: 5,
    name: "Carmen Gloria Mendez",
    birthDate: "September 12, 1968",
    deathDate: "January 6, 2025",
    age: 56,
    image:
      "https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=400&h=400&fit=crop",
    excerpt:
      "Compassionate nurse, loving mother, and devoted friend. Carmen's caring spirit and selfless dedication to helping others will be deeply missed.",
    fullBiography: `
            <p>Carmen Gloria Mendez, 56, a compassionate nurse and loving mother, passed away unexpectedly on January 6, 2025. Born on September 12, 1968, Carmen dedicated her life to caring for others, both professionally and personally.</p>
            
            <p>For over 30 years, Carmen served as a nurse in various hospitals and community health centers. She was known for her gentle bedside manner, tireless work ethic, and genuine concern for her patients' wellbeing. Many families remember her as the nurse who went above and beyond to provide comfort during difficult times.</p>
            
            <p>As a mother, Carmen was nurturing, supportive, and endlessly devoted to her children's happiness and success. She balanced her demanding career with being present for every important moment in their lives. Her legacy of compassion and service continues through her children and all whose lives she touched.</p>
            
            <div class="memorial-quote">
                <p>"Nursing is not just a profession; it's a calling to serve with compassion and dignity." - Carmen answered this calling every day of her life.</p>
            </div>
        `,
    survivedBy: [
      "Children: Andrea Mendez, Gabriel Mendez, Sofia Mendez",
      "Mother: Gloria Reyes",
      "Siblings: Roberto Mendez, Maria Mendez-Cruz",
      "Partner: Dr. Luis Santos",
    ],
    wakeSchedule: "January 8-10, 2025",
    chapel: "Serenity Chapel",
    viewingHours: "9:00 AM - 9:00 PM",
    interment: "January 11, 2025, 3:00 PM at Garden of Peace Memorial Park",
  },
  6: {
    id: 6,
    name: "Fernando Luis Garcia",
    birthDate: "January 28, 1975",
    deathDate: "January 3, 2025",
    age: 49,
    image:
      "https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=400&h=400&fit=crop",
    excerpt:
      "Talented musician, devoted husband, and proud father. Fernando's love for music and passion for life touched everyone who had the privilege of knowing him.",
    fullBiography: `
            <p>Fernando Luis Garcia, 49, talented musician and beloved family man, passed away tragically on January 3, 2025. Born on January 28, 1975, Fernando lived a life filled with music, love, and joy that he generously shared with everyone around him.</p>
            
            <p>As a professional guitarist and music teacher, Fernando inspired countless students to pursue their passion for music. He performed with various bands throughout his career and was known for his versatility and soulful performances. Music wasn't just his profession—it was his way of expressing love and connecting with others.</p>
            
            <p>Fernando was a devoted husband who loved his wife deeply and a proud father who encouraged his children to follow their dreams. His home was always filled with music, laughter, and love. He believed in living life to the fullest and making every moment count.</p>
            
            <div class="memorial-quote">
                <p>"Music gives a soul to the universe, wings to the mind, and life to everything." - Fernando embodied this philosophy, bringing beauty and joy to the world through his music.</p>
            </div>
        `,
    survivedBy: [
      "Wife: Isabella Garcia",
      "Children: Luis Fernando Garcia, Maya Isabella Garcia",
      "Parents: Luis Garcia Sr., Carmen Garcia",
      "Siblings: Marco Garcia, Lucia Garcia-Mendez",
    ],
    wakeSchedule: "January 5-7, 2025",
    chapel: "Harmony Hall",
    viewingHours: "8:00 AM - 10:00 PM",
    interment: "January 8, 2025, 11:00 AM at Eternal Gardens",
  },
};

// Filter and search obituaries
function filterObituaries() {
  const searchTerm = document
    .getElementById("searchObituary")
    .value.toLowerCase();
  const sortFilter = document.getElementById("sortFilter").value;
  const obituaries = document.querySelectorAll(".obituary-item");

  let visibleObituaries = [];

  obituaries.forEach((obituary) => {
    const name = obituary.getAttribute("data-name").toLowerCase();

    let showObituary = true;

    // Search filter
    if (searchTerm && !name.includes(searchTerm)) {
      showObituary = false;
    }

    if (showObituary) {
      obituary.style.display = "block";
      visibleObituaries.push({
        element: obituary,
        name: obituary.getAttribute("data-name"),
        date: obituary.getAttribute("data-date"),
      });
    } else {
      obituary.style.display = "none";
    }
  });

  // Sort obituaries
  if (sortFilter === "newest") {
    visibleObituaries.sort((a, b) => new Date(b.date) - new Date(a.date));
  } else if (sortFilter === "oldest") {
    visibleObituaries.sort((a, b) => new Date(a.date) - new Date(b.date));
  } else if (sortFilter === "name-asc") {
    visibleObituaries.sort((a, b) => a.name.localeCompare(b.name));
  } else if (sortFilter === "name-desc") {
    visibleObituaries.sort((a, b) => b.name.localeCompare(a.name));
  }

  // Reorder in DOM
  const container = document.querySelector("#obituaries-container");
  visibleObituaries.forEach((item) => {
    container.appendChild(item.element);
  });

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
  const obituary = obituariesData[obituaryId];

  if (!obituary) return;

  const modalBody = document.getElementById("modalObituaryDetails");

  let survivedByHTML = "";
  obituary.survivedBy.forEach((person) => {
    survivedByHTML += `<li>${person}</li>`;
  });

  modalBody.innerHTML = `
        <div class="obituary-detail-view">
            <img src="${obituary.image}" 
                 alt="${obituary.name}" 
                 class="obituary-modal-image"
                 onerror="this.src='https://via.placeholder.com/300x300/2c3e50/ffffff?text=${encodeURIComponent(
                   obituary.name
                     .split(" ")
                     .map((n) => n[0])
                     .join(""),
                 )}'">
            
            <div class="obituary-modal-header">
                <h2 class="obituary-modal-name">${obituary.name}</h2>
                <div class="obituary-modal-dates">
                    ${obituary.birthDate} — ${obituary.deathDate}
                </div>
                <div class="obituary-modal-age">${obituary.age} years old</div>
            </div>
            
            <div class="obituary-full-content">
                ${obituary.fullBiography}
                
                <h3 class="obituary-section-title">Survived By</h3>
                <ul class="survived-by-list">
                    ${survivedByHTML}
                </ul>
                
                <div class="service-schedule-box">
                    <h4><i class="bi bi-calendar-event"></i> Service Information</h4>
                    <p><strong>Wake Schedule:</strong> ${obituary.wakeSchedule}</p>
                    <p><strong>Chapel:</strong> ${obituary.chapel}</p>
                    <p><strong>Viewing Hours:</strong> ${obituary.viewingHours}</p>
                    <p><strong>Interment:</strong> ${obituary.interment}</p>
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

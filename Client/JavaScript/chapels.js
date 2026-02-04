const API_BASE = "http://localhost/DULCESYSTEM1/Client/api/";

// ================= LOAD CHAPELS =================
async function loadChapels() {
  console.log("🔄 Starting chapel fetch...");

  try {
    const url = API_BASE + "get-chapels.php";
    console.log("📡 Fetching:", url);

    const response = await fetch(url);

    console.log("📥 HTTP Status:", response.status);

    if (!response.ok) {
      console.error("❌ HTTP ERROR — Server returned:", response.status);
      return;
    }

    const text = await response.text();
    console.log("📄 Raw response text:", text);

    let chapels;
    try {
      chapels = JSON.parse(text);
    } catch (jsonErr) {
      console.error("❌ JSON PARSE ERROR — Not valid JSON");
      return;
    }

    console.log("✅ Parsed chapel data:", chapels);

    if (!Array.isArray(chapels)) {
      console.error(
        "❌ DATA FORMAT ERROR — Expected array, got:",
        typeof chapels,
      );
      return;
    }

    const container = document.getElementById("chapels-container");

    if (!container) {
      console.error("❌ DOM ERROR — #chapels-container not found");
      return;
    }

    container.innerHTML = "";

    if (chapels.length === 0) {
      console.warn("⚠️ No chapels returned from database");
      document.getElementById("no-results-chapel").style.display = "block";
      return;
    }

    chapels.forEach((chapel, index) => {
      console.log(`🧱 Rendering chapel #${index + 1}`, chapel);

      const featuresArray = Array.isArray(chapel.features)
        ? chapel.features
        : [];

      const featuresHTML = featuresArray
        .map(
          (f) =>
            `<div class="col-6"><div class="feature-item">${f}</div></div>`,
        )
        .join("");

      const imagePath = chapel.image
        ? `../../ADMIN/uploads/packages/${chapel.image}`
        : "https://via.placeholder.com/800x500/2c3e50/ffffff?text=Chapel";

      const cardHTML = `
        <div class="col-lg-6 chapel-item"
             data-capacity="${chapel.capacity_type}"
             data-features="${featuresArray.join(",")}">

          <div class="chapel-card">
            <div class="chapel-image-wrapper">
              <img src="${imagePath}" class="chapel-image"
                   onerror="this.src='https://via.placeholder.com/800x500/2c3e50/ffffff?text=Chapel'">
            </div>

            <div class="chapel-content">
              <h3>${chapel.name}</h3>
              <div><i class="bi bi-people-fill"></i> Capacity: ${chapel.capacity}</div>
              <div class="chapel-description">${chapel.description || ""}</div>

              <div class="row g-2">${featuresHTML}</div>

              <div class="chapel-actions">
                <button onclick="selectChapel(${chapel.id})" class="btn btn-select-chapel">
                  Select Home Service
                </button>
              </div>
            </div>
          </div>
        </div>
      `;

      container.insertAdjacentHTML("beforeend", cardHTML);
    });

    console.log("🎉 Chapel cards rendered successfully");
  } catch (err) {
    console.error("❌ FETCH FAILED:", err);
  }
}

// ================= SELECT CHAPEL =================
function selectChapel(id) {
  console.log("👉 Select chapel clicked. ID:", id);

  fetch(API_BASE + "check-session.php", { credentials: "include" })
    .then((res) => res.json())
    .then((data) => {
      console.log("👤 Session data:", data);

      if (data.logged_in) {
        console.log("✅ User logged in → redirect booking page");
        window.location.href = `chapel-booking.html?chapel_id=${id}`;
      } else {
        console.log("🚫 Not logged in → redirect register");
        window.location.href = "../../SignUp&Login/Pages/register.html";
      }
    })
    .catch((err) => {
      console.error("❌ Session check failed:", err);
    });
}

// ================= INIT =================
document.addEventListener("DOMContentLoaded", () => {
  console.log("📄 Page loaded, initializing chapels...");
  loadChapels();
});

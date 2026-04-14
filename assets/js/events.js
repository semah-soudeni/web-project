const clubColors = {
  secu: "#E74E25",
  aero: "#3280C2",
  ieee: "#362B69",
  acm: "#7DF0CA",
  android: "#78DE85",
  cim: "#F6C011",
};

const clubNames = {
  aero: "Aerobotix",
  secu: "Securinets",
  ieee: "IEEE",
  acm: "ACM",
  android: "Android Club",
  cim: "CIM",
};

let allEvents = [];
let currentClub = "all";

document.addEventListener("DOMContentLoaded", () => {
  const payload = window.PHP_EVENTS_PAYLOAD || {};
  const hasQueryError = Boolean(payload.queryError);

  if (!hasQueryError) {
    allEvents = Array.isArray(payload.events) ? payload.events : [];
    currentClub = payload.selectedClub || "all";
    renderByClub(currentClub);
  }

  setupFilters();
});

function renderByClub(clubSlug = "all") {
  const container = document.querySelector("#events-container");
  if (!container) {
    return;
  }

  const filteredEvents =
    clubSlug === "all"
      ? allEvents
      : allEvents.filter((event) => (event.club || "").toLowerCase() === clubSlug);

  renderEvents(filteredEvents);
}

function renderEvents(events) {
  const container = document.querySelector("#events-container");
  if (!container) {
    return;
  }

  container.innerHTML = "";

  if (!events.length) {
    container.innerHTML = `
            <div class="empty-state">
                <h3>No events found</h3>
                <p>There are no upcoming events for this club at the moment.</p>
            </div>`;
    return;
  }

  const grouped = groupEventsByMonth(events);

  Object.keys(grouped).forEach((monthYear) => {
    const section = document.createElement("div");
    section.className = "month-group";

    const title = document.createElement("h2");
    title.className = "month-title";
    title.textContent = monthYear;
    section.appendChild(title);

    grouped[monthYear].forEach((event) => {
      section.innerHTML += createEventCard(event);
    });

    container.appendChild(section);
  });
}

function groupEventsByMonth(events) {
  const grouped = {};

  events.forEach((event) => {
    const date = new Date(`${event.date}T00:00:00`);
    if (Number.isNaN(date.getTime())) {
      return;
    }

    const monthYear = `${date.toLocaleString("default", { month: "long" })} ${date.getFullYear()}`;

    if (!grouped[monthYear]) {
      grouped[monthYear] = [];
    }

    grouped[monthYear].push(event);
  });

  return grouped;
}

function createEventCard(event) {
  const date = new Date(event.date + "T00:00");
  const months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
  const day = date.getDate();
  const month = months[date.getMonth()];

  const timeStr = formatTime(event.time);

  const slug = (event.club || "").toLowerCase();
  const clubColor = clubColors[slug] || "#3182ce";
  const clubName = event.club_name || clubNames[slug] || slug;

  return `
        <div class="event-card" data-club="${slug}">
            <div class="date-badge">
                <div class="day-badge">${day}</div>
                <div class="month-badge">${month}</div>
            </div>
            <div class="event-content">
                <div class="event-header">
                    <div class="club-tag ${slug}" style="background-color: ${clubColor}">
                        ${clubName}
                    </div>
                    <div class="event-time">
                        🕐 ${timeStr}
                    </div>
                </div>
                <h3 class="event-title">${escapeHtml(event.title || "Untitled Event")}</h3>
                <p class="event-desc">${escapeHtml(event.description || "")}</p>
                <div class="event-footer">
                    <div class="event-location">
                        <span class="location-icon">📍</span>
                        <span>${escapeHtml(event.location || "TBA")}</span>
                    </div>
                    <div class="event-location">
                        <span>👥</span>
                        <span>${Number(event.attendees ?? 0)} attendees</span>
                    </div>

                    ${
                      event.is_registered
                        ? `<a class="register-btn" aria-disabled="true" style="opacity:0.6; pointer-events:none;">Registered ✓</a>`
                        : `<a class="register-btn" href="event-registration.html?event_id=${Number(event.id || 0)}">Register</a>`
                    }
                </div>
            </div>
        </div>
    `;
}

function setupFilters() {
  const filterButtons = document.querySelectorAll(".filter-btn");
  const transitionDelayMs = 160;

  setActiveFilter(currentClub);

  document.querySelectorAll(".filter-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const slug = (this.dataset.club || "all").toLowerCase();
      if (slug === currentClub) {
        return;
      }

      filterButtons.forEach((b) => b.classList.remove("active", "switching"));
      this.classList.add("switching");

      window.setTimeout(() => {
        this.classList.remove("switching");
        currentClub = slug;
        setActiveFilter(currentClub);
        renderByClub(currentClub);
      }, transitionDelayMs);
    });
  });
}

function setActiveFilter(slug) {
  document.querySelectorAll(".filter-btn").forEach((btn) => {
    btn.classList.toggle("active", (btn.dataset.club || "all").toLowerCase() === slug);
  });
}

function formatTime(timeStr) {
  if (!timeStr) return "TBA";
  const [hourStr, minStr] = String(timeStr).split(":");
  let hour = parseInt(hourStr, 10);
  const min = minStr || "00";

  if (Number.isNaN(hour)) {
    return "TBA";
  }

  const suffix = hour >= 12 ? "PM" : "AM";
  if (hour >= 12) hour -= 12;
  if (hour === 0) hour = 12;
  return `${hour}:${min} ${suffix}`;
}

function escapeHtml(value) {
  return String(value)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/\"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

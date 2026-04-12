const EVENTS_API = "../backend/events.php";

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

document.addEventListener("DOMContentLoaded", () => {
  console.log('slm');
  loadEvents();
  setupFilters();
});

async function loadEvents(clubSlug = "all") {
  const container = document.querySelector("#events-container");

  document.getElementById('loader').classList.add('active');

  try {
    const url = clubSlug
      ? `${EVENTS_API}?club=${encodeURIComponent(clubSlug)}`
      : EVENTS_API;

    const res = await fetch(url, { credentials: "include" });
    document.getElementById('loader').classList.remove('active');
    if (!res.ok) throw new Error(`Server responded with ${res.status}`);

    const events = await res.json();
    renderEvents(events);
  } catch (err) {
    document.getElementById('loader').classList.remove('active');
    container.innerHTML = `
            <div class="empty-state">
                <h3>Could not load events</h3>
                <p>Make sure XAMPP is running</p>
            </div>
        `;
    console.error("Events fetch error:", err);
  }
}

function renderEvents(events) {
  const container = document.querySelector("#events-container");
  const oldlen = container.innerHTML.length;

  if (!events.length) {
    container.innerHTML = `
            <div class="empty-state">
                <h3>No events found</h3>
                <p>There are no upcoming events for this club at the moment.</p>
            </div>`;
    return;
  }
  console.log('slm');
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
  container.innerHTML = container.innerHTML.slice(oldlen);
}

function groupEventsByMonth(events) {
  const grouped = {};
  console.log(events);
  events.forEach((event) => {
    const date = new Date(event.date);
    const monthYear = `${date.toLocaleString("default", { month: "long" })} ${date.getFullYear()}`;

    if (!grouped[monthYear]) {
      grouped[monthYear] = [];
    }
    grouped[monthYear].push(event);
    console.log(monthYear);
  });
  return grouped;
}

function createEventCard(event) {
  const date = new Date(event.date + "T00:00");
  const months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
  const day = date.getDate();
  const month = months[date.getMonth()];

  const timeStr = formatTime(event.time); // hedhi bch 09:00:00 twally 09:00 AM

  const slug = event.club;
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
                <h3 class="event-title">${event.title}</h3>
                <p class="event-description">${event.description ?? ""}</p>
                <div class="event-footer">
                    <div class="event-location">
                        <span class="location-icon">📍</span>
                        <span>${event.location ?? "TBA"}</span>
                    </div>
                    <div class="event-attendees">
                        <span>👥</span>
                        <span>${event.attendees ?? 0} attendees</span>
                    </div>
                    <form action='register.php' method="POST">
                    <button class="register-btn" onclick="registerForEvent(${event.id}, this)">Register</button>
                    </form> 
                </div> 
            </div>
        </div>
    `;
}

function setupFilters() {
  document.querySelectorAll(".filter-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      document.querySelectorAll(".filter-btn").forEach((b) => b.classList.remove("active"));
      this.classList.add("active");
      const slug = this.dataset.club;
      loadEvents(slug === "all" ? null : slug);
    });
  });
}

function registerForEvent(eventId, btn) {
  btn.textContent = "Registered ✓";
  btn.disabled = true;
  btn.style.opacity = "0.6";
}

function formatTime(timeStr) {
  if (!timeStr) return "TBA";
  const [hourStr, minStr] = timeStr.split(":");
  let hour = parseInt(hourStr, 10);
  const min = minStr;
  const suffix = hour >= 12 ? "PM" : "AM";
  if (hour >= 12) hour -= 12;
  if (hour === 0) hour = 12;
  return `${hour}:${min} ${suffix}`;
}

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

function formatDate(dateString) {
  const date = new Date(dateString);
  const months = [
    "JAN",
    "FEB",
    "MAR",
    "APR",
    "MAY",
    "JUN",
    "JUL",
    "AUG",
    "SEP",
    "OCT",
    "NOV",
    "DEC",
  ];
  return {
    day: date.getDate(),
    month: months[date.getMonth()],
    year: date.getFullYear(),
  };
}

function groupEventsByMonth(events) {
  const grouped = {};
  events.forEach((event) => {
    const date = new Date(event.date);
    const monthYear = `${date.toLocaleString("default", { month: "long" })} ${date.getFullYear()}`;

    if (!grouped[monthYear]) {
      grouped[monthYear] = [];
    }
    grouped[monthYear].push(event);
  });
  return grouped;
}

function createEventCard(event) {
  const dateInfo = formatDate(event.date);
  const clubColor = clubColors[event.club] || "#3182ce";
  const clubName = clubNames[event.club] || event.club;

  return `
        <div class="event-card" data-club="${event.club}">
            <div class="date-badge">
                <div class="day-badge">${dateInfo.day}</div>
                <div class="month-badge">${dateInfo.month}</div>
            </div>
            <div class="event-content">
                <div class="event-header">
                    <div class="club-tag ${event.club}" style="background-color: ${clubColor}">
                        ${clubName}
                    </div>
                    <div class="event-time">
                        🕐 ${event.time}
                    </div>
                </div>
                <h3 class="event-title">${event.title}</h3>
                <p class="event-description">${event.description}</p>
                <div class="event-footer">
                    <div class="event-location">
                        <span class="location-icon">📍</span>
                        <span>${event.location}</span>
                    </div>
                    <div class="event-attendees">
                        <span>👥</span>
                        <span>${event.available_places} available places</span>
                    </div>
                    <button class="register-btn" onclick="registerForEvent(${event.id})">Register</button>
                </div>
            </div>
        </div>
    `;
}

function renderEvents(events, filterClub = "all") {
  const container = document.getElementById("events-container");

  if (!container) {
    console.error("Events container not found");
    return;
  }
  let filteredEvents = events;
  if (filterClub !== "all") {
    filteredEvents = events.filter((event) => event.club === filterClub);
  }

  filteredEvents.sort((a, b) => new Date(a.date) - new Date(b.date));

  const groupedEvents = groupEventsByMonth(filteredEvents);
  container.innerHTML = "";

  if (filteredEvents.length === 0) {
    container.innerHTML = `
            <div class="empty-state">
                <h3>No events found</h3>
                <p>There are no upcoming events for this club at the moment.</p>
            </div>
        `;
    return;
  }

  Object.keys(groupedEvents).forEach((monthYear) => {
    const monthSection = document.createElement("div");
    monthSection.className = "month-group";

    const monthTitle = document.createElement("h2");
    monthTitle.className = "month-title";
    monthTitle.textContent = monthYear;
    monthSection.appendChild(monthTitle);

    groupedEvents[monthYear].forEach((event) => {
      monthSection.innerHTML += createEventCard(event);
    });

    container.appendChild(monthSection);
  });
}

document.addEventListener("DOMContentLoaded", async function () {
  await loadEvents();

  const filterButtons = document.querySelectorAll(".filter-btn");
  filterButtons.forEach((btn) => {
    btn.addEventListener("click", async function () {
      document
        .querySelectorAll(".filter-btn")
        .forEach((b) => b.classList.remove("active"));
      this.classList.add("active");
      await loadEvents(this.dataset.club);
    });
  });
});

async function loadEvents(club = "all") {
  try {
    const res = await fetch(`../backend/events.php?club=${club}`);
    const data = await res.json();
    renderEvents(data);
  } catch (err) {
    console.log(err);
    document.querySelector("#events-container").innerHTML =
      "<p>Failed to load events.</p>";
  }
}

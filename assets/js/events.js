let currentClub = "all";

document.addEventListener("DOMContentLoaded", () => {
  const activeBtn = document.querySelector(".filter-btn.active");
  currentClub = (activeBtn?.dataset.club || "all").toLowerCase();
  setupFilters();
});

function renderByClub(clubSlug = "all") {
  document.querySelectorAll(".event-card").forEach(card => {
    if (clubSlug === "all") {
      card.style.display = "";
      return;
    }
    const cardSlugs = (card.dataset.club || "").split(',').map(s => s.trim());
    card.style.display = cardSlugs.includes(clubSlug) ? "" : "none";
  })

  document.querySelectorAll(".month-group").forEach(group => {
    const hasVisible = [...group.querySelectorAll(".event-card")].some(c => c.style.display !== "none");
    group.style.display = hasVisible ? "" : "none";
  });
}

function setupFilters() {
  document.querySelectorAll(".filter-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      const slug = (this.dataset.club || "all").toLowerCase();
      if (slug === currentClub) return;
      currentClub = slug;
      document.querySelectorAll(".filter-btn").forEach((b) => b.classList.remove("active"));
      this.classList.add("active");
      renderByClub(currentClub);
    });
  });
}
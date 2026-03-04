

/* ============================================================
   SCROLL — FADE IN ANIMATIONS
   ============================================================ */
const fadeObserver = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      }
    });
  },
  { threshold: 0.1, rootMargin: '0px 0px -40px 0px' }
);

document.querySelectorAll('.fade-in-up').forEach((el) => fadeObserver.observe(el));

/* ============================================================
   STAT COUNTER ANIMATION
   ============================================================ */
function animateCounter(element, target, suffix, duration = 1400) {
  const increment = target / (duration / 16);
  let current = 0;

  const timer = setInterval(() => {
    current += increment;
    if (current >= target) {
      current = target;
      clearInterval(timer);
    }
    element.textContent = Math.floor(current) + suffix;
  }, 16);
}

const statsObserver = new IntersectionObserver(
  (entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        document.querySelectorAll('.stat-number[data-target]').forEach((el) => {
          const target = parseInt(el.dataset.target, 10);
          const suffix = el.dataset.suffix || '';
          animateCounter(el, target, suffix);
        });
        statsObserver.disconnect();
      }
    });
  },
  { threshold: 0.35 }
);

const bentoSection = document.querySelector('.bento-section');
if (bentoSection) statsObserver.observe(bentoSection);

/* ============================================================
   EVENTS STRIP — DRAG TO SCROLL
   ============================================================ */
const strip = document.querySelector('.events-strip-wrapper');

if (strip) {
  let isDown = false;
  let startX;
  let scrollLeft;

  strip.addEventListener('mousedown', (e) => {
    isDown = true;
    strip.style.cursor = 'grabbing';
    startX = e.pageX - strip.offsetLeft;
    scrollLeft = strip.scrollLeft;
  });

  strip.addEventListener('mouseleave', () => {
    isDown = false;
    strip.style.cursor = 'default';
  });

  strip.addEventListener('mouseup', () => {
    isDown = false;
    strip.style.cursor = 'default';
  });

  strip.addEventListener('mousemove', (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - strip.offsetLeft;
    const walk = (x - startX) * 1.4;
    strip.scrollLeft = scrollLeft - walk;
  });
}

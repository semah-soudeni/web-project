/**
 * cim.js - Structural Magazine Interactivity
 */

document.addEventListener('DOMContentLoaded', () => {

    // 1. Subtle Parallax for Hero
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const hero = document.querySelector('.hero-banner');
        if (hero) {
            // Use 50% as base to match 'center' and apply offset
            hero.style.backgroundPosition = `center calc(50% + ${scrolled * 0.4}px)`;
        }
    });

    // 2. Stat Counter Animation
    const stats = document.querySelectorAll('.stat-val');

    const observerOptions = {
        threshold: 0.5
    };

    const countUp = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = entry.target;
                const endValue = parseInt(target.innerText.replace('+', ''));
                let startValue = 0;
                const duration = 2000;
                const startTime = performance.now();

                const updateCount = (timestamp) => {
                    const elapsed = timestamp - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const current = Math.floor(progress * endValue);

                    target.innerText = current + (target.innerText.includes('+') ? '+' : '');

                    if (progress < 1) {
                        requestAnimationFrame(updateCount);
                    }
                };

                requestAnimationFrame(updateCount);
                observer.unobserve(target);
            }
        });
    };

    const statObserver = new IntersectionObserver(countUp, observerOptions);
    stats.forEach(stat => statObserver.observe(stat));

    // 3. Fade-in for Content Sections
    const sections = document.querySelectorAll('.section-title, .text-block, .activity-list li, .timeline-item');

    const fadeIn = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    };

    const contentObserver = new IntersectionObserver(fadeIn, { threshold: 0.1 });

    sections.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
        contentObserver.observe(el);
    });

    console.log("%c [CIM_RECONSTRUCTION] %c MAGAZINE_LAYOUT_INITIALIZED", "color: #B87333; font-weight: bold", "color: #708090");
});

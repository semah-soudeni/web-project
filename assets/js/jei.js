document.addEventListener('DOMContentLoaded', () => {
    // Animate stats circles on scroll
    const statCircles = document.querySelectorAll('.stat-circle');

    // Counter animation function
    const animateStat = (circle) => {
        const numElement = circle.querySelector('.stat-num');
        const target = +numElement.getAttribute('data-val');
        let current = 0;
        const speed = 40; // milliseconds
        const increment = target / 30; // 30 steps

        const updateCounter = () => {
            current += increment;
            if (current < target) {
                numElement.innerText = Math.ceil(current);
                setTimeout(updateCounter, speed);
            } else {
                numElement.innerText = target + "+";
            }
        };
        updateCounter();
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
                animateStat(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.4
    });

    statCircles.forEach(circle => {
        observer.observe(circle);
    });
});

document.addEventListener('DOMContentLoaded', () => {
    // Scroll animations
    const slideElements = document.querySelectorAll('.slide-in-left, .slide-in-right');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.2
    });

    slideElements.forEach(element => {
        observer.observe(element);
    });
});

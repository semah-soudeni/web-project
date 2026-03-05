// Animate elements on scroll
document.addEventListener('DOMContentLoaded', () => {
    // Open curtains after a brief delay
    setTimeout(() => {
        document.body.classList.add('loaded');
    }, 500);

    const fadeElements = document.querySelectorAll('.fade-in');

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

    fadeElements.forEach(element => {
        observer.observe(element);
    });
});

document.addEventListener('DOMContentLoaded', () => {
    // Parallax effect for cards
    const cards = document.querySelectorAll('.pillar-card');

    document.addEventListener('mousemove', (e) => {
        const xAxis = (window.innerWidth / 2 - e.pageX) / 50;
        const yAxis = (window.innerHeight / 2 - e.pageY) / 50;

        cards.forEach(card => {
            // Only apply if the card is visible in viewport to save performance
            const rect = card.getBoundingClientRect();
            if (rect.top < window.innerHeight && rect.bottom > 0) {
                card.style.transform = `perspective(1000px) rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
            }
        });
    });

    // Reset when mouse leaves
    document.addEventListener('mouseleave', () => {
        cards.forEach(card => {
            card.style.transform = `perspective(1000px) rotateY(0deg) rotateX(0deg)`;
        });
    });
});

// Cine Radio specific JS interactions
document.addEventListener('DOMContentLoaded', () => {
    // Add hover effects for waveform sync
    const cards = document.querySelectorAll('.card');
    const waveBars = document.querySelectorAll('.waveform span');

    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            waveBars.forEach(bar => {
                bar.style.animationDuration = '0.5s';
            });
        });

        card.addEventListener('mouseleave', () => {
            waveBars.forEach(bar => {
                bar.style.animationDuration = '1.2s';
            });
        });
    });
});

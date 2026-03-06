// Simple falling leaves logic
document.addEventListener('DOMContentLoaded', () => {
    const ecoCanvas = document.getElementById('eco-canvas');
    if (!ecoCanvas) return;

    const count = 20;

    for (let i = 0; i < count; i++) {
        const leaf = document.createElement('div');
        leaf.innerText = '🍃';
        leaf.style.position = 'absolute';

        leaf.style.left = `${Math.random() * 100}vw`;
        leaf.style.top = `-${Math.random() * 100}vh`; // Start above screen
        leaf.style.fontSize = `${Math.random() * 1.5 + 1}rem`;
        leaf.style.opacity = Math.random() * 0.5 + 0.2;

        // Animation
        const duration = Math.random() * 10 + 10;
        const delay = Math.random() * 5;
        leaf.style.animation = `fall ${duration}s linear ${delay}s infinite`;

        ecoCanvas.appendChild(leaf);
    }

    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes fall {
            0% { transform: translateY(-10vh) rotate(0deg) translateX(0); }
            50% { transform: translateY(50vh) rotate(180deg) translateX(50px); }
            100% { transform: translateY(110vh) rotate(360deg) translateX(-50px); }
        }
    `;
    document.head.appendChild(style);
});

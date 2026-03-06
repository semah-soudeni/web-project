// Draw floating molecular structures and equations
document.addEventListener('DOMContentLoaded', () => {
    const bg = document.querySelector('.chem-background');
    const items = ['H₂O', 'CO₂', 'C₆H₁₂O₆', 'NaCl', '⬡', 'E=mc²', 'NH₃', 'CH₄', 'O₂', '⌬'];
    const count = 30;

    for (let i = 0; i < count; i++) {
        const el = document.createElement('div');
        el.innerText = items[Math.floor(Math.random() * items.length)];
        el.style.position = 'absolute';

        // Random styling
        el.style.left = `${Math.random() * 100}vw`;
        el.style.top = `${Math.random() * 100}vh`;
        el.style.fontSize = `${Math.random() * 1.5 + 1}rem`;
        el.style.opacity = Math.random() * 0.3 + 0.1;
        el.style.color = Math.random() > 0.5 ? 'var(--chem-green)' : 'var(--chem-blue)';

        // Animation
        const duration = Math.random() * 20 + 20; // 20-40s
        el.style.animation = `float ${duration}s linear infinite`;
        el.style.animationDelay = `-${Math.random() * duration}s`;

        bg.appendChild(el);
    }

    // Add keyframes dynamically
    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-100px) rotate(180deg); }
            100% { transform: translateY(0) rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
});

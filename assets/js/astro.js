document.addEventListener('DOMContentLoaded', () => {
    const universe = document.getElementById('universe-canvas');

    // Make stars
    for (let i = 0; i < 200; i++) {
        const star = document.createElement('div');
        const size = Math.random() * 3 + 1;

        star.style.position = 'absolute';
        star.style.left = `${Math.random() * 100}vw`;
        star.style.top = `${Math.random() * 100}vh`;
        star.style.width = `${size}px`;
        star.style.height = `${size}px`;
        star.style.backgroundColor = 'white';
        star.style.borderRadius = '50%';
        star.style.opacity = Math.random();
        star.style.boxShadow = `0 0 ${size * 2}px white`;

        // Twinkle
        const duration = Math.random() * 3 + 2;
        star.style.animation = `twinkle ${duration}s ease-in-out infinite alternate`;

        universe.appendChild(star);
    }

    // Make planets
    const planetColors = [
        'linear-gradient(45deg, #1f4037, #99f2c8)',
        'linear-gradient(45deg, #cb2d3e, #ef473a)',
        'linear-gradient(45deg, #4b6cb7, #182848)',
        'linear-gradient(45deg, #FFDEAD, #D2B48C)'
    ];

    for (let i = 0; i < 4; i++) {
        const planet = document.createElement('div');
        const size = Math.random() * 100 + 50;

        planet.style.position = 'absolute';
        planet.style.left = `${Math.random() * 90}vw`;
        planet.style.top = `${Math.random() * 90}vh`;
        planet.style.width = `${size}px`;
        planet.style.height = `${size}px`;
        planet.style.background = planetColors[i];
        planet.style.borderRadius = '50%';
        planet.style.opacity = 0.8;
        planet.style.boxShadow = `inset -10px -10px 20px rgba(0,0,0,0.5), 0 0 20px ${planetColors[i].split(', ')[1]}`;

        // Slow orbit animation
        const dur = Math.random() * 60 + 40;
        planet.style.animation = `orbit ${dur}s linear infinite`;

        universe.appendChild(planet);
    }

    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes twinkle {
            0% { opacity: 0.2; }
            100% { opacity: 1; transform: scale(1.2); }
        }
        @keyframes orbit {
            0% { transform: translateY(0) translateX(0); }
            50% { transform: translateY(-20px) translateX(20px); }
            100% { transform: translateY(0) translateX(0); }
        }
    `;
    document.head.appendChild(style);
});

// Create particles
const particlesContainer = document.getElementById('particles');
for (let i = 0; i < 50; i++) {
    const particle = document.createElement('div');
    particle.className = 'particle';
    particle.style.width = Math.random() * 4 + 2 + 'px';
    particle.style.height = particle.style.width;
    particle.style.left = Math.random() * 100 + '%';
    particle.style.animationDelay = Math.random() * 15 + 's';
    particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
    particlesContainer.appendChild(particle);
}

// Create binary rain
const binaryRain = document.getElementById('binaryRain');
for (let i = 0; i < 30; i++) {
    const column = document.createElement('div');
    column.className = 'binary-column';
    column.style.left = Math.random() * 100 + '%';
    column.style.animationDuration = (Math.random() * 10 + 15) + 's';
    column.style.animationDelay = Math.random() * 10 + 's';
    
    let binaryText = '';
    for (let j = 0; j < 30; j++) {
        binaryText += Math.random() > 0.5 ? '1' : '0';
        if (j % 8 === 7) binaryText += '<br>';
    }
    column.innerHTML = binaryText;
    
    binaryRain.appendChild(column);
}

// Create data streams
const dataStreamsContainer = document.getElementById('dataStreams');
for (let i = 0; i < 10; i++) {
    const stream = document.createElement('div');
    stream.className = 'data-stream';
    stream.style.top = Math.random() * 100 + '%';
    stream.style.width = Math.random() * 200 + 100 + 'px';
    stream.style.animationDuration = (Math.random() * 5 + 3) + 's';
    stream.style.animationDelay = Math.random() * 5 + 's';
    dataStreamsContainer.appendChild(stream);
}

// Additional glitch effect on random intervals
setInterval(() => {
    if (Math.random() > 0.95) {
        const logo = document.getElementById('mainLogo');
        logo.style.transform = `translate(${Math.random() * 6 - 3}px, ${Math.random() * 6 - 3}px)`;
        setTimeout(() => {
            logo.style.transform = 'translate(0, 0)';
        }, 50);
    }
}, 100);

const canvas = document.getElementById("rockets")
canvas.height = window.innerHeight;
canvas.width = window.innerWidth;

const ctx = canvas.getContext('2d')
//var startX = 400;
//var startY = 600;
//var vitesse = {
    //x:0,
    //y:0
//};
//const rocket = new Image();
//rocket.src = '/assets/img/rocket.png'
const stars = []
for (let index = 0 ; index<500 ; index++){
    stars.push({
        x:Math.random() * window.innerWidth,
        y:Math.random() * window.innerHeight,
        raduis:Math.random() * 4
    })
}

//var blurAcc = 0;
//var blurRate = 20;
var active = false;
function animate(){
    ctx.save()
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    //if (blurRate == 20)
        //blurAcc = -1;
    //else if (blurRate <= 10)
        //blurAcc = 1;
//blurRate += blurAcc*0.1
    //ctx.shadowBlur = blurRate
    stars.forEach( star => {
        ctx.fillStyle = "rgba(255,255,255)"
        ctx.shadowColor = "white"
        setInterval( () => active = !active , 5)
        ctx.shadowBlur = active ? 20 : 15
        ctx.beginPath()
        ctx.arc(star.x, star.y, star.raduis , 0, Math.PI * 2 )
        ctx.fill()
    }) 
    ctx.restore()
    requestAnimationFrame(animate)
}
animate()
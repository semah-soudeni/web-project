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
const rocket = new Image();
rocket.src = '/assets/img/rocket.png'
const stars = []
const betterStars = []
function generateStars(){
    for (let index = 0 ; index<500 ; index++){
        stars.push({
            x:Math.random() * window.innerWidth,
            y:Math.random() * window.innerHeight,
            raduis:Math.random() * 4
        })
    }
    for (let index = 0 ; index < 5 ; index++){
        betterStars.push({
            x:Math.random() * window.innerWidth,
            y:Math.random() * window.innerHeight
        })
    }
}
generateStars()

var blurAcc = 0;
var blurRate = 20;
var radius = 0
var radiusAcc = -1
function animate(){
    ctx.save()
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    //console.log("blurRate ="+blurRate)
    if (blurRate == 20)
        blurAcc = -1;
    else if (blurRate <= 0){
        blurAcc = 1;
    }
    blurRate += blurAcc*0.05

    stars.forEach( star => {
        ctx.fillStyle = "rgba(255,255,255)"
        ctx.shadowColor = "white"
        star.x = (star.x + 0.05) % window.innerWidth
        ctx.shadowBlur = blurRate
        ctx.beginPath()
        ctx.arc(star.x, star.y, star.raduis , 0, Math.PI * 2 )
        ctx.fill()
    }); 
    betterStars.forEach ( star => {
        ctx.fillStyle = "white"
        ctx.shadowColor = "white"
        ctx.shadowBlur = 20
        star.x = (star.x + 0.05) % window.innerWidth
        //console.log(radius)
        if (radius >= 4){
            radiusAcc = -1;
        }
        else if (radius < 0.005){
            radius = 0
            radiusAcc = 1;
        }
        radius += radiusAcc*0.005
        ctx.beginPath()
        ctx.arc(star.x ,star.y , radius , 0 , Math.PI * 2)
        ctx.fill()
    });
    ctx.restore()
    requestAnimationFrame(animate)
}
animate()
window.addEventListener("resize", () => {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
    generateStars()
});

const obs = new IntersectionObserver((entries) => {
entries.forEach((e, i) => {
  if (e.isIntersecting) {
    setTimeout(() => e.target.classList.add('visible'), i * 80);
  }
});
}, { threshold: 0.1 });

document.querySelectorAll('.fade-in').forEach(el => obs.observe(el));

let base = 148;
setInterval(() => {
const delta = Math.random() < 0.3 ? (Math.random() < 0.5 ? 1 : -1) : 0;
base = Math.max(100, base + delta);
document.getElementById('member-count').textContent = base;
document.getElementById('live-num').textContent = base;
}, 3500);

// Counter animation
function animateCount(el, target, duration) {
let start = 0, step = target / (duration / 16);
const tick = () => {
  start = Math.min(start + step, target);
  el.textContent = Math.floor(start);
  if (start < target) requestAnimationFrame(tick);
};
tick();
}

const counterObs = new IntersectionObserver((entries) => {
entries.forEach(e => {
  if (e.isIntersecting) {
    animateCount(document.getElementById('live-num'), 148, 1200);
    counterObs.unobserve(e.target);
  }
});
}, { threshold: 0.5 });

counterObs.observe(document.getElementById('members-live'));

// Smooth nav active highlighting
const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('nav ul a');

window.addEventListener('scroll', () => {
let current = '';
sections.forEach(s => {
  if (window.scrollY >= s.offsetTop - 120) current = s.id;
});
navLinks.forEach(a => {
  a.style.color = a.getAttribute('href') === '#' + current
    ? 'var(--accent)' : '';
});
});

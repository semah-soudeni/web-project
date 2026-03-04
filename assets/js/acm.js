// ===============================
// ACM INSAT: Scroll-driven Code Explorer
// ===============================

// Canvas setup
const canvas = document.getElementById("bg-canvas");
const ctx = canvas.getContext("2d");

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

// ----------------
// Particle Config
// ----------------
const symbols = ["{ }", "< >", ";", "()", "function", "let", "const", "if", "return", "for"];
const numParticles = 50;
const particles = [];

function random(min, max) {
  return Math.random() * (max - min) + min;
}

// Particle class
class Particle {
  constructor() {
    this.reset();
  }

  reset() {
    this.x = random(0, canvas.width);
    this.y = random(0, canvas.height);
    this.size = random(14, 28);
    this.symbol = symbols[Math.floor(Math.random() * symbols.length)];
    this.speedY = random(0.5, 2.0);
    this.speedX = random(-0.5, 0.5);
    this.baseOpacity = random(0.3, 0.8);
    this.opacity = this.baseOpacity;
    this.color = `rgba(0,255,200,${this.opacity})`;
  }

  update() {
    this.y -= this.speedY;
    this.x += this.speedX;

    // Wrap around screen
    if (this.y < -50) this.reset();
    if (this.x > canvas.width + 50) this.x = -50;
    if (this.x < -50) this.x = canvas.width + 50;

    // Fade near top
    const fadeZone = canvas.height * 0.2;
    if (this.y < fadeZone) this.opacity = this.baseOpacity * (this.y / fadeZone);
    else this.opacity = this.baseOpacity;

    this.color = `rgba(0,255,200,${this.opacity})`;
  }

  draw() {
    ctx.font = `${this.size}px monospace`;
    ctx.fillStyle = this.color;
    ctx.fillText(this.symbol, this.x, this.y);
  }
}

// Initialize particles
for (let i = 0; i < numParticles; i++) {
  particles.push(new Particle());
}

const pathPoints = [
  { x: 0.97, y: 0.15 },
  { x: 0.975, y: 0.32 },
  { x: 0.96, y: 0.55 },
  { x: 0.975, y: 0.75 },
  { x: 0.97, y: 0.92 },
];
// Avatar properties
const avatar = {
  radius: 15,
  color: "#00FFC8",
  glow: 0.6,
};

// ----------------
// Animation loop
// ----------------
function animate() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);

  // Draw path
  ctx.strokeStyle = "rgba(0,255,200,0.2)";
  ctx.lineWidth = 3;
  ctx.beginPath();
  pathPoints.forEach((p, i) => {
    const px = p.x * canvas.width;
    const py = p.y * canvas.height;
    if (i === 0) ctx.moveTo(px, py);
    else ctx.lineTo(px, py);
  });
  ctx.stroke();

  // Draw milestones
  pathPoints.forEach((p, i) => {
    const px = p.x * canvas.width;
    const py = p.y * canvas.height;
    ctx.fillStyle = "rgba(0,255,200,0.3)";
    ctx.beginPath();
    ctx.arc(px, py, 10, 0, Math.PI * 2);
    ctx.fill();
  });

  // Calculate avatar position based on scroll
  const scrollPercent = window.scrollY / (document.body.scrollHeight - window.innerHeight);
  const pathIndex = Math.floor(scrollPercent * (pathPoints.length - 1));
  const t = (scrollPercent * (pathPoints.length - 1)) % 1;

  const start = pathPoints[pathIndex];
  const end = pathPoints[Math.min(pathIndex + 1, pathPoints.length - 1)];
  const avatarX = start.x * canvas.width + (end.x - start.x) * canvas.width * t;
  const avatarY = start.y * canvas.height + (end.y - start.y) * canvas.height * t;

  // Draw avatar with glow
  ctx.shadowColor = avatar.color;
  ctx.shadowBlur = 20;
  ctx.fillStyle = avatar.color;
  ctx.beginPath();
  ctx.arc(avatarX, avatarY, avatar.radius, 0, Math.PI * 2);
  ctx.fill();
  ctx.shadowBlur = 0;

  // Update & draw particles
  particles.forEach(p => {
    p.update();
    p.draw();
  });

  requestAnimationFrame(animate);
}

animate();

// ----------------
// Handle window resize
// ----------------
window.addEventListener("resize", () => {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
});

const canvas = document.getElementById("rockets");
const ctx = canvas.getContext("2d");

function resize() {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
}
resize();
window.addEventListener("resize", () => { resize(); initStars(); });

const COLORS = [
  { r: 0,   g: 245, b: 196 },  // teal   --accent
  { r: 123, g: 97,  b: 255 },  // purple --accent2
  { r: 232, g: 234, b: 246 },  // soft white
  { r: 0,   g: 245, b: 196 },  // teal weighted extra
  { r: 180, g: 160, b: 255 },  // lavender
];

const NUM_STARS    = 420;
const NUM_PULSARS  = 6;   // large breathing stars
const NUM_SHOOTERS = 3;   // shooting stars

let stars    = [];
let pulsars  = [];
let shooters = [];

function randColor() {
  return COLORS[Math.floor(Math.random() * COLORS.length)];
}

function initStars() {
  stars = [];
  pulsars = [];
  shooters = [];

  for (let i = 0; i < NUM_STARS; i++) {
    const c = randColor();
    stars.push({
      x:       Math.random() * canvas.width,
      y:       Math.random() * canvas.height,
      r:       Math.random() * 1.4 + 0.2,       // 0.2 – 1.6 px
      speed:   Math.random() * 0.04 + 0.01,     // drift right
      alpha:   Math.random() * 0.5 + 0.3,
      twinkleSpeed: Math.random() * 0.008 + 0.002,
      twinkleDir:   Math.random() < 0.5 ? 1 : -1,
      color: c,
    });
  }

  for (let i = 0; i < NUM_PULSARS; i++) {
    const c = randColor();
    pulsars.push({
      x:       Math.random() * canvas.width,
      y:       Math.random() * canvas.height,
      r:       0,
      maxR:    Math.random() * 2.5 + 1.5,       // 1.5 – 4 px
      phase:   Math.random() * Math.PI * 2,
      speed:   Math.random() * 0.012 + 0.005,
      driftX:  Math.random() * 0.03 + 0.01,
      color: c,
    });
  }

  spawnShooter();
}

function spawnShooter() {
  shooters = [];
  for (let i = 0; i < NUM_SHOOTERS; i++) {
    resetShooter(i);
  }
}

function resetShooter(i) {
  const angle = (Math.random() * 30 + 10) * (Math.PI / 180); // 10–40 deg downward
  const speed = Math.random() * 6 + 4;
  shooters[i] = {
    x:      Math.random() * canvas.width * 0.8,
    y:      Math.random() * canvas.height * 0.4,
    vx:     Math.cos(angle) * speed,
    vy:     Math.sin(angle) * speed,
    len:    Math.random() * 120 + 60,
    alpha:  0,
    fadeIn: true,
    active: false,
    delay:  Math.random() * 8000 + 2000,   // ms until next launch
    lastFired: performance.now(),
  };
}

let lastTime = 0;

function animate(now) {
  requestAnimationFrame(animate);

  const dt = now - lastTime;
  lastTime = now;

  ctx.clearRect(0, 0, canvas.width, canvas.height);

  // ── regular stars ──────────────────────────────────────
  stars.forEach(s => {
    s.x = (s.x + s.speed) % canvas.width;

    // twinkle
    s.alpha += s.twinkleSpeed * s.twinkleDir;
    if (s.alpha >= 0.85) { s.alpha = 0.85; s.twinkleDir = -1; }
    if (s.alpha <= 0.1)  { s.alpha = 0.1;  s.twinkleDir =  1; }

    const { r, g, b } = s.color;
    ctx.save();
    ctx.beginPath();
    ctx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
    ctx.fillStyle = `rgba(${r},${g},${b},${s.alpha.toFixed(2)})`;
    ctx.shadowColor = `rgba(${r},${g},${b},0.6)`;
    ctx.shadowBlur  = s.r * 3;
    ctx.fill();
    ctx.restore();
  });

  // ── pulsars (breathing glow) ───────────────────────────
  pulsars.forEach(p => {
    p.phase += p.speed;
    p.r = p.maxR * (0.4 + 0.6 * Math.abs(Math.sin(p.phase)));
    p.x = (p.x + p.driftX) % canvas.width;

    const alpha = 0.5 + 0.5 * Math.abs(Math.sin(p.phase));
    const { r, g, b } = p.color;

    ctx.save();
    // outer glow
    const grad = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.r * 5);
    grad.addColorStop(0,   `rgba(${r},${g},${b},${(alpha * 0.35).toFixed(2)})`);
    grad.addColorStop(1,   `rgba(${r},${g},${b},0)`);
    ctx.beginPath();
    ctx.arc(p.x, p.y, p.r * 5, 0, Math.PI * 2);
    ctx.fillStyle = grad;
    ctx.fill();

    // core
    ctx.beginPath();
    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
    ctx.fillStyle = `rgba(${r},${g},${b},${alpha.toFixed(2)})`;
    ctx.shadowColor = `rgba(${r},${g},${b},0.9)`;
    ctx.shadowBlur  = 18;
    ctx.fill();
    ctx.restore();
  });

  // ── shooting stars ─────────────────────────────────────
  shooters.forEach((s, i) => {
    if (!s.active) {
      if (now - s.lastFired >= s.delay) {
        s.active = true;
        s.x = Math.random() * canvas.width * 0.7;
        s.y = Math.random() * canvas.height * 0.35;
        s.alpha = 0;
        s.fadeIn = true;
      }
      return;
    }

    s.x += s.vx;
    s.y += s.vy;

    if (s.fadeIn) {
      s.alpha += 0.04;
      if (s.alpha >= 1) { s.alpha = 1; s.fadeIn = false; }
    } else {
      s.alpha -= 0.018;
    }

    if (s.alpha <= 0 || s.x > canvas.width || s.y > canvas.height) {
      s.active = false;
      s.lastFired = now;
      s.delay = Math.random() * 8000 + 3000;
      return;
    }

    const tailX = s.x - s.vx * (s.len / Math.hypot(s.vx, s.vy));
    const tailY = s.y - s.vy * (s.len / Math.hypot(s.vx, s.vy));

    ctx.save();
    const grad = ctx.createLinearGradient(s.x, s.y, tailX, tailY);
    grad.addColorStop(0,   `rgba(0,245,196,${s.alpha.toFixed(2)})`);
    grad.addColorStop(0.4, `rgba(123,97,255,${(s.alpha * 0.5).toFixed(2)})`);
    grad.addColorStop(1,   `rgba(0,245,196,0)`);

    ctx.beginPath();
    ctx.moveTo(s.x, s.y);
    ctx.lineTo(tailX, tailY);
    ctx.strokeStyle = grad;
    ctx.lineWidth   = 1.5;
    ctx.lineCap     = "round";
    ctx.shadowColor = "rgba(0,245,196,0.8)";
    ctx.shadowBlur  = 8;
    ctx.stroke();

    // head dot
    ctx.beginPath();
    ctx.arc(s.x, s.y, 1.8, 0, Math.PI * 2);
    ctx.fillStyle = `rgba(255,255,255,${s.alpha.toFixed(2)})`;
    ctx.shadowBlur = 12;
    ctx.fill();
    ctx.restore();
  });
}

initStars();
requestAnimationFrame(animate);

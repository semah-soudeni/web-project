const canvas = document.getElementById("construction-canvas");
const ctx = canvas.getContext("2d");

function resizeCanvas() {
    canvas.width = window.innerWidth;
    canvas.height = 320; // Increased height
}
resizeCanvas();
window.addEventListener("resize", resizeCanvas);

let truckX = -200;
const truckSpeed = 4.5; // Faster truck

function drawGround() {
    ctx.fillStyle = "#2a2a2a";
    ctx.fillRect(0, canvas.height - 60, canvas.width, 60);
}

function drawWheel(x, y, radius) {
    ctx.beginPath();
    ctx.arc(x, y, radius, 0, Math.PI * 2);
    ctx.fillStyle = "black";
    ctx.fill();

    ctx.beginPath();
    ctx.arc(x, y, radius / 2, 0, Math.PI * 2);
    ctx.fillStyle = "#777";
    ctx.fill();
}

function drawTruck() {
    const baseY = canvas.height - 80;

    // Truck body
    ctx.fillStyle = "#FFC107";
    ctx.fillRect(truckX, baseY - 40, 140, 40);

    // Cabin
    ctx.fillRect(truckX + 100, baseY - 65, 60, 65);

    // Window
    ctx.fillStyle = "#B0BEC5";
    ctx.fillRect(truckX + 110, baseY - 55, 35, 30);

    // Wheels
    drawWheel(truckX + 35, baseY, 18);
    drawWheel(truckX + 105, baseY, 18);
}

function drawHammer(x, y) {
    ctx.fillStyle = "#8D6E63";
    ctx.fillRect(x, y - 40, 8, 40);

    ctx.fillStyle = "#B0BEC5";
    ctx.fillRect(x - 15, y - 50, 40, 10);
}

function drawWrench(x, y) {
    ctx.strokeStyle = "#B0BEC5";
    ctx.lineWidth = 6;
    ctx.beginPath();
    ctx.moveTo(x, y);
    ctx.lineTo(x + 50, y - 25);
    ctx.stroke();
}

function drawGear(x, y, size) {
    ctx.strokeStyle = "#FFC107";
    ctx.lineWidth = 4;
    ctx.beginPath();
    ctx.arc(x, y, size, 0, Math.PI * 2);
    ctx.stroke();

    for (let i = 0; i < 8; i++) {
        const angle = (Math.PI * 2 / 8) * i;
        const tx = x + Math.cos(angle) * (size + 8);
        const ty = y + Math.sin(angle) * (size + 8);

        ctx.beginPath();
        ctx.moveTo(x + Math.cos(angle) * size, y + Math.sin(angle) * size);
        ctx.lineTo(tx, ty);
        ctx.stroke();
    }
}

function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    drawGround();

    // Left tools (bigger now)
    drawHammer(80, canvas.height - 60);
    drawWrench(140, canvas.height - 40);

    // Right tools (bigger gear)
    drawGear(canvas.width - 100, canvas.height - 50, 25);

    // Truck movement
    drawTruck();

    truckX += truckSpeed;
    if (truckX > canvas.width + 200) {
        truckX = -200;
    }

    requestAnimationFrame(animate);
}

animate();

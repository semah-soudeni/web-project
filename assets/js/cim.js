const canvas = document.getElementById("construction-canvas");
const ctx = canvas.getContext("2d");

function resizeCanvas() {
    canvas.width = window.innerWidth;
    canvas.height = 320; // Increased height
}
resizeCanvas();
window.addEventListener("resize", resizeCanvas);

let truckX = -200;
const truckSpeed = 1.5; // Slower truck

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

function drawShovel(x, y) {
    // Handle
    ctx.fillStyle = "#8D6E63";
    ctx.fillRect(x, y - 50, 6, 50);

    // Blade
    ctx.fillStyle = "#B0BEC5";
    ctx.beginPath();
    ctx.moveTo(x - 8, y - 70);
    ctx.lineTo(x + 14, y - 70);
    ctx.lineTo(x + 8, y - 45);
    ctx.lineTo(x - 2, y - 45);
    ctx.fill();

    // Grip
    ctx.fillStyle = "#8D6E63";
    ctx.fillRect(x - 4, y - 5, 14, 5);
}

function drawAxe(x, y) {
    // Handle
    ctx.fillStyle = "#8D6E63";
    ctx.fillRect(x, y - 50, 6, 50);

    // Head
    ctx.fillStyle = "#B0BEC5";
    ctx.beginPath();
    ctx.moveTo(x, y - 45);
    ctx.lineTo(x + 20, y - 50);
    ctx.lineTo(x + 20, y - 20);
    ctx.lineTo(x, y - 30);
    ctx.fill();
}

function drawPickaxe(x, y) {
    // Handle
    ctx.fillStyle = "#8D6E63";
    ctx.fillRect(x, y - 45, 6, 45);

    // Head (Curved)
    ctx.fillStyle = "#B0BEC5";
    ctx.beginPath();
    ctx.moveTo(x - 25, y - 25);
    ctx.quadraticCurveTo(x + 3, y - 55, x + 31, y - 25);
    ctx.lineTo(x + 25, y - 20);
    ctx.quadraticCurveTo(x + 3, y - 45, x - 19, y - 20);
    ctx.fill();
}

function drawMaterials(x, y) {
    // Bricks
    ctx.fillStyle = "#D32F2F";
    ctx.fillRect(x, y - 10, 20, 10);
    ctx.fillRect(x + 22, y - 10, 20, 10);
    ctx.fillRect(x + 11, y - 22, 20, 10);

    // Wooden plank
    ctx.fillStyle = "#8D6E63";
    ctx.fillRect(x + 50, y - 8, 40, 8);
    ctx.fillRect(x + 48, y - 18, 40, 8);
}

function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    drawGround();

    // Tools
    drawShovel(80, canvas.height - 60);
    drawAxe(140, canvas.height - 60);
    drawPickaxe(200, canvas.height - 60);

    // Materials
    drawMaterials(canvas.width - 200, canvas.height - 60);

    // Truck movement
    drawTruck();

    truckX += truckSpeed;
    if (truckX > canvas.width + 200) {
        truckX = -200;
    }

    requestAnimationFrame(animate);
}

animate();

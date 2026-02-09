const canvas = document.getElementById("bg");

const renderer = new THREE.WebGLRenderer({
  canvas,
  antialias: true,
  alpha: true
});
renderer.setSize(window.innerWidth, window.innerHeight);
renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

const scene = new THREE.Scene();

const camera = new THREE.OrthographicCamera(-1, 1, 1, -1, 0.1, 10);
camera.position.z = 1;

// ---------- Texture ----------
const loader = new THREE.TextureLoader();
const logo = loader.load("../../assets/img/secu-logo.png");
logo.minFilter = THREE.LinearFilter;
logo.magFilter = THREE.LinearFilter;

// ---------- Mouse ----------
const mouse = new THREE.Vector2(0.5, 0.5);
window.addEventListener("mousemove", e => {
  mouse.x = e.clientX / window.innerWidth;
  mouse.y = 1.0 - e.clientY / window.innerHeight;
});

// ---------- Shaders ----------
const vertexShader = `
varying vec2 vUv;
void main() {
  vUv = uv;
  gl_Position = vec4(position, 1.0);
}
`;

const fragmentShader = `
uniform sampler2D logo;
uniform float time;
uniform float hover;
uniform vec2 mouse;

varying vec2 vUv;

// hash
float hash(float n) {
  return fract(sin(n) * 43758.5453123);
}

float noise(vec2 x) {
  vec2 p = floor(x);
  vec2 f = fract(x);
  f = f * f * (3.0 - 2.0 * f);
  float n = p.x + p.y * 57.0;
  return mix(
    mix(hash(n + 0.0), hash(n + 1.0), f.x),
    mix(hash(n + 57.0), hash(n + 58.0), f.x),
    f.y
  );
}

float sampleMask(vec2 uv) {
  return texture2D(logo, uv).a;
}

float blurredMask(vec2 uv) {
  vec2 o = vec2(0.006, 0.006);
  float a = sampleMask(uv) * 0.28;
  a += sampleMask(uv + vec2(o.x, 0.0)) * 0.12;
  a += sampleMask(uv - vec2(o.x, 0.0)) * 0.12;
  a += sampleMask(uv + vec2(0.0, o.y)) * 0.12;
  a += sampleMask(uv - vec2(0.0, o.y)) * 0.12;
  a += sampleMask(uv + vec2(o.x, o.y)) * 0.06;
  a += sampleMask(uv + vec2(-o.x, o.y)) * 0.06;
  a += sampleMask(uv + vec2(o.x, -o.y)) * 0.06;
  a += sampleMask(uv + vec2(-o.x, -o.y)) * 0.06;
  return a;
}

void main() {
  // Mask with logo
  float maskA = blurredMask(vUv);
  if (maskA < 0.1) discard;

  // ----- Mouse distortion -----
  vec2 uv = vUv;
  float d = distance(uv, mouse);
  uv += (uv - mouse) * d * 0.15 * hover;

  // ----- Matrix grid -----
  float columns = 80.0;
  vec2 grid = vec2(columns, 40.0);
  vec2 cell = floor(uv * grid);

  // column speed
  float speed = mix(1.5, 4.0, hash(cell.x));
  float y = fract(uv.y * grid.y - time * speed);

  // head of the rain
  float head = smoothstep(0.95, 1.0, y);

  // trail
  float trail = smoothstep(0.0, 0.8, y);

  float intensity = head + trail * 0.35;

  // flicker per cell
  float flicker = step(0.85, noise(cell + floor(time * 10.0)));

  // colors
  vec3 green = vec3(0.0, 1.0, 0.4);
  vec3 darkGreen = vec3(0.0, 0.3, 0.1);
  vec3 baseRed = vec3(1.0, 0.0, 0.0);

  vec3 matrixColor = mix(darkGreen, green, head) * intensity * flicker;

  vec3 finalColor = mix(
    baseRed,
    matrixColor,
    hover
  );

  gl_FragColor = vec4(finalColor, maskA);
}
`;

// ---------- Material ----------
const material = new THREE.ShaderMaterial({
  uniforms: {
    logo: { value: logo },
    time: { value: 0 },
    hover: { value: 0 },
    mouse: { value: mouse }
  },
  vertexShader,
  fragmentShader,
  transparent: true
});

// ---------- Plane ----------
const plane = new THREE.Mesh(
  new THREE.PlaneGeometry(1.3, 1.5),
  material
);
scene.add(plane);

// ---------- Hover detection ----------
let targetHover = 0;
window.addEventListener("mousemove", e => {
  const x = e.clientX / window.innerWidth - 0.5;
  const y = e.clientY / window.innerHeight - 0.5;
  const dist = Math.sqrt(x * x + y * y);
  targetHover = dist < 0.28 ? 1 : 0;
});

// ---------- Animate ----------
function animate(t) {
  requestAnimationFrame(animate);
  material.uniforms.time.value = t * 0.001;
  material.uniforms.hover.value +=
    (targetHover - material.uniforms.hover.value) * 0.06;
  renderer.render(scene, camera);
}

animate();

import * as THREE from "three";
import { OBJLoader } from "/assets/js/vendor/OBJLoader.js";
import { MTLLoader } from "/assets/js/vendor/MTLLoader.js";
import { animate } from '/assets/js/aero.js';

await document.fonts.load('120px Doto');

const terminal = document.getElementById("term");
const bootScreen = document.getElementById("startup");
const canv = document.getElementById("three-canvas");
const textcanv = document.createElement("canvas");
textcanv.width = 800;
textcanv.height = 400;
const textctx = textcanv.getContext('2d')

let lines = [
  "Initializing run #134:",
  "Powering control system...",
  "Booting line-following module v2.7",
  "Calibrating optical sensors...",
  "Left sensors: OK",
  "Right sensors: OK",
  "",
  "Scanning track surface...",
  "White/black threshold calibration in progress...",
  "Ambient light compensation: active",
  "",
  "Loading PID controller...",
  "P: 1.25",
  "I: 0.02",
  "D: 0.35",
];

function load_contents(){
  for(let i=1;i<=6;i++){
    contents.push(document.getElementById('content'+i));
  }
}

window.onload = () => {
  window.scrollTo(0, 0);
  load_contents();
  init_everything();
  render();
  toggle_scroll(false);
  print_line();
};

async function skip_intro(){
  window.scrollTo(0, 0);
  init_everything();
  animate();
  bootScreen.classList.add("fade");
  canv.classList.add("show");
  write_lcd(0);
  load_contents();
  contents[0].classList.add('show')
  camera.position.set(2.2, 0, 3);
  //camera.position.set(4.5,-6, 50.5);
  toggle_scroll(true);
  finished_zooming = true;
}

let i = 0;
function print_line(){
  if (i < lines.length) {
    terminal.textContent += lines[i] + "\n";
    i++;
    setTimeout(print_line, 300 + Math.random() * 300);
  } else {
    setTimeout(end_boot, 800);
  }
}

// the exports
let
  camera,
  renderer,
  texture,
  car,
  wheell,
  wheelr,
  finished_zooming = false,
  contents = [];

let scene;
let running_animations = [];

function add_anim(v1, v2, p, v, t, is_first_cam_zoom){
  running_animations.push({
    done: false,
    v1,
    v2,
    t: 0,
    pos: p,
    val: v,
    maxT: t,
    is_first_cam_zoom
  })
}

function init_everything(){
  scene = new THREE.Scene();
  scene.background = new THREE.Color(0x000000);

  camera = new THREE.PerspectiveCamera(
    75,
    2,
    0.1,
    1000
  );

  renderer = new THREE.WebGLRenderer({ canvas: canv, antialias: true });
  renderer.setSize(window.innerWidth, window.innerHeight);
  document.body.appendChild(renderer.domElement);

  const light = new THREE.DirectionalLight(0xffffee, 1);
  light.position.set(5, 5, 5);
  scene.add(light);

  const ambient = new THREE.AmbientLight(0xffffff, 0.2);
  scene.add(ambient);

  texture = new THREE.CanvasTexture(textcanv);
  texture.needsUpdate = true;

  const lcd = new THREE.Mesh(
    new THREE.PlaneGeometry(0.8, 0.3),
    new THREE.MeshBasicMaterial({ map: texture, transparent: true })
  )

  lcd.rotation.x = -Math.PI/2;
  lcd.rotation.z = Math.PI;
  lcd.position.set(0, 0.4,-0.24);

  const mtlLoader = new MTLLoader();
  let track;
  mtlLoader.load('/assets/models/track.mtl', (materials) => {
    materials.preload();

    const objLoader = new OBJLoader();
    objLoader.setMaterials(materials);

    objLoader.load('/assets/models/track.obj', (object) => {
      track = object;
      track.position.x -= 0.3;
      track.rotation.x = Math.PI/2;
      track.rotation.y = Math.PI;
      scene.add(track);
    });
  });
  mtlLoader.load('/assets/models/trash.mtl', (materials) => {
    materials.preload();

    const objLoader = new OBJLoader();
    objLoader.setMaterials(materials);

    objLoader.load('/assets/models/trash.obj', (object) => {
      object.position.x =-18;
      object.position.y =-24;
      object.position.z =-10;
      object.rotation.x = Math.PI/2;
      object.rotation.y = Math.PI/2;
      scene.add(object);
    });
  });
  mtlLoader.load('/assets/models/wheel.mtl', (materials) => {
    materials.preload();

    const objLoader = new OBJLoader();
    objLoader.setMaterials(materials);

    objLoader.load('/assets/models/wheel.obj', (object) => {
      wheell = object;
      wheell.position.set(0.8, 0.2, 0.78);
      wheell.rotation.x = Math.PI/2;
      wheell.rotation.y = Math.PI;
    });
  });
  mtlLoader.load('/assets/models/wheel.mtl', (materials) => {
    materials.preload();

    const objLoader = new OBJLoader();
    objLoader.setMaterials(materials);

    objLoader.load('/assets/models/wheel.obj', (object) => {
      wheelr = object;
      wheelr.position.set(-0.8, 0.2, 0.78);
      wheelr.rotation.x = Math.PI/2;
    });
  });
  mtlLoader.load('/assets/models/car.mtl', (materials) => {
    materials.preload();

    const objLoader = new OBJLoader();
    objLoader.setMaterials(materials);

    objLoader.load('/assets/models/car.obj', (object) => {
      car = object;
      car.rotation.x = Math.PI/2;
      car.rotation.y = Math.PI;
      scene.add(car);
      car.add(lcd);
      car.add(wheell);
      car.add(wheelr);
    });
  });
}

window.addEventListener("resize", () => {
  renderer.setSize(window.innerWidth, window.innerHeight);
  renderer.setPixelRatio(window.devicePixelRatio);
})

function toggle_scroll(s){
  if(s){
    document.body.style.overflow = 'scroll';
  }else{
    document.body.style.overflow = 'hidden';
  }
}

const initial_pos = new THREE.Vector3(0,-0.23, 0.7);
function end_boot(){
  bootScreen.classList.add("fade");
  canv.classList.add("show");
  animate();

  setTimeout(() => {
    bootScreen.style.display = "none";

    camera.position.copy(initial_pos);
    write_lcd(0).then(zoom_out);
  }, 1200);
}

let lcd_messages = [
  ['WELCOME TO', 'AEROBOTIX!'],
  ['BEST CLUB', 'EVER!!!!'],
  ['About', 'AEROBOTIX'],
  [' # # # # #', '# # # # # '],
  ['What We\'ve', 'Acheived?'],
  ['EL...', 'STAFF-O!!!'],
  ['Come', 'On In B)'],
  ['IK U', 'Want to ;)'],
  ['just...', ' ...JOIN!'],
];
let lcd_char_speed = 100;
let lcd_write_id =-1;
function write_lcd(id){
  if(id == lcd_write_id) return;
  let [msg1, msg2] = lcd_messages[id];
  lcd_write_id = id;
  write_lcd_(0, lcd_write_id);
  return new Promise(x => setTimeout(x, (msg1.length + msg2.length)*lcd_char_speed));
}

function write_lcd_(i, id){
  let [msg1, msg2] = lcd_messages[id];
  if(lcd_write_id != id){
    return;
  }
  textctx.fillStyle = 'blue';
  textctx.fillRect(0, 0, 800, 400);
  textctx.fillStyle = 'white';
  textctx.font = "120px Doto";
  let l1 = msg1.length,
      l2 = msg2.length;
  textctx.fillText(msg1.slice(0, Math.min(i+1, l1)), 50, 150);
  if(i >= l1){
    textctx.fillText(msg2.slice(0, Math.min(i-l1+1, l2)), 50, 320);
  }
  texture.needsUpdate = true;
  if(i < l1 + l2){
    setTimeout(write_lcd_, lcd_char_speed, i+1, id);
  }
}

function zoom_out(){
  add_anim(initial_pos.x, 2.2, camera.position, 'x', 120);
  add_anim(initial_pos.y, 0,   camera.position, 'y', 150, true);
  add_anim(initial_pos.z, 3, camera.position, 'z', 150);
  contents[0].classList.add('show');
}

function step_animations(){
  for(let a of running_animations){
    if(a.done) continue;
    a.t++;
    if(a.t > a.maxT){
      a.done = true;
      if(a.is_first_cam_zoom){
        toggle_scroll(true);
        finished_zooming = true;
      }
      continue;
    }

    let t = a.t / a.maxT;
    t = -(Math.cos(Math.PI * t) - 1) / 2;
    a.pos[a.val] = a.v1*(1-t) + a.v2*t;
  }
  running_animations = running_animations.filter(x => !x.done);
}

function render(){
  renderer.render(scene, camera);
}

export {
  camera,
  car,
  wheell,
  wheelr,
  finished_zooming,
  contents,
  add_anim,
  write_lcd,
  step_animations,
  render,
};

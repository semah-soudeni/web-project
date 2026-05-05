import * as THREE from "three";

import {
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
} from '/assets/js/aerob.js';

function show_content(i){
  for(let j=0;j<contents.length;j++){
    contents[j].classList.remove('show');
  }
  if(i >= 0)
    contents[i].classList.add('show');
}

let scrollY = 0;

let heading = new THREE.Vector3(0,-1, 0);
let pos = 0,
  posi  = 0,
  desired_pos  = 0,
  desired_posi = 0,
  vel = 0,
  acc = 0;
let maxacc = 0.18;

let camp = 0,
  cami = 0,
  camd = new THREE.Vector3();

window.addEventListener("scroll", (e) => {
  scrollY = window.scrollY;
  console.log(scrollY);
})

let car_track = [
  { p: new THREE.Vector3(0, 0, 0), r: new THREE.Euler(Math.PI/2, Math.PI, 0), a: 0.08 },
  { p: new THREE.Vector3(0,-7, 0), r: new THREE.Euler(Math.PI/2, Math.PI, 0), a: 0.18 },
  { p: new THREE.Vector3(2.3,-7.6, 0), r: new THREE.Euler(Math.PI/2,-Math.PI/2, 0), a: 0.18 },
  { p: new THREE.Vector3(4.5,-9, 0), r: new THREE.Euler(Math.PI/2, Math.PI, 0), a: 0.05 },
  { p: new THREE.Vector3(4.2,-36, 0), r: new THREE.Euler(Math.PI/2, Math.PI, 0), a: 0.08 },
  { p: new THREE.Vector3(4.2,-36, 0), r: new THREE.Euler(Math.PI/2, Math.PI, 0), a: 0.03 },
  { p: new THREE.Vector3(4.2,-41, 0), r: new THREE.Euler(Math.PI/2, Math.PI, 0), a: 0.08 },
  { p: new THREE.Vector3(4.2,-47.5, 0), r: new THREE.Euler(Math.PI/2, Math.PI, 0), a: 0.18 },
  { p: new THREE.Vector3(4.2,-48, 0), r: new THREE.Euler(Math.PI/2,-3*Math.PI/4, 0), a: 0.18 },
  { p: new THREE.Vector3(4.8,-48.25, 0), r: new THREE.Euler(Math.PI/2,-Math.PI/2, 0), a: 0.08 },
  { p: new THREE.Vector3(50.2,-48.7, 0), r: new THREE.Euler(Math.PI/2,-Math.PI/2, 0), a: 0.08 },
  { p: new THREE.Vector3(60.2,-48.7, 0), r: new THREE.Euler(Math.PI/2,-Math.PI/2, 0), a: 0.08 },
  { p: new THREE.Vector3(70.2,-48.7, 0), r: new THREE.Euler(Math.PI/2,-Math.PI/2, 0), a: 0.08 },
];

let car_loop_track = [];
let detail = 32;
let rad = 2.65;
for(let i=0;i<detail;i++){
  if(i < detail/4){
    let theta = i/detail*4*Math.PI;
    car_loop_track.push({
      p: new THREE.Vector3(1.5+Math.cos(theta)*rad, -36-Math.sin(theta)*rad, 0),
      r: new THREE.Euler(Math.PI/2, Math.PI - theta, 0),
    });
  }else if(i < detail*3/4){
    let theta = (i-detail/4)/detail*4*Math.PI;
    car_loop_track.push({
      p: new THREE.Vector3(1.3-2*rad+Math.cos(theta)*rad, -36+Math.sin(theta)*rad, 0),
      r: new THREE.Euler(Math.PI/2, theta, 0),
    });
  }else{
    let theta = Math.PI + (i-detail*3/4)/detail*4*Math.PI;
    car_loop_track.push({
      p: new THREE.Vector3(1.5+Math.cos(theta)*rad, -36-Math.sin(theta)*rad, 0),
      r: new THREE.Euler(Math.PI/2, Math.PI - theta, 0),
    });
  }
}

let loopi = 0;

let cam_track = [
  { p: new THREE.Vector3(2.2, 0, 3) },
  { p: new THREE.Vector3(0,-8, 3) },
  { p: new THREE.Vector3(0,-15, 8) },
  { p: new THREE.Vector3(0,-15, 8) },
  { p: new THREE.Vector3(-8,-36, 9) },
  { p: new THREE.Vector3(-8,-36, 9) },
  { p: new THREE.Vector3(4.2,-40, 3) },
  { p: new THREE.Vector3(20,-60, 4) },
  { p: new THREE.Vector3(30,-60, 4) },
  { p: new THREE.Vector3(50,-48.25, 4) },
  { p: new THREE.Vector3(60,-48.25, 3) },
  { p: new THREE.Vector3(70,-48.25, 3) },
];

car_track = car_track.map((x) => {
  let r = new THREE.Quaternion();
  r.setFromEuler(x.r);
  return {p: x.p, r, a: x.a};
});

car_loop_track = car_loop_track.map((x) => {
  let r = new THREE.Quaternion();
  r.setFromEuler(x.r);
  return {p: x.p, r, a: x.a};
});

function update_car(dt){
  let from,
    to;

  if(posi == 4 && (desired_posi == 4 || loopi != 0)){ // the car is in loop
    acc = 0.4;
    if(desired_posi != 4){
      acc = 1.5;
    }
    if(pos > 1){
      pos--;
      loopi++;
    }
    if(pos <= 0){
      pos++;
      loopi--;
    }
    loopi = loopi%car_loop_track.length;

    from = car_loop_track[loopi];
    to = car_loop_track[(loopi+1)%car_loop_track.length];
    if(desired_posi != 4 && loopi == 0){
      pos = 1.1;
      update_car(dt);
      return;
    }
  }else{
    loopi = 0;

    while(pos > 1 && posi < car_track.length-2){
      pos  -= 1;
      posi += 1;
    }
    while(pos < 0 && posi > 0){
      pos  += 1;
      posi -= 1;
    }

    pos = clamp(pos, 0, 1);

    maxacc = car_track[posi].a;

    if(posi < desired_posi){
      acc = maxacc;
    }else if(posi > desired_posi){
      acc =-maxacc;
    }else{
      acc = clamp(desired_pos - pos, -maxacc, maxacc)
    }

    from = car_track[posi];
    to = car_track[posi+1];
  }

  vel += acc * dt;
  vel *= 0.85;
  pos += vel * dt;
  let q = new THREE.Quaternion();
  q.slerpQuaternions(from.r, to.r, pos);
  car.position.lerpVectors(from.p, to.p, pos);
  car.rotation.setFromQuaternion(q);
  heading.set(0, 0,-1);
  heading.applyEuler(car.rotation);

  let d = from.p.distanceTo(to.p);
  wheell.rotation.x -= vel*d*2.5*dt;
  wheelr.rotation.x -= vel*d*2.5*dt;
}


let step = 0;
let past_time = 0;
function animate(current_time) {
  let dt = current_time - past_time;
  past_time = current_time;
  if(dt > 32) dt = 32;
  dt *= 0.01;

  requestAnimationFrame(animate);
  step_animations();

  if(step != 4){
    camera.rotation.x = 25*camera.rotation.x/26;
  }
  if(step != 5 && step != 6){
    camera.rotation.z = 25*camera.rotation.z/26;
  }
  switch(step){
    case 0: // lcd welcome scene + zoom out
      if(finished_zooming) step++;
      break;
    case 1: // 1st part of html, straight line, 0 < scrollY < 500
      if(scrollY > 500) step = 2;
      if(scrollY < 250) write_lcd(0);
      else write_lcd(1);
      show_content(0);
      camd.lerpVectors(cam_track[cami].p, cam_track[cami+1].p, camp);
      camera.position.lerp(camd, 0.03);

      camp = scrollY / 500;
      cami = 0;

      desired_posi = 0;
      desired_pos = scrollY / 500;

      update_car(dt);
      break;
    case 2: // small curve, 500 < scrollY < something idk
      if(scrollY <  500) step = 1;
      if(scrollY > 1600) step = 3;
      write_lcd(2);
      camd.lerpVectors(cam_track[cami].p, cam_track[cami+1].p, camp);
      camera.position.lerp(camd, 0.03);

      if(scrollY < 600){
        show_content(-1);
      }else{
        show_content(1);
      }

      if(scrollY < 800){
        desired_posi = 1;
        desired_pos = (scrollY-500)/300;
        cami = 1;
        camp = (scrollY-500)/300;
      }else if(scrollY < 1000){
        desired_posi = 2;
        desired_pos = (scrollY-800)/200;
        cami = 2;
        camp = (scrollY-800)/200;
      }else{
        desired_posi = 3;
        desired_pos = (scrollY-1000)/600;
        cami = 2;
        camp = (scrollY-1000)/600;
      }

      update_car(dt);
      break;
    case 3:
      if(scrollY < 1600) step = 2;
      if(scrollY > 3100) step = 4;
      write_lcd(3);
      camd.lerpVectors(cam_track[cami].p, cam_track[cami+1].p, camp);
      camera.position.lerp(camd, 0.03);

      desired_posi = 4;
      desired_pos = (scrollY - 1600) / 500;

      if(scrollY < 1800){
        show_content(-1);
        cami = 3;
        camp = (scrollY - 1600) / 200;
      }else{
        show_content(2);
        cami = 4;
        camp = (scrollY - 1800) / 600;
      }

      update_car(dt);
      break;
    case 4:
      if(scrollY < 3100) step = 3;
      if(scrollY > 5800) step = 5;
      write_lcd(4);
      camd.lerpVectors(cam_track[cami].p, cam_track[cami+1].p, camp);
      if(scrollY < 3800 && loopi == 0){
        camd.y = car.position.y-1;
      }
      camera.position.lerp(camd, 0.03);

      if(scrollY < 3800){
        desired_posi = 5;
        desired_pos = (scrollY - 3100) / 700;

        cami = 5;
        camp = Math.min((scrollY - 3100) / 400, 1);
        camera.rotation.x = 25*camera.rotation.x/26;
      }else if(scrollY<4400){
        desired_posi = 6;
        desired_pos = (scrollY - 3800) / 700;

        cami = 6;
        camp = (scrollY - 3800) / 700;
        camera.rotation.x = 25*camera.rotation.x/26;
        show_content(-1);
      }else{
        desired_posi = 9;
        desired_pos = (scrollY - 4400) / 1400;

        camera.rotation.x = (Math.PI/4 + 25*camera.rotation.x)/26;
        cami = 7;
        camp = (scrollY - 4400) / 700;
        show_content(3);
        contents[3].style.transform = `translateX(${window.innerWidth + 4200 - Math.floor(scrollY)}px)`;
      }

      update_car(dt);
      break;
    case 5:
      if(scrollY < 5800) step = 4;
      if(scrollY > 6800) step = 6;
      write_lcd(5);
      show_content(4);
      camd.lerpVectors(cam_track[cami].p, cam_track[cami+1].p, camp);
      camera.position.lerp(camd, 0.03);
      cami = 9;
      camp = (scrollY - 5800) / 1000;
      camera.rotation.z = (Math.PI/2 + 20*camera.rotation.z)/21;
      desired_posi = 10;
      desired_pos = (scrollY - 5800) / 1000;
      update_car(dt);
      break;
    case 6:
      if(scrollY < 6800) step = 5;
      write_lcd(6+(Math.floor(current_time/4000)%3));
      show_content(5);
      camd.lerpVectors(cam_track[cami].p, cam_track[cami+1].p, camp);
      camera.position.lerp(camd, 0.03);
      cami = 10;
      camp = Math.min((scrollY - 6800) / 350, 1);
      camera.rotation.z = (Math.PI/2 + 25*camera.rotation.z)/26;
      desired_posi = 11;
      desired_pos = Math.min((scrollY - 6800) / 350, 1);
      update_car(dt);
      break;
  }
  render();
}

function clamp(x, m, M){
  return x < m ? m : (x > M ? M : x);
}

export { animate };

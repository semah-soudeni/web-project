const canvas = document.getElementById('scroll-follower')
const ctx = canvas.getContext('2d')
const bgcanvas = document.getElementById('bg-canvas')
const bgctx = bgcanvas.getContext('2d')

let maxSpeed = 0.04
let scrollYReal = 0;
let scrollY = 0;
let maxHeadingSpeed = 0.05
let heading = Math.PI/2;

let planes = []

for(let i=0;i<50;i++){
  let x = Math.random()*bgcanvas.width
  let y = Math.random()*bgcanvas.height
  let h = Math.random()*2*Math.PI
  planes.push({x, y, h})
}

function resize(){
  const par = document.getElementsByClassName('parallax')[0]
  canvas.width = par.clientWidth
  canvas.height = par.clientHeight
  bgcanvas.width = document.body.clientWidth
  bgcanvas.height = document.body.clientHeight
  draw()
}
resize()

window.addEventListener('resize', resize)

window.addEventListener("scroll", () => {
  const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
  const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
  scrollYReal = scrollTop / scrollHeight;

  const scrollStart = 0.18
  const scrollEnd = 0.9
  scrollYReal = Math.min(Math.max(0, scrollYReal/(scrollEnd-scrollStart)-scrollStart), 1)
  //requestAnimationFrame(draw);
});

function draw() {
  let diff = Math.max(-maxSpeed, Math.min(scrollYReal-scrollY, maxSpeed))
  scrollY += diff*0.03;
  const w = canvas.width, h = canvas.height
  ctx.clearRect(0, 0, w, h);

  const points = [
    {x: w/2, y: h/15-w/6},
    {x: w/2, y: h/5},
    {x: w*3/4, y: h/4},
    {x: w/2, y: h*3/10},
    {x: w/2, y: h*2/3},
    {x: w/2, y: h*7/9},
    {x: w/2, y: h*14/15-w/6},
  ]

  ctx.fillStyle = '#184167';
  ctx.strokeStyle = '#402663';
  ctx.lineWidth = 3
  //ctx.fillRect(0, 0, w, h);
  //ctx.fillStyle = '#f0f0f0';
  ctx.fillRect(w/3, h/15-w/3, w/3, w/3);
  ctx.setLineDash([])
  ctx.beginPath()
  ctx.moveTo(w/2, h/15)
  ctx.lineTo(w/2, h/5)
  ctx.lineTo(w/4, h/4)
  ctx.moveTo(w/2, h/5)
  ctx.lineTo(w*3/4, h/4)
  ctx.lineTo(w/2, h*3/10)
  ctx.lineTo(w/2, h*7/20)
  ctx.stroke()

  ctx.setLineDash([10, 10])
  ctx.beginPath()
  ctx.moveTo(w/2, h*7/20)
  ctx.lineTo(w/2, h*3/5)
  ctx.stroke()

  ctx.setLineDash([])
  ctx.beginPath()
  ctx.moveTo(w/2, h*3/5)
  ctx.arc(w/2, h*13/18, h/18,-Math.PI/2, Math.PI/2, false)
  ctx.lineTo(w/2, h*13.5/15)
  ctx.stroke()
  ctx.fillRect(w/3, h*14/15-w/3, w/3, w/3);

  /*for(let p of points){
    ctx.beginPath();
    ctx.arc(p.x, p.y, 5, 0, 2 * Math.PI);
    ctx.fill();
  }*/

  let posx = canvas.width/2
  let posy = scrollY*(points[points.length-1].y-points[0].y)+points[0].y
  let t = 6;
  let innert = 0;
  for(let i=1;i<points.length;i++){
    if(posy < points[i].y){
      t = i
      innert = (points[i].y-posy)/(points[i].y-points[i-1].y)
      break;
    }
  }

  posx = points[t-1].x*innert + points[t].x*(1-innert)
  let headingReal = Math.atan2(points[t].y - points[t-1].y, points[t].x - points[t-1].x)
  maxSpeed = 0.05
  maxHeadingSpeed = 0.05
  if(t == 4 && innert<0.05){
    maxSpeed = 0.01
    maxHeadingSpeed = 0.03
  }
  if(t == 5){
    if(innert>0.8 || innert<0.2){
      maxSpeed = 0.01
      maxHeadingSpeed = 0.03
    }
    posx = w/2+Math.sqrt(0.25-Math.pow(innert-0.5, 2))*h/9
    headingReal = 0*innert + Math.PI*(1-innert)
  }
  if(t == 6 && innert>0.95){
    maxSpeed = 0.01
    maxHeadingSpeed = 0.03
  }
  let headingDiff = Math.max(-maxHeadingSpeed, Math.min(headingReal-heading, maxHeadingSpeed))
  heading += headingDiff;
  ctx.save()
  ctx.translate(posx, posy)
  ctx.rotate(heading)
  ctx.fillStyle = '#000000';
  ctx.fillRect(-12, -12, 10, 5)
  ctx.fillRect(-12, 7.5, 10, 5)
  ctx.fillStyle = '#895129';
  ctx.fillRect(-10, -10, 20, 20)
  ctx.fillStyle = '#dfdfdf';
  ctx.fillRect(-8, -7.5, 10, 15)
  ctx.fillStyle = '#ffffce';
  ctx.beginPath()
  ctx.arc(7,-7, 2, 0, 2*Math.PI, false)
  ctx.arc(7, 0, 2, 0, 2*Math.PI, false)
  ctx.arc(7, 7, 2, 0, 2*Math.PI, false)
  ctx.fill()
  ctx.restore()
  //console.log(heading)

  bgctx.fillStyle = '#2e7eae';
  bgctx.fillRect(0, 0, bgcanvas.width, bgcanvas.height);
  for(let p of planes){
    drawPlane(p.x, p.y, p.h)
    p.x += 0.5*Math.cos(p.h)
    p.y += 0.5*Math.sin(p.h)
    if(p.x < 0)
      p.x = bgcanvas.width
    if(p.x > bgcanvas.width)
      p.x = 0
    if(p.y < 0)
      p.y = bgcanvas.width
    if(p.y > bgcanvas.height)
      p.y = 0

    p.h += 0.01 * (Math.random()*2. - 1.)
  }
  requestAnimationFrame(draw);
}

function drawPlane(x, y, h){
  bgctx.save()
  bgctx.translate(x, y)
  bgctx.rotate(h)
  bgctx.fillStyle = '#eeeeee';
  bgctx.fillRect(-15, -3, 30, 6)
  bgctx.fillRect(5, -15, 7, 30)
  bgctx.fillRect(-15, -5, 4, 10)
  bgctx.restore()
}

draw();

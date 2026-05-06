const clubs = ['aero', 'ieee', 'secu', 'acm'];

function show(loc){
  clubs.forEach(club => {
    if(club == loc) document.getElementById(club).style.opacity = 1;
    else document.getElementById(club).style.opacity = 0;
  });
}

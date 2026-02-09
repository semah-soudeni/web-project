const sidebar = document.getElementById('sidebar')
const p = document.getElementById('test')

const openWidth = '30%'

function openBar(loc){
  p.innerText = loc
  sidebar.style.display = 'block'
  setTimeout(()=>{
    sidebar.style.width = openWidth
  }, 0)
}

function closeBar(){
  sidebar.style.width = '0%'
  
}

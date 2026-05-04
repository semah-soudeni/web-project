const query = window.location.search;

const urlParams = new URLSearchParams(query)

if (urlParams.has('eventName')){
        
    eventName = urlParams.get('eventName')

    
    const title = document.getElementById("title"); 

    if (title)
        title.innerHTML = "Edit " + eventName
    else 
        console.error("Could not find title")
    
    const button = document.getElementById("submit-btn")
    if (button)
        button.value = "Edit event"
    else 
        console.error("Could not find submit button")
    fetch("../../backend/getEventByName.php", {
            method: 'POST',
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `eventName=${encodeURIComponent(eventName)}`
        })
    .then(response => response.json())
    .then(data => setData(data))
    .catch(error => console.error(error))
}
function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}
let teammateCount = -1;
function setData(data){
    if (data) {
        const form = document.getElementById('add-event');
        if (!form) return;

        console.log(data);
        // Set select field
        if (data[0].event_type) {
            form.querySelector('select[name="Event_Type"]').value = capitalizeFirstLetter(data[0].event_type);
        }

        // Set simple input/textarea fields
        const fieldsNames = ['description', 'title', 'date', 'time', 'duration', 'prize', 'location', 'max_attendees'];
        const fields = ['description', 'title','event_date','event_time','duration','prize_pool','location','max_attendees'];
        for (let index = 0; index < fields.length; index++) {
            if (data[0][fields[index]] !== undefined) {
                const el = form.querySelector(`[name="${fieldsNames[index]}"]`);
                if (el) el.value = data[0][fields[index]];
            }
        }

        // Set participating clubs (drag-and-drop)
        const leftColumn = document.getElementById('left');
        const rightColumn = document.getElementById('right');
        const dropHint = document.getElementById('drop-hint');

        data.clubs.forEach(d => {
          const clubEl = leftColumn.querySelector(`.list[data-club="${d.name}"]`);
          if (clubEl) {
            rightColumn.appendChild(clubEl);
            if (dropHint) dropHint.style.display = 'none';
          }
        });


        const teammatesList = document.getElementById('teammates-list');
        teammatesList.innerHTML = '';

              teammateCount++;
              let index = teammateCount;
        data.staff.forEach(member => {
              const memberEl = document.createElement('div');
              memberEl.classList.add('teammate-entry');
              memberEl.dataset.index = index;
              memberEl.innerHTML = `
                <input type="email" name="staffmember[${index}][email]" value="${member.email}" required>
                <input type="text"  name="staffmember[${index}][role]"  value="${member.role}">
                <div class="teammate-photo-wrapper">
                    
                    <img 
                        id="preview-${index}" 
                        class="teammate-photo-preview" 
                        src="${member.photo}" 
                        alt="Preview"
                        style="display:block"
                    >

                    <input 
                        type="file" 
                        class="teammate-file-input" 
                        name="staffmember[${index}][photo]" 
                        accept="image/*"
                        onchange="previewTeammatePhoto(this, ${index})"
                    >

                </div>
                <button type="button" class="remove-teammate-btn" onclick="this.closest('.teammate-entry').remove()">&#x2715;</button>`;


              teammatesList.appendChild(memberEl);
        });
        
        form.onsubmit = function (e) {
            e.preventDefault();
            handleEdit(data[0].id , data.staff);
        };
    }
}

function handleEdit(event_id , staff){
    const form = document.getElementById("add-event");
    const data = new FormData(form);
    data.append("other_participating_clubs", JSON.stringify(getClubsNames()));
    data.append("event_id",event_id);

    for (let index = 0; index < staff.length; index++) {
        if (!data.get(`staffmember[${index}][photo]`)["name"] && staff[index].photo){
            let photo = data.get(`staffmember[${index}][photo]`)
            photo["name"] = staff[index].photo;
        }
    }
    for (let [key, value] of data.entries()) {
        console.log(key, value);
    }
    

    fetch("../../backend/editEvent.php",{
        method: 'POST',
        body: data
    })
    .then(response => response.json())
    .then(data => handleRequest(data))
    .catch(error => console.error(error))
}

(function () {
    const leftBox  = document.getElementById("left");
    const rightBox = document.getElementById("right");
    const dropHint = document.getElementById("drop-hint");

    let dragged   = null;   
    let ghost     = null;   
    let offsetX   = 0;
    let offsetY   = 0;

    function updateHint() {
        dropHint.style.display =
            rightBox.querySelectorAll(".list").length > 0 ? "none" : "block";
    }

    function createGhost(card) {
        const g = card.cloneNode(true);
        g.style.cssText = `
            position: fixed;
            pointer-events: none;
            opacity: 0.75;
            z-index: 9999;
            width: ${card.offsetWidth}px;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        `;
        document.body.appendChild(g);
        return g;
    }

    function getColumn(x, y) {
        const rRect = rightBox.getBoundingClientRect();
        const lRect = leftBox.getBoundingClientRect();
        if (x >= rRect.left && x <= rRect.right && y >= rRect.top && y <= rRect.bottom) return rightBox;
        if (x >= lRect.left && x <= lRect.right && y >= lRect.top && y <= lRect.bottom) return leftBox;
        return null;
    }

    function onMouseDown(e) {
        const card = e.currentTarget;
        e.preventDefault();

        dragged = card;
        const rect = card.getBoundingClientRect();
        offsetX = e.clientX - rect.left;
        offsetY = e.clientY - rect.top;

        ghost = createGhost(card);
        ghost.style.left = (e.clientX - offsetX) + "px";
        ghost.style.top  = (e.clientY - offsetY) + "px";

        card.style.opacity = "0.3";
        document.addEventListener("mousemove", onMouseMove);
        document.addEventListener("mouseup",   onMouseUp);
    }

    function onMouseMove(e) {
        if (!ghost) return;
        ghost.style.left = (e.clientX - offsetX) + "px";
        ghost.style.top  = (e.clientY - offsetY) + "px";

        // highlight drop zones
        [leftBox, rightBox].forEach(col => col.classList.remove("drag-over"));
        const target = getColumn(e.clientX, e.clientY);
        if (target) target.classList.add("drag-over");
    }

    function onMouseUp(e) {
        document.removeEventListener("mousemove", onMouseMove);
        document.removeEventListener("mouseup",   onMouseUp);

        if (ghost) { ghost.remove(); ghost = null; }

        [leftBox, rightBox].forEach(col => col.classList.remove("drag-over"));

        if (dragged) {
            dragged.style.opacity = "1";
            const target = getColumn(e.clientX, e.clientY);
            if (target && target !== dragged.parentElement) {
                target.appendChild(dragged);
            }
            dragged = null;
            updateHint();
        }
    }

    document.querySelectorAll(".list").forEach(card => {
        card.style.opacity = "1"; 
        card.addEventListener("mousedown", onMouseDown);
    });
    updateHint();


})();


document.getElementById('add-teammate-btn').addEventListener('click', function () {
    teammateCount++;
    const index = teammateCount;

    const entry = document.createElement('div');
    entry.classList.add('teammate-entry');
    entry.dataset.index = index;

    entry.innerHTML = `
        <input type="email" name="staffmember[${index}][email]" placeholder="Email" required>
        <input type="text"  name="staffmember[${index}][role]"  placeholder="Role ">
        <div class="teammate-photo-wrapper">
            <input type="file" class="teammate-file-input" name="staffmember[${index}][photo]" accept="image/*" 
                onchange="previewTeammatePhoto(this, ${index})">
            <img id="preview-${index}" class="teammate-photo-preview" src="" alt="Preview">
        </div>
        <button type="button" class="remove-teammate-btn" onclick="this.closest('.teammate-entry').remove()">&#x2715;</button>
    `;

    document.getElementById('teammates-list').appendChild(entry);
});

function previewTeammatePhoto(input, index) {
    const preview = document.getElementById('preview-' + index);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}





function getClubsNames() {
    const right_block = document.getElementById("right");
    const participating_clubs = right_block.getElementsByClassName("list");
    const array = Array.from(participating_clubs).map(el => el.getAttribute("data-club"));
    console.log(array);
    return array;
}

function sendData() {
    const form = document.getElementById("add-event");
    const data = new FormData(form);
    data.append("other_participating_clubs", JSON.stringify(getClubsNames()));

    fetch('../../backend/addEvent.php', {
    method: 'POST',
    body: data
    })
    .then(response =>{
        return response.json();
    })
    .then(d => handleRequest(d))
    .catch(error => console.error(error));
}

const errorBox   = document.getElementById("error");
const successBox = document.getElementById("success");  
const addEventContainer = document.getElementsByClassName("add-event-container")[0];
function toggleBox(box) {
    if (box == null) return;
    setTimeout(() => {
    function handler(event) {
      if (!box.contains(event.target)) {
        box.style.visibility = "hidden";
        addEventContainer.style.filter = "none";
        document.removeEventListener('click', handler);
      }
    }
    document.addEventListener('click', handler);
    }, 0);
}

function handleRequest(data) {
    console.log(data);
    const box = data.success ? successBox : errorBox;
    
    const message = document.getElementById("panel-message") ;
    message.innerHTML = data.message;

    box.style.visibility = "visible";
    addEventContainer.style.filter = "blur(3px)";
    toggleBox(box);
}




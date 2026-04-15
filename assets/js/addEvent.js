(function () {
    const leftBox  = document.getElementById("left");
    const rightBox = document.getElementById("right");
    const dropHint = document.getElementById("drop-hint");

    let dragged   = null;   // the card being moved
    let ghost     = null;   // the visual clone following the cursor
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

let teammateCount = 0;

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
    return Array.from(participating_clubs).map(el => el.getAttribute("data-club"));
}

function sendData() {
    const form = document.getElementById("add-event");
    const data = new FormData(form);
    data.append("other_participating_clubs", getClubsNames());

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
    const box = data.success ? successBox : errorBox;
    
    document.querySelector(".panel-message").textContent = data.message;
    box.style.visibility = "visible";
    addEventContainer.style.filter = "blur(3px)";
    toggleBox(box);
}

function togglePanel() {
    document.getElementById('ebPanel').classList.toggle('open');
}

var dropdownOpen = false;
function toggleDropdown() {
    dropdownOpen = !dropdownOpen;
    document.getElementById('dropdownMenu').classList.toggle('open', dropdownOpen);
    document.getElementById('dropdownTrigger').classList.toggle('active', dropdownOpen);
}

function selectOption(el, value, label) {
    document.getElementById('receiverValue').value = value;
    var lbl = document.getElementById('dropdownLabel');
    lbl.textContent = label;
    lbl.classList.remove('placeholder');
    document.querySelectorAll('.eb-dropdown-option').forEach(function(o) { o.classList.remove('selected'); });
    el.classList.add('selected');
    dropdownOpen = false;
    document.getElementById('dropdownMenu').classList.remove('open');
    document.getElementById('dropdownTrigger').classList.remove('active');
}

document.getElementById('msgInput').addEventListener('input', function() {
    document.getElementById('charCount').textContent = this.value.length + ' characters';
});

document.addEventListener('click', function(e) {
    var dd = document.getElementById('receiverDropdown');
    if (dd && !dd.contains(e.target)) {
      dropdownOpen = false;
      document.getElementById('dropdownMenu').classList.remove('open');
      document.getElementById('dropdownTrigger').classList.remove('active');
    }
});

const broadcastForm = document.querySelector('form[action="../backend/broadcast.php"]');
const modal = document.getElementById('broadcastModal');

if (broadcastForm) {
  broadcastForm.addEventListener('submit', function(e) {
      e.preventDefault();

      const receivers = document.getElementById('dropdownLabel').textContent.trim();
      const subject   = document.getElementById('subjectInput').value.trim();
      const message   = document.getElementById('msgInput').value.trim();

      if (!document.getElementById('receiverValue').value) {
        alert('Please select a recipient group.');
        return;
      }

      document.getElementById('modal-receivers').textContent = receivers;
      document.getElementById('modal-subject').textContent   = subject || '(no subject)';
      document.getElementById('modal-preview').textContent   = message.length > 120
        ? message.slice(0, 120) + '…'
        : message;

      modal.style.display = 'flex';
  });
}

function closeModal() {
  modal.style.display = 'none';
}

function confirmSend() {
  modal.style.display = 'none';
  submitEmail(new FormData(broadcastForm));
}

// Close on backdrop click
modal.addEventListener('click', function(e) {
  if (e.target === modal) closeModal();
});


function submitEmail(data){
    fetch("../../backend/broadcast.php",{
        method : 'POST',
        body : data
    })
    .then(response => response.json())
    .then(Data => handleSubmission(Data))
    .catch(error => console.error(error))
}
function handleSubmission(Data){
    showToast(Data.success ? "successToast" : "errorToast");
}

function showToast(toastId, autoHide = true) {
  console.log("here"); 
  const toast = document.getElementById(toastId);
  if (!toast) return;
  toast.classList.remove('hiding');
  toast.classList.add('show');
  
  if (autoHide) {
    setTimeout(() => {
      hideToast(toastId);
    }, 5000); 
  }
}

function hideToast(toastId) {
  const toast = document.getElementById(toastId);
  if (!toast) return;
  
  toast.classList.add('hiding');
  setTimeout(() => {
    toast.classList.remove('show', 'hiding');
  }, 300); 
}



let eventToDelete = null;

function editEvent(eventName) {
    fetch("../../backend/getEventByName.php",{
        method: "POST",
        body: eventName
    })
    .then(response => response.json())
    .then(response => handleEdit(response))
    .catch(error => console.error(error))
}
function handleEdit(data){
   const URL = `/pages/addEvent.php?Event_Type=${data.event.event_type}&description=${data.event.description}&title=${data.event.title}&date=${data.event.date}&time=${data.event.time}&duration=${data.event.duration}&prize=${data.event.prize_pool}&location=${data.event.location}&max_attendees=${data.event.max_attendees}`; 
    window.location.href = URL; 
}
function confirmDeleteEvent( eventName) {
  eventToDelete = eventName;
  document.getElementById('deleteEventName').textContent = eventName;
  document.getElementById('deleteModal').classList.add('show');
}

function closeDeleteModal() {
  document.getElementById('deleteModal').classList.remove('show');
  eventToDelete = null;
}

function deleteEvent() {
  if (eventToDelete === null) return;
  
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '../backend/deleteEvent.php';
  
  const input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'event_name';
  input.value = eventToDelete;
  
  form.appendChild(input);
  document.body.appendChild(form);
  form.submit();
}

document.getElementById('deleteModal')?.addEventListener('click', function(e) {
  if (e.target === this) {
    closeDeleteModal();
  }
});

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeDeleteModal();
  }
});

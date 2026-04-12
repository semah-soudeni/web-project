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

document.addEventListener('DOMContentLoaded', () => {
    // Set dynamic date for newspaper header
    const dateObj = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const dateString = dateObj.toLocaleDateString('en-US', options);

    const dateElement = document.getElementById('current-date');
    if (dateElement) {
        dateElement.textContent = dateString.toUpperCase();
    }
});

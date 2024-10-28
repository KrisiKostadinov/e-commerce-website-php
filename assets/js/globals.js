const form = document.getElementById("form");

if (form) {
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const submitButton = event.target.querySelector('button[type="submit"]');
        submitButton.setAttribute("disabled", true);
        submitButton.textContent = 'Изпращане...';

        event.target.submit();
    });
}

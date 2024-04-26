document.getElementById('signupForm').addEventListener('submit', function(event) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;
    const message = document.getElementById('message');

    if (password !== confirmPassword) {
        event.preventDefault(); // Stop the form from submitting
        message.textContent = 'Passwords do not match.';
        message.style.color = 'red';
    } else {
        message.textContent = '';
    }
});

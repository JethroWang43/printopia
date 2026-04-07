document.addEventListener('DOMContentLoaded', () => {

    // 🔹 Toggle password visibility
    const togglePassword = document.querySelector('.toggle-password');
    const passwordField = document.getElementById('password-field');

    if (togglePassword && passwordField) {
        togglePassword.addEventListener('click', () => {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
        });
    }

    // 🔹 Auto-hide flash messages after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.classList.add('fade-out');
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
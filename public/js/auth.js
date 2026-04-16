console.log("Auth JS loaded");

// Toggle password visibility
function togglePassword(fieldId = 'password-field') {
    const passwordField = document.getElementById(fieldId);
    if (!passwordField) {
        return;
    }

    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);

    const toggleButton = document.querySelector(`[data-toggle-password="${fieldId}"]`);
    if (toggleButton) {
        const isVisible = type === 'text';
        toggleButton.textContent = isVisible ? 'Hide' : 'Show';
        toggleButton.setAttribute('aria-label', isVisible ? 'Hide password' : 'Show password');
    }
}

// -------- LOGIN --------
const loginForm = document.getElementById('loginForm');
if(loginForm){
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch("/printopia/api/auth/login", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            if(res.status === "success"){
                alert("Login successful! Welcome " + res.user.first_name);
                console.log("JWT Token:", res.token);
                window.location.href = "/printopia/dashboard";
                // Optionally store JWT: sessionStorage.setItem('token', res.token);
            } else {
                alert("Error: " + (res.message || "Login failed"));
            }
        })
        .catch(err => console.error(err));
    });
}

// -------- SIGNUP --------
const signupForm = document.getElementById('signupForm');
if(signupForm){
    signupForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        // Password confirmation check
        if(data.password !== data.confirm_password){
            alert("Passwords do not match!");
            return;
        }

        delete data.confirm_password; // Remove confirm_password before sending

        fetch("/printopia/api/auth/register", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            if(res.status === "success"){
                alert("Registration successful! Please login.");
                window.location.href = "/printopia/login";
            } else {
                alert("Error: " + (res.message || "Registration failed"));
            }
        })
        .catch(err => console.error(err));
    });
}
// Admin Login JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('adminLoginForm');
    
    // Handle form submission
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const rememberMe = document.getElementById('rememberMe').checked;
        
        // Simple validation (in real app, this would be server-side)
        if (username === 'admin' && password === 'admin123') {
            // Store login status
            if (rememberMe) {
                localStorage.setItem('adminLoggedIn', 'true');
            } else {
                sessionStorage.setItem('adminLoggedIn', 'true');
            }
            
            // Redirect to dashboard
            window.location.href = 'admin-dashboard.html';
        } else {
            alert('Invalid credentials. Please try again.');
        }
    });
});

// Toggle password visibility
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleButton = document.querySelector('.toggle-password i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.classList.remove('fa-eye');
        toggleButton.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleButton.classList.remove('fa-eye-slash');
        toggleButton.classList.add('fa-eye');
    }
}

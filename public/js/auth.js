// Authentication JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // Registration Form Handler
    const registrationForm = document.getElementById('registrationForm');
    if (registrationForm) {
        registrationForm.addEventListener('submit', handleRegistration);
    }
    
    // Login Form Handler - COMMENTED OUT: Let server handle authentication
    // const loginForm = document.getElementById('loginForm');
    // if (loginForm) {
    //     loginForm.addEventListener('submit', handleLogin);
    // }
    
    // Real-time validation
    document.querySelectorAll('input[required], select[required]').forEach(input => {
        input.addEventListener('blur', validateField);
        input.addEventListener('input', clearFieldError);
    });
    
    // Password confirmation validation
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmPassword');
    
    if (confirmPassword) {
        confirmPassword.addEventListener('blur', validatePasswordMatch);
    }
    
    // Phone number formatting
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', formatPhoneNumber);
    }
    
    // Wedding date validation
    const weddingDate = document.getElementById('weddingDate');
    if (weddingDate) {
        // Set minimum date to today
        const today = new Date();
        today.setDate(today.getDate() + 30); // Minimum 30 days from today
        weddingDate.min = today.toISOString().split('T')[0];
    }
});

// Password toggle functionality
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const toggle = field.nextElementSibling;
    const icon = toggle.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// Form validation functions
function validateField(event) {
    const field = event.target;
    const value = field.value.trim();
    const fieldName = field.name;
    
    clearFieldError(event);
    
    switch (fieldName) {
        case 'firstName':
        case 'lastName':
            if (value.length < 2) {
                showFieldError(field, 'Name must be at least 2 characters long');
                return false;
            }
            break;
            
        case 'email':
            if (!window.WeddingBooking.validateEmail(value)) {
                showFieldError(field, 'Please enter a valid email address');
                return false;
            }
            break;
            
        // case 'phone':
        //     if (!window.WeddingBooking.validatePhone(value)) {
        //         showFieldError(field, 'Please enter a valid phone number');
        //         return false;
        //     }
        //     break;
            
        case 'password':
            if (value.length < 8) {
                showFieldError(field, 'Password must be at least 8 characters long');
                return false;
            }
            if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(value)) {
                showFieldError(field, 'Password must contain uppercase, lowercase, and number');
                return false;
            }
            break;
            
        // case 'partnerName':
        //     if (value && value.length < 2) {
        //         showFieldError(field, 'Partner name must be at least 2 characters long');
        //         return false;
        //     }
        //     break;
            
        case 'weddingDate':
            if (value) {
                const selectedDate = new Date(value);
                const minDate = new Date();
                minDate.setDate(minDate.getDate() + 30);
                
                if (selectedDate < minDate) {
                    showFieldError(field, 'Wedding date must be at least 30 days from today');
                    return false;
                }
            }
            break;
    }
    
    return true;
}

function validatePasswordMatch() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmPassword');
    
    if (password && confirmPassword) {
        if (password.value !== confirmPassword.value) {
            showFieldError(confirmPassword, 'Passwords do not match');
            return false;
        }
    }
    
    return true;
}

function showFieldError(field, message) {
    const formGroup = field.closest('.form-group');
    const errorElement = formGroup.querySelector('.form-error');
    
    formGroup.classList.add('error');
    errorElement.textContent = message;
    errorElement.style.display = 'block';
}

function clearFieldError(event) {
    const field = event.target;
    const formGroup = field.closest('.form-group');
    const errorElement = formGroup ? formGroup.querySelector('.form-error') : null;
    
    if (formGroup) {
        formGroup.classList.remove('error');
    }
    if (errorElement) {
        errorElement.style.display = 'none';
    }
}

// Form submission handlers
async function handleRegistration(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Validate all fields
    let isValid = true;
    form.querySelectorAll('input[required], select[required]').forEach(field => {
        if (!validateField({ target: field })) {
            isValid = false;
        }
    });
    
    // Check password match
    if (!validatePasswordMatch()) {
        isValid = false;
    }
    
    // Check terms acceptance
    const termsCheckbox = form.querySelector('#terms');
    if (!termsCheckbox.checked) {
        showFieldError(termsCheckbox, 'You must accept the terms and conditions');
        isValid = false;
    }
    
    if (!isValid) {
        window.WeddingBooking.showNotification('Please fix the errors above', 'error');
        return;
    }
    
    // Show loading state
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;
    
    // try {
    //     // Simulate API call
    //     await new Promise(resolve => setTimeout(resolve, 2000));
        
    //     // Store user data temporarily (in real app, this would be handled by backend)
    //     const userData = {
    //         firstName: formData.get('firstName'),
    //         lastName: formData.get('lastName'),
    //         email: formData.get('email'),
    //         phone: formData.get('phone'),
    //         partnerName: formData.get('partnerName'),
    //         weddingDate: formData.get('weddingDate'),
    //         venue: formData.get('venue'),
    //         newsletter: formData.get('newsletter') === 'on'
    //     };
        
    //     localStorage.setItem('weddingUserData', JSON.stringify(userData));
        
    //     window.WeddingBooking.showNotification('Account created successfully! Redirecting to dashboard...', 'success');
        
    //     // Redirect to dashboard
    //     setTimeout(() => {
    //         window.location.href = 'dashboard.html';
    //     }, 1500);
        
    // } catch (error) {
    //     console.error('Registration error:', error);
    //     window.WeddingBooking.showNotification('Registration failed. Please try again.', 'error');
    // } finally {
    //     submitBtn.classList.remove('loading');
    //     submitBtn.disabled = false;
    // }
}

async function handleLogin(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Basic validation
    const email = formData.get('email');
    const password = formData.get('password');
    
    if (!email || !password) {
        window.WeddingBooking.showNotification('Please fill in all fields', 'error');
        return;
    }
    
    if (!window.WeddingBooking.validateEmail(email)) {
        showFieldError(form.querySelector('#email'), 'Please enter a valid email address');
        return;
    }
    
    // Show loading state
    submitBtn.classList.add('loading');
    submitBtn.disabled = true;
    
    try {
        // Simulate API call
        await new Promise(resolve => setTimeout(resolve, 1500));
        
        // In a real app, this would validate against backend
        // For demo purposes, we'll accept any valid email/password combination
        const loginData = {
            email: email,
            loginTime: new Date().toISOString(),
            remember: formData.get('remember') === 'on'
        };
        
        localStorage.setItem('weddingLoginData', JSON.stringify(loginData));
        
        window.WeddingBooking.showNotification('Login successful! Redirecting to dashboard...', 'success');
        
        // Redirect to dashboard
        setTimeout(() => {
            window.location.href = 'dashboard.html';
        }, 1500);
        
    } catch (error) {
        console.error('Login error:', error);
        window.WeddingBooking.showNotification('Login failed. Please check your credentials.', 'error');
    } finally {
        submitBtn.classList.remove('loading');
        submitBtn.disabled = false;
    }
}

// Phone number formatting
function formatPhoneNumber(event) {
    let value = event.target.value.replace(/\D/g, '');
    
    // Handle Uganda phone numbers
    if (value.startsWith('256')) {
        value = value.substring(3);
    } else if (value.startsWith('0')) {
        value = value.substring(1);
    }
    
    // Format as +256 XXX XXX XXX
    if (value.length > 0) {
        if (value.length <= 3) {
            value = `+256 ${value}`;
        } else if (value.length <= 6) {
            value = `+256 ${value.substring(0, 3)} ${value.substring(3)}`;
        } else {
            value = `+256 ${value.substring(0, 3)} ${value.substring(3, 6)} ${value.substring(6, 9)}`;
        }
    }
    
    event.target.value = value;
}

// Social authentication handlers
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('btn-social')) {
        event.preventDefault();
        
        const provider = event.target.classList.contains('google') ? 'Google' : 'Facebook';
        
        // In a real app, this would integrate with OAuth providers
        window.WeddingBooking.showNotification(`${provider} authentication will be available soon!`, 'info');
    }
});

// Auto-save form data (for registration form)
let autoSaveTimer;
document.addEventListener('input', function(event) {
    if (event.target.form && event.target.form.id === 'registrationForm') {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(() => {
            saveFormData(event.target.form);
        }, 2000);
    }
});

function saveFormData(form) {
    const formData = new FormData(form);
    const data = {};
    
    for (let [key, value] of formData.entries()) {
        if (key !== 'password' && key !== 'confirmPassword') {
            data[key] = value;
        }
    }
    
    localStorage.setItem('weddingFormDraft', JSON.stringify(data));
}

// Load saved form data
window.addEventListener('load', function() {
    const registrationForm = document.getElementById('registrationForm');
    if (registrationForm) {
        const savedData = localStorage.getItem('weddingFormDraft');
        if (savedData) {
            const data = JSON.parse(savedData);
            
            for (let [key, value] of Object.entries(data)) {
                const field = registrationForm.querySelector(`[name="${key}"]`);
                if (field) {
                    if (field.type === 'checkbox') {
                        field.checked = value === 'on';
                    } else {
                        field.value = value;
                    }
                }
            }
        }
    }
});

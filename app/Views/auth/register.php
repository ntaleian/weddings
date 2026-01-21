<?= $this->extend('layouts/auth') ?>

<?= $this->section('title') ?>
<?= $title ?? 'Create Account' ?>
<?= $this->endSection() ?>

<?= $this->section('auth_left') ?>
    <div class="auth-welcome">
        <div class="welcome-header">
            <h1>Glad to have you here!</h1>
            <p class="welcome-subtitle">Start your beautiful wedding journey with Watoto Church</p>
        </div>
        
        <div class="auth-features">
            <div class="auth-feature">
                <i class="fas fa-user-check"></i>
                <div>
                    <strong>Easy Registration</strong>
                    <span>Create your account in minutes</span>
                </div>
            </div>
            <div class="auth-feature">
                <i class="fas fa-calendar-alt"></i>
                <div>
                    <strong>Book Your Wedding</strong>
                    <span>Reserve your special day</span>
                </div>
            </div>
            <div class="auth-feature">
                <i class="fas fa-hands-helping"></i>
                <div>
                    <strong>Expert Support</strong>
                    <span>Get help from our dedicated team</span>
                </div>
            </div>
        </div>

        <div class="auth-quote">
            <i class="fas fa-quote-left"></i>
            <p>"Therefore what God has joined together, let no one separate."</p>
            <cite>Mark 10:9</cite>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('auth_right') ?>
    <div class="register-container">
        <div class="register-header">
            <div class="register-icon">
                <i class="fas fa-heart"></i>
            </div>
            <h1>Create Account</h1>
            <p class="register-subtitle">Join us and begin your wedding journey</p>
        </div>

        <!-- Flash Messages -->
        <?= $this->include('partials/flash_messages') ?>

        <form class="register-form" method="post" action="<?= base_url('register') ?>" autocomplete="off" id="registerForm">
            <?= csrf_field() ?>
            
            <div class="form-grid">
                <div class="form-field">
                    <label for="first_name" class="field-label">
                        First Name
                    </label>
                    <div class="input-wrapper">
                        <i class="input-icon fas fa-user"></i>
                        <input type="text" id="first_name" name="first_name" value="<?= old('first_name') ?>" required placeholder="John">
                    </div>
                    <span class="field-error"></span>
                </div>
                
                <div class="form-field">
                    <label for="last_name" class="field-label">
                        Last Name
                    </label>
                    <div class="input-wrapper">
                        <i class="input-icon fas fa-user"></i>
                        <input type="text" id="last_name" name="last_name" value="<?= old('last_name') ?>" required placeholder="Doe">
                    </div>
                    <span class="field-error"></span>
                </div>
            </div>

            <div class="form-field">
                <label for="email" class="field-label">
                    Email Address
                </label>
                <div class="input-wrapper">
                    <i class="input-icon fas fa-envelope"></i>
                    <input type="email" id="email" name="email" value="<?= old('email') ?>" required autocomplete="off" placeholder="john.doe@example.com">
                </div>
                <span class="field-error"></span>
            </div>

            <div class="form-grid">
                <div class="form-field">
                    <label for="password" class="field-label">
                        Password
                    </label>
                    <div class="input-wrapper">
                        <i class="input-icon fas fa-lock"></i>
                        <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="••••••••">
                        <button type="button" class="password-toggle-btn" onclick="togglePassword('password')" aria-label="Toggle password visibility">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <span class="field-error"></span>
                </div>
                
                <div class="form-field">
                    <label for="confirm_password" class="field-label">
                        Confirm Password
                    </label>
                    <div class="input-wrapper">
                        <i class="input-icon fas fa-lock"></i>
                        <input type="password" id="confirm_password" name="confirm_password" required placeholder="••••••••">
                        <button type="button" class="password-toggle-btn" onclick="togglePassword('confirm_password')" aria-label="Toggle password visibility">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <span class="field-error"></span>
                </div>
            </div>

            <!-- Math CAPTCHA -->
            <div class="form-field captcha-field">
                <label for="captcha_answer" class="field-label">
                    Security Verification
                </label>
                <div class="captcha-box">
                    <div class="captcha-display">
                        <div class="captcha-text-wrapper">
                            <i class="captcha-icon fas fa-calculator"></i>
                            <span id="captcha-text"><?= $captcha_question ?? '2 + 2' ?> = ?</span>
                        </div>
                        <button type="button" class="captcha-refresh-btn" onclick="refreshCaptcha()" title="Get new question">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <div class="input-wrapper captcha-input-wrapper">
                        <i class="input-icon fas fa-keyboard"></i>
                        <input type="number" id="captcha_answer" name="captcha_answer" placeholder="Enter answer" required>
                    </div>
                    <span class="captcha-hint">Solve the math question to verify you're human</span>
                </div>
                <span class="field-error"></span>
            </div>

            <div class="form-field checkbox-field">
                <label class="custom-checkbox">
                    <input type="checkbox" id="terms" name="terms" required>
                    <span class="checkbox-indicator"></span>
                    <span class="checkbox-text">
                        I agree to the <a href="#" class="link-primary">Terms & Conditions</a> and <a href="#" class="link-primary">Privacy Policy</a>
                    </span>
                </label>
                <span class="field-error"></span>
            </div>

            <button type="submit" class="submit-btn">
                <span class="btn-text">
                    <i class="fas fa-user-plus"></i>
                    <span>Create My Account</span>
                </span>
                <span class="btn-spinner" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
            </button>
        </form>

        <div class="register-footer">
            <p>Already have an account? <a href="<?= base_url('login') ?>" class="login-link">Sign in</a></p>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('additional_css') ?>
    <style>
    /* Modern Register Page Design */
    .register-container {
        width: 100%;
        max-width: 100%;
    }

    .register-header {
        text-align: center;
        margin-bottom: 35px;
        padding-bottom: 25px;
        border-bottom: 1px solid #e9ecef;
    }

    .register-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #25802D, #1a5a20);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(37, 128, 45, 0.25);
    }

    .register-icon i {
        font-size: 28px;
        color: white;
    }

    .register-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 8px 0;
        font-family: 'Outfit', sans-serif;
    }

    .register-subtitle {
        font-size: 15px;
        color: #6c757d;
        margin: 0;
        font-weight: 400;
    }

    .register-form {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-field {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .field-label {
        font-size: 14px;
        font-weight: 600;
        color: #343a40;
        margin: 0;
        display: block;
    }

    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon {
        position: absolute;
        left: 16px;
        color: #6c757d;
        font-size: 16px;
        pointer-events: none;
        z-index: 1;
    }

    .input-wrapper input {
        width: 100%;
        padding: 14px 16px 14px 48px;
        border: 1.5px solid #dee2e6;
        border-radius: 10px;
        font-size: 15px;
        font-family: 'Outfit', sans-serif;
        background: #ffffff;
        color: #1a1a1a;
        outline: none;
        transition: all 0.2s ease;
    }

    .input-wrapper input::placeholder {
        color: #adb5bd;
    }

    .input-wrapper input:focus {
        border-color: #25802D;
        box-shadow: 0 0 0 3px rgba(37, 128, 45, 0.1);
        background: #ffffff;
    }

    .input-wrapper input:hover:not(:focus) {
        border-color: #adb5bd;
    }

    .password-toggle-btn {
        position: absolute;
        right: 14px;
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        font-size: 16px;
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .password-toggle-btn:hover {
        color: #25802D;
        background: rgba(37, 128, 45, 0.08);
    }

    .field-error {
        font-size: 13px;
        color: #dc3545;
        display: none;
        margin-top: 4px;
    }

    .form-field.error .field-error {
        display: block;
    }

    .form-field.error .input-wrapper input {
        border-color: #dc3545;
        background: #fff5f5;
    }

    /* CAPTCHA Styling */
    .captcha-field {
        margin-top: 8px;
    }

    .captcha-box {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding: 18px;
        background: #f8f9fa;
        border: 1.5px solid #e9ecef;
        border-radius: 10px;
    }

    .captcha-display {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        background: white;
        border: 1.5px solid #dee2e6;
        border-radius: 8px;
        gap: 12px;
    }

    .captcha-text-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }

    .captcha-icon {
        width: 36px;
        height: 36px;
        background: rgba(37, 128, 45, 0.1);
        color: #25802D;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .captcha-text-wrapper span {
        font-size: 16px;
        font-weight: 600;
        color: #343a40;
        font-family: 'Outfit', sans-serif;
    }

    .captcha-refresh-btn {
        width: 38px;
        height: 38px;
        background: #25802D;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .captcha-refresh-btn:hover {
        background: #1a5a20;
        transform: scale(1.05);
    }

    .captcha-refresh-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .captcha-input-wrapper {
        margin-top: 4px;
    }

    .captcha-hint {
        font-size: 12px;
        color: #6c757d;
        margin-top: 4px;
        font-style: italic;
    }

    /* Checkbox Styling */
    .checkbox-field {
        margin-top: 8px;
    }

    .custom-checkbox {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        cursor: pointer;
        user-select: none;
        padding: 4px 0;
    }

    .custom-checkbox input[type="checkbox"] {
        display: none;
    }

    .checkbox-indicator {
        width: 20px;
        height: 20px;
        border: 2px solid #dee2e6;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 2px;
        background: white;
        transition: all 0.2s ease;
    }

    .custom-checkbox input[type="checkbox"]:checked + .checkbox-indicator {
        background: #25802D;
        border-color: #25802D;
    }

    .custom-checkbox input[type="checkbox"]:checked + .checkbox-indicator::after {
        content: '\f00c';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        color: white;
        font-size: 12px;
    }

    .checkbox-text {
        font-size: 14px;
        color: #495057;
        line-height: 1.5;
        flex: 1;
    }

    .link-primary {
        color: #25802D;
        text-decoration: none;
        font-weight: 600;
    }

    .link-primary:hover {
        color: #1a5a20;
        text-decoration: underline;
    }

    /* Submit Button */
    .submit-btn {
        width: 100%;
        padding: 16px 24px;
        background: linear-gradient(135deg, #25802D 0%, #1a5a20 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        font-family: 'Outfit', sans-serif;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 8px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(37, 128, 45, 0.25);
        transition: all 0.2s ease;
    }

    .submit-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 128, 45, 0.35);
    }

    .submit-btn:active:not(:disabled) {
        transform: translateY(0);
    }

    .submit-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .btn-text {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-spinner {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .submit-btn i {
        font-size: 16px;
    }

    /* Footer */
    .register-footer {
        margin-top: 28px;
        padding-top: 24px;
        border-top: 1px solid #e9ecef;
        text-align: center;
    }

    .register-footer p {
        font-size: 14px;
        color: #6c757d;
        margin: 0;
    }

    .login-link {
        color: #25802D;
        text-decoration: none;
        font-weight: 600;
        margin-left: 4px;
    }

    .login-link:hover {
        color: #1a5a20;
        text-decoration: underline;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .register-header {
            margin-bottom: 28px;
            padding-bottom: 20px;
        }

        .register-icon {
            width: 56px;
            height: 56px;
            margin-bottom: 16px;
        }

        .register-icon i {
            font-size: 24px;
        }

        .register-header h1 {
            font-size: 24px;
        }

        .register-subtitle {
            font-size: 14px;
        }

        .register-form {
            gap: 20px;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .captcha-box {
            padding: 16px;
        }

        .captcha-display {
            padding: 12px 14px;
        }

        .captcha-text-wrapper span {
            font-size: 15px;
        }
    }

    @media (max-width: 480px) {
        .register-header {
            margin-bottom: 24px;
        }

        .register-header h1 {
            font-size: 22px;
        }

        .register-form {
            gap: 18px;
        }

        .input-wrapper input {
            padding: 13px 14px 13px 44px;
            font-size: 14px;
        }

        .submit-btn {
            padding: 14px 20px;
            font-size: 15px;
        }
    }
    </style>
<?= $this->endSection() ?>

<?= $this->section('additional_scripts') ?>
    <script>
        // Form submission loading state
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('.submit-btn');
            const btnText = btn.querySelector('.btn-text');
            const btnSpinner = btn.querySelector('.btn-spinner');
            
            if (btnText && btnSpinner) {
                btnText.style.display = 'none';
                btnSpinner.style.display = 'flex';
                btn.disabled = true;
            }
        });

        // Password toggle function
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const toggleBtn = input.closest('.input-wrapper').querySelector('.password-toggle-btn');
            const icon = toggleBtn.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Form validation for math captcha
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const captchaAnswer = document.getElementById('captcha_answer').value;
            const errorElement = document.querySelector('.captcha-field .field-error');
            const submitBtn = this.querySelector('.submit-btn');
            const btnText = submitBtn.querySelector('.btn-text');
            const btnSpinner = submitBtn.querySelector('.btn-spinner');
            
            if (!captchaAnswer || captchaAnswer.trim() === '') {
                e.preventDefault();
                errorElement.textContent = 'Please answer the security question.';
                errorElement.style.display = 'block';
                errorElement.style.color = '#dc3545';
                this.querySelector('.captcha-field').classList.add('error');
                
                // Re-enable button if validation fails
                if (btnText && btnSpinner) {
                    btnText.style.display = 'flex';
                    btnSpinner.style.display = 'none';
                    submitBtn.disabled = false;
                }
                return false;
            } else {
                errorElement.style.display = 'none';
                this.querySelector('.captcha-field').classList.remove('error');
            }
        });

        // Clear error when user types in captcha
        document.getElementById('captcha_answer').addEventListener('input', function() {
            const errorElement = document.querySelector('.captcha-field .field-error');
            errorElement.style.display = 'none';
            this.closest('.captcha-field').classList.remove('error');
        });

        // Refresh captcha function
        function refreshCaptcha() {
            const refreshBtn = document.querySelector('.captcha-refresh-btn');
            const captchaText = document.getElementById('captcha-text');
            const captchaInput = document.getElementById('captcha_answer');
            
            if (!refreshBtn || !captchaText || !captchaInput) return;
            
            // Show loading state
            refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            refreshBtn.disabled = true;
            
            // Get CSRF token from form
            const csrfTokenName = '<?= csrf_token() ?>';
            const csrfToken = document.querySelector('input[name="' + csrfTokenName + '"]');
            const csrfValue = csrfToken ? csrfToken.value : '';
            const csrfHeader = '<?= csrf_header() ?>';
            
            if (!csrfValue) {
                console.error('CSRF token not found in form');
                alert('Security token missing. Please refresh the page and try again.');
                refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i>';
                refreshBtn.disabled = false;
                return;
            }
            
            // Prepare form data
            const formData = new URLSearchParams();
            formData.append(csrfTokenName, csrfValue);
            
            // Prepare headers
            const headers = {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            };
            
            // Add CSRF token to header if available
            if (csrfHeader && csrfValue) {
                headers[csrfHeader] = csrfValue;
            }
            
            fetch('<?= site_url('refresh-captcha') ?>', {
                method: 'POST',
                headers: headers,
                body: formData,
                credentials: 'same-origin'
            })
            .then(response => {
                // Check content type
                const contentType = response.headers.get('content-type');
                
                if (!response.ok) {
                    // Try to get error message from response
                    if (contentType && contentType.includes('application/json')) {
                        return response.json().then(data => {
                            throw new Error(data.message || `Server returned ${response.status}`);
                        });
                    } else {
                        return response.text().then(text => {
                            console.error('Non-JSON error response:', text);
                            throw new Error(`Server returned ${response.status}. Please check the console for details.`);
                        });
                    }
                }
                
                // Check if response is JSON
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // If not JSON, try to parse as text first
                    return response.text().then(text => {
                        console.error('Expected JSON but got:', text);
                        throw new Error('Server returned invalid response format');
                    });
                }
            })
            .then(data => {
                if (data && data.success && data.question) {
                    captchaText.textContent = data.question + ' = ?';
                    captchaInput.value = '';
                    captchaInput.focus();
                    // Update CSRF token if provided
                    if (data.csrf_token && csrfToken) {
                        csrfToken.value = data.csrf_token;
                    }
                    // Also update CSRF token name if provided
                    if (data.csrf_name && csrfToken) {
                        csrfToken.name = data.csrf_name;
                    }
                } else {
                    console.error('Server error:', data);
                    const errorMsg = data && data.message ? data.message : 'Unknown error occurred';
                    alert('Failed to refresh captcha: ' + errorMsg);
                    captchaInput.value = '';
                }
            })
            .catch(error => {
                console.error('Error refreshing captcha:', error);
                const errorMsg = error.message || 'Network error or server unavailable';
                alert('Failed to refresh captcha: ' + errorMsg + '\n\nPlease check your connection and try again.');
                captchaInput.value = '';
            })
            .finally(() => {
                refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i>';
                refreshBtn.disabled = false;
            });
        }
    </script>
<?= $this->endSection() ?>

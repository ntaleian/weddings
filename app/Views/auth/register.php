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
    <div class="auth-header">
        <h2>Create Your Account</h2>
        <p>Start your wedding booking journey with Watoto Church</p>
    </div>

    <!-- Flash Messages -->
    <?= $this->include('partials/flash_messages') ?>

    <form class="auth-form" method="post" action="<?= base_url('register') ?>" autocomplete="off" id="registerForm">
        <?= csrf_field() ?>
        
        <div class="form-row">
            <div class="form-group">
                <label for="first_name">
                    <i class="fas fa-user"></i>
                    First Name
                </label>
                <input type="text" id="first_name" name="first_name" value="<?= old('first_name') ?>" required placeholder="Enter your first name">
                <span class="form-error"></span>
            </div>
            <div class="form-group">
                <label for="last_name">
                    <i class="fas fa-user"></i>
                    Last Name
                </label>
                <input type="text" id="last_name" name="last_name" value="<?= old('last_name') ?>" required placeholder="Enter your last name">
                <span class="form-error"></span>
            </div>
        </div>

        <div class="form-group">
            <label for="email">
                <i class="fas fa-envelope"></i>
                Email Address
            </label>
            <input type="email" id="email" name="email" value="<?= old('email') ?>" required autocomplete="off" placeholder="Enter your email">
            <span class="form-error"></span>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i>
                    Password
                </label>
                <div class="password-input">
                    <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="Create a password">
                    <button type="button" class="password-toggle" onclick="togglePassword('password')" aria-label="Toggle password visibility">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <span class="form-error"></span>
            </div>
            <div class="form-group">
                <label for="confirm_password">
                    <i class="fas fa-lock"></i>
                    Confirm Password
                </label>
                <div class="password-input">
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm your password">
                    <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')" aria-label="Toggle password visibility">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <span class="form-error"></span>
            </div>
        </div>

        <!-- Math CAPTCHA -->
        <div class="form-group math-captcha-group">
            <label for="captcha_answer">
                <i class="fas fa-shield-alt"></i>
                Security Verification
            </label>
            <div class="captcha-container">
                <div class="captcha-question">
                    <div class="captcha-question-content">
                        <i class="fas fa-calculator"></i>
                        <span id="captcha-text">What is <?= $captcha_question ?? '2 + 2' ?>?</span>
                    </div>
                    <button type="button" class="captcha-refresh" onclick="refreshCaptcha()" title="Get new question">
                        <i class="fas fa-redo-alt"></i>
                    </button>
                </div>
                <input type="number" id="captcha_answer" name="captcha_answer" placeholder="Enter your answer" required>
                <div class="captcha-help">Please solve the math question above to verify you're human</div>
            </div>
            <span class="form-error"></span>
        </div>

        <div class="form-group checkbox-group">
            <label class="checkbox-label">
                <input type="checkbox" id="terms" name="terms" required>
                <span class="checkmark"></span>
                <span>I agree to the <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a></span>
            </label>
            <span class="form-error"></span>
        </div>

        <button type="submit" class="btn btn-primary btn-full">
            <span class="btn-content">
                <i class="fas fa-user-plus"></i>
                <span>Create Account</span>
            </span>
            <span class="btn-loader" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i>
            </span>
        </button>
    </form>

    <div class="auth-footer">
        <p>Already have an account? <a href="<?= base_url('login') ?>">Login here</a></p>
    </div>
<?= $this->endSection() ?>

<?= $this->section('additional_scripts') ?>
    <style>
    /* Enhanced Register Page Styles - Clean & Organized */
    .auth-welcome {
        display: flex;
        flex-direction: column;
        height: 100%;
        animation: fadeInUp 0.6s ease-out;
    }

    .welcome-header {
        margin-bottom: 50px;
    }

    .welcome-header h1 {
        margin-bottom: 12px;
        font-size: 2.5rem;
        line-height: 1.2;
    }

    .welcome-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        line-height: 1.5;
        margin: 0;
    }

    .auth-features {
        margin-bottom: 50px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .auth-feature {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 18px 20px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    .auth-feature:hover {
        background: rgba(255, 255, 255, 0.12);
        transform: translateX(4px);
    }

    .auth-feature i {
        width: 44px;
        height: 44px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: var(--white);
        flex-shrink: 0;
    }

    .auth-feature div {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .auth-feature strong {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--white);
        line-height: 1.3;
    }

    .auth-feature span {
        font-size: 0.85rem;
        opacity: 0.8;
        color: var(--white);
        line-height: 1.4;
    }

    .auth-quote {
        margin-top: auto;
        padding: 24px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        text-align: center;
        animation: fadeIn 0.8s ease-out 0.3s both;
    }

    .auth-quote i {
        font-size: 1.5rem;
        color: rgba(255, 255, 255, 0.6);
        margin-bottom: 12px;
        display: block;
    }

    .auth-quote p {
        font-style: italic;
        font-size: 1rem;
        margin-bottom: 10px;
        line-height: 1.6;
        color: var(--white);
    }

    .auth-quote cite {
        font-size: 0.85rem;
        opacity: 0.75;
        font-style: normal;
        color: var(--white);
    }

    .auth-header {
        animation: fadeInDown 0.6s ease-out;
    }

    .auth-form {
        animation: fadeInUp 0.6s ease-out 0.2s both;
    }

    .form-group label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
    }

    .form-group label i {
        color: #25802D;
        font-size: 0.9rem;
    }

    .form-group input {
        padding: 16px 18px;
        border: 2px solid #e8ecef;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .form-group input:focus {
        background: white;
        border-color: #25802D;
        box-shadow: 0 0 0 4px rgba(37, 128, 45, 0.1);
        transform: translateY(-1px);
    }

    .form-group input::placeholder {
        color: #adb5bd;
    }

    .password-input {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        font-size: 1.1rem;
        padding: 5px;
        transition: all 0.3s ease;
        z-index: 2;
    }

    .password-toggle:hover {
        color: #25802D;
        transform: translateY(-50%) scale(1.1);
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        user-select: none;
    }

    .checkbox-label span:not(.checkmark) {
        color: #495057;
        font-size: 0.95rem;
    }

    .auth-footer {
        margin-top: 30px;
        padding-top: 25px;
        border-top: 2px solid #e8ecef;
        animation: fadeIn 0.8s ease-out 0.4s both;
    }

    .auth-footer p {
        color: #6c757d;
        font-size: 0.95rem;
    }

    .auth-footer a {
        color: #25802D;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .auth-footer a:hover {
        color: #1a5a20;
        text-decoration: underline;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 16px 30px;
        border: none;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        font-size: 1.05rem;
        line-height: 1.2;
        white-space: nowrap;
        position: relative;
        overflow: hidden;
    }

    .btn-primary {
        background: linear-gradient(135deg, #25802D 0%, #1a5a20 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(37, 128, 45, 0.3);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #1a5a20 0%, #25802D 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(37, 128, 45, 0.4);
        color: white;
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .btn-full {
        width: 100%;
        margin-top: 10px;
    }

    .btn-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-loader {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Enhanced CAPTCHA Design - Cleaner */
    .math-captcha-group {
        margin: 20px 0;
        background: linear-gradient(135deg, rgba(37, 128, 45, 0.04) 0%, rgba(37, 128, 45, 0.01) 100%);
        border-radius: 12px;
        padding: 20px;
        border: 2px solid rgba(37, 128, 45, 0.15);
    }
    
    .math-captcha-group label {
        font-weight: 600;
        color: #25802D;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .captcha-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .captcha-question {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: white;
        padding: 14px 18px;
        border-radius: 10px;
        border: 2px solid rgba(37, 128, 45, 0.15);
        box-shadow: 0 2px 6px rgba(37, 128, 45, 0.08);
    }
    
    .captcha-question-content {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }
    
    .captcha-question i {
        color: #25802D;
        font-size: 1rem;
        background: rgba(37, 128, 45, 0.1);
        padding: 8px;
        border-radius: 8px;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .captcha-question span {
        font-weight: 600;
        color: #2c3e50;
        font-size: 1rem;
    }
    
    .captcha-refresh {
        background: #25802D;
        color: white;
        border: none;
        border-radius: 8px;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 6px rgba(37, 128, 45, 0.2);
    }
    
    .captcha-refresh:hover {
        background: #1a5a20;
        transform: rotate(180deg);
        box-shadow: 0 4px 10px rgba(37, 128, 45, 0.3);
    }
    
    .captcha-refresh:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    
    .math-captcha-group input[type="number"] {
        background: white;
        border: 2px solid rgba(37, 128, 45, 0.15);
        border-radius: 10px;
        padding: 14px 16px;
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .math-captcha-group input[type="number"]:focus {
        outline: none;
        border-color: #25802D;
        box-shadow: 0 0 0 4px rgba(37, 128, 45, 0.1);
        background: rgba(37, 128, 45, 0.02);
    }
    
    .captcha-help {
        font-size: 0.85rem;
        color: #6c757d;
        font-style: italic;
        margin-top: 6px;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    /* Form validation styles */
    .form-group.error input {
        border-color: #dc3545;
        background: #fff5f5;
    }

    .form-group.error .form-error {
        display: block;
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-group.error .form-error::before {
        content: '\f06a';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
    }

    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .welcome-header h1 {
            font-size: 2.2rem;
        }

        .welcome-subtitle {
            font-size: 1rem;
        }
    }

    @media (max-width: 768px) {
        .auth-welcome {
            text-align: center;
        }

        .welcome-header {
            margin-bottom: 40px;
        }

        .auth-features {
            margin-bottom: 40px;
        }

        .auth-feature {
            padding: 16px;
            justify-content: center;
        }

        .auth-feature:hover {
            transform: translateY(-2px);
        }

        .auth-quote {
            margin-top: 30px;
        }

        .math-captcha-group {
            padding: 16px;
        }
        
        .captcha-question {
            padding: 12px 15px;
        }
        
        .captcha-question span {
            font-size: 0.95rem;
        }
        
        .captcha-refresh {
            width: 32px;
            height: 32px;
        }
    }
    </style>
    
    <script>
        // Form submission loading state
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('.btn-primary');
            const btnContent = btn.querySelector('.btn-content');
            const btnLoader = btn.querySelector('.btn-loader');
            
            if (btnContent && btnLoader) {
                btnContent.style.display = 'none';
                btnLoader.style.display = 'flex';
                btn.disabled = true;
            }
        });

        // Enhanced password toggle
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const toggle = input.nextElementSibling;
            const icon = toggle.querySelector('i');
            
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

        // Simple form validation for math captcha
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const captchaAnswer = document.getElementById('captcha_answer').value;
            const errorElement = document.querySelector('.math-captcha-group .form-error');
            
            if (!captchaAnswer || captchaAnswer.trim() === '') {
                e.preventDefault();
                errorElement.textContent = 'Please answer the security question.';
                errorElement.style.display = 'block';
                errorElement.style.color = '#dc3545';
                
                // Re-enable button if validation fails
                const btn = this.querySelector('.btn-primary');
                const btnContent = btn.querySelector('.btn-content');
                const btnLoader = btn.querySelector('.btn-loader');
                if (btnContent && btnLoader) {
                    btnContent.style.display = 'flex';
                    btnLoader.style.display = 'none';
                    btn.disabled = false;
                }
                return false;
            } else {
                errorElement.style.display = 'none';
            }
        });

        // Clear error when user types
        document.getElementById('captcha_answer').addEventListener('input', function() {
            const errorElement = document.querySelector('.math-captcha-group .form-error');
            errorElement.style.display = 'none';
        });

        // Refresh captcha function
        function refreshCaptcha() {
            const refreshBtn = document.querySelector('.captcha-refresh');
            const captchaText = document.getElementById('captcha-text');
            const captchaInput = document.getElementById('captcha_answer');
            
            // Show loading state
            refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            refreshBtn.disabled = true;
            
            fetch('<?= base_url('refresh-captcha') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    captchaText.textContent = 'What is ' + data.question + '?';
                    captchaInput.value = '';
                    captchaInput.focus();
                } else {
                    console.error('Server error:', data.message);
                    captchaInput.value = '';
                }
            })
            .catch(error => {
                console.error('Error refreshing captcha:', error);
                captchaInput.value = '';
                captchaInput.focus();
            })
            .finally(() => {
                refreshBtn.innerHTML = '<i class="fas fa-redo-alt"></i>';
                refreshBtn.disabled = false;
            });
        }
    </script>
<?= $this->endSection() ?>

<?= $this->extend('layouts/auth') ?>

<?= $this->section('title') ?>
<?= $title ?? 'Login' ?>
<?= $this->endSection() ?>

<?= $this->section('auth_left') ?>
    <div class="auth-welcome">
        <div class="welcome-header">
            <!-- <div class="welcome-icon">
                <i class="fas fa-heart"></i>
            </div> -->
            <h1>Welcome Back!</h1>
            <p class="welcome-subtitle">Continue your wedding planning journey with us</p>
        </div>
        
        <div class="auth-features">
            <div class="auth-feature">
                <i class="fas fa-calendar-check"></i>
                <div>
                    <strong>Manage Bookings</strong>
                    <span>View and track your wedding details</span>
                </div>
            </div>
            <div class="auth-feature">
                <i class="fas fa-comments"></i>
                <div>
                    <strong>Stay Connected</strong>
                    <span>Communicate with our team</span>
                </div>
            </div>
            <div class="auth-feature">
                <i class="fas fa-file-alt"></i>
                <div>
                    <strong>Track Progress</strong>
                    <span>Monitor your application status</span>
                </div>
            </div>
        </div>

        <div class="auth-quote">
            <i class="fas fa-quote-left"></i>
            <p>"Love is patient, love is kind. It does not envy, it does not boast, it is not proud."</p>
            <cite>1 Corinthians 13:4</cite>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('auth_right') ?>
    <div class="login-container">
        <div class="login-header">
            <div class="login-icon">
                <i class="fas fa-sign-in-alt"></i>
            </div>
            <h1>Welcome Back</h1>
            <p class="login-subtitle">Sign in to continue your wedding journey</p>
        </div>

        <!-- Flash Messages -->
        <?= $this->include('partials/flash_messages') ?>

        <form class="login-form" method="post" action="<?= base_url('login') ?>" autocomplete="off" id="loginForm">
            <?= csrf_field() ?>
            
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

            <div class="form-row-options">
                <div class="form-field checkbox-field">
                    <label class="custom-checkbox">
                        <input type="checkbox" id="remember" name="remember">
                        <span class="checkbox-indicator"></span>
                        <span class="checkbox-text">Remember me</span>
                    </label>
                </div>
                <div class="form-field">
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div>
            </div>

            <button type="submit" class="submit-btn">
                <span class="btn-text">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Sign In</span>
                </span>
                <span class="btn-spinner" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
            </button>
        </form>

        <div class="login-footer">
            <p>Don't have an account? <a href="<?= base_url('register') ?>" class="register-link">Create one here</a></p>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('additional_css') ?>
    <style>
    /* Modern Login Page Design - Matching Register Style */
    .login-container {
        width: 100%;
        max-width: 100%;
    }

    .login-header {
        text-align: center;
        margin-bottom: 35px;
        padding-bottom: 25px;
        border-bottom: 1px solid #e9ecef;
    }

    .login-icon {
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

    .login-icon i {
        font-size: 28px;
        color: white;
    }

    .login-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 8px 0;
        font-family: 'Outfit', sans-serif;
    }

    .login-subtitle {
        font-size: 15px;
        color: #6c757d;
        margin: 0;
        font-weight: 400;
    }

    .login-form {
        display: flex;
        flex-direction: column;
        gap: 22px;
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

    .form-row-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }

    .checkbox-field {
        flex: 0 0 auto;
    }

    .custom-checkbox {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        user-select: none;
        padding: 0;
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
        background: white;
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
    }

    .forgot-link {
        color: #25802D;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
    }

    .forgot-link:hover {
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
    .login-footer {
        margin-top: 28px;
        padding-top: 24px;
        border-top: 1px solid #e9ecef;
        text-align: center;
    }

    .login-footer p {
        font-size: 14px;
        color: #6c757d;
        margin: 0;
    }

    .register-link {
        color: #25802D;
        text-decoration: none;
        font-weight: 600;
        margin-left: 4px;
    }

    .register-link:hover {
        color: #1a5a20;
        text-decoration: underline;
    }

    /* Welcome Section (Left Side) - Keep existing styles */
    .auth-welcome {
        display: flex;
        flex-direction: column;
        height: 100%;
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
    }

    .auth-feature:hover {
        background: rgba(255, 255, 255, 0.12);
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

    /* Responsive Design */
    @media (max-width: 768px) {
        .login-header {
            margin-bottom: 28px;
            padding-bottom: 20px;
        }

        .login-icon {
            width: 56px;
            height: 56px;
            margin-bottom: 16px;
        }

        .login-icon i {
            font-size: 24px;
        }

        .login-header h1 {
            font-size: 24px;
        }

        .login-subtitle {
            font-size: 14px;
        }

        .login-form {
            gap: 20px;
        }

        .form-row-options {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .forgot-link {
            align-self: flex-end;
        }
    }

    @media (max-width: 480px) {
        .login-header {
            margin-bottom: 24px;
        }

        .login-header h1 {
            font-size: 22px;
        }

        .login-form {
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
        document.getElementById('loginForm').addEventListener('submit', function(e) {
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
    </script>
<?= $this->endSection() ?>

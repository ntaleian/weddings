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
    <div class="auth-header">
        <!-- <div class="auth-logo">
            <i class="fas fa-heart"></i>
        </div> -->
        <h2>Login to Your Account</h2>
        <p>Access your wedding planning dashboard</p>
    </div>

    <!-- Flash Messages -->
    <?= $this->include('partials/flash_messages') ?>

    <form class="auth-form" method="post" action="<?= base_url('login') ?>" autocomplete="off" id="loginForm">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label for="email">
                <i class="fas fa-envelope"></i>
                Email Address
            </label>
            <input type="email" id="email" name="email" value="<?= old('email') ?>" required autocomplete="off" placeholder="Enter your email">
            <span class="form-error"></span>
        </div>

        <div class="form-group">
            <label for="password">
                <i class="fas fa-lock"></i>
                Password
            </label>
            <div class="password-input">
                <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="Enter your password">
                <button type="button" class="password-toggle" onclick="togglePassword('password')" aria-label="Toggle password visibility">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <span class="form-error"></span>
        </div>

        <div class="form-row">
            <div class="form-group checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" id="remember" name="remember">
                    <span class="checkmark"></span>
                    <span>Remember me</span>
                </label>
            </div>
            <div class="form-group">
                <a href="#" class="forgot-link">Forgot Password?</a>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-full">
            <span class="btn-content">
                <i class="fas fa-sign-in-alt"></i>
                <span>Login</span>
            </span>
            <span class="btn-loader" style="display: none;">
                <i class="fas fa-spinner fa-spin"></i>
            </span>
        </button>
    </form>

    <div class="auth-footer">
        <p>Don't have an account? <a href="<?= base_url('register') ?>">Create one here</a></p>
    </div>
<?= $this->endSection() ?>

<?= $this->section('additional_scripts') ?>
    <style>
    /* Enhanced Login Page Styles - Clean & Organized */
    .auth-welcome {
        display: flex;
        flex-direction: column;
        height: 100%;
        animation: fadeInUp 0.6s ease-out;
    }

    .welcome-header {
        margin-bottom: 50px;
    }

    .welcome-icon {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.12);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 25px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .welcome-icon i {
        font-size: 2rem;
        color: var(--white);
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

    .auth-logo {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #25802D 0%, #1a5a20 100%);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 8px 20px rgba(37, 128, 45, 0.3);
        animation: fadeInDown 0.6s ease-out;
    }

    .auth-logo i {
        font-size: 2rem;
        color: white;
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

    .forgot-link {
        color: #25802D;
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .forgot-link:hover {
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

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
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

        .welcome-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 20px;
        }

        .welcome-icon i {
            font-size: 1.8rem;
        }

        .welcome-header h1 {
            font-size: 2rem;
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
    }
    </style>

    <script>
    // Form submission loading state
    document.getElementById('loginForm').addEventListener('submit', function(e) {
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
    </script>
<?= $this->endSection() ?>

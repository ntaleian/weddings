<?= $this->extend('layouts/auth') ?>

<?= $this->section('title') ?>
Verify Your Email - Watoto Church Wedding Booking
<?= $this->endSection() ?>

<?= $this->section('auth_left') ?>
    <div class="auth-welcome">
        <div class="welcome-header">
            <h1>Almost There!</h1>
            <p class="welcome-subtitle">Just one more step to complete your registration and start planning your perfect wedding day.</p>
        </div>
        
        <div class="auth-features">
            <div class="auth-feature">
                <i class="fas fa-envelope-open"></i>
                <div>
                    <strong>Check Your Email</strong>
                    <span>We've sent you a verification code</span>
                </div>
            </div>
            <div class="auth-feature">
                <i class="fas fa-shield-alt"></i>
                <div>
                    <strong>Secure Verification</strong>
                    <span>Your account is protected</span>
                </div>
            </div>
            <div class="auth-feature">
                <i class="fas fa-clock"></i>
                <div>
                    <strong>Quick Process</strong>
                    <span>Verify in seconds</span>
                </div>
            </div>
            <div class="auth-feature">
                <i class="fas fa-heart"></i>
                <div>
                    <strong>Begin Your Journey</strong>
                    <span>Start planning your special day</span>
                </div>
            </div>
        </div>

        <div class="auth-quote">
            <i class="fas fa-quote-left"></i>
            <p>"Trust in the Lord with all your heart and lean not on your own understanding."</p>
            <cite>Proverbs 3:5</cite>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('auth_right') ?>
    <div class="verify-container">
        <div class="verify-header">
            <div class="verify-icon">
                <i class="fas fa-envelope-open"></i>
            </div>
            <h1>Verify Your Email</h1>
            <p class="verify-subtitle">We've sent a 6-digit code to <strong><?= esc($email ?? 'your email') ?></strong></p>
        </div>

        <!-- Flash Messages -->
        <?= $this->include('partials/flash_messages') ?>

        <form class="verify-form" action="<?= site_url('process-email-verification') ?>" method="POST" autocomplete="off" id="verification-form">
            <?= csrf_field() ?>

            <div class="form-field">
                <label for="otp_1" class="field-label">
                    Enter Verification Code
                </label>
                <div class="otp-input-group">
                    <input type="text" id="otp_1" name="otp_1" maxlength="1" pattern="[0-9]" class="otp-digit" inputmode="numeric" autocomplete="off" required>
                    <input type="text" id="otp_2" name="otp_2" maxlength="1" pattern="[0-9]" class="otp-digit" inputmode="numeric" autocomplete="off" required>
                    <input type="text" id="otp_3" name="otp_3" maxlength="1" pattern="[0-9]" class="otp-digit" inputmode="numeric" autocomplete="off" required>
                    <input type="text" id="otp_4" name="otp_4" maxlength="1" pattern="[0-9]" class="otp-digit" inputmode="numeric" autocomplete="off" required>
                    <input type="text" id="otp_5" name="otp_5" maxlength="1" pattern="[0-9]" class="otp-digit" inputmode="numeric" autocomplete="off" required>
                    <input type="text" id="otp_6" name="otp_6" maxlength="1" pattern="[0-9]" class="otp-digit" inputmode="numeric" autocomplete="off" required>
                </div>
                <input type="hidden" id="otp_code" name="otp_code">
                <small class="otp-hint">6-digit code sent to your email</small>
            </div>

            <button type="submit" class="submit-btn">
                <span class="btn-text">
                    <i class="fas fa-check-circle"></i>
                    <span>Verify & Continue</span>
                </span>
                <span class="btn-spinner" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
            </button>
        </form>

        <div class="verify-footer">
            <div class="timer-info">
                <i class="fas fa-clock"></i>
                <span>Code expires in <strong>15 minutes</strong></span>
            </div>
            <div class="resend-section">
                <span class="resend-text">Didn't receive the code?</span>
                <form action="<?= site_url('resend-otp') ?>" method="POST" class="resend-form">
                    <?= csrf_field() ?>
                    <button type="submit" class="resend-btn">
                        <i class="fas fa-paper-plane"></i>
                        <span>Send New Code</span>
                    </button>
                </form>
            </div>
            <div class="back-link">
                <a href="<?= site_url('register') ?>" class="back-link-text">
                    <i class="fas fa-arrow-left"></i>
                    <span>Wrong email? Back to Registration</span>
                </a>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('additional_css') ?>
    <style>
    /* Modern Verify Email Page Design - Matching Login/Register Style */
    .verify-container {
        width: 100%;
        max-width: 100%;
    }

    .verify-header {
        text-align: center;
        margin-bottom: 35px;
        padding-bottom: 25px;
        border-bottom: 1px solid #e9ecef;
    }

    .verify-icon {
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

    .verify-icon i {
        font-size: 28px;
        color: white;
    }

    .verify-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 8px 0;
        font-family: 'Outfit', sans-serif;
    }

    .verify-subtitle {
        font-size: 15px;
        color: #6c757d;
        margin: 0;
        font-weight: 400;
        line-height: 1.5;
    }

    .verify-subtitle strong {
        color: #25802D;
        font-weight: 600;
    }

    .verify-form {
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

    .otp-input-group {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin: 10px 0;
    }

    .otp-digit {
        width: 50px;
        height: 60px;
        text-align: center;
        font-size: 24px;
        font-weight: 600;
        font-family: 'Outfit', sans-serif;
        border: 1.5px solid #dee2e6;
        border-radius: 10px;
        background: #ffffff;
        color: #1a1a1a;
        outline: none;
        transition: all 0.2s ease;
        padding: 0;
    }

    .otp-digit:focus {
        border-color: #25802D;
        box-shadow: 0 0 0 3px rgba(37, 128, 45, 0.1);
        transform: scale(1.05);
    }

    .otp-digit:hover:not(:focus) {
        border-color: #adb5bd;
    }

    .otp-hint {
        font-size: 13px;
        color: #6c757d;
        text-align: center;
        margin-top: 4px;
        font-style: italic;
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
    .verify-footer {
        margin-top: 28px;
        padding-top: 24px;
        border-top: 1px solid #e9ecef;
        display: flex;
        flex-direction: column;
        gap: 16px;
        align-items: center;
        text-align: center;
    }

    .timer-info {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 20px;
        font-size: 13px;
        color: #856404;
    }

    .timer-info i {
        color: #ffc107;
        font-size: 14px;
    }

    .timer-info strong {
        color: #856404;
        font-weight: 600;
    }

    .resend-section {
        display: flex;
        flex-direction: column;
        gap: 10px;
        align-items: center;
    }

    .resend-text {
        font-size: 14px;
        color: #6c757d;
        font-weight: 500;
    }

    .resend-form {
        display: inline-block;
    }

    .resend-btn {
        background: linear-gradient(135deg, #25802D 0%, #1a5a20 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        font-family: 'Outfit', sans-serif;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(37, 128, 45, 0.2);
    }

    .resend-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 128, 45, 0.3);
    }

    .resend-btn:active {
        transform: translateY(0);
    }

    .resend-btn i {
        font-size: 13px;
    }

    .back-link {
        margin-top: 8px;
    }

    .back-link-text {
        color: #25802D;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .back-link-text:hover {
        color: #1a5a20;
        text-decoration: underline;
    }

    .back-link-text i {
        font-size: 12px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .verify-header {
            margin-bottom: 28px;
            padding-bottom: 20px;
        }

        .verify-icon {
            width: 56px;
            height: 56px;
            margin-bottom: 16px;
        }

        .verify-icon i {
            font-size: 24px;
        }

        .verify-header h1 {
            font-size: 24px;
        }

        .verify-subtitle {
            font-size: 14px;
        }

        .verify-form {
            gap: 20px;
        }

        .otp-input-group {
            gap: 8px;
        }

        .otp-digit {
            width: 45px;
            height: 55px;
            font-size: 20px;
        }
    }

    @media (max-width: 480px) {
        .verify-header {
            margin-bottom: 24px;
        }

        .verify-header h1 {
            font-size: 22px;
        }

        .verify-form {
            gap: 18px;
        }

        .otp-input-group {
            gap: 6px;
        }

        .otp-digit {
            width: 40px;
            height: 50px;
            font-size: 18px;
        }

        .submit-btn {
            padding: 14px 20px;
            font-size: 15px;
        }

        .resend-section {
            gap: 8px;
        }

        .resend-btn {
            padding: 8px 16px;
            font-size: 13px;
        }
    }
    </style>
<?= $this->endSection() ?>

<?= $this->section('additional_scripts') ?>
    <script>
        // Form submission loading state
        document.getElementById('verification-form').addEventListener('submit', function(e) {
            const btn = this.querySelector('.submit-btn');
            const btnText = btn.querySelector('.btn-text');
            const btnSpinner = btn.querySelector('.btn-spinner');
            
            if (btnText && btnSpinner) {
                btnText.style.display = 'none';
                btnSpinner.style.display = 'flex';
                btn.disabled = true;
            }
        });

        // Resend form loading state
        const resendForm = document.querySelector('.resend-form');
        if (resendForm) {
            resendForm.addEventListener('submit', function(e) {
                const btn = this.querySelector('.resend-btn');
                const originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Sending...</span>';
                btn.disabled = true;
                
                // Re-enable after 3 seconds if still on page (in case of error)
                setTimeout(() => {
                    if (btn.disabled) {
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                    }
                }, 3000);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-digit');
            const hiddenOtpInput = document.getElementById('otp_code');
            const form = document.getElementById('verification-form');

            // Focus first input on page load
            if (otpInputs.length > 0) {
                otpInputs[0].focus();
            }

            // Handle input for each OTP digit
            otpInputs.forEach((input, index) => {
                // Input event - handle typing
                input.addEventListener('input', function(e) {
                    // Only allow single digit
                    let value = e.target.value;
                    if (value.length > 1) {
                        value = value.slice(-1); // Keep only the last character
                    }
                    
                    // Remove non-digits
                    value = value.replace(/\D/g, '');
                    e.target.value = value;

                    // Update hidden input
                    updateHiddenInput();

                    // Auto-focus next input if digit entered
                    if (value && index < otpInputs.length - 1) {
                        setTimeout(() => {
                            otpInputs[index + 1].focus();
                            otpInputs[index + 1].select(); // Select any existing content
                        }, 50);
                    }

                    // Auto-submit when all 6 digits are entered
                    if (hiddenOtpInput.value.length === 6) {
                        setTimeout(() => {
                            form.submit();
                        }, 500);
                    }
                });

                // Keydown event - handle navigation and validation
                input.addEventListener('keydown', function(e) {
                    // Handle backspace
                    if (e.key === 'Backspace') {
                        if (!this.value && index > 0) {
                            setTimeout(() => {
                                otpInputs[index - 1].focus();
                                otpInputs[index - 1].select();
                            }, 50);
                        }
                        return; // Allow backspace
                    }

                    // Allow navigation keys
                    if (['Tab', 'Enter', 'ArrowLeft', 'ArrowRight', 'Delete'].includes(e.key)) {
                        return;
                    }

                    // Only allow numbers 0-9
                    if (!/^[0-9]$/.test(e.key)) {
                        e.preventDefault();
                    }
                });

                // Handle paste event
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const paste = (e.clipboardData || window.clipboardData).getData('text');
                    const digits = paste.replace(/\D/g, '').substring(0, 6);

                    // Fill inputs with pasted digits
                    for (let i = 0; i < digits.length && i < otpInputs.length; i++) {
                        otpInputs[i].value = digits[i];
                    }

                    updateHiddenInput();

                    // Focus appropriate input
                    const focusIndex = Math.min(digits.length, otpInputs.length - 1);
                    otpInputs[focusIndex].focus();

                    // Auto-submit if all digits pasted
                    if (digits.length === 6) {
                        setTimeout(() => {
                            form.submit();
                        }, 500);
                    }
                });
            });

            function updateHiddenInput() {
                const otpValue = Array.from(otpInputs).map(input => input.value || '').join('');
                hiddenOtpInput.value = otpValue;
            }

            // Handle form submission
            form.addEventListener('submit', function(e) {
                updateHiddenInput();
                
                // Validate that we have 6 digits
                if (hiddenOtpInput.value.length !== 6) {
                    e.preventDefault();
                    alert('Please enter all 6 digits of the verification code.');
                    otpInputs[0].focus();
                }
            });
        });
    </script>
<?= $this->endSection() ?>

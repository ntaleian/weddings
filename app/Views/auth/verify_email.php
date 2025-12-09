<?= $this->extend('layouts/auth') ?>

<?= $this->section('title') ?>
Verify Your Email - Watoto Church Wedding Booking
<?= $this->endSection() ?>

<?= $this->section('auth_left') ?>
    <h1>Almost There!</h1>
    <p>Just one more step to complete your registration and start planning your perfect wedding day.</p>
    
    <div class="auth-features">
        <div class="auth-feature">
            <i class="fas fa-envelope-open"></i>
            <span>Check your email</span>
        </div>
        <div class="auth-feature">
            <i class="fas fa-shield-alt"></i>
            <span>Secure verification</span>
        </div>
        <div class="auth-feature">
            <i class="fas fa-clock"></i>
            <span>Quick process</span>
        </div>
        <div class="auth-feature">
            <i class="fas fa-heart"></i>
            <span>Begin your journey</span>
        </div>
    </div>

    
<?= $this->endSection() ?>

<?= $this->section('auth_right') ?>
    <div class="auth-header">
        <h2>Check Your Email</h2>
        <p>We've sent a 6-digit code to <strong><?= esc($email) ?></strong></p>
    </div>

    <!-- Flash Messages -->
    <?= $this->include('partials/flash_messages') ?>

    <div class="verification-card">
        <div class="verification-icon">
            <i class="fas fa-envelope-open"></i>
        </div>

        <form action="<?= site_url('process-email-verification') ?>" method="POST" class="auth-form" id="verification-form">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="otp_1">Enter Verification Code</label>
                <div class="otp-input-group">
                    <input type="text" id="otp_1" name="otp_1" maxlength="1" pattern="[0-9]" class="otp-digit" inputmode="numeric" autocomplete="off" required>
                    <input type="text" id="otp_2" name="otp_2" maxlength="1" pattern="[0-9]" class="otp-digit" inputmode="numeric" autocomplete="off" required>
                    <input type="text" id="otp_3" name="otp_3" maxlength="1" pattern="[0-9]" class="otp-digit" inputmode="numeric" autocomplete="off" required>
                    <input type="text" id="otp_4" name="otp_4" maxlength="1" pattern="[0-9]" class="otp-digit" inputmode="numeric" autocomplete="off" required>
                    <input type="text" id="otp_5" name="otp_5" maxlength="1" pattern="[0-9]" class="otp-digit" inputmode="numeric" autocomplete="off" required>
                    <input type="text" id="otp_6" name="otp_6" maxlength="1" pattern="[0-9]" class="otp-digit" inputmode="numeric" autocomplete="off" required>
                </div>
                <input type="hidden" id="otp_code" name="otp_code">
                <small>6-digit code sent to your email</small>
            </div>

            <button type="submit" class="btn btn-primary btn-full">
                <i class="fas fa-check"></i>
                Verify & Continue
            </button>
        </form>

        <div class="verification-footer">
            <div class="footer-info">
                <div class="timer-info">
                    <i class="fas fa-clock"></i>
                    <span>Code expires in <strong>15 minutes</strong></span>
                </div>
                <div class="divider"></div>
                <div class="resend-info">
                    <span>Didn't receive the code?</span>
                    <form action="<?= site_url('resend-otp') ?>" method="POST" style="display: inline;">
                        <?= csrf_field() ?>
                        <button type="submit" class="resend-btn">
                            <i class="fas fa-paper-plane"></i>
                            Send New Code
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="auth-footer">
        <p>Wrong email? <a href="<?= site_url('register') ?>">← Back to Registration</a></p>
    </div>
<?= $this->endSection() ?>

<?= $this->section('additional_css') ?>
<style>
/* Clean up any conflicting styles and ensure horizontal OTP layout */
.verification-card {
    text-align: center;
}

/* Override any potential vertical layout styles */
.otp-input-group {
    display: flex !important;
    justify-content: center !important;
    flex-direction: row !important;
    gap: 6px !important;
    margin: 20px 0 !important;
}

/* Ensure OTP digits are properly styled */
.otp-digit {
    display: inline-block !important;
    width: 35px !important;
    height: 40px !important;
    text-align: center !important;
    font-size: 1.2rem !important;
    font-weight: 600 !important;
    border: 2px solid #e1e8ed !important;
    border-radius: 6px !important;
    background: #fff !important;
    transition: all 0.3s ease !important;
    outline: none !important;
    padding: 0 !important;
    margin: 0 !important;
    line-height: 36px !important;
}

.otp-digit:focus {
    border-color: #6f017f !important;
    box-shadow: 0 0 0 3px rgba(111, 1, 127, 0.1) !important;
    transform: scale(1.05) !important;
}

/* Resend Button Styling */
.resend-btn {
    background: linear-gradient(135deg, #6f017f 0%, #8e0199 100%) !important;
    border: none !important;
    color: white !important;
    padding: 8px 16px !important;
    border-radius: 20px !important;
    font-size: 0.8rem !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 5px !important;
    text-decoration: none !important;
    box-shadow: 0 2px 8px rgba(111, 1, 127, 0.2) !important;
}

.resend-btn:hover {
    background: linear-gradient(135deg, #8e0199 0%, #6f017f 100%) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 16px rgba(111, 1, 127, 0.4) !important;
    color: white !important;
}

.resend-btn:active {
    transform: translateY(0) !important;
    box-shadow: 0 2px 8px rgba(111, 1, 127, 0.2) !important;
}

.resend-btn i {
    font-size: 0.75rem !important;
}

/* Footer Styling */
.footer-info {
    display: flex !important;
    flex-direction: column !important;
    gap: 12px !important;
    align-items: center !important;
    text-align: center !important;
}

.timer-info {
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    color: #6c757d !important;
    font-size: 0.85rem !important;
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%) !important;
    padding: 6px 12px !important;
    border-radius: 16px !important;
    border: 1px solid #ffc107 !important;
}

.timer-info i {
    color: #ffc107 !important;
    font-size: 0.9rem !important;
}

.timer-info strong {
    color: #856404 !important;
}

.divider {
    width: 40px !important;
    height: 1px !important;
    background: linear-gradient(90deg, transparent, #e1e8ed, transparent) !important;
    margin: 0 auto !important;
}

.resend-info {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    gap: 6px !important;
}

.resend-info span {
    color: #6c757d !important;
    font-size: 0.85rem !important;
    font-weight: 500 !important;
}

/* Additional verification card styling */
.verification-footer {
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #f0f0f0;
    text-align: center;
}

/* Index page button styling override */
.btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 10px !important;
    padding: 15px 30px !important;
    border: none !important;
    border-radius: 50px !important;
    text-decoration: none !important;
    font-weight: 600 !important;
    transition: all 0.3s ease !important;
    cursor: pointer !important;
    font-size: 1rem !important;
    line-height: 1.2 !important;
    white-space: nowrap !important;
}

.btn-primary {
    background: #64017f !important;
    color: white !important;
    box-shadow: 0 4px 15px rgba(100, 1, 127, 0.3) !important;
}

.btn-primary:hover {
    background: #4a0160 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 25px rgba(100, 1, 127, 0.4) !important;
    color: white !important;
}

.btn-full {
    width: 100% !important;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('additional_scripts') ?>
<script>
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

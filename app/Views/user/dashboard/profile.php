<?= $this->extend('layouts/user/base') ?>

<?= $this->section('styles') ?>
    <link href="<?= base_url('assets/css/dashboard.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/dashboard-components.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/dashboard-sections.css') ?>" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?= $this->include('partials/user_nav') ?>
    
    <!-- Dashboard Main Content -->
    <div class="dashboard-container">
        <?= $this->include('partials/user_sidebar') ?>
        
        <main class="dashboard-main">
            <!-- Flash Messages -->
            <?= $this->include('partials/flash_messages') ?>
            
            <!-- Profile Section -->
            <div class="profile-section">
                <!-- Profile Header -->
                <div class="section-header">
                    <div class="header-content">
                        <div class="profile-avatar">
                            <div class="avatar-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                            <button class="avatar-edit-btn" onclick="document.getElementById('avatarUpload').click()">
                                <i class="fas fa-camera"></i>
                            </button>
                            <input type="file" id="avatarUpload" accept="image/*" style="display: none;">
                        </div>
                        <div class="profile-info">
                            <h1><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></h1>
                            <p class="profile-email"><?= esc($user['email']) ?></p>
                            <p class="profile-member-since">Member since <?= date('F Y', strtotime($user['created_at'])) ?></p>
                        </div>
                    </div>
                    <div class="header-actions">
                        <button type="button" class="btn btn-outline-primary" onclick="toggleEditMode()">
                            <i class="fas fa-edit"></i> Edit Profile
                        </button>
                    </div>
                </div>

                <!-- Profile Content Cards -->
                <div class="quick-actions">
                    <!-- Personal Information Card -->
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-user"></i> Personal Information</h3>
                            <p>Manage your personal details and contact information</p>
                        </div>
                        <div class="card-body">
                            <form id="profile-form" action="<?= site_url('dashboard/update-profile') ?>" method="POST">
                                <?= csrf_field() ?>
                                
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input type="text" 
                                           id="first_name" 
                                           name="first_name" 
                                           value="<?= esc($user['first_name']) ?>" 
                                           class="form-control" 
                                           readonly>
                                    <small class="form-help">Your legal first name</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" 
                                           id="last_name" 
                                           name="last_name" 
                                           value="<?= esc($user['last_name']) ?>" 
                                           class="form-control" 
                                           readonly>
                                    <small class="form-help">Your legal last name</small>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="<?= esc($user['email']) ?>" 
                                           class="form-control" 
                                           readonly>
                                    <small class="form-help">Your primary email address for communications</small>
                                    <?php if (isset($user['is_email_verified']) && $user['is_email_verified']): ?>
                                        <div class="verification-status verified">
                                            <i class="fas fa-check-circle"></i> Verified
                                        </div>
                                    <?php else: ?>
                                        <div class="verification-status unverified">
                                            <i class="fas fa-exclamation-circle"></i> 
                                            Not verified 
                                            <button type="button" class="verify-btn" onclick="resendVerification()">Verify Now</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" 
                                           id="phone" 
                                           name="phone" 
                                           value="<?= esc($user['phone'] ?? '') ?>" 
                                           class="form-control" 
                                           placeholder="+256 XXX XXX XXX"
                                           readonly>
                                    <small class="form-help">Your contact phone number</small>
                                </div>

                                <div class="form-actions" style="display: none;">
                                    <button type="button" class="btn btn-secondary" onclick="cancelEdit()">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Security Card -->
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-lock"></i> Security Settings</h3>
                            <p>Manage your password and account security</p>
                        </div>
                        <div class="card-body">
                            <div class="security-item">
                                <div class="security-info">
                                    <h4>Password</h4>
                                    <p>Last changed <?= isset($user['password_changed_at']) && $user['password_changed_at'] ? date('F j, Y', strtotime($user['password_changed_at'])) : 'Never' ?></p>
                                </div>
                                <button type="button" class="btn btn-outline-primary" onclick="showChangePassword()">
                                    Change Password
                                </button>
                            </div>

                            <!-- Change Password Form -->
                            <div id="change-password-form" class="password-form" style="display: none;">
                                <form action="<?= site_url('dashboard/change-password') ?>" method="POST">
                                    <?= csrf_field() ?>
                                    <div class="form-group">
                                        <label for="current_password">Current Password</label>
                                        <input type="password" id="current_password" name="current_password" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="new_password">New Password</label>
                                        <input type="password" id="new_password" name="new_password" class="form-control" required>
                                        <small class="form-help">Minimum 8 characters with letters and numbers</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm_password">Confirm New Password</label>
                                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                                    </div>
                                    <div class="form-actions">
                                        <button type="button" class="btn btn-secondary" onclick="hideChangePassword()">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Preferences Card -->
                    <div class="card">
                        <div class="card-header">
                            <h3><i class="fas fa-cog"></i> Preferences</h3>
                            <p>Customize your experience and notification settings</p>
                        </div>
                        <div class="card-body">
                            <form action="<?= site_url('dashboard/update-preferences') ?>" method="POST">
                                <?= csrf_field() ?>
                                
                                <div class="preferences-section">
                                    <h4>Notifications</h4>
                                    
                                    <div class="preference-item">
                                        <div class="preference-info">
                                            <label for="email_notifications">Email Notifications</label>
                                            <p>Receive updates about your wedding application via email</p>
                                        </div>
                                        <div class="preference-control">
                                            <label class="switch">
                                                <input type="checkbox" id="email_notifications" name="email_notifications" <?= isset($user['email_notifications']) && $user['email_notifications'] ? 'checked' : '' ?>>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="preference-item">
                                        <div class="preference-info">
                                            <label for="sms_notifications">SMS Notifications</label>
                                            <p>Receive important updates via SMS</p>
                                        </div>
                                        <div class="preference-control">
                                            <label class="switch">
                                                <input type="checkbox" id="sms_notifications" name="sms_notifications" <?= isset($user['sms_notifications']) && $user['sms_notifications'] ? 'checked' : '' ?>>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="preference-item">
                                        <div class="preference-info">
                                            <label for="marketing_emails">Marketing Emails</label>
                                            <p>Receive newsletters and wedding tips</p>
                                        </div>
                                        <div class="preference-control">
                                            <label class="switch">
                                                <input type="checkbox" id="marketing_emails" name="marketing_emails" <?= isset($user['marketing_emails']) && $user['marketing_emails'] ? 'checked' : '' ?>>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="preferences-section">
                                    <h4>Privacy</h4>
                                    <div class="preference-item">
                                        <div class="preference-info">
                                            <label for="profile_visibility">Profile Visibility</label>
                                            <p>Control who can see your profile information</p>
                                        </div>
                                        <div class="preference-control">
                                            <select id="profile_visibility" name="profile_visibility" class="form-control">
                                                <option value="private" <?= isset($user['profile_visibility']) && $user['profile_visibility'] === 'private' ? 'selected' : '' ?>>Private</option>
                                                <option value="church_members" <?= isset($user['profile_visibility']) && $user['profile_visibility'] === 'church_members' ? 'selected' : '' ?>>Church Members Only</option>
                                                <option value="public" <?= isset($user['profile_visibility']) && $user['profile_visibility'] === 'public' ? 'selected' : '' ?>>Public</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Save Preferences</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
    <script>
        // Profile page functionality
        function toggleEditMode() {
            const form = document.getElementById('profile-form');
            const inputs = form.querySelectorAll('input[readonly]');
            const actions = form.querySelector('.form-actions');
            const editBtn = document.querySelector('.header-actions .btn');
            
            if (inputs[0].hasAttribute('readonly')) {
                // Enable edit mode
                inputs.forEach(input => {
                    if (input.name !== 'email') { // Keep email readonly for security
                        input.removeAttribute('readonly');
                        input.classList.remove('readonly');
                    }
                });
                actions.style.display = 'flex';
                editBtn.innerHTML = '<i class="fas fa-times"></i> Cancel Edit';
                editBtn.onclick = cancelEdit;
            } else {
                cancelEdit();
            }
        }

        function cancelEdit() {
            const form = document.getElementById('profile-form');
            const inputs = form.querySelectorAll('input');
            const actions = form.querySelector('.form-actions');
            const editBtn = document.querySelector('.header-actions .btn');
            
            // Reset form and disable inputs
            form.reset();
            inputs.forEach(input => {
                if (input.name !== 'email') {
                    input.setAttribute('readonly', true);
                    input.classList.add('readonly');
                }
            });
            
            actions.style.display = 'none';
            editBtn.innerHTML = '<i class="fas fa-edit"></i> Edit Profile';
            editBtn.onclick = toggleEditMode;
        }

        function showChangePassword() {
            document.getElementById('change-password-form').style.display = 'block';
        }

        function hideChangePassword() {
            document.getElementById('change-password-form').style.display = 'none';
        }

        function resendVerification() {
            if (confirm('Send verification email to your registered email address?')) {
                fetch('<?= site_url('auth/resend-verification') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Verification email sent successfully!');
                    } else {
                        alert('Failed to send verification email. Please try again.');
                    }
                })
                .catch(error => {
                    alert('An error occurred. Please try again.');
                });
            }
        }

        // Password validation
        document.addEventListener('DOMContentLoaded', function() {
            const newPasswordInput = document.getElementById('new_password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            
            if (newPasswordInput) {
                newPasswordInput.addEventListener('input', function() {
                    const password = this.value;
                    const strength = calculatePasswordStrength(password);
                    // Add visual password strength indicator here if needed
                });
            }
            
            if (confirmPasswordInput) {
                confirmPasswordInput.addEventListener('input', function() {
                    const newPassword = newPasswordInput.value;
                    const confirmPassword = this.value;
                    
                    if (confirmPassword && newPassword !== confirmPassword) {
                        this.setCustomValidity('Passwords do not match');
                    } else {
                        this.setCustomValidity('');
                    }
                });
            }
        });

        function calculatePasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            return strength;
        }
    </script>
    
    <style>
        /* Profile-specific styles */
        .profile-section {
            padding: 0;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Modern Profile Header */
        .section-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 24px 30px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            box-shadow: 0 2px 12px rgba(100, 1, 127, 0.15);
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .profile-avatar {
            position: relative;
        }

        .avatar-placeholder {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .avatar-edit-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #fff;
            color: var(--primary-color);
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.75rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .avatar-edit-btn:hover {
            transform: scale(1.1);
        }

        .profile-info h1 {
            margin: 0 0 4px 0;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .profile-email {
            margin: 0 0 4px 0;
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .profile-member-since {
            margin: 0;
            font-size: 0.85rem;
            opacity: 0.8;
        }

        .header-actions .btn {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
        }

        .header-actions .btn:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Modern Card Design */
        .quick-actions {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            border: 1px solid #e9ecef;
        }

        .card-header {
            padding: 20px 24px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .card-header h3 {
            margin: 0 0 6px 0;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header h3 i {
            font-size: 1.1rem;
        }

        .card-header p {
            margin: 0;
            font-size: 0.9rem;
            color: #6c757d;
        }

        .card-body {
            padding: 24px;
        }

        /* Modern Form Styling */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #495057;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background: #fff;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(100, 1, 127, 0.1);
        }

        .form-control[readonly] {
            background: #f8f9fa;
            cursor: not-allowed;
            border-color: #e9ecef;
        }

        .form-help {
            display: block;
            margin-top: 4px;
            font-size: 0.8rem;
            color: #6c757d;
        }

        /* Button Styling */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: #4a0159;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(100, 1, 127, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #545b62;
        }

        .btn-outline-primary {
            background: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        /* Security Section */
        .security-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .security-item:last-child {
            border-bottom: none;
        }

        .security-info h4 {
            margin: 0 0 4px 0;
            color: var(--text-color);
            font-size: 1rem;
            font-weight: 600;
        }

        .security-info p {
            margin: 0;
            color: #6c757d;
            font-size: 0.85rem;
        }

        .password-form {
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        /* Preferences Section */
        .preferences-section {
            margin-bottom: 24px;
        }

        .preferences-section:last-child {
            margin-bottom: 0;
        }

        .preferences-section h4 {
            margin: 0 0 16px 0;
            color: var(--text-color);
            font-size: 1rem;
            font-weight: 700;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }

        .preference-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 16px 0;
            border-bottom: 1px solid #f0f0f0;
            gap: 20px;
        }

        .preference-item:last-child {
            border-bottom: none;
        }

        .preference-info {
            flex: 1;
        }

        .preference-info label {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 4px;
            display: block;
            font-size: 0.9rem;
        }

        .preference-info p {
            margin: 0;
            color: #6c757d;
            font-size: 0.85rem;
        }

        .preference-control {
            flex-shrink: 0;
        }

        /* Modern Toggle Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 26px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.3s;
            border-radius: 26px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        input:checked + .slider {
            background-color: var(--primary-color);
        }

        input:checked + .slider:before {
            transform: translateX(22px);
        }

        /* Verification Status */
        .verification-status {
            margin-top: 8px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
        }

        .verification-status.verified {
            background: #d4edda;
            color: #155724;
        }

        .verification-status.unverified {
            background: #fff3cd;
            color: #856404;
        }

        .verify-btn {
            background: none;
            border: none;
            color: var(--primary-color);
            text-decoration: underline;
            cursor: pointer;
            font-size: 0.8rem;
            margin-left: 4px;
            font-weight: 600;
        }

        .verify-btn:hover {
            color: #4a0159;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .section-header {
                padding: 20px;
                flex-direction: column;
                text-align: center;
                gap: 16px;
            }
            
            .header-content {
                flex-direction: column;
                text-align: center;
                width: 100%;
            }

            .header-actions {
                width: 100%;
            }

            .header-actions .btn {
                width: 100%;
                justify-content: center;
            }
            
            .card-header {
                padding: 16px 20px;
            }

            .card-header h3 {
                font-size: 1.1rem;
            }

            .card-body {
                padding: 20px;
            }

            .form-group {
                margin-bottom: 16px;
            }

            .security-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .security-item .btn {
                width: 100%;
            }

            .preference-item {
                flex-direction: column;
                gap: 12px;
            }

            .preference-control {
                align-self: flex-start;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .form-actions .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .section-header {
                padding: 18px 16px;
            }

            .profile-info h1 {
                font-size: 1.3rem;
            }

            .avatar-placeholder {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .card-header {
                padding: 14px 16px;
            }

            .card-body {
                padding: 16px;
            }

            .form-group label {
                font-size: 0.8rem;
            }

            .form-control {
                padding: 9px 12px;
                font-size: 0.9rem;
            }
        }
    </style>
<?= $this->endSection() ?>
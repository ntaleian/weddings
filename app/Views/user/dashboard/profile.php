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
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .profile-avatar {
            position: relative;
        }

        .avatar-placeholder {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
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
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }

        .profile-info h1 {
            margin: 0 0 5px 0;
            font-size: 1.8rem;
            font-weight: 600;
        }

        .header-actions .btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        /* Form styling to match payment page */
        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #495057;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 0.95rem;
            transition: border-color 0.15s ease;
            background: #fff;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #64017f;
            box-shadow: 0 0 0 2px rgba(100, 1, 127, 0.1);
        }

        .form-control[readonly] {
            background: #e9ecef;
            cursor: not-allowed;
        }

        .form-help {
            display: block;
            margin-top: 4px;
            font-size: 0.8rem;
            color: #6c757d;
        }

        /* Button styling */
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.15s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary {
            background: #64017f;
            color: white;
        }

        .btn-primary:hover {
            background: #4a0159;
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
            color: #64017f;
            border: 1px solid #64017f;
        }

        .btn-outline-primary:hover {
            background: #64017f;
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

        .security-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .security-info h4 {
            margin: 0 0 5px 0;
            color: var(--text-color);
            font-size: 1rem;
            font-weight: 600;
        }

        .security-info p {
            margin: 0;
            color: var(--gray);
            font-size: 0.9rem;
        }

        .password-form {
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .preferences-section {
            margin-bottom: 30px;
        }

        .preferences-section h4 {
            margin: 0 0 20px 0;
            color: var(--text-color);
            font-size: 1.1rem;
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
        }

        .preference-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
            gap: 15px;
        }

        .preference-info {
            flex: 1;
        }

        .preference-info label {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 5px;
            display: block;
        }

        .preference-info p {
            margin: 0;
            color: var(--gray);
            font-size: 0.9rem;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
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
            transition: .4s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: var(--primary-color);
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .verification-status {
            margin-top: 8px;
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 5px;
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
            margin-left: 5px;
        }

        @media (max-width: 768px) {
            .section-header {
                flex-direction: column;
                text-align: center;
            }
            
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
<?= $this->endSection() ?>
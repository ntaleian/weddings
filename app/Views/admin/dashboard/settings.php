<?= $this->extend('layouts/admin/admin') ?>

<?= $this->section('main_content') ?>
<div class="content-wrapper">
    <section id="settings" class="content-section">
        <div class="section-header">
            <h2>System Settings</h2>
            <div class="section-actions">
                <button class="btn btn-secondary" onclick="backupSettings()">
                    <i class="fas fa-download"></i>
                    Backup Settings
                </button>
                <button class="btn btn-primary" onclick="saveAllSettings()">
                    <i class="fas fa-save"></i>
                    Save All Changes
                </button>
            </div>
        </div>

        <div class="settings-container">
            <!-- Settings Navigation -->
            <div class="settings-nav">
                <ul class="nav-list">
                    <li><a href="#general" class="nav-link active" onclick="showSettingsTab('general')">General</a></li>
                    <li><a href="#booking" class="nav-link" onclick="showSettingsTab('booking')">Booking</a></li>
                    <li><a href="#payment" class="nav-link" onclick="showSettingsTab('payment')">Payment</a></li>
                    <li><a href="#email" class="nav-link" onclick="showSettingsTab('email')">Email</a></li>
                    <li><a href="#security" class="nav-link" onclick="showSettingsTab('security')">Security</a></li>
                    <li><a href="#maintenance" class="nav-link" onclick="showSettingsTab('maintenance')">Maintenance</a></li>
                </ul>
            </div>

            <!-- Settings Content -->
            <div class="settings-content">
                <!-- General Settings -->
                <div id="general-settings" class="settings-tab active">
                    <h3>General Settings</h3>
                    <form class="settings-form">
                        <div class="form-group">
                            <label for="site_name">Site Name</label>
                            <input type="text" id="site_name" name="site_name" value="<?= esc($settings['site_name'] ?? 'Wedding Booking System') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="site_description">Site Description</label>
                            <textarea id="site_description" name="site_description" rows="3"><?= esc($settings['site_description'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="contact_email">Contact Email</label>
                            <input type="email" id="contact_email" name="contact_email" value="<?= esc($settings['contact_email'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="contact_phone">Contact Phone</label>
                            <input type="tel" id="contact_phone" name="contact_phone" value="<?= esc($settings['contact_phone'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="timezone">Timezone</label>
                            <select id="timezone" name="timezone">
                                <option value="America/New_York" <?= ($settings['timezone'] ?? '') === 'America/New_York' ? 'selected' : '' ?>>Eastern Time</option>
                                <option value="America/Chicago" <?= ($settings['timezone'] ?? '') === 'America/Chicago' ? 'selected' : '' ?>>Central Time</option>
                                <option value="America/Denver" <?= ($settings['timezone'] ?? '') === 'America/Denver' ? 'selected' : '' ?>>Mountain Time</option>
                                <option value="America/Los_Angeles" <?= ($settings['timezone'] ?? '') === 'America/Los_Angeles' ? 'selected' : '' ?>>Pacific Time</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="maintenance_mode" <?= !empty($settings['maintenance_mode']) ? 'checked' : '' ?>>
                                Maintenance Mode
                            </label>
                            <small>Enable to temporarily disable user access</small>
                        </div>
                    </form>
                </div>

                <!-- Booking Settings -->
                <div id="booking-settings" class="settings-tab">
                    <h3>Booking Settings</h3>
                    <form class="settings-form">
                        <div class="form-group">
                            <label for="booking_advance_days">Minimum Advance Booking (days)</label>
                            <input type="number" id="booking_advance_days" name="booking_advance_days" min="1" value="<?= esc($settings['booking_advance_days'] ?? '30') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="max_booking_months">Maximum Booking Window (months)</label>
                            <input type="number" id="max_booking_months" name="max_booking_months" min="1" value="<?= esc($settings['max_booking_months'] ?? '24') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="default_event_duration">Default Event Duration (hours)</label>
                            <input type="number" id="default_event_duration" name="default_event_duration" min="1" value="<?= esc($settings['default_event_duration'] ?? '6') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="booking_fee">Booking Fee ($)</label>
                            <input type="number" id="booking_fee" name="booking_fee" min="0" step="0.01" value="<?= esc($settings['booking_fee'] ?? '0') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="require_approval" <?= !empty($settings['require_approval']) ? 'checked' : '' ?>>
                                Require Admin Approval for Bookings
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="allow_weekend_bookings" <?= !empty($settings['allow_weekend_bookings']) ? 'checked' : '' ?>>
                                Allow Weekend Bookings
                            </label>
                        </div>
                    </form>
                </div>

                <!-- Payment Settings -->
                <div id="payment-settings" class="settings-tab">
                    <h3>Payment Settings</h3>
                    <form class="settings-form">
                        <div class="form-group">
                            <label for="payment_processor">Payment Processor</label>
                            <select id="payment_processor" name="payment_processor">
                                <option value="stripe" <?= ($settings['payment_processor'] ?? '') === 'stripe' ? 'selected' : '' ?>>Stripe</option>
                                <option value="paypal" <?= ($settings['payment_processor'] ?? '') === 'paypal' ? 'selected' : '' ?>>PayPal</option>
                                <option value="square" <?= ($settings['payment_processor'] ?? '') === 'square' ? 'selected' : '' ?>>Square</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="deposit_percentage">Required Deposit (%)</label>
                            <input type="number" id="deposit_percentage" name="deposit_percentage" min="0" max="100" value="<?= esc($settings['deposit_percentage'] ?? '50') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="payment_terms">Payment Terms (days)</label>
                            <input type="number" id="payment_terms" name="payment_terms" min="1" value="<?= esc($settings['payment_terms'] ?? '30') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="late_fee">Late Payment Fee ($)</label>
                            <input type="number" id="late_fee" name="late_fee" min="0" step="0.01" value="<?= esc($settings['late_fee'] ?? '25') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="auto_charge_remaining" <?= !empty($settings['auto_charge_remaining']) ? 'checked' : '' ?>>
                                Automatically charge remaining balance
                            </label>
                        </div>
                    </form>
                </div>

                <!-- Email Settings -->
                <div id="email-settings" class="settings-tab">
                    <h3>Email Settings</h3>
                    <form class="settings-form">
                        <div class="form-group">
                            <label for="smtp_host">SMTP Host</label>
                            <input type="text" id="smtp_host" name="smtp_host" value="<?= esc($settings['smtp_host'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="smtp_port">SMTP Port</label>
                            <input type="number" id="smtp_port" name="smtp_port" value="<?= esc($settings['smtp_port'] ?? '587') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="smtp_username">SMTP Username</label>
                            <input type="text" id="smtp_username" name="smtp_username" value="<?= esc($settings['smtp_username'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="smtp_password">SMTP Password</label>
                            <input type="password" id="smtp_password" name="smtp_password" value="<?= esc($settings['smtp_password'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="from_email">From Email</label>
                            <input type="email" id="from_email" name="from_email" value="<?= esc($settings['from_email'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="from_name">From Name</label>
                            <input type="text" id="from_name" name="from_name" value="<?= esc($settings['from_name'] ?? '') ?>">
                        </div>
                        
                        <div class="form-group">
                            <button type="button" class="btn btn-secondary" onclick="testEmailConnection()">
                                <i class="fas fa-paper-plane"></i>
                                Test Email Connection
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Security Settings -->
                <div id="security-settings" class="settings-tab">
                    <h3>Security Settings</h3>
                    <form class="settings-form">
                        <div class="form-group">
                            <label for="session_timeout">Session Timeout (minutes)</label>
                            <input type="number" id="session_timeout" name="session_timeout" min="5" value="<?= esc($settings['session_timeout'] ?? '120') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="max_login_attempts">Max Login Attempts</label>
                            <input type="number" id="max_login_attempts" name="max_login_attempts" min="1" value="<?= esc($settings['max_login_attempts'] ?? '5') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="lockout_duration">Lockout Duration (minutes)</label>
                            <input type="number" id="lockout_duration" name="lockout_duration" min="1" value="<?= esc($settings['lockout_duration'] ?? '15') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="require_strong_passwords" <?= !empty($settings['require_strong_passwords']) ? 'checked' : '' ?>>
                                Require Strong Passwords
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="enable_two_factor" <?= !empty($settings['enable_two_factor']) ? 'checked' : '' ?>>
                                Enable Two-Factor Authentication
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="log_admin_actions" <?= !empty($settings['log_admin_actions']) ? 'checked' : '' ?>>
                                Log Admin Actions
                            </label>
                        </div>
                    </form>
                </div>

                <!-- Maintenance Settings -->
                <div id="maintenance-settings" class="settings-tab">
                    <h3>Maintenance & Backup</h3>
                    <div class="maintenance-actions">
                        <div class="action-card">
                            <h4>Database Backup</h4>
                            <p>Create a backup of your database</p>
                            <button class="btn btn-primary" onclick="createBackup()">
                                <i class="fas fa-database"></i>
                                Create Backup
                            </button>
                        </div>
                        
                        <div class="action-card">
                            <h4>Clear Cache</h4>
                            <p>Clear all cached data to improve performance</p>
                            <button class="btn btn-secondary" onclick="clearCache()">
                                <i class="fas fa-broom"></i>
                                Clear Cache
                            </button>
                        </div>
                        
                        <div class="action-card">
                            <h4>System Info</h4>
                            <p>View system information and requirements</p>
                            <button class="btn btn-info" onclick="showSystemInfo()">
                                <i class="fas fa-info-circle"></i>
                                View Info
                            </button>
                        </div>
                        
                        <div class="action-card">
                            <h4>Update Check</h4>
                            <p>Check for system updates</p>
                            <button class="btn btn-success" onclick="checkUpdates()">
                                <i class="fas fa-sync-alt"></i>
                                Check Updates
                            </button>
                        </div>
                    </div>
                    
                    <div class="system-status">
                        <h4>System Status</h4>
                        <div class="status-grid">
                            <div class="status-item">
                                <span class="status-label">PHP Version:</span>
                                <span class="status-value"><?= PHP_VERSION ?></span>
                                <span class="status-indicator <?= version_compare(PHP_VERSION, '8.0', '>=') ? 'good' : 'warning' ?>"></span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">Database:</span>
                                <span class="status-value">Connected</span>
                                <span class="status-indicator good"></span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">Storage:</span>
                                <span class="status-value"><?= $systemInfo['storage_usage'] ?? 'Unknown' ?></span>
                                <span class="status-indicator <?= ($systemInfo['storage_percent'] ?? 0) < 80 ? 'good' : 'warning' ?>"></span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">Last Backup:</span>
                                <span class="status-value"><?= $systemInfo['last_backup'] ?? 'Never' ?></span>
                                <span class="status-indicator <?= !empty($systemInfo['last_backup']) ? 'good' : 'error' ?>"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function showSettingsTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.settings-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remove active class from nav links
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-settings').classList.add('active');
    
    // Add active class to clicked nav link
    event.target.classList.add('active');
}

function saveAllSettings() {
    const formData = new FormData();
    
    // Collect all form data from active forms
    document.querySelectorAll('.settings-form input, .settings-form select, .settings-form textarea').forEach(field => {
        if (field.type === 'checkbox') {
            formData.append(field.name, field.checked ? '1' : '0');
        } else {
            formData.append(field.name, field.value);
        }
    });
    
    // Add CSRF token
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    
    fetch('<?= site_url('admin/settings/save') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Settings saved successfully!');
        } else {
            alert('Error saving settings: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error saving settings: ' + error.message);
    });
}

function backupSettings() {
    window.location.href = '<?= site_url('admin/settings/backup') ?>';
}

function testEmailConnection() {
    const button = event.target;
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Testing...';
    
    fetch('<?= site_url('admin/settings/test-email') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        })
    })
    .then(response => response.json())
    .then(data => {
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-paper-plane"></i> Test Email Connection';
        
        if (data.success) {
            alert('Email test successful!');
        } else {
            alert('Email test failed: ' + data.message);
        }
    });
}

function createBackup() {
    if (confirm('This will create a backup of your database. Continue?')) {
        window.location.href = '<?= site_url('admin/maintenance/backup') ?>';
    }
}

function clearCache() {
    if (confirm('This will clear all cached data. Continue?')) {
        fetch('<?= site_url('admin/maintenance/clear-cache') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
        });
    }
}

function showSystemInfo() {
    window.open('<?= site_url('admin/maintenance/system-info') ?>', '_blank');
}

function checkUpdates() {
    alert('Update check functionality would be implemented here.');
}
</script>

<style>
.settings-container {
    display: grid;
    grid-template-columns: 250px 1fr;
    gap: 2rem;
    margin-top: 1rem;
}

.settings-nav {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    height: fit-content;
}

.nav-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-list li {
    margin-bottom: 0.5rem;
}

.nav-link {
    display: block;
    padding: 0.75rem 1rem;
    color: #6B7280;
    text-decoration: none;
    border-radius: 6px;
    transition: all 0.2s;
}

.nav-link:hover,
.nav-link.active {
    background: #4F46E5;
    color: white;
}

.settings-content {
    background: white;
    border-radius: 8px;
    padding: 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.settings-tab {
    display: none;
}

.settings-tab.active {
    display: block;
}

.settings-form {
    max-width: 600px;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #374151;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #D1D5DB;
    border-radius: 6px;
    font-size: 0.875rem;
}

.checkbox-label {
    display: flex !important;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.checkbox-label input {
    width: auto !important;
}

.maintenance-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.action-card {
    padding: 1.5rem;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    text-align: center;
}

.action-card h4 {
    margin: 0 0 0.5rem 0;
    color: #111827;
}

.action-card p {
    margin: 0 0 1rem 0;
    color: #6B7280;
    font-size: 0.875rem;
}

.system-status {
    margin-top: 2rem;
}

.status-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.status-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    border: 1px solid #E5E7EB;
    border-radius: 6px;
}

.status-label {
    font-weight: 500;
    color: #374151;
}

.status-value {
    margin-left: auto;
    color: #6B7280;
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.status-indicator.good { background: #10B981; }
.status-indicator.warning { background: #F59E0B; }
.status-indicator.error { background: #EF4444; }

@media (max-width: 768px) {
    .settings-container {
        grid-template-columns: 1fr;
    }
    
    .settings-nav {
        order: 2;
    }
    
    .nav-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .nav-list li {
        margin-bottom: 0;
    }
}
</style>
<?= $this->endSection() ?>

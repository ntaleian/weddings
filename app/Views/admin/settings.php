<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>
<?php
$pageActions = '
    <button type="button" class="btn btn-secondary btn-sm" onclick="resetToDefaults()">
        <i class="fas fa-undo"></i> Reset to Defaults
    </button>
';
?>
<?= $this->include('admin_template/partials/page_header', [
    'title' => 'System Settings',
    'subtitle' => 'Configure system preferences and settings',
    'actions' => $pageActions
]) ?>

<div class="settings-container">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <!-- Settings Navigation Tabs -->
            <div class="settings-nav">
                <button class="nav-tab active" onclick="showTab('wedding-fees')">
                    <i class="fas fa-dollar-sign"></i>
                    Wedding Fees
                </button>
                <button class="nav-tab" onclick="showTab('general')">
                    <i class="fas fa-cog"></i>
                    General Settings
                </button>
                <button class="nav-tab" onclick="showTab('booking')">
                    <i class="fas fa-calendar"></i>
                    Booking Settings
                </button>
                <button class="nav-tab" onclick="showTab('notifications')">
                    <i class="fas fa-bell"></i>
                    Notifications
                </button>
            </div>

            <!-- Wedding Fees Tab -->
            <div id="wedding-fees" class="settings-tab active">
                <form action="<?= site_url('admin/settings/wedding-fees') ?>" method="post" class="settings-form">
                    <?= csrf_field() ?>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Wedding Fee Structure</h3>
                            <p style="margin: 0.5rem 0 0 0; color: var(--text-tertiary);">Set the pricing for various wedding services and amenities.</p>
                        </div>
                        
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="base_wedding_fee">Base Wedding Fee *</label>
                                    <div class="input-with-currency">
                                        <span class="currency-symbol">UGX</span>
                                        <input type="number" 
                                               id="base_wedding_fee" 
                                               name="base_wedding_fee" 
                                               value="<?= $weddingFees['base_wedding_fee'] ?? 500000 ?>" 
                                               required
                                               min="0"
                                               step="1000"
                                               placeholder="500000">
                                    </div>
                                    <small class="form-help">Base fee charged for all wedding ceremonies</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="overtime_fee_per_hour">Overtime Fee per Hour</label>
                                    <div class="input-with-currency">
                                        <span class="currency-symbol">UGX</span>
                                        <input type="number" 
                                               id="overtime_fee_per_hour" 
                                               name="overtime_fee_per_hour" 
                                               value="<?= $weddingFees['overtime_fee_per_hour'] ?? 50000 ?>" 
                                               min="0"
                                               step="1000"
                                               placeholder="50000">
                                    </div>
                                    <small class="form-help">Additional fee for events exceeding scheduled time</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Save Wedding Fees
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- General Settings Tab -->
            <div id="general" class="settings-tab">
                <form action="<?= site_url('admin/settings/general') ?>" method="post" class="settings-form">
                    <?= csrf_field() ?>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">General System Settings</h3>
                            <p style="margin: 0.5rem 0 0 0; color: var(--text-tertiary);">Configure basic system information and preferences.</p>
                        </div>
                        
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="site_name">Site Name *</label>
                                    <input type="text" 
                                           id="site_name" 
                                           name="site_name" 
                                           value="<?= $generalSettings['site_name'] ?? 'Wedding Management System' ?>" 
                                           required
                                           placeholder="Wedding Management System">
                                </div>
                                
                                <div class="form-group">
                                    <label for="site_email">Contact Email *</label>
                                    <input type="email" 
                                           id="site_email" 
                                           name="site_email" 
                                           value="<?= $generalSettings['site_email'] ?? '' ?>" 
                                           required
                                           placeholder="admin@church.com">
                                </div>
                                
                                <div class="form-group">
                                    <label for="site_phone">Contact Phone</label>
                                    <input type="tel" 
                                           id="site_phone" 
                                           name="site_phone" 
                                           value="<?= $generalSettings['site_phone'] ?? '' ?>" 
                                           placeholder="+256700000000">
                                </div>
                                
                                <div class="form-group">
                                    <label for="currency_symbol">Currency Symbol</label>
                                    <input type="text" 
                                           id="currency_symbol" 
                                           name="currency_symbol" 
                                           value="<?= $generalSettings['currency_symbol'] ?? 'UGX' ?>" 
                                           placeholder="UGX">
                                </div>
                                
                                <div class="form-group full-width">
                                    <label for="site_address">Church Address</label>
                                    <textarea id="site_address" 
                                              name="site_address" 
                                              rows="3"
                                              placeholder="Enter church address..."><?= $generalSettings['site_address'] ?? '' ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Save General Settings
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Booking Settings Tab -->
            <div id="booking" class="settings-tab">
                <form action="<?= site_url('admin/settings/booking') ?>" method="post" class="settings-form">
                    <?= csrf_field() ?>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Booking Configuration</h3>
                            <p style="margin: 0.5rem 0 0 0; color: var(--text-tertiary);">Set booking rules and limitations for wedding reservations.</p>
                        </div>
                        
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="advance_booking_days">Minimum advance (days) *</label>
                                    <input type="number"
                                           id="advance_booking_days"
                                           name="advance_booking_days"
                                           value="<?= esc($bookingSettings['advance_booking_days'] ?? 180) ?>"
                                           required
                                           min="0"
                                           placeholder="180">
                                    <small class="form-help">Couples cannot pick a wedding date sooner than this many days from today.</small>
                                </div>

                                <div class="form-group">
                                    <label for="earliest_selectable_date">Earliest selectable date</label>
                                    <input type="date"
                                           id="earliest_selectable_date"
                                           name="earliest_selectable_date"
                                           value="<?= esc($bookingSettings['earliest_selectable_date'] ?? '') ?>">
                                    <small class="form-help">Optional. The first calendar day users may choose (e.g. start of next season). Leave empty to use only “minimum advance” above. If both apply, the <strong>later</strong> date is used.</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Save Booking Settings
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Notifications Tab -->
            <div id="notifications" class="settings-tab">
                <form action="<?= site_url('admin/settings/notifications') ?>" method="post" class="settings-form">
                    <?= csrf_field() ?>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Notification Settings</h3>
                            <p style="margin: 0.5rem 0 0 0; color: var(--text-tertiary);">Configure email notifications and reminders.</p>
                        </div>
                        
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="send_booking_confirmations">Booking Confirmations</label>
                                    <select id="send_booking_confirmations" name="send_booking_confirmations">
                                        <option value="1" <?= ($notificationSettings['send_booking_confirmations'] ?? true) ? 'selected' : '' ?>>
                                            Send Email Confirmations
                                        </option>
                                        <option value="0" <?= !($notificationSettings['send_booking_confirmations'] ?? true) ? 'selected' : '' ?>>
                                            Disable Email Confirmations
                                        </option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="send_reminder_notifications">Reminder Notifications</label>
                                    <select id="send_reminder_notifications" name="send_reminder_notifications">
                                        <option value="1" <?= ($notificationSettings['send_reminder_notifications'] ?? true) ? 'selected' : '' ?>>
                                            Send Reminders
                                        </option>
                                        <option value="0" <?= !($notificationSettings['send_reminder_notifications'] ?? true) ? 'selected' : '' ?>>
                                            Disable Reminders
                                        </option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="reminder_days_before">Reminder Days Before Wedding</label>
                                    <input type="number" 
                                           id="reminder_days_before" 
                                           name="reminder_days_before" 
                                           value="<?= $notificationSettings['reminder_days_before'] ?? 7 ?>" 
                                           min="1"
                                           placeholder="7">
                                    <small class="form-help">Send reminder emails X days before the wedding</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="admin_notification_email">Admin Notification Email</label>
                                    <input type="email" 
                                           id="admin_notification_email" 
                                           name="admin_notification_email" 
                                           value="<?= $notificationSettings['admin_notification_email'] ?? '' ?>" 
                                           placeholder="admin@church.com">
                                    <small class="form-help">Email address to receive admin notifications</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Save Notification Settings
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.settings-container {
    max-width: 1200px;
    margin: 0 auto;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-danger {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.alert-success {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    color: #155724;
}

.settings-nav {
    display: flex;
    gap: 2px;
    margin-bottom: 30px;
    background: var(--light-gray);
    border-radius: 8px;
    padding: 4px;
}

.nav-tab {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 20px;
    border: none;
    background: transparent;
    color: var(--gray);
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
}

.nav-tab:hover {
    background: white;
    color: var(--dark-gray);
}

.nav-tab.active {
    background: var(--primary-color);
    color: #3d2929;
    box-shadow: 0 2px 8px rgba(100, 1, 127, 0.3);
}

.settings-tab {
    display: none;
}

.settings-tab.active {
    display: block;
}

/* Settings cards use template card class */
.settings-container .card {
    margin-bottom: 1.5rem;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
}

.form-grid .full-width {
    grid-column: 1 / -1;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: 600;
    color: var(--dark-gray);
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.form-group input,
.form-group textarea,
.form-group select {
    padding: 12px 15px;
    border: 2px solid #cccccc;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px var(--primary-color-10);
}

.input-with-currency {
    position: relative;
    display: flex;
    align-items: center;
}

.currency-symbol {
    position: absolute;
    left: 15px;
    color: var(--gray);
    font-weight: 600;
    z-index: 2;
}

.input-with-currency input {
    padding-left: 50px;
}

.form-help {
    color: var(--gray);
    font-size: 0.8rem;
    margin-top: 5px;
}

.card-footer {
    padding: 20px 30px;
    background: var(--light-gray);
    border-top: 1px solid var(--border-color);
    text-align: right;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-primary {
    background:  #00BC15;
    color: white;
}

.btn-primary:hover {
    background: #00BC15;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(100, 1, 127, 0.3);
}

.btn-secondary {
    background: var(--gray);
    color: white;
}

.btn-secondary:hover {
    background: var(--dark-gray);
    transform: translateY(-2px);
}

@media (max-width: 968px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .settings-nav {
        flex-wrap: wrap;
    }
    
    .nav-tab {
        flex: none;
        min-width: 120px;
    }
}

@media (max-width: 768px) {
    .card-footer {
        padding: 15px 20px;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function showTab(tabName) {
    // Hide all tabs
    const tabs = document.querySelectorAll('.settings-tab');
    tabs.forEach(tab => tab.classList.remove('active'));
    
    // Remove active class from all nav tabs
    const navTabs = document.querySelectorAll('.nav-tab');
    navTabs.forEach(tab => tab.classList.remove('active'));
    
    // Show selected tab
    document.getElementById(tabName).classList.add('active');
    
    // Add active class to clicked nav tab
    event.target.classList.add('active');
}

function resetToDefaults() {
    if (confirm('Are you sure you want to reset all settings to their default values? This action cannot be undone.')) {
        // Create a form to submit reset request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= site_url('admin/settings/reset') ?>';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// Format currency inputs
document.addEventListener('DOMContentLoaded', function() {
    const currencyInputs = document.querySelectorAll('.input-with-currency input');
    
    currencyInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d]/g, '');
            if (value) {
                // Format with commas
                e.target.value = parseInt(value).toLocaleString();
            }
        });
        
        input.addEventListener('focus', function(e) {
            // Remove formatting on focus for easier editing
            e.target.value = e.target.value.replace(/[^\d]/g, '');
        });
        
        input.addEventListener('blur', function(e) {
            // Re-format on blur
            let value = e.target.value.replace(/[^\d]/g, '');
            if (value) {
                e.target.value = parseInt(value).toLocaleString();
            }
        });
    });
});

// Form validation
document.querySelectorAll('.settings-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('input[required], select[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.style.borderColor = '#dc3545';
            } else {
                field.style.borderColor = '';
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
});
</script>
<?= $this->endSection() ?>

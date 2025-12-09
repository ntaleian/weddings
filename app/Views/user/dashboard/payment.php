<?= $this->extend('layouts/user/base') ?>

<?= $this->section('styles') ?>
    <link href="<?= base_url('assets/css/dashboard.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/dashboard-components.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/dashboard-sections.css') ?>" rel="stylesheet">
<style>
/* Payment Page Styles */
.payment-container {
    max-width: 900px;
    margin: 0 auto;
}

.payment-card {
    background: #fff;
    border: 1px solid #e1e5e9;
    border-radius: 8px;
    padding: 24px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.payment-header {
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid #e9ecef;
}

.payment-header h2 {
    color: #64017f;
    margin-bottom: 8px;
    font-size: 1.5rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.payment-header p {
    color: #6c757d;
    margin: 0;
    font-size: 0.95rem;
}

.payment-amount {
    font-size: 2.2rem;
    font-weight: 700;
    color: #64017f;
    margin: 16px 0;
    text-align: center;
}

.payment-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 16px;
    font-size: 0.85rem;
    font-weight: 500;
    margin-bottom: 16px;
}

.status-unpaid {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffd60a;
}

.status-paid {
    background: #d1eddd;
    color: #155724;
    border: 1px solid #28a745;
}

.status-pending {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #17a2b8;
}

.payment-instructions {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-left: 4px solid #64017f;
    padding: 20px;
    border-radius: 6px;
    margin: 20px 0;
}

.payment-instructions h3 {
    color: #64017f;
    margin-bottom: 12px;
    font-size: 1.1rem;
    font-weight: 600;
}

.payment-instructions p {
    color: #495057;
    line-height: 1.5;
    margin-bottom: 16px;
}

.bank-details {
    background: #fff;
    border: 1px solid #dee2e6;
    padding: 16px;
    border-radius: 6px;
    margin: 12px 0;
}

.bank-detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f1f3f4;
}

.bank-detail-item:last-child {
    border-bottom: none;
}

.bank-detail-label {
    font-weight: 500;
    color: #6c757d;
    font-size: 0.9rem;
}

.bank-detail-value {
    font-weight: 600;
    color: #212529;
    font-family: monospace;
    font-size: 0.9rem;
}

.payment-form {
    background: #fff;
    border: 1px solid #dee2e6;
    padding: 20px;
    border-radius: 6px;
    margin-top: 20px;
}

.payment-form h3 {
    color: #64017f;
    margin-bottom: 16px;
    font-size: 1.1rem;
    font-weight: 600;
}

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
}

.form-control:focus {
    outline: none;
    border-color: #64017f;
    box-shadow: 0 0 0 2px rgba(100, 1, 127, 0.1);
}

.form-control[readonly] {
    background-color: #f8f9fa;
    cursor: not-allowed;
    opacity: 0.9;
}

.form-control[readonly]:focus {
    border-color: #ced4da;
    box-shadow: none;
}

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

.payment-history {
    margin-top: 24px;
}

.payment-history h3 {
    color: #64017f;
    margin-bottom: 16px;
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.payment-item {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 16px;
    margin-bottom: 12px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.payment-info h4 {
    margin: 0 0 6px 0;
    color: #212529;
    font-size: 0.95rem;
    font-weight: 600;
}

.payment-meta {
    font-size: 0.85rem;
    color: #6c757d;
    line-height: 1.4;
}

.payment-meta i {
    width: 12px;
    margin-right: 4px;
}

.payment-item-amount {
    font-size: 1.1rem;
    font-weight: 600;
    color: #64017f;
    text-align: right;
}

.no-payments {
    text-align: center;
    padding: 32px;
    color: #6c757d;
    font-size: 0.95rem;
}

/* Remove complex alert styles - use existing flash message system */
.alert {
    /* Inherit from flash message system */
}

@media (max-width: 768px) {
    .payment-card {
        padding: 16px;
        margin-bottom: 16px;
    }
    
    .payment-amount {
        font-size: 1.8rem;
    }
    
    .bank-detail-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
        padding: 12px 0;
    }
    
    .payment-item {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }
    
    .payment-item-amount {
        text-align: left;
        font-size: 1rem;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?= $this->include('partials/user_nav') ?>
    
    <!-- Dashboard Main Content -->
    <div class="dashboard-container">
        <?= $this->include('partials/user_sidebar') ?>
        
        <main class="dashboard-main">
            <!-- Flash Messages -->
            <?= $this->include('partials/flash_messages') ?>
            
            <!-- Payment Section -->
            <?= $this->include('user/dashboard/sections/payment') ?>
        </main>
    </div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
    <script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
    <script>
        // Initialize payment functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Set today as default payment date
            const paymentDateInput = document.getElementById('payment_date');
            if (paymentDateInput && !paymentDateInput.value) {
                paymentDateInput.value = new Date().toISOString().split('T')[0];
            }
            
            // Amount input is readonly, no formatting needed
        });
    </script>
<?= $this->endSection() ?>

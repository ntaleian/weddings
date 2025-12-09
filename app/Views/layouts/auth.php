<?= $this->extend('layouts/base') ?>

<?= $this->section('styles') ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url('css/auth.css') ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        /* Custom Toastr styling to match brand colors */
        .toast-success {
            background: linear-gradient(135deg, rgba(37, 128, 45, 0.1) 0%, rgba(37, 128, 45, 0.15) 100%) !important;
            color: #1a5a20 !important;
            border-left: 4px solid #25802D !important;
        }
        
        .toast-error {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%) !important;
            color: #721c24 !important;
            border-left: 4px solid #dc3545 !important;
        }
        
        .toast-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%) !important;
            color: #0c5460 !important;
            border-left: 4px solid #17a2b8 !important;
        }
        
        .toast-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%) !important;
            color: #856404 !important;
            border-left: 4px solid #ffc107 !important;
        }
        
        .toast {
            border-radius: 12px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }
        
        .toast-progress {
            opacity: 0.6 !important;
        }
    </style>
    <!-- Render additional CSS from individual views -->
    <?= $this->renderSection('additional_css') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?= $this->include('partials/auth_navbar') ?>
    
    <!-- Auth Section -->
    <section class="auth-section">
        <div class="container">
            <div class="auth-container">
                <div class="auth-left">
                    <div class="auth-content">
                        <?= $this->renderSection('auth_left') ?>
                    </div>
                </div>

                <div class="auth-right">
                    <div class="auth-form-container">
                        <?= $this->renderSection('auth_right') ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url('js/auth.js') ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        // Configure Toastr to match brand colors
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        
        <?php if (session()->getFlashdata('success')): ?>
            toastr.success("<?= esc(session()->getFlashdata('success'), 'js') ?>");
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            toastr.error("<?= esc(session()->getFlashdata('error'), 'js') ?>");
        <?php endif; ?>
        <?php if (session()->getFlashdata('info')): ?>
            toastr.info("<?= esc(session()->getFlashdata('info'), 'js') ?>");
        <?php endif; ?>
        <?php if (session()->getFlashdata('warning')): ?>
            toastr.warning("<?= esc(session()->getFlashdata('warning'), 'js') ?>");
        <?php endif; ?>
    </script>
    
    <!-- Render additional scripts from individual views -->
    <?= $this->renderSection('additional_scripts') ?>
<?= $this->endSection() ?>

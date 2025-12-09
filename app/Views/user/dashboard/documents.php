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
            
            <!-- Documents Section -->
            <?= $this->include('user/dashboard/sections/documents') ?>
        </main>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
    <script>
        // Initialize document upload functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Handle file uploads
            document.querySelectorAll('input[type="file"]').forEach(input => {
                input.addEventListener('change', function() {
                    if (window.dashboard) {
                        dashboard.handleFileUpload(this);
                    }
                });
            });
        });
    </script>
<?= $this->endSection() ?>

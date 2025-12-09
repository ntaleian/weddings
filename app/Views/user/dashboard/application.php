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
            
            <!-- Application Section -->
            <?= $this->include('user/dashboard/sections/application') ?>
        </main>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <!-- Application Form JavaScript - handled by step1_venue_date.php -->
    <script>
        // Disable the default dashboard step navigation to avoid conflicts
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Application page loaded - using custom step navigation from step1_venue_date.php');
        });
    </script>
<?= $this->endSection() ?>

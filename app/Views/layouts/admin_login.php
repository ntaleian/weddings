<?= $this->extend('layouts/base') ?>

<?= $this->section('styles') ?>
    <link href="<?= base_url('css/admin.css') ?>" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="admin-login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="<?= base_url('images/watoto_logo.png') ?>" alt="Watoto Church" class="logo">
                <h2>Admin Portal</h2>
                <p>Wedding Booking System</p>
            </div>
            
            <!-- Flash Messages -->
            <?= $this->include('partials/flash_messages') ?>
            
            <?= $this->renderSection('main_content') ?>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url('js/admin-login.js') ?>"></script>
<?= $this->endSection() ?>

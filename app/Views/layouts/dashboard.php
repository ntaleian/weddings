<?= $this->extend('layouts/base') ?>

<?= $this->section('styles') ?>
    <link href="<?= base_url('css/dashboard.css') ?>" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?= $this->include('partials/dashboard_sidebar') ?>
    
    <div class="main-content">
        <?= $this->include('partials/dashboard_header') ?>
        
        <!-- Flash Messages -->
        <?= $this->include('partials/flash_messages') ?>
        
        <!-- Page Content -->
        <main class="content-area">
            <?= $this->renderSection('main_content') ?>
        </main>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url('js/dashboard.js') ?>"></script>
<?= $this->endSection() ?>

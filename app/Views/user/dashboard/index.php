<?= $this->extend('layouts/user/base') ?>

<?= $this->section('styles') ?>
    <link href="<?= base_url('css/dashboard.css') ?>" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?= $this->include('partials/user_nav') ?>
    <!-- Dashboard Main Content -->
    <div class="dashboard-container">

        <?= $this->include('partials/user_sidebar') ?>
    
        <main class="dashboard-main">
            <?= $this->include('partials/dashboard_header') ?>
            
            <!-- Flash Messages -->
            <?= $this->include('partials/flash_messages') ?>
            
            <!-- Page Content -->
            <main class="content-area">
                <?= $this->renderSection('main_content') ?>
            </main>
        </main>

    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url('js/dashboard.js') ?>"></script>
<?= $this->endSection() ?>
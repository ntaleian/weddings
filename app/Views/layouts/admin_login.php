<?= $this->extend('layouts/base') ?>

<?= $this->section('styles') ?>
    <link href="<?= base_url('css/admin.css') ?>" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="admin-login-container">
        <div class="admin-login-card">
            <?= $this->renderSection('main_content') ?>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <?= $this->renderSection('scripts') ?>
<?= $this->endSection() ?>

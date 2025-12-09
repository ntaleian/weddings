<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
    <?= $this->include('partials/navbar') ?>
    
    <!-- Flash Messages -->
    <?= $this->include('partials/flash_messages') ?>
    
    <!-- Page Content -->
    <main>
        <?= $this->renderSection('main_content') ?>
    </main>
    
    <?= $this->include('partials/footer') ?>
<?= $this->endSection() ?>

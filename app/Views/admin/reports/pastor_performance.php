<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>

<?php
$pageActions = '
    <a href="' . site_url('admin/reports') . '" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
';
?>

<?= $this->include('admin_template/partials/page_header', [
    'title' => 'Pastor Performance Report',
    'subtitle' => 'Wedding assignments by pastor',
    'actions' => $pageActions
]) ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Pastor Wedding Assignments</h3>
    </div>
    <div class="card-body">
        <p>This report shows wedding assignments by pastor.</p>
        <!-- Add your pastor performance report content here -->
    </div>
</div>

<?= $this->endSection() ?>

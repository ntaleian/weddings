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
    'title' => 'Revenue Report',
    'subtitle' => 'Financial analysis and revenue tracking',
    'actions' => $pageActions
]) ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Revenue Analytics</h3>
    </div>
    <div class="card-body">
        <p>This report shows financial analysis and revenue tracking.</p>
        <p><strong>Total Revenue:</strong> UGX <?= number_format($totalRevenue ?? 0) ?></p>
        <!-- Add your revenue report content here -->
    </div>
</div>

<?= $this->endSection() ?>

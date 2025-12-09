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
    'title' => 'Monthly Trends Report',
    'subtitle' => 'Booking trends and patterns by month',
    'actions' => $pageActions
]) ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Monthly Booking Trends for <?= $year ?? date('Y') ?></h3>
    </div>
    <div class="card-body">
        <p>This report shows booking trends and patterns by month.</p>
        <!-- Add your monthly trends report content here -->
    </div>
</div>

<?= $this->endSection() ?>

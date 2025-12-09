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
    'title' => 'Completed Weddings Report',
    'subtitle' => 'Successfully completed wedding ceremonies',
    'actions' => $pageActions
]) ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Completed Weddings</h3>
        <div class="card-actions">
            <span class="badge badge-info"><?= count($bookings ?? []) ?> Total</span>
        </div>
    </div>
    <div class="card-body">
        <p>This report shows all completed wedding ceremonies.</p>
        <!-- Add your completed weddings report content here -->
    </div>
</div>

<?= $this->endSection() ?>

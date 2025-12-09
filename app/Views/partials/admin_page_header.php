<?php
/**
 * Standard Admin Page Header Partial
 * 
 * Usage:
 * <?= $this->include('partials/admin_page_header', [
 *     'title' => 'Page Title',
 *     'subtitle' => 'Optional subtitle',
 *     'actions' => '<button>Action</button>'
 * ]) ?>
 */
$title = $title ?? 'Admin';
$subtitle = $subtitle ?? null;
$actions = $actions ?? '';
?>
<div class="page-header">
    <div class="header-content">
        <h1 class="page-title"><?= esc($title) ?></h1>
        <?php if (!empty($subtitle)): ?>
            <p class="page-subtitle"><?= esc($subtitle) ?></p>
        <?php endif; ?>
    </div>
    <?php if (!empty($actions)): ?>
        <div class="header-actions">
            <?= $actions ?>
        </div>
    <?php endif; ?>
</div>


<?php
/**
 * Standard Admin Card Partial
 * 
 * Usage:
 * <?= $this->include('partials/admin_card', [
 *     'title' => 'Card Title',
 *     'content' => 'Card content HTML',
 *     'footer' => 'Optional footer HTML',
 *     'actions' => 'Optional action buttons'
 * ]) ?>
 */
$title = $title ?? '';
$content = $content ?? '';
$footer = $footer ?? null;
$actions = $actions ?? '';
?>
<div class="admin-card">
    <?php if (!empty($title) || !empty($actions)): ?>
        <div class="card-header">
            <?php if (!empty($title)): ?>
                <h3 class="card-title"><?= esc($title) ?></h3>
            <?php endif; ?>
            <?php if (!empty($actions)): ?>
                <div class="card-actions">
                    <?= $actions ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="card-body">
        <?= $content ?>
    </div>
    <?php if (!empty($footer)): ?>
        <div class="card-footer">
            <?= $footer ?>
        </div>
    <?php endif; ?>
</div>


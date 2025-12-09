<?php
/**
 * Page Header Partial
 * 
 * @param string $title - Page title
 * @param string $subtitle - Optional subtitle
 * @param string $actions - Optional action buttons HTML
 */
// Extract variables from passed data if they exist
$pageTitle = $title ?? '';
$pageSubtitle = $subtitle ?? '';
$pageActions = $actions ?? '';
?>
<div class="page-header">
    <div class="header-content">
        <div class="title-section">
            <h1 class="page-title"><?= esc($pageTitle ?: 'Page Title') ?></h1>
            <?php if (!empty($pageSubtitle)): ?>
                <p class="page-subtitle"><?= esc($pageSubtitle) ?></p>
            <?php endif; ?>
        </div>
        <?php if (!empty($pageActions)): ?>
            <div class="page-actions">
                <?= $pageActions ?>
            </div>
        <?php endif; ?>
    </div>
</div>


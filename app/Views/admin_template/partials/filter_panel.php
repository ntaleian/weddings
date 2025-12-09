<?php
/**
 * Filter Panel Partial
 * 
 * @param string $content - Filter form content
 * @param bool $collapsible - Whether panel can be collapsed
 */
?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Filters</h3>
        <?php if ($collapsible ?? false): ?>
            <button class="card-toggle" onclick="toggleFilterPanel()">
                <i class="fas fa-chevron-up"></i>
            </button>
        <?php endif; ?>
    </div>
    <div class="card-body" id="filterPanelBody">
        <?= $content ?? '' ?>
    </div>
</div>


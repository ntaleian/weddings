<?php
/**
 * Action Buttons Partial
 * 
 * @param array $actions - Array of action buttons with keys: type, icon, title, onclick/url, class
 * Example: [
 *   ['type' => 'view', 'icon' => 'fa-eye', 'title' => 'View', 'url' => '/admin/booking/1'],
 *   ['type' => 'edit', 'icon' => 'fa-edit', 'title' => 'Edit', 'onclick' => 'editItem(1)'],
 *   ['type' => 'delete', 'icon' => 'fa-trash', 'title' => 'Delete', 'onclick' => 'deleteItem(1)']
 * ]
 */
// Get actions from passed data
$actionButtons = $actions ?? [];
?>
<?php if (!empty($actionButtons) && is_array($actionButtons)): ?>
<div class="action-buttons" style="display: flex !important; gap: 6px; align-items: center; flex-wrap: nowrap; visibility: visible !important;">
    <?php foreach ($actionButtons as $action): ?>
        <?php
        $type = $action['type'] ?? 'default';
        $icon = $action['icon'] ?? 'fa-circle';
        $title = $action['title'] ?? '';
        $class = $action['class'] ?? '';
        $onclick = $action['onclick'] ?? '';
        $url = $action['url'] ?? '#';
        ?>
        <?php if (!empty($onclick)): ?>
            <button type="button" class="btn-action <?= esc($type) ?> <?= esc($class) ?>" 
                    onclick="<?= esc($onclick) ?>" 
                    title="<?= esc($title) ?>"
                    style="width: 32px !important; height: 32px !important; border: none !important; border-radius: 6px !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; cursor: pointer !important; text-decoration: none !important; font-size: 0.85rem !important; flex-shrink: 0; visibility: visible !important;">
                <i class="fas <?= esc($icon) ?>"></i>
            </button>
        <?php else: ?>
            <a href="<?= esc($url) ?>" 
               class="btn-action <?= esc($type) ?> <?= esc($class) ?>" 
               title="<?= esc($title) ?>"
               style="width: 32px !important; height: 32px !important; border: none !important; border-radius: 6px !important; display: inline-flex !important; align-items: center !important; justify-content: center !important; cursor: pointer !important; text-decoration: none !important; font-size: 0.85rem !important; flex-shrink: 0; visibility: visible !important;">
                <i class="fas <?= esc($icon) ?>"></i>
            </a>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<?php endif; ?>

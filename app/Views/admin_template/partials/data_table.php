<?php
/**
 * Data Table Partial
 * 
 * @param string $tableId - ID for the table
 * @param string $title - Table title
 * @param string $thead - Table header HTML
 * @param string $tbody - Table body HTML
 * @param string $tools - Optional table tools HTML
 * @param array $options - DataTable options
 */
?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?= esc($title ?? 'Data Table') ?></h3>
        <?php if (isset($tools)): ?>
            <div class="card-actions">
                <?= $tools ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table class="data-table" id="<?= esc($tableId ?? 'dataTable') ?>">
                <thead>
                    <?= $thead ?? '' ?>
                </thead>
                <tbody>
                    <?= $tbody ?? '' ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if (isset($options)): ?>
<script>
$(document).ready(function() {
    $('#<?= esc($tableId ?? 'dataTable') ?>').DataTable(<?= json_encode($options) ?>);
});
</script>
<?php endif; ?>


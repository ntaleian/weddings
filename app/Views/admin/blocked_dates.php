<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>
<?php
$pageActions = '
    <button class="btn btn-primary btn-sm" onclick="openQuickBlock()">
        <i class="fas fa-plus"></i> Quick Block
    </button>
';
?>
<?= $this->include('admin_template/partials/page_header', [
    'title' => 'Blocked Dates',
    'subtitle' => 'Manage unavailable dates across all campuses',
    'actions' => $pageActions
]) ?>

<!-- Quick Block Form -->
<div class="card" id="quickBlockForm" style="display: none;">
    <div class="card-header">
        <h3 class="card-title">Quick Block Date</h3>
    </div>
    <div class="card-body">
        <form action="<?= site_url('admin/blocked-dates') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Campus</label>
                    <select name="campus_id" class="form-control" required>
                        <option value="">Select Campus</option>
                        <?php foreach ($campuses as $campus): ?>
                            <option value="<?= $campus['id'] ?>"><?= esc($campus['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Date</label>
                    <input type="date" name="blocked_date" class="form-control" min="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Reason</label>
                    <input type="text" name="reason" class="form-control" placeholder="Reason for blocking..." required>
                </div>
                <div class="form-group">
                    <label class="form-label">&nbsp;</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <button type="submit" class="btn btn-success">Block</button>
                        <button type="button" class="btn btn-secondary" onclick="closeQuickBlock()">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Stats -->
<?php
$futureBlocked = 0;
$thisWeekBlocked = 0;
foreach ($blockedDates as $blockedDate) {
    if (strtotime($blockedDate['blocked_date']) >= time()) {
        $futureBlocked++;
    }
    if (strtotime($blockedDate['blocked_date']) >= time() && 
        strtotime($blockedDate['blocked_date']) < strtotime('+7 days')) {
        $thisWeekBlocked++;
    }
}
?>
<div class="stats-grid" style="margin-bottom: 1.5rem;">
    <?= $this->include('admin_template/partials/stat_card', [
        'value' => count($blockedDates),
        'label' => 'Total Blocked',
        'icon' => 'fa-ban',
        'iconGradient' => 'linear-gradient(135deg, #ef4444, #dc2626)'
    ]) ?>
    
    <?= $this->include('admin_template/partials/stat_card', [
        'value' => $futureBlocked,
        'label' => 'Active Blocks',
        'icon' => 'fa-calendar-times',
        'iconGradient' => 'linear-gradient(135deg, #f59e0b, #d97706)'
    ]) ?>
    
    <?= $this->include('admin_template/partials/stat_card', [
        'value' => count($campuses),
        'label' => 'Campuses',
        'icon' => 'fa-building',
        'iconGradient' => 'linear-gradient(135deg, #3b82f6, #2563eb)'
    ]) ?>
    
    <?= $this->include('admin_template/partials/stat_card', [
        'value' => $thisWeekBlocked,
        'label' => 'This Week',
        'icon' => 'fa-calendar-week',
        'iconGradient' => 'linear-gradient(135deg, #8b5cf6, #7c3aed)'
    ]) ?>
</div>

<!-- Filters -->
<?php
ob_start();
?>
<div class="filter-row">
    <div class="form-group">
        <label class="form-label">Campus</label>
        <select id="campusFilter" class="form-control">
            <option value="">All Campuses</option>
            <?php foreach ($campuses as $campus): ?>
                <option value="<?= esc($campus['name']) ?>"><?= esc($campus['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Status</label>
        <select id="statusFilter" class="form-control">
            <option value="">All Dates</option>
            <option value="upcoming">Upcoming</option>
            <option value="past">Past</option>
            <option value="this-week">This Week</option>
        </select>
    </div>
</div>
<?php
$filterContent = ob_get_clean();
?>
<?= $this->include('admin_template/partials/filter_panel', ['content' => $filterContent]) ?>

<!-- Blocked Dates Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Blocked Dates</h3>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table class="data-table" id="blockedDatesTable">
                <thead>
                    <tr>
                        <th>Campus</th>
                        <th>Date</th>
                        <th>Reason</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($blockedDates)): ?>
                        <tr>
                            <td colspan="5" class="text-center">No blocked dates found.</td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        usort($blockedDates, function($a, $b) {
                            $dateA = strtotime($a['blocked_date']);
                            $dateB = strtotime($b['blocked_date']);
                            $now = time();
                            
                            if (($dateA >= $now && $dateB >= $now) || ($dateA < $now && $dateB < $now)) {
                                return $dateA - $dateB;
                            }
                            
                            return ($dateA >= $now) ? -1 : 1;
                        });
                        
                        foreach ($blockedDates as $blockedDate): ?>
                        <tr class="<?= strtotime($blockedDate['blocked_date']) < time() ? 'past-date' : 'future-date' ?>">
                            <td>
                                <span class="badge badge-info"><?= esc($blockedDate['campus_name']) ?></span>
                            </td>
                            <td data-order="<?= strtotime($blockedDate['blocked_date']) ?>">
                                <div class="date-info">
                                    <strong><?= date('M j, Y', strtotime($blockedDate['blocked_date'])) ?></strong>
                                    <small class="d-block text-muted"><?= date('l', strtotime($blockedDate['blocked_date'])) ?></small>
                                    <?php if (strtotime($blockedDate['blocked_date']) < time()): ?>
                                        <span class="badge badge-secondary mt-1">Past</span>
                                    <?php elseif (strtotime($blockedDate['blocked_date']) < strtotime('+7 days')): ?>
                                        <span class="badge badge-warning mt-1">This Week</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span title="<?= esc($blockedDate['reason']) ?>">
                                    <?= esc(substr($blockedDate['reason'], 0, 50)) ?><?= strlen($blockedDate['reason']) > 50 ? '...' : '' ?>
                                </span>
                            </td>
                            <td data-order="<?= strtotime($blockedDate['created_at']) ?>">
                                <small class="text-muted"><?= date('M j, Y', strtotime($blockedDate['created_at'])) ?></small>
                            </td>
                            <td>
                                <?php if (strtotime($blockedDate['blocked_date']) >= time()): ?>
                                    <?= $this->include('admin_template/partials/action_buttons', [
                                        'actions' => [
                                            ['type' => 'delete', 'icon' => 'fa-trash', 'title' => 'Remove Block', 'onclick' => 'removeBlock(' . $blockedDate['id'] . ')']
                                        ]
                                    ]) ?>
                                <?php else: ?>
                                    <span class="text-muted">Past date</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.past-date {
    opacity: 0.7;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function openQuickBlock() {
    document.getElementById('quickBlockForm').style.display = 'block';
    document.querySelector('[name="campus_id"]').focus();
}

function closeQuickBlock() {
    document.getElementById('quickBlockForm').style.display = 'none';
}

function removeBlock(id) {
    if (confirm('Remove this blocked date? Users will be able to book this date again.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= site_url('admin/blocked-date/') ?>${id}`;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        
        form.appendChild(methodInput);
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}

$(document).ready(function() {
    const table = $('#blockedDatesTable').DataTable({
        order: [[1, 'asc']],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        language: {
            search: "Search dates:",
            lengthMenu: "Show _MENU_ dates per page",
            info: "Showing _START_ to _END_ of _TOTAL_ blocked dates",
            infoEmpty: "No blocked dates found",
            infoFiltered: "(filtered from _MAX_ total dates)",
            emptyTable: "No blocked dates found",
            zeroRecords: "No matching dates found"
        },
        columnDefs: [
            {
                targets: 4,
                orderable: false,
                searchable: false
            }
        ],
        responsive: true,
        dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>rtip'
    });

    const campusFilter = document.getElementById('campusFilter');
    const statusFilter = document.getElementById('statusFilter');
    
    campusFilter.addEventListener('change', function() {
        table.column(0).search(this.value).draw();
    });
    
    statusFilter.addEventListener('change', function() {
        if (this.value === '') {
            table.column(1).search('').draw();
        } else if (this.value === 'upcoming') {
            table.column(1).search('upcoming').draw();
        } else if (this.value === 'past') {
            table.column(1).search('past').draw();
        }
    });
});
</script>
<?= $this->endSection() ?>

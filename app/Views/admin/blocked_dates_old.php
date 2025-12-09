<?= $this->extend('layouts/admin/admin') ?>

<?= $this->section('main_content') ?>
<div class="content-wrapper">
    <!-- Compact Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="title-section">
                <h2>Blocked Dates</h2>
                <p>Manage unavailable dates across all campuses</p>
            </div>
            <div class="quick-actions">
                <button class="btn btn-sm btn-primary" onclick="openQuickBlock()">
                    <i class="fas fa-plus"></i>
                    Quick Block
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Block Compact Form -->
    <div id="quickBlockForm" class="quick-form" style="display: none;">
        <form class="inline-form" action="<?= site_url('admin/blocked-dates') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="form-row">
                <select name="campus_id" class="form-control form-control-sm" required>
                    <option value="">Select Campus</option>
                    <?php foreach ($campuses as $campus): ?>
                        <option value="<?= $campus['id'] ?>"><?= esc($campus['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="date" name="blocked_date" class="form-control form-control-sm" min="<?= date('Y-m-d') ?>" required>
                <input type="text" name="reason" class="form-control form-control-sm" placeholder="Reason for blocking..." required>
                <button type="submit" class="btn btn-sm btn-success">Block</button>
                <button type="button" class="btn btn-sm btn-secondary" onclick="closeQuickBlock()">Cancel</button>
            </div>
        </form>
    </div>

    <!-- Compact Stats -->
    <div class="stats-row">
        <div class="stat-item">
            <span class="stat-number"><?= count($blockedDates) ?></span>
            <span class="stat-label">Total</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">
                <?php
                $futureBlocked = 0;
                foreach ($blockedDates as $blockedDate) {
                    if (strtotime($blockedDate['blocked_date']) >= time()) {
                        $futureBlocked++;
                    }
                }
                echo $futureBlocked;
                ?>
            </span>
            <span class="stat-label">Active</span>
        </div>
        <div class="stat-item">
            <span class="stat-number"><?= count($campuses) ?></span>
            <span class="stat-label">Campuses</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">
                <?php
                $thisWeekBlocked = 0;
                foreach ($blockedDates as $blockedDate) {
                    if (strtotime($blockedDate['blocked_date']) >= time() && 
                        strtotime($blockedDate['blocked_date']) < strtotime('+7 days')) {
                        $thisWeekBlocked++;
                    }
                }
                echo $thisWeekBlocked;
                ?>
            </span>
            <span class="stat-label">This Week</span>
        </div>
    </div>

    <!-- Compact Filter Bar -->
    <div class="filter-bar">
        <div class="filter-controls">
            <select id="campusFilter" class="form-control form-control-sm">
                <option value="">All Campuses</option>
                <?php foreach ($campuses as $campus): ?>
                    <option value="<?= esc($campus['name']) ?>"><?= esc($campus['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <select id="statusFilter" class="form-control form-control-sm">
                <option value="">All Dates</option>
                <option value="upcoming">Upcoming</option>
                <option value="past">Past</option>
                <option value="this-week">This Week</option>
            </select>
        </div>
    </div>

    <!-- Data Table -->
    <div class="table-container">
        <table class="table table-striped table-hover" id="blockedDatesTable">
            <thead>
                <tr>
                    <th>Campus</th>
                    <th data-sort="date">Date</th>
                    <th>Reason</th>
                    <th data-sort="date">Created</th>
                    <th data-orderable="false">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($blockedDates)): ?>
                    <tr>
                        <td colspan="5" class="text-center">No blocked dates found.</td>
                    </tr>
                <?php else: ?>
                    <?php 
                    // Sort dates: future dates first, then past dates
                    usort($blockedDates, function($a, $b) {
                        $dateA = strtotime($a['blocked_date']);
                        $dateB = strtotime($b['blocked_date']);
                        $now = time();
                        
                        // If both are future or both are past, sort by date ascending
                        if (($dateA >= $now && $dateB >= $now) || ($dateA < $now && $dateB < $now)) {
                            return $dateA - $dateB;
                        }
                        
                        // Future dates come first
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
                                <small><?= date('l', strtotime($blockedDate['blocked_date'])) ?></small>
                                <?php if (strtotime($blockedDate['blocked_date']) < time()): ?>
                                    <span class="status past">Past</span>
                                <?php elseif (strtotime($blockedDate['blocked_date']) < strtotime('+7 days')): ?>
                                    <span class="status upcoming">This Week</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <span class="reason-text" title="<?= esc($blockedDate['reason']) ?>">
                                <?= esc(substr($blockedDate['reason'], 0, 50)) ?><?= strlen($blockedDate['reason']) > 50 ? '...' : '' ?>
                            </span>
                        </td>
                        <td data-order="<?= strtotime($blockedDate['created_at']) ?>">
                            <small class="text-muted"><?= date('M j, Y', strtotime($blockedDate['created_at'])) ?></small>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <?php if (strtotime($blockedDate['blocked_date']) >= time()): ?>
                                    <button class="btn-action delete" onclick="removeBlock(<?= $blockedDate['id'] ?>)" title="Remove Block">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                <?php else: ?>
                                    <span class="text-muted">Past date</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
/* Compact Page Design */
.page-header {
    background: #64017f;
    color: white;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.title-section h2 {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0 0 0.25rem 0;
}

.title-section p {
    opacity: 0.9;
    margin: 0;
    font-size: 0.9rem;
}

/* Quick Form */
.quick-form {
    background: white;
    border: 1px solid #e1e5e9;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.inline-form .form-row {
    display: grid;
    grid-template-columns: 180px 140px 1fr auto auto;
    gap: 0.75rem;
    align-items: center;
}

/* Compact Stats */
.stats-row {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.stat-item {
    background: white;
    border: 1px solid #e1e5e9;
    border-radius: 6px;
    padding: 0.75rem;
    text-align: center;
    flex: 1;
}

.stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #64017f;
}

.stat-label {
    display: block;
    font-size: 0.8rem;
    color: #6b7280;
    margin-top: 0.25rem;
}

/* Filter Bar */
.filter-bar {
    background: white;
    border: 1px solid #e1e5e9;
    border-radius: 6px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.filter-controls {
    display: flex;
    gap: 1rem;
}

/* Table */
.table-container {
    background: white;
    border: 1px solid #e1e5e9;
    border-radius: 6px;
    overflow: hidden;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    background: #f8f9fa;
    padding: 0.75rem;
    font-weight: 600;
    color: #374151;
    border-bottom: 1px solid #e5e7eb;
    font-size: 0.9rem;
}

.data-table td {
    padding: 0.75rem;
    border-bottom: 1px solid #f3f4f6;
    font-size: 0.85rem;
}

.data-table tr:hover {
    background: #f9fafb;
}

.badge {
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.badge-info {
    background: #64017f;
    color: white;
}

.date-info strong {
    display: block;
    color: #374151;
    font-size: 0.9rem;
}

.date-info small {
    color: #6b7280;
    font-size: 0.75rem;
}

.status {
    padding: 0.2rem 0.4rem;
    border-radius: 10px;
    font-size: 0.7rem;
    font-weight: 500;
    margin-left: 0.5rem;
}

.status.past {
    background: #f3f4f6;
    color: #6b7280;
}

.status.upcoming {
    background: #fef3c7;
    color: #92400e;
}

.reason-text {
    color: #374151;
    font-size: 0.85rem;
}

.btn-action {
    background: none;
    border: 1px solid #e5e7eb;
    color: #6b7280;
    padding: 0.4rem 0.6rem;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.8rem;
}

.btn-action.delete:hover {
    background: #fef2f2;
    border-color: #fca5a5;
    color: #dc2626;
}

.text-muted {
    color: #6b7280;
    font-size: 0.8rem;
}

.text-center {
    text-align: center;
}

/* DataTable Styling */
.dataTables_wrapper {
    margin-top: 0;
}

.dataTables_length, .dataTables_filter {
    margin-bottom: 1rem;
}

.dataTables_length select {
    padding: 0.25rem 0.5rem;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    font-size: 0.875rem;
}

.dataTables_filter input {
    padding: 0.25rem 0.5rem;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    margin-left: 0.5rem;
    font-size: 0.875rem;
}

.dataTables_info {
    color: #6b7280;
    font-size: 0.875rem;
    margin-top: 1rem;
}

.dataTables_paginate {
    margin-top: 1rem;
}

.dataTables_paginate .paginate_button {
    padding: 0.5rem 0.75rem;
    margin: 0 0.125rem;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    background: white;
    color: #374151;
    text-decoration: none;
    font-size: 0.875rem;
}

.dataTables_paginate .paginate_button:hover {
    background: #64017f;
    border-color: #64017f;
    color: white;
}

.dataTables_paginate .paginate_button.current {
    background: #64017f;
    border-color: #64017f;
    color: white;
}

.dataTables_paginate .paginate_button.disabled {
    color: #9ca3af;
    background: #f9fafb;
}

/* Responsive Design */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 0.75rem;
        text-align: center;
    }
    
    .inline-form .form-row {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }
    
    .stats-row {
        flex-wrap: wrap;
    }
    
    .filter-controls {
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .dataTables_length, .dataTables_filter {
        text-align: center;
    }
    
    .dataTables_paginate {
        text-align: center;
    }
}
</style>

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

// Filter functionality and DataTable initialization
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable with custom configuration
    const table = $('#blockedDatesTable').DataTable({
        "order": [[ 1, "asc" ]], // Sort by date column (already pre-sorted by PHP)
        "pageLength": 25,
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "language": {
            "search": "Search dates:",
            "lengthMenu": "Show _MENU_ dates per page",
            "info": "Showing _START_ to _END_ of _TOTAL_ blocked dates",
            "infoEmpty": "No blocked dates found",
            "infoFiltered": "(filtered from _MAX_ total dates)",
            "emptyTable": "No blocked dates found",
            "zeroRecords": "No matching dates found"
        },
        "columnDefs": [
            {
                "targets": 3, // Actions column
                "orderable": false,
                "searchable": false
            }
        ],
        "responsive": true,
        "dom": '<"row"<"col-sm-6"l><"col-sm-6"f>>rtip'
    });

    // Custom filters still work alongside DataTable search
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
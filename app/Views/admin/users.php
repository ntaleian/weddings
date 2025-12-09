<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>
<?php
$pageActions = '
    <button class="btn btn-secondary btn-sm" onclick="toggleFilter()">
        <i class="fas fa-filter"></i> Filter
    </button>
    <button class="btn btn-success btn-sm" onclick="exportUsers()">
        <i class="fas fa-download"></i> Export
    </button>
';
?>
<?= $this->include('admin_template/partials/page_header', [
    'title' => 'Manage Users',
    'subtitle' => 'View and manage user accounts',
    'actions' => $pageActions
]) ?>

<!-- Filter Panel -->
<?php
ob_start();
?>
<div class="filter-row">
    <div class="form-group">
        <label class="form-label">Status</label>
        <select id="statusFilter" class="form-control">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">From Date</label>
        <input type="date" id="startDate" class="form-control">
    </div>
    <div class="form-group">
        <label class="form-label">To Date</label>
        <input type="date" id="endDate" class="form-control">
    </div>
    <div class="form-group">
        <label class="form-label">Role</label>
        <select id="roleFilter" class="form-control">
            <option value="">All Roles</option>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">&nbsp;</label>
        <div class="filter-actions">
            <button class="btn btn-primary btn-sm" onclick="applyFilters()">Apply Filters</button>
            <button class="btn btn-secondary btn-sm" onclick="clearFilters()">Clear</button>
        </div>
    </div>
</div>
<?php
$filterContent = ob_get_clean();
?>
<div class="card" id="filterPanel" style="display: none;">
    <div class="card-header">
        <h3 class="card-title">Filters</h3>
    </div>
    <div class="card-body">
        <?= $filterContent ?>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Users</h3>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table id="usersDataTable" class="data-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Bookings</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                            <tr class="user-row">
                                <td>
                                    <strong class="user-id">#<?= str_pad($user['id'], 4, '0', STR_PAD_LEFT) ?></strong>
                                </td>
                                <td>
                                    <div class="user-info">
                                        <strong><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></strong>
                                        <?php if (!empty($user['partner_name'])): ?>
                                            <small class="partner-name">Partner: <?= esc($user['partner_name']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="contact-info">
                                        <a href="mailto:<?= esc($user['email']) ?>"><?= esc($user['email']) ?></a>
                                    </div>
                                </td>
                                <td>
                                    <div class="phone-info">
                                        <?php if (!empty($user['phone'])): ?>
                                            <a href="tel:<?= esc($user['phone']) ?>"><?= esc($user['phone']) ?></a>
                                        <?php else: ?>
                                            <small class="text-muted">Not provided</small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td data-order="<?= $user['is_active'] ? 1 : 0 ?>">
                                    <?php if ($user['is_active']): ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="booking-count">
                                        <span class="badge-count"><?= $user['booking_count'] ?? 0 ?></span>
                                        <small>bookings</small>
                                    </div>
                                </td>
                                <td data-order="<?= isset($user['created_at']) ? strtotime($user['created_at']) : 0 ?>">
                                    <div class="date-info">
                                        <strong><?= isset($user['created_at']) ? date('M j, Y', strtotime($user['created_at'])) : 'Unknown' ?></strong>
                                        <small><?= isset($user['created_at']) ? date('g:i A', strtotime($user['created_at'])) : '' ?></small>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $actions = [
                                        ['type' => 'view', 'icon' => 'fa-eye', 'title' => 'View Details', 'url' => site_url('admin/user/' . $user['id'])]
                                    ];
                                    
                                    if ($user['is_active']) {
                                        $actions[] = ['type' => 'warning', 'icon' => 'fa-user-times', 'title' => 'Deactivate', 'onclick' => 'deactivateUser(' . $user['id'] . ')'];
                                    } else {
                                        $actions[] = ['type' => 'approve', 'icon' => 'fa-user-check', 'title' => 'Activate', 'onclick' => 'activateUser(' . $user['id'] . ')'];
                                    }
                                    
                                    $actions[] = ['type' => 'info', 'icon' => 'fa-envelope', 'title' => 'Send Email', 'url' => 'mailto:' . esc($user['email'])];
                                    $actions[] = ['type' => 'delete', 'icon' => 'fa-trash', 'title' => 'Delete User', 'onclick' => 'deleteUser(' . $user['id'] . ')'];
                                    ?>
                                    <?= $this->include('admin_template/partials/action_buttons', ['actions' => $actions]) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
        </div>
    </div>
</div>

<!-- Modal for User Deletion Confirmation -->
<div class="modal-overlay" id="deleteModal" style="display: none;">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fas fa-trash"></i> Delete User</h3>
            <button class="modal-close" onclick="closeModal('deleteModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="deleteForm" method="POST">
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Are you sure you want to delete this user?</strong>
                    <p style="margin-top: 0.5rem; margin-bottom: 0;">This action cannot be undone and will also delete all associated bookings and data.</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Reason for Deletion (Optional):</label>
                    <textarea class="form-control" id="deletion_reason" name="reason" rows="3" placeholder="Provide a reason for deleting this user account..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete User
                </button>
            </div>
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1rem;
    align-items: end;
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function toggleFilter() {
    const panel = document.getElementById('filterPanel');
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
}

function activateUser(id) {
    if (confirm('Are you sure you want to activate this user?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= site_url('admin/user/') ?>${id}/activate`;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function deactivateUser(id) {
    if (confirm('Are you sure you want to deactivate this user? They will not be able to login.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= site_url('admin/user/') ?>${id}/deactivate`;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteUser(id) {
    document.getElementById('deleteForm').action = `<?= site_url('admin/user/') ?>${id}/delete`;
    showModal('deleteModal');
}

function showModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = 'auto';
    
    // Clear form data
    const form = document.querySelector(`#${modalId} form`);
    if (form) {
        form.reset();
    }
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modals = document.querySelectorAll('.admin-modal');
        modals.forEach(modal => {
            if (modal.style.display === 'flex') {
                closeModal(modal.id);
            }
        });
    }
});

function exportUsers() {
    // Simple CSV export without DataTable buttons dependency
    const table = $('#usersDataTable').DataTable();
    const rows = table.rows({ filter: 'applied' }).data();
    
    // Create CSV content
    let csvContent = 'ID,User,Email,Phone,Status,Bookings,Joined\n';
    
    // Add data rows
    rows.each(function(row, index) {
        const cells = table.row(index).nodes().to$().find('td');
        const csvRow = [
            $(cells[0]).text().trim(), // ID
            $(cells[1]).text().trim(), // User
            $(cells[2]).text().trim(), // Email
            $(cells[3]).text().trim(), // Phone
            $(cells[4]).text().trim(), // Status
            $(cells[5]).text().trim(), // Bookings
            $(cells[6]).text().trim()  // Joined
        ];
        
        // Escape quotes and wrap in quotes
        const escapedRow = csvRow.map(cell => '"' + cell.replace(/"/g, '""') + '"');
        csvContent += escapedRow.join(',') + '\n';
    });
    
    // Download CSV
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'users_' + new Date().toISOString().slice(0,10) + '.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function refreshTable() {
    // Simple page reload since we're not using server-side processing
    location.reload();
}

function applyFilters() {
    const status = document.getElementById('statusFilter').value;
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const role = document.getElementById('roleFilter').value;
    
    const table = $('#usersDataTable').DataTable();
    
    // Apply status filter
    if (status) {
        table.column(4).search(status);
    } else {
        table.column(4).search('');
    }
    
    // Apply role filter (if role column exists)
    if (role) {
        table.column(1).search(role);
    } else {
        table.column(1).search('');
    }
    
    // For date range filtering, we'll use a custom filter
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        if (settings.nTable.id !== 'usersDataTable') {
            return true;
        }
        
        const joinedDateStr = data[6]; // Joined date column
        if (!startDate && !endDate) {
            return true;
        }
        
        // Extract date from the formatted string
        const dateMatch = joinedDateStr.match(/(\w{3} \d{1,2}, \d{4})/);
        if (!dateMatch) {
            return true;
        }
        
        const joinedDate = new Date(dateMatch[1]);
        const start = startDate ? new Date(startDate) : null;
        const end = endDate ? new Date(endDate) : null;
        
        if (start && joinedDate < start) {
            return false;
        }
        if (end && joinedDate > end) {
            return false;
        }
        
        return true;
    });
    
    table.draw();
}

function clearFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    document.getElementById('roleFilter').value = '';
    
    const table = $('#usersDataTable').DataTable();
    
    // Clear all column searches
    table.columns().search('').draw();
    
    // Clear custom date filter
    $.fn.dataTable.ext.search.pop();
}

// Initialize DataTable
$(document).ready(function() {
    $('#usersDataTable').DataTable({
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        order: [[6, 'desc']], // Sort by joined date descending
        columnDefs: [
            {
                targets: [7], // Actions column
                orderable: false,
                searchable: false
            },
            {
                targets: [4, 6], // Status and date columns
                type: 'num' // Use data-order attributes for sorting
            }
        ],
        language: {
            search: "Search users:",
            lengthMenu: "Show _MENU_ users per page",
            info: "Showing _START_ to _END_ of _TOTAL_ users",
            infoEmpty: "No users found",
            infoFiltered: "(filtered from _MAX_ total users)",
            emptyTable: "No users have been registered yet",
            zeroRecords: "No matching users found"
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        initComplete: function() {
            // Move filter panel above DataTable controls
            const filterPanel = $('#filterPanel');
            if (filterPanel.length) {
                filterPanel.insertBefore('.dataTables_wrapper');
            }
        }
    });
});
</script>

});
</script>
<?= $this->endSection() ?>

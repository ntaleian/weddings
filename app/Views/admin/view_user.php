<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>
<?php
ob_start();
echo '<a href="' . site_url('admin/users') . '" class="btn btn-secondary btn-sm">
    <i class="fas fa-arrow-left"></i> Back to Users
</a>';

if ($user['is_active']):
    echo '<button type="button" class="btn btn-warning btn-sm" onclick="deactivateUser(' . $user['id'] . ')">
        <i class="fas fa-user-times"></i> Deactivate
    </button>';
else:
    echo '<button type="button" class="btn btn-success btn-sm" onclick="activateUser(' . $user['id'] . ')">
        <i class="fas fa-user-check"></i> Activate
    </button>';
endif;

$pageActions = ob_get_clean();
?>
<?= $this->include('admin_template/partials/page_header', [
    'title' => 'User Details',
    'subtitle' => 'View complete user information and booking history',
    'actions' => $pageActions
]) ?>

        <!-- User Status Banner -->
        <div class="user-status-banner <?= $user['is_active'] ? 'status-approved' : 'status-cancelled' ?>">
            <div class="banner-background"></div>
            <div class="banner-content">
                <div class="user-avatar">
                    <div class="avatar-circle">
                        <i class="fas <?= $user['is_active'] ? 'fa-user-check' : 'fa-user-times' ?>"></i>
                    </div>
                </div>
                <div class="user-details">
                    <h2 class="user-name"><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></h2>
                    <p class="user-meta">
                        <span class="user-status">
                            <i class="fas <?= $user['is_active'] ? 'fa-circle' : 'fa-circle' ?>"></i>
                            <?= $user['is_active'] ? 'Active User' : 'Inactive User' ?>
                        </span>
                        <span class="user-id">ID: #<?= str_pad($user['id'], 4, '0', STR_PAD_LEFT) ?></span>
                    </p>
                    <div class="user-badges">
                        <span class="status-indicator <?= $user['is_active'] ? 'active' : 'inactive' ?>">
                            <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                        <span class="role-indicator"><?= ucfirst($user['role']) ?></span>
                    </div>
                </div>
                <div class="banner-actions">
                    <?php if (count($bookings) > 0): ?>
                    <div class="quick-stat">
                        <div class="stat-number"><?= count($bookings) ?></div>
                        <div class="stat-text">Booking<?= count($bookings) !== 1 ? 's' : '' ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

<!-- User Information Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
    <!-- Personal Information Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user"></i> Personal Information</h3>
        </div>
        <div class="card-body">
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-signature"></i>
                            <span>Full Name</span>
                        </div>
                        <div class="info-value">
                            <?= esc($user['first_name'] . ' ' . $user['last_name']) ?>
                        </div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-envelope"></i>
                            <span>Email Address</span>
                        </div>
                        <div class="info-value">
                            <a href="mailto:<?= esc($user['email']) ?>" class="contact-link">
                                <?= esc($user['email']) ?>
                            </a>
                        </div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-phone"></i>
                            <span>Phone Number</span>
                        </div>
                        <div class="info-value">
                            <?php if (!empty($user['phone'])): ?>
                                <a href="tel:<?= esc($user['phone']) ?>" class="contact-link">
                                    <?= esc($user['phone']) ?>
                                </a>
                            <?php else: ?>
                                <span class="not-provided">Not provided</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-user-shield"></i>
                            <span>User Role</span>
                        </div>
                        <div class="info-value">
                            <span class="role-badge"><?= ucfirst($user['role']) ?></span>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Wedding Information Card -->
    <?php if (!empty($user['partner_name']) || !empty($user['preferred_date'])): ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-heart"></i> Wedding Information</h3>
        </div>
        <div class="card-body">
                    <?php if (!empty($user['partner_name'])): ?>
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-ring"></i>
                            <span>Partner Name</span>
                        </div>
                        <div class="info-value">
                            <?= esc($user['partner_name']) ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($user['preferred_date'])): ?>
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-calendar-heart"></i>
                            <span>Preferred Date</span>
                        </div>
                        <div class="info-value">
                            <span class="date-value"><?= date('F j, Y', strtotime($user['preferred_date'])) ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

    <!-- Account Information Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-clock"></i> Account Information</h3>
        </div>
        <div class="card-body">
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-calendar-plus"></i>
                            <span>Registration Date</span>
                        </div>
                        <div class="info-value">
                            <span class="date-value">
                                <?= isset($user['created_at']) ? date('F j, Y', strtotime($user['created_at'])) : 'Unknown' ?>
                            </span>
                            <small class="time-value">
                                <?= isset($user['created_at']) ? date('g:i A', strtotime($user['created_at'])) : '' ?>
                            </small>
                        </div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-edit"></i>
                            <span>Last Updated</span>
                        </div>
                        <div class="info-value">
                            <span class="date-value">
                                <?= isset($user['updated_at']) ? date('F j, Y', strtotime($user['updated_at'])) : 'Never' ?>
                            </span>
                            <?php if (isset($user['updated_at'])): ?>
                            <small class="time-value">
                                <?= date('g:i A', strtotime($user['updated_at'])) ?>
                            </small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Booking Statistics Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-chart-pie"></i> Booking Statistics</h3>
        </div>
        <div class="card-body">
                    <div class="stats-container">
                        <div class="stat-item total">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number"><?= count($bookings) ?></div>
                                <div class="stat-label">Total Bookings</div>
                            </div>
                        </div>
                        
                        <div class="stat-item approved">
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">
                                    <?php
                                    $approvedCount = 0;
                                    foreach ($bookings as $booking) {
                                        if ($booking['status'] === 'approved') $approvedCount++;
                                    }
                                    echo $approvedCount;
                                    ?>
                                </div>
                                <div class="stat-label">Approved</div>
                            </div>
                        </div>
                        
                        <div class="stat-item pending">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">
                                    <?php
                                    $pendingCount = 0;
                                    foreach ($bookings as $booking) {
                                        if ($booking['status'] === 'pending') $pendingCount++;
                                    }
                                    echo $pendingCount;
                                    ?>
                                </div>
                                <div class="stat-label">Pending</div>
                            </div>
                        </div>
                        
                        <div class="stat-item completed">
                            <div class="stat-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number">
                                    <?php
                                    $completedCount = 0;
                                    foreach ($bookings as $booking) {
                                        if ($booking['status'] === 'completed') $completedCount++;
                                    }
                                    echo $completedCount;
                                    ?>
                                </div>
                                <div class="stat-label">Completed</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<!-- User's Bookings Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">User's Wedding Bookings</h3>
        <div class="card-actions">
            <button class="btn btn-success btn-sm" onclick="exportUserBookings()">
                <i class="fas fa-download"></i> Export
            </button>
            <button class="btn btn-info btn-sm" onclick="refreshTable()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table id="userBookingsDataTable" class="data-table" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Couple Names</th>
                            <th>Campus</th>
                            <th>Wedding Date</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bookings)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No bookings found for this user.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($bookings as $booking): ?>
                            <tr class="booking-row">
                                <td>
                                    <strong class="booking-id">#<?= str_pad($booking['id'], 4, '0', STR_PAD_LEFT) ?></strong>
                                </td>
                                <td>
                                    <div class="couple-names">
                                        <strong><?= esc($booking['bride_name'] ?? '') ?> & <?= esc($booking['groom_name'] ?? '') ?></strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="campus-badge"><?= esc($booking['campus_name'] ?? 'Unknown') ?></span>
                                </td>
                                <td data-order="<?= isset($booking['wedding_date']) ? strtotime($booking['wedding_date']) : 0 ?>">
                                    <div class="date-info">
                                        <strong><?= isset($booking['wedding_date']) ? date('M j, Y', strtotime($booking['wedding_date'])) : 'Not set' ?></strong>
                                        <small><?= isset($booking['wedding_time']) ? date('g:i A', strtotime($booking['wedding_time'])) : 'Time TBD' ?></small>
                                    </div>
                                </td>
                                <td data-order="<?= array_search($booking['status'] ?? 'unknown', ['pending', 'approved', 'rejected', 'completed', 'cancelled']) ?>">
                                    <?php
                                    $statusBadgeClass = 'badge-secondary';
                                    switch($booking['status']) {
                                        case 'pending':
                                            $statusBadgeClass = 'badge-warning';
                                            break;
                                        case 'approved':
                                            $statusBadgeClass = 'badge-success';
                                            break;
                                        case 'rejected':
                                            $statusBadgeClass = 'badge-danger';
                                            break;
                                        case 'completed':
                                            $statusBadgeClass = 'badge-info';
                                            break;
                                        case 'cancelled':
                                            $statusBadgeClass = 'badge-secondary';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?= $statusBadgeClass ?>"><?= ucfirst($booking['status'] ?? 'unknown') ?></span>
                                </td>
                                <td data-order="<?= isset($booking['created_at']) ? strtotime($booking['created_at']) : 0 ?>">
                                    <small class="created-date"><?= isset($booking['created_at']) ? date('M j, Y', strtotime($booking['created_at'])) : 'Unknown' ?></small>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= site_url('admin/booking/' . $booking['id']) ?>" class="btn-action view" title="View Booking">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
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
/* User Status Banner */
.user-status-banner {
    position: relative;
    background: #ffffff;
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
}

.banner-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 120px;
    background: #495057;
    opacity: 0.9;
}

.user-status-banner.status-approved .banner-background {
    background: #28a745;
}

.user-status-banner.status-cancelled .banner-background {
    background: #dc3545;
}

.banner-content {
    position: relative;
    display: flex;
    align-items: center;
    padding: 32px 32px 24px;
    gap: 24px;
}

.user-avatar {
    position: relative;
    z-index: 2;
}

.avatar-circle {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: #495057;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    border: 4px solid rgba(255, 255, 255, 0.9);
}

.user-status-banner.status-approved .avatar-circle {
    color: #28a745;
}

.user-status-banner.status-cancelled .avatar-circle {
    color: #dc3545;
}

.user-details {
    flex: 1;
    position: relative;
    z-index: 2;
}

.user-name {
    margin: 0 0 8px 0;
    font-size: 28px;
    font-weight: 700;
    color: #ffffff;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    line-height: 1.2;
}

.user-meta {
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
}

.user-status {
    display: flex;
    align-items: center;
    gap: 8px;
    color: rgba(255, 255, 255, 0.9);
    font-size: 14px;
    font-weight: 500;
}

.user-status i {
    font-size: 8px;
    animation: pulse 2s infinite;
}

.user-id {
    color: rgba(255, 255, 255, 0.8);
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 0.5px;
}

.user-badges {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.status-indicator {
    background: rgba(255, 255, 255, 0.95);
    color: #333;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.status-indicator.active {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status-indicator.inactive {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.role-indicator {
    background: #6c757d;
    color: white;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.banner-actions {
    position: relative;
    z-index: 2;
}

.quick-stat {
    text-align: center;
    background: rgba(255, 255, 255, 0.95);
    padding: 16px 20px;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
    min-width: 80px;
}

.quick-stat .stat-number {
    font-size: 24px;
    font-weight: 700;
    color: #333;
    line-height: 1;
}

.quick-stat .stat-text {
    font-size: 11px;
    color: #666;
    margin-top: 4px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

/* User Info Grid */
.user-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 1rem 0;
    border-bottom: 1px solid #f1f3f4;
    gap: 1rem;
}

.info-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.info-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    color: #495057;
    min-width: 140px;
    font-size: 0.9rem;
}

.info-label i {
    color: #6c757d;
    width: 16px;
    text-align: center;
}

.info-value {
    flex: 1;
    text-align: right;
    font-size: 0.9rem;
}

.contact-link {
    color: #667eea;
    text-decoration: none;
    transition: color 0.2s ease;
}

.contact-link:hover {
    color: #764ba2;
    text-decoration: underline;
}

.not-provided {
    color: #6c757d;
    font-style: italic;
    font-size: 13px;
}

.role-badge {
    background: #495057;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    letter-spacing: 0.5px;
}

.date-value {
    font-weight: 500;
    color: #212529;
}

.time-value {
    display: block;
    color: #6c757d;
    font-size: 12px;
    margin-top: 2px;
}

.stats-container {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.stat-item.total .stat-icon {
    background: #495057;
}

.stat-item.approved .stat-icon {
    background: #28a745;
}

.stat-item.pending .stat-icon {
    background: #ffc107;
}

.stat-item.completed .stat-icon {
    background: #17a2b8;
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #212529;
    line-height: 1;
}

.stat-label {
    font-size: 0.75rem;
    color: #6c757d;
    margin-top: 0.25rem;
    font-weight: 500;
}

/* Responsive Design */
@media (max-width: 768px) {
    .banner-content {
        flex-direction: column;
        text-align: center;
        padding: 24px 20px;
        gap: 16px;
    }
    
    .avatar-circle {
        width: 60px;
        height: 60px;
        font-size: 20px;
    }
    
    .user-name {
        font-size: 22px;
    }
    
    .user-meta {
        justify-content: center;
        gap: 16px;
    }
    
    .user-badges {
        justify-content: center;
    }
    
    .user-info-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .info-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .info-value {
        text-align: left;
    }
    
    .info-label {
        min-width: auto;
    }
    
    .stats-container {
        grid-template-columns: 1fr;
        gap: 12px;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
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

function exportUserBookings() {
    // Simple CSV export for user's bookings
    const table = $('#userBookingsDataTable').DataTable();
    const rows = table.rows({ filter: 'applied' }).data();
    
    // Create CSV content
    let csvContent = 'ID,Couple Names,Campus,Wedding Date,Status,Created\n';
    
    // Add data rows
    rows.each(function(row, index) {
        const cells = table.row(index).nodes().to$().find('td');
        const csvRow = [
            $(cells[0]).text().trim(), // ID
            $(cells[1]).text().trim(), // Couple Names
            $(cells[2]).text().trim(), // Campus
            $(cells[3]).text().trim(), // Wedding Date
            $(cells[4]).text().trim(), // Status
            $(cells[5]).text().trim()  // Created
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
    link.setAttribute('download', 'user_<?= $user['id'] ?>_bookings_' + new Date().toISOString().slice(0,10) + '.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function refreshTable() {
    location.reload();
}

// Initialize DataTable for user bookings
$(document).ready(function() {
    $('#userBookingsDataTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
        order: [[5, 'desc']], // Sort by created date descending
        columnDefs: [
            {
                targets: [6], // Actions column
                orderable: false,
                searchable: false
            },
            {
                targets: [3, 5], // Date columns
                type: 'num' // Use data-order attributes for sorting
            }
        ],
        language: {
            search: "Search user bookings:",
            lengthMenu: "Show _MENU_ bookings per page",
            info: "Showing _START_ to _END_ of _TOTAL_ bookings",
            infoEmpty: "No bookings found",
            infoFiltered: "(filtered from _MAX_ total bookings)",
            emptyTable: "This user has no wedding bookings yet",
            zeroRecords: "No matching bookings found"
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
    });
});
</script>
<?= $this->endSection() ?>

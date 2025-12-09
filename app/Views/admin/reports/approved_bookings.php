<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>

<?php
$pageActions = '
    <a href="' . site_url('admin/reports/export') . '?type=approved&' . http_build_query($filters ?? []) . '" 
       class="btn btn-success btn-sm" target="_blank">
        <i class="fas fa-download"></i> Export
    </a>
    <a href="' . site_url('admin/reports') . '" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
';
?>

<?= $this->include('admin_template/partials/page_header', [
    'title' => 'Approved Bookings Report',
    'subtitle' => 'List of all approved wedding bookings',
    'actions' => $pageActions
]) ?>

<!-- Filter Panel -->
<?php
ob_start();
?>
<div class="filter-row">
    <div class="form-group">
        <label class="form-label">From Date</label>
        <input type="date" id="start_date" name="start_date" class="form-control" 
               value="<?= $filters['start_date'] ?? '' ?>">
    </div>
    
    <div class="form-group">
        <label class="form-label">To Date</label>
        <input type="date" id="end_date" name="end_date" class="form-control" 
               value="<?= $filters['end_date'] ?? '' ?>">
    </div>
    
    <div class="form-group">
        <label class="form-label">Campus</label>
        <select id="campus_id" name="campus_id" class="form-control">
            <option value="">All Campuses</option>
            <?php foreach ($campuses as $campus): ?>
                <option value="<?= $campus['id'] ?>" 
                        <?= ($filters['campus_id'] ?? '') == $campus['id'] ? 'selected' : '' ?>>
                    <?= esc($campus['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label class="form-label">&nbsp;</label>
        <div class="filter-actions">
            <button type="button" class="btn btn-primary btn-sm" onclick="applyFilters()">
                <i class="fas fa-filter"></i> Apply
            </button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="clearFilters()">
                <i class="fas fa-times"></i> Clear
            </button>
        </div>
    </div>
</div>
<?php
$filterContent = ob_get_clean();
?>
<?= $this->include('admin_template/partials/filter_panel', ['content' => $filterContent]) ?>

<!-- Bookings Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Approved Bookings</h3>
        <div class="card-actions">
            <span class="badge badge-success"><?= count($bookings ?? []) ?> Total</span>
        </div>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table id="bookingsTable" class="data-table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Couple Names</th>
                        <th>Campus</th>
                        <th>Wedding Date</th>
                        <th>Pastor</th>
                        <th>Status</th>
                        <th>Applied Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings ?? [] as $booking): ?>
                        <tr class="booking-row">
                            <td><strong>#<?= $booking['id'] ?></strong></td>
                            <td>
                                <div class="couple-names">
                                    <strong><?= esc($booking['bride_name']) ?> & <?= esc($booking['groom_name']) ?></strong>
                                    <small class="text-muted d-block"><?= esc($booking['email']) ?></small>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-secondary"><?= esc($booking['campus_name']) ?></span>
                            </td>
                            <td data-order="<?= strtotime($booking['wedding_date']) ?>">
                                <strong><?= date('M j, Y', strtotime($booking['wedding_date'])) ?></strong>
                            </td>
                            <td><?= esc($booking['pastor_name'] ?? 'Not assigned') ?></td>
                            <td>
                                <span class="badge badge-success"><?= ucfirst($booking['status']) ?></span>
                            </td>
                            <td data-order="<?= strtotime($booking['created_at']) ?>">
                                <?= date('M j, Y', strtotime($booking['created_at'])) ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?= site_url('admin/booking/' . $booking['id']) ?>" 
                                       class="btn btn-sm btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= site_url('admin/booking/' . $booking['id'] . '/manage') ?>" 
                                       class="btn btn-sm btn-outline-info" title="Manage">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#bookingsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[6, 'desc']], // Order by applied date desc
        columnDefs: [
            { orderable: false, targets: [7] }, // Actions column not orderable
            { responsivePriority: 1, targets: [1, 3] }, // Couple names and wedding date priority
            { responsivePriority: 2, targets: [5, 7] } // Status and actions priority
        ],
        language: {
            search: "Search bookings:",
            lengthMenu: "Show _MENU_ bookings per page",
            info: "Showing _START_ to _END_ of _TOTAL_ bookings",
            infoEmpty: "No bookings found",
            infoFiltered: "(filtered from _MAX_ total bookings)",
            emptyTable: "No approved bookings found"
        }
    });
});

function applyFilters() {
    const params = new URLSearchParams();
    
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const campusId = document.getElementById('campus_id').value;
    
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);
    if (campusId) params.append('campus_id', campusId);
    
    const url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
    window.location.href = url;
}

function clearFilters() {
    window.location.href = window.location.pathname;
}
</script>
<?= $this->endSection() ?>

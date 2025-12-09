<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>

<?php
$pageActions = '
    <a href="' . site_url('admin/reports/export') . '?type=rejected&' . http_build_query($filters ?? []) . '" 
       class="btn btn-danger btn-sm" target="_blank">
        <i class="fas fa-download"></i> Export
    </a>
    <a href="' . site_url('admin/reports') . '" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
';
?>

<?= $this->include('admin_template/partials/page_header', [
    'title' => 'Rejected Bookings Report',
    'subtitle' => 'List of rejected booking applications',
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
        <h3 class="card-title">Rejected Bookings</h3>
        <div class="card-actions">
            <span class="badge badge-danger"><?= count($bookings ?? []) ?> Total</span>
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
                        <th>Status</th>
                        <th>Applied Date</th>
                        <th>Rejected Date</th>
                        <th>Rejection Reason</th>
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
                            <td>
                                <span class="badge badge-danger"><?= ucfirst($booking['status']) ?></span>
                            </td>
                            <td data-order="<?= strtotime($booking['created_at']) ?>">
                                <?= date('M j, Y', strtotime($booking['created_at'])) ?>
                            </td>
                            <td data-order="<?= strtotime($booking['updated_at']) ?>">
                                <strong><?= date('M j, Y', strtotime($booking['updated_at'])) ?></strong>
                            </td>
                            <td>
                                <?php if (!empty($booking['rejection_reason'])): ?>
                                    <span class="text-muted" style="font-size: 0.875rem;">
                                        <?= strlen($booking['rejection_reason']) > 50 ? 
                                            substr(esc($booking['rejection_reason']), 0, 50) . '...' : 
                                            esc($booking['rejection_reason']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">No reason provided</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?= site_url('admin/booking/' . $booking['id']) ?>" 
                                       class="btn btn-sm btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if (!empty($booking['rejection_reason'])): ?>
                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                onclick="showRejectionReason('<?= addslashes($booking['rejection_reason']) ?>')" 
                                                title="View Rejection Reason">
                                            <i class="fas fa-comment-alt"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Rejection Reason Modal -->
<div class="modal-overlay" id="reasonModal" style="display: none;">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fas fa-times-circle"></i> Rejection Reason</h3>
            <button type="button" class="modal-close" onclick="closeModal('reasonModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="rejectionReasonContent" style="padding: 1rem; background: #f8f9fa; border-radius: 6px; line-height: 1.6;">
                <!-- Reason content will be inserted here -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('reasonModal')">Close</button>
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
        order: [[6, 'desc']], // Order by rejected date desc
        columnDefs: [
            { orderable: false, targets: [8] }, // Actions column not orderable
            { responsivePriority: 1, targets: [1, 3] }, // Couple names and wedding date priority
            { responsivePriority: 2, targets: [4, 6, 8] } // Status, rejected date, and actions priority
        ],
        language: {
            search: "Search bookings:",
            lengthMenu: "Show _MENU_ bookings per page",
            info: "Showing _START_ to _END_ of _TOTAL_ bookings",
            infoEmpty: "No bookings found",
            infoFiltered: "(filtered from _MAX_ total bookings)",
            emptyTable: "No rejected bookings found"
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

function showRejectionReason(reason) {
    document.getElementById('rejectionReasonContent').textContent = reason;
    document.getElementById('reasonModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modals = document.querySelectorAll('.modal-overlay');
        modals.forEach(modal => {
            if (modal.style.display === 'flex') {
                closeModal(modal.id);
            }
        });
    }
});
</script>
<?= $this->endSection() ?>

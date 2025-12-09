<?= $this->extend('layouts/admin/admin') ?>

<?= $this->section('main_content') ?>
<div class="content-wrapper">
    <section id="rejected-report" class="content-section active">
        
        <!-- Filter Panel -->
        <div class="filter-panel" id="filterPanel">
            <div class="filter-container">
                <div class="filter-group">
                    <label for="start_date">From Date</label>
                    <input type="date" id="start_date" name="start_date" class="filter-input" 
                           value="<?= $filters['start_date'] ?? '' ?>">
                </div>
                
                <div class="filter-group">
                    <label for="end_date">To Date</label>
                    <input type="date" id="end_date" name="end_date" class="filter-input" 
                           value="<?= $filters['end_date'] ?? '' ?>">
                </div>
                
                <div class="filter-group">
                    <label for="campus_id">Campus</label>
                    <select id="campus_id" name="campus_id" class="filter-select">
                        <option value="">All Campuses</option>
                        <?php foreach ($campuses as $campus): ?>
                            <option value="<?= $campus['id'] ?>" 
                                    <?= ($filters['campus_id'] ?? '') == $campus['id'] ? 'selected' : '' ?>>
                                <?= esc($campus['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
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

        <div class="bookings-table-container">
            <div class="table-header">
                <h3>Rejected Bookings Report</h3>
                <div class="table-tools">
                    <div class="datatable-controls">
                        <span class="badge badge-danger badge-lg"><?= count($bookings) ?> Total</span>
                        <a href="<?= site_url('admin/reports/export') ?>?type=rejected&<?= http_build_query($filters) ?>" 
                           class="btn btn-danger btn-sm" target="_blank">
                            <i class="fas fa-download"></i> Export
                        </a>
                        <a href="<?= site_url('admin/reports') ?>" class="btn btn-info btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Reports
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table id="bookingsTable" class="admin-table display nowrap" style="width:100%">
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
                        <?php foreach ($bookings as $booking): ?>
                            <tr class="booking-row">
                                <td><strong>#<?= $booking['id'] ?></strong></td>
                                <td>
                                    <div class="couple-names">
                                        <strong><?= esc($booking['bride_name']) ?> & <?= esc($booking['groom_name']) ?></strong>
                                        <small class="email"><?= esc($booking['email']) ?></small>
                                    </div>
                                </td>
                                <td>
                                    <span class="campus-badge"><?= esc($booking['campus_name']) ?></span>
                                </td>
                                <td data-order="<?= strtotime($booking['wedding_date']) ?>">
                                    <div class="date-info">
                                        <strong><?= date('M j, Y', strtotime($booking['wedding_date'])) ?></strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-rejected"><?= ucfirst($booking['status']) ?></span>
                                </td>
                                <td data-order="<?= strtotime($booking['created_at']) ?>">
                                    <?= date('M j, Y', strtotime($booking['created_at'])) ?>
                                </td>
                                <td data-order="<?= strtotime($booking['updated_at']) ?>">
                                    <strong><?= date('M j, Y', strtotime($booking['updated_at'])) ?></strong>
                                </td>
                                <td>
                                    <div class="reason-info">
                                        <?php if (!empty($booking['rejection_reason'])): ?>
                                            <span class="reason-text" title="<?= esc($booking['rejection_reason']) ?>">
                                                <?= strlen($booking['rejection_reason']) > 50 ? 
                                                    substr(esc($booking['rejection_reason']), 0, 50) . '...' : 
                                                    esc($booking['rejection_reason']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">No reason provided</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= site_url('admin/booking/' . $booking['id']) ?>" 
                                           class="btn-action view" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if (!empty($booking['rejection_reason'])): ?>
                                            <button type="button" class="btn-action reason" 
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
    </section>
</div>

<!-- Rejection Reason Modal -->
<div class="admin-modal" id="reasonModal" style="display: none;">
    <div class="modal-overlay" onclick="closeModal('reasonModal')"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="fas fa-times-circle"></i> Rejection Reason</h3>
            <button type="button" class="modal-close" onclick="closeModal('reasonModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="reason-display" id="rejectionReasonContent">
                <!-- Reason content will be inserted here -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('reasonModal')">Close</button>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<?= $this->section('styles') ?>
<style>
/* Filter Panel Styles - Same as bookings page */
.filter-panel {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.filter-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 20px;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.filter-group label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0;
}

.filter-select,
.filter-input {
    padding: 8px 12px;
    border: 1px solid #ced4da;
    border-radius: 6px;
    font-size: 0.9rem;
    background: white;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.filter-select:focus,
.filter-input:focus {
    outline: none;
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.filter-actions {
    display: flex;
    gap: 8px;
    align-items: center;
}

.filter-actions .btn {
    padding: 8px 16px;
    font-size: 0.85rem;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 5px;
    text-decoration: none;
}

.filter-actions .btn-primary {
    background: #007bff;
    color: white;
}

.filter-actions .btn-primary:hover {
    background: #0056b3;
}

.filter-actions .btn-secondary {
    background: #6c757d;
    color: white;
}

.filter-actions .btn-secondary:hover {
    background: #545b62;
}

/* Select2 Customization */
.select2-container--default .select2-selection--single {
    height: 38px;
    border: 1px solid #ced4da;
    border-radius: 6px;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
    padding-left: 12px;
    font-size: 0.9rem;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
    right: 10px;
}

.select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.select2-dropdown {
    border: 1px solid #ced4da;
    border-radius: 6px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.select2-container {
    width: 100% !important;
}

.bookings-table-container {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #dee2e6;
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    border-radius: 8px 8px 0 0;
}

.table-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.table-tools {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.datatable-controls {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    font-size: 0.9rem;
}

.admin-table thead th {
    background: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    padding: 1rem 0.75rem;
    font-weight: 600;
    color: #495057;
    text-align: left;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.admin-table tbody td {
    padding: 1rem 0.75rem;
    border-bottom: 1px solid #dee2e6;
    vertical-align: middle;
}

.admin-table tbody tr:hover {
    background-color: #f8f9fa;
}

.couple-names {
    line-height: 1.4;
}

.couple-names strong {
    display: block;
    color: #495057;
}

.couple-names .email {
    color: #6c757d;
    font-size: 0.8rem;
}

.campus-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #e9ecef;
    color: #495057;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
}

.date-info strong {
    color: #495057;
}

.status-badge {
    display: inline-block;
    padding: 0.35rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-rejected {
    background: #f8d7da;
    color: #721c24;
}

.reason-info {
    max-width: 200px;
}

.reason-text {
    display: block;
    font-size: 0.85rem;
    color: #495057;
    cursor: pointer;
    text-decoration: underline;
    text-decoration-style: dotted;
}

.reason-text:hover {
    color: #007bff;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.2s ease;
    font-size: 0.85rem;
    border: none;
    cursor: pointer;
}

.btn-action.view {
    background: #e3f2fd;
    color: #1976d2;
}

.btn-action.view:hover {
    background: #bbdefb;
    color: #1565c0;
}

.btn-action.reason {
    background: #fff3e0;
    color: #f57c00;
}

.btn-action.reason:hover {
    background: #ffe0b2;
    color: #ef6c00;
}

.badge-lg {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
}

/* Modal Styles */
.admin-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.modal-container {
    background: white;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    max-height: 80vh;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.modal-header {
    background: #f8f9fa;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: #495057;
    font-size: 1.1rem;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.2rem;
    color: #6c757d;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
}

.modal-close:hover {
    background: #e9ecef;
    color: #495057;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    background: #f8f9fa;
    padding: 1rem 1.5rem;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

.reason-display {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 1rem;
    font-size: 0.9rem;
    line-height: 1.5;
    color: #495057;
    min-height: 60px;
}

@media (max-width: 768px) {
    .filter-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .filter-panel {
        padding: 15px;
    }
    
    .filter-actions {
        justify-content: center;
        grid-column: 1 / -1;
    }
    
    .filter-actions .btn {
        flex: 1;
        justify-content: center;
    }
    
    .table-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .datatable-controls {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .admin-table {
        font-size: 0.8rem;
    }
    
    .modal-container {
        width: 95%;
        margin: 1rem;
    }
    
    .reason-info {
        max-width: 150px;
    }
}

@media (max-width: 576px) {
    .filter-container {
        grid-template-columns: 1fr;
        gap: 15px;
    }
}
</style>
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
            emptyTable: "No rejected bookings found",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });

    // Initialize Select2 for campus filter
    $('#campus_id').select2({
        placeholder: 'Select campus...',
        allowClear: true,
        width: '100%'
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
    showModal('reasonModal');
}

function showModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = 'auto';
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
</script>

<!-- Include Select2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<?= $this->endSection() ?>

<?= $this->extend('layouts/admin/admin') ?>

<?= $this->section('main_content') ?>
<div class="content-wrapper">
    <section id="pending-report" class="content-section active">
        
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
                <h3>Pending Bookings Report</h3>
                <div class="table-tools">
                    <div class="datatable-controls">
                        <span class="badge badge-warning badge-lg"><?= count($bookings) ?> Total</span>
                        <a href="<?= site_url('admin/reports/export') ?>?type=pending&<?= http_build_query($filters) ?>" 
                           class="btn btn-warning btn-sm" target="_blank">
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
                            <th>Days Pending</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <?php
                            $daysPending = floor((time() - strtotime($booking['created_at'])) / (60 * 60 * 24));
                            $urgency = $daysPending > 7 ? 'high' : ($daysPending > 3 ? 'medium' : 'low');
                            ?>
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
                                    <span class="status-badge status-pending"><?= ucfirst($booking['status']) ?></span>
                                </td>
                                <td data-order="<?= strtotime($booking['created_at']) ?>">
                                    <?= date('M j, Y', strtotime($booking['created_at'])) ?>
                                </td>
                                <td>
                                    <span class="urgency-badge urgency-<?= $urgency ?>"><?= $daysPending ?> days</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= site_url('admin/booking/' . $booking['id']) ?>" 
                                           class="btn-action view" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= site_url('admin/booking/' . $booking['id'] . '/manage') ?>" 
                                           class="btn-action manage" title="Manage">
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
    </section>
</div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.filter-panel {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.filter-container {
    padding: 1.5rem;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) auto;
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.filter-input,
.filter-select {
    padding: 0.5rem 0.75rem;
    border: 1px solid #ced4da;
    border-radius: 4px;
    font-size: 0.9rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.filter-input:focus,
.filter-select:focus {
    border-color: #007bff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
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
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
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

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.urgency-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.urgency-low {
    background: #d4edda;
    color: #155724;
}

.urgency-medium {
    background: #fff3cd;
    color: #856404;
}

.urgency-high {
    background: #f8d7da;
    color: #721c24;
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
}

.btn-action.view {
    background: #e3f2fd;
    color: #1976d2;
}

.btn-action.view:hover {
    background: #bbdefb;
    color: #1565c0;
}

.btn-action.manage {
    background: #fff3e0;
    color: #f57c00;
}

.btn-action.manage:hover {
    background: #ffe0b2;
    color: #ef6c00;
}

.badge-lg {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
}

@media (max-width: 768px) {
    .filter-container {
        grid-template-columns: 1fr;
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
        order: [[5, 'desc']], // Order by applied date desc
        columnDefs: [
            { orderable: false, targets: [7] }, // Actions column not orderable
            { responsivePriority: 1, targets: [1, 3] }, // Couple names and wedding date priority
            { responsivePriority: 2, targets: [4, 6, 7] } // Status, days pending, and actions priority
        ],
        language: {
            search: "Search bookings:",
            lengthMenu: "Show _MENU_ bookings per page",
            info: "Showing _START_ to _END_ of _TOTAL_ bookings",
            infoEmpty: "No bookings found",
            infoFiltered: "(filtered from _MAX_ total bookings)",
            emptyTable: "No pending bookings found",
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
</script>
<?= $this->endSection() ?>

<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>
<?php
$pageActions = '
    <button class="btn btn-success btn-sm" onclick="exportBookings()">
        <i class="fas fa-download"></i> Export
    </button>
    <button class="btn btn-info btn-sm" onclick="refreshTable()">
        <i class="fas fa-sync-alt"></i> Refresh
    </button>
';
?>
<?= $this->include('admin_template/partials/page_header', [
    'title' => 'All Wedding Bookings',
    'subtitle' => 'Manage and approve wedding booking requests',
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
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>
    
    <div class="form-group">
        <label class="form-label">Campus</label>
        <select id="campusFilter" class="form-control">
            <option value="">All Campuses</option>
            <?php if (!empty($campuses)): ?>
                <?php foreach ($campuses as $campus): ?>
                    <option value="<?= esc($campus['name']) ?>"><?= esc($campus['name']) ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
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
        <h3 class="card-title">All Wedding Bookings</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info" style="margin-bottom: 15px;">
            <i class="fas fa-info-circle"></i> 
            <strong>Table View:</strong> All columns are always visible. Scroll horizontally if needed to see all data.
        </div>
        
        <div class="table-wrapper">
            <table id="bookingsDataTable" class="data-table" style="width:100%; min-width: 1000px;">
                <thead>
                    <tr>
                        <th>SNO</th>
                        <th>Couple Names</th>
                        <th>Campus</th>
                        <th>Wedding Date</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bookings)): ?>
                        <?php 
                            $ct = 1;
                            foreach ($bookings as $booking):
                        ?>
                        <tr class="booking-row">
                            <td><strong><?= $ct ?></strong></td>
                            <td>
                                <div class="couple-names">
                                    <strong><?= esc($booking['bride_name'] ?? '') ?> & <?= esc($booking['groom_name'] ?? '') ?></strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-secondary"><?= esc($booking['campus_name'] ?? 'Unknown') ?></span>
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
                            <td>
                                <?php 
                                $paymentStatus = $booking['payment_status'] ?? [];
                                $paymentPercentage = $paymentStatus['payment_percentage'] ?? 0;
                                $isComplete = $paymentStatus['is_complete'] ?? false;
                                ?>
                                <div class="payment-status">
                                    <?php if ($isComplete): ?>
                                        <span class="badge badge-success">Paid</span>
                                    <?php elseif ($paymentPercentage > 0): ?>
                                        <span class="badge badge-warning"><?= $paymentPercentage ?>%</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Unpaid</span>
                                    <?php endif; ?>
                                    <small class="d-block mt-1 text-muted">
                                        UGX <?= number_format($paymentStatus['total_paid'] ?? 0, 0) ?>
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div class="contact-info">
                                    <small><?= esc($booking['bride_phone'] ?? $booking['groom_phone'] ?? '') ?></small>
                                    <small class="text-muted d-block"><?= esc($booking['bride_email'] ?? $booking['groom_email'] ?? '') ?></small>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons" style="display: flex; gap: 6px; align-items: center; flex-wrap: nowrap;">
                                    <!-- View Button -->
                                    <a href="<?= site_url('admin/booking/' . $booking['id']) ?>" 
                                       class="btn-action view" 
                                       title="View Details"
                                       style="width: 32px; height: 32px; border: none; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; text-decoration: none; font-size: 0.85rem; flex-shrink: 0; background: linear-gradient(135deg, #17a2b8, #138496); color: white;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <?php if ($booking['status'] === 'pending'): ?>
                                        <?php
                                        $paymentComplete = $booking['payment_status']['is_complete'] ?? false;
                                        $remainingBalance = $booking['payment_status']['remaining_balance'] ?? $booking['payment_status']['pending_amount'] ?? 0;
                                        ?>
                                        
                                        <!-- Approve Button -->
                                        <?php if ($paymentComplete): ?>
                                            <button type="button" 
                                                    class="btn-action approve" 
                                                    onclick="approveBooking(<?= $booking['id'] ?>)" 
                                                    title="Approve Booking"
                                                    style="width: 32px; height: 32px; border: none; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; text-decoration: none; font-size: 0.85rem; flex-shrink: 0; background: linear-gradient(135deg, #28a745, #20c997); color: white;">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        <?php else: ?>
                                            <button type="button" 
                                                    class="btn-action approve disabled" 
                                                    onclick="showPaymentRequiredAlert(<?= $booking['id'] ?>, <?= $remainingBalance ?>)" 
                                                    title="Payment Required"
                                                    style="width: 32px; height: 32px; border: none; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; cursor: not-allowed; text-decoration: none; font-size: 0.85rem; flex-shrink: 0; background: #6c757d; color: white; opacity: 0.6;">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        <?php endif; ?>
                                        
                                        <!-- Reject Button -->
                                        <button type="button" 
                                                class="btn-action reject" 
                                                onclick="rejectBooking(<?= $booking['id'] ?>)" 
                                                title="Reject"
                                                style="width: 32px; height: 32px; border: none; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; text-decoration: none; font-size: 0.85rem; flex-shrink: 0; background: linear-gradient(135deg, #dc3545, #c82333); color: white;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    <?php endif; ?>
                                    
                                    <?php if ($booking['status'] === 'approved'): ?>
                                        <!-- Manage Button -->
                                        <a href="<?= site_url('admin/booking/' . $booking['id'] . '/manage') ?>" 
                                           class="btn-action manage" 
                                           title="Manage Counseling & Admin"
                                           style="width: 32px; height: 32px; border: none; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; text-decoration: none; font-size: 0.85rem; flex-shrink: 0; background: linear-gradient(135deg, #64017f, #8b1fa9); color: white;">
                                            <i class="fas fa-cogs"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if (in_array($booking['status'], ['pending', 'approved'])): ?>
                                        <!-- Cancel Button -->
                                        <button type="button" 
                                                class="btn-action cancel" 
                                                onclick="cancelBooking(<?= $booking['id'] ?>)" 
                                                title="Cancel"
                                                style="width: 32px; height: 32px; border: none; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; text-decoration: none; font-size: 0.85rem; flex-shrink: 0; background: linear-gradient(135deg, #ffc107, #fd7e14); color: #212529;">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            $ct++;
                            endforeach;
                        ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modals -->
<div class="modal-overlay" id="rejectModal" style="display: none;">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fas fa-times-circle"></i> Reject Booking</h3>
            <button class="modal-close" onclick="closeModal('rejectModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="rejectForm" method="POST">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Reason for Rejection:</label>
                    <textarea class="form-control" id="rejection_reason" name="reason" rows="4" required placeholder="Please provide a clear reason for rejecting this booking request..."></textarea>
                    <small class="form-help">This reason will be communicated to the couple.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('rejectModal')">Cancel</button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-times"></i> Reject Booking
                </button>
            </div>
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        </form>
    </div>
</div>

<div class="modal-overlay" id="cancelModal" style="display: none;">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fas fa-ban"></i> Cancel Booking</h3>
            <button class="modal-close" onclick="closeModal('cancelModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="cancelForm" method="POST">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Reason for Cancellation:</label>
                    <textarea class="form-control" id="cancellation_reason" name="reason" rows="4" required placeholder="Please provide a reason for cancelling this booking..."></textarea>
                    <small class="form-help">This reason will be recorded for administrative purposes.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('cancelModal')">Cancel</button>
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-ban"></i> Cancel Booking
                </button>
            </div>
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

.payment-status {
    text-align: center;
}

.payment-status .badge {
    font-size: 0.75rem;
    padding: 0.25em 0.5em;
}

.payment-status small {
    font-size: 0.7rem;
    line-height: 1.2;
}

.couple-names {
    max-width: 180px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.date-info {
    line-height: 1.3;
}

.contact-info {
    line-height: 1.2;
    font-size: 0.75rem;
}

.btn-action.disabled {
    opacity: 0.6;
    cursor: not-allowed;
    background-color: #6c757d !important;
}

.action-buttons {
    display: flex !important;
    gap: 6px;
    align-items: center;
    min-width: 100px;
}

.btn-action {
    width: 32px !important;
    height: 32px !important;
    border: none !important;
    border-radius: 6px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
    transition: all 0.2s ease;
    text-decoration: none !important;
    font-size: 0.85rem !important;
    flex-shrink: 0;
}

.btn-action.view {
    background: linear-gradient(135deg, #17a2b8, #138496) !important;
    color: white !important;
}

.btn-action.approve {
    background: linear-gradient(135deg, #28a745, #20c997) !important;
    color: white !important;
}

.btn-action.reject {
    background: linear-gradient(135deg, #dc3545, #c82333) !important;
    color: white !important;
}

.btn-action.cancel {
    background: linear-gradient(135deg, #ffc107, #fd7e14) !important;
    color: #212529 !important;
}

.btn-action.manage {
    background: linear-gradient(135deg, #64017f, #8b1fa9) !important;
    color: white !important;
}

.btn-action:hover:not(.disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.select2-container {
    width: 100% !important;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
function approveBooking(id) {
    if (confirm('Are you sure you want to approve this booking?')) {
        window.location.href = `<?= site_url('admin/booking/') ?>${id}/approve`;
    }
}

function showPaymentRequiredAlert(bookingId, pendingAmount) {
    alert(`Payment Required!\n\nBooking cannot be approved until payment is complete.\n\nPlease process the payment before approving this booking.`);
}

function rejectBooking(id) {
    document.getElementById('rejectForm').action = `<?= site_url('admin/booking/') ?>${id}/reject`;
    showModal('rejectModal');
}

function cancelBooking(id) {
    document.getElementById('cancelForm').action = `<?= site_url('admin/booking/') ?>${id}/cancel`;
    showModal('cancelModal');
}

function exportBookings() {
    const table = $('#bookingsDataTable').DataTable();
    const rows = table.rows({ filter: 'applied' }).data();
    
    let csvContent = 'ID,Couple Names,Campus,Wedding Date,Status,Contact\n';
    
    rows.each(function(row, index) {
        const cells = table.row(index).nodes().to$().find('td');
        const csvRow = [
            $(cells[0]).text().trim(),
            $(cells[1]).text().trim(),
            $(cells[2]).text().trim(),
            $(cells[3]).text().trim(),
            $(cells[4]).text().trim(),
            $(cells[5]).text().trim()
        ];
        
        const escapedRow = csvRow.map(cell => '"' + cell.replace(/"/g, '""') + '"');
        csvContent += escapedRow.join(',') + '\n';
    });
    
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'wedding_bookings_' + new Date().toISOString().slice(0,10) + '.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function refreshTable() {
    location.reload();
}

function applyFilters() {
    const status = $('#statusFilter').val();
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const campus = $('#campusFilter').val();
    
    const table = $('#bookingsDataTable').DataTable();
    
    if (status) {
        table.column(4).search(status, true, false);
    } else {
        table.column(4).search('');
    }
    
    if (campus) {
        table.column(2).search(campus, true, false);
    } else {
        table.column(2).search('');
    }
    
    $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(function(fn) {
        return fn.toString().indexOf('bookingsDataTable') === -1;
    });
    
    if (startDate || endDate) {
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            if (settings.nTable.id !== 'bookingsDataTable') {
                return true;
            }
            
            const weddingDateStr = data[3];
            if (!startDate && !endDate) {
                return true;
            }
            
            const dateMatch = weddingDateStr.match(/(\w{3} \d{1,2}, \d{4})/);
            if (!dateMatch) {
                return true;
            }
            
            const weddingDate = new Date(dateMatch[1]);
            const start = startDate ? new Date(startDate) : null;
            const end = endDate ? new Date(endDate) : null;
            
            if (start && weddingDate < start) {
                return false;
            }
            if (end && weddingDate > end) {
                return false;
            }
            
            return true;
        });
    }
    
    table.draw();
}

function clearFilters() {
    $('#statusFilter').val('').trigger('change');
    $('#campusFilter').val('').trigger('change');
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    
    const table = $('#bookingsDataTable').DataTable();
    table.columns().search('').draw();
    
    $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(function(fn) {
        return fn.toString().indexOf('bookingsDataTable') === -1;
    });
    
    table.draw();
}

$(document).ready(function() {
    $('#statusFilter, #campusFilter').select2({
        theme: 'default',
        minimumResultsForSearch: Infinity,
        placeholder: function() {
            return $(this).find('option:first').text();
        }
    });
    
    $('#statusFilter, #campusFilter').on('change', function() {
        applyFilters();
    });
    
    $('#startDate, #endDate').on('change', function() {
        applyFilters();
    });
    
    $('#bookingsDataTable').DataTable({
        responsive: false,
        scrollX: true,
        autoWidth: false,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        order: [[0, 'asc']],
        columnDefs: [
            { targets: [7], orderable: false, searchable: false, width: '120px' },
            { targets: [0], width: '60px' },
            { targets: [1], width: '200px' },
            { targets: [2], width: '120px' },
            { targets: [3], type: 'num', width: '140px' },
            { targets: [4], width: '100px' },
            { targets: [5], width: '100px' },
            { targets: [6], width: '150px' }
        ],
        language: {
            search: "Search bookings:",
            lengthMenu: "Show _MENU_ bookings per page",
            info: "Showing _START_ to _END_ of _TOTAL_ bookings",
            infoEmpty: "No bookings found",
            infoFiltered: "(filtered from _MAX_ total bookings)",
            emptyTable: "No wedding bookings have been submitted yet",
            zeroRecords: "No matching bookings found"
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
    });
});
</script>
<?= $this->endSection() ?>


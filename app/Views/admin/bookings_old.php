<?= $this->extend('layouts/admin/admin') ?>

<?= $this->section('main_content') ?>
<div class="content-wrapper">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="title-section">
                <h2>All Wedding Bookings</h2>
                <p>Manage and approve wedding booking requests</p>
            </div>
            <div class="quick-actions">
                <button class="btn btn-success btn-sm" onclick="exportBookings()">
                    <i class="fas fa-download"></i> Export
                </button>
                <button class="btn btn-info btn-sm" onclick="refreshTable()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Panel -->
    <div class="filter-panel" id="filterPanel">
        <div class="filter-container">
            <div class="filter-group">
                <label for="statusFilter">Status</label>
                <select id="statusFilter" class="filter-select">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="campusFilter">Campus</label>
                <select id="campusFilter" class="filter-select">
                    <option value="">All Campuses</option>
                    <?php if (!empty($campuses)): ?>
                        <?php foreach ($campuses as $campus): ?>
                            <option value="<?= esc($campus['name']) ?>"><?= esc($campus['name']) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="startDate">From Date</label>
                <input type="date" id="startDate" class="filter-input" placeholder="Start date">
            </div>
            
            <div class="filter-group">
                <label for="endDate">To Date</label>
                <input type="date" id="endDate" class="filter-input" placeholder="End date">
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
       
    <!-- Bookings Table -->
    <div class="bookings-table-container">
        <div class="table-header">
            <h3>All Wedding Bookings</h3>
        </div>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> 
            <strong>Table View:</strong> All columns are always visible. Scroll horizontally if needed to see all data.
        </div>
        
        <div class="table-responsive">
            <table id="bookingsDataTable" class="admin-table display nowrap" style="width:100%; min-width: 1000px;">
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
                                <td>
                                    <strong><?= $ct ?></strong>
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
                                    $statusClass = '';
                                    switch($booking['status']) {
                                        case 'pending':
                                            $statusClass = 'status-pending';
                                            break;
                                        case 'approved':
                                            $statusClass = 'status-approved';
                                            break;
                                        case 'rejected':
                                            $statusClass = 'status-rejected';
                                            break;
                                        case 'completed':
                                            $statusClass = 'status-completed';
                                            break;
                                        case 'cancelled':
                                            $statusClass = 'status-cancelled';
                                            break;
                                        default:
                                            $statusClass = 'status-unknown';
                                    }
                                    ?>
                                    <span class="status-badge <?= $statusClass ?>"><?= ucfirst($booking['status'] ?? 'unknown') ?></span>
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
                                        <small class="email"><?= esc($booking['bride_email'] ?? $booking['groom_email'] ?? '') ?></small>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= site_url('admin/booking/' . $booking['id']) ?>" class="btn-action view" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <?php if ($booking['status'] === 'pending'): ?>
                                            <?php 
                                            $paymentComplete = $booking['payment_status']['is_complete'] ?? false;
                                            $pendingAmount = $booking['payment_status']['pending_amount'] ?? 0;
                                            ?>
                                            <?php if ($paymentComplete): ?>
                                                <button type="button" class="btn-action approve" onclick="approveBooking(<?= $booking['id'] ?>)" title="Approve Booking">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn-action approve disabled" 
                                                        title="Payment Required - Balance: UGX <?= number_format($pendingAmount, 0) ?>" 
                                                        onclick="showPaymentRequiredAlert(<?= $booking['id'] ?>, <?= $pendingAmount ?>)">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            <?php endif; ?>
                                            <button type="button" class="btn-action reject" onclick="rejectBooking(<?= $booking['id'] ?>)" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        <?php endif; ?>
                                        
                                        <?php if ($booking['status'] === 'approved'): ?>
                                            <a href="<?= site_url('admin/booking/' . $booking['id'] . '/manage') ?>" class="btn-action manage" title="Manage Counseling & Admin">
                                                <i class="fas fa-cogs"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (in_array($booking['status'], ['pending', 'approved'])): ?>
                                            <button type="button" class="btn-action cancel" onclick="cancelBooking(<?= $booking['id'] ?>)" title="Cancel">
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

<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
/* Filter Panel Styles */

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
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(37, 128, 45, 0.25);
}

.filter-actions {
    display: flex;
    gap: 8px;
    align-items: center;
}

.filter-actions {
    display: flex;
    gap: 8px;
    align-items: center;
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
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(37, 128, 45, 0.25);
}

.select2-dropdown {
    border: 1px solid #ced4da;
    border-radius: 6px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.select2-container {
    width: 100% !important;
}

/* Payment validation styles */
.btn-action.disabled {
    opacity: 0.6;
    cursor: not-allowed;
    background-color: var(--gray) !important;
    border-color: var(--gray) !important;
}

.btn-action.disabled:hover {
    background-color: var(--dark-gray) !important;
    border-color: var(--dark-gray) !important;
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

/* DataTable styling for better visibility */
#bookingsDataTable {
    font-size: 0.85rem;
}

#bookingsDataTable td {
    white-space: nowrap;
    vertical-align: middle;
    padding: 8px 6px;
}

#bookingsDataTable th {
    white-space: nowrap;
    font-size: 0.8rem;
    font-weight: 600;
    padding: 10px 6px;
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

.contact-info .email {
    display: block;
    color: var(--gray);
    margin-top: 2px;
}

.action-buttons {
    display: flex;
    gap: 4px;
    justify-content: center;
    flex-wrap: nowrap;
}

.btn-action {
    min-width: 28px;
    height: 28px;
    padding: 4px 6px;
    font-size: 0.75rem;
    border: none;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-action.view {
    background: linear-gradient(135deg, #17a2b8, #138496);
    color: white;
}

.btn-action.approve {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
}

.btn-action.reject {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
}

.btn-action.cancel {
    background: linear-gradient(135deg, #ffc107, #fd7e14);
    color: #212529;
}

.btn-action.manage {
    background: linear-gradient(135deg, #6c757d, #5a6268);
    color: white;
}

.btn-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.campus-badge {
    display: inline-block;
    font-size: 0.75rem;
    padding: 2px 6px;
    background: var(--light-gray);
    border-radius: 3px;
    max-width: 100px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-info {
    background: #d1ecf1;
    border: 1px solid #bee5eb;
    color: #0c5460;
}

/* Responsive Design */
@media (max-width: 992px) {
    .filter-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
}

@media (max-width: 576px) {
    .filter-container {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .filter-panel {
        padding: 15px;
    }
    
    .filter-actions {
        justify-content: center;
    }
    
}
</style>

<!-- Modal for Rejection Reason -->
<div class="admin-modal" id="rejectModal" style="display: none;">
    <div class="modal-overlay" onclick="closeModal('rejectModal')"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="fas fa-times-circle"></i> Reject Booking</h3>
            <button type="button" class="modal-close" onclick="closeModal('rejectModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="rejectForm" method="POST">
            <div class="modal-body">
                <div class="form-group">
                    <label for="rejection_reason">Reason for Rejection:</label>
                    <textarea class="form-input" id="rejection_reason" name="reason" rows="4" required placeholder="Please provide a clear reason for rejecting this booking request..."></textarea>
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

<!-- Modal for Cancellation Reason -->
<div class="admin-modal" id="cancelModal" style="display: none;">
    <div class="modal-overlay" onclick="closeModal('cancelModal')"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="fas fa-ban"></i> Cancel Booking</h3>
            <button type="button" class="modal-close" onclick="closeModal('cancelModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="cancelForm" method="POST">
            <div class="modal-body">
                <div class="form-group">
                    <label for="cancellation_reason">Reason for Cancellation:</label>
                    <textarea class="form-input" id="cancellation_reason" name="reason" rows="4" required placeholder="Please provide a reason for cancelling this booking..."></textarea>
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

<script>
function toggleFilter() {
    const panel = document.getElementById('filterPanel');
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
}

function approveBooking(id) {
    if (confirm('Are you sure you want to approve this booking?')) {
        window.location.href = `<?= site_url('admin/booking/') ?>${id}/approve`;
    }
}

function showPaymentRequiredAlert(bookingId, pendingAmount) {
    alert(`Payment Required!\n\nBooking cannot be approved until payment is complete.\n\nRemaining Balance: UGX ${pendingAmount.toLocaleString()}\n\nPlease process the payment before approving this booking.`);
}

function rejectBooking(id) {
    document.getElementById('rejectForm').action = `<?= site_url('admin/booking/') ?>${id}/reject`;
    showModal('rejectModal');
}

function cancelBooking(id) {
    document.getElementById('cancelForm').action = `<?= site_url('admin/booking/') ?>${id}/cancel`;
    showModal('cancelModal');
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

function exportBookings() {
    // Simple CSV export without DataTable buttons dependency
    const table = $('#bookingsDataTable').DataTable();
    const rows = table.rows({ filter: 'applied' }).data();
    
    // Create CSV content
    let csvContent = 'ID,Couple Names,Campus,Wedding Date,Status,Contact\n';
    
    // Add data rows
    rows.each(function(row, index) {
        const cells = table.row(index).nodes().to$().find('td');
        const csvRow = [
            $(cells[0]).text().trim(), // ID
            $(cells[1]).text().trim(), // Couple Names
            $(cells[2]).text().trim(), // Campus
            $(cells[3]).text().trim(), // Wedding Date
            $(cells[4]).text().trim(), // Status
            $(cells[5]).text().trim()  // Contact
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
    link.setAttribute('download', 'wedding_bookings_' + new Date().toISOString().slice(0,10) + '.csv');
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
    const status = $('#statusFilter').val();
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const campus = $('#campusFilter').val();
    
    const table = $('#bookingsDataTable').DataTable();
    
    // Apply status filter
    if (status) {
        table.column(4).search(status, true, false);
    } else {
        table.column(4).search('');
    }
    
    // Apply campus filter
    if (campus) {
        table.column(2).search(campus, true, false);
    } else {
        table.column(2).search('');
    }
    
    // Clear any existing date filters
    $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(function(fn) {
        return fn.toString().indexOf('bookingsDataTable') === -1;
    });
    
    // For date range filtering, add custom filter
    if (startDate || endDate) {
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            if (settings.nTable.id !== 'bookingsDataTable') {
                return true;
            }
            
            const weddingDateStr = data[3]; // Wedding date column
            if (!startDate && !endDate) {
                return true;
            }
            
            // Extract date from the formatted string
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
    
    // Visual feedback
    const applyBtn = document.querySelector('.filter-actions .btn-primary');
    if (applyBtn) {
        const originalText = applyBtn.innerHTML;
        applyBtn.innerHTML = '<i class="fas fa-check"></i> Applied';
        applyBtn.style.background = '#28a745';
        
        setTimeout(() => {
            applyBtn.innerHTML = originalText;
            applyBtn.style.background = '';
        }, 1500);
    }
}

function clearFilters() {
    $('#statusFilter').val('').trigger('change');
    $('#campusFilter').val('').trigger('change');
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    
    const table = $('#bookingsDataTable').DataTable();
    
    // Clear all column searches
    table.columns().search('').draw();
    
    // Clear custom date filters
    $.fn.dataTable.ext.search = $.fn.dataTable.ext.search.filter(function(fn) {
        return fn.toString().indexOf('bookingsDataTable') === -1;
    });
    
    table.draw();
    
    // Visual feedback
    const clearBtn = document.querySelector('.filter-actions .btn-secondary');
    if (clearBtn) {
        const originalText = clearBtn.innerHTML;
        clearBtn.innerHTML = '<i class="fas fa-check"></i> Cleared';
        
        setTimeout(() => {
            clearBtn.innerHTML = originalText;
        }, 1000);
    }
}

// Initialize DataTable
$(document).ready(function() {
    // Initialize Select2
    $('#statusFilter, #campusFilter').select2({
        theme: 'default',
        minimumResultsForSearch: Infinity, // Disable search for these simple dropdowns
        placeholder: function() {
            return $(this).find('option:first').text();
        }
    });
    
    // Auto-apply filters on change
    $('#statusFilter, #campusFilter').on('change', function() {
        applyFilters();
    });
    
    // Auto-apply filters on date change
    $('#startDate, #endDate').on('change', function() {
        applyFilters();
    });
    
    $('#bookingsDataTable').DataTable({
        responsive: false, // Disable responsive collapsing
        scrollX: true, // Enable horizontal scrolling
        autoWidth: false, // Disable auto width calculation
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        order: [[0, 'asc']], // Sort by ID ascending
        columnDefs: [
            {
                targets: [7], // Actions column
                orderable: false,
                searchable: false,
                width: '120px' // Fixed width for actions
            },
            {
                targets: [0], // ID column
                width: '60px'
            },
            {
                targets: [1], // Couple Names column
                width: '200px'
            },
            {
                targets: [2], // Campus column
                width: '120px'
            },
            {
                targets: [3], // Date columns
                type: 'num', // Use data-order attributes for sorting
                width: '140px'
            },
            {
                targets: [4], // Status column
                width: '100px'
            },
            {
                targets: [5], // Payment column
                width: '100px'
            },
            {
                targets: [6], // Contact column
                width: '150px'
            }
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?= $this->endSection() ?>

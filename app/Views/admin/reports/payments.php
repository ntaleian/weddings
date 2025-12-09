<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>

<?php
$pageActions = '
    <a href="' . site_url('admin/reports') . '" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
';
?>

<?= $this->include('admin_template/partials/page_header', [
    'title' => 'Payments Report',
    'subtitle' => 'Track and manage all payment records',
    'actions' => $pageActions
]) ?>

<!-- Summary Cards -->
<div class="payment-summary-cards mb-3">
    <div class="card">
        <div class="card-body text-center">
            <div class="summary-value" style="font-size: 1.75rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">
                <?= $summary['total_payments'] ?? 0 ?>
            </div>
            <div class="summary-label" style="color: var(--text-tertiary); font-size: 0.875rem;">Total Payments</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center" style="border-left: 4px solid #28a745;">
            <div class="summary-value" style="font-size: 1.75rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">
                UGX <?= number_format($summary['total_completed'] ?? 0) ?>
            </div>
            <div class="summary-label" style="color: var(--text-tertiary); font-size: 0.875rem;">Completed</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center" style="border-left: 4px solid #ffc107;">
            <div class="summary-value" style="font-size: 1.75rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">
                UGX <?= number_format($summary['total_pending'] ?? 0) ?>
            </div>
            <div class="summary-label" style="color: var(--text-tertiary); font-size: 0.875rem;">Pending</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center" style="border-left: 4px solid #dc3545;">
            <div class="summary-value" style="font-size: 1.75rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">
                UGX <?= number_format($summary['total_failed'] ?? 0) ?>
            </div>
            <div class="summary-label" style="color: var(--text-tertiary); font-size: 0.875rem;">Failed</div>
        </div>
    </div>
</div>

<!-- Filters Panel -->
<?php
ob_start();
?>
<div class="filter-row">
    <div class="form-group">
        <label class="form-label">Campus</label>
        <select name="campus_id" id="campus_id" class="form-control">
            <option value="">All Campuses</option>
            <?php foreach ($campuses ?? [] as $campus): ?>
                <option value="<?= $campus['id'] ?>" <?= ($filters['campus_id'] ?? '') == $campus['id'] ? 'selected' : '' ?>>
                    <?= esc($campus['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Payment Status</label>
        <select name="status" id="status" class="form-control">
            <option value="">All Statuses</option>
            <option value="pending" <?= ($filters['status'] ?? '') == 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="completed" <?= ($filters['status'] ?? '') == 'completed' ? 'selected' : '' ?>>Completed</option>
            <option value="failed" <?= ($filters['status'] ?? '') == 'failed' ? 'selected' : '' ?>>Failed</option>
            <option value="refunded" <?= ($filters['status'] ?? '') == 'refunded' ? 'selected' : '' ?>>Refunded</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">From Date</label>
        <input type="date" name="from_date" id="from_date" class="form-control" value="<?= $filters['from_date'] ?? '' ?>">
    </div>
    <div class="form-group">
        <label class="form-label">To Date</label>
        <input type="date" name="to_date" id="to_date" class="form-control" value="<?= $filters['to_date'] ?? '' ?>">
    </div>
    <div class="form-group">
        <label class="form-label">&nbsp;</label>
        <div class="filter-actions">
            <button type="submit" class="btn btn-primary btn-sm" form="filterForm">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="<?= current_url() ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-times"></i> Clear
            </a>
        </div>
    </div>
</div>
<?php
$filterContent = ob_get_clean();
?>
<form method="GET" action="<?= current_url() ?>" id="filterForm">
    <?= $this->include('admin_template/partials/filter_panel', ['content' => $filterContent]) ?>
</form>

<!-- Payments Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Payments List</h3>
    </div>
    <div class="card-body">
        <?php if (!empty($payments)): ?>
            <div class="table-wrapper">
                <table class="data-table" id="paymentsTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Couple</th>
                            <th>Campus</th>
                            <th>Wedding Date</th>
                            <th>Amount</th>
                            <th>Reference</th>
                            <th>Payment Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td>
                                    <strong>#<?= $payment['id'] ?></strong>
                                    <div class="text-muted" style="font-size: 0.8rem;">
                                        Booking: #<?= $payment['booking_id'] ?>
                                    </div>
                                </td>
                                <td>
                                    <strong><?= esc($payment['groom_name']) ?> & <?= esc($payment['bride_name']) ?></strong>
                                    <div class="text-muted" style="font-size: 0.8rem;">
                                        <?= esc($payment['first_name']) ?> <?= esc($payment['last_name']) ?>
                                        <br><small><?= esc($payment['email']) ?></small>
                                    </div>
                                </td>
                                <td><?= esc($payment['campus_name']) ?></td>
                                <td>
                                    <?= date('M d, Y', strtotime($payment['wedding_date'])) ?>
                                    <div class="text-muted" style="font-size: 0.8rem;">
                                        Booking: <?= ucfirst($payment['booking_status']) ?>
                                    </div>
                                </td>
                                <td><strong>UGX <?= number_format($payment['amount']) ?></strong></td>
                                <td>
                                    <span style="font-family: monospace; background: #f8f9fa; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.8rem;">
                                        <?= esc($payment['payment_reference']) ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y', strtotime($payment['payment_date'])) ?></td>
                                <td>
                                    <select class="form-control form-control-sm payment-status payment-status-<?= $payment['status'] ?>" 
                                            data-payment-id="<?= $payment['id'] ?>" 
                                            onchange="updatePaymentStatus(this)"
                                            style="min-width: 120px;">
                                        <option value="pending" <?= ($payment['status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                                        <option value="completed" <?= ($payment['status'] == 'completed') ? 'selected' : '' ?>>Completed</option>
                                        <option value="failed" <?= ($payment['status'] == 'failed') ? 'selected' : '' ?>>Failed</option>
                                        <option value="refunded" <?= ($payment['status'] == 'refunded') ? 'selected' : '' ?>>Refunded</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="action-buttons" style="display: flex; gap: 0.5rem;">
                                        <button class="btn btn-sm btn-outline-info" onclick="viewPaymentDetails(<?= $payment['id'] ?>)" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="<?= site_url('admin/booking/' . $payment['booking_id']) ?>" 
                                           class="btn btn-sm btn-outline-primary" title="View Booking">
                                            <i class="fas fa-file-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-money-bill-wave" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                <h5 style="color: var(--text-secondary);">No payments found</h5>
                <p style="color: var(--text-tertiary);">No payment records match your current filter criteria.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Payment Details Modal -->
<div class="modal-overlay" id="paymentDetailsModal" style="display: none;">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Payment Details</h3>
            <button type="button" class="modal-close" onclick="closeModal('paymentDetailsModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="paymentDetailsContent">
            <!-- Payment details will be loaded here -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('paymentDetailsModal')">Close</button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.payment-summary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.payment-status {
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
    border: 1px solid var(--border);
}

.payment-status-pending {
    background-color: #fff3cd;
    color: #856404;
    border-color: #ffc107;
}

.payment-status-completed {
    background-color: #d4edda;
    color: #155724;
    border-color: #28a745;
}

.payment-status-failed {
    background-color: #f8d7da;
    color: #721c24;
    border-color: #dc3545;
}

.payment-status-refunded {
    background-color: #e2e3e5;
    color: #383d41;
    border-color: #6c757d;
}

@media (max-width: 768px) {
    .payment-summary-cards {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#paymentsTable').DataTable({
        order: [[6, 'desc']], // Sort by payment date
        pageLength: 25,
        responsive: true,
        language: {
            search: "Search payments:",
            lengthMenu: "Show _MENU_ payments per page",
            info: "Showing _START_ to _END_ of _TOTAL_ payments",
            infoEmpty: "Showing 0 to 0 of 0 payments",
            zeroRecords: "No matching payment records found"
        },
        columnDefs: [
            { orderable: false, targets: -1 } // Disable sorting on actions column
        ]
    });
});

function updatePaymentStatus(selectElement) {
    const paymentId = selectElement.dataset.paymentId;
    const newStatus = selectElement.value;
    const originalStatus = selectElement.querySelector('option[selected]')?.value || selectElement.options[selectElement.selectedIndex].value;
    
    if (confirm(`Are you sure you want to change the payment status to "${newStatus.toUpperCase()}"?`)) {
        fetch('<?= site_url("admin/update-payment-status") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                payment_id: paymentId,
                status: newStatus,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the select element's class
                selectElement.className = `form-control form-control-sm payment-status payment-status-${newStatus}`;
                
                // Show success message
                showAlert('success', data.message);
                
                // Reload page to update summary cards
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                // Revert the selection
                selectElement.value = originalStatus;
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Revert the selection
            selectElement.value = originalStatus;
            showAlert('error', 'An error occurred while updating the payment status');
        });
    } else {
        // Revert the selection if cancelled
        selectElement.value = originalStatus;
    }
}

function viewPaymentDetails(paymentId) {
    document.getElementById('paymentDetailsContent').innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-2">Loading payment details...</p>
        </div>
    `;
    showModal('paymentDetailsModal');
    
    // Here you would typically make an AJAX call to get payment details
    setTimeout(() => {
        document.getElementById('paymentDetailsContent').innerHTML = `
            <p>Payment details functionality can be implemented here to show:</p>
            <ul>
                <li>Full payment information</li>
                <li>Transaction history</li>
                <li>Related booking details</li>
                <li>Payment method specifics</li>
                <li>Administrative notes</li>
            </ul>
        `;
    }, 1000);
}

function showModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = 'auto';
}

function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type === 'success' ? 'success' : 'error'}">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
            ${message}
        </div>
    `;
    
    // Insert the alert at the top of the content
    const content = document.querySelector('.admin-content');
    if (content) {
        content.insertAdjacentHTML('afterbegin', alertHtml);
        setTimeout(() => {
            const alert = content.querySelector('.alert');
            if (alert) alert.remove();
        }, 5000);
    }
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

<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>
<?php
ob_start();

if ($booking['status'] === 'pending'):
    if (!$paymentSummary['is_fully_paid']):
        echo '<button type="button" class="btn btn-success btn-sm" disabled title="Payment must be completed before approval">
            <i class="fas fa-lock"></i> Approve (Payment Required)
        </button>';
    else:
        echo '<button type="button" class="btn btn-success btn-sm" onclick="approveBooking(' . $booking['id'] . ')">
            <i class="fas fa-check"></i> Approve
        </button>';
    endif;
    echo '<button type="button" class="btn btn-danger btn-sm" onclick="rejectBooking(' . $booking['id'] . ')">
        <i class="fas fa-times"></i> Reject
    </button>';
endif;

if ($booking['status'] === 'approved'):
    echo '<a href="' . site_url('admin/booking/' . $booking['id'] . '/manage') . '" class="btn btn-primary btn-sm">
        <i class="fas fa-cogs"></i> Manage Booking
    </a>';
endif;

if (in_array($booking['status'], ['pending', 'approved'])):
    echo '<button type="button" class="btn btn-warning btn-sm" onclick="cancelBooking(' . $booking['id'] . ')">
        <i class="fas fa-ban"></i> Cancel
    </button>';
endif;

echo '<button type="button" class="btn btn-info btn-sm" onclick="downloadPDF()">
    <i class="fas fa-download"></i> Download PDF
</button>';

echo '<a href="' . site_url('admin/bookings') . '" class="btn btn-secondary btn-sm">
    <i class="fas fa-arrow-left"></i> Back to List
</a>';

$pageActions = ob_get_clean();

// Set data for the included view using setData()
$this->setData([
    'title' => 'Booking Details #' . str_pad($booking['id'], 4, '0', STR_PAD_LEFT),
    'subtitle' => 'View complete booking information and manage status',
    'actions' => $pageActions
]);
?>
<?= $this->include('admin_template/partials/page_header') ?>

<!-- Booking Status Banner -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-body">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: center;">
            <div>
                <div style="margin-bottom: 0.5rem;">
                    <span style="color: #6c757d; font-size: 0.9rem;">Booking ID:</span>
                    <strong style="font-size: 1.1rem; margin-left: 0.5rem;">#<?= str_pad($booking['id'], 4, '0', STR_PAD_LEFT) ?></strong>
                </div>
                <div>
                    <?php
                    $statusBadgeClass = 'badge-secondary';
                    $statusIcon = 'fa-question-circle';
                    switch($booking['status']) {
                        case 'pending':
                            $statusBadgeClass = 'badge-warning';
                            $statusIcon = 'fa-clock';
                            break;
                        case 'approved':
                            $statusBadgeClass = 'badge-success';
                            $statusIcon = 'fa-check-circle';
                            break;
                        case 'rejected':
                            $statusBadgeClass = 'badge-danger';
                            $statusIcon = 'fa-times-circle';
                            break;
                        case 'completed':
                            $statusBadgeClass = 'badge-info';
                            $statusIcon = 'fa-star';
                            break;
                        case 'cancelled':
                            $statusBadgeClass = 'badge-secondary';
                            $statusIcon = 'fa-ban';
                            break;
                    }
                    ?>
                    <span class="badge <?= $statusBadgeClass ?>" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                        <i class="fas <?= $statusIcon ?>"></i> <?= ucfirst($booking['status']) ?>
                    </span>
                </div>
            </div>
            <div>
                <div style="margin-bottom: 0.5rem;">
                    <span style="color: #6c757d; font-size: 0.9rem;">Application Submitted:</span>
                    <span style="margin-left: 0.5rem;"><?= isset($booking['created_at']) ? date('M j, Y \a\t g:i A', strtotime($booking['created_at'])) : 'Unknown' ?></span>
                </div>
                <?php if (isset($booking['wedding_date'])): ?>
                <div style="margin-bottom: 0.5rem;">
                    <span style="color: #6c757d; font-size: 0.9rem;">Wedding Date:</span>
                    <span style="margin-left: 0.5rem;"><?= date('l, F j, Y', strtotime($booking['wedding_date'])) ?></span>
                </div>
                <?php endif; ?>
                <?php if (isset($booking['wedding_time'])): ?>
                <div>
                    <span style="color: #6c757d; font-size: 0.9rem;">Ceremony Time:</span>
                    <span style="margin-left: 0.5rem;"><?= date('g:i A', strtotime($booking['wedding_time'])) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

        <!-- Payment Requirement Warning -->
        <?php if ($booking['status'] === 'pending' && !$paymentSummary['is_fully_paid']): ?>
        <div class="alert alert-warning flash-alert" style="margin: 20px 0;">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="alert-content">
                <strong>Payment Required for Approval:</strong> 
                This booking cannot be approved until payment is completed. 
                Current balance: <strong>UGX <?= number_format($paymentSummary['remaining_balance']) ?></strong>
                <?php if ($paymentSummary['pending_amount'] > 0): ?>
                    (UGX <?= number_format($paymentSummary['pending_amount']) ?> pending verification)
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

<!-- Booking Information Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
    <!-- Couple Information -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-heart"></i> Couple Information</h3>
        </div>
        <div class="card-body">
                    <div class="couple-details">
                        <div class="bride-info">
                            <h4><i class="fas fa-female"></i> Bride Details</h4>
                            <div class="detail-row">
                                <span class="label">Full Name:</span>
                                <span class="value"><?= esc($booking['bride_name'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Phone:</span>
                                <span class="value"><?= esc($booking['bride_phone'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Email:</span>
                                <span class="value"><?= esc($booking['bride_email'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Date of Birth:</span>
                                <span class="value"><?= isset($booking['bride_date_of_birth']) ? date('M j, Y', strtotime($booking['bride_date_of_birth'])) : 'Not provided' ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Age:</span>
                                <span class="value"><?= esc($booking['bride_age'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Birth Place:</span>
                                <span class="value"><?= esc($booking['bride_birth_place'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Nationality:</span>
                                <span class="value"><?= esc($booking['bride_nationality'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Occupation:</span>
                                <span class="value"><?= esc($booking['bride_occupation'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Employer:</span>
                                <span class="value"><?= esc($booking['bride_employer'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Education Level:</span>
                                <span class="value"><?= esc($booking['bride_education_level'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">ID Number:</span>
                                <span class="value"><?= esc($booking['bride_id_number'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">ID Type:</span>
                                <span class="value"><?= esc($booking['bride_id_type'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Address:</span>
                                <span class="value"><?= esc($booking['bride_address'] ?? 'Not provided') ?></span>
                            </div>
                        </div>
                        <div class="groom-info">
                            <h4><i class="fas fa-male"></i> Groom Details</h4>
                            <div class="detail-row">
                                <span class="label">Full Name:</span>
                                <span class="value"><?= esc($booking['groom_name'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Phone:</span>
                                <span class="value"><?= esc($booking['groom_phone'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Email:</span>
                                <span class="value"><?= esc($booking['groom_email'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Date of Birth:</span>
                                <span class="value"><?= isset($booking['groom_date_of_birth']) ? date('M j, Y', strtotime($booking['groom_date_of_birth'])) : 'Not provided' ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Age:</span>
                                <span class="value"><?= esc($booking['groom_age'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Birth Place:</span>
                                <span class="value"><?= esc($booking['groom_birth_place'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Nationality:</span>
                                <span class="value"><?= esc($booking['groom_nationality'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Occupation:</span>
                                <span class="value"><?= esc($booking['groom_occupation'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Employer:</span>
                                <span class="value"><?= esc($booking['groom_employer'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Education Level:</span>
                                <span class="value"><?= esc($booking['groom_education_level'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">ID Number:</span>
                                <span class="value"><?= esc($booking['groom_id_number'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">ID Type:</span>
                                <span class="value"><?= esc($booking['groom_id_type'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Address:</span>
                                <span class="value"><?= esc($booking['groom_address'] ?? 'Not provided') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Family Information -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-users"></i> Family Information</h3>
        </div>
        <div class="card-body">
                    <div class="family-details">
                        <div class="bride-family">
                            <h4><i class="fas fa-home"></i> Bride's Family</h4>
                            <div class="detail-row">
                                <span class="label">Father's Name:</span>
                                <span class="value"><?= esc($booking['bride_father'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Father's Occupation:</span>
                                <span class="value"><?= esc($booking['bride_father_occupation'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Mother's Name:</span>
                                <span class="value"><?= esc($booking['bride_mother'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Mother's Occupation:</span>
                                <span class="value"><?= esc($booking['bride_mother_occupation'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Family Contact:</span>
                                <span class="value"><?= esc($booking['bride_family_phone'] ?? 'Not provided') ?></span>
                            </div>
                        </div>
                        <div class="groom-family">
                            <h4><i class="fas fa-home"></i> Groom's Family</h4>
                            <div class="detail-row">
                                <span class="label">Father's Name:</span>
                                <span class="value"><?= esc($booking['groom_father'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Father's Occupation:</span>
                                <span class="value"><?= esc($booking['groom_father_occupation'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Mother's Name:</span>
                                <span class="value"><?= esc($booking['groom_mother'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Mother's Occupation:</span>
                                <span class="value"><?= esc($booking['groom_mother_occupation'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Family Contact:</span>
                                <span class="value"><?= esc($booking['groom_family_phone'] ?? 'Not provided') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Church Membership -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-church"></i> Church Membership</h3>
        </div>
        <div class="card-body">
                    <div class="membership-details">
                        <div class="bride-membership">
                            <h4><i class="fas fa-cross"></i> Bride's Membership</h4>
                            <div class="detail-row">
                                <span class="label">Church Member:</span>
                                <span class="value"><?= isset($booking['bride_church_member']) ? ($booking['bride_church_member'] ? 'Yes' : 'No') : 'Not specified' ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Membership Duration:</span>
                                <span class="value"><?= esc($booking['bride_membership_duration'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Cell Family Number:</span>
                                <span class="value"><?= esc($booking['bride_cell_group_number'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Cell Family Leader Name:</span>
                                <span class="value"><?= esc($booking['bride_cell_leader_name'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Cell Family Leader Phone:</span>
                                <span class="value"><?= esc($booking['bride_cell_leader_phone'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Church Name:</span>
                                <span class="value"><?= esc($booking['bride_church_name'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Senior Pastor:</span>
                                <span class="value"><?= esc($booking['bride_senior_pastor'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Pastor Phone:</span>
                                <span class="value"><?= esc($booking['bride_pastor_phone'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Religion:</span>
                                <span class="value"><?= esc($booking['bride_religion'] ?? 'Not specified') ?></span>
                            </div>
                        </div>
                        <div class="groom-membership">
                            <h4><i class="fas fa-cross"></i> Groom's Membership</h4>
                            <div class="detail-row">
                                <span class="label">Church Member:</span>
                                <span class="value"><?= isset($booking['groom_church_member']) ? ($booking['groom_church_member'] ? 'Yes' : 'No') : 'Not specified' ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Membership Duration:</span>
                                <span class="value"><?= esc($booking['groom_membership_duration'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Cell Family Number:</span>
                                <span class="value"><?= esc($booking['groom_cell_group_number'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Cell Family Leader Name:</span>
                                <span class="value"><?= esc($booking['groom_cell_leader_name'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Cell Family Leader Phone:</span>
                                <span class="value"><?= esc($booking['groom_cell_leader_phone'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Church Name:</span>
                                <span class="value"><?= esc($booking['groom_church_name'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Senior Pastor:</span>
                                <span class="value"><?= esc($booking['groom_senior_pastor'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Pastor Phone:</span>
                                <span class="value"><?= esc($booking['groom_pastor_phone'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Religion:</span>
                                <span class="value"><?= esc($booking['groom_religion'] ?? 'Not specified') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Witnesses Information -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-friends"></i> Best Man & Matron Information</h3>
        </div>
        <div class="card-body">
                    <div class="witnesses-details">
                        <div class="witness1-info">
                            <h4><i class="fas fa-user"></i> Best Man</h4>
                            <div class="detail-row">
                                <span class="label">Full Name:</span>
                                <span class="value"><?= esc($booking['witness1_name'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Phone:</span>
                                <span class="value"><?= esc($booking['witness1_phone'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">ID Number:</span>
                                <span class="value"><?= esc($booking['witness1_id_number'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Relationship:</span>
                                <span class="value"><?= esc($booking['witness1_relationship'] ?? 'Not provided') ?></span>
                            </div>
                        </div>
                        <div class="witness2-info">
                            <h4><i class="fas fa-user"></i> Matron</h4>
                            <div class="detail-row">
                                <span class="label">Full Name:</span>
                                <span class="value"><?= esc($booking['witness2_name'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Phone:</span>
                                <span class="value"><?= esc($booking['witness2_phone'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">ID Number:</span>
                                <span class="value"><?= esc($booking['witness2_id_number'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Relationship:</span>
                                <span class="value"><?= esc($booking['witness2_relationship'] ?? 'Not provided') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<!-- Documents and Payment Row -->
<div style="display: flex; flex-direction: column; gap: 1.5rem; margin-top: 1.5rem; width: 100%;">
    <!-- Uploaded Documents - Full Row -->
    <div class="card" style="width: 100%; max-width: 100%;">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-file-upload"></i> Uploaded Documents</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($uploadedDocuments)): ?>
            <ul class="documents-list" style="list-style: none; padding: 0; margin: 0;">
                <?php foreach ($uploadedDocuments as $doc): ?>
                <li style="display: flex; align-items: center; justify-content: space-between; padding: 12px 15px; border-bottom: 1px solid #dee2e6; transition: background-color 0.2s;">
                    <div style="display: flex; align-items: center; gap: 12px; flex: 1;">
                        <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #64017f, #8b1fa9); border-radius: 6px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1rem; flex-shrink: 0;">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-weight: 600; color: #212529; margin-bottom: 2px;"><?= esc($doc['name']) ?></div>
                            <div style="font-size: 0.85rem; color: #6c757d; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?= esc($doc['original_name'] ?? $doc['filename']) ?>">
                                <?= esc($doc['original_name'] ?? $doc['filename']) ?>
                            </div>
                            <?php if (!empty($doc['uploaded_at'])): ?>
                            <div style="font-size: 0.75rem; color: #6c757d; margin-top: 2px;">
                                <i class="fas fa-calendar"></i> <?= date('M d, Y', strtotime($doc['uploaded_at'])) ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!empty($doc['file_path'])): ?>
                    <a href="<?= base_url($doc['file_path']) ?>" target="_blank" class="btn btn-sm btn-primary" style="margin-left: 15px; white-space: nowrap;">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
            <div style="text-align: center; padding: 30px; color: #6c757d;">
                <i class="fas fa-folder-open" style="font-size: 3rem; margin-bottom: 15px; opacity: 0.5;"></i>
                <p style="margin: 0;">No documents have been uploaded yet.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Payment Information - Full Row -->
    <div class="card" style="width: 100%; max-width: 100%;">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-credit-card"></i> Payment Information</h3>
                <div class="payment-status-summary">
                    <?php if ($paymentSummary['is_fully_paid']): ?>
                        <span class="payment-badge paid">
                            <i class="fas fa-check-circle"></i> Fully Paid
                        </span>
                        <?php if ($booking['status'] === 'pending'): ?>
                            <span class="approval-ready" style="margin-left: 10px; color: #28a745; font-size: 0.85rem;">
                                <i class="fas fa-thumbs-up"></i> Ready for Approval
                            </span>
                        <?php endif; ?>
                    <?php elseif ($paymentSummary['total_paid'] > 0): ?>
                        <span class="payment-badge partial">
                            <i class="fas fa-clock"></i> Partially Paid
                        </span>
                        <?php if ($booking['status'] === 'pending'): ?>
                            <span class="approval-blocked" style="margin-left: 10px; color: #dc3545; font-size: 0.85rem;">
                                <i class="fas fa-lock"></i> Approval Blocked
                            </span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="payment-badge unpaid">
                            <i class="fas fa-exclamation-triangle"></i> Unpaid
                        </span>
                        <?php if ($booking['status'] === 'pending'): ?>
                            <span class="approval-blocked" style="margin-left: 10px; color: #dc3545; font-size: 0.85rem;">
                                <i class="fas fa-lock"></i> Approval Blocked
                            </span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
    </div>
    
    <div class="card-body">
                <!-- Payment Summary -->
                <div class="payment-summary">
                    <div class="summary-row">
                        <span class="label">Total Required:</span>
                        <span class="value amount">UGX <?= number_format($paymentSummary['total_required']) ?></span>
                    </div>
                    <div class="summary-row">
                        <span class="label">Total Paid:</span>
                        <span class="value amount completed">UGX <?= number_format($paymentSummary['total_paid']) ?></span>
                    </div>
                    <?php if ($paymentSummary['pending_amount'] > 0): ?>
                    <div class="summary-row">
                        <span class="label">Pending Verification:</span>
                        <span class="value amount pending">UGX <?= number_format($paymentSummary['pending_amount']) ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="summary-row total">
                        <span class="label">Remaining Balance:</span>
                        <span class="value amount <?= $paymentSummary['remaining_balance'] <= 0 ? 'completed' : 'outstanding' ?>">
                            UGX <?= number_format(max(0, $paymentSummary['remaining_balance'])) ?>
                        </span>
                    </div>
                </div>

                <!-- Payment Requirement Notice -->
                <?php if ($booking['status'] === 'pending' && !$paymentSummary['is_fully_paid']): ?>
                <div class="payment-requirement-notice" style="background: #fff3cd; border: 1px solid #ffd60a; border-left: 4px solid #ffc107; padding: 16px; border-radius: 6px; margin-top: 16px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-exclamation-triangle" style="color: #856404; font-size: 1.2rem;"></i>
                        <div>
                            <strong style="color: #856404;">Approval Blocked</strong>
                            <p style="margin: 4px 0 0 0; color: #856404; font-size: 0.9rem;">
                                This booking cannot be approved until the full payment is received and verified.
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Payment Records -->
                <?php if (!empty($payments)): ?>
                <div class="payment-records">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h4><i class="fas fa-list"></i> Payment History</h4>
                        <small style="color: #6c757d; font-style: italic;">
                            <i class="fas fa-info-circle"></i> Use the dropdown to verify/update payment status
                        </small>
                    </div>
                    <div class="payments-table">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
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
                                    <td>#<?= $payment['id'] ?></td>
                                    <td><strong>UGX <?= number_format($payment['amount']) ?></strong></td>
                                    <td>
                                        <code class="payment-ref"><?= esc($payment['transaction_reference'] ?? 'N/A') ?></code>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($payment['payment_date'])) ?></td>
                                    <td>
                                        <select class="payment-status-select status-<?= $payment['status'] ?>" 
                                                data-payment-id="<?= $payment['id'] ?>" 
                                                onchange="updatePaymentStatus(this)"
                                                title="Click to verify/update payment status">
                                            <option value="pending" <?= ($payment['status'] == 'pending') ? 'selected' : '' ?>>Pending Verification</option>
                                            <option value="completed" <?= ($payment['status'] == 'completed') ? 'selected' : '' ?>>Completed ✓</option>
                                            <option value="failed" <?= ($payment['status'] == 'failed') ? 'selected' : '' ?>>Failed ✗</option>
                                            <option value="refunded" <?= ($payment['status'] == 'refunded') ? 'selected' : '' ?>>Refunded</option>
                                        </select>
                                    </td>
                                    <td>
                                        <?php if (!empty($payment['notes'])): ?>
                                        <button class="btn btn-sm btn-info" 
                                                onclick="showPaymentNotes('<?= esc($payment['notes']) ?>')" 
                                                title="View Notes">
                                            <i class="fas fa-sticky-note"></i>
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php else: ?>
                <div class="no-payments">
                    <div class="empty-state">
                        <i class="fas fa-money-bill-wave fa-3x"></i>
                        <h4>No Payment Records</h4>
                        <p>No payment records found for this booking.</p>
                    </div>
                </div>
                <?php endif; ?>
    </div>
</div>
</div>
<!-- End Documents and Payment Row -->

<!-- Reason Display -->
        <?php if (!empty($booking['reason']) && in_array($booking['status'], ['rejected', 'cancelled'])): ?>
        <div class="reason-alert">
            <div class="alert-header">
                <i class="fas fa-info-circle"></i>
                <span>Reason for <?= ucfirst($booking['status']) ?></span>
            </div>
            <div class="alert-content">
                <?= nl2br(esc($booking['reason'])) ?>
            </div>
        </div>
        <?php endif; ?>

<!-- Quick Actions Panel -->
<?php if ($booking['status'] === 'approved'): ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-cogs"></i> Quick Actions</h3>
    </div>
    <div class="card-body">
                <button type="button" class="btn btn-primary" onclick="markAsCompleted(<?= $booking['id'] ?>)">
                    <i class="fas fa-check-circle"></i> Mark as Completed
                </button>
                <button type="button" class="btn btn-info" onclick="sendReminder(<?= $booking['id'] ?>)">
                    <i class="fas fa-envelope"></i> Send Reminder Email
                </button>
                <button type="button" class="btn btn-secondary" onclick="addNotes(<?= $booking['id'] ?>)">
                    <i class="fas fa-sticky-note"></i> Add Notes
                </button>
    </div>
</div>
<?php endif; ?>

<!-- Modals -->
<div class="modal-overlay" id="rejectModal" style="display: none;">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Reject Booking</h3>
            <button class="modal-close" onclick="closeModal('rejectModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
            <form id="rejectForm" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejection_reason">Reason for Rejection:</label>
                        <textarea class="form-control" id="rejection_reason" name="reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('rejectModal')">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Booking</button>
                </div>
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
            </form>
    </div>
</div>

<div class="modal-overlay" id="cancelModal" style="display: none;">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Cancel Booking</h3>
            <button class="modal-close" onclick="closeModal('cancelModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="cancelForm" method="POST">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Reason for Cancellation:</label>
                    <textarea class="form-control" id="cancellation_reason" name="reason" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('cancelModal')">Cancel</button>
                <button type="submit" class="btn btn-warning">Cancel Booking</button>
            </div>
            <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
function approveBooking(id) {
    <?php if (!$paymentSummary['is_fully_paid']): ?>
        alert('This booking cannot be approved until payment is completed.');
        return false;
    <?php else: ?>
        if (confirm('Are you sure you want to approve this booking?')) {
            window.location.href = `<?= site_url('admin/booking/') ?>${id}/approve`;
        }
    <?php endif; ?>
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
    
    const form = document.querySelector(`#${modalId} form`);
    if (form) {
        form.reset();
    }
}

function markAsCompleted(id) {
    if (confirm('Mark this booking as completed?')) {
        window.location.href = `<?= site_url('admin/booking/') ?>${id}/complete`;
    }
}

function sendReminder(id) {
    if (confirm('Send reminder email to the couple?')) {
        // Implement reminder functionality
        alert('Reminder email sent successfully!');
    }
}

function addNotes(id) {
    const notes = prompt('Add administrative notes:');
    if (notes) {
        // Implement notes functionality
        alert('Notes added successfully!');
    }
}
</script>

<style>
/* Booking Details Specific Styles */
.booking-status-banner {
    background: #495057;
    color: white;
    padding: 24px;
    border-radius: 12px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.status-info {
    display: flex;
    align-items: center;
    gap: 24px;
}

.booking-id .label,
.booking-dates .label {
    font-size: 12px;
    opacity: 0.8;
    display: block;
    margin-bottom: 4px;
}

.booking-id .value,
.booking-dates .value {
    font-size: 16px;
    font-weight: 600;
    display: block;
}

.status-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 14px;
}

.status-pending { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
.status-approved { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
.status-rejected { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
.status-completed { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
.status-cancelled { background: #e2e3e5; color: #383d41; border: 1px solid #d6d8db; }

.booking-dates {
    text-align: right;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.created-date,
.wedding-date,
.wedding-time {
    margin-bottom: 4px;
}

/* Info grid uses template card structure */

.couple-details,
.family-details,
.membership-details,
.witnesses-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    overflow: visible;
}

.bride-info,
.groom-info,
.bride-family,
.groom-family,
.bride-membership,
.groom-membership,
.witness1-info,
.witness2-info {
    min-width: 0;
    overflow: visible;
}

.bride-info h4,
.groom-info h4,
.bride-family h4,
.groom-family h4,
.bride-membership h4,
.groom-membership h4,
.witness1-info h4,
.witness2-info h4 {
    color: #495057;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 2px solid #e9ecef;
    display: flex;
    align-items: center;
    gap: 6px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 8px 0;
    border-bottom: 1px solid #f8f9fa;
    font-size: 13px;
    gap: 12px;
    min-width: 0;
    overflow: visible;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-row .label {
    font-weight: 600;
    color: #6c757d;
    min-width: 100px;
    flex-shrink: 0;
    line-height: 1.4;
}

.detail-row .value {
    text-align: right;
    flex: 1;
    color: #212529;
    word-wrap: break-word;
    overflow-wrap: break-word;
    line-height: 1.4;
    min-width: 0;
}

.reason-alert {
    background: #e7f3ff;
    border: 1px solid #b8daff;
    border-radius: 8px;
    margin-bottom: 24px;
    overflow: hidden;
}

.reason-alert .alert-header {
    background: #cce7ff;
    padding: 12px 20px;
    font-weight: 600;
    color: #004085;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.reason-alert .alert-content {
    padding: 16px 20px;
    color: #004085;
    line-height: 1.6;
    font-size: 14px;
}

.actions-panel {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    border: 1px solid #e9ecef;
}

.actions-panel .panel-header {
    background: #6c757d;
    color: white;
    padding: 16px 20px;
}

.actions-panel .panel-header h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}

.actions-panel .panel-content {
    padding: 20px;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.actions-panel .btn {
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.3s ease;
    border: none;
}

.actions-panel .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Responsive Design */
@media (max-width: 768px) {
    .booking-status-banner {
        flex-direction: column;
        text-align: center;
        padding: 20px;
    }
    
    .status-info {
        flex-direction: column;
        gap: 16px;
    }
    
    .booking-dates {
        text-align: center;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .couple-details,
    .family-details,
    .membership-details,
    .witnesses-details {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .detail-row {
        flex-direction: column;
        gap: 4px;
        align-items: flex-start;
    }
    
    .detail-row .label {
        min-width: auto;
        font-size: 12px;
    }
    
    .detail-row .value {
        text-align: left;
        font-size: 13px;
    }
    
    .actions-panel .panel-content {
        flex-direction: column;
    }
    
    .payment-summary {
        flex-direction: column;
    }
    
    .payments-table {
        overflow-x: auto;
    }
}

/* Payment Styles */
.payment-status-summary .payment-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.payment-badge.paid {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.payment-badge.partial {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.payment-badge.unpaid {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Approval Status Indicators */
.approval-ready {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-weight: 600;
    background: rgba(40, 167, 69, 0.1);
    padding: 4px 8px;
    border-radius: 12px;
    border: 1px solid rgba(40, 167, 69, 0.3);
}

.approval-blocked {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-weight: 600;
    background: rgba(220, 53, 69, 0.1);
    padding: 4px 8px;
    border-radius: 12px;
    border: 1px solid rgba(220, 53, 69, 0.3);
}

/* Disabled approval button styling */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.payment-summary {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 25px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #e9ecef;
}

.summary-row:last-child {
    border-bottom: none;
}

.summary-row.total {
    font-weight: bold;
    padding-top: 12px;
    margin-top: 8px;
    border-top: 2px solid #6c757d;
    border-bottom: none;
}

.summary-row .label {
    color: #6c757d;
    font-weight: 500;
}

.summary-row .value.amount {
    font-weight: 600;
    font-family: 'Courier New', monospace;
}

.summary-row .value.completed {
    color: #28a745;
}

.summary-row .value.pending {
    color: #ffc107;
}

.summary-row .value.outstanding {
    color: #dc3545;
}

.payment-records h4 {
    color: #495057;
    margin-bottom: 15px;
    font-size: 16px;
}

.payments-table {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.payments-table table {
    width: 100%;
    margin: 0;
}

.payments-table th {
    background: #495057;
    color: white;
    font-weight: 600;
    font-size: 13px;
    padding: 12px 15px;
    text-align: left;
}

.payments-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #e9ecef;
    font-size: 14px;
}

.payments-table tr:last-child td {
    border-bottom: none;
}

.payment-ref {
    background: #e9ecef;
    padding: 4px 8px;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 12px;
}

.payment-status-select {
    padding: 4px 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
}

.payment-status-select.status-pending {
    background: #fff3cd;
    color: #856404;
}

.payment-status-select.status-completed {
    background: #d4edda;
    color: #155724;
}

.payment-status-select.status-failed {
    background: #f8d7da;
    color: #721c24;
}

.payment-status-select.status-refunded {
    background: #e2e3e5;
    color: #383d41;
}

.no-payments .empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.no-payments .empty-state i {
    color: #dee2e6;
    margin-bottom: 15px;
}

.no-payments .empty-state h4 {
    margin: 15px 0 10px 0;
    color: #495057;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 13px;
    border-radius: 6px;
    font-weight: 500;
}

.section-actions .btn {
    margin-left: 8px;
}

.section-actions .btn:first-child {
    margin-left: 0;
}

/* Print styles for official document */
@media print {
    body * {
        visibility: hidden;
    }
    
    .print-content, .print-content * {
        visibility: visible;
    }
    
    .print-content {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 20mm;
        font-family: 'Times New Roman', serif;
        font-size: 12pt;
        line-height: 1.4;
        color: #000;
    }
}

@media (max-width: 480px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .info-card .card-content {
        padding: 16px;
    }
    
    .booking-status-banner {
        padding: 16px;
    }
    
    .detail-row {
        padding: 6px 0;
    }
    
    .payment-summary {
        padding: 15px;
    }
    
    .payments-table {
        font-size: 12px;
    }
    
    .payments-table th,
    .payments-table td {
        padding: 8px 10px;
    }
}
</style>

<script>
function updatePaymentStatus(selectElement) {
    const paymentId = selectElement.dataset.paymentId;
    const newStatus = selectElement.value;
    const originalStatus = selectElement.className.match(/status-(\w+)/)?.[1];
    
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
                selectElement.className = `payment-status-select status-${newStatus}`;
                
                // Show success message
                alert('Payment status updated successfully!');
                
                // Reload page to update payment summary
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                // Revert the selection
                if (originalStatus) {
                    selectElement.value = originalStatus;
                }
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Revert the selection
            if (originalStatus) {
                selectElement.value = originalStatus;
            }
            alert('An error occurred while updating the payment status');
        });
    } else {
        // Revert the selection if cancelled
        if (originalStatus) {
            selectElement.value = originalStatus;
        }
    }
}

function showPaymentNotes(notes) {
    alert('Payment Notes:\n\n' + notes);
}

// Existing JavaScript functions for booking management
function approveBooking(bookingId) {
    if (confirm('Are you sure you want to approve this booking?')) {
        window.location.href = '<?= site_url("admin/booking/") ?>' + bookingId + '/approve';
    }
}

function rejectBooking(bookingId) {
    const reason = prompt('Please provide a reason for rejection:');
    if (reason && reason.trim() !== '') {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= site_url("admin/booking/") ?>' + bookingId + '/reject';
        
        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'reason';
        reasonInput.value = reason;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        
        form.appendChild(reasonInput);
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function cancelBooking(bookingId) {
    const reason = prompt('Please provide a reason for cancellation:');
    if (reason && reason.trim() !== '') {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= site_url("admin/booking/") ?>' + bookingId + '/cancel';
        
        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'reason';
        reasonInput.value = reason;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        
        form.appendChild(reasonInput);
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// PDF Generation Function
function downloadPDF() {
    // Include jsPDF library
    const script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
    script.onload = function() {
        generatePDF();
    };
    document.head.appendChild(script);
}

function generatePDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'mm', 'a4');
    
    // Helper function to add new page if needed
    function checkNewPage(requiredSpace) {
        if (yPos + requiredSpace > 270) {
            doc.addPage();
            yPos = 20;
            return true;
        }
        return false;
    }
    
    // Helper function to add section header
    function addSectionHeader(title, y) {
        doc.setTextColor(100, 1, 127);
        doc.setFontSize(13);
        doc.setFont(undefined, 'bold');
        doc.text(title, 20, y);
        doc.setLineWidth(0.8);
        doc.setDrawColor(100, 1, 127);
        doc.line(20, y + 2, 190, y + 2);
        doc.setFont(undefined, 'normal');
        return y + 8;
    }
    
    // Helper function to add label-value pair
    function addLabelValue(label, value, x, y, maxWidth = 80) {
        doc.setTextColor(100, 1, 127);
        doc.setFontSize(9);
        doc.setFont(undefined, 'bold');
        doc.text(label + ':', x, y);
        doc.setTextColor(52, 73, 94);
        doc.setFont(undefined, 'normal');
        const lines = doc.splitTextToSize(value || 'Not provided', maxWidth);
        doc.text(lines, x + 35, y);
        return y + (lines.length * 5);
    }
    
    // Set primary colors
    const primaryPurple = [100, 1, 127];
    const darkGray = [52, 73, 94];
    const lightGray = [189, 195, 199];
    
    // Header with background color
    doc.setFillColor(100, 1, 127);
    doc.rect(0, 0, 210, 50, 'F');
    
    // Church logo placeholder
    doc.setFillColor(255, 255, 255);
    doc.circle(30, 25, 10, 'F');
    doc.setTextColor(100, 1, 127);
    doc.setFontSize(10);
    doc.setFont(undefined, 'bold');
    doc.text('WC', 30, 27, { align: 'center' });
    
    // Header text
    doc.setTextColor(255, 255, 255);
    doc.setFontSize(22);
    doc.setFont(undefined, 'bold');
    doc.text('WATOTO CHURCH', 105, 22, { align: 'center' });
    doc.setFontSize(14);
    doc.text('WEDDING CEREMONY OFFICIAL PROFILE', 105, 30, { align: 'center' });
    doc.setFontSize(10);
    doc.setFont(undefined, 'normal');
    doc.text('"Celebrating Christ, Caring for Community"', 105, 38, { align: 'center' });
    doc.text('Official Application Document', 105, 44, { align: 'center' });
    
    // Document info box
    const bookingId = '<?= str_pad($booking["id"], 4, "0", STR_PAD_LEFT) ?>';
    doc.setFillColor(248, 249, 250);
    doc.roundedRect(15, 55, 180, 18, 3, 3, 'F');
    doc.setDrawColor(100, 1, 127);
    doc.setLineWidth(0.5);
    doc.roundedRect(15, 55, 180, 18, 3, 3, 'D');
    
    doc.setTextColor(52, 73, 94);
    doc.setFontSize(11);
    doc.setFont(undefined, 'bold');
    doc.text('APPLICATION #' + bookingId, 20, 62);
    doc.text('STATUS: <?= strtoupper($booking["status"]) ?>', 120, 62);
    doc.setFont(undefined, 'normal');
    doc.setFontSize(9);
    doc.text('Generated: ' + new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }), 20, 68);
    doc.text('Application Date: <?= isset($booking["created_at"]) ? date("F j, Y", strtotime($booking["created_at"])) : "Unknown" ?>', 120, 68);
    
    let yPos = 80;
    
    // ========== VENUE & DATE INFORMATION ==========
    yPos = addSectionHeader('VENUE & DATE INFORMATION', yPos);
    checkNewPage(25);
    
    doc.setFillColor(255, 248, 220);
    doc.roundedRect(15, yPos - 3, 180, 25, 2, 2, 'F');
    doc.setDrawColor(255, 193, 7);
    doc.setLineWidth(0.3);
    doc.roundedRect(15, yPos - 3, 180, 25, 2, 2, 'D');
    
    yPos = addLabelValue('Campus/Venue', '<?= esc($booking["campus_name"] ?? "Not assigned") ?>', 20, yPos, 150);
    yPos = addLabelValue('Wedding Date', '<?= isset($booking["wedding_date"]) ? date("l, F j, Y", strtotime($booking["wedding_date"])) : "Not scheduled" ?>', 20, yPos, 150);
    yPos = addLabelValue('Ceremony Time', '<?= isset($booking["wedding_time"]) ? date("g:i A", strtotime($booking["wedding_time"])) : "Not scheduled" ?>', 20, yPos, 150);
    yPos += 5;
    
    // ========== BRIDE INFORMATION ==========
    yPos = addSectionHeader('BRIDE INFORMATION', yPos);
    checkNewPage(60);
    
    doc.setFillColor(255, 240, 245);
    doc.roundedRect(15, yPos - 3, 180, 60, 2, 2, 'F');
    doc.setDrawColor(255, 182, 193);
    doc.setLineWidth(0.3);
    doc.roundedRect(15, yPos - 3, 180, 60, 2, 2, 'D');
    
    let brideY = yPos;
    brideY = addLabelValue('Full Name', '<?= esc($booking["bride_name"] ?? "Not provided") ?>', 20, brideY, 150);
    brideY = addLabelValue('Phone', '<?= esc($booking["bride_phone"] ?? "Not provided") ?>', 20, brideY, 150);
    brideY = addLabelValue('Email', '<?= esc($booking["bride_email"] ?? "Not provided") ?>', 20, brideY, 150);
    brideY = addLabelValue('Date of Birth', '<?= isset($booking["bride_date_of_birth"]) ? date("F j, Y", strtotime($booking["bride_date_of_birth"])) : "Not provided" ?>', 20, brideY, 150);
    brideY = addLabelValue('Age', '<?= esc($booking["bride_age"] ?? "Not provided") ?>', 20, brideY, 150);
    brideY = addLabelValue('Birth Place', '<?= esc($booking["bride_birth_place"] ?? "Not provided") ?>', 20, brideY, 150);
    brideY = addLabelValue('Nationality', '<?= esc($booking["bride_nationality"] ?? "Not provided") ?>', 20, brideY, 150);
    brideY = addLabelValue('Religion', '<?= esc($booking["bride_religion"] ?? "Not provided") ?>', 20, brideY, 150);
    brideY = addLabelValue('Marital Status', '<?php
        $brideStatus = $booking["bride_marital_status"] ?? "";
        $brideStatusLabels = [
            "spinster" => "Spinster",
            "divorced-separated" => "Divorced/Separated",
            "married-traditionally" => "Married Traditionally",
            "widowed" => "Widowed",
            "civil-marriage" => "Civil Marriage",
            "cohabiting" => "Cohabiting"
        ];
        echo esc($brideStatusLabels[$brideStatus] ?? ucfirst(str_replace("-", " ", $brideStatus)) ?: "Not provided");
    ?>', 20, brideY, 150);
    brideY = addLabelValue('Occupation', '<?= esc($booking["bride_occupation"] ?? "Not provided") ?>', 20, brideY, 150);
    brideY = addLabelValue('Employer', '<?= esc($booking["bride_employer"] ?? "Not provided") ?>', 20, brideY, 150);
    brideY = addLabelValue('Education Level', '<?= esc($booking["bride_education_level"] ?? "Not provided") ?>', 20, brideY, 150);
    brideY = addLabelValue('ID Type', '<?= esc($booking["bride_id_type"] ?? "Not provided") ?>', 20, brideY, 150);
    brideY = addLabelValue('ID Number', '<?= esc($booking["bride_id_number"] ?? "Not provided") ?>', 20, brideY, 150);
    brideY = addLabelValue('Address', '<?= esc($booking["bride_address"] ?? "Not provided") ?>', 20, brideY, 150);
    yPos = brideY + 5;
    
    // ========== GROOM INFORMATION ==========
    yPos = addSectionHeader('GROOM INFORMATION', yPos);
    checkNewPage(60);
    
    doc.setFillColor(240, 248, 255);
    doc.roundedRect(15, yPos - 3, 180, 60, 2, 2, 'F');
    doc.setDrawColor(173, 216, 230);
    doc.setLineWidth(0.3);
    doc.roundedRect(15, yPos - 3, 180, 60, 2, 2, 'D');
    
    let groomY = yPos;
    groomY = addLabelValue('Full Name', '<?= esc($booking["groom_name"] ?? "Not provided") ?>', 20, groomY, 150);
    groomY = addLabelValue('Phone', '<?= esc($booking["groom_phone"] ?? "Not provided") ?>', 20, groomY, 150);
    groomY = addLabelValue('Email', '<?= esc($booking["groom_email"] ?? "Not provided") ?>', 20, groomY, 150);
    groomY = addLabelValue('Date of Birth', '<?= isset($booking["groom_date_of_birth"]) ? date("F j, Y", strtotime($booking["groom_date_of_birth"])) : "Not provided" ?>', 20, groomY, 150);
    groomY = addLabelValue('Age', '<?= esc($booking["groom_age"] ?? "Not provided") ?>', 20, groomY, 150);
    groomY = addLabelValue('Birth Place', '<?= esc($booking["groom_birth_place"] ?? "Not provided") ?>', 20, groomY, 150);
    groomY = addLabelValue('Nationality', '<?= esc($booking["groom_nationality"] ?? "Not provided") ?>', 20, groomY, 150);
    groomY = addLabelValue('Religion', '<?= esc($booking["groom_religion"] ?? "Not provided") ?>', 20, groomY, 150);
    groomY = addLabelValue('Marital Status', '<?php
        $groomStatus = $booking["groom_marital_status"] ?? "";
        $groomStatusLabels = [
            "bachelor" => "Bachelor",
            "divorced-separated" => "Divorced/Separated",
            "married-traditionally" => "Married Traditionally",
            "widowed" => "Widowed",
            "civil-marriage" => "Civil Marriage",
            "cohabiting" => "Cohabiting"
        ];
        echo esc($groomStatusLabels[$groomStatus] ?? ucfirst(str_replace("-", " ", $groomStatus)) ?: "Not provided");
    ?>', 20, groomY, 150);
    groomY = addLabelValue('Occupation', '<?= esc($booking["groom_occupation"] ?? "Not provided") ?>', 20, groomY, 150);
    groomY = addLabelValue('Employer', '<?= esc($booking["groom_employer"] ?? "Not provided") ?>', 20, groomY, 150);
    groomY = addLabelValue('Education Level', '<?= esc($booking["groom_education_level"] ?? "Not provided") ?>', 20, groomY, 150);
    groomY = addLabelValue('ID Type', '<?= esc($booking["groom_id_type"] ?? "Not provided") ?>', 20, groomY, 150);
    groomY = addLabelValue('ID Number', '<?= esc($booking["groom_id_number"] ?? "Not provided") ?>', 20, groomY, 150);
    groomY = addLabelValue('Address', '<?= esc($booking["groom_address"] ?? "Not provided") ?>', 20, groomY, 150);
    yPos = groomY + 5;
    
    // ========== FAMILY INFORMATION ==========
    yPos = addSectionHeader('FAMILY INFORMATION', yPos);
    checkNewPage(50);
    
    doc.setFillColor(248, 255, 248);
    doc.roundedRect(15, yPos - 3, 180, 50, 2, 2, 'F');
    doc.setDrawColor(200, 230, 200);
    doc.setLineWidth(0.3);
    doc.roundedRect(15, yPos - 3, 180, 50, 2, 2, 'D');
    
    let familyY = yPos;
    doc.setTextColor(100, 1, 127);
    doc.setFontSize(10);
    doc.setFont(undefined, 'bold');
    doc.text('Bride\'s Family:', 20, familyY);
    familyY += 6;
    familyY = addLabelValue('Father\'s Name', '<?= esc($booking["bride_father"] ?? "Not provided") ?>', 20, familyY, 150);
    familyY = addLabelValue('Father\'s Occupation', '<?= esc($booking["bride_father_occupation"] ?? "Not provided") ?>', 20, familyY, 150);
    familyY = addLabelValue('Mother\'s Name', '<?= esc($booking["bride_mother"] ?? "Not provided") ?>', 20, familyY, 150);
    familyY = addLabelValue('Mother\'s Occupation', '<?= esc($booking["bride_mother_occupation"] ?? "Not provided") ?>', 20, familyY, 150);
    familyY = addLabelValue('Family Contact', '<?= esc($booking["bride_family_phone"] ?? "Not provided") ?>', 20, familyY, 150);
    familyY += 3;
    doc.setTextColor(100, 1, 127);
    doc.setFontSize(10);
    doc.setFont(undefined, 'bold');
    doc.text('Groom\'s Family:', 20, familyY);
    familyY += 6;
    familyY = addLabelValue('Father\'s Name', '<?= esc($booking["groom_father"] ?? "Not provided") ?>', 20, familyY, 150);
    familyY = addLabelValue('Father\'s Occupation', '<?= esc($booking["groom_father_occupation"] ?? "Not provided") ?>', 20, familyY, 150);
    familyY = addLabelValue('Mother\'s Name', '<?= esc($booking["groom_mother"] ?? "Not provided") ?>', 20, familyY, 150);
    familyY = addLabelValue('Mother\'s Occupation', '<?= esc($booking["groom_mother_occupation"] ?? "Not provided") ?>', 20, familyY, 150);
    familyY = addLabelValue('Family Contact', '<?= esc($booking["groom_family_phone"] ?? "Not provided") ?>', 20, familyY, 150);
    yPos = familyY + 5;
    
    // ========== CHURCH MEMBERSHIP ==========
    yPos = addSectionHeader('CHURCH MEMBERSHIP', yPos);
    checkNewPage(50);
    
    doc.setFillColor(255, 250, 240);
    doc.roundedRect(15, yPos - 3, 180, 50, 2, 2, 'F');
    doc.setDrawColor(255, 218, 185);
    doc.setLineWidth(0.3);
    doc.roundedRect(15, yPos - 3, 180, 50, 2, 2, 'D');
    
    let churchY = yPos;
    doc.setTextColor(100, 1, 127);
    doc.setFontSize(10);
    doc.setFont(undefined, 'bold');
    doc.text('Bride\'s Membership:', 20, churchY);
    churchY += 6;
    churchY = addLabelValue('Church Member', '<?= isset($booking["bride_church_member"]) ? ($booking["bride_church_member"] ? "Yes" : "No") : "Not specified" ?>', 20, churchY, 150);
    churchY = addLabelValue('Membership Duration', '<?= esc($booking["bride_membership_duration"] ?? "Not provided") ?>', 20, churchY, 150);
    churchY = addLabelValue('Cell Family Number', '<?= esc($booking["bride_cell_group_number"] ?? "Not provided") ?>', 20, churchY, 150);
    churchY = addLabelValue('Cell Family Leader', '<?= esc($booking["bride_cell_leader_name"] ?? "Not provided") ?>', 20, churchY, 150);
    churchY = addLabelValue('Leader Phone', '<?= esc($booking["bride_cell_leader_phone"] ?? "Not provided") ?>', 20, churchY, 150);
    <?php if (!empty($booking['bride_church_name'])): ?>
    churchY = addLabelValue('Church Name', '<?= esc($booking["bride_church_name"]) ?>', 20, churchY, 150);
    churchY = addLabelValue('Senior Pastor', '<?= esc($booking["bride_senior_pastor"] ?? "Not provided") ?>', 20, churchY, 150);
    churchY = addLabelValue('Pastor Phone', '<?= esc($booking["bride_pastor_phone"] ?? "Not provided") ?>', 20, churchY, 150);
    <?php endif; ?>
    churchY += 3;
    doc.setTextColor(100, 1, 127);
    doc.setFontSize(10);
    doc.setFont(undefined, 'bold');
    doc.text('Groom\'s Membership:', 20, churchY);
    churchY += 6;
    churchY = addLabelValue('Church Member', '<?= isset($booking["groom_church_member"]) ? ($booking["groom_church_member"] ? "Yes" : "No") : "Not specified" ?>', 20, churchY, 150);
    churchY = addLabelValue('Membership Duration', '<?= esc($booking["groom_membership_duration"] ?? "Not provided") ?>', 20, churchY, 150);
    churchY = addLabelValue('Cell Family Number', '<?= esc($booking["groom_cell_group_number"] ?? "Not provided") ?>', 20, churchY, 150);
    churchY = addLabelValue('Cell Family Leader', '<?= esc($booking["groom_cell_leader_name"] ?? "Not provided") ?>', 20, churchY, 150);
    churchY = addLabelValue('Leader Phone', '<?= esc($booking["groom_cell_leader_phone"] ?? "Not provided") ?>', 20, churchY, 150);
    <?php if (!empty($booking['groom_church_name'])): ?>
    churchY = addLabelValue('Church Name', '<?= esc($booking["groom_church_name"]) ?>', 20, churchY, 150);
    churchY = addLabelValue('Senior Pastor', '<?= esc($booking["groom_senior_pastor"] ?? "Not provided") ?>', 20, churchY, 150);
    churchY = addLabelValue('Pastor Phone', '<?= esc($booking["groom_pastor_phone"] ?? "Not provided") ?>', 20, churchY, 150);
    <?php endif; ?>
    yPos = churchY + 5;
    
    // ========== BEST MAN & MATRON ==========
    yPos = addSectionHeader('BEST MAN & MATRON', yPos);
    checkNewPage(40);
    
    doc.setFillColor(245, 245, 250);
    doc.roundedRect(15, yPos - 3, 180, 40, 2, 2, 'F');
    doc.setDrawColor(200, 200, 220);
    doc.setLineWidth(0.3);
    doc.roundedRect(15, yPos - 3, 180, 40, 2, 2, 'D');
    
    let witnessY = yPos;
    doc.setTextColor(100, 1, 127);
    doc.setFontSize(10);
    doc.setFont(undefined, 'bold');
    doc.text('Best Man:', 20, witnessY);
    witnessY += 6;
    witnessY = addLabelValue('Full Name', '<?= esc($booking["witness1_name"] ?? "Not provided") ?>', 20, witnessY, 150);
    witnessY = addLabelValue('Phone', '<?= esc($booking["witness1_phone"] ?? "Not provided") ?>', 20, witnessY, 150);
    witnessY = addLabelValue('ID Number', '<?= esc($booking["witness1_id_number"] ?? "Not provided") ?>', 20, witnessY, 150);
    witnessY = addLabelValue('Relationship', '<?= esc($booking["witness1_relationship"] ?? "Not provided") ?>', 20, witnessY, 150);
    witnessY += 3;
    doc.setTextColor(100, 1, 127);
    doc.setFontSize(10);
    doc.setFont(undefined, 'bold');
    doc.text('Matron:', 20, witnessY);
    witnessY += 6;
    witnessY = addLabelValue('Full Name', '<?= esc($booking["witness2_name"] ?? "Not provided") ?>', 20, witnessY, 150);
    witnessY = addLabelValue('Phone', '<?= esc($booking["witness2_phone"] ?? "Not provided") ?>', 20, witnessY, 150);
    witnessY = addLabelValue('ID Number', '<?= esc($booking["witness2_id_number"] ?? "Not provided") ?>', 20, witnessY, 150);
    witnessY = addLabelValue('Relationship', '<?= esc($booking["witness2_relationship"] ?? "Not provided") ?>', 20, witnessY, 150);
    yPos = witnessY + 5;
    
    // ========== PAYMENT INFORMATION ==========
    yPos = addSectionHeader('PAYMENT INFORMATION', yPos);
    checkNewPage(40);
    
    <?php 
    $totalPaid = 0;
    $pendingAmount = 0;
    if (!empty($payments)) {
        foreach ($payments as $payment) {
            if ($payment['status'] === 'completed') {
                $totalPaid += $payment['amount'];
            } elseif ($payment['status'] === 'pending') {
                $pendingAmount += $payment['amount'];
            }
        }
    }
    $totalRequired = $paymentSummary['total_required'] ?? 600000;
    $remainingBalance = max(0, $totalRequired - $totalPaid - $pendingAmount);
    ?>
    
    const totalRequired = <?= $totalRequired ?>;
    const totalPaid = <?= $totalPaid ?>;
    const pendingAmount = <?= $pendingAmount ?>;
    const remainingBalance = <?= $remainingBalance ?>;
    
    doc.setFillColor(remainingBalance <= 0 ? 220 : 255, remainingBalance <= 0 ? 255 : 248, 220);
    doc.roundedRect(15, yPos - 3, 180, 35, 2, 2, 'F');
    doc.setDrawColor(remainingBalance <= 0 ? 40 : 255, remainingBalance <= 0 ? 167 : 193, remainingBalance <= 0 ? 69 : 7);
    doc.setLineWidth(0.5);
    doc.roundedRect(15, yPos - 3, 180, 35, 2, 2, 'D');
    
    doc.setTextColor(52, 73, 94);
    doc.setFontSize(10);
    doc.setFont(undefined, 'bold');
    doc.text('Total Required:', 20, yPos);
    doc.text('Amount Paid:', 20, yPos + 7);
    if (pendingAmount > 0) {
        doc.text('Pending Verification:', 20, yPos + 14);
    }
    doc.text('Remaining Balance:', 20, yPos + (pendingAmount > 0 ? 21 : 14));
    
    doc.setFont(undefined, 'normal');
    doc.text('UGX ' + totalRequired.toLocaleString(), 80, yPos);
    doc.text('UGX ' + totalPaid.toLocaleString(), 80, yPos + 7);
    if (pendingAmount > 0) {
        doc.setTextColor(255, 152, 0);
        doc.text('UGX ' + pendingAmount.toLocaleString(), 80, yPos + 14);
    }
    if (remainingBalance > 0) {
        doc.setTextColor(231, 76, 60);
    } else {
        doc.setTextColor(39, 174, 96);
    }
    doc.text('UGX ' + remainingBalance.toLocaleString(), 80, yPos + (pendingAmount > 0 ? 21 : 14));
    
    doc.setTextColor(52, 73, 94);
    doc.setFont(undefined, 'bold');
    doc.text('Status:', 120, yPos + 7);
    doc.setFont(undefined, 'normal');
    if (remainingBalance <= 0) {
        doc.setTextColor(39, 174, 96);
    } else {
        doc.setTextColor(230, 126, 34);
    }
    doc.text(remainingBalance <= 0 ? 'PAID IN FULL' : (totalPaid === 0 ? 'PENDING' : 'PARTIAL PAYMENT'), 140, yPos + 7);
    
    yPos += 40;
    
    // Payment History
    <?php if (!empty($payments)): ?>
    checkNewPage(30);
    doc.setTextColor(100, 1, 127);
    doc.setFontSize(11);
    doc.setFont(undefined, 'bold');
    doc.text('Payment History:', 20, yPos);
    yPos += 8;
    
    doc.setFillColor(250, 250, 250);
    doc.roundedRect(15, yPos - 3, 180, 8, 1, 1, 'F');
    doc.setDrawColor(200, 200, 200);
    doc.setLineWidth(0.2);
    doc.roundedRect(15, yPos - 3, 180, 8, 1, 1, 'D');
    
    doc.setTextColor(52, 73, 94);
    doc.setFontSize(8);
    doc.setFont(undefined, 'bold');
    doc.text('Date', 18, yPos);
    doc.text('Amount', 60, yPos);
    doc.text('Reference', 100, yPos);
    doc.text('Status', 160, yPos);
    yPos += 5;
    
    <?php foreach ($payments as $payment): ?>
    checkNewPage(8);
    doc.setFont(undefined, 'normal');
    doc.text('<?= date("M d, Y", strtotime($payment["payment_date"])) ?>', 18, yPos);
    doc.text('UGX <?= number_format($payment["amount"]) ?>', 60, yPos);
    doc.text('<?= esc($payment["transaction_reference"] ?? "N/A") ?>', 100, yPos);
    <?php if ($payment["status"] === "completed"): ?>
    doc.setTextColor(39, 174, 96);
    <?php elseif ($payment["status"] === "pending"): ?>
    doc.setTextColor(255, 152, 0);
    <?php else: ?>
    doc.setTextColor(231, 76, 60);
    <?php endif; ?>
    doc.text('<?= strtoupper($payment["status"]) ?>', 160, yPos);
    doc.setTextColor(52, 73, 94);
    yPos += 5;
    <?php endforeach; ?>
    yPos += 3;
    <?php endif; ?>
    
    // ========== UPLOADED DOCUMENTS ==========
    <?php if (!empty($uploadedDocuments)): ?>
    yPos = addSectionHeader('UPLOADED DOCUMENTS', yPos);
    checkNewPage(20);
    
    doc.setFillColor(250, 250, 255);
    doc.roundedRect(15, yPos - 3, 180, 15, 2, 2, 'F');
    doc.setDrawColor(200, 200, 255);
    doc.setLineWidth(0.3);
    doc.roundedRect(15, yPos - 3, 180, 15, 2, 2, 'D');
    
    doc.setTextColor(52, 73, 94);
    doc.setFontSize(9);
    <?php foreach ($uploadedDocuments as $index => $doc): ?>
    if (<?= $index ?> > 0) yPos += 5;
    checkNewPage(8);
    doc.text('• <?= esc($doc["name"]) ?>', 20, yPos);
    doc.setFontSize(8);
    doc.setTextColor(128, 128, 128);
    doc.text('  Uploaded: <?= !empty($doc["uploaded_at"]) ? date("M d, Y", strtotime($doc["uploaded_at"])) : "N/A" ?>', 25, yPos + 4);
    doc.setFontSize(9);
    doc.setTextColor(52, 73, 94);
    yPos += 6;
    <?php endforeach; ?>
    yPos += 3;
    <?php endif; ?>
    
    // ========== FOOTER ==========
    const pageCount = doc.internal.getNumberOfPages();
    for (let i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        doc.setFillColor(100, 1, 127);
        doc.rect(0, 280, 210, 15, 'F');
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(8);
        doc.text('Official Document | Watoto Church Wedding Department', 105, 286, { align: 'center' });
        doc.text('For inquiries: weddings@watotochurch.org | Page ' + i + ' of ' + pageCount, 105, 292, { align: 'center' });
    }
    
    // Save the PDF
    const fileName = 'Watoto_Wedding_Profile_' + bookingId + '_' + new Date().toISOString().split('T')[0] + '.pdf';
    doc.save(fileName);
}
</script>
<?= $this->endSection() ?>

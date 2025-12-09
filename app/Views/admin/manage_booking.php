<?= $this->extend('layouts/admin/admin') ?>

<?= $this->section('main_content') ?>
<div class="content-wrapper">
    <!-- <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Manage Wedding Booking</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/bookings') ?>">Bookings</a></li>
                        <li class="breadcrumb-item active">Manage Booking</li>
                    </ol>
                </div>
            </div>
        </div>
    </div> -->

    <section class="content">
        <div class="container-fluid">
            <!-- Booking Overview -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Booking Overview</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Couple:</strong></td>
                                    <td><?= esc($booking['bride_name']) ?> & <?= esc($booking['groom_name']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Campus:</strong></td>
                                    <td><?= esc($booking['campus_name']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Wedding Date:</strong></td>
                                    <td><?= date('l, M j, Y', strtotime($booking['wedding_date'])) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Time:</strong></td>
                                    <td><?= date('g:i A', strtotime($booking['wedding_time'])) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-success"><?= ucfirst($booking['status']) ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Contact:</strong></td>
                                    <td>
                                        <?= esc($booking['bride_phone']) ?><br>
                                        <small><?= esc($booking['bride_email']) ?></small>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Administrative Progress</h3>
                        </div>
                        <div class="card-body">
                            <div class="progress-timeline">
                                <div class="timeline-item <?= $booking['admin_counseling_status'] !== 'not-started' ? 'completed' : '' ?>">
                                    <i class="fas fa-comments"></i>
                                    <span>Counseling Management</span>
                                </div>
                                <div class="timeline-item <?= !empty($booking['admin_documents_checklist']) ? 'completed' : '' ?>">
                                    <i class="fas fa-file-alt"></i>
                                    <span>Document Review</span>
                                </div>
                                <div class="timeline-item <?= !empty($booking['admin_final_approval_date']) ? 'completed' : '' ?>">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Final Approval</span>
                                </div>
                                <div class="timeline-item <?= $booking['admin_preparation_status'] === 'completed' ? 'completed' : '' ?>">
                                    <i class="fas fa-church"></i>
                                    <span>Ceremony Ready</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Counseling Management Section -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-comments"></i> Counseling Management
                            </h3>
                        </div>
                        
                        <form action="<?= site_url('admin/booking/' . $booking['id'] . '/update-counseling') ?>" method="POST">
                            <?= csrf_field() ?>
                            
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="admin_counseling_status">Counseling Status</label>
                                            <select class="form-control" id="admin_counseling_status" name="admin_counseling_status" required>
                                                <option value="not-started" <?= $booking['admin_counseling_status'] === 'not-started' ? 'selected' : '' ?>>Not Started</option>
                                                <option value="scheduled" <?= $booking['admin_counseling_status'] === 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
                                                <option value="in-progress" <?= $booking['admin_counseling_status'] === 'in-progress' ? 'selected' : '' ?>>In Progress</option>
                                                <option value="completed" <?= $booking['admin_counseling_status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="admin_counseling_pastor_id">Assigned Pastor</label>
                                            <select class="form-control" id="admin_counseling_pastor_id" name="admin_counseling_pastor_id">
                                                <option value="">Select Pastor</option>
                                                <?php foreach ($pastors as $pastor): ?>
                                                    <option value="<?= $pastor['id'] ?>" <?= $booking['admin_counseling_pastor_id'] == $pastor['id'] ? 'selected' : '' ?>>
                                                        <?= esc($pastor['name']) ?> - <?= esc($pastor['specialization']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="admin_counseling_sessions">Number of Sessions</label>
                                            <input type="number" class="form-control" id="admin_counseling_sessions" 
                                                   name="admin_counseling_sessions" min="0" max="20" 
                                                   value="<?= $booking['admin_counseling_sessions'] ?? 0 ?>">
                                            <small class="form-text text-muted">Total number of counseling sessions completed</small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="admin_counseling_completion_date">Completion Date</label>
                                            <input type="date" class="form-control" id="admin_counseling_completion_date" 
                                                   name="admin_counseling_completion_date" 
                                                   value="<?= $booking['admin_counseling_completion_date'] ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="admin_counseling_notes">Counseling Notes</label>
                                    <textarea class="form-control" id="admin_counseling_notes" 
                                              name="admin_counseling_notes" rows="4" 
                                              placeholder="Add notes about the counseling progress, sessions attended, etc."><?= esc($booking['admin_counseling_notes']) ?></textarea>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Counseling Info
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Quick Actions</h3>
                        </div>
                        <div class="card-body">
                            <div class="btn-group-vertical w-100">
                                <button type="button" class="btn btn-info btn-sm" onclick="showDocumentChecklist()">
                                    <i class="fas fa-file-alt"></i> Document Checklist
                                </button>
                                <button type="button" class="btn btn-success btn-sm" onclick="showFinalApproval()" 
                                        <?= $booking['admin_counseling_status'] !== 'completed' ? 'disabled' : '' ?>>
                                    <i class="fas fa-check-circle"></i> Final Approval
                                </button>
                                <button type="button" class="btn btn-warning btn-sm" onclick="showCeremonyNotes()">
                                    <i class="fas fa-church"></i> Ceremony Notes
                                </button>
                                <a href="<?= site_url('admin/booking/' . $booking['id']) ?>" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-eye"></i> View Full Details
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Status Information -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Payment Status</h3>
                        </div>
                        <div class="card-body">
                            <?php 
                            $paymentInfo = $paymentStatus ?? [];
                            $isComplete = $paymentInfo['is_complete'] ?? false;
                            $paymentPercentage = $paymentInfo['payment_percentage'] ?? 0;
                            ?>
                            
                            <div class="payment-summary mb-3">
                                <?php if ($isComplete): ?>
                                    <div class="alert alert-success">
                                        <i class="fas fa-check-circle"></i>
                                        <strong>Payment Complete</strong>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Payment Incomplete (<?= $paymentPercentage ?>%)</strong>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Total Required:</strong></td>
                                    <td>UGX <?= number_format($paymentInfo['total_cost'] ?? 0, 0) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Total Paid:</strong></td>
                                    <td>UGX <?= number_format($paymentInfo['total_paid'] ?? 0, 0) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Remaining:</strong></td>
                                    <td>UGX <?= number_format($paymentInfo['pending_amount'] ?? 0, 0) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- User-Provided Information -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">User-Provided Info</h3>
                        </div>
                        <div class="card-body">
                            <small class="text-muted">Original counseling status from user:</small>
                            <p><strong><?= ucfirst(str_replace('-', ' ', $booking['premarital_counseling'] ?? 'Not specified')) ?></strong></p>
                            
                            <?php if (!empty($booking['counseling_pastor'])): ?>
                                <small class="text-muted">User mentioned pastor:</small>
                                <p><?= esc($booking['counseling_pastor']) ?></p>
                            <?php endif; ?>
                            
                            <?php if (!empty($booking['pastor_recommendation'])): ?>
                                <small class="text-muted">Pastor recommendation:</small>
                                <p><?= esc($booking['pastor_recommendation']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Document Checklist Modal -->
<div class="admin-modal" id="documentModal" style="display: none;">
    <div class="modal-overlay" onclick="closeModal('documentModal')"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="fas fa-file-alt"></i> Document Checklist</h3>
            <button type="button" class="modal-close" onclick="closeModal('documentModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="documentForm" action="<?= site_url('admin/booking/' . $booking['id'] . '/update-documents') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="modal-body">
                <div class="document-checklist">
                    <?php 
                    $documents = json_decode($booking['admin_documents_checklist'] ?? '{}', true);
                    $requiredDocs = [
                        'national_ids' => 'National IDs (Bride & Groom)',
                        'birth_certificates' => 'Birth Certificates',
                        'baptism_certificates' => 'Baptism Certificates',
                        'passport_photos' => 'Passport Photos',
                        'introduction_letter' => 'Introduction Letter',
                        'medical_certificates' => 'Medical Certificates',
                        'affidavit_single_status' => 'Affidavit of Single Status',
                        'witness_ids' => 'Best Man & Matron National IDs',
                        'church_membership' => 'Church Membership Proof'
                    ];
                    ?>
                    
                    <?php foreach ($requiredDocs as $key => $label): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="documents[<?= $key ?>]" value="1" 
                                   id="doc_<?= $key ?>"
                                   <?= isset($documents[$key]) && $documents[$key] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="doc_<?= $key ?>">
                                <?= $label ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('documentModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Documents
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Final Approval Modal -->
<div class="admin-modal" id="approvalModal" style="display: none;">
    <div class="modal-overlay" onclick="closeModal('approvalModal')"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="fas fa-check-circle"></i> Final Approval</h3>
            <button type="button" class="modal-close" onclick="closeModal('approvalModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="approvalForm" action="<?= site_url('admin/booking/' . $booking['id'] . '/final-approval') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h5><i class="fas fa-info-circle"></i> Final Approval Checklist</h5>
                    <ul class="mb-0">
                        <li>Counseling completed successfully</li>
                        <li>All required documents verified</li>
                        <li>Pastor assigned and confirmed</li>
                        <li>Venue and time confirmed</li>
                    </ul>
                </div>
                
                <div class="form-group">
                    <label for="admin_preparation_status">Preparation Status</label>
                    <select class="form-control" name="admin_preparation_status" required>
                        <option value="pending" <?= $booking['admin_preparation_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="in-progress" <?= $booking['admin_preparation_status'] === 'in-progress' ? 'selected' : '' ?>>In Progress</option>
                        <option value="ready" <?= $booking['admin_preparation_status'] === 'ready' ? 'selected' : '' ?>>Ready for Ceremony</option>
                        <option value="completed" <?= $booking['admin_preparation_status'] === 'completed' ? 'selected' : '' ?>>Ceremony Completed</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('approvalModal')">Cancel</button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> Grant Final Approval
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Ceremony Notes Modal -->
<div class="admin-modal" id="ceremonyModal" style="display: none;">
    <div class="modal-overlay" onclick="closeModal('ceremonyModal')"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="fas fa-church"></i> Ceremony Notes</h3>
            <button type="button" class="modal-close" onclick="closeModal('ceremonyModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="ceremonyForm" action="<?= site_url('admin/booking/' . $booking['id'] . '/update-ceremony') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="admin_ceremony_notes">Ceremony Notes & Instructions</label>
                    <textarea class="form-control" name="admin_ceremony_notes" rows="6" 
                              placeholder="Add any special instructions for the ceremony, music preferences, seating arrangements, etc."><?= esc($booking['admin_ceremony_notes']) ?></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('ceremonyModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Notes
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.progress-timeline {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.timeline-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    border-radius: 6px;
    background: #f8f9fa;
    border-left: 4px solid #dee2e6;
    transition: all 0.3s ease;
}

.timeline-item.completed {
    background: #e8f5e8;
    border-left-color: #28a745;
}

.timeline-item.completed i {
    color: #28a745;
}

.timeline-item i {
    width: 20px;
    text-align: center;
    color: #6c757d;
}

.document-checklist .form-check {
    margin-bottom: 10px;
    padding: 8px;
    border-radius: 4px;
    background: #f8f9fa;
}

.document-checklist .form-check:hover {
    background: #e9ecef;
}

.btn-group-vertical .btn {
    margin-bottom: 5px;
}
</style>

<script>
function showDocumentChecklist() {
    showModal('documentModal');
}

function showFinalApproval() {
    showModal('approvalModal');
}

function showCeremonyNotes() {
    showModal('ceremonyModal');
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

// Auto-enable/disable completion date based on status
document.getElementById('admin_counseling_status').addEventListener('change', function() {
    const completionDate = document.getElementById('admin_counseling_completion_date');
    if (this.value === 'completed') {
        completionDate.required = true;
        if (!completionDate.value) {
            completionDate.value = new Date().toISOString().split('T')[0];
        }
    } else {
        completionDate.required = false;
    }
});
</script>
<?= $this->endSection() ?>

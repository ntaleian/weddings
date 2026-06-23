<!-- Payment Pending Alert -->
<?php if (isset($paymentInfo) && $paymentInfo && !$paymentInfo['isFullyPaid'] && ($hasSubmittedApplication ?? false)): ?>
<div class="payment-alert" style="background: #c0392b; color: white; padding: 25px 30px; border-radius: 16px; margin-bottom: 30px; box-shadow: 0 4px 20px rgba(192, 57, 43, 0.35); display: flex; align-items: center; justify-content: space-between; gap: 20px; animation: pulse 2s infinite;">
    <div style="flex: 1;">
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
            <div style="width: 50px; height: 50px; background: rgba(255, 255, 255, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div>
                <h3 style="margin: 0; font-size: 1.4rem; font-weight: 700;">Payment Required</h3>
                <p style="margin: 5px 0 0 0; opacity: 0.95; font-size: 1rem;">
                    <?php if ($paymentInfo['status'] === 'pending_verification'): ?>
                        Your payment is pending verification. Once verified, your application will proceed.
                    <?php elseif ($paymentInfo['status'] === 'partial'): ?>
                        You have made a partial payment. Please complete your payment to proceed.
                    <?php else: ?>
                        Payment is required to proceed with your application approval.
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <div style="display: flex; gap: 30px; margin-top: 15px; flex-wrap: wrap;">
            <div>
                <span style="opacity: 0.9; font-size: 0.9rem;">Total Amount:</span>
                <strong style="font-size: 1.1rem; margin-left: 8px;">UGX <?= number_format($paymentInfo['totalCost'], 0) ?></strong>
            </div>
            <?php if ($paymentInfo['totalPaid'] > 0): ?>
            <div>
                <span style="opacity: 0.9; font-size: 0.9rem;">Amount Paid:</span>
                <strong style="font-size: 1.1rem; margin-left: 8px; color: #78BE20;">UGX <?= number_format($paymentInfo['totalPaid'], 0) ?></strong>
            </div>
            <?php endif; ?>
            <?php if ($paymentInfo['pendingAmount'] > 0): ?>
            <div>
                <span style="opacity: 0.9; font-size: 0.9rem;">Pending Verification:</span>
                <strong style="font-size: 1.1rem; margin-left: 8px; color: #f1c40f;">UGX <?= number_format($paymentInfo['pendingAmount'], 0) ?></strong>
            </div>
            <?php endif; ?>
            <div>
                <span style="opacity: 0.9; font-size: 0.9rem;">Remaining Balance:</span>
                <strong style="font-size: 1.2rem; margin-left: 8px;">UGX <?= number_format($paymentInfo['remainingBalance'], 0) ?></strong>
            </div>
        </div>
    </div>
    <div>
        <a href="<?= site_url('dashboard/payment') ?>" class="btn" style="background: white; color: #008C15; padding: 12px 30px; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-block; transition: all 0.3s ease; white-space: nowrap; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);">
            <i class="fas fa-credit-card" style="margin-right: 8px;"></i>
            <?= $paymentInfo['totalPaid'] > 0 ? 'Complete Payment' : 'Make Payment' ?>
        </a>
    </div>
</div>
<style>
@keyframes pulse {
    0%, 100% {
        box-shadow: 0 4px 20px rgba(192, 57, 43, 0.35);
    }
    50% {
        box-shadow: 0 4px 28px rgba(192, 57, 43, 0.5);
    }
}
.payment-alert .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
}
@media (max-width: 768px) {
    .payment-alert {
        flex-direction: column;
        text-align: center;
    }
    .payment-alert > div:first-child {
        width: 100%;
    }
    .payment-alert .btn {
        width: 100%;
    }
}
</style>
<?php endif; ?>

<?php
$hasSubmitted = (bool) ($hasSubmittedApplication ?? false);
$hasDraft = (bool) ($hasDraftApplication ?? false);
$currentStep = isset($currentStep) ? (int) $currentStep : 1;
$progress = isset($progress) ? (float) $progress : 0;
$bookings = $bookings ?? $userBookings ?? [];
$applicationStepLabels = $applicationStepLabels ?? [
    1 => 'Venue & Date',
    2 => 'Personal Details',
    3 => 'Additional Info',
    4 => 'Review & Submit',
];
$currentStepLabel = $applicationStepLabels[$currentStep] ?? $applicationStepLabels[1];
$resumeApplicationUrl = $resumeApplicationUrl ?? site_url('dashboard/application');
$draftSummary = $draftSummary ?? [];
?>

<!-- Welcome Section -->
<div class="welcome-section">
    <div class="welcome-content">
        <h1>Welcome Back, <?= esc($user['first_name'] ?? 'User') ?>!</h1>
        <?php if ($hasSubmitted): ?>
            <p>Your wedding application has been submitted. Track the review status and next steps from here.</p>
            <div class="status-badge status-<?= $applicationStatus ?? 'pending' ?>">
                Status: <?= ucfirst($applicationStatus ?? 'Pending') ?>
            </div>
        <?php elseif ($hasDraft): ?>
            <p>Your application is saved. Resume from step <?= esc((string) $currentStep) ?>: <?= esc($currentStepLabel) ?>.</p>
        <?php else: ?>
            <p>Start your wedding application, choose a venue and date, then save your progress as you go.</p>
        <?php endif; ?>
    </div>
    <div class="welcome-actions">
        <?php if ($hasSubmitted): ?>
            <a href="<?= site_url('dashboard/application') ?>" class="btn btn-secondary">
                <i class="fas fa-eye"></i>
                View Application
            </a>
        <?php elseif ($hasDraft): ?>
            <a href="<?= esc($resumeApplicationUrl) ?>" class="btn btn-primary">
                <i class="fas fa-play"></i>
                Resume Application
            </a>
        <?php else: ?>
            <a href="<?= site_url('dashboard/application') ?>" class="btn btn-primary">
                <i class="fas fa-play"></i>
                Start Application
            </a>
        <?php endif; ?>
        <a href="<?= site_url('dashboard/download-checklist') ?>" class="btn btn-secondary">
            <i class="fas fa-download"></i>
            Download Checklist
        </a>
    </div>
</div>

<?php if (!$hasSubmitted && $hasDraft): ?>
<div class="resume-summary">
    <div class="resume-summary__header">
        <div>
            <div class="resume-summary__eyebrow">Saved application</div>
            <h3>Continue from <?= esc($draftSummary['step_label'] ?? $currentStepLabel) ?></h3>
            <p>
                Your answers are saved as a draft. Continue where you stopped, review the details,
                and submit when every section is complete.
            </p>
        </div>
        <a href="<?= esc($resumeApplicationUrl) ?>" class="btn btn-primary">
            <i class="fas fa-arrow-right"></i>
            Resume
        </a>
    </div>
    <div class="resume-summary__details">
        <div class="resume-detail">
            <span>Current step</span>
            <strong>Step <?= esc((string) $currentStep) ?> of 4</strong>
        </div>
        <div class="resume-detail">
            <span>Venue</span>
            <strong><?= esc($draftSummary['campus_name'] ?? 'Not selected yet') ?></strong>
        </div>
        <div class="resume-detail">
            <span>Wedding date</span>
            <strong><?= esc($draftSummary['wedding_date'] ?? 'Not selected yet') ?></strong>
        </div>
        <div class="resume-detail">
            <span>Time slot</span>
            <strong><?= esc($draftSummary['wedding_time'] ?? 'Not selected yet') ?></strong>
        </div>
        <div class="resume-detail">
            <span>Last saved</span>
            <strong><?= esc($draftSummary['last_updated'] ?? 'Recently') ?></strong>
        </div>
    </div>
</div>
<?php elseif (!$hasSubmitted): ?>
<div class="resume-summary empty">
    <div class="resume-summary__header">
        <div>
            <div class="resume-summary__eyebrow">No application yet</div>
            <h3>Start with your venue and date</h3>
            <p>
                Begin the application by choosing a campus, date, and time slot. The form will save
                your progress automatically once you start filling it in.
            </p>
        </div>
        <a href="<?= site_url('dashboard/application') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Start
        </a>
    </div>
</div>
<?php endif; ?>

<style>
.status-badge {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-top: 10px;
}

.status-pending {
    background-color: #FBD110;
    color: #1a1a1a;
}

.status-approved {
    background-color: #008C15;
    color: white;
}

.status-rejected {
    background-color: #e74c3c;
    color: white;
}

.status-under-review {
    background-color: #3498db;
    color: white;
}

.resume-summary {
    background: white;
    border: 1px solid #e9ecef;
    border-left: 5px solid var(--primary-color);
    border-radius: 10px;
    padding: 22px;
    margin-bottom: 24px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
}

.resume-summary.empty {
    border-left-color: #6c757d;
}

.resume-summary__header {
    display: flex;
    justify-content: space-between;
    gap: 18px;
    align-items: flex-start;
    margin-bottom: 18px;
}

.resume-summary__eyebrow {
    color: var(--primary-color);
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    margin-bottom: 6px;
    text-transform: uppercase;
}

.resume-summary h3 {
    color: #243124;
    font-size: 1.18rem;
    margin: 0 0 6px;
}

.resume-summary p {
    color: #5f6b63;
    line-height: 1.5;
    margin: 0;
}

.resume-summary__details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
}

.resume-detail {
    background: #f8f9fa;
    border: 1px solid #edf0f2;
    border-radius: 8px;
    padding: 12px;
}

.resume-detail span {
    color: #6c757d;
    display: block;
    font-size: 0.78rem;
    margin-bottom: 4px;
}

.resume-detail strong {
    color: #243124;
    font-size: 0.95rem;
}

.resume-summary__actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 18px;
}

/* Compact Progress Steps */
.progress-overview {
    margin-bottom: 20px;
}

.progress-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 12px;
    margin-bottom: 15px;
}

.progress-step {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 12px;
    text-align: center;
    transition: all 0.3s ease;
    min-height: 80px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.progress-step.completed {
    border-color: #008C15;
    background: rgba(0, 140, 21, 0.06);
}

.progress-step.current {
    border-color: var(--primary-color);
    background: rgba(0, 140, 21, 0.08);
    box-shadow: 0 2px 8px rgba(0, 140, 21, 0.15);
}

.step-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 6px;
}

.progress-step.completed .step-icon {
    background: #008C15;
    color: white;
}

.progress-step.current .step-icon {
    background: var(--primary-color);
    color: white;
}

.step-content h5 {
    font-size: 0.9rem;
    margin: 0 0 2px 0;
    color: #333;
    font-weight: 600;
}

.step-content small {
    font-size: 0.75rem;
    color: #6c757d;
    line-height: 1.2;
}

.progress-bar {
    height: 4px;
    background: #e9ecef;
    border-radius: 2px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: var(--primary-color);
    transition: width 0.3s ease;
}

/* Compact Quick Actions */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.action-card {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 16px;
    text-align: center;
    transition: all 0.3s ease;
    min-height: 120px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
}

.action-card:hover {
    border-color: var(--primary-color);
    box-shadow: 0 2px 8px rgba(0, 140, 21, 0.12);
    transform: translateY(-1px);
}

.card-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(0, 140, 21, 0.1);
    color: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    margin-bottom: 8px;
}

.card-content h4 {
    font-size: 0.95rem;
    margin: 0 0 4px 0;
    color: #333;
    font-weight: 600;
}

.card-content p {
    font-size: 0.8rem;
    color: #6c757d;
    margin: 0 0 10px 0;
    line-height: 1.3;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 0.8rem;
}

@media (max-width: 768px) {
    .welcome-section {
        flex-direction: column;
        text-align: center;
        padding: 30px 20px;
        gap: 24px;
        align-items: center;
    }
    
    .welcome-content h1 {
        font-size: 1.5rem;
        margin-bottom: 12px;
    }
    
    .welcome-content p {
        font-size: 1rem;
    }
    
    .welcome-actions {
        flex-direction: column;
        width: 100%;
        gap: 12px;
    }
    
    .welcome-actions .btn {
        width: 100%;
        justify-content: center;
    }
    
    .progress-overview {
        padding: 20px;
        margin-bottom: 24px;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 24px;
    }
    
    .section-header h2 {
        font-size: 1.4rem;
    }
    
    .progress-steps {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .progress-step {
        min-height: auto;
        padding: 14px;
    }
    
    .step-icon {
        width: 32px;
        height: 32px;
        font-size: 0.85rem;
    }
    
    .step-content h5 {
        font-size: 0.9rem;
    }
    
    .step-content small {
        font-size: 0.7rem;
    }
    
    .quick-actions {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .action-card {
        min-height: auto;
        padding: 20px;
    }
    
    .card-icon {
        width: 45px;
        height: 45px;
        font-size: 1.1rem;
        margin-bottom: 12px;
    }
    
    .card-content h4 {
        font-size: 0.95rem;
    }
    
    .card-content p {
        font-size: 0.8rem;
    }
    
    .payment-alert {
        flex-direction: column;
        padding: 20px;
        gap: 16px;
    }
    
    .payment-alert > div:first-child {
        width: 100%;
    }
    
    .payment-alert > div:first-child > div:first-child {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .payment-alert .btn {
        width: 100%;
    }

    .resume-summary {
        padding: 18px;
    }

    .resume-summary__header {
        flex-direction: column;
    }

    .resume-summary__header .btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .welcome-section {
        padding: 24px 16px;
    }
    
    .welcome-content h1 {
        font-size: 1.3rem;
    }
    
    .progress-overview {
        padding: 16px;
    }
    
    .section-header h2 {
        font-size: 1.2rem;
    }
    
    .progress-step {
        padding: 12px;
    }
    
    .step-icon {
        width: 28px;
        height: 28px;
        font-size: 0.75rem;
    }
    
    .action-card {
        padding: 16px;
    }
    
    .card-icon {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .payment-alert {
        padding: 16px;
    }
    
    .payment-alert h3 {
        font-size: 1.2rem;
    }
    
    .payment-alert > div:first-child > div:last-child {
        flex-direction: column;
        gap: 12px;
    }
}
</style>

<?php
// Set default values to prevent undefined variable errors
$currentStep = isset($currentStep) ? $currentStep : 1;
$progress = isset($progress) ? $progress : 0;
?>

<!-- Welcome Section -->

<!-- Application Progress -->
<div class="progress-overview">
    <div class="section-header">
        <h2>Application Progress</h2>
        <?php if ($hasSubmitted): ?>
            <span class="progress-percentage submitted">Application Submitted</span>
        <?php else: ?>
            <span class="progress-percentage"><?= $progress ?? '0' ?>% Complete</span>
        <?php endif; ?>
    </div>
    <div class="progress-steps">
        <div class="progress-step <?= ($currentStep === 1 && !$hasSubmitted) ? 'current' : '' ?> <?= ($currentStep > 1 || $hasSubmitted) ? 'completed' : '' ?>">
            <div class="step-icon"><?= ($currentStep > 1) ? '✓' : '1' ?></div>
            <div class="step-content">
                <h5>Venue & Date</h5>
                <small>Campus and wedding date</small>
            </div>
        </div>
        <div class="progress-step <?= ($currentStep === 2 && !$hasSubmitted) ? 'current' : '' ?> <?= ($currentStep > 2 || $hasSubmitted) ? 'completed' : '' ?>">
            <div class="step-icon"><?= ($currentStep > 2) ? '✓' : '2' ?></div>
            <div class="step-content">
                <h5>Personal Details</h5>
                <small>Bride & Groom info</small>
            </div>
        </div>
        <div class="progress-step <?= ($currentStep === 3 && !$hasSubmitted) ? 'current' : '' ?> <?= ($currentStep > 3 || $hasSubmitted) ? 'completed' : '' ?>">
            <div class="step-icon"><?= ($currentStep > 3) ? '✓' : '3' ?></div>
            <div class="step-content">
                <h5>Additional Info</h5>
                <small>Family & witnesses</small>
            </div>
        </div>
        <div class="progress-step <?= ($currentStep === 4 && !$hasSubmitted) ? 'current' : '' ?> <?= $hasSubmitted ? 'completed' : '' ?>">
            <div class="step-icon"><?= $hasSubmitted ? '✓' : '4' ?></div>
            <div class="step-content">
                <h5>Review & Submit</h5>
                <small>Final review</small>
            </div>
        </div>
        <?php if ($hasSubmitted): ?>
        <div class="progress-step completed submitted-step">
            <div class="step-icon">✓</div>
            <div class="step-content">
                <h5>Submitted</h5>
                <small>Under review</small>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div class="progress-bar">
        <div class="progress-fill" style="width: <?= $progress ?? '0' ?>%"></div>
    </div>
</div>

<style>
.progress-percentage.submitted {
    background: #008C15;
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
}

.submitted-step {
    border-color: #008C15 !important;
    background: rgba(39, 174, 96, 0.1) !important;
}

.submitted-step .step-icon {
    background: #008C15 !important;
    color: white !important;
}

.submitted-step .step-content h4 {
    color: #008C15 !important;
}
</style>

<!-- Quick Actions -->
<?php if ($hasSubmitted): ?>
<div class="quick-actions">
    <div class="action-card">
        <div class="card-icon">
            <i class="fas fa-eye"></i>
        </div>
        <div class="card-content">
            <h4>View Application</h4>
            <p>Review your submitted application</p>
            <a href="<?= site_url('dashboard/application') ?>" class="btn btn-outline btn-sm">View Details</a>
        </div>
    </div>
    <div class="action-card">
        <div class="card-icon">
            <i class="fas fa-credit-card"></i>
        </div>
        <div class="card-content">
            <h4>Payment Status</h4>
            <p>Track payments and balances</p>
            <a href="<?= site_url('dashboard/payment') ?>" class="btn btn-outline btn-sm">Open Payments</a>
        </div>
    </div>
    <div class="action-card">
        <div class="card-icon">
            <i class="fas fa-comments"></i>
        </div>
        <div class="card-content">
            <h4>Contact Coordinator</h4>
            <p>Get help with your status</p>
            <a href="<?= site_url('dashboard/messages') ?>" class="btn btn-outline btn-sm">Message</a>
        </div>
    </div>
</div>
<?php elseif ($hasDraft): ?>
<div class="quick-actions">
    <div class="action-card">
        <div class="card-icon">
            <i class="fas fa-pen"></i>
        </div>
        <div class="card-content">
            <h4>Resume Application</h4>
            <p>Continue from <?= esc($draftSummary['step_label'] ?? $currentStepLabel) ?></p>
            <a href="<?= esc($resumeApplicationUrl) ?>" class="btn btn-outline btn-sm">Resume</a>
        </div>
    </div>
    <div class="action-card">
        <div class="card-icon">
            <i class="fas fa-tasks"></i>
        </div>
        <div class="card-content">
            <h4>Checklist</h4>
            <p>Review what you will need</p>
            <a href="<?= site_url('dashboard/download-checklist') ?>" class="btn btn-outline btn-sm">Download</a>
        </div>
    </div>
    <div class="action-card">
        <div class="card-icon">
            <i class="fas fa-comments"></i>
        </div>
        <div class="card-content">
            <h4>Contact Coordinator</h4>
            <p>Get help with application</p>
            <a href="<?= site_url('dashboard/messages') ?>" class="btn btn-outline btn-sm">Message</a>
        </div>
    </div>
</div>
<?php else: ?>
<div class="quick-actions">
    <div class="action-card">
        <div class="card-icon">
            <i class="fas fa-calendar-plus"></i>
        </div>
        <div class="card-content">
            <h4>Start Application</h4>
            <p>Choose your venue, date, and time</p>
            <a href="<?= site_url('dashboard/application') ?>" class="btn btn-outline btn-sm">Start</a>
        </div>
    </div>
    <div class="action-card">
        <div class="card-icon">
            <i class="fas fa-file-download"></i>
        </div>
        <div class="card-content">
            <h4>Wedding Checklist</h4>
            <p>See the documents and steps ahead</p>
            <a href="<?= site_url('dashboard/download-checklist') ?>" class="btn btn-outline btn-sm">Download</a>
        </div>
    </div>
    <div class="action-card">
        <div class="card-icon">
            <i class="fas fa-question-circle"></i>
        </div>
        <div class="card-content">
            <h4>Need Help?</h4>
            <p>Contact the wedding coordination team</p>
            <a href="<?= site_url('dashboard/messages') ?>" class="btn btn-outline btn-sm">Message</a>
        </div>
    </div>
</div>
<?php endif; ?>

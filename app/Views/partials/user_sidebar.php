<!-- Sidebar -->
        <?php
            $paymentStatusValue = $paymentStatus ?? 'no_booking';
            $hasSubmittedBooking = $paymentStatusValue !== 'no_booking';
            $request = service('request');
            $stepQuery = (string) ($request->getGet('step') ?? '');
            $documentsUrl = $hasSubmittedBooking
                ? site_url('dashboard/documents')
                : site_url('dashboard/application') . '?step=4';
            $paymentUrl = $hasSubmittedBooking
                ? site_url('dashboard/payment')
                : site_url('dashboard/application') . '?step=5';
            $documentsActive = uri_string() === 'dashboard/documents'
                || (uri_string() === 'dashboard/application' && $stepQuery === '4');
            $paymentActive = uri_string() === 'dashboard/payment'
                || (uri_string() === 'dashboard/application' && $stepQuery === '5');
        ?>
        <aside class="dashboard-sidebar">
            <div class="sidebar-header">
                <h3>Your Wedding Journey</h3>
            </div>
            <nav class="sidebar-nav">
                <a href="<?= site_url('dashboard') ?>" class="nav-item <?= (current_url() === site_url('dashboard') || uri_string() === 'dashboard') ? 'active' : '' ?>">
                    <i class="fas fa-home"></i>
                    <span>Overview</span>
                </a>
                <a href="<?= site_url('dashboard/application') ?>" class="nav-item <?= (uri_string() === 'dashboard/application' && ! in_array($stepQuery, ['4', '5'], true)) ? 'active' : '' ?>">
                    <i class="fas fa-file-contract"></i>
                    <span>Application</span>
                    <!-- <span class="nav-badge">In Progress</span> -->
                </a>
                <a href="<?= esc($documentsUrl) ?>" class="nav-item <?= $documentsActive ? 'active' : '' ?>">
                    <i class="fas fa-file-alt"></i>
                    <span>Step 4: Documents</span>
                </a>
                <a href="<?= esc($paymentUrl) ?>" class="nav-item <?= $paymentActive ? 'active' : '' ?>">
                    <i class="fas fa-credit-card"></i>
                    <span>Step 5: Payment</span>
                    <?php if (isset($paymentStatus)): ?>
                        <?php if ($paymentStatus === 'required'): ?>
                            <span class="nav-badge nav-badge-danger">Required</span>
                        <?php elseif ($paymentStatus === 'pending_verification'): ?>
                            <span class="nav-badge nav-badge-warning">Pending</span>
                        <?php elseif ($paymentStatus === 'partial'): ?>
                            <span class="nav-badge nav-badge-info">Partial</span>
                        <?php elseif ($paymentStatus === 'completed'): ?>
                            <span class="nav-badge nav-badge-success">Complete</span>
                        <?php endif; ?>
                    <?php elseif (isset($hasUnpaidFees) && $hasUnpaidFees): ?>
                        <span class="nav-badge nav-badge-danger">Required</span>
                    <?php endif; ?>
                </a>
                <a href="<?= site_url('dashboard/profile') ?>" class="nav-item <?= (uri_string() === 'dashboard/profile') ? 'active' : '' ?>">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
            </nav>
        </aside>

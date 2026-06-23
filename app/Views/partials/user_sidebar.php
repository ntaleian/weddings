<!-- Sidebar -->
        <?php
            $paymentStatusValue = $paymentStatus ?? 'no_booking';
            $documentsUrl = site_url('dashboard/documents');
            $paymentUrl = site_url('dashboard/payment');
            $documentSummary = $documentSummary ?? [];
            $documentsUploaded = (int) ($documentSummary['uploaded'] ?? 0);
            $documentsTotal = (int) ($documentSummary['total'] ?? 0);
            $documentsBadgeText = $documentsTotal > 0
                ? $documentsUploaded . '/' . $documentsTotal
                : 'Pending';
            $documentsBadgeClass = ! empty($documentSummary['isComplete'])
                ? 'nav-badge-success'
                : ($documentsUploaded > 0 ? 'nav-badge-info' : 'nav-badge-warning');
            $paymentBadgeMap = [
                'no_booking' => ['text' => 'Not started', 'class' => 'nav-badge-warning'],
                'required' => ['text' => 'Required', 'class' => 'nav-badge-danger'],
                'pending_verification' => ['text' => 'Pending', 'class' => 'nav-badge-warning'],
                'partial' => ['text' => 'Partial', 'class' => 'nav-badge-info'],
                'completed' => ['text' => 'Complete', 'class' => 'nav-badge-success'],
            ];
            $paymentBadge = $paymentBadgeMap[$paymentStatusValue] ?? $paymentBadgeMap['no_booking'];
            $documentsActive = uri_string() === 'dashboard/documents';
            $paymentActive = uri_string() === 'dashboard/payment';
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
                <a href="<?= site_url('dashboard/application') ?>" class="nav-item <?= uri_string() === 'dashboard/application' ? 'active' : '' ?>">
                    <i class="fas fa-file-contract"></i>
                    <span>Application</span>
                    <!-- <span class="nav-badge">In Progress</span> -->
                </a>
                <a href="<?= esc($documentsUrl) ?>" class="nav-item <?= $documentsActive ? 'active' : '' ?>">
                    <i class="fas fa-file-alt"></i>
                    <span>Documents uploaded</span>
                    <span class="nav-badge <?= esc($documentsBadgeClass) ?>"><?= esc($documentsBadgeText) ?></span>
                </a>
                <a href="<?= esc($paymentUrl) ?>" class="nav-item <?= $paymentActive ? 'active' : '' ?>">
                    <i class="fas fa-credit-card"></i>
                    <span>Payment status</span>
                    <span class="nav-badge <?= esc($paymentBadge['class']) ?>"><?= esc($paymentBadge['text']) ?></span>
                </a>
                <a href="<?= site_url('dashboard/profile') ?>" class="nav-item <?= (uri_string() === 'dashboard/profile') ? 'active' : '' ?>">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
            </nav>
        </aside>

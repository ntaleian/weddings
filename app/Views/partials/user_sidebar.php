<!-- Sidebar -->
        <aside class="dashboard-sidebar">
            <div class="sidebar-header">
                <h3>Your Wedding Journey</h3>
            </div>
            <nav class="sidebar-nav">
                <a href="<?= site_url('dashboard') ?>" class="nav-item <?= (current_url() === site_url('dashboard') || uri_string() === 'dashboard') ? 'active' : '' ?>">
                    <i class="fas fa-home"></i>
                    <span>Overview</span>
                </a>
                <a href="<?= site_url('dashboard/application') ?>" class="nav-item <?= (uri_string() === 'dashboard/application') ? 'active' : '' ?>">
                    <i class="fas fa-file-contract"></i>
                    <span>Application</span>
                    <!-- <span class="nav-badge">In Progress</span> -->
                </a>
                <a href="<?= site_url('dashboard/documents') ?>" class="nav-item <?= (uri_string() === 'dashboard/documents') ? 'active' : '' ?>">
                    <i class="fas fa-file-alt"></i>
                    <span>Documents</span>
                </a>
                <a href="<?= site_url('dashboard/payment') ?>" class="nav-item <?= (uri_string() === 'dashboard/payment') ? 'active' : '' ?>">
                    <i class="fas fa-credit-card"></i>
                    <span>Payment</span>
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
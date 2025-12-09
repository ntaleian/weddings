<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>
<div class="page-header">
    <h1 class="page-title">Dashboard Overview</h1>
    <p class="page-subtitle">Welcome back! Here's what's happening with wedding bookings today.</p>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        <h3 class="stat-value"><?= number_format($totalBookings ?? 0) ?></h3>
        <p class="stat-label">Total Bookings</p>
        <span class="stat-change positive">
            <i class="fas fa-arrow-up"></i> 12% from last month
        </span>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <h3 class="stat-value"><?= number_format($pendingBookings ?? 0) ?></h3>
        <p class="stat-label">Pending Approvals</p>
        <span class="stat-change">
            Requires attention
        </span>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <h3 class="stat-value"><?= number_format($approvedBookings ?? 0) ?></h3>
        <p class="stat-label">Approved Bookings</p>
        <span class="stat-change positive">
            <i class="fas fa-arrow-up"></i> 8% increase
        </span>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <h3 class="stat-value"><?= number_format($totalUsers ?? 0) ?></h3>
        <p class="stat-label">Registered Users</p>
        <span class="stat-change positive">
            <i class="fas fa-arrow-up"></i> 5 new today
        </span>
    </div>
</div>

<!-- Recent Activity -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Recent Bookings</h3>
        <div class="card-actions">
            <a href="<?= site_url('admin/bookings') ?>" class="btn btn-sm btn-secondary">View All</a>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($recentBookings)): ?>
            <div class="data-table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Couple</th>
                            <th>Campus</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentBookings as $booking): ?>
                        <tr>
                            <td>
                                <strong><?= esc($booking['bride_name'] ?? '') ?> & <?= esc($booking['groom_name'] ?? '') ?></strong>
                            </td>
                            <td><?= esc($booking['campus_name'] ?? 'Unknown') ?></td>
                            <td><?= isset($booking['wedding_date']) ? date('M j, Y', strtotime($booking['wedding_date'])) : 'Not set' ?></td>
                            <td>
                                <span class="badge badge-<?= $booking['status'] === 'pending' ? 'warning' : ($booking['status'] === 'approved' ? 'success' : 'secondary') ?>">
                                    <?= ucfirst($booking['status'] ?? 'unknown') ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?= site_url('admin/booking/' . $booking['id']) ?>" class="btn-action view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center text-muted">No recent bookings found.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Quick Actions -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Quick Actions</h3>
    </div>
    <div class="card-body">
        <div class="page-actions">
            <a href="<?= site_url('admin/bookings') ?>" class="btn btn-primary">
                <i class="fas fa-calendar-check"></i> Manage Bookings
            </a>
            <a href="<?= site_url('admin/campuses') ?>" class="btn btn-secondary">
                <i class="fas fa-building"></i> Manage Campuses
            </a>
            <a href="<?= site_url('admin/pastors') ?>" class="btn btn-secondary">
                <i class="fas fa-user-tie"></i> Manage Pastors
            </a>
            <a href="<?= site_url('admin/reports') ?>" class="btn btn-info">
                <i class="fas fa-chart-bar"></i> View Reports
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>


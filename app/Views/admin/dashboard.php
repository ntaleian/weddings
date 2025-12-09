
<?= $this->extend('layouts/admin/admin') ?>

<?= $this->section('main_content') ?>
<div class="content-wrapper">
    <section id="dashboard" class="content-section active">
        <!-- Page Header -->
        <div class="section-header">
            <h2>Dashboard Overview</h2>
            <div class="section-actions">
                <button class="btn btn-secondary">
                    <i class="fas fa-sync-alt"></i>
                    Refresh
                </button>
                <button class="btn btn-primary">
                    <i class="fas fa-download"></i>
                    Export Report
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <h3><?= number_format($totalBookings); ?></h3>
                    <p>Total Bookings</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon pending">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3><?= number_format($pendingBookings); ?></h3>
                    <p>Pending Approvals</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon venues">
                    <i class="fas fa-church"></i>
                </div>
                <div class="stat-info">
                    <h3><?= number_format($totalCampuses); ?></h3>
                    <p>Active Venues</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon users">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3><?= number_format($totalUsers); ?></h3>
                    <p>Registered Users</p>
                </div>
            </div>
        </div>

        <!-- Dashboard Content Grid -->
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-calendar-alt"></i>
                        <h3>Recent Bookings</h3>
                    </div>
                    <a href="<?= site_url('admin/bookings') ?>" class="view-all-btn">
                        <span>View All</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="card-content">
                    <?php if (empty($recentBookings)): ?>
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <p>No recent bookings found.</p>
                        </div>
                    <?php else: ?>
                        <div class="booking-list">
                            <?php foreach ($recentBookings as $booking): ?>
                            <div class="booking-item">
                                <div class="booking-info">
                                    <h4><?= esc($booking['bride_name'] ?? 'Unknown') ?> & <?= esc($booking['groom_name'] ?? 'Unknown') ?></h4>
                                    <p><?= esc($booking['campus_name'] ?? 'Unknown Campus') ?> • <?= isset($booking['wedding_date']) ? date('M j, Y', strtotime($booking['wedding_date'])) : 'Date TBD' ?></p>
                                </div>
                                <span class="status-badge <?= 'status-' . ($booking['status'] ?? 'pending') ?>"><?= ucfirst($booking['status'] ?? 'pending') ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-clock"></i>
                        <h3>Upcoming Events</h3>
                    </div>
                    <a href="#" class="view-all-btn">
                        <span>View Calendar</span>
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
                <div class="card-content">
                    <div class="event-list">
                        <div class="event-item">
                            <div class="event-date">
                                <span class="day">15</span>
                                <span class="month">Dec</span>
                            </div>
                            <div class="event-info">
                                <h4>Wedding Ceremony</h4>
                                <p>Sarah & John - Downtown Campus</p>
                                <span class="time">10:00 AM</span>
                            </div>
                        </div>
                        <div class="event-item">
                            <div class="event-date">
                                <span class="day">22</span>
                                <span class="month">Dec</span>
                            </div>
                            <div class="event-info">
                                <h4>Wedding Ceremony</h4>
                                <p>Grace & David - Ntinda Campus</p>
                                <span class="time">2:00 PM</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions-section">
            <div class="section-header">
                <h3>Quick Actions</h3>
            </div>
            <div class="quick-actions-grid">
                <a href="<?= site_url('admin/bookings') ?>" class="quick-action-card">
                    <div class="action-icon">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div class="action-info">
                        <h4>Manage Bookings</h4>
                        <p>View and approve wedding bookings</p>
                    </div>
                </a>
                <a href="<?= site_url('admin/users') ?>" class="quick-action-card">
                    <div class="action-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div class="action-info">
                        <h4>User Management</h4>
                        <p>Manage registered users</p>
                    </div>
                </a>
                <a href="#" class="quick-action-card">
                    <div class="action-icon">
                        <i class="fas fa-church"></i>
                    </div>
                    <div class="action-info">
                        <h4>Venue Settings</h4>
                        <p>Configure church venues</p>
                    </div>
                </a>
                <a href="#" class="quick-action-card">
                    <div class="action-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="action-info">
                        <h4>View Reports</h4>
                        <p>Analytics and statistics</p>
                    </div>
                </a>
            </div>
        </div>
    </section>
</div>

<style>
/* Dashboard Specific Styles */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    padding-bottom: 16px;
    border-bottom: 1px solid #e9ecef;
}

.section-header h2 {
    margin: 0;
    font-size: 28px;
    font-weight: 600;
    color: #212529;
}

.section-actions {
    display: flex;
    gap: 12px;
}

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

.stat-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.stat-icon.total {
    background: #495057;
}

.stat-icon.pending {
    background: #ffc107;
}

.stat-icon.venues {
    background: #17a2b8;
}

.stat-icon.users {
    background: #28a745;
}

.stat-info h3 {
    margin: 0 0 4px 0;
    font-size: 32px;
    font-weight: 700;
    color: #212529;
    line-height: 1;
}

.stat-info p {
    margin: 0;
    font-size: 14px;
    color: #6c757d;
    font-weight: 500;
}

/* Dashboard Grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

.dashboard-card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    overflow: hidden;
}

.card-header {
    padding: 20px 24px;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title {
    display: flex;
    align-items: center;
    gap: 12px;
}

.card-title i {
    color: #495057;
    font-size: 18px;
}

.card-title h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #212529;
}

.view-all-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #495057;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: color 0.2s ease;
}

.view-all-btn:hover {
    color: #212529;
    text-decoration: none;
}

.card-content {
    padding: 24px;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
}

.empty-state i {
    font-size: 48px;
    color: #dee2e6;
    margin-bottom: 16px;
}

.empty-state p {
    margin: 0;
    color: #6c757d;
    font-size: 16px;
}

.booking-list, .event-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.booking-item, .event-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.booking-info h4, .event-info h4 {
    margin: 0 0 2px 0;
    font-size: 14px;
    font-weight: 600;
    color: #212529;
}

.booking-info p, .event-info p {
    margin: 0;
    font-size: 12px;
    color: #6c757d;
}

.event-date {
    text-align: center;
    background: #495057;
    color: white;
    border-radius: 6px;
    padding: 8px 10px;
    min-width: 50px;
}

.event-date .day {
    display: block;
    font-size: 18px;
    font-weight: 700;
    line-height: 1;
}

.event-date .month {
    display: block;
    font-size: 10px;
    margin-top: 2px;
    opacity: 0.9;
}

.event-info {
    flex: 1;
    margin-left: 12px;
}

.event-info .time {
    font-size: 11px;
    color: #495057;
    font-weight: 500;
}

.status-badge {
    padding: 3px 10px;
    border-radius: 16px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.status-approved {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status-completed {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

/* Quick Actions */
.quick-actions-section {
    margin-top: 32px;
}

.quick-actions-section .section-header {
    margin-bottom: 24px;
}

.quick-actions-section .section-header h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    color: #212529;
}

.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

.quick-action-card {
    background: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
}

.quick-action-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    text-decoration: none;
    color: inherit;
}

.action-icon {
    width: 50px;
    height: 50px;
    background: #f8f9fa;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: #495057;
}

.action-info h4 {
    margin: 0 0 4px 0;
    font-size: 16px;
    font-weight: 600;
    color: #212529;
}

.action-info p {
    margin: 0;
    font-size: 14px;
    color: #6c757d;
}

/* Responsive Design */
@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .quick-actions-grid {
        grid-template-columns: 1fr;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .section-actions {
        width: 100%;
        justify-content: flex-end;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-card {
        padding: 20px;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
    
    .stat-info h3 {
        font-size: 28px;
    }
}
</style>
<?= $this->endSection() ?>
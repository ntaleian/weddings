<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>

<?php
// Quick Debug Test - Remove this block after debugging
$showDebug = false; // Set to true to see variable values
if ($showDebug) {
    echo '<div style="background: #fff3cd; border: 2px solid #ffc107; padding: 1rem; margin-bottom: 1rem; border-radius: 4px;">';
    echo '<strong>Variable Check:</strong><br>';
    echo 'totalBookings: ' . (isset($totalBookings) ? $totalBookings : 'NOT SET') . '<br>';
    echo 'pendingBookings: ' . (isset($pendingBookings) ? $pendingBookings : 'NOT SET') . '<br>';
    echo 'approvedBookings: ' . (isset($approvedBookings) ? $approvedBookings : 'NOT SET') . '<br>';
    echo 'totalUsers: ' . (isset($totalUsers) ? $totalUsers : 'NOT SET') . '<br>';
    echo '</div>';
}
?>

<?= $this->include('admin_template/partials/page_header', [
    'title' => 'Dashboard Overview',
    'subtitle' => 'Welcome back! Here\'s what\'s happening today.',
    'actions' => '
        <button class="btn btn-secondary btn-sm" onclick="location.reload()">
            <i class="fas fa-sync-alt"></i> Refresh
        </button>
        <a href="' . site_url('admin/reports') . '" class="btn btn-info btn-sm">
            <i class="fas fa-chart-bar"></i> Reports
        </a>
    '
]) ?>

<!-- Debug Info (Set $debug = true to enable) -->
<?php 
$debug = false; // Set to true to see debug information
if ($debug): 
?>
<div class="alert alert-info mb-4">
    <h5><i class="fas fa-bug"></i> Debug Information:</h5>
    <pre style="background: #f8f9fa; padding: 1rem; border-radius: 4px; overflow-x: auto;"><?php 
    echo "totalBookings: " . var_export($totalBookings ?? null, true) . "\n";
    echo "pendingBookings: " . var_export($pendingBookings ?? null, true) . "\n";
    echo "approvedBookings: " . var_export($approvedBookings ?? null, true) . "\n";
    echo "totalUsers: " . var_export($totalUsers ?? null, true) . "\n";
    echo "totalCampuses: " . var_export($totalCampuses ?? null, true) . "\n";
    echo "totalPastors: " . var_export($totalPastors ?? null, true) . "\n";
    echo "completedBookings: " . var_export($completedBookings ?? null, true) . "\n";
    echo "monthlyRevenue: " . var_export($monthlyRevenue ?? null, true) . "\n";
    ?></pre>
    <p><small>To disable this debug panel, set <code>$debug = false</code> in the view file.</small></p>
</div>
<?php endif; ?>

<!-- Primary Stats Grid -->
<div class="stats-grid mb-3">
    <!-- Total Bookings -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon" style="background: linear-gradient(135deg, #64017f, #8b4a9c);">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        <h3 class="stat-value"><?= number_format($totalBookings ?? 0) ?></h3>
        <p class="stat-label">Total Bookings</p>
    </div>
    
    <!-- Pending Approvals -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ffc107, #fd7e14);">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <h3 class="stat-value"><?= number_format($pendingBookings ?? 0) ?></h3>
        <p class="stat-label">Pending Approvals</p>
    </div>
    
    <!-- Approved Bookings -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <h3 class="stat-value"><?= number_format($approvedBookings ?? 0) ?></h3>
        <p class="stat-label">Approved Bookings</p>
    </div>
    
    <!-- Registered Users -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon" style="background: linear-gradient(135deg, #17a2b8, #138496);">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <h3 class="stat-value"><?= number_format($totalUsers ?? 0) ?></h3>
        <p class="stat-label">Registered Users</p>
    </div>
</div>

<!-- Secondary Stats Grid -->
<div class="stats-grid mb-3">
    <!-- Completed Weddings -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <i class="fas fa-heart"></i>
            </div>
        </div>
        <h3 class="stat-value"><?= number_format($completedBookings ?? 0) ?></h3>
        <p class="stat-label">Completed Weddings</p>
    </div>
    
    <!-- Active Campuses -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon" style="background: linear-gradient(135deg, #17a2b8, #138496);">
                <i class="fas fa-church"></i>
            </div>
        </div>
        <h3 class="stat-value"><?= number_format($totalCampuses ?? 0) ?></h3>
        <p class="stat-label">Active Campuses</p>
    </div>
    
    <!-- Pastors -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon" style="background: linear-gradient(135deg, #64017f, #8b4a9c);">
                <i class="fas fa-user-tie"></i>
            </div>
        </div>
        <h3 class="stat-value"><?= number_format($totalPastors ?? 0) ?></h3>
        <p class="stat-label">Pastors</p>
    </div>
    
    <!-- Monthly Revenue -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
        <h3 class="stat-value">UGX <?= number_format($monthlyRevenue ?? 0, 0) ?></h3>
        <p class="stat-label">This Month Revenue</p>
    </div>
</div>

<!-- Main Content Grid -->
<div class="dashboard-grid mb-3">
    <!-- Mini Calendar Widget -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-calendar-alt"></i> Calendar
            </h3>
            <div class="card-actions">
                <a href="<?= site_url('admin/calendar') ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-expand"></i> View Full Calendar
                </a>
            </div>
        </div>
        <div class="card-body">
            <div id="mini-calendar" class="mini-calendar">
                <!-- Calendar will be generated by JavaScript -->
            </div>
            <div class="calendar-events-summary mt-2">
                <h5 class="mb-1" style="font-size: 0.875rem; font-weight: 600;">This Month's Events</h5>
                <?php if (!empty($calendarBookings)): ?>
                    <div class="mini-events-list">
                        <?php foreach (array_slice($calendarBookings, 0, 3) as $event): ?>
                            <div class="mini-event-item">
                                <div class="event-date-badge">
                                    <span class="event-day"><?= date('j', strtotime($event['wedding_date'])) ?></span>
                                    <span class="event-month"><?= date('M', strtotime($event['wedding_date'])) ?></span>
                                </div>
                                <div class="event-info">
                                    <strong><?= esc($event['bride_name'] ?? '') ?> & <?= esc($event['groom_name'] ?? '') ?></strong>
                                    <small class="text-muted d-block"><?= esc($event['campus_name'] ?? 'Unknown') ?> • <?= date('g:i A', strtotime($event['wedding_time'] ?? '12:00:00')) ?></small>
                                </div>
                                <a href="<?= site_url('admin/booking/' . $event['id']) ?>" class="btn btn-xs btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($calendarBookings) > 3): ?>
                        <a href="<?= site_url('admin/calendar') ?>" class="btn btn-xs btn-outline-primary btn-block mt-1">
                            View All <?= count($calendarBookings) ?> Events
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-muted text-center">No events scheduled this month</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Upcoming Events -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-calendar-week"></i> Upcoming Events (Next 7 Days)
            </h3>
            <div class="card-actions">
                <a href="<?= site_url('admin/bookings') ?>" class="btn btn-sm btn-secondary">View All</a>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($upcomingBookings)): ?>
                <div class="upcoming-events-list">
                    <?php foreach (array_slice($upcomingBookings, 0, 5) as $booking): ?>
                        <div class="upcoming-event-item">
                            <div class="event-date-indicator">
                                <div class="event-day-number"><?= date('j', strtotime($booking['wedding_date'])) ?></div>
                                <div class="event-day-name"><?= date('D', strtotime($booking['wedding_date'])) ?></div>
                            </div>
                            <div class="event-details">
                                <h5 class="mb-1"><?= esc($booking['bride_name'] ?? '') ?> & <?= esc($booking['groom_name'] ?? '') ?></h5>
                                <p class="mb-1 text-muted">
                                    <i class="fas fa-map-marker-alt"></i> <?= esc($booking['campus_name'] ?? 'Unknown') ?>
                                </p>
                                <p class="mb-0 text-muted">
                                    <i class="fas fa-clock"></i> <?= date('g:i A', strtotime($booking['wedding_time'] ?? '12:00:00')) ?>
                                </p>
                            </div>
                            <div class="event-status">
                                <span class="badge badge-<?= $booking['status'] === 'approved' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($booking['status'] ?? 'pending') ?>
                                </span>
                                <a href="<?= site_url('admin/booking/' . $booking['id']) ?>" class="btn btn-xs btn-outline-primary mt-2">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-muted">No upcoming events in the next 7 days.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Bottom Grid -->
<div class="dashboard-grid mb-3">
    <!-- Recent Bookings -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-history"></i> Recent Bookings
            </h3>
            <div class="card-actions">
                <a href="<?= site_url('admin/bookings') ?>" class="btn btn-sm btn-secondary">View All</a>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($recentBookings)): ?>
                <div class="table-wrapper">
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
                            <?php foreach (array_slice($recentBookings, 0, 3) as $booking): ?>
                            <tr>
                                <td>
                                    <strong><?= esc($booking['bride_name'] ?? '') ?> & <?= esc($booking['groom_name'] ?? '') ?></strong>
                                </td>
                                <td><?= esc($booking['campus_name'] ?? 'Unknown') ?></td>
                                <td><?= isset($booking['wedding_date']) ? date('M j, Y', strtotime($booking['wedding_date'])) : 'Not set' ?></td>
                                <td>
                                    <?php
                                    $statusClass = 'badge-secondary';
                                    switch($booking['status']) {
                                        case 'pending': $statusClass = 'badge-warning'; break;
                                        case 'approved': $statusClass = 'badge-success'; break;
                                        case 'rejected': $statusClass = 'badge-danger'; break;
                                        case 'completed': $statusClass = 'badge-info'; break;
                                        case 'cancelled': $statusClass = 'badge-secondary'; break;
                                    }
                                    ?>
                                    <span class="badge <?= $statusClass ?>">
                                        <?= ucfirst($booking['status'] ?? 'unknown') ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= site_url('admin/booking/' . $booking['id']) ?>" class="btn-action view" title="View Details">
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

    <!-- Top Performing Campuses -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-trophy"></i> Top Performing Campuses
            </h3>
            <div class="card-actions">
                <a href="<?= site_url('admin/venues') ?>" class="btn btn-sm btn-secondary">View All</a>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($topCampuses)): ?>
                <div class="top-campuses-list">
                    <?php foreach (array_slice($topCampuses, 0, 3) as $index => $campus): ?>
                        <div class="campus-rank-item">
                            <div class="rank-badge rank-<?= $index + 1 ?>">
                                <?= $index + 1 ?>
                            </div>
                            <div class="campus-info">
                                <h5 class="mb-1"><?= esc($campus['name']) ?></h5>
                                <p class="mb-0 text-muted">
                                    <i class="fas fa-map-marker-alt"></i> <?= esc($campus['location'] ?? 'Unknown') ?>
                                </p>
                            </div>
                            <div class="campus-stats">
                                <div class="stat-value"><?= number_format($campus['booking_count'] ?? 0) ?></div>
                                <div class="stat-label">Bookings</div>
                            </div>
                            <a href="<?= site_url('admin/campus/' . $campus['id']) ?>" class="btn btn-xs btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-muted">No campus data available.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-bolt"></i> Quick Actions
        </h3>
    </div>
    <div class="card-body">
        <div class="quick-actions-grid">
            <a href="<?= site_url('admin/bookings') ?>" class="quick-action-card">
                <div class="action-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="action-info">
                    <h4>Manage Bookings</h4>
                    <p>View and approve wedding bookings</p>
                </div>
            </a>
            <a href="<?= site_url('admin/venues') ?>" class="quick-action-card">
                <div class="action-icon">
                    <i class="fas fa-church"></i>
                </div>
                <div class="action-info">
                    <h4>Manage Campuses</h4>
                    <p>Configure church venues</p>
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
            <a href="<?= site_url('admin/pastors') ?>" class="quick-action-card">
                <div class="action-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="action-info">
                    <h4>Manage Pastors</h4>
                    <p>View and manage pastors</p>
                </div>
            </a>
            <a href="<?= site_url('admin/calendar') ?>" class="quick-action-card">
                <div class="action-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="action-info">
                    <h4>View Calendar</h4>
                    <p>Full calendar view</p>
                </div>
            </a>
            <a href="<?= site_url('admin/reports') ?>" class="quick-action-card">
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
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
/* Compact Stat Cards */
.stats-grid {
    gap: 0.75rem;
}

.stats-grid .stat-card {
    padding: 0.875rem;
}

.stats-grid .stat-header {
    margin-bottom: 0.5rem;
}

.stats-grid .stat-icon {
    width: 36px;
    height: 36px;
    font-size: 16px;
}

.stats-grid .stat-value {
    font-size: 22px;
    margin: 0.25rem 0;
}

.stats-grid .stat-label {
    font-size: 0.7rem;
    margin-top: 0.125rem;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

/* Mini Calendar Styles */
.mini-calendar {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 0.75rem;
    margin-bottom: 0.75rem;
}

.mini-calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #495057;
    font-size: 0.875rem;
}

.mini-calendar-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 0.125rem;
    margin-bottom: 0.375rem;
}

.mini-calendar-weekday {
    text-align: center;
    font-size: 0.7rem;
    font-weight: 600;
    color: #6c757d;
    padding: 0.125rem;
}

.mini-calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 0.125rem;
}

.mini-calendar-day {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    padding: 0.125rem;
}

.mini-calendar-day:hover {
    background: #e9ecef;
}

.mini-calendar-day.other-month {
    color: #adb5bd;
}

.mini-calendar-day.today {
    background: #64017f;
    color: white;
    font-weight: 600;
}

.mini-calendar-day.has-event {
    background: #fff3cd;
    font-weight: 600;
}

.mini-calendar-day.has-event.today {
    background: #ffc107;
    color: #000;
}

.mini-calendar-day.has-event::after {
    content: '';
    position: absolute;
    bottom: 2px;
    left: 50%;
    transform: translateX(-50%);
    width: 4px;
    height: 4px;
    background: #ffc107;
    border-radius: 50%;
}

.mini-calendar-day.has-event.today::after {
    background: #000;
}

/* Calendar Events Summary */
.calendar-events-summary h5 {
    font-size: 0.8rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.mini-events-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.mini-event-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    background: #f8f9fa;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.mini-event-item:hover {
    background: #e9ecef;
}

.event-date-badge {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    padding: 0.375rem;
    background: #64017f;
    color: white;
    border-radius: 6px;
    font-weight: 600;
}

.event-date-badge .event-day {
    font-size: 1rem;
    line-height: 1;
}

.event-date-badge .event-month {
    font-size: 0.65rem;
    text-transform: uppercase;
    opacity: 0.9;
}

.event-info {
    flex: 1;
}

.event-info strong {
    font-size: 0.8rem;
    color: #212529;
}

.event-info small {
    font-size: 0.7rem;
}

/* Upcoming Events */
.upcoming-events-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.upcoming-event-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 6px;
    border-left: 3px solid #64017f;
    transition: all 0.2s ease;
}

.upcoming-event-item:hover {
    background: #e9ecef;
    transform: translateX(4px);
}

.event-date-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-width: 50px;
    padding: 0.5rem;
    background: #64017f;
    color: white;
    border-radius: 6px;
    font-weight: 600;
}

.event-day-number {
    font-size: 1.25rem;
    line-height: 1;
}

.event-day-name {
    font-size: 0.65rem;
    text-transform: uppercase;
    opacity: 0.9;
}

.event-details {
    flex: 1;
}

.event-details h5 {
    font-size: 0.9rem;
    margin-bottom: 0.125rem;
    color: #212529;
}

.event-details p {
    font-size: 0.8rem;
    margin-bottom: 0.125rem;
}

.event-status {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.5rem;
}

/* Top Campuses */
.top-campuses-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.campus-rank-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.campus-rank-item:hover {
    background: #e9ecef;
}

.rank-badge {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    font-weight: 700;
    font-size: 0.9rem;
    color: white;
}

.rank-badge.rank-1 {
    background: linear-gradient(135deg, #ffd700, #ffed4e);
    color: #000;
}

.rank-badge.rank-2 {
    background: linear-gradient(135deg, #c0c0c0, #e8e8e8);
    color: #000;
}

.rank-badge.rank-3 {
    background: linear-gradient(135deg, #cd7f32, #daa520);
    color: white;
}

.rank-badge:not(.rank-1):not(.rank-2):not(.rank-3) {
    background: #6c757d;
}

.campus-info {
    flex: 1;
}

.campus-info h5 {
    font-size: 0.9rem;
    margin-bottom: 0.125rem;
    color: #212529;
}

.campus-stats {
    text-align: center;
}

.stat-value {
    font-size: 1rem;
    font-weight: 700;
    color: #64017f;
    line-height: 1;
}

.stat-label {
    font-size: 0.75rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

/* Quick Actions Grid */
.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

.quick-action-card {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.quick-action-card:hover {
    background: white;
    border-color: #64017f;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(100, 1, 127, 0.15);
    text-decoration: none;
    color: inherit;
}

.action-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #64017f, #8b4a9c);
    color: white;
    border-radius: 8px;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.action-info h4 {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.125rem;
    color: #212529;
}

.action-info p {
    font-size: 0.75rem;
    color: #6c757d;
    margin: 0;
}

/* Compact Table */
.card .data-table {
    font-size: 0.875rem;
}

.card .data-table th,
.card .data-table td {
    padding: 0.5rem 0.75rem;
}

.card .data-table thead th {
    font-size: 0.8rem;
    font-weight: 600;
}

/* Compact Card Headers */
.card-header {
    padding: 0.625rem 0.875rem;
}

.card-header .card-title {
    font-size: 0.95rem;
    margin: 0;
}

.card-body {
    padding: 0.875rem;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .quick-actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .quick-actions-grid {
        grid-template-columns: 1fr;
    }
    
    .upcoming-event-item,
    .campus-rank-item {
        flex-wrap: wrap;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Mini Calendar JavaScript
document.addEventListener('DOMContentLoaded', function() {
    renderMiniCalendar();
});

function renderMiniCalendar() {
    const calendarContainer = document.getElementById('mini-calendar');
    if (!calendarContainer) return;
    
    const today = new Date();
    const currentMonth = today.getMonth();
    const currentYear = today.getFullYear();
    
    // Get first day of month and number of days
    const firstDay = new Date(currentYear, currentMonth, 1);
    const lastDay = new Date(currentYear, currentMonth + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDayOfWeek = firstDay.getDay();
    
    // Get events data
    const eventsData = <?= json_encode($calendarBookings ?? []) ?>;
    const eventDates = eventsData.map(event => event.wedding_date);
    
    // Create calendar HTML
    let html = '<div class="mini-calendar-header">';
    html += '<span>' + today.toLocaleDateString('en-US', { month: 'long', year: 'numeric' }) + '</span>';
    html += '<a href="<?= site_url('admin/calendar') ?>" class="btn btn-xs btn-outline-primary">View Full</a>';
    html += '</div>';
    
    // Weekday headers
    html += '<div class="mini-calendar-weekdays">';
    const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    weekdays.forEach(day => {
        html += '<div class="mini-calendar-weekday">' + day + '</div>';
    });
    html += '</div>';
    
    // Calendar days
    html += '<div class="mini-calendar-days">';
    
    // Empty cells for days before month starts
    for (let i = 0; i < startingDayOfWeek; i++) {
        html += '<div class="mini-calendar-day other-month"></div>';
    }
    
    // Days of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const isToday = day === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear();
        const hasEvent = eventDates.includes(dateStr);
        
        let dayClass = 'mini-calendar-day';
        if (isToday) dayClass += ' today';
        if (hasEvent) dayClass += ' has-event';
        
        html += `<div class="${dayClass}" data-date="${dateStr}">${day}</div>`;
    }
    
    html += '</div>';
    
    calendarContainer.innerHTML = html;
    
    // Add click handlers to days with events
    calendarContainer.querySelectorAll('.mini-calendar-day.has-event').forEach(day => {
        day.addEventListener('click', function() {
            window.location.href = '<?= site_url('admin/calendar') ?>?date=' + this.dataset.date;
        });
        day.style.cursor = 'pointer';
    });
}
</script>
<?= $this->endSection() ?>

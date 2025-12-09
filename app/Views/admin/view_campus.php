<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>
<?php
ob_start();
echo '<a href="' . site_url('admin/campuses') . '" class="btn btn-secondary btn-sm">
    <i class="fas fa-arrow-left"></i> Back to Campuses
</a>';
echo '<a href="' . site_url('admin/campus/' . $campus['id'] . '/edit') . '" class="btn btn-primary btn-sm">
    <i class="fas fa-edit"></i> Edit Campus
</a>';

$pageActions = ob_get_clean();
?>
<?= $this->include('admin_template/partials/page_header', [
    'title' => 'Campus Details',
    'subtitle' => 'View complete campus information and booking statistics',
    'actions' => $pageActions
]) ?>

<div class="campus-details-container">
    <!-- Campus Information Card -->
    <div class="card campus-info-card mb-3">
        <div class="campus-image-section">
            <img src="<?= base_url('public/images/campuses/' . ($campus['image_path'] ?? 'default-campus.jpg')) ?>" 
                 alt="<?= esc($campus['name']) ?>" class="campus-detail-image">
            <div class="campus-status-overlay">
                <span class="badge <?= $campus['is_active'] ? 'badge-success' : 'badge-secondary' ?>">
                    <?= $campus['is_active'] ? 'Active' : 'Inactive' ?>
                </span>
            </div>
        </div>
        
        <div class="campus-info-content">
            <h1 class="campus-name"><?= esc($campus['name']) ?></h1>
            
            <div class="campus-basic-info">
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <label>Location</label>
                        <span><?= esc($campus['location']) ?></span>
                    </div>
                </div>
                
                <div class="info-item">
                    <i class="fas fa-users"></i>
                    <div>
                        <label>Capacity</label>
                        <span><?= number_format($campus['capacity']) ?> guests</span>
                    </div>
                </div>
                
                <div class="info-item">
                    <i class="fas fa-dollar-sign"></i>
                    <div>
                        <label>Cost</label>
                        <span>UGX <?= number_format($campus['cost']) ?></span>
                    </div>
                </div>
                
                <div class="info-item">
                    <i class="fas fa-calendar-plus"></i>
                    <div>
                        <label>Created</label>
                        <span><?= date('M j, Y', strtotime($campus['created_at'] ?? 'now')) ?></span>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($campus['description'])): ?>
            <div class="campus-description">
                <h3>Description</h3>
                <p><?= nl2br(esc($campus['description'])) ?></p>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($campus['facilities'])): ?>
            <div class="campus-facilities">
                <h3>Facilities & Amenities</h3>
                <div class="facilities-list">
                    <?php 
                    $facilities = explode(',', $campus['facilities']);
                    foreach ($facilities as $facility): 
                    ?>
                        <span class="facility-tag"><?= trim(esc($facility)) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid mb-3">
        <?= $this->include('admin_template/partials/stat_card', ['icon' => 'fas fa-calendar-check', 'number' => number_format($campus['total_bookings'] ?? 0), 'label' => 'Total Bookings', 'type' => 'primary']) ?>
        <?= $this->include('admin_template/partials/stat_card', ['icon' => 'fas fa-clock', 'number' => number_format($campus['pending_bookings'] ?? 0), 'label' => 'Pending', 'type' => 'warning']) ?>
        <?= $this->include('admin_template/partials/stat_card', ['icon' => 'fas fa-check-circle', 'number' => number_format($campus['approved_bookings'] ?? 0), 'label' => 'Approved', 'type' => 'success']) ?>
        <?= $this->include('admin_template/partials/stat_card', ['icon' => 'fas fa-heart', 'number' => number_format($campus['completed_bookings'] ?? 0), 'label' => 'Completed', 'type' => 'info']) ?>
    </div>

    <!-- Recent Bookings -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Bookings</h3>
            <div class="card-actions">
                <a href="<?= site_url('admin/bookings?campus=' . $campus['id']) ?>" class="btn btn-sm btn-secondary">
                    View All Bookings
                </a>
            </div>
        </div>
        
        <div class="card-body">
            <?php if (empty($recentBookings)): ?>
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h4>No Bookings Yet</h4>
                    <p>This campus doesn't have any bookings yet.</p>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Couple</th>
                                <th>Wedding Date</th>
                                <th>Status</th>
                                <th>Booked Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentBookings as $booking): ?>
                            <tr>
                                <td>
                                    <div class="couple-info">
                                        <strong><?= esc($booking['bride_name']) ?> & <?= esc($booking['groom_name']) ?></strong>
                                    </div>
                                </td>
                                <td data-order="<?= strtotime($booking['wedding_date']) ?>">
                                    <span class="wedding-date">
                                        <?= date('M j, Y', strtotime($booking['wedding_date'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $statusBadgeClass = 'badge-secondary';
                                    switch($booking['status']) {
                                        case 'pending': $statusBadgeClass = 'badge-warning'; break;
                                        case 'approved': $statusBadgeClass = 'badge-success'; break;
                                        case 'rejected': $statusBadgeClass = 'badge-danger'; break;
                                        case 'completed': $statusBadgeClass = 'badge-info'; break;
                                        case 'cancelled': $statusBadgeClass = 'badge-secondary'; break;
                                    }
                                    ?>
                                    <span class="badge <?= $statusBadgeClass ?>"><?= ucfirst($booking['status']) ?></span>
                                </td>
                                <td data-order="<?= strtotime($booking['created_at']) ?>">
                                    <span class="booked-date">
                                        <?= date('M j, Y', strtotime($booking['created_at'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= site_url('admin/booking/' . $booking['id']) ?>" class="btn-action view" title="View Booking">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.campus-details-container {
    display: grid;
    gap: 1rem;
}

.campus-info-card {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 0;
    overflow: hidden;
    padding: 0;
}

.campus-image-section {
    position: relative;
    height: 240px;
}

.campus-detail-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.campus-status-overlay {
    position: absolute;
    top: 10px;
    right: 10px;
}

.status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.active {
    background: #28a745;
    color: white;
}

.status-badge.inactive {
    background: #6c757d;
    color: white;
}

.campus-info-content {
    padding: 1.25rem;
}

.campus-name {
    font-family: var(--font-heading);
    color: var(--accent);
    font-size: 1.75rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.campus-basic-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.info-item i {
    width: 18px;
    color: var(--accent);
    font-size: 1rem;
    flex-shrink: 0;
}

.info-item label {
    display: block;
    font-weight: 600;
    color: var(--text-tertiary);
    font-size: 0.75rem;
    margin-bottom: 0.125rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item span {
    color: var(--text-primary);
    font-size: 0.9rem;
}

.campus-description,
.campus-facilities {
    margin-bottom: 1rem;
}

.campus-description h3,
.campus-facilities h3 {
    color: var(--accent);
    margin-bottom: 0.5rem;
    font-size: 1rem;
    font-weight: 600;
}

.campus-description p {
    color: var(--text-secondary);
    line-height: 1.5;
    font-size: 0.875rem;
    margin: 0;
}

.facilities-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.facility-tag {
    background: var(--bg-tertiary);
    color: var(--text-secondary);
    padding: 0.375rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    border: 1px solid var(--border);
}

/* Compact Stats Grid */
.campus-details-container .stats-grid {
    gap: 0.75rem;
}

.campus-details-container .stats-grid .stat-card {
    padding: 0.875rem;
}

.campus-details-container .stats-grid .stat-icon {
    width: 36px;
    height: 36px;
    font-size: 16px;
}

.campus-details-container .stats-grid .stat-value {
    font-size: 22px;
}

.campus-details-container .stats-grid .stat-label {
    font-size: 0.7rem;
}

/* Compact Table */
.campus-details-container .data-table {
    font-size: 0.875rem;
}

.campus-details-container .data-table th,
.campus-details-container .data-table td {
    padding: 0.5rem 0.75rem;
}

.couple-info strong {
    color: var(--text-primary);
    font-size: 0.9rem;
}

.wedding-date,
.booked-date {
    color: var(--text-tertiary);
    font-size: 0.85rem;
}

/* Action buttons use template btn-action class */

.empty-state {
    text-align: center;
    padding: 2rem 1rem;
    color: var(--text-tertiary);
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 0.75rem;
    opacity: 0.5;
    color: var(--text-muted);
}

.empty-state h4 {
    margin-bottom: 0.5rem;
    color: var(--text-secondary);
    font-size: 1rem;
}

.empty-state p {
    color: var(--text-tertiary);
    margin: 0;
    font-size: 0.875rem;
}

@media (max-width: 968px) {
    .campus-info-card {
        grid-template-columns: 1fr;
    }
    
    .campus-image-section {
        height: 200px;
    }
    
    .campus-basic-info {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    
    .campus-info-content {
        padding: 1rem;
    }
    
    .campus-name {
        font-size: 1.5rem;
        margin-bottom: 0.75rem;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>

<?php
$pageActions = '
    <a href="' . site_url('admin/reports/export') . '?type=campus-performance&' . http_build_query($filters ?? []) . '" 
       class="btn btn-success btn-sm" target="_blank">
        <i class="fas fa-download"></i> Export
    </a>
    <a href="' . site_url('admin/reports') . '" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
';
?>

<?= $this->include('admin_template/partials/page_header', [
    'title' => 'Campus Performance Report',
    'subtitle' => 'Detailed performance by campus location',
    'actions' => $pageActions
]) ?>

<!-- Filter Panel -->
<?php
ob_start();
?>
<div class="filter-row">
    <div class="form-group">
        <label class="form-label">From Date</label>
        <input type="date" id="start_date" name="start_date" class="form-control" 
               value="<?= $filters['start_date'] ?? '' ?>">
    </div>
    
    <div class="form-group">
        <label class="form-label">To Date</label>
        <input type="date" id="end_date" name="end_date" class="form-control" 
               value="<?= $filters['end_date'] ?? '' ?>">
    </div>
    
    <div class="form-group">
        <label class="form-label">Campus</label>
        <select id="campus_id" name="campus_id" class="form-control">
            <option value="">All Campuses</option>
            <?php foreach ($campuses ?? [] as $campus): ?>
                <option value="<?= $campus['id'] ?>" 
                        <?= ($filters['campus_id'] ?? '') == $campus['id'] ? 'selected' : '' ?>>
                    <?= esc($campus['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label class="form-label">&nbsp;</label>
        <div class="filter-actions">
            <button type="button" class="btn btn-primary btn-sm" onclick="applyFilters()">
                <i class="fas fa-filter"></i> Apply
            </button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="clearFilters()">
                <i class="fas fa-times"></i> Clear
            </button>
        </div>
    </div>
</div>
<?php
$filterContent = ob_get_clean();
?>
<?= $this->include('admin_template/partials/filter_panel', ['content' => $filterContent]) ?>

<!-- Campus Performance Cards -->
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">Campus Performance Overview</h3>
    </div>
    <div class="card-body">
        <div class="campus-grid">
            <?php foreach ($campusData ?? [] as $campus): ?>
                <?php 
                $score = $campus['total_bookings'] > 0 ? 
                    round(($campus['approved_bookings'] / $campus['total_bookings']) * 100) : 0;
                $scoreClass = $score >= 80 ? 'excellent' : ($score >= 60 ? 'good' : 'needs-improvement');
                ?>
                <div class="campus-card">
                    <div class="campus-card-header">
                        <h4><?= esc($campus['name']) ?></h4>
                        <div class="performance-score">
                            <span class="badge badge-<?= $scoreClass === 'excellent' ? 'success' : ($scoreClass === 'good' ? 'warning' : 'danger') ?>">
                                <?= $score ?>%
                            </span>
                        </div>
                    </div>
                    
                    <div class="campus-card-body">
                        <div class="stats-row">
                            <div class="stat-item">
                                <div class="stat-value"><?= $campus['total_bookings'] ?></div>
                                <div class="stat-label">Total Bookings</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">UGX <?= number_format($campus['total_revenue']) ?></div>
                                <div class="stat-label">Revenue</div>
                            </div>
                        </div>
                        
                        <div class="status-breakdown">
                            <div class="status-item">
                                <span class="badge badge-success"><?= $campus['approved_bookings'] ?></span>
                                <span class="status-text">Approved</span>
                            </div>
                            <div class="status-item">
                                <span class="badge badge-warning"><?= $campus['pending_bookings'] ?></span>
                                <span class="status-text">Pending</span>
                            </div>
                            <div class="status-item">
                                <span class="badge badge-danger"><?= $campus['rejected_bookings'] ?></span>
                                <span class="status-text">Rejected</span>
                            </div>
                            <div class="status-item">
                                <span class="badge badge-info"><?= $campus['completed_bookings'] ?></span>
                                <span class="status-text">Completed</span>
                            </div>
                        </div>
                        
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= $score ?>%"></div>
                        </div>
                    </div>
                    
                    <div class="campus-card-footer">
                        <small class="text-muted">Last updated: <?= date('M j, Y') ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Detailed Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Campus Performance Details</h3>
        <div class="card-actions">
            <span class="badge badge-info"><?= count($campusData ?? []) ?> Campuses</span>
        </div>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table id="campusTable" class="data-table" style="width:100%">
                <thead>
                    <tr>
                        <th>Campus</th>
                        <th>Total Bookings</th>
                        <th>Approved</th>
                        <th>Pending</th>
                        <th>Rejected</th>
                        <th>Completed</th>
                        <th>Revenue</th>
                        <th>Success Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($campusData ?? [] as $campus): ?>
                        <?php 
                        $successRate = $campus['total_bookings'] > 0 ? 
                            round(($campus['approved_bookings'] / $campus['total_bookings']) * 100, 1) : 0;
                        $rateClass = $successRate >= 80 ? 'success' : ($successRate >= 60 ? 'warning' : 'danger');
                        ?>
                        <tr>
                            <td>
                                <strong><?= esc($campus['name']) ?></strong>
                            </td>
                            <td><strong><?= $campus['total_bookings'] ?></strong></td>
                            <td><span class="badge badge-success"><?= $campus['approved_bookings'] ?></span></td>
                            <td><span class="badge badge-warning"><?= $campus['pending_bookings'] ?></span></td>
                            <td><span class="badge badge-danger"><?= $campus['rejected_bookings'] ?></span></td>
                            <td><span class="badge badge-info"><?= $campus['completed_bookings'] ?></span></td>
                            <td><strong>UGX <?= number_format($campus['total_revenue']) ?></strong></td>
                            <td>
                                <span class="badge badge-<?= $rateClass ?>"><?= $successRate ?>%</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.campus-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
}

.campus-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.campus-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.campus-card-header {
    background: linear-gradient(135deg, #64017f, #8b4a9c);
    color: white;
    padding: 1.25rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.campus-card-header h4 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.campus-card-body {
    padding: 1.25rem;
}

.stats-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-item {
    text-align: center;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.85rem;
    color: var(--text-tertiary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-breakdown {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.status-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-text {
    font-size: 0.85rem;
    color: var(--text-tertiary);
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: var(--bg-tertiary);
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #28a745, #20c997);
    transition: width 0.3s ease;
}

.campus-card-footer {
    padding: 0.75rem 1.25rem;
    background: var(--bg-tertiary);
    border-top: 1px solid var(--border);
}

@media (max-width: 768px) {
    .campus-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-row {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    
    .status-breakdown {
        grid-template-columns: 1fr;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#campusTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[1, 'desc']], // Order by total bookings desc
        columnDefs: [
            { responsivePriority: 1, targets: [0, 1] }, // Campus name and total bookings priority
            { responsivePriority: 2, targets: [6, 7] } // Revenue and success rate priority
        ],
        language: {
            search: "Search campuses:",
            lengthMenu: "Show _MENU_ campuses per page",
            info: "Showing _START_ to _END_ of _TOTAL_ campuses",
            infoEmpty: "No campuses found",
            infoFiltered: "(filtered from _MAX_ total campuses)",
            emptyTable: "No campus data available"
        }
    });
});

function applyFilters() {
    const params = new URLSearchParams();
    
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const campusId = document.getElementById('campus_id').value;
    
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);
    if (campusId) params.append('campus_id', campusId);
    
    const url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
    window.location.href = url;
}

function clearFilters() {
    window.location.href = window.location.pathname;
}
</script>
<?= $this->endSection() ?>

<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>

<?php
$pageActions = '
    <a href="' . site_url('admin/reports/export?type=overview') . '" 
       class="btn btn-success btn-sm" target="_blank">
        <i class="fas fa-download"></i> Export
    </a>
    <a href="' . site_url('admin/reports') . '" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Reports
    </a>
';
?>

<?= $this->include('admin_template/partials/page_header', [
    'title' => 'Overview Report',
    'subtitle' => 'Complete booking statistics and analytics',
    'actions' => $pageActions
]) ?>

<!-- Filter Panel -->
<?php
ob_start();
?>
<div class="filter-row">
    <div class="form-group">
        <label class="form-label">Campus</label>
        <select id="campusFilter" class="form-control">
            <option value="">All Campuses</option>
            <?php if (!empty($campuses)): ?>
                <?php foreach ($campuses as $campus): ?>
                    <option value="<?= esc($campus['name']) ?>"><?= esc($campus['name']) ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label class="form-label">From Date</label>
        <input type="date" id="startDate" class="form-control" placeholder="Start date">
    </div>
    
    <div class="form-group">
        <label class="form-label">To Date</label>
        <input type="date" id="endDate" class="form-control" placeholder="End date">
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

<!-- Quick Stats -->
<div class="stats-grid mb-3">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon" style="background: linear-gradient(135deg, #17a2b8, #138496);">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
        <h3 class="stat-value"><?= number_format($totalBookings ?? 0) ?></h3>
        <p class="stat-label">Total Bookings</p>
    </div>
    
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <h3 class="stat-value"><?= number_format($approvedBookings ?? 0) ?></h3>
        <p class="stat-label">Approved Bookings</p>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ffc107, #fd7e14);">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <h3 class="stat-value"><?= number_format($pendingBookings ?? 0) ?></h3>
        <p class="stat-label">Pending Bookings</p>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon" style="background: linear-gradient(135deg, #dc3545, #c82333);">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
        <h3 class="stat-value">UGX <?= number_format($totalRevenue ?? 0, 0) ?></h3>
        <p class="stat-label">Total Revenue</p>
    </div>
</div>

<!-- Charts Section -->
<div class="charts-grid mb-3">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Status Distribution</h3>
        </div>
        <div class="card-body">
            <canvas id="statusChart" style="height: 300px;"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Monthly Trends</h3>
        </div>
        <div class="card-body">
            <canvas id="monthlyChart" style="height: 300px;"></canvas>
        </div>
    </div>
</div>

<!-- Campus Performance Table -->
<div class="card mb-3">
    <div class="card-header">
        <h3 class="card-title">Campus Performance</h3>
    </div>
    <div class="card-body">
        <div class="table-wrapper">
            <table id="campusTable" class="data-table" style="width:100%">
                <thead>
                    <tr>
                        <th>Campus</th>
                        <th>Total</th>
                        <th>Approved</th>
                        <th>Pending</th>
                        <th>Rejected</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($campusPerformance ?? [] as $campus): ?>
                        <tr>
                            <td><strong><?= esc($campus['name']) ?></strong></td>
                            <td><?= $campus['total_bookings'] ?></td>
                            <td><span class="badge badge-success"><?= $campus['approved_bookings'] ?></span></td>
                            <td><span class="badge badge-warning"><?= $campus['pending_bookings'] ?></span></td>
                            <td><span class="badge badge-danger"><?= $campus['rejected_bookings'] ?></span></td>
                            <td><strong>UGX <?= number_format($campus['total_revenue']) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Additional Stats -->
<div class="additional-stats">
    <div class="card">
        <div class="card-body">
            <div class="info-card">
                <div class="info-icon" style="background: linear-gradient(135deg, #17a2b8, #138496);">
                    <i class="fas fa-church"></i>
                </div>
                <div class="info-content">
                    <span class="info-text">Active Campuses</span>
                    <span class="info-number"><?= $activeCampuses ?? 0 ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="info-card">
                <div class="info-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="info-content">
                    <span class="info-text">Active Pastors</span>
                    <span class="info-number"><?= $activePastors ?? 0 ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
    gap: 1rem;
}

.additional-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
}

.info-card {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.info-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.info-content {
    display: flex;
    flex-direction: column;
}

.info-text {
    font-size: 0.875rem;
    color: var(--text-tertiary);
    margin-bottom: 0.25rem;
}

.info-number {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-primary);
}

@media (max-width: 992px) {
    .charts-grid {
        grid-template-columns: 1fr;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize filters and DataTable
$(document).ready(function() {
    // Initialize DataTable for campus performance
    $('#campusTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]],
        order: [[1, 'desc']], // Sort by total bookings
        columnDefs: [
            {
                targets: [0], // Campus column
                responsivePriority: 1
            },
            {
                targets: [1, 5], // Total and Revenue columns
                responsivePriority: 2
            }
        ],
        language: {
            search: "Search campuses:",
            lengthMenu: "Show _MENU_ campuses per page",
            info: "Showing _START_ to _END_ of _TOTAL_ campuses",
            infoEmpty: "No campuses found",
            emptyTable: "No campus data available"
        }
    });
    
    // Generate charts
    generateStatusChart();
    generateMonthlyChart();
});

// Filter functions
function applyFilters() {
    // This would typically refresh the data via AJAX
    // For now, show visual feedback
    const applyBtn = document.querySelector('.filter-actions .btn-primary');
    const originalText = applyBtn.innerHTML;
    applyBtn.innerHTML = '<i class="fas fa-check"></i> Applied';
    applyBtn.style.background = '#28a745';
    
    setTimeout(() => {
        applyBtn.innerHTML = originalText;
        applyBtn.style.background = '';
    }, 1500);
}

function clearFilters() {
    $('#campusFilter').val('');
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    
    const clearBtn = document.querySelector('.filter-actions .btn-secondary');
    const originalText = clearBtn.innerHTML;
    clearBtn.innerHTML = '<i class="fas fa-check"></i> Cleared';
    
    setTimeout(() => {
        clearBtn.innerHTML = originalText;
    }, 1000);
}

// Status Distribution Chart
function generateStatusChart() {
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Approved', 'Pending', 'Rejected', 'Completed'],
                datasets: [{
                    data: [<?= $approvedBookings ?? 0 ?>, <?= $pendingBookings ?? 0 ?>, <?= $rejectedBookings ?? 0 ?>, <?= $completedBookings ?? 0 ?>],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }
}

// Monthly Trends Chart
function generateMonthlyChart() {
    const monthlyCtx = document.getElementById('monthlyChart');
    if (monthlyCtx) {
        const monthlyData = <?= json_encode($monthlyTrends ?? []) ?>;
        const labels = Object.keys(monthlyData);
        const data = Object.values(monthlyData);
        
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Bookings',
                    data: data,
                    borderColor: '#64017f',
                    backgroundColor: 'rgba(100, 1, 127, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#64017f',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
}
</script>
<?= $this->endSection() ?>

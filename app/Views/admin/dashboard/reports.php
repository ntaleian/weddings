<?= $this->extend('layouts/admin/admin') ?>

<?= $this->section('main_content') ?>
<div class="content-wrapper">
    <section id="reports" class="content-section">
        <div class="section-header">
            <h2>Reports & Analytics</h2>
            <div class="section-actions">
                <select id="reportPeriod" onchange="updateReportPeriod()">
                    <option value="7">Last 7 Days</option>
                    <option value="30" selected>Last 30 Days</option>
                    <option value="90">Last 3 Months</option>
                    <option value="365">Last Year</option>
                </select>
                <button class="btn btn-secondary" onclick="exportReports()">
                    <i class="fas fa-download"></i>
                    Export PDF
                </button>
                <button class="btn btn-primary" onclick="refreshReports()">
                    <i class="fas fa-sync-alt"></i>
                    Refresh
                </button>
            </div>
        </div>

        <!-- Reports Overview Cards -->
        <div class="reports-grid">
            <div class="report-card">
                <div class="report-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="report-content">
                    <h3><?= number_format($reportData['total_bookings'] ?? 0) ?></h3>
                    <p>Total Bookings</p>
                    <small class="trend up">
                        <i class="fas fa-arrow-up"></i>
                        +12% from last period
                    </small>
                </div>
            </div>

            <div class="report-card">
                <div class="report-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="report-content">
                    <h3>$<?= number_format($reportData['total_revenue'] ?? 0) ?></h3>
                    <p>Total Revenue</p>
                    <small class="trend up">
                        <i class="fas fa-arrow-up"></i>
                        +8% from last period
                    </small>
                </div>
            </div>

            <div class="report-card">
                <div class="report-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="report-content">
                    <h3><?= number_format($reportData['new_users'] ?? 0) ?></h3>
                    <p>New Users</p>
                    <small class="trend neutral">
                        <i class="fas fa-minus"></i>
                        No change
                    </small>
                </div>
            </div>

            <div class="report-card">
                <div class="report-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="report-content">
                    <h3><?= number_format($reportData['avg_booking_value'] ?? 0) ?></h3>
                    <p>Avg. Booking Value</p>
                    <small class="trend down">
                        <i class="fas fa-arrow-down"></i>
                        -3% from last period
                    </small>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="charts-container">
            <div class="chart-section">
                <div class="chart-header">
                    <h3>Booking Trends</h3>
                    <div class="chart-controls">
                        <button class="btn-chart active" data-chart="bookings">Bookings</button>
                        <button class="btn-chart" data-chart="revenue">Revenue</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="trendsChart" width="400" height="200"></canvas>
                </div>
            </div>

            <div class="chart-section">
                <div class="chart-header">
                    <h3>Campus Utilization</h3>
                </div>
                <div class="chart-container">
                    <canvas id="utilizationChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Detailed Reports -->
        <div class="detailed-reports">
            <div class="report-section">
                <h3>Popular Wedding Dates</h3>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Day</th>
                                <th>Bookings</th>
                                <th>Revenue</th>
                                <th>Campus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($popularDates)): ?>
                                <?php foreach ($popularDates as $date): ?>
                                <tr>
                                    <td><?= date('M j, Y', strtotime($date['wedding_date'])) ?></td>
                                    <td><?= date('l', strtotime($date['wedding_date'])) ?></td>
                                    <td><?= $date['booking_count'] ?></td>
                                    <td>$<?= number_format($date['total_revenue']) ?></td>
                                    <td><?= esc($date['campus_name']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No data available for selected period</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="report-section">
                <h3>Campus Performance</h3>
                <div class="campus-performance">
                    <?php if (!empty($campusPerformance)): ?>
                        <?php foreach ($campusPerformance as $campus): ?>
                        <div class="performance-item">
                            <div class="campus-info">
                                <h4><?= esc($campus['name']) ?></h4>
                                <span class="location"><?= esc($campus['location']) ?></span>
                            </div>
                            <div class="performance-metrics">
                                <div class="metric">
                                    <span class="metric-value"><?= $campus['bookings'] ?></span>
                                    <span class="metric-label">Bookings</span>
                                </div>
                                <div class="metric">
                                    <span class="metric-value">$<?= number_format($campus['revenue']) ?></span>
                                    <span class="metric-label">Revenue</span>
                                </div>
                                <div class="metric">
                                    <span class="metric-value"><?= $campus['utilization'] ?>%</span>
                                    <span class="metric-label">Utilization</span>
                                </div>
                            </div>
                            <div class="performance-bar">
                                <div class="bar-fill" style="width: <?= $campus['utilization'] ?>%"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">No campus performance data available</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Monthly Summary -->
        <div class="monthly-summary">
            <h3>Monthly Summary</h3>
            <div class="summary-grid">
                <div class="summary-item">
                    <h4>Top Performing Month</h4>
                    <p class="summary-value"><?= $reportData['top_month'] ?? 'N/A' ?></p>
                    <small><?= $reportData['top_month_bookings'] ?? 0 ?> bookings</small>
                </div>
                <div class="summary-item">
                    <h4>Average Monthly Revenue</h4>
                    <p class="summary-value">$<?= number_format($reportData['avg_monthly_revenue'] ?? 0) ?></p>
                    <small>Last 12 months</small>
                </div>
                <div class="summary-item">
                    <h4>Growth Rate</h4>
                    <p class="summary-value"><?= $reportData['growth_rate'] ?? 0 ?>%</p>
                    <small>Year over year</small>
                </div>
                <div class="summary-item">
                    <h4>Customer Retention</h4>
                    <p class="summary-value"><?= $reportData['retention_rate'] ?? 0 ?>%</p>
                    <small>Returning customers</small>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
// Chart.js would be loaded in the admin layout
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
});

function initializeCharts() {
    // Booking Trends Chart
    const trendsCtx = document.getElementById('trendsChart');
    if (trendsCtx) {
        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($chartData['months'] ?? []) ?>,
                datasets: [{
                    label: 'Bookings',
                    data: <?= json_encode($chartData['bookings'] ?? []) ?>,
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Campus Utilization Chart
    const utilizationCtx = document.getElementById('utilizationChart');
    if (utilizationCtx) {
        new Chart(utilizationCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($chartData['campus_names'] ?? []) ?>,
                datasets: [{
                    data: <?= json_encode($chartData['campus_utilization'] ?? []) ?>,
                    backgroundColor: [
                        '#4F46E5',
                        '#06B6D4',
                        '#10B981',
                        '#F59E0B',
                        '#EF4444'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    }
}

function updateReportPeriod() {
    const period = document.getElementById('reportPeriod').value;
    window.location.href = `<?= site_url('admin/reports') ?>?period=${period}`;
}

function exportReports() {
    const period = document.getElementById('reportPeriod').value;
    window.open(`<?= site_url('admin/reports/export') ?>?period=${period}&format=pdf`, '_blank');
}

function refreshReports() {
    location.reload();
}

// Chart control buttons
document.querySelectorAll('.btn-chart').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelectorAll('.btn-chart').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const chartType = this.dataset.chart;
        // Switch chart data based on selection
        console.log('Switching to', chartType, 'chart');
    });
});
</script>

<style>
.reports-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.report-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.report-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #4F46E5;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.report-content h3 {
    margin: 0;
    font-size: 2rem;
    font-weight: bold;
    color: #111827;
}

.report-content p {
    margin: 0.25rem 0;
    color: #6B7280;
}

.trend {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.875rem;
}

.trend.up { color: #10B981; }
.trend.down { color: #EF4444; }
.trend.neutral { color: #6B7280; }

.charts-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.chart-section {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.chart-controls {
    display: flex;
    gap: 0.5rem;
}

.btn-chart {
    padding: 0.5rem 1rem;
    border: 1px solid #D1D5DB;
    background: white;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-chart.active {
    background: #4F46E5;
    color: white;
    border-color: #4F46E5;
}

.detailed-reports {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.report-section {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.campus-performance {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.performance-item {
    padding: 1rem;
    border: 1px solid #E5E7EB;
    border-radius: 6px;
}

.campus-info h4 {
    margin: 0 0 0.25rem 0;
    color: #111827;
}

.performance-metrics {
    display: flex;
    gap: 1.5rem;
    margin: 1rem 0;
}

.metric {
    text-align: center;
}

.metric-value {
    display: block;
    font-size: 1.25rem;
    font-weight: bold;
    color: #111827;
}

.metric-label {
    font-size: 0.875rem;
    color: #6B7280;
}

.performance-bar {
    height: 6px;
    background: #E5E7EB;
    border-radius: 3px;
    overflow: hidden;
}

.bar-fill {
    height: 100%;
    background: #4F46E5;
    transition: width 0.3s ease;
}

.monthly-summary {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.summary-item {
    text-align: center;
    padding: 1rem;
    border: 1px solid #E5E7EB;
    border-radius: 6px;
}

.summary-value {
    font-size: 1.5rem;
    font-weight: bold;
    color: #4F46E5;
    margin: 0.5rem 0;
}

@media (max-width: 768px) {
    .charts-container {
        grid-template-columns: 1fr;
    }
    
    .detailed-reports {
        grid-template-columns: 1fr;
    }
    
    .performance-metrics {
        flex-wrap: wrap;
        gap: 1rem;
    }
}
</style>
<?= $this->endSection() ?>

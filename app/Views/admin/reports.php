<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>

<?= $this->include('admin_template/partials/page_header', [
    'title' => 'Reports & Analytics',
    'subtitle' => 'View detailed reports and analytics for wedding bookings',
    'actions' => ''
]) ?>

<!-- Report Types Section -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Report Types</h3>
    </div>
    <div class="card-body">
        <div class="reports-grid">
            <!-- Overview Report -->
            <a href="<?= site_url('admin/reports/overview') ?>" class="report-card">
                <div class="report-icon" style="background: linear-gradient(135deg, #64017f, #8b4a9c);">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div class="report-info">
                    <h5>Overview Report</h5>
                    <p>Complete booking statistics and analytics</p>
                </div>
            </a>
            
            <!-- Approved Bookings -->
            <a href="<?= site_url('admin/reports/approved-bookings') ?>" class="report-card">
                <div class="report-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="report-info">
                    <h5>Approved Bookings</h5>
                    <p>List of all approved wedding bookings</p>
                </div>
            </a>
            
            <!-- Pending Bookings -->
            <a href="<?= site_url('admin/reports/pending-bookings') ?>" class="report-card">
                <div class="report-icon" style="background: linear-gradient(135deg, #ffc107, #fd7e14);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="report-info">
                    <h5>Pending Bookings</h5>
                    <p>Bookings waiting for approval</p>
                </div>
            </a>
            
            <!-- Rejected Bookings -->
            <a href="<?= site_url('admin/reports/rejected-bookings') ?>" class="report-card">
                <div class="report-icon" style="background: linear-gradient(135deg, #dc3545, #c82333);">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="report-info">
                    <h5>Rejected Bookings</h5>
                    <p>List of rejected booking applications</p>
                </div>
            </a>
            
            <!-- Completed Weddings -->
            <a href="<?= site_url('admin/reports/completed-weddings') ?>" class="report-card">
                <div class="report-icon" style="background: linear-gradient(135deg, #17a2b8, #138496);">
                    <i class="fas fa-flag-checkered"></i>
                </div>
                <div class="report-info">
                    <h5>Completed Weddings</h5>
                    <p>Successfully completed wedding ceremonies</p>
                </div>
            </a>
            
            <!-- Campus Performance -->
            <a href="<?= site_url('admin/reports/campus-performance') ?>" class="report-card">
                <div class="report-icon" style="background: linear-gradient(135deg, #6c757d, #5a6268);">
                    <i class="fas fa-church"></i>
                </div>
                <div class="report-info">
                    <h5>Campus Performance</h5>
                    <p>Detailed performance by campus location</p>
                </div>
            </a>
            
            <!-- Revenue Report -->
            <a href="<?= site_url('admin/reports/revenue') ?>" class="report-card">
                <div class="report-icon" style="background: linear-gradient(135deg, #212529, #343a40);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="report-info">
                    <h5>Revenue Report</h5>
                    <p>Financial analysis and revenue tracking</p>
                </div>
            </a>
            
            <!-- Payments Report -->
            <a href="<?= site_url('admin/reports/payments') ?>" class="report-card">
                <div class="report-icon" style="background: linear-gradient(135deg, #64017f, #8b4a9c);">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="report-info">
                    <h5>Payments Report</h5>
                    <p>Track and manage all payment records</p>
                </div>
            </a>
            
            <!-- Monthly Trends -->
            <a href="<?= site_url('admin/reports/monthly-trends') ?>" class="report-card">
                <div class="report-icon" style="background: linear-gradient(135deg, #64017f, #8b4a9c);">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="report-info">
                    <h5>Monthly Trends</h5>
                    <p>Booking trends and patterns by month</p>
                </div>
            </a>
            
            <!-- Pastor Performance -->
            <a href="<?= site_url('admin/reports/pastor-performance') ?>" class="report-card">
                <div class="report-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="report-info">
                    <h5>Pastor Performance</h5>
                    <p>Wedding assignments by pastor</p>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Report Content Area -->
<div id="reportContent" class="mt-3">
    <div class="card">
        <div class="card-body text-center" style="padding: 3rem;">
            <i class="fas fa-chart-bar" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
            <h4 style="color: var(--text-primary); margin-bottom: 0.5rem;">Select a Report Type</h4>
            <p style="color: var(--text-tertiary); margin: 0;">Choose a report type above to view detailed analytics and data.</p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.reports-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
}

.report-card {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 1rem;
    text-decoration: none;
    color: inherit;
    position: relative;
    overflow: hidden;
}

.report-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--accent);
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.report-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: var(--accent);
}

.report-card:hover::before {
    transform: scaleY(1);
}

.report-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    flex-shrink: 0;
    box-shadow: var(--shadow-md);
}

.report-info {
    flex: 1;
}

.report-info h5 {
    margin: 0 0 0.5rem 0;
    color: var(--text-primary);
    font-weight: 600;
    font-size: 1rem;
    transition: color 0.3s ease;
}

.report-info p {
    margin: 0;
    color: var(--text-tertiary);
    font-size: 0.875rem;
    line-height: 1.5;
}

.report-card:hover .report-info h5 {
    color: var(--accent);
}

/* Responsive Design */
@media (max-width: 768px) {
    .reports-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1rem;
    }
    
    .report-card {
        padding: 1.25rem;
    }
    
    .report-icon {
        width: 48px;
        height: 48px;
        font-size: 1.25rem;
    }
}

@media (max-width: 480px) {
    .reports-grid {
        grid-template-columns: 1fr;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Include Chart.js for future report charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?= $this->endSection() ?>

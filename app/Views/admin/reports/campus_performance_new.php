<?= $this->extend('layouts/admin/admin') ?>

<?= $this->section('main_content') ?>
<div class="content-wrapper">
    <section id="campus-performance-report" class="content-section active">
        
        <!-- Filter Panel -->
        <div class="filter-panel" id="filterPanel">
            <div class="filter-container">
                <div class="filter-group">
                    <label for="start_date">From Date</label>
                    <input type="date" id="start_date" name="start_date" class="filter-input" 
                           value="<?= $filters['start_date'] ?? '' ?>">
                </div>
                
                <div class="filter-group">
                    <label for="end_date">To Date</label>
                    <input type="date" id="end_date" name="end_date" class="filter-input" 
                           value="<?= $filters['end_date'] ?? '' ?>">
                </div>
                
                <div class="filter-group">
                    <label for="campus_id">Campus</label>
                    <select id="campus_id" name="campus_id" class="filter-select">
                        <option value="">All Campuses</option>
                        <?php foreach ($campuses as $campus): ?>
                            <option value="<?= $campus['id'] ?>" 
                                    <?= ($filters['campus_id'] ?? '') == $campus['id'] ? 'selected' : '' ?>>
                                <?= esc($campus['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
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

        <!-- Campus Performance Cards -->
        <div class="campus-cards-container">
            <div class="section-header">
                <h3>Campus Performance Overview</h3>
                <div class="section-tools">
                    <a href="<?= site_url('admin/reports/export') ?>?type=campus-performance&<?= http_build_query($filters) ?>" 
                       class="btn btn-success btn-sm" target="_blank">
                        <i class="fas fa-download"></i> Export
                    </a>
                    <a href="<?= site_url('admin/reports') ?>" class="btn btn-info btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Reports
                    </a>
                </div>
            </div>
            
            <div class="campus-grid">
                <?php foreach ($campusData as $campus): ?>
                    <div class="campus-card">
                        <div class="campus-card-header">
                            <h4><?= esc($campus['name']) ?></h4>
                            <div class="performance-score">
                                <?php 
                                $score = $campus['total_bookings'] > 0 ? 
                                    round(($campus['approved_bookings'] / $campus['total_bookings']) * 100) : 0;
                                $scoreClass = $score >= 80 ? 'excellent' : ($score >= 60 ? 'good' : 'needs-improvement');
                                ?>
                                <span class="score score-<?= $scoreClass ?>"><?= $score ?>%</span>
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
                                    <span class="status-badge status-approved"><?= $campus['approved_bookings'] ?></span>
                                    <span class="status-text">Approved</span>
                                </div>
                                <div class="status-item">
                                    <span class="status-badge status-pending"><?= $campus['pending_bookings'] ?></span>
                                    <span class="status-text">Pending</span>
                                </div>
                                <div class="status-item">
                                    <span class="status-badge status-rejected"><?= $campus['rejected_bookings'] ?></span>
                                    <span class="status-text">Rejected</span>
                                </div>
                                <div class="status-item">
                                    <span class="status-badge status-completed"><?= $campus['completed_bookings'] ?></span>
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

        <!-- Detailed Table -->
        <div class="bookings-table-container">
            <div class="table-header">
                <h3>Campus Performance Details</h3>
                <div class="table-tools">
                    <div class="datatable-controls">
                        <span class="badge badge-info badge-lg"><?= count($campusData) ?> Campuses</span>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table id="campusTable" class="admin-table display nowrap" style="width:100%">
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
                        <?php foreach ($campusData as $campus): ?>
                            <?php 
                            $successRate = $campus['total_bookings'] > 0 ? 
                                round(($campus['approved_bookings'] / $campus['total_bookings']) * 100, 1) : 0;
                            ?>
                            <tr>
                                <td>
                                    <div class="campus-info">
                                        <strong><?= esc($campus['name']) ?></strong>
                                    </div>
                                </td>
                                <td><strong><?= $campus['total_bookings'] ?></strong></td>
                                <td><span class="status-badge status-approved"><?= $campus['approved_bookings'] ?></span></td>
                                <td><span class="status-badge status-pending"><?= $campus['pending_bookings'] ?></span></td>
                                <td><span class="status-badge status-rejected"><?= $campus['rejected_bookings'] ?></span></td>
                                <td><span class="status-badge status-completed"><?= $campus['completed_bookings'] ?></span></td>
                                <td><strong>UGX <?= number_format($campus['total_revenue']) ?></strong></td>
                                <td>
                                    <?php 
                                    $rateClass = $successRate >= 80 ? 'success' : ($successRate >= 60 ? 'warning' : 'danger');
                                    ?>
                                    <span class="rate-badge rate-<?= $rateClass ?>"><?= $successRate ?>%</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.filter-panel {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.filter-container {
    padding: 1.5rem;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) auto;
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.filter-input,
.filter-select {
    padding: 0.5rem 0.75rem;
    border: 1px solid #ced4da;
    border-radius: 4px;
    font-size: 0.9rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.filter-input:focus,
.filter-select:focus {
    border-color: #007bff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
}

.campus-cards-container {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #dee2e6;
    background: linear-gradient(135deg, #17a2b8 0%, #007bff 100%);
    color: white;
    border-radius: 8px 8px 0 0;
}

.section-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.section-tools {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.campus-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
    padding: 1.5rem;
}

.campus-card {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.campus-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.campus-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

.performance-score .score {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 600;
}

.score-excellent {
    background: rgba(40, 167, 69, 0.2);
    color: #155724;
}

.score-good {
    background: rgba(255, 193, 7, 0.2);
    color: #856404;
}

.score-needs-improvement {
    background: rgba(220, 53, 69, 0.2);
    color: #721c24;
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
    color: #495057;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.85rem;
    color: #6c757d;
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
    color: #6c757d;
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: #e9ecef;
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
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

.bookings-table-container {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #dee2e6;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px 8px 0 0;
}

.table-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.table-tools {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.datatable-controls {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    font-size: 0.9rem;
}

.admin-table thead th {
    background: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    padding: 1rem 0.75rem;
    font-weight: 600;
    color: #495057;
    text-align: left;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.admin-table tbody td {
    padding: 1rem 0.75rem;
    border-bottom: 1px solid #dee2e6;
    vertical-align: middle;
}

.admin-table tbody tr:hover {
    background-color: #f8f9fa;
}

.campus-info strong {
    color: #495057;
    font-size: 1rem;
}

.status-badge {
    display: inline-block;
    padding: 0.35rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-approved {
    background: #d4edda;
    color: #155724;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-rejected {
    background: #f8d7da;
    color: #721c24;
}

.status-completed {
    background: #d1ecf1;
    color: #0c5460;
}

.rate-badge {
    display: inline-block;
    padding: 0.35rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
}

.rate-success {
    background: #d4edda;
    color: #155724;
}

.rate-warning {
    background: #fff3cd;
    color: #856404;
}

.rate-danger {
    background: #f8d7da;
    color: #721c24;
}

.badge-lg {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
}

@media (max-width: 768px) {
    .filter-container {
        grid-template-columns: 1fr;
    }
    
    .section-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .campus-grid {
        grid-template-columns: 1fr;
        padding: 1rem;
    }
    
    .stats-row {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
    
    .status-breakdown {
        grid-template-columns: 1fr;
    }
    
    .table-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
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
            emptyTable: "No campus data available",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });

    // Initialize Select2 for campus filter
    $('#campus_id').select2({
        placeholder: 'Select campus...',
        allowClear: true,
        width: '100%'
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

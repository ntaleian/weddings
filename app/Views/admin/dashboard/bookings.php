<?= $this->extend('layouts/admin/admin') ?>

<?= $this->section('main_content') ?>
<div class="content-wrapper">
    <section id="bookings" class="content-section">
        <div class="section-header">
            <h2>Booking Management</h2>
            <div class="section-actions">
                <button class="btn btn-secondary" onclick="toggleFilter()">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>
                <a href="<?= site_url('admin/booking/new') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Add Booking
                </a>
            </div>
        </div>

        <!-- Filter Panel -->
        <div id="filterPanel" class="filter-panel" style="display: none;">
            <div class="filter-content">
                <div class="filter-group">
                    <label>Status:</label>
                    <select id="statusFilter">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Date Range:</label>
                    <input type="date" id="startDate">
                    <input type="date" id="endDate">
                </div>
                <div class="filter-group">
                    <label>Campus:</label>
                    <select id="campusFilter">
                        <option value="">All Campuses</option>
                        <!-- Add campus options dynamically -->
                    </select>
                </div>
                <button class="btn btn-primary" onclick="applyFilters()">Apply Filters</button>
                <button class="btn btn-secondary" onclick="clearFilters()">Clear</button>
            </div>
        </div>

        <div class="table-container">
            <table class="data-table" id="bookingsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Couple</th>
                        <th>Venue</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bookings)): ?>
                        <tr>
                            <td colspan="7" class="text-center">No bookings found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td>#<?= str_pad($booking['id'], 4, '0', STR_PAD_LEFT) ?></td>
                            <td>
                                <div class="couple-info">
                                    <strong><?= esc($booking['bride_name'] . ' & ' . $booking['groom_name']) ?></strong>
                                    <small><?= esc($booking['user_email'] ?? '') ?></small>
                                </div>
                            </td>
                            <td><?= esc($booking['campus_name'] ?? 'Unknown Campus') ?></td>
                            <td>
                                <div class="date-info">
                                    <strong><?= date('M j, Y', strtotime($booking['wedding_date'])) ?></strong>
                                    <small><?= date('g:i A', strtotime($booking['wedding_time'])) ?></small>
                                </div>
                            </td>
                            <td><span class="status <?= $booking['status'] ?>"><?= ucfirst($booking['status']) ?></span></td>
                            <td><?= date('M j, Y', strtotime($booking['created_at'])) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?= site_url('admin/booking/' . $booking['id']) ?>" class="btn-action view" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($booking['status'] === 'pending'): ?>
                                        <button class="btn-action approve" onclick="approveBooking(<?= $booking['id'] ?>)" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn-action reject" onclick="rejectBooking(<?= $booking['id'] ?>)" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    <?php endif; ?>
                                    <a href="<?= site_url('admin/booking/' . $booking['id'] . '/edit') ?>" class="btn-action edit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($booking['status'] !== 'completed'): ?>
                                        <button class="btn-action delete" onclick="cancelBooking(<?= $booking['id'] ?>)" title="Cancel">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
function toggleFilter() {
    const panel = document.getElementById('filterPanel');
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
}

function approveBooking(id) {
    if (confirm('Are you sure you want to approve this booking?')) {
        window.location.href = `<?= site_url('admin/booking/') ?>${id}/approve`;
    }
}

function rejectBooking(id) {
    const reason = prompt('Please provide a reason for rejection:');
    if (reason) {
        // Send rejection with reason
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= site_url('admin/booking/') ?>${id}/reject`;
        
        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'reason';
        reasonInput.value = reason;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        
        form.appendChild(reasonInput);
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function cancelBooking(id) {
    const reason = prompt('Please provide a reason for cancellation:');
    if (reason) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= site_url('admin/booking/') ?>${id}/cancel`;
        
        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'reason';
        reasonInput.value = reason;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        
        form.appendChild(reasonInput);
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function applyFilters() {
    // Implement filter functionality
    console.log('Applying filters...');
}

function clearFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    document.getElementById('campusFilter').value = '';
}
</script>
<?= $this->endSection() ?>

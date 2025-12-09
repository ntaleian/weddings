<?= $this->extend('layouts/admin/admin') ?>

<?= $this->section('main_content') ?>
<div class="content-wrapper">
    <section id="users" class="content-section">
        <div class="section-header">
            <h2>User Management</h2>
            <div class="section-stats">
                <span class="stat-badge">
                    <i class="fas fa-users"></i>
                    <?= number_format($totalUsers) ?> Total Users
                </span>
            </div>
            <div class="section-actions">
                <button class="btn btn-secondary" onclick="exportUsers()">
                    <i class="fas fa-download"></i>
                    Export
                </button>
                <button class="btn btn-secondary" onclick="toggleUserFilter()">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>
                <a href="<?= site_url('admin/user/new') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Add User
                </a>
            </div>
        </div>

        <!-- Filter Panel -->
        <div id="userFilterPanel" class="filter-panel" style="display: none;">
            <div class="filter-content">
                <div class="filter-group">
                    <label>Status:</label>
                    <select id="userStatusFilter">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Registration Date:</label>
                    <input type="date" id="userStartDate">
                    <input type="date" id="userEndDate">
                </div>
                <div class="filter-group">
                    <label>Search:</label>
                    <input type="text" id="userSearch" placeholder="Search by name or email">
                </div>
                <button class="btn btn-primary" onclick="applyUserFilters()">Apply Filters</button>
                <button class="btn btn-secondary" onclick="clearUserFilters()">Clear</button>
            </div>
        </div>

        <div class="table-container">
            <table class="data-table" id="usersTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Bookings</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="8" class="text-center">No users found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td>#<?= str_pad($user['id'], 4, '0', STR_PAD_LEFT) ?></td>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        <img src="<?= base_url('public/images/avatars/' . ($user['avatar'] ?? 'default-avatar.png')) ?>" alt="Avatar">
                                    </div>
                                    <div class="user-details">
                                        <strong><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></strong>
                                        <small>ID: <?= $user['id'] ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="mailto:<?= esc($user['email']) ?>"><?= esc($user['email']) ?></a>
                            </td>
                            <td>
                                <?php if (!empty($user['phone'])): ?>
                                    <a href="tel:<?= esc($user['phone']) ?>"><?= esc($user['phone']) ?></a>
                                <?php else: ?>
                                    <span class="text-muted">Not provided</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status <?= $user['is_active'] ? 'active' : 'inactive' ?>">
                                    <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <span class="booking-count">
                                    <?= $this->getUserBookingCount($user['id']) ?> bookings
                                </span>
                            </td>
                            <td>
                                <span class="join-date">
                                    <?= date('M j, Y', strtotime($user['created_at'])) ?>
                                </span>
                                <small><?= date('g:i A', strtotime($user['created_at'])) ?></small>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="<?= site_url('admin/user/' . $user['id']) ?>" class="btn-action view" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= site_url('admin/user/' . $user['id'] . '/edit') ?>" class="btn-action edit" title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($user['is_active']): ?>
                                        <button class="btn-action warning" onclick="toggleUserStatus(<?= $user['id'] ?>, 0)" title="Deactivate">
                                            <i class="fas fa-user-slash"></i>
                                        </button>
                                    <?php else: ?>
                                        <button class="btn-action success" onclick="toggleUserStatus(<?= $user['id'] ?>, 1)" title="Activate">
                                            <i class="fas fa-user-check"></i>
                                        </button>
                                    <?php endif; ?>
                                    <a href="mailto:<?= esc($user['email']) ?>" class="btn-action info" title="Send Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                    <button class="btn-action danger" onclick="deleteUser(<?= $user['id'] ?>)" title="Delete User">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="table-pagination">
            <!-- Add pagination here if needed -->
        </div>
    </section>
</div>

<script>
function toggleUserFilter() {
    const panel = document.getElementById('userFilterPanel');
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
}

function toggleUserStatus(id, status) {
    const action = status ? 'activate' : 'deactivate';
    if (confirm(`Are you sure you want to ${action} this user?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= site_url('admin/user/') ?>${id}/${action}`;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteUser(id) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone and will also delete all associated bookings.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= site_url('admin/user/') ?>${id}/delete`;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function exportUsers() {
    window.location.href = '<?= site_url('admin/users/export') ?>';
}

function applyUserFilters() {
    // Implement user filter functionality
    console.log('Applying user filters...');
}

function clearUserFilters() {
    document.getElementById('userStatusFilter').value = '';
    document.getElementById('userStartDate').value = '';
    document.getElementById('userEndDate').value = '';
    document.getElementById('userSearch').value = '';
}
</script>

<?php
// Helper method that would be better in the controller
function getUserBookingCount($userId) {
    $db = \Config\Database::connect();
    return $db->table('bookings')->where('user_id', $userId)->countAllResults();
}
?>
<?= $this->endSection() ?>

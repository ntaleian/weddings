<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>
<?php
$pageActions = '
    <a href="' . site_url('admin/campus/new') . '" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Add Campus
    </a>
';
?>
<?= $this->include('admin_template/partials/page_header', [
    'title' => 'Manage Campuses',
    'subtitle' => 'View and manage all campus locations',
    'actions' => $pageActions
]) ?>

<?php if (empty($campuses)): ?>
    <div class="card">
        <div class="card-body text-center" style="padding: 3rem;">
            <i class="fas fa-building" style="font-size: 3rem; color: #cbd5e0; margin-bottom: 1rem;"></i>
            <h3>No Campuses Found</h3>
            <p class="text-muted">Start by adding your first campus location.</p>
            <a href="<?= site_url('admin/campus/new') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Campus
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="campuses-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
        <?php foreach ($campuses as $campus): ?>
        <div class="card" style="overflow: hidden;">
            <div style="position: relative; height: 200px; overflow: hidden;">
                <img src="<?= base_url('images/campuses/' . ($campus['image_path'] ?? 'default-campus.jpg')) ?>" 
                     alt="<?= esc($campus['name']) ?>" 
                     style="width: 100%; height: 100%; object-fit: cover;">
                <div style="position: absolute; top: 10px; right: 10px;">
                    <span class="badge <?= $campus['is_active'] ? 'badge-success' : 'badge-secondary' ?>">
                        <?= $campus['is_active'] ? 'Active' : 'Inactive' ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <h3 style="margin-bottom: 0.5rem; font-size: 1.25rem;"><?= esc($campus['name']) ?></h3>
                <p style="color: #6c757d; margin-bottom: 0.5rem;">
                    <i class="fas fa-map-marker-alt"></i> <?= esc($campus['location']) ?>
                </p>
                <p style="color: #6c757d; margin-bottom: 1rem;">
                    <i class="fas fa-users"></i> Capacity: <?= number_format($campus['capacity']) ?> guests
                </p>
                
                <?php if (!empty($campus['facilities'])): ?>
                <div style="margin-bottom: 1rem; padding: 0.75rem; background: #f8f9fa; border-radius: 6px;">
                    <strong style="font-size: 0.85rem;">Facilities:</strong>
                    <p style="font-size: 0.85rem; margin: 0.25rem 0 0 0; color: #495057;"><?= esc($campus['facilities']) ?></p>
                </div>
                <?php endif; ?>
                
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; margin-bottom: 1rem; padding: 0.75rem; background: #f8f9fa; border-radius: 6px;">
                    <div style="text-align: center;">
                        <div style="font-size: 1.25rem; font-weight: bold; color: var(--primary);"><?= $campus['total_bookings'] ?? 0 ?></div>
                        <div style="font-size: 0.75rem; color: #6c757d;">Total</div>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 1.25rem; font-weight: bold; color: var(--warning);"><?= $campus['pending_bookings'] ?? 0 ?></div>
                        <div style="font-size: 0.75rem; color: #6c757d;">Pending</div>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 1.25rem; font-weight: bold; color: var(--success);"><?= $campus['approved_bookings'] ?? 0 ?></div>
                        <div style="font-size: 0.75rem; color: #6c757d;">Approved</div>
                    </div>
                    <div style="text-align: center;">
                        <div style="font-size: 1.25rem; font-weight: bold; color: var(--info);"><?= $campus['completed_bookings'] ?? 0 ?></div>
                        <div style="font-size: 0.75rem; color: #6c757d;">Completed</div>
                    </div>
                </div>
                
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    <a href="<?= site_url('admin/campus/' . $campus['id']) ?>" class="btn btn-sm btn-info" style="flex: 1;">
                        <i class="fas fa-eye"></i> View
                    </a>
                    <a href="<?= site_url('admin/campus/' . $campus['id'] . '/edit') ?>" class="btn btn-sm btn-secondary" style="flex: 1;">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <?php if ($campus['is_active']): ?>
                        <button class="btn btn-sm btn-warning" onclick="toggleVenueStatus(<?= $campus['id'] ?>, 0)" style="flex: 1;">
                            <i class="fas fa-pause"></i> Deactivate
                        </button>
                    <?php else: ?>
                        <button class="btn btn-sm btn-success" onclick="toggleVenueStatus(<?= $campus['id'] ?>, 1)" style="flex: 1;">
                            <i class="fas fa-play"></i> Activate
                        </button>
                    <?php endif; ?>
                    <button class="btn btn-sm btn-danger" onclick="deleteVenue(<?= $campus['id'] ?>)" style="flex: 1;">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function toggleVenueStatus(id, status) {
    const action = status ? 'activate' : 'deactivate';
    if (confirm(`Are you sure you want to ${action} this venue?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= site_url('admin/campus/') ?>${id}/toggle-status`;
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'is_active';
        statusInput.value = status;
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        
        form.appendChild(statusInput);
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteVenue(id) {
    if (confirm('Are you sure you want to delete this venue? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= site_url('admin/campus/') ?>${id}`;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= csrf_token() ?>';
        csrfInput.value = '<?= csrf_hash() ?>';
        
        form.appendChild(methodInput);
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?= $this->endSection() ?>

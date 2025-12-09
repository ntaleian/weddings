<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>
<?= $this->include('admin_template/partials/page_header', [
    'title' => 'Edit Pastor',
    'subtitle' => 'Update pastor information and settings',
    'actions' => '<a href="' . site_url('admin/pastors') . '" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back to Pastors</a>'
]) ?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pastor Information</h3>
            </div>
            <div class="card-body">
                <form id="pastorForm" action="<?= site_url('admin/pastor/' . $pastor['id']) ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-input" id="name" name="name" 
                                   value="<?= old('name') ?: esc($pastor['name']) ?>" required>
                            <?php if (session('errors.name')): ?>
                                <div class="form-error"><?= session('errors.name') ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="campus_id">Campus <span class="text-danger">*</span></label>
                            <select class="form-input" id="campus_id" name="campus_id" required>
                                <option value="">Select Campus</option>
                                <?php foreach ($campuses as $campus): ?>
                                    <option value="<?= $campus['id'] ?>" 
                                            <?= (old('campus_id') ?: $pastor['campus_id']) == $campus['id'] ? 'selected' : '' ?>>
                                        <?= esc($campus['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (session('errors.campus_id')): ?>
                                <div class="form-error"><?= session('errors.campus_id') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="phone">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-input" id="phone" name="phone" 
                                   value="<?= old('phone') ?: esc($pastor['phone']) ?>" required>
                            <?php if (session('errors.phone')): ?>
                                <div class="form-error"><?= session('errors.phone') ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-input" id="email" name="email" 
                                   value="<?= old('email') ?: esc($pastor['email']) ?>" required>
                            <?php if (session('errors.email')): ?>
                                <div class="form-error"><?= session('errors.email') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="specialization">Specialization</label>
                        <select class="form-input" id="specialization" name="specialization">
                            <option value="">Select Specialization</option>
                            <option value="Senior Pastor" <?= (old('specialization') ?: $pastor['specialization']) == 'Senior Pastor' ? 'selected' : '' ?>>Senior Pastor</option>
                            <option value="Associate Pastor" <?= (old('specialization') ?: $pastor['specialization']) == 'Associate Pastor' ? 'selected' : '' ?>>Associate Pastor</option>
                            <option value="Youth Pastor" <?= (old('specialization') ?: $pastor['specialization']) == 'Youth Pastor' ? 'selected' : '' ?>>Youth Pastor</option>
                            <option value="Wedding Pastor" <?= (old('specialization') ?: $pastor['specialization']) == 'Wedding Pastor' ? 'selected' : '' ?>>Wedding Pastor</option>
                            <option value="Counseling Pastor" <?= (old('specialization') ?: $pastor['specialization']) == 'Counseling Pastor' ? 'selected' : '' ?>>Counseling Pastor</option>
                            <option value="General" <?= (old('specialization') ?: $pastor['specialization']) == 'General' ? 'selected' : '' ?>>General</option>
                        </select>
                        <small class="form-help">Optional: Select the pastor's area of specialization</small>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="is_available" name="is_available" value="1" 
                                   <?= (old('is_available') !== null ? old('is_available') : $pastor['is_available']) ? 'checked' : '' ?>>
                            <span class="checkmark"></span>
                            Available for weddings
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="pastor_image">Pastor Photo</label>
                        <?php if (!empty($pastor['image_path'])): ?>
                            <div class="current-image mb-2">
                                <img src="<?= base_url('public/images/pastors/' . $pastor['image_path']) ?>" 
                                     alt="Current Pastor Photo" class="img-thumbnail" style="max-width: 150px; border-radius: 8px;">
                                <p class="text-muted mt-1">Current photo</p>
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-input" id="pastor_image" name="pastor_image" accept="image/*">
                        <small class="form-help">
                            Upload a new photo to replace the current one. Supported formats: JPG, PNG, WebP. Maximum size: 5MB.
                        </small>
                        <?php if (session('errors.pastor_image')): ?>
                            <div class="form-error"><?= session('errors.pastor_image') ?></div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button type="submit" form="pastorForm" class="btn btn-success">
                    <i class="fas fa-save"></i> Update Pastor
                </button>
                <a href="<?= site_url('admin/pastors') ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="button" class="btn btn-danger" onclick="deletePastor(<?= $pastor['id'] ?>)">
                    <i class="fas fa-trash"></i> Delete Pastor
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pastor Statistics</h3>
            </div>
            <div class="card-body">
                <div class="stats-grid-mini">
                    <?= $this->include('admin_template/partials/stat_card', ['icon' => 'fas fa-calendar-check', 'number' => number_format($stats['total_weddings'] ?? 0), 'label' => 'Total Weddings', 'type' => 'info-mini']) ?>
                    <?= $this->include('admin_template/partials/stat_card', ['icon' => 'fas fa-clock', 'number' => number_format($stats['this_month'] ?? 0), 'label' => 'This Month', 'type' => 'success-mini']) ?>
                    <?= $this->include('admin_template/partials/stat_card', ['icon' => 'fas fa-hourglass-half', 'number' => number_format($stats['pending_bookings'] ?? 0), 'label' => 'Pending', 'type' => 'warning-mini']) ?>
                </div>

                <div class="alert alert-info mt-3">
                    <h5><i class="fas fa-info-circle"></i> Information</h5>
                    <ul class="mb-0">
                        <li><strong>Pastor ID:</strong> <?= $pastor['id'] ?></li>
                        <li><strong>Created:</strong> <?= isset($pastor['created_at']) ? date('M j, Y', strtotime($pastor['created_at'])) : 'Unknown' ?></li>
                        <li><strong>Status:</strong> 
                            <?= $pastor['is_available'] ? '<span class="badge badge-success">Available</span>' : '<span class="badge badge-warning">Unavailable</span>' ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}

.form-group {
    margin-bottom: 1.25rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #495057;
}

.form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-input:focus {
    outline: none;
    border-color: #64017f;
}

.form-help {
    display: block;
    margin-top: 0.375rem;
    color: #6c757d;
    font-size: 0.875rem;
}

.form-error {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.375rem;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    font-size: 1rem;
}

.checkbox-label input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.stats-grid-mini {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function deletePastor(id) {
    if (confirm('Are you sure you want to delete this pastor? This action cannot be undone and will affect any associated bookings.')) {
        // Create a form and submit DELETE request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= site_url('admin/pastor/') ?>${id}`;
        
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

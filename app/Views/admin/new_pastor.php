<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>
<?= $this->include('admin_template/partials/page_header', [
    'title' => 'Add New Pastor',
    'subtitle' => 'Register a new pastor in the system',
    'actions' => '<a href="' . site_url('admin/pastors') . '" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back to Pastors</a>'
]) ?>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pastor Information</h3>
            </div>
            <div class="card-body">
                <form id="pastorForm" action="<?= site_url('admin/pastors') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-input" id="name" name="name" 
                                   value="<?= old('name') ?>" required>
                            <?php if (session('errors.name')): ?>
                                <div class="form-error"><?= session('errors.name') ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="campus_id">Campus <span class="text-danger">*</span></label>
                            <select class="form-input" id="campus_id" name="campus_id" required>
                                <option value="">Select Campus</option>
                                <?php foreach ($campuses as $campus): ?>
                                    <option value="<?= $campus['id'] ?>" <?= old('campus_id') == $campus['id'] ? 'selected' : '' ?>>
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
                                   value="<?= old('phone') ?>" required>
                            <?php if (session('errors.phone')): ?>
                                <div class="form-error"><?= session('errors.phone') ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-input" id="email" name="email" 
                                   value="<?= old('email') ?>" required>
                            <?php if (session('errors.email')): ?>
                                <div class="form-error"><?= session('errors.email') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="specialization">Specialization</label>
                        <select class="form-input" id="specialization" name="specialization">
                            <option value="">Select Specialization</option>
                            <option value="Senior Pastor" <?= old('specialization') == 'Senior Pastor' ? 'selected' : '' ?>>Senior Pastor</option>
                            <option value="Associate Pastor" <?= old('specialization') == 'Associate Pastor' ? 'selected' : '' ?>>Associate Pastor</option>
                            <option value="Youth Pastor" <?= old('specialization') == 'Youth Pastor' ? 'selected' : '' ?>>Youth Pastor</option>
                            <option value="Wedding Pastor" <?= old('specialization') == 'Wedding Pastor' ? 'selected' : '' ?>>Wedding Pastor</option>
                            <option value="Counseling Pastor" <?= old('specialization') == 'Counseling Pastor' ? 'selected' : '' ?>>Counseling Pastor</option>
                            <option value="General" <?= old('specialization') == 'General' ? 'selected' : '' ?>>General</option>
                        </select>
                        <small class="form-help">Optional: Select the pastor's area of specialization</small>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="is_available" name="is_available" value="1" 
                                   <?= old('is_available') ? 'checked' : 'checked' ?>>
                            <span class="checkmark"></span>
                            Available for weddings
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="pastor_image">Pastor Photo (Optional)</label>
                        <input type="file" class="form-input" id="pastor_image" name="pastor_image" accept="image/*">
                        <small class="form-help">
                            Upload a professional photo of the pastor. Supported formats: JPG, PNG, WebP. Maximum size: 5MB.
                        </small>
                        <?php if (session('errors.pastor_image')): ?>
                            <div class="form-error"><?= session('errors.pastor_image') ?></div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button type="submit" form="pastorForm" class="btn btn-success">
                    <i class="fas fa-save"></i> Add Pastor
                </button>
                <a href="<?= site_url('admin/pastors') ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Information</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h5><i class="fas fa-info-circle"></i> Guidelines</h5>
                    <ul class="mb-0">
                        <li>All fields marked with <span class="text-danger">*</span> are required</li>
                        <li>Pastor will be associated with the selected campus</li>
                        <li>Phone number should include country code for international numbers</li>
                        <li>Email should be unique for each pastor</li>
                        <li>Specialization helps in assigning appropriate pastors to weddings</li>
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

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->endSection() ?>

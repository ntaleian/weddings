<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>
<?= $this->include('admin_template/partials/page_header', [
    'title' => 'Add New Campus',
    'subtitle' => 'Create a new church campus location',
    'actions' => '<a href="' . site_url('admin/venues') . '" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back to Campuses</a>'
]) ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger mb-4">
        <i class="fas fa-exclamation-circle"></i>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="<?= site_url('admin/campus/store') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="form-section mb-4">
                <h3 class="form-section-title">Basic Information</h3>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Campus Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-input" id="name" name="name" value="<?= old('name') ?>" required>
                        <?php if (session('errors.name')): ?>
                            <div class="form-error"><?= session('errors.name') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="location">Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-input" id="location" name="location" value="<?= old('location') ?>" required>
                        <small class="form-help">Full address or location description</small>
                        <?php if (session('errors.location')): ?>
                            <div class="form-error"><?= session('errors.location') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="capacity">Capacity <span class="text-danger">*</span></label>
                        <input type="number" class="form-input" id="capacity" name="capacity" min="1" value="<?= old('capacity') ?>" required>
                        <small class="form-help">Maximum number of guests</small>
                        <?php if (session('errors.capacity')): ?>
                            <div class="form-error"><?= session('errors.capacity') ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group">
                        <label for="cost">Cost (UGX) <span class="text-danger">*</span></label>
                        <input type="number" class="form-input" id="cost" name="cost" min="0" step="1000" value="<?= old('cost') ?>" required>
                        <small class="form-help">Campus rental cost</small>
                        <?php if (session('errors.cost')): ?>
                            <div class="form-error"><?= session('errors.cost') ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="form-section mb-4">
                <h3 class="form-section-title">Campus Image</h3>
                
                <div class="form-group">
                    <label for="image">Campus Image</label>
                    <div class="image-upload-area" id="imageUploadArea">
                        <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                        <div class="upload-placeholder">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click to upload campus image</p>
                            <small>Recommended: 800x600px, JPG/PNG format</small>
                        </div>
                        <img id="imagePreview" class="image-preview" style="display: none;">
                    </div>
                    <?php if (session('errors.image')): ?>
                        <div class="form-error"><?= session('errors.image') ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-section mb-4">
                <h3 class="form-section-title">Additional Details</h3>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-input" id="description" name="description" rows="4" placeholder="Describe the campus features and ambiance..."><?= old('description') ?></textarea>
                    <?php if (session('errors.description')): ?>
                        <div class="form-error"><?= session('errors.description') ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="facilities">Facilities & Amenities</label>
                    <textarea class="form-input" id="facilities" name="facilities" rows="3" placeholder="List available facilities (e.g., Parking, Air Conditioning, Sound System, etc.)"><?= old('facilities') ?></textarea>
                    <small class="form-help">Separate multiple facilities with commas</small>
                    <?php if (session('errors.facilities')): ?>
                        <div class="form-error"><?= session('errors.facilities') ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-section mb-4">
                <h3 class="form-section-title">Availability</h3>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" <?= old('is_active', '1') ? 'checked' : '' ?>>
                        <span class="checkmark"></span>
                        Make this campus available for bookings immediately
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Campus
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.form-section {
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e9ecef;
}

.form-section:last-of-type {
    border-bottom: none;
}

.form-section-title {
    color: #64017f;
    margin-bottom: 1.25rem;
    font-size: 1.25rem;
    font-weight: 600;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
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

.image-upload-area {
    position: relative;
    border: 2px dashed #e9ecef;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    transition: border-color 0.3s ease;
    cursor: pointer;
}

.image-upload-area:hover {
    border-color: #64017f;
}

.image-upload-area input[type="file"] {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.upload-placeholder i {
    font-size: 3rem;
    color: #6c757d;
    margin-bottom: 1rem;
}

.upload-placeholder p {
    font-size: 1.1rem;
    color: #495057;
    margin-bottom: 0.375rem;
}

.upload-placeholder small {
    color: #6c757d;
}

.image-preview {
    max-width: 100%;
    max-height: 300px;
    border-radius: 8px;
    object-fit: cover;
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

.form-actions {
    display: flex;
    gap: 0.9375rem;
    justify-content: flex-end;
    margin-top: 1.5rem;
    padding-top: 1.25rem;
    border-top: 1px solid #e9ecef;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column-reverse;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const placeholder = document.querySelector('.upload-placeholder');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        };
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.style.display = 'none';
        placeholder.style.display = 'block';
    }
}

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const location = document.getElementById('location').value.trim();
    const capacity = document.getElementById('capacity').value;
    const cost = document.getElementById('cost').value;
    
    if (!name || !location || !capacity || !cost) {
        e.preventDefault();
        alert('Please fill in all required fields.');
        return false;
    }
    
    if (parseInt(capacity) < 1) {
        e.preventDefault();
        alert('Capacity must be at least 1 guest.');
        return false;
    }
    
    if (parseInt(cost) < 0) {
        e.preventDefault();
        alert('Cost cannot be negative.');
        return false;
    }
});
</script>
<?= $this->endSection() ?>

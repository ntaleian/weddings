<?= $this->extend('admin_template/layout') ?>

<?= $this->section('content') ?>
<?= $this->include('admin_template/partials/page_header', [
    'title' => 'Edit Campus',
    'subtitle' => 'Update campus information and settings',
    'actions' => '
        <a href="' . site_url('admin/campus/' . $campus['id']) . '" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Campus
        </a>
        <a href="' . site_url('admin/venues') . '" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-list"></i> Manage Campuses
        </a>
    '
]) ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger mb-4">
        <i class="fas fa-exclamation-circle"></i>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success mb-4">
        <i class="fas fa-check-circle"></i>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="<?= site_url('admin/campus/' . $campus['id'] . '/update') ?>" method="post" enctype="multipart/form-data" class="edit-campus-form">
            <?= csrf_field() ?>
            
            <div class="form-grid-layout">
                <!-- Campus Image Section -->
                <div class="image-upload-section">
                    <div class="current-image mb-4">
                        <h3 class="form-section-title">Current Image</h3>
                        <div class="image-preview-container">
                            <img src="<?= base_url('public/images/campuses/' . ($campus['image_path'] ?? 'default-campus.jpg')) ?>" 
                                 alt="<?= esc($campus['name']) ?>" 
                                 class="current-campus-image" 
                                 id="imagePreview">
                            <div class="image-overlay">
                                <span class="badge <?= $campus['is_active'] ? 'badge-success' : 'badge-secondary' ?>">
                                    <?= $campus['is_active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="image-upload">
                        <label for="campus_image">Update Campus Image</label>
                        <div class="file-upload-wrapper">
                            <input type="file" 
                                   id="campus_image" 
                                   name="campus_image" 
                                   accept="image/*"
                                   onchange="previewImage(this)">
                            <div class="file-upload-display">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Choose new image or drag & drop</span>
                                <small>Supported formats: JPG, PNG, WebP (Max: 5MB)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Campus Information Section -->
                <div class="campus-info-section">
                    <h3 class="form-section-title">Campus Information</h3>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name">Campus Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-input"
                                   id="name" 
                                   name="name" 
                                   value="<?= esc($campus['name']) ?>" 
                                   required
                                   placeholder="Enter campus name">
                        </div>
                        
                        <div class="form-group">
                            <label for="location">Location <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-input"
                                   id="location" 
                                   name="location" 
                                   value="<?= esc($campus['location']) ?>" 
                                   required
                                   placeholder="Enter campus location">
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="capacity">Capacity <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-input"
                                   id="capacity" 
                                   name="capacity" 
                                   value="<?= $campus['capacity'] ?>" 
                                   required
                                   min="1"
                                   placeholder="Maximum number of guests">
                        </div>
                        
                        <div class="form-group">
                            <label for="is_active">Status</label>
                            <select class="form-input" id="is_active" name="is_active" required>
                                <option value="1" <?= $campus['is_active'] ? 'selected' : '' ?>>Active</option>
                                <option value="0" <?= !$campus['is_active'] ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-input" 
                                  id="description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="Describe the campus, its features, and what makes it special..."><?= esc($campus['description'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="facilities">Facilities & Amenities</label>
                        <input type="text" 
                               class="form-input"
                               id="facilities" 
                               name="facilities" 
                               value="<?= esc($campus['facilities'] ?? '') ?>"
                               placeholder="e.g. Parking, Sound System, Catering Kitchen, Garden (separate with commas)">
                        <small class="form-help">Separate each facility with a comma</small>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Campus
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.form-grid-layout {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.image-upload-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
}

.form-section-title {
    color: #64017f;
    margin-bottom: 1.25rem;
    font-size: 1.125rem;
    font-weight: 600;
}

.image-preview-container {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.current-campus-image {
    width: 100%;
    height: 250px;
    object-fit: cover;
    display: block;
}

.image-overlay {
    position: absolute;
    top: 15px;
    right: 15px;
}

.image-upload label {
    display: block;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.625rem;
}

.file-upload-wrapper {
    position: relative;
    border: 2px dashed #e9ecef;
    border-radius: 8px;
    padding: 1.875rem 1.25rem;
    text-align: center;
    background: white;
    transition: all 0.3s ease;
    cursor: pointer;
}

.file-upload-wrapper:hover {
    border-color: #64017f;
    background: rgba(100, 1, 127, 0.05);
}

.file-upload-wrapper input[type="file"] {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.file-upload-display i {
    font-size: 2rem;
    color: #64017f;
    margin-bottom: 0.625rem;
    display: block;
}

.file-upload-display span {
    display: block;
    color: #495057;
    font-weight: 500;
    margin-bottom: 0.3125rem;
}

.file-upload-display small {
    color: #6c757d;
    font-size: 0.875rem;
}

.campus-info-section {
    padding: 0;
}

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
    font-size: 0.9rem;
}

.form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.form-input:focus {
    outline: none;
    border-color: #64017f;
    box-shadow: 0 0 0 3px rgba(100, 1, 127, 0.1);
}

.form-input textarea {
    resize: vertical;
    min-height: 100px;
    font-family: inherit;
}

.form-help {
    color: #6c757d;
    font-size: 0.8rem;
    margin-top: 0.3125rem;
}

.form-actions {
    padding-top: 1.5rem;
    border-top: 1px solid #e9ecef;
    display: flex;
    justify-content: flex-end;
    gap: 0.9375rem;
}

@media (max-width: 968px) {
    .form-grid-layout {
        grid-template-columns: 1fr;
    }
    
    .image-upload-section {
        order: 2;
    }
    
    .campus-info-section {
        order: 1;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            preview.src = e.target.result;
        };
        
        reader.readAsDataURL(input.files[0]);
        
        // Update file upload display
        const fileName = input.files[0].name;
        const fileDisplay = input.parentElement.querySelector('.file-upload-display span');
        if (fileDisplay) {
            fileDisplay.textContent = `Selected: ${fileName}`;
        }
    }
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.edit-campus-form');
    
    form.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const location = document.getElementById('location').value.trim();
        const capacity = document.getElementById('capacity').value;
        
        if (!name || !location || !capacity) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }
        
        if (parseInt(capacity) < 1) {
            e.preventDefault();
            alert('Capacity must be at least 1 guest.');
            return false;
        }
    });
});
</script>
<?= $this->endSection() ?>

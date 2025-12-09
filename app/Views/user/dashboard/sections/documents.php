<style>
/* Tabs Styles */
.documents-tabs {
    margin-bottom: 20px;
}

.tab-buttons {
    display: flex;
    gap: 8px;
    border-bottom: 2px solid var(--light-gray);
    margin-bottom: 20px;
}

.tab-button {
    padding: 12px 24px;
    background: transparent;
    border: none;
    border-bottom: 3px solid transparent;
    color: var(--text-color);
    font-size: 0.95rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    align-items: center;
    gap: 8px;
}

.tab-button:hover {
    color: var(--primary-color);
    background: rgba(0, 0, 0, 0.02);
}

.tab-button.active {
    color: var(--primary-color);
    border-bottom-color: var(--primary-color);
    background: rgba(0, 0, 0, 0.02);
}

.tab-button.uploaded-tab.active {
    color: var(--success-color);
    border-bottom-color: var(--success-color);
}

.tab-button.required-tab.active {
    color: var(--error-color);
    border-bottom-color: var(--error-color);
}

.tab-button .badge {
    background: var(--light-gray);
    color: var(--text-color);
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    min-width: 24px;
    text-align: center;
}

.tab-button.uploaded-tab.active .badge {
    background: var(--success-color);
    color: white;
}

.tab-button.required-tab.active .badge {
    background: var(--error-color);
    color: white;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.documents-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 20px;
}

@media (max-width: 1400px) {
    .documents-container {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 992px) {
    .documents-container {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .documents-container {
        grid-template-columns: 1fr;
    }
}

.document-card {
    background: var(--white);
    border-radius: 10px;
    padding: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    border: 2px solid var(--light-gray);
    transition: all 0.3s ease;
}

.document-card:hover {
    transform: translateY(-2px);
    border-color: var(--primary-color);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.document-card.uploaded {
    border-color: var(--success-color);
    background: linear-gradient(to bottom, rgba(46, 204, 113, 0.03), var(--white));
    padding: 10px;
}

.document-card.uploaded:hover {
    border-color: var(--success-color);
    box-shadow: 0 2px 10px rgba(46, 204, 113, 0.2);
}

.document-header {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    margin-bottom: 8px;
}

.document-icon {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 0.85rem;
    flex-shrink: 0;
    margin-top: 2px;
}

.document-card.uploaded .document-icon {
    background: linear-gradient(135deg, var(--success-color), #27ae60);
}

.document-info {
    flex: 1;
    min-width: 0;
}

.document-info h3 {
    margin: 0 0 3px 0;
    color: var(--text-color);
    font-size: 0.85rem;
    font-weight: 600;
    line-height: 1.2;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.document-status {
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-block;
    white-space: nowrap;
}

.status-required {
    background: rgba(231, 76, 60, 0.1);
    color: var(--error-color);
}

.status-uploaded {
    background: rgba(46, 204, 113, 0.1);
    color: var(--success-color);
}

.status-pending {
    background: rgba(255, 193, 7, 0.1);
    color: #f39c12;
}

.upload-area {
    border: 2px dashed var(--light-gray);
    border-radius: 6px;
    padding: 10px 8px;
    text-align: center;
    margin-top: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
}

.upload-area:hover {
    border-color: var(--primary-color);
    background: rgba(100, 1, 127, 0.05);
}

.upload-area.dragover {
    border-color: var(--primary-color);
    background: rgba(100, 1, 127, 0.1);
}

.upload-area i {
    font-size: 1.2rem;
    color: var(--primary-color);
    margin-bottom: 4px;
}

.upload-area p {
    margin: 4px 0 2px 0;
    color: var(--text-color);
    font-weight: 500;
    font-size: 0.75rem;
    line-height: 1.2;
}

.upload-area small {
    color: #6c757d;
    display: block;
    margin-top: 2px;
    font-size: 0.7rem;
    line-height: 1.2;
}

.upload-note {
    display: block;
    margin-top: 6px;
    color: #495057;
    font-weight: 500;
    background: rgba(0, 123, 255, 0.1);
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
}

.document-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px;
    border: 1px solid var(--light-gray);
    border-radius: 6px;
    margin-top: 8px;
    background: #f8f9fa;
}

.document-name {
    display: flex;
    align-items: center;
    gap: 6px;
    flex: 1;
    font-weight: 500;
    font-size: 0.85rem;
    min-width: 0;
}

.document-name span {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.document-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.upload-date {
    color: #6c757d;
    font-size: 0.7rem;
}

.document-description {
    margin-bottom: 6px;
    line-height: 1.3;
    color: #6c757d;
    font-size: 0.75rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.no-documents {
    text-align: center;
    padding: 20px;
    color: #6c757d;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px dashed #dee2e6;
    margin-top: 15px;
}

.no-documents i {
    font-size: 2rem;
    margin-bottom: 10px;
    opacity: 0.5;
}

.no-documents p {
    margin: 0;
    font-style: italic;
}

.file-input-wrapper {
    position: relative;
}

.file-input-wrapper input[type="file"] {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.upload-progress {
    display: none;
    margin-top: 10px;
}

.upload-progress.active {
    display: block;
}

.progress-bar {
    width: 100%;
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: var(--primary-color);
    transition: width 0.3s ease;
}

.error-message {
    color: var(--error-color);
    font-size: 0.85rem;
    margin-top: 8px;
    display: none;
}

.error-message.show {
    display: block;
}
</style>

<div class="section-header">
    <h1>Documents</h1>
    <p>Upload all required documents for your wedding application. Each document must be 1MB or less.</p>
</div>

<?php
// Initialize required documents
$requiredDocs = $required_documents ?? [];

// Get booking information for document tracking
$userId = session()->get('user_id');
$bookingModel = new \App\Models\BookingModel();
$userBooking = $bookingModel->where('user_id', $userId)
                            ->where('is_draft', 0)
                            ->orderBy('created_at', 'DESC')
                            ->first();

$checklistDocs = [];
if ($userBooking && !empty($userBooking['admin_documents_checklist'])) {
    $checklistDocs = json_decode($userBooking['admin_documents_checklist'], true);
    if (!is_array($checklistDocs)) {
        $checklistDocs = [];
    }
}

// Calculate document upload progress
$totalDocs = count($requiredDocs);
$uploadedCount = 0;
$uploadedDocIds = [];

if (!empty($checklistDocs)) {
    foreach ($checklistDocs as $docId => $docData) {
        if (is_array($docData) && isset($docData['status']) && $docData['status'] === 'submitted') {
            $uploadedCount++;
            $uploadedDocIds[] = $docId;
        } elseif ($docData === 'submitted' || $docData === true) {
            $uploadedCount++;
            $uploadedDocIds[] = $docId;
        }
    }
}

$progressPercentage = $totalDocs > 0 ? round(($uploadedCount / $totalDocs) * 100) : 0;
?>

<!-- Document Progress Tracker -->
<div class="document-progress-tracker" style="background: white; border-radius: 12px; padding: 16px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; flex-wrap: wrap; gap: 10px;">
        <div>
            <h2 style="margin: 0 0 3px 0; color: var(--text-color); font-size: 1.2rem; font-weight: 600;">Document Upload Progress</h2>
            <p style="margin: 0; color: #6c757d; font-size: 0.85rem;">
                <?= $uploadedCount ?> of <?= $totalDocs ?> documents uploaded
            </p>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary-color); line-height: 1;">
                <?= $progressPercentage ?>%
            </div>
            <small style="color: #6c757d; font-size: 0.75rem;">Complete</small>
        </div>
    </div>
    
    <!-- Progress Bar -->
    <div style="background: #e9ecef; border-radius: 8px; height: 8px; overflow: hidden; margin-bottom: 12px; position: relative;">
        <div style="background: linear-gradient(90deg, var(--primary-color), var(--secondary-color)); height: 100%; width: <?= $progressPercentage ?>%; transition: width 0.5s ease; border-radius: 8px;"></div>
    </div>
    
    <!-- Quick Stats -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 12px;">
        <div style="text-align: center; padding: 10px; background: rgba(46, 204, 113, 0.1); border-radius: 6px;">
            <div style="font-size: 1.3rem; font-weight: 700; color: var(--success-color); margin-bottom: 3px;">
                <?= $uploadedCount ?>
            </div>
            <div style="font-size: 0.75rem; color: #6c757d; font-weight: 500;">Uploaded</div>
        </div>
        <div style="text-align: center; padding: 10px; background: rgba(231, 76, 60, 0.1); border-radius: 6px;">
            <div style="font-size: 1.3rem; font-weight: 700; color: var(--error-color); margin-bottom: 3px;">
                <?= $totalDocs - $uploadedCount ?>
            </div>
            <div style="font-size: 0.75rem; color: #6c757d; font-weight: 500;">Remaining</div>
        </div>
        <div style="text-align: center; padding: 10px; background: rgba(100, 1, 127, 0.1); border-radius: 6px;">
            <div style="font-size: 1.3rem; font-weight: 700; color: var(--primary-color); margin-bottom: 3px;">
                <?= $totalDocs ?>
            </div>
            <div style="font-size: 0.75rem; color: #6c757d; font-weight: 500;">Total</div>
        </div>
    </div>
    
    <?php if ($uploadedCount < $totalDocs): ?>
    <div style="padding: 10px; background: rgba(255, 193, 7, 0.1); border-left: 3px solid #f39c12; border-radius: 4px;">
        <p style="margin: 0; color: #856404; font-size: 0.8rem; line-height: 1.4;">
            <i class="fas fa-info-circle" style="margin-right: 6px;"></i>
            <strong>Note:</strong> You can upload documents one at a time. Your progress is saved automatically.
        </p>
    </div>
    <?php else: ?>
    <div style="padding: 10px; background: rgba(46, 204, 113, 0.1); border-left: 3px solid var(--success-color); border-radius: 4px;">
        <p style="margin: 0; color: #155724; font-size: 0.8rem; line-height: 1.4;">
            <i class="fas fa-check-circle" style="margin-right: 6px;"></i>
            <strong>Great job!</strong> All required documents have been uploaded. Your application is being reviewed.
        </p>
    </div>
    <?php endif; ?>
</div>

<div class="documents-container">
    <?php 
    $uploadedDocs = [];
    
    // Get uploaded documents status
    if (!empty($documents)) {
        foreach ($documents as $doc) {
            if (isset($doc['name'])) {
                $uploadedDocs[strtolower(str_replace(' ', '_', $doc['name']))] = $doc;
            }
        }
    }
    
    // Checklist docs already calculated above
    
    // Sort documents: uploaded first, then required
    $sortedDocs = [];
    $uploadedDocsList = [];
    $requiredDocsList = [];
    
    foreach ($requiredDocs as $doc) {
        $docId = $doc['id'] ?? strtolower(str_replace(' ', '_', $doc['name'] ?? ''));
        $isUploaded = isset($checklistDocs[$docId]) && 
                     (isset($checklistDocs[$docId]['status']) && $checklistDocs[$docId]['status'] === 'submitted');
        
        $doc['docId'] = $docId;
        $doc['isUploaded'] = $isUploaded;
        $doc['uploadedFile'] = $isUploaded ? $checklistDocs[$docId] : null;
        
        if ($isUploaded) {
            $uploadedDocsList[] = $doc;
        } else {
            $requiredDocsList[] = $doc;
        }
    }
    
    // Display documents in tabs
    ?>
    <div class="documents-tabs">
        <div class="tab-buttons">
            <?php if (count($uploadedDocsList) > 0): ?>
            <button class="tab-button uploaded-tab active" onclick="switchTab('uploaded', this)">
                <i class="fas fa-check-circle"></i>
                Uploaded Documents
                <span class="badge"><?= count($uploadedDocsList) ?></span>
            </button>
            <?php endif; ?>
            
            <?php if (count($requiredDocsList) > 0): ?>
            <button class="tab-button required-tab <?= count($uploadedDocsList) == 0 ? 'active' : '' ?>" onclick="switchTab('required', this)">
                <i class="fas fa-exclamation-circle"></i>
                Required Documents
                <span class="badge"><?= count($requiredDocsList) ?></span>
            </button>
            <?php endif; ?>
        </div>
        
        <?php if (count($uploadedDocsList) > 0): ?>
        <div id="tab-uploaded" class="tab-content <?= count($requiredDocsList) > 0 ? 'active' : '' ?>">
            <div class="documents-container">
            <?php foreach ($uploadedDocsList as $doc): 
                $docId = $doc['docId'];
                $isUploaded = true;
                $uploadedFile = $doc['uploadedFile'];
            ?>
    <div class="document-card <?= $isUploaded ? 'uploaded' : '' ?>" id="card-<?= $docId ?>">
        <div class="document-header">
            <div class="document-icon">
                <i class="fas fa-file-pdf"></i>
            </div>
            <div class="document-info">
                <h3><?= esc($doc['name']) ?></h3>
                <span class="document-status status-<?= $isUploaded ? 'uploaded' : 'required' ?>">
                    <?= $isUploaded ? 'Uploaded' : 'Required' ?>
                </span>
            </div>
        </div>
        <?php if (!$isUploaded): ?>
        <p class="document-description"><?= esc($doc['description'] ?? '') ?></p>
        
        <form action="<?= site_url('dashboard/upload-document') ?>" method="POST" enctype="multipart/form-data" class="upload-form" id="form-<?= $docId ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="document_type" value="<?= $docId ?>">
            
            <div class="file-input-wrapper">
                <div class="upload-area" id="upload-area-<?= $docId ?>" onclick="document.getElementById('file-<?= $docId ?>').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Click to upload</p>
                    <small>PDF, JPG, PNG, DOC, DOCX (max 1MB)</small>
                </div>
                <input type="file" 
                       id="file-<?= $docId ?>" 
                       name="document" 
                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" 
                       style="display: none;" 
                       onchange="handleFileUpload(this, '<?= $docId ?>')">
            </div>
            
            <div class="upload-progress" id="progress-<?= $docId ?>">
                <div class="progress-bar">
                    <div class="progress-fill" id="progress-fill-<?= $docId ?>" style="width: 0%"></div>
                </div>
                <small style="display: block; text-align: center; margin-top: 5px;">Uploading...</small>
            </div>
            
            <div class="error-message" id="error-<?= $docId ?>"></div>
        </form>
        <?php endif; ?>
        
        <?php if ($isUploaded && $uploadedFile): ?>
        <div style="margin-top: 8px;">
            <div class="document-item" id="uploaded-<?= $docId ?>" style="background: rgba(46, 204, 113, 0.08); border-color: var(--success-color); padding: 8px;">
                <span class="document-name" style="flex: 1; min-width: 0;">
                    <i class="fas fa-file-check" style="color: var(--success-color); margin-right: 6px; font-size: 0.8rem;"></i>
                    <span style="flex: 1; min-width: 0;">
                        <strong style="font-size: 0.8rem; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; line-height: 1.3;"><?= esc($uploadedFile['original_name'] ?? $uploadedFile['filename'] ?? 'Document') ?></strong>
                        <small style="color: #6c757d; font-size: 0.65rem; display: block; margin-top: 2px;">
                            <?= isset($uploadedFile['uploaded_at']) ? date('M j, Y', strtotime($uploadedFile['uploaded_at'])) : date('M j, Y') ?>
                        </small>
                    </span>
                </span>
                <div class="document-actions" style="flex-shrink: 0;">
                    <?php if (isset($uploadedFile['file_path'])): ?>
                    <a href="<?= base_url($uploadedFile['file_path']) ?>" target="_blank" class="btn btn-sm" style="background: var(--success-color); color: white; border: none; padding: 3px 8px; font-size: 0.7rem; border-radius: 4px;" title="View document">
                        <i class="fas fa-eye"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <form action="<?= site_url('dashboard/upload-document') ?>" method="POST" enctype="multipart/form-data" class="upload-form" id="form-<?= $docId ?>" style="margin-top: 6px;">
                <?= csrf_field() ?>
                <input type="hidden" name="document_type" value="<?= $docId ?>">
                <div class="file-input-wrapper">
                    <div class="upload-area" id="upload-area-<?= $docId ?>" onclick="document.getElementById('file-<?= $docId ?>').click()" style="padding: 6px; border-width: 1px;">
                        <i class="fas fa-sync-alt" style="font-size: 0.9rem; margin-bottom: 2px;"></i>
                        <p style="font-size: 0.7rem; margin: 2px 0;">Replace</p>
                    </div>
                    <input type="file" 
                           id="file-<?= $docId ?>" 
                           name="document" 
                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" 
                           style="display: none;" 
                           onchange="handleFileUpload(this, '<?= $docId ?>')">
                </div>
                <div class="upload-progress" id="progress-<?= $docId ?>">
                    <div class="progress-bar">
                        <div class="progress-fill" id="progress-fill-<?= $docId ?>" style="width: 0%"></div>
                    </div>
                </div>
                <div class="error-message" id="error-<?= $docId ?>"></div>
            </form>
        </div>
        <?php endif; ?>
    </div>
            <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (count($requiredDocsList) > 0): ?>
        <div id="tab-required" class="tab-content <?= count($uploadedDocsList) == 0 ? 'active' : '' ?>">
            <div class="documents-container">
            <?php foreach ($requiredDocsList as $doc): 
                $docId = $doc['docId'];
                $isUploaded = false; // These are required, so not uploaded
                $uploadedFile = null;
            ?>
            <div class="document-card" id="card-<?= $docId ?>">
                <div class="document-header">
                    <div class="document-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="document-info">
                        <h3><?= esc($doc['name']) ?></h3>
                        <span class="document-status status-required">
                            Required
                        </span>
                    </div>
                </div>
                <p class="document-description"><?= esc($doc['description'] ?? '') ?></p>
                
                <form action="<?= site_url('dashboard/upload-document') ?>" method="POST" enctype="multipart/form-data" class="upload-form" id="form-<?= $docId ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="document_type" value="<?= $docId ?>">
                    
                    <div class="file-input-wrapper">
                        <div class="upload-area" id="upload-area-<?= $docId ?>" onclick="document.getElementById('file-<?= $docId ?>').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click to upload</p>
                            <small>PDF, JPG, PNG, DOC, DOCX (max 1MB)</small>
                        </div>
                        <input type="file" 
                               id="file-<?= $docId ?>" 
                               name="document" 
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" 
                               style="display: none;" 
                               onchange="handleFileUpload(this, '<?= $docId ?>')">
                    </div>
                    
                    <div class="upload-progress" id="progress-<?= $docId ?>">
                        <div class="progress-bar">
                            <div class="progress-fill" id="progress-fill-<?= $docId ?>" style="width: 0%"></div>
                        </div>
                        <small style="display: block; text-align: center; margin-top: 5px;">Uploading...</small>
                    </div>
                    
                    <div class="error-message" id="error-<?= $docId ?>"></div>
                </form>
            </div>
            <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function switchTab(tabName, buttonElement) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
    });
    
    // Show selected tab content
    const selectedTab = document.getElementById('tab-' + tabName);
    if (selectedTab) {
        selectedTab.classList.add('active');
    }
    
    // Add active class to clicked button
    if (buttonElement) {
        buttonElement.classList.add('active');
    }
}

function handleFileUpload(input, docId) {
    const file = input.files[0];
    if (!file) return;
    
    // Validate file size (1MB = 1048576 bytes)
    const maxSize = 1024 * 1024; // 1MB in bytes
    if (file.size > maxSize) {
        showError(docId, 'File size exceeds 1MB limit. Please choose a smaller file.');
        input.value = '';
        return;
    }
    
    // Validate file type
    const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 
                         'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    if (!allowedTypes.includes(file.type)) {
        showError(docId, 'Invalid file type. Please upload PDF, JPG, PNG, DOC, or DOCX files only.');
        input.value = '';
        return;
    }
    
    // Hide error if any
    hideError(docId);
    
    // Show progress
    const progressDiv = document.getElementById('progress-' + docId);
    const progressFill = document.getElementById('progress-fill-' + docId);
    progressDiv.classList.add('active');
    progressFill.style.width = '0%';
    
    // Create FormData
    const form = document.getElementById('form-' + docId);
    const formData = new FormData(form);
    
    // Upload file
    fetch('<?= site_url('dashboard/upload-document') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        progressFill.style.width = '100%';
        
        if (data.success) {
            // Update UI without full page reload
            updateDocumentStatus(docId, data);
            
            // Reload page after a short delay to update progress tracker
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showError(docId, data.message || 'Upload failed. Please try again.');
            progressDiv.classList.remove('active');
            input.value = '';
        }
    })
    .catch(error => {
        console.error('Upload error:', error);
        showError(docId, 'An error occurred during upload. Please try again.');
        progressDiv.classList.remove('active');
        input.value = '';
    });
}

function showError(docId, message) {
    const errorDiv = document.getElementById('error-' + docId);
    errorDiv.textContent = message;
    errorDiv.classList.add('show');
}

function hideError(docId) {
    const errorDiv = document.getElementById('error-' + docId);
    errorDiv.classList.remove('show');
}

function updateDocumentStatus(docId, data) {
    // Update card to show uploaded status
    const card = document.getElementById('card-' + docId);
    if (card) {
        card.classList.add('uploaded');
        
        // Update status badge
        const statusBadge = card.querySelector('.document-status');
        if (statusBadge) {
            statusBadge.textContent = 'Uploaded';
            statusBadge.className = 'document-status status-uploaded';
        }
        
        // Update icon
        const icon = card.querySelector('.document-icon');
        if (icon) {
            icon.style.background = 'linear-gradient(135deg, var(--success-color), #27ae60)';
        }
        
        // Update upload area text
        const uploadArea = document.getElementById('upload-area-' + docId);
        if (uploadArea) {
            const p = uploadArea.querySelector('p');
            if (p) {
                p.textContent = 'Replace Document';
            }
        }
        
        // Hide progress
        const progressDiv = document.getElementById('progress-' + docId);
        if (progressDiv) {
            setTimeout(() => {
                progressDiv.classList.remove('active');
            }, 500);
        }
    }
}

// Drag and drop functionality
document.addEventListener('DOMContentLoaded', function() {
    <?php foreach ($requiredDocs as $doc): 
        $docId = $doc['id'] ?? strtolower(str_replace(' ', '_', $doc['name'] ?? ''));
    ?>
    const uploadArea<?= str_replace('-', '', $docId) ?> = document.getElementById('upload-area-<?= $docId ?>');
    const fileInput<?= str_replace('-', '', $docId) ?> = document.getElementById('file-<?= $docId ?>');
    
    if (uploadArea<?= str_replace('-', '', $docId) ?>) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea<?= str_replace('-', '', $docId) ?>.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea<?= str_replace('-', '', $docId) ?>.addEventListener(eventName, () => {
                uploadArea<?= str_replace('-', '', $docId) ?>.classList.add('dragover');
            }, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea<?= str_replace('-', '', $docId) ?>.addEventListener(eventName, () => {
                uploadArea<?= str_replace('-', '', $docId) ?>.classList.remove('dragover');
            }, false);
        });
        
        uploadArea<?= str_replace('-', '', $docId) ?>.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0) {
                fileInput<?= str_replace('-', '', $docId) ?>.files = files;
                handleFileUpload(fileInput<?= str_replace('-', '', $docId) ?>, '<?= $docId ?>');
            }
        }, false);
    }
    <?php endforeach; ?>
});
</script>

<?php
$requiredDocuments = $requiredDocuments ?? [];
$documentsAcknowledged = old(
    'documents_acknowledged',
    $application['documents_acknowledged'] ?? ''
);
?>
<style>
.form-section[data-step="4"] .documents-intro {
    background: #f8f9fa;
    border: 1px solid var(--light-gray);
    border-left: 4px solid var(--primary-color);
    border-radius: 10px;
    color: var(--text-color);
    line-height: 1.5;
    margin-bottom: 24px;
    padding: 18px 20px;
}

.form-section[data-step="4"] .documents-grid {
    display: grid;
    gap: 14px;
    grid-template-columns: repeat(auto-fit, minmax(min(240px, 100%), 1fr));
    margin-bottom: 24px;
}

.form-section[data-step="4"] .document-card {
    background: var(--white);
    border: 1px solid var(--light-gray);
    border-radius: 10px;
    padding: 16px;
}

.form-section[data-step="4"] .document-card h3 {
    color: var(--primary-color);
    font-size: 0.98rem;
    margin: 0 0 8px;
}

.form-section[data-step="4"] .document-card p {
    color: var(--gray);
    font-size: 0.86rem;
    line-height: 1.45;
    margin: 0 0 10px;
}

.form-section[data-step="4"] .document-card small {
    color: #6c757d;
    display: block;
    font-size: 0.78rem;
}

.form-section[data-step="4"] .document-acknowledgement {
    background: var(--white);
    border: 2px solid var(--light-gray);
    border-radius: 10px;
    padding: 16px;
}

.form-section[data-step="4"] .document-acknowledgement label {
    align-items: flex-start;
    color: var(--text-color);
    cursor: pointer;
    display: flex;
    gap: 10px;
    line-height: 1.45;
}

.form-section[data-step="4"] .document-acknowledgement input {
    margin-top: 4px;
}
</style>

<!-- Step 4: Documents -->
<div class="form-section" data-step="4" style="display: none;">
    <div class="form-section-header">
        <h2>Documents</h2>
        <p>Review the documents you will need after your application is submitted.</p>
    </div>

    <div class="documents-intro">
        Document uploads are completed after submission because uploads are attached to your
        submitted booking record. Use this step to confirm you know what to prepare.
    </div>

    <div class="documents-grid">
        <?php foreach ($requiredDocuments as $document): ?>
            <div class="document-card">
                <h3><?= esc($document['name'] ?? 'Required document') ?></h3>
                <p><?= esc($document['description'] ?? 'Prepare this document for review.') ?></p>
                <?php if (! empty($document['max_size'])): ?>
                    <small>Maximum file size: <?= esc((string) $document['max_size']) ?> KB</small>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="document-acknowledgement">
        <label for="documentsAcknowledged">
            <input
                type="checkbox"
                id="documentsAcknowledged"
                name="documents_acknowledged"
                value="1"
                <?= (string) $documentsAcknowledged === '1' ? 'checked' : '' ?>
            >
            <span>I understand which documents are required and will upload them from the Documents section after submitting this application.</span>
        </label>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const documentsStep = document.querySelector('[data-step="4"]');
    if (!documentsStep) return;

    documentsStep.querySelectorAll('input, select, textarea').forEach(function(input) {
        input.addEventListener('change', function() {
            if (window.scheduleAutoSave) {
                window.scheduleAutoSave(4);
            }
        });
    });
});
</script>

<style>
/* Application Form Styles */
.application-header {
    background: var(--white);
    padding: 30px;
    border-radius: 16px;
    margin-bottom: 30px;
    text-align: center;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.application-header h1 {
    color: var(--primary-color);
    margin-bottom: 10px;
    font-family: 'Playfair Display', serif;
}

.app-progress-indicator {
    background: var(--white);
    padding: 30px;
    border-radius: 16px;
    margin-bottom: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.step-indicator {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

.step-indicator::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 10%;
    right: 10%;
    height: 2px;
    background: var(--light-gray);
    z-index: 1;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    background: #f8f9fa;
    padding: 10px;
    border-radius: 8px;
    min-width: 120px;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--light-gray);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-bottom: 10px;
    transition: all 0.3s ease;
}

.step.completed .step-circle {
    background: var(--success-color);
    color: var(--white);
}

.step.active .step-circle {
    background: var(--primary-color);
    color: var(--white);
}

.step-label {
    font-size: 0.8rem;
    text-align: center;
    color: var(--gray);
    font-weight: 500;
}

/* Application Form Container */
.application-form-container {
    background: var(--white);
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 30px;
}

.form-section-header {
    text-align: center;
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 2px solid var(--light-gray);
}

.form-section-header h2 {
    color: var(--primary-color);
    margin-bottom: 10px;
    font-family: 'Playfair Display', serif;
}

/* Form Grid Layout */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.form-group input,
.form-group select {
    padding: 12px 16px;
    border: 2px solid var(--light-gray);
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--white);
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(100, 1, 127, 0.1);
}

/* Radio Options */
.radio-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 10px;
    margin-top: 10px;
}

.radio-option {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    padding: 8px 12px;
    border: 1px solid var(--light-gray);
    border-radius: 6px;
    transition: all 0.3s ease;
}

.radio-option:hover {
    border-color: var(--primary-color);
    background: rgba(100, 1, 127, 0.05);
}

.radio-option input[type="radio"] {
    margin: 0;
    width: auto;
    height: auto;
}

.person-section {
    margin-bottom: 40px;
    padding: 30px;
    border: 2px solid var(--light-gray);
    border-radius: 12px;
    background: #fafbfc;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--primary-color);
    margin-bottom: 25px;
    font-size: 1.2rem;
    font-family: 'Playfair Display', serif;
}

.form-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 40px;
    padding-top: 20px;
    border-top: 2px solid var(--light-gray);
}

.save-status {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--success-color);
    font-size: 0.9rem;
}
</style>

<div class="application-header">
    <h1>Wedding Venue Application</h1>
    <p>Complete all sections to submit your application. Your progress is automatically saved.</p>
</div>

<!-- Application Progress Indicator -->
<div class="app-progress-indicator">
    <div class="step-indicator">
        <div class="step active" data-step="1">
            <div class="step-circle">1</div>
            <span class="step-label">Venue & Date</span>
        </div>
        <div class="step" data-step="2">
            <div class="step-circle">2</div>
            <span class="step-label">Personal Details</span>
        </div>
        <div class="step" data-step="3">
            <div class="step-circle">3</div>
            <span class="step-label">Additional Info</span>
        </div>
        <div class="step" data-step="4">
            <div class="step-circle">4</div>
            <span class="step-label">Review & Submit</span>
        </div>
    </div>
</div>

<!-- Application Form Container -->
<?php if ($isSubmitted ?? false): ?>
    <!-- Submitted Application Notice -->
    <div class="application-form-container">
        <div class="submitted-notice">
            <div class="notice-content">
                <i class="fas fa-check-circle"></i>
                <h2>Application Submitted Successfully</h2>
                <p>Your wedding application has been submitted and is currently being reviewed by our team.</p>
                <div class="status-info">
                    <strong>Status:</strong> <span class="status-badge status-<?= $applicationStatus ?? 'pending' ?>"><?= ucfirst($applicationStatus ?? 'Pending') ?></span>
                </div>
                <div class="next-steps">
                    <h3>Next Steps:</h3>
                    <ul>
                        <li>Our team will review your application within 3-5 business days</li>
                        <li>You will receive an email notification once the review is complete</li>
                        <li>If approved, you'll receive wedding coordination details</li>
                        <li>Contact our coordinator if you have any questions</li>
                    </ul>
                </div>
                <div class="action-buttons">
                    <a href="<?= site_url('dashboard') ?>" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i>
                        Back to Dashboard
                    </a>
                    <button onclick="window.print()" class="btn btn-outline">
                        <i class="fas fa-print"></i>
                        Print Application
                    </button>
                    <a href="<?= site_url('dashboard/messages') ?>" class="btn btn-outline">
                        <i class="fas fa-comments"></i>
                        Contact Coordinator
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
    .submitted-notice {
        background: white;
        border-radius: 16px;
        padding: 50px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin: 20px 0;
    }

    .submitted-notice i.fa-check-circle {
        font-size: 4rem;
        color: #27ae60;
        margin-bottom: 20px;
    }

    .submitted-notice h2 {
        color: var(--primary-color);
        margin-bottom: 15px;
        font-family: 'Playfair Display', serif;
    }

    .submitted-notice p {
        color: var(--gray);
        font-size: 1.1rem;
        margin-bottom: 25px;
    }

    .status-info {
        margin: 25px 0;
        font-size: 1.1rem;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        margin-left: 10px;
    }

    .status-pending {
        background-color: #f39c12;
        color: white;
    }

    .status-approved {
        background-color: #27ae60;
        color: white;
    }

    .status-rejected {
        background-color: #e74c3c;
        color: white;
    }

    .status-under-review {
        background-color: #3498db;
        color: white;
    }

    .next-steps {
        text-align: left;
        max-width: 500px;
        margin: 30px auto;
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
    }

    .next-steps h3 {
        color: var(--primary-color);
        margin-bottom: 15px;
    }

    .next-steps ul {
        color: var(--text-color);
        line-height: 1.6;
    }

    .next-steps li {
        margin-bottom: 8px;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .action-buttons .btn {
            width: 200px;
        }
    }
    </style>

<?php else: ?>
<div class="application-form-container">
    <form id="applicationForm" action="<?= base_url('dashboard/save-application') ?>" method="POST">
        <?= csrf_field() ?>
        
        <?php 
        // Make saved data available to all components as $application
        $application = $saved_data ?? [];
        
        // Debug: Show what data we have (remove this in production)
        // if (!empty($application)) {
        //     echo '<div style="background: #f0f8ff; border: 1px solid #0066cc; padding: 10px; margin: 10px 0; border-radius: 5px;">';
        //     echo '<strong>Debug - Loaded Application Data:</strong><br>';
        //     echo '<pre style="font-size: 12px; max-height: 200px; overflow-y: auto;">';
        //     print_r($application);
        //     echo '</pre>';
        //     echo '</div>';
        // }
        ?>
        
        <!-- Step 1: Venue & Date Selection -->
        <?= $this->include('user/dashboard/components/step1_venue_date', ['application' => $application]) ?>
        
        <!-- Step 2: Personal Details -->
        <?= $this->include('user/dashboard/components/step2_personal_details', ['application' => $application]) ?>
        
        <!-- Step 3: Additional Information -->
        <?= $this->include('user/dashboard/components/step3_additional_info', ['application' => $application]) ?>
        
        <!-- Step 4: Review & Submit -->
        <?= $this->include('user/dashboard/components/step4_review_submit', ['application' => $application]) ?>

        <!-- Form Navigation -->
        <div class="form-navigation">
            <button type="button" class="btn btn-secondary" onclick="previousStep()" style="display: none;" id="prevButton">
                <i class="fas fa-arrow-left"></i>
                Previous
            </button>
            
            <div class="save-status">
                <i class="fas fa-check-circle"></i>
                Changes saved automatically
            </div>
            
            <button type="button" class="btn btn-primary" onclick="nextStep()" id="nextButton" disabled>
                Next
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </form>
</div>
<?php endif; ?>

<script>
// Global Auto-Save Functionality
let autoSaveTimeout;
let isAutoSaving = false;

// Auto-save function
async function autoSaveApplication(currentStep = null) {
    if (isAutoSaving) return;
    
    isAutoSaving = true;
    const form = document.getElementById('applicationForm');
    
    if (!form) {
        console.error('Application form not found');
        isAutoSaving = false;
        return;
    }
    
    const formData = new FormData(form);
    
    // Add current step if provided
    if (currentStep) {
        formData.append('step', currentStep);
    } else {
        formData.append('step', window.currentStep || 1);
    }
    
    // Ensure CSRF token is included (it should already be in FormData from the form)
    // But we'll verify and add it if missing
    const csrfTokenName = '<?= csrf_token() ?>';
    const csrfInput = form.querySelector(`input[name="${csrfTokenName}"]`);
    if (csrfInput && !formData.has(csrfTokenName)) {
        formData.append(csrfTokenName, csrfInput.value);
    }
    
    console.log('Auto-saving application for step:', formData.get('step'));
    
    try {
        const response = await fetch('<?= site_url('dashboard/auto-save-application') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });
        
        const result = await response.json();
        
        console.log('Auto-save response:', result);
        
        if (result.success) {
            // Update save status indicator
            const saveStatus = document.querySelector('.save-status');
            if (saveStatus) {
                const originalText = saveStatus.innerHTML;
                saveStatus.innerHTML = '<i class="fas fa-check-circle"></i> Saved at ' + result.timestamp;
                saveStatus.style.color = 'var(--success-color)';
                
                // Reset after 3 seconds
                setTimeout(() => {
                    saveStatus.innerHTML = originalText;
                    saveStatus.style.color = '';
                }, 3000);
            }
        } else {
            console.error('Auto-save failed:', result.message);
            // Show error indicator
            const saveStatus = document.querySelector('.save-status');
            if (saveStatus) {
                saveStatus.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Save failed';
                saveStatus.style.color = 'var(--error-color)';
            }
        }
    } catch (error) {
        console.error('Auto-save failed:', error);
    } finally {
        isAutoSaving = false;
    }
}

// Debounced auto-save function
function scheduleAutoSave(step = null) {
    clearTimeout(autoSaveTimeout);
    autoSaveTimeout = setTimeout(() => {
        autoSaveApplication(step);
    }, 2000); // Save after 2 seconds of inactivity
}

// Load saved draft on page load
async function loadApplicationDraft() {
    // First, check if we have data from the database (passed from controller)
    const savedData = <?= json_encode($saved_data ?? []) ?>;
    
    if (savedData && Object.keys(savedData).length > 0) {
        console.log('Loading saved data from database:', savedData);
        
        // Show loading indicator
        const loadingIndicator = document.createElement('div');
        loadingIndicator.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading saved application data...';
        loadingIndicator.style.cssText = 'position: fixed; top: 20px; right: 20px; background: var(--primary-color); color: white; padding: 10px 15px; border-radius: 5px; z-index: 1000;';
        document.body.appendChild(loadingIndicator);
        
        populateFormFields(savedData);
        
        // Remove loading indicator
        setTimeout(() => {
            document.body.removeChild(loadingIndicator);
        }, 2000);
        
        // Set current step if saved
        if (savedData.current_step && savedData.current_step > 1) {
            // Navigate to saved step
            for (let i = 1; i < savedData.current_step; i++) {
                if (window.nextStep) {
                    window.nextStep();
                }
            }
        }
        return;
    }
    
    // Fallback: try to load from server draft endpoint
    try {
        const response = await fetch('<?= site_url('dashboard/load-application-draft') ?>', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        });
        const result = await response.json();
        
        if (result.success && result.data) {
            // Populate form fields with saved data
            populateFormFields(result.data);
            
            // Set current step if saved
            if (result.current_step && result.current_step > 1) {
                // Navigate to saved step
                for (let i = 1; i < result.current_step; i++) {
                    if (window.nextStep) {
                        window.nextStep();
                    }
                }
            }
        }
    } catch (error) {
        console.error('Failed to load draft:', error);
    }
}

// Function to populate form fields
function populateFormFields(data) {
    console.log('Populating form fields with:', data);
    
    Object.keys(data).forEach(key => {
        const field = document.querySelector(`[name="${key}"]`);
        if (field) {
            if (field.type === 'radio') {
                const radioField = document.querySelector(`[name="${key}"][value="${data[key]}"]`);
                if (radioField) radioField.checked = true;
            } else if (field.type === 'checkbox') {
                field.checked = data[key] === '1' || data[key] === 'on';
            } else {
                field.value = data[key];
            }
        }
    });
    
    // Special handling for step 1 selections
    if (data.selectedCampus) {
        // Trigger campus selection in step 1
        setTimeout(() => {
            if (window.selectCampus) {
                window.selectCampus(data.selectedCampus);
            }
        }, 100);
    }
    
    if (data.selectedDate) {
        // Set the calendar to show the selected date
        setTimeout(() => {
            const dateField = document.getElementById('selectedDate');
            if (dateField) {
                dateField.value = data.selectedDate;
                dateField.dispatchEvent(new Event('change'));
            }
        }, 200);
    }
    
    if (data.selectedTime) {
        // Select the time slot
        setTimeout(() => {
            if (window.selectTime) {
                window.selectTime(data.selectedTime);
            }
        }, 300);
    }
    
    // Trigger church membership field visibility for bride and groom
    setTimeout(() => {
        // Trigger bride church fields
        const brideChurchMember = document.querySelector('input[name="bride_church_member"]:checked');
        if (brideChurchMember && window.toggleBrideChurchFields) {
            window.toggleBrideChurchFields();
        }
        
        // Trigger groom church fields
        const groomChurchMember = document.querySelector('input[name="groom_church_member"]:checked');
        if (groomChurchMember && window.toggleGroomChurchFields) {
            window.toggleGroomChurchFields();
        }
    }, 400);
}

// Add event listeners for auto-save
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('applicationForm');
    
    if (form) {
        console.log('Application form found, initializing...');
        
        // Load existing draft
        loadApplicationDraft();
        
        // Add auto-save listeners
        form.addEventListener('input', function(e) {
            scheduleAutoSave();
        });
        
        form.addEventListener('change', function(e) {
            scheduleAutoSave();
        });
        
        // Save when navigating between steps
        const originalNextStep = window.nextStep;
        const originalPreviousStep = window.previousStep;
        
        if (originalNextStep) {
            window.nextStep = function() {
                autoSaveApplication(window.currentStep);
                originalNextStep();
            };
        }
        
        if (originalPreviousStep) {
            window.previousStep = function() {
                autoSaveApplication(window.currentStep);
                originalPreviousStep();
            };
        }
    }
});

// Save before page unload
window.addEventListener('beforeunload', function() {
    if (!isAutoSaving) {
        // Synchronous save for page unload
        const form = document.getElementById('applicationForm');
        if (!form) return;
        
        const formData = new FormData(form);
        formData.append('step', window.currentStep || 1);
        
        // Ensure CSRF token is included
        const csrfTokenName = '<?= csrf_token() ?>';
        const csrfInput = form.querySelector(`input[name="${csrfTokenName}"]`);
        if (csrfInput && !formData.has(csrfTokenName)) {
            formData.append(csrfTokenName, csrfInput.value);
        }
        
        // Note: sendBeacon doesn't support custom headers, so CSRF must be in FormData
        navigator.sendBeacon('<?= site_url('dashboard/auto-save-application') ?>', formData);
    }
});
</script>

<!-- Step 4: Review & Submit -->
<div class="form-section" data-step="4" style="display: none;">
    <div class="form-section-header">
        <h2>Review & Submit</h2>
        <p>Please review your information before submitting</p>
    </div>
    
    <!-- Review Summary -->
    <div class="review-summary">
        <div class="review-section">
            <h3 class="section-title">
                <i class="fas fa-church"></i>
                Venue & Date Selection
            </h3>
            <div class="review-grid">
                <div class="review-item">
                    <strong>Campus:</strong>
                    <span id="reviewCampus">-</span>
                </div>
                <div class="review-item">
                    <strong>Date:</strong>
                    <span id="reviewDate">-</span>
                </div>
                <div class="review-item">
                    <strong>Time:</strong>
                    <span id="reviewTime">-</span>
                </div>
            </div>
        </div>
        
        <div class="review-section">
            <h3 class="section-title">
                <i class="fas fa-users"></i>
                Personal Details
            </h3>
            <div class="review-grid">
                <div class="review-item">
                    <strong>Bride Name:</strong>
                    <span id="reviewBrideName">-</span>
                </div>
                <div class="review-item">
                    <strong>Bride Email:</strong>
                    <span id="reviewBrideEmail">-</span>
                </div>
                <div class="review-item">
                    <strong>Bride Phone:</strong>
                    <span id="reviewBridePhone">-</span>
                </div>
                <div class="review-item">
                    <strong>Bride Age:</strong>
                    <span id="reviewBrideAge">-</span>
                </div>
                <div class="review-item">
                    <strong>Bride Nationality:</strong>
                    <span id="reviewBrideNationality">-</span>
                </div>
                <div class="review-item">
                    <strong>Bride Marital Status:</strong>
                    <span id="reviewBrideMaritalStatus">-</span>
                </div>
                <div class="review-item">
                    <strong>Groom Name:</strong>
                    <span id="reviewGroomName">-</span>
                </div>
                <div class="review-item">
                    <strong>Groom Email:</strong>
                    <span id="reviewGroomEmail">-</span>
                </div>
                <div class="review-item">
                    <strong>Groom Phone:</strong>
                    <span id="reviewGroomPhone">-</span>
                </div>
                <div class="review-item">
                    <strong>Groom Age:</strong>
                    <span id="reviewGroomAge">-</span>
                </div>
                <div class="review-item">
                    <strong>Groom Nationality:</strong>
                    <span id="reviewGroomNationality">-</span>
                </div>
                <div class="review-item">
                    <strong>Groom Marital Status:</strong>
                    <span id="reviewGroomMaritalStatus">-</span>
                </div>
            </div>
        </div>
        
        <div class="review-section">
            <h3 class="section-title">
                <i class="fas fa-user-friends"></i>
                Best Man & Matron
            </h3>
            <div class="review-grid">
                <div class="review-item">
                    <strong>Best Man Name:</strong>
                    <span id="reviewWitness1">-</span>
                </div>
                <div class="review-item">
                    <strong>Best Man Phone:</strong>
                    <span id="reviewWitness1Phone">-</span>
                </div>
                <div class="review-item">
                    <strong>Best Man ID:</strong>
                    <span id="reviewWitness1Id">-</span>
                </div>
                <div class="review-item">
                    <strong>Best Man Relationship:</strong>
                    <span id="reviewWitness1Relationship">-</span>
                </div>
                <div class="review-item">
                    <strong>Matron Name:</strong>
                    <span id="reviewWitness2">-</span>
                </div>
                <div class="review-item">
                    <strong>Matron Phone:</strong>
                    <span id="reviewWitness2Phone">-</span>
                </div>
                <div class="review-item">
                    <strong>Matron ID:</strong>
                    <span id="reviewWitness2Id">-</span>
                </div>
                <div class="review-item">
                    <strong>Matron Relationship:</strong>
                    <span id="reviewWitness2Relationship">-</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Terms and Conditions -->
    <div class="terms-section">
        <h3 class="section-title">
            <i class="fas fa-file-contract"></i>
            Terms and Conditions
        </h3>
        <div class="terms-content">
            <p>By submitting this application, you agree to the following:</p>
            <ul>
                <li>All information provided is accurate and complete</li>
                <li>You will attend mandatory pre-marital counseling sessions</li>
                <li>Wedding ceremony must follow Watoto Church guidelines</li>
                <li>Full payment must be completed before the wedding date</li>
                <li>Cancellation must be made at least 30 days in advance</li>
                <li>Final application will be saved to the church database upon submission</li>
            </ul>
            <div class="checkbox-group">
                <input type="checkbox" id="acceptTerms" name="accept_terms" required>
                <label for="acceptTerms">I accept the terms and conditions and authorize saving this application to the database *</label>
            </div>
        </div>
    </div>
</div>

<script>
// Comprehensive function to populate all review details
function populateStep4Review() {
    console.log('Populating step 4 review...');
    
    // Helper function to safely set text content
    function setReviewText(elementId, value) {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = value || '-';
        } else {
            console.warn('Review element not found:', elementId);
        }
    }
    
    // Helper function to get select option text
    function getSelectText(selectElement) {
        if (!selectElement) return '-';
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        return selectedOption && selectedOption.value ? selectedOption.text : '-';
    }
    
    // 1. Venue & Date Selection
    // Campus info - check global variables first
    if (typeof selectedCampusId !== 'undefined' && typeof campusData !== 'undefined' && campusData) {
        const campus = campusData.find(c => c.id == selectedCampusId);
        if (campus) {
            setReviewText('reviewCampus', campus.name);
        }
    } else {
        // Fallback: check if there's a selected campus in the form
        const campusSelect = document.querySelector('[name="campus_id"]');
        if (campusSelect && campusSelect.value) {
            const campusOption = campusSelect.options[campusSelect.selectedIndex];
            if (campusOption) {
                setReviewText('reviewCampus', campusOption.text);
            }
        }
    }
    
    // Date - check global variable first
    if (typeof selectedDate !== 'undefined' && selectedDate) {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        setReviewText('reviewDate', selectedDate.toLocaleDateString('en-US', options));
    } else {
        // Fallback: check form field
        const dateField = document.getElementById('selectedDate') || document.querySelector('[name="wedding_date"]');
        if (dateField && dateField.value) {
            const date = new Date(dateField.value);
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            setReviewText('reviewDate', date.toLocaleDateString('en-US', options));
        }
    }
    
    // Time - check global variable first
    if (typeof selectedTime !== 'undefined' && selectedTime) {
        const timeSlots = {
            '11:00': '11:00 AM',
            '13:00': '1:00 PM'
        };
        setReviewText('reviewTime', timeSlots[selectedTime] || selectedTime);
    } else {
        // Fallback: check form field
        const timeField = document.querySelector('[name="wedding_time"]');
        if (timeField && timeField.value) {
            const timeValue = timeField.value;
            const timeSlots = {
                '11:00': '11:00 AM',
                '13:00': '1:00 PM'
            };
            setReviewText('reviewTime', timeSlots[timeValue] || timeValue);
        }
    }
    
    // 2. Personal Details - Bride
    const brideName = document.getElementById('brideName');
    const brideEmail = document.getElementById('brideEmail');
    const bridePhone = document.getElementById('bridePhone');
    const brideAge = document.getElementById('brideAge');
    const brideNationality = document.getElementById('brideNationality');
    const brideMaritalStatus = document.getElementById('brideMaritalStatus');
    
    setReviewText('reviewBrideName', brideName ? brideName.value : null);
    setReviewText('reviewBrideEmail', brideEmail ? brideEmail.value : null);
    setReviewText('reviewBridePhone', bridePhone ? bridePhone.value : null);
    setReviewText('reviewBrideAge', brideAge && brideAge.value ? brideAge.value + ' years' : null);
    setReviewText('reviewBrideNationality', brideNationality ? brideNationality.value : null);
    setReviewText('reviewBrideMaritalStatus', getSelectText(brideMaritalStatus));
    
    // 3. Personal Details - Groom
    const groomName = document.getElementById('groomName');
    const groomEmail = document.getElementById('groomEmail');
    const groomPhone = document.getElementById('groomPhone');
    const groomAge = document.getElementById('groomAge');
    const groomNationality = document.getElementById('groomNationality');
    const groomMaritalStatus = document.getElementById('groomMaritalStatus');
    
    setReviewText('reviewGroomName', groomName ? groomName.value : null);
    setReviewText('reviewGroomEmail', groomEmail ? groomEmail.value : null);
    setReviewText('reviewGroomPhone', groomPhone ? groomPhone.value : null);
    setReviewText('reviewGroomAge', groomAge && groomAge.value ? groomAge.value + ' years' : null);
    setReviewText('reviewGroomNationality', groomNationality ? groomNationality.value : null);
    setReviewText('reviewGroomMaritalStatus', getSelectText(groomMaritalStatus));
    
    // 4. Witness Details - Best Man
    const witness1Name = document.getElementById('witness1Name');
    const witness1Phone = document.getElementById('witness1Phone');
    const witness1IdNumber = document.getElementById('witness1IdNumber');
    const witness1Relationship = document.getElementById('witness1Relationship');
    
    setReviewText('reviewWitness1', witness1Name ? witness1Name.value : null);
    setReviewText('reviewWitness1Phone', witness1Phone ? witness1Phone.value : null);
    setReviewText('reviewWitness1Id', witness1IdNumber ? witness1IdNumber.value : null);
    setReviewText('reviewWitness1Relationship', getSelectText(witness1Relationship));
    
    // 5. Witness Details - Matron
    const witness2Name = document.getElementById('witness2Name');
    const witness2Phone = document.getElementById('witness2Phone');
    const witness2IdNumber = document.getElementById('witness2IdNumber');
    const witness2Relationship = document.getElementById('witness2Relationship');
    
    setReviewText('reviewWitness2', witness2Name ? witness2Name.value : null);
    setReviewText('reviewWitness2Phone', witness2Phone ? witness2Phone.value : null);
    setReviewText('reviewWitness2Id', witness2IdNumber ? witness2IdNumber.value : null);
    setReviewText('reviewWitness2Relationship', getSelectText(witness2Relationship));
    
    console.log('Step 4 review populated');
}

// Make function available globally
window.populateStep4Review = populateStep4Review;

// Auto-save for step 4 (terms acceptance)
document.addEventListener('DOMContentLoaded', function() {
    const acceptTerms = document.getElementById('acceptTerms');
    if (acceptTerms) {
        acceptTerms.addEventListener('change', function() {
            if (window.scheduleAutoSave) {
                window.scheduleAutoSave();
            }
        });
    }
    
    // Populate review when step 4 is shown
    const step4Section = document.querySelector('[data-step="4"]');
    if (step4Section) {
        // Use MutationObserver to detect when step 4 becomes visible
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                    const display = step4Section.style.display;
                    const computedStyle = window.getComputedStyle(step4Section);
                    if (display !== 'none' && computedStyle.display !== 'none') {
                        // Step 4 is now visible, populate the review with a slight delay
                        setTimeout(function() {
                            populateStep4Review();
                        }, 200);
                    }
                }
            });
        });
        
        observer.observe(step4Section, {
            attributes: true,
            attributeFilter: ['style']
        });
        
        // Also check on initial load if step 4 is already visible
        const computedStyle = window.getComputedStyle(step4Section);
        if (step4Section.style.display !== 'none' && computedStyle.display !== 'none') {
            setTimeout(function() {
                populateStep4Review();
            }, 200);
        }
    }
    
    // Also listen for custom event if navigation system uses it
    document.addEventListener('step4shown', function() {
        setTimeout(populateStep4Review, 200);
    });
});
</script>

<style>
/* Review Summary Styles */
.review-summary {
    margin-bottom: 30px;
}

.review-section {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 20px;
    border: 1px solid var(--light-gray);
}

.review-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.review-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.review-item strong {
    color: var(--primary-color);
    font-size: 0.9rem;
}

.review-item span {
    color: var(--text-color);
    font-weight: 500;
}

/* Terms Section */
.terms-section {
    background: rgba(100, 1, 127, 0.05);
    border: 2px solid var(--primary-color);
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 25px;
}

.terms-content ul {
    margin: 15px 0;
    padding-left: 20px;
}

.terms-content li {
    margin-bottom: 8px;
    color: var(--text-color);
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 20px;
    padding: 15px;
    background: var(--white);
    border-radius: 8px;
    border: 2px solid var(--light-gray);
}

.checkbox-group input[type="checkbox"] {
    width: 18px;
    height: 18px;
    margin: 0;
}

.checkbox-group label {
    margin: 0;
    color: var(--text-color);
    font-weight: 600;
}
</style>

<!-- Step 2: Personal Details -->
<div class="form-section" data-step="2" style="display: none;">
    <div class="form-section-header">
        <h2>Personal Details</h2>
        <p>Provide information about the bride and groom</p>
    </div>
    
    <!-- Bride Information -->
    <div class="person-section">
        <h3 class="section-title">
            <i class="fas fa-female"></i>
            Bride Information
        </h3>
        
        <div class="form-grid">
            <div class="form-group">
                <label for="brideName">Full Name *</label>
                <input type="text" id="brideName" name="bride_name" value="<?= old('bride_name', $application['bride_name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="brideDateOfBirth">Date of Birth *</label>
                <input type="date" id="brideDateOfBirth" name="bride_date_of_birth" value="<?= old('bride_date_of_birth', $application['bride_date_of_birth'] ?? '') ?>" required>
                <small class="form-text text-muted">Must be 18 years or older</small>
                <div id="brideAgeError" class="error-message" style="display: none; color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">
                    <i class="fas fa-exclamation-circle"></i> Bride must be at least 18 years old
                </div>
            </div>
            <div class="form-group">
                <label for="brideAge">Age *</label>
                <input type="number" id="brideAge" name="bride_age" min="18" max="100" value="<?= old('bride_age', $application['bride_age'] ?? '') ?>" required readonly>
            </div>
            <div class="form-group">
                <label for="brideBirthPlace">Place of Birth</label>
                <input type="text" id="brideBirthPlace" name="bride_birth_place" value="<?= old('bride_birth_place', $application['bride_birth_place'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="brideEmail">Email Address *</label>
                <input type="email" id="brideEmail" name="bride_email" value="<?= old('bride_email', $application['bride_email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="bridePhone">Phone Number *</label>
                <input type="tel" id="bridePhone" name="bride_phone" value="<?= old('bride_phone', $application['bride_phone'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="brideOccupation">Occupation</label>
                <input type="text" id="brideOccupation" name="bride_occupation" value="<?= old('bride_occupation', $application['bride_occupation'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="brideEmployer">Employer/Company</label>
                <input type="text" id="brideEmployer" name="bride_employer" value="<?= old('bride_employer', $application['bride_employer'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="brideEducationLevel">Education Level</label>
                <select id="brideEducationLevel" name="bride_education_level">
                    <option value="">Select level</option>
                    <option value="primary" <?= old('bride_education_level', $application['bride_education_level'] ?? '') === 'primary' ? 'selected' : '' ?>>Primary</option>
                    <option value="secondary" <?= old('bride_education_level', $application['bride_education_level'] ?? '') === 'secondary' ? 'selected' : '' ?>>Secondary</option>
                    <option value="diploma" <?= old('bride_education_level', $application['bride_education_level'] ?? '') === 'diploma' ? 'selected' : '' ?>>Diploma</option>
                    <option value="degree" <?= old('bride_education_level', $application['bride_education_level'] ?? '') === 'degree' ? 'selected' : '' ?>>Bachelor's Degree</option>
                    <option value="masters" <?= old('bride_education_level', $application['bride_education_level'] ?? '') === 'masters' ? 'selected' : '' ?>>Master's Degree</option>
                    <option value="phd" <?= old('bride_education_level', $application['bride_education_level'] ?? '') === 'phd' ? 'selected' : '' ?>>PhD</option>
                </select>
            </div>
            <div class="form-group">
                <label for="brideNationality">Nationality *</label>
                <input type="text" id="brideNationality" name="bride_nationality" value="<?= old('bride_nationality', $application['bride_nationality'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="brideReligion">Religion</label>
                <input type="text" id="brideReligion" name="bride_religion" value="<?= old('bride_religion', $application['bride_religion'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="brideMaritalStatus">Marital Status *</label>
                <select id="brideMaritalStatus" name="bride_marital_status" required>
                    <option value="">Select status</option>
                    <option value="spinster" <?= old('bride_marital_status', $application['bride_marital_status'] ?? '') === 'spinster' ? 'selected' : '' ?>>Spinster</option>
                    <option value="divorced-separated" <?= old('bride_marital_status', $application['bride_marital_status'] ?? '') === 'divorced-separated' ? 'selected' : '' ?>>Divorced/Separated</option>
                    <option value="married-traditionally" <?= old('bride_marital_status', $application['bride_marital_status'] ?? '') === 'married-traditionally' ? 'selected' : '' ?>>Married Traditionally</option>
                    <option value="widowed" <?= old('bride_marital_status', $application['bride_marital_status'] ?? '') === 'widowed' ? 'selected' : '' ?>>Widowed</option>
                    <option value="civil-marriage" <?= old('bride_marital_status', $application['bride_marital_status'] ?? '') === 'civil-marriage' ? 'selected' : '' ?>>Civil Marriage</option>
                    <option value="cohabiting" <?= old('bride_marital_status', $application['bride_marital_status'] ?? '') === 'cohabiting' ? 'selected' : '' ?>>Cohabiting</option>
                </select>
            </div>
            <div class="form-group full-width">
                <label for="brideAddress">Current Address *</label>
                <textarea id="brideAddress" name="bride_address" rows="3" required><?= old('bride_address', $application['bride_address'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label for="brideIdNumber">ID/Passport Number *</label>
                <input type="text" id="brideIdNumber" name="bride_id_number" value="<?= old('bride_id_number', $application['bride_id_number'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="brideIdType">ID Type *</label>
                <select id="brideIdType" name="bride_id_type" required>
                    <option value="">Select ID type</option>
                    <option value="national_id" <?= old('bride_id_type', $application['bride_id_type'] ?? '') === 'national_id' ? 'selected' : '' ?>>National ID</option>
                    <option value="passport" <?= old('bride_id_type', $application['bride_id_type'] ?? '') === 'passport' ? 'selected' : '' ?>>Passport</option>
                    <option value="driving_license" <?= old('bride_id_type', $application['bride_id_type'] ?? '') === 'driving_license' ? 'selected' : '' ?>>Driving License</option>
                </select>
            </div>
            <div class="form-group">
                <label for="brideChurchMember">Church Member? *</label>
                <div class="radio-grid">
                    <div class="radio-option">
                        <input type="radio" id="brideChurchMemberYes" name="bride_church_member" value="yes" <?= old('bride_church_member', $application['bride_church_member'] ?? '') === 'yes' ? 'checked' : '' ?> required>
                        <label for="brideChurchMemberYes">Yes - Watoto Church</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" id="brideChurchMemberOther" name="bride_church_member" value="other" <?= old('bride_church_member', $application['bride_church_member'] ?? '') === 'other' ? 'checked' : '' ?> required>
                        <label for="brideChurchMemberOther">Yes - Other Church</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" id="brideChurchMemberNo" name="bride_church_member" value="no" <?= old('bride_church_member', $application['bride_church_member'] ?? '') === 'no' ? 'checked' : '' ?> required>
                        <label for="brideChurchMemberNo">No</label>
                    </div>
                </div>
            </div>
            <!-- Watoto Church Member Fields -->
            <div class="form-group" id="brideWatotoFields" style="display: none;">
                <label for="brideMembershipDuration">Membership Duration at Watoto</label>
                <input type="text" id="brideMembershipDuration" name="bride_membership_duration" placeholder="e.g., 2 years, 6 months" value="<?= old('bride_membership_duration', $application['bride_membership_duration'] ?? '') ?>">
            </div>
            <div class="form-group" id="brideCellGroupField" style="display: none;">
                <label for="brideCellGroupNumber">Cell Family Number *</label>
                <input type="text" id="brideCellGroupNumber" name="bride_cell_group_number" value="<?= old('bride_cell_group_number', $application['bride_cell_group_number'] ?? '') ?>">
            </div>
            <div class="form-group" id="brideCellLeaderField" style="display: none;">
                <label for="brideCellLeaderName">Cell Family Leader Name *</label>
                <input type="text" id="brideCellLeaderName" name="bride_cell_leader_name" value="<?= old('bride_cell_leader_name', $application['bride_cell_leader_name'] ?? '') ?>">
            </div>
            <div class="form-group" id="brideCellLeaderPhoneField" style="display: none;">
                <label for="brideCellLeaderPhone">Cell Family Leader Phone *</label>
                <input type="tel" id="brideCellLeaderPhone" name="bride_cell_leader_phone" value="<?= old('bride_cell_leader_phone', $application['bride_cell_leader_phone'] ?? '') ?>">
            </div>
            <!-- Other Church Member Fields -->
            <div class="form-group" id="brideOtherChurchField" style="display: none;">
                <label for="brideChurchName">Church Name *</label>
                <input type="text" id="brideChurchName" name="bride_church_name" value="<?= old('bride_church_name', $application['bride_church_name'] ?? '') ?>">
            </div>
            <div class="form-group" id="brideSeniorPastorField" style="display: none;">
                <label for="brideSeniorPastor">Senior Pastor Name *</label>
                <input type="text" id="brideSeniorPastor" name="bride_senior_pastor" value="<?= old('bride_senior_pastor', $application['bride_senior_pastor'] ?? '') ?>">
            </div>
            <div class="form-group" id="bridePastorPhoneField" style="display: none;">
                <label for="bridePastorPhone">Senior Pastor Phone *</label>
                <input type="tel" id="bridePastorPhone" name="bride_pastor_phone" value="<?= old('bride_pastor_phone', $application['bride_pastor_phone'] ?? '') ?>">
            </div>
        </div>
    </div>
    
    <!-- Groom Information -->
    <div class="person-section">
        <h3 class="section-title">
            <i class="fas fa-male"></i>
            Groom Information
        </h3>
        
        <div class="form-grid">
            <div class="form-group">
                <label for="groomName">Full Name *</label>
                <input type="text" id="groomName" name="groom_name" value="<?= old('groom_name', $application['groom_name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="groomDateOfBirth">Date of Birth *</label>
                <input type="date" id="groomDateOfBirth" name="groom_date_of_birth" value="<?= old('groom_date_of_birth', $application['groom_date_of_birth'] ?? '') ?>" required>
                <small class="form-text text-muted">Must be 18 years or older</small>
                <div id="groomAgeError" class="error-message" style="display: none; color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;">
                    <i class="fas fa-exclamation-circle"></i> Groom must be at least 18 years old
                </div>
            </div>
            <div class="form-group">
                <label for="groomAge">Age *</label>
                <input type="number" id="groomAge" name="groom_age" min="18" max="100" value="<?= old('groom_age', $application['groom_age'] ?? '') ?>" required readonly>
            </div>
            <div class="form-group">
                <label for="groomBirthPlace">Place of Birth</label>
                <input type="text" id="groomBirthPlace" name="groom_birth_place" value="<?= old('groom_birth_place', $application['groom_birth_place'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="groomEmail">Email Address *</label>
                <input type="email" id="groomEmail" name="groom_email" value="<?= old('groom_email', $application['groom_email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="groomPhone">Phone Number *</label>
                <input type="tel" id="groomPhone" name="groom_phone" value="<?= old('groom_phone', $application['groom_phone'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="groomOccupation">Occupation</label>
                <input type="text" id="groomOccupation" name="groom_occupation" value="<?= old('groom_occupation', $application['groom_occupation'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="groomEmployer">Employer/Company</label>
                <input type="text" id="groomEmployer" name="groom_employer" value="<?= old('groom_employer', $application['groom_employer'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="groomEducationLevel">Education Level</label>
                <select id="groomEducationLevel" name="groom_education_level">
                    <option value="">Select level</option>
                    <option value="primary" <?= old('groom_education_level', $application['groom_education_level'] ?? '') === 'primary' ? 'selected' : '' ?>>Primary</option>
                    <option value="secondary" <?= old('groom_education_level', $application['groom_education_level'] ?? '') === 'secondary' ? 'selected' : '' ?>>Secondary</option>
                    <option value="diploma" <?= old('groom_education_level', $application['groom_education_level'] ?? '') === 'diploma' ? 'selected' : '' ?>>Diploma</option>
                    <option value="degree" <?= old('groom_education_level', $application['groom_education_level'] ?? '') === 'degree' ? 'selected' : '' ?>>Bachelor's Degree</option>
                    <option value="masters" <?= old('groom_education_level', $application['groom_education_level'] ?? '') === 'masters' ? 'selected' : '' ?>>Master's Degree</option>
                    <option value="phd" <?= old('groom_education_level', $application['groom_education_level'] ?? '') === 'phd' ? 'selected' : '' ?>>PhD</option>
                </select>
            </div>
            <div class="form-group">
                <label for="groomNationality">Nationality *</label>
                <input type="text" id="groomNationality" name="groom_nationality" value="<?= old('groom_nationality', $application['groom_nationality'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="groomReligion">Religion</label>
                <input type="text" id="groomReligion" name="groom_religion" value="<?= old('groom_religion', $application['groom_religion'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="groomMaritalStatus">Marital Status *</label>
                <select id="groomMaritalStatus" name="groom_marital_status" required>
                    <option value="">Select status</option>
                    <option value="bachelor" <?= old('groom_marital_status', $application['groom_marital_status'] ?? '') === 'bachelor' ? 'selected' : '' ?>>Bachelor</option>
                    <option value="divorced-separated" <?= old('groom_marital_status', $application['groom_marital_status'] ?? '') === 'divorced-separated' ? 'selected' : '' ?>>Divorced/Separated</option>
                    <option value="married-traditionally" <?= old('groom_marital_status', $application['groom_marital_status'] ?? '') === 'married-traditionally' ? 'selected' : '' ?>>Married Traditionally</option>
                    <option value="widowed" <?= old('groom_marital_status', $application['groom_marital_status'] ?? '') === 'widowed' ? 'selected' : '' ?>>Widowed</option>
                    <option value="civil-marriage" <?= old('groom_marital_status', $application['groom_marital_status'] ?? '') === 'civil-marriage' ? 'selected' : '' ?>>Civil Marriage</option>
                    <option value="cohabiting" <?= old('groom_marital_status', $application['groom_marital_status'] ?? '') === 'cohabiting' ? 'selected' : '' ?>>Cohabiting</option>
                </select>
            </div>
            <div class="form-group full-width">
                <label for="groomAddress">Current Address *</label>
                <textarea id="groomAddress" name="groom_address" rows="3" required><?= old('groom_address', $application['groom_address'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label for="groomIdNumber">ID/Passport Number *</label>
                <input type="text" id="groomIdNumber" name="groom_id_number" value="<?= old('groom_id_number', $application['groom_id_number'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="groomIdType">ID Type *</label>
                <select id="groomIdType" name="groom_id_type" required>
                    <option value="">Select ID type</option>
                    <option value="national_id" <?= old('groom_id_type', $application['groom_id_type'] ?? '') === 'national_id' ? 'selected' : '' ?>>National ID</option>
                    <option value="passport" <?= old('groom_id_type', $application['groom_id_type'] ?? '') === 'passport' ? 'selected' : '' ?>>Passport</option>
                    <option value="driving_license" <?= old('groom_id_type', $application['groom_id_type'] ?? '') === 'driving_license' ? 'selected' : '' ?>>Driving License</option>
                </select>
            </div>
            <div class="form-group">
                <label for="groomChurchMember">Church Member? *</label>
                <div class="radio-grid">
                    <div class="radio-option">
                        <input type="radio" id="groomChurchMemberYes" name="groom_church_member" value="yes" <?= old('groom_church_member', $application['groom_church_member'] ?? '') === 'yes' ? 'checked' : '' ?> required>
                        <label for="groomChurchMemberYes">Yes - Watoto Church</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" id="groomChurchMemberOther" name="groom_church_member" value="other" <?= old('groom_church_member', $application['groom_church_member'] ?? '') === 'other' ? 'checked' : '' ?> required>
                        <label for="groomChurchMemberOther">Yes - Other Church</label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" id="groomChurchMemberNo" name="groom_church_member" value="no" <?= old('groom_church_member', $application['groom_church_member'] ?? '') === 'no' ? 'checked' : '' ?> required>
                        <label for="groomChurchMemberNo">No</label>
                    </div>
                </div>
            </div>
            <!-- Watoto Church Member Fields -->
            <div class="form-group" id="groomWatotoFields" style="display: none;">
                <label for="groomMembershipDuration">Membership Duration at Watoto</label>
                <input type="text" id="groomMembershipDuration" name="groom_membership_duration" placeholder="e.g., 2 years, 6 months" value="<?= old('groom_membership_duration', $application['groom_membership_duration'] ?? '') ?>">
            </div>
            <div class="form-group" id="groomCellGroupField" style="display: none;">
                <label for="groomCellGroupNumber">Cell Family Number *</label>
                <input type="text" id="groomCellGroupNumber" name="groom_cell_group_number" value="<?= old('groom_cell_group_number', $application['groom_cell_group_number'] ?? '') ?>">
            </div>
            <div class="form-group" id="groomCellLeaderField" style="display: none;">
                <label for="groomCellLeaderName">Cell Family Leader Name *</label>
                <input type="text" id="groomCellLeaderName" name="groom_cell_leader_name" value="<?= old('groom_cell_leader_name', $application['groom_cell_leader_name'] ?? '') ?>">
            </div>
            <div class="form-group" id="groomCellLeaderPhoneField" style="display: none;">
                <label for="groomCellLeaderPhone">Cell Family Leader Phone *</label>
                <input type="tel" id="groomCellLeaderPhone" name="groom_cell_leader_phone" value="<?= old('groom_cell_leader_phone', $application['groom_cell_leader_phone'] ?? '') ?>">
            </div>
            <!-- Other Church Member Fields -->
            <div class="form-group" id="groomOtherChurchField" style="display: none;">
                <label for="groomChurchName">Church Name *</label>
                <input type="text" id="groomChurchName" name="groom_church_name" value="<?= old('groom_church_name', $application['groom_church_name'] ?? '') ?>">
            </div>
            <div class="form-group" id="groomSeniorPastorField" style="display: none;">
                <label for="groomSeniorPastor">Senior Pastor Name *</label>
                <input type="text" id="groomSeniorPastor" name="groom_senior_pastor" value="<?= old('groom_senior_pastor', $application['groom_senior_pastor'] ?? '') ?>">
            </div>
            <div class="form-group" id="groomPastorPhoneField" style="display: none;">
                <label for="groomPastorPhone">Senior Pastor Phone *</label>
                <input type="tel" id="groomPastorPhone" name="groom_pastor_phone" value="<?= old('groom_pastor_phone', $application['groom_pastor_phone'] ?? '') ?>">
            </div>
        </div>
    </div>
</div>

<script>
// Calculate maximum date (18 years ago from today)
function getMaxDateOfBirth() {
    const today = new Date();
    const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
    return maxDate.toISOString().split('T')[0];
}

// Set max date on date of birth fields
document.addEventListener('DOMContentLoaded', function() {
    const maxDate = getMaxDateOfBirth();
    const brideDateField = document.getElementById('brideDateOfBirth');
    const groomDateField = document.getElementById('groomDateOfBirth');
    
    if (brideDateField) {
        brideDateField.setAttribute('max', maxDate);
    }
    if (groomDateField) {
        groomDateField.setAttribute('max', maxDate);
    }
});

// Validate age and show error if below 18
function validateAge(birthDateInput, ageInput, errorElement, personName) {
    const birthDate = new Date(birthDateInput.value);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    
    ageInput.value = age;
    
    // Validate age is at least 18
    if (age < 18) {
        errorElement.style.display = 'block';
        birthDateInput.setCustomValidity(personName + ' must be at least 18 years old');
        birthDateInput.classList.add('is-invalid');
        return false;
    } else {
        errorElement.style.display = 'none';
        birthDateInput.setCustomValidity('');
        birthDateInput.classList.remove('is-invalid');
        return true;
    }
}

// Auto-calculate age for bride
document.getElementById('brideDateOfBirth').addEventListener('change', function() {
    const brideAgeInput = document.getElementById('brideAge');
    const brideAgeError = document.getElementById('brideAgeError');
    
    validateAge(this, brideAgeInput, brideAgeError, 'Bride');
    
    // Trigger auto-save only if age is valid
    if (window.scheduleAutoSave && parseInt(brideAgeInput.value) >= 18) {
        window.scheduleAutoSave(2);
    }
});

// Auto-calculate age for groom
document.getElementById('groomDateOfBirth').addEventListener('change', function() {
    const groomAgeInput = document.getElementById('groomAge');
    const groomAgeError = document.getElementById('groomAgeError');
    
    validateAge(this, groomAgeInput, groomAgeError, 'Groom');
    
    // Trigger auto-save only if age is valid
    if (window.scheduleAutoSave && parseInt(groomAgeInput.value) >= 18) {
        window.scheduleAutoSave(2);
    }
});

// Handle bride church membership fields visibility
function toggleBrideChurchFields() {
    const brideChurchMember = document.querySelector('input[name="bride_church_member"]:checked');
    const watotoFields = ['brideWatotoFields', 'brideCellGroupField', 'brideCellLeaderField', 'brideCellLeaderPhoneField'];
    const otherFields = ['brideOtherChurchField', 'brideSeniorPastorField', 'bridePastorPhoneField'];
    
    // Hide all fields first
    [...watotoFields, ...otherFields].forEach(id => {
        const field = document.getElementById(id);
        if (field) field.style.display = 'none';
    });
    
    if (brideChurchMember && brideChurchMember.value === 'yes') {
        // Show Watoto church fields
        watotoFields.forEach(id => {
            const field = document.getElementById(id);
            if (field) field.style.display = 'flex';
        });
    } else if (brideChurchMember && brideChurchMember.value === 'other') {
        // Show other church fields
        otherFields.forEach(id => {
            const field = document.getElementById(id);
            if (field) field.style.display = 'flex';
        });
    }
    
    // Trigger auto-save when church membership changes
    if (window.scheduleAutoSave) {
        window.scheduleAutoSave(2);
    }
}

// Handle groom church membership fields visibility
function toggleGroomChurchFields() {
    const groomChurchMember = document.querySelector('input[name="groom_church_member"]:checked');
    const watotoFields = ['groomWatotoFields', 'groomCellGroupField', 'groomCellLeaderField', 'groomCellLeaderPhoneField'];
    const otherFields = ['groomOtherChurchField', 'groomSeniorPastorField', 'groomPastorPhoneField'];
    
    // Hide all fields first
    [...watotoFields, ...otherFields].forEach(id => {
        const field = document.getElementById(id);
        if (field) field.style.display = 'none';
    });
    
    if (groomChurchMember && groomChurchMember.value === 'yes') {
        // Show Watoto church fields
        watotoFields.forEach(id => {
            const field = document.getElementById(id);
            if (field) field.style.display = 'flex';
        });
    } else if (groomChurchMember && groomChurchMember.value === 'other') {
        // Show other church fields
        otherFields.forEach(id => {
            const field = document.getElementById(id);
            if (field) field.style.display = 'flex';
        });
    }
    
    // Trigger auto-save when church membership changes
    if (window.scheduleAutoSave) {
        window.scheduleAutoSave(2);
    }
}

// Add event listeners for bride church membership
document.querySelectorAll('input[name="bride_church_member"]').forEach(radio => {
    radio.addEventListener('change', toggleBrideChurchFields);
});

// Add event listeners for groom church membership
document.querySelectorAll('input[name="groom_church_member"]').forEach(radio => {
    radio.addEventListener('change', toggleGroomChurchFields);
});

// Auto-calculate ages on page load if dates exist and set initial field visibility
window.addEventListener('DOMContentLoaded', function() {
    const brideDateField = document.getElementById('brideDateOfBirth');
    const groomDateField = document.getElementById('groomDateOfBirth');
    
    // Set max date
    const maxDate = getMaxDateOfBirth();
    if (brideDateField) {
        brideDateField.setAttribute('max', maxDate);
        if (brideDateField.value) {
            brideDateField.dispatchEvent(new Event('change'));
        }
    }
    
    if (groomDateField) {
        groomDateField.setAttribute('max', maxDate);
        if (groomDateField.value) {
            groomDateField.dispatchEvent(new Event('change'));
        }
    }
    
    // Set initial field visibility based on current selection
    toggleBrideChurchFields();
    toggleGroomChurchFields();
});

// Add validation function to global scope for form submission validation
window.validateStep2Ages = function() {
    const brideDateField = document.getElementById('brideDateOfBirth');
    const groomDateField = document.getElementById('groomDateOfBirth');
    const brideAgeInput = document.getElementById('brideAge');
    const groomAgeInput = document.getElementById('groomAge');
    const brideAgeError = document.getElementById('brideAgeError');
    const groomAgeError = document.getElementById('groomAgeError');
    
    let isValid = true;
    
    if (brideDateField && brideDateField.value) {
        if (!validateAge(brideDateField, brideAgeInput, brideAgeError, 'Bride')) {
            isValid = false;
        }
    }
    
    if (groomDateField && groomDateField.value) {
        if (!validateAge(groomDateField, groomAgeInput, groomAgeError, 'Groom')) {
            isValid = false;
        }
    }
    
    return isValid;
};

// Make church toggle functions available globally for data loading
window.toggleBrideChurchFields = toggleBrideChurchFields;
window.toggleGroomChurchFields = toggleGroomChurchFields;
</script>

<!-- Step 3: Additional Information -->
<div class="form-section" data-step="3" style="display: none;">
    <div class="form-section-header">
        <h2>Additional Information</h2>
        <p>Wedding details and family information</p>
    </div>
    
    <!-- Family Information -->
    <div class="person-section">
        <h3 class="section-title">
            <i class="fas fa-users"></i>
            Family Information
        </h3>
        
        <div class="form-grid">
            <div class="form-group">
                <label for="brideFather">Bride's Father Name</label>
                <input type="text" id="brideFather" name="bride_father" value="<?= old('bride_father', $application['bride_father'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="brideFatherOccupation">Bride's Father Occupation</label>
                <input type="text" id="brideFatherOccupation" name="bride_father_occupation" value="<?= old('bride_father_occupation', $application['bride_father_occupation'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="brideMother">Bride's Mother Name</label>
                <input type="text" id="brideMother" name="bride_mother" value="<?= old('bride_mother', $application['bride_mother'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="brideMotherOccupation">Bride's Mother Occupation</label>
                <input type="text" id="brideMotherOccupation" name="bride_mother_occupation" value="<?= old('bride_mother_occupation', $application['bride_mother_occupation'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="groomFather">Groom's Father Name</label>
                <input type="text" id="groomFather" name="groom_father" value="<?= old('groom_father', $application['groom_father'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="groomFatherOccupation">Groom's Father Occupation</label>
                <input type="text" id="groomFatherOccupation" name="groom_father_occupation" value="<?= old('groom_father_occupation', $application['groom_father_occupation'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="groomMother">Groom's Mother Name</label>
                <input type="text" id="groomMother" name="groom_mother" value="<?= old('groom_mother', $application['groom_mother'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="groomMotherOccupation">Groom's Mother Occupation</label>
                <input type="text" id="groomMotherOccupation" name="groom_mother_occupation" value="<?= old('groom_mother_occupation', $application['groom_mother_occupation'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="brideFamilyPhone">Bride's Family Contact</label>
                <input type="tel" id="brideFamilyPhone" name="bride_family_phone" value="<?= old('bride_family_phone', $application['bride_family_phone'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="groomFamilyPhone">Groom's Family Contact</label>
                <input type="tel" id="groomFamilyPhone" name="groom_family_phone" value="<?= old('groom_family_phone', $application['groom_family_phone'] ?? '') ?>">
            </div>
        </div>
    </div>
    
    <!-- Witnesses -->
    <div class="person-section">
        <h3 class="section-title">
            <i class="fas fa-user-friends"></i>
            Best Man & Matron
        </h3>
        
        <div class="form-grid">
            <div class="form-group">
                <label for="witness1Name">Best Man - Full Name *</label>
                <input type="text" id="witness1Name" name="witness1_name" value="<?= old('witness1_name', $application['witness1_name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="witness1Phone">Best Man - Phone *</label>
                <input type="tel" id="witness1Phone" name="witness1_phone" value="<?= old('witness1_phone', $application['witness1_phone'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="witness1IdNumber">Best Man - ID Number *</label>
                <input type="text" id="witness1IdNumber" name="witness1_id_number" value="<?= old('witness1_id_number', $application['witness1_id_number'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="witness1Relationship">Best Man - Relationship *</label>
                <select id="witness1Relationship" name="witness1_relationship" required>
                    <option value="">Select relationship</option>
                    <option value="family" <?= old('witness1_relationship', $application['witness1_relationship'] ?? '') === 'family' ? 'selected' : '' ?>>Family Member</option>
                    <option value="friend" <?= old('witness1_relationship', $application['witness1_relationship'] ?? '') === 'friend' ? 'selected' : '' ?>>Friend</option>
                    <option value="colleague" <?= old('witness1_relationship', $application['witness1_relationship'] ?? '') === 'colleague' ? 'selected' : '' ?>>Colleague</option>
                    <option value="church-member" <?= old('witness1_relationship', $application['witness1_relationship'] ?? '') === 'church-member' ? 'selected' : '' ?>>Church Member</option>
                    <option value="other" <?= old('witness1_relationship', $application['witness1_relationship'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="witness2Name">Matron - Full Name *</label>
                <input type="text" id="witness2Name" name="witness2_name" value="<?= old('witness2_name', $application['witness2_name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="witness2Phone">Matron - Phone *</label>
                <input type="tel" id="witness2Phone" name="witness2_phone" value="<?= old('witness2_phone', $application['witness2_phone'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="witness2IdNumber">Matron - ID Number *</label>
                <input type="text" id="witness2IdNumber" name="witness2_id_number" value="<?= old('witness2_id_number', $application['witness2_id_number'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="witness2Relationship">Matron - Relationship *</label>
                <select id="witness2Relationship" name="witness2_relationship" required>
                    <option value="">Select relationship</option>
                    <option value="family" <?= old('witness2_relationship', $application['witness2_relationship'] ?? '') === 'family' ? 'selected' : '' ?>>Family Member</option>
                    <option value="friend" <?= old('witness2_relationship', $application['witness2_relationship'] ?? '') === 'friend' ? 'selected' : '' ?>>Friend</option>
                    <option value="colleague" <?= old('witness2_relationship', $application['witness2_relationship'] ?? '') === 'colleague' ? 'selected' : '' ?>>Colleague</option>
                    <option value="church-member" <?= old('witness2_relationship', $application['witness2_relationship'] ?? '') === 'church-member' ? 'selected' : '' ?>>Church Member</option>
                    <option value="other" <?= old('witness2_relationship', $application['witness2_relationship'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                </select>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-save triggers for step 3 fields
document.addEventListener('DOMContentLoaded', function() {
    // Add auto-save to all form inputs in step 3
    const step3 = document.querySelector('[data-step="3"]');
    if (step3) {
        const inputs = step3.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                if (window.scheduleAutoSave) {
                    window.scheduleAutoSave();
                }
            });
            
            // For text inputs, also save on blur
            if (input.type === 'text' || input.type === 'email' || input.type === 'tel' || input.tagName === 'TEXTAREA') {
                input.addEventListener('blur', function() {
                    if (window.scheduleAutoSave) {
                        window.scheduleAutoSave();
                    }
                });
            }
        });
    }
});
</script>

<?php
helper('marital_status');

$witnessMarital = witness_marital_status_options();

$w1ms = old('witness1_marital_status', $application['witness1_marital_status'] ?? $application['witness1_relationship'] ?? '');
$w2ms = old('witness2_marital_status', $application['witness2_marital_status'] ?? $application['witness2_relationship'] ?? '');

$legacyBrideFamily = $application['bride_family_phone'] ?? '';
$legacyGroomFamily = $application['groom_family_phone'] ?? '';

$bfd = old('bride_father_status', $application['bride_father_status'] ?? '');
$bfp = old('bride_father_phone', $application['bride_father_phone'] ?? '');
if ($bfp === '' && $legacyBrideFamily !== '') {
    $bfp = $legacyBrideFamily;
}
$bmd = old('bride_mother_status', $application['bride_mother_status'] ?? '');
$bmp = old('bride_mother_phone', $application['bride_mother_phone'] ?? '');
if ($bmp === '' && $legacyBrideFamily !== '') {
    $bmp = $legacyBrideFamily;
}
$gfd = old('groom_father_status', $application['groom_father_status'] ?? '');
$gfp = old('groom_father_phone', $application['groom_father_phone'] ?? '');
if ($gfp === '' && $legacyGroomFamily !== '') {
    $gfp = $legacyGroomFamily;
}
$gmd = old('groom_mother_status', $application['groom_mother_status'] ?? '');
$gmp = old('groom_mother_phone', $application['groom_mother_phone'] ?? '');
if ($gmp === '' && $legacyGroomFamily !== '') {
    $gmp = $legacyGroomFamily;
}
?>
<style>
/* Step 3 only: grouped cards so father/mother and witnesses stay visually aligned */
.form-section[data-step="3"] .step3-family-block {
    margin-bottom: 2rem;
}
.form-section[data-step="3"] .step3-family-block:last-of-type {
    margin-bottom: 0;
}
.form-section[data-step="3"] .step3-block-heading {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--primary-color);
    margin: 0 0 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid rgba(0, 140, 21, 0.2);
    font-family: 'Playfair Display', serif;
}
.form-section[data-step="3"] .step3-parent-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
    align-items: stretch;
}
@media (max-width: 900px) {
    .form-section[data-step="3"] .step3-parent-row {
        grid-template-columns: 1fr;
    }
}
.form-section[data-step="3"] .step3-parent-card {
    background: var(--white);
    border: 1px solid var(--light-gray);
    border-radius: 12px;
    padding: 1.25rem 1.35rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}
.form-section[data-step="3"] .step3-parent-card__title {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--text-color);
    text-transform: uppercase;
    letter-spacing: 0.03em;
    margin: 0 0 0.25rem;
    padding-bottom: 0.65rem;
    border-bottom: 1px solid var(--light-gray);
}
.form-section[data-step="3"] .step3-field {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
}
.form-section[data-step="3"] .step3-field label,
.form-section[data-step="3"] .step3-field .label-block {
    font-weight: 600;
    color: var(--text-color);
    font-size: 0.88rem;
}
.form-section[data-step="3"] .step3-field input,
.form-section[data-step="3"] .step3-field select {
    padding: 12px 16px;
    border: 2px solid var(--light-gray);
    border-radius: 8px;
    font-size: 1rem;
    background: var(--white);
}
.form-section[data-step="3"] .step3-field input:focus,
.form-section[data-step="3"] .step3-field select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(0, 140, 21, 0.1);
}
.form-section[data-step="3"] .step3-witness-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
    align-items: start;
}
@media (max-width: 900px) {
    .form-section[data-step="3"] .step3-witness-row {
        grid-template-columns: 1fr;
    }
}
.form-section[data-step="3"] .step3-witness-card {
    background: var(--white);
    border: 1px solid var(--light-gray);
    border-radius: 12px;
    padding: 1.25rem 1.35rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}
.form-section[data-step="3"] .step3-witness-card__title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--primary-color);
    margin: 0 0 0.35rem;
    padding-bottom: 0.65rem;
    border-bottom: 2px solid rgba(0, 140, 21, 0.15);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.form-section[data-step="3"] .step3-witness-card__title i {
    opacity: 0.85;
}
.form-section[data-step="3"] .step3-field .radio-grid {
    margin-top: 0.25rem;
}
</style>

<!-- Step 3: Additional Information -->
<div class="form-section" data-step="3" style="display: none;">
    <div class="form-section-header">
        <h2>Additional Information</h2>
        <p>Wedding details and family information</p>
    </div>
    
    <div class="person-section">
        <h3 class="section-title">
            <i class="fas fa-users"></i>
            Family Information
        </h3>

        <div class="step3-family-block">
            <h4 class="step3-block-heading">Bride's parents</h4>
            <div class="step3-parent-row">
                <div class="step3-parent-card">
                    <div class="step3-parent-card__title">Father</div>
                    <div class="step3-field">
                        <label for="brideFather">Full name</label>
                        <input type="text" id="brideFather" name="bride_father" value="<?= old('bride_father', $application['bride_father'] ?? '') ?>">
                    </div>
                    <div class="step3-field">
                        <label for="brideFatherOccupation">Occupation</label>
                        <input type="text" id="brideFatherOccupation" name="bride_father_occupation" value="<?= old('bride_father_occupation', $application['bride_father_occupation'] ?? '') ?>">
                    </div>
                    <div class="step3-field">
                        <span class="label-block">Living status *</span>
                        <div class="radio-grid">
                            <div class="radio-option">
                                <input type="radio" id="brideFatherAlive" name="bride_father_status" value="alive" <?= $bfd === 'alive' ? 'checked' : '' ?> required>
                                <label for="brideFatherAlive">Alive</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" id="brideFatherDeceased" name="bride_father_status" value="deceased" <?= $bfd === 'deceased' ? 'checked' : '' ?> required>
                                <label for="brideFatherDeceased">Deceased</label>
                            </div>
                        </div>
                    </div>
                    <div class="step3-field">
                        <label for="brideFatherPhone">Contact phone</label>
                        <input type="tel" id="brideFatherPhone" name="bride_father_phone" value="<?= esc($bfp) ?>" data-parent-phone-for="bride_father_status">
                        <small class="form-text text-muted">Required if alive</small>
                    </div>
                </div>
                <div class="step3-parent-card">
                    <div class="step3-parent-card__title">Mother</div>
                    <div class="step3-field">
                        <label for="brideMother">Full name</label>
                        <input type="text" id="brideMother" name="bride_mother" value="<?= old('bride_mother', $application['bride_mother'] ?? '') ?>">
                    </div>
                    <div class="step3-field">
                        <label for="brideMotherOccupation">Occupation</label>
                        <input type="text" id="brideMotherOccupation" name="bride_mother_occupation" value="<?= old('bride_mother_occupation', $application['bride_mother_occupation'] ?? '') ?>">
                    </div>
                    <div class="step3-field">
                        <span class="label-block">Living status *</span>
                        <div class="radio-grid">
                            <div class="radio-option">
                                <input type="radio" id="brideMotherAlive" name="bride_mother_status" value="alive" <?= $bmd === 'alive' ? 'checked' : '' ?> required>
                                <label for="brideMotherAlive">Alive</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" id="brideMotherDeceased" name="bride_mother_status" value="deceased" <?= $bmd === 'deceased' ? 'checked' : '' ?> required>
                                <label for="brideMotherDeceased">Deceased</label>
                            </div>
                        </div>
                    </div>
                    <div class="step3-field">
                        <label for="brideMotherPhone">Contact phone</label>
                        <input type="tel" id="brideMotherPhone" name="bride_mother_phone" value="<?= esc($bmp) ?>" data-parent-phone-for="bride_mother_status">
                        <small class="form-text text-muted">Required if alive</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="step3-family-block">
            <h4 class="step3-block-heading">Groom's parents</h4>
            <div class="step3-parent-row">
                <div class="step3-parent-card">
                    <div class="step3-parent-card__title">Father</div>
                    <div class="step3-field">
                        <label for="groomFather">Full name</label>
                        <input type="text" id="groomFather" name="groom_father" value="<?= old('groom_father', $application['groom_father'] ?? '') ?>">
                    </div>
                    <div class="step3-field">
                        <label for="groomFatherOccupation">Occupation</label>
                        <input type="text" id="groomFatherOccupation" name="groom_father_occupation" value="<?= old('groom_father_occupation', $application['groom_father_occupation'] ?? '') ?>">
                    </div>
                    <div class="step3-field">
                        <span class="label-block">Living status *</span>
                        <div class="radio-grid">
                            <div class="radio-option">
                                <input type="radio" id="groomFatherAlive" name="groom_father_status" value="alive" <?= $gfd === 'alive' ? 'checked' : '' ?> required>
                                <label for="groomFatherAlive">Alive</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" id="groomFatherDeceased" name="groom_father_status" value="deceased" <?= $gfd === 'deceased' ? 'checked' : '' ?> required>
                                <label for="groomFatherDeceased">Deceased</label>
                            </div>
                        </div>
                    </div>
                    <div class="step3-field">
                        <label for="groomFatherPhone">Contact phone</label>
                        <input type="tel" id="groomFatherPhone" name="groom_father_phone" value="<?= esc($gfp) ?>" data-parent-phone-for="groom_father_status">
                        <small class="form-text text-muted">Required if alive</small>
                    </div>
                </div>
                <div class="step3-parent-card">
                    <div class="step3-parent-card__title">Mother</div>
                    <div class="step3-field">
                        <label for="groomMother">Full name</label>
                        <input type="text" id="groomMother" name="groom_mother" value="<?= old('groom_mother', $application['groom_mother'] ?? '') ?>">
                    </div>
                    <div class="step3-field">
                        <label for="groomMotherOccupation">Occupation</label>
                        <input type="text" id="groomMotherOccupation" name="groom_mother_occupation" value="<?= old('groom_mother_occupation', $application['groom_mother_occupation'] ?? '') ?>">
                    </div>
                    <div class="step3-field">
                        <span class="label-block">Living status *</span>
                        <div class="radio-grid">
                            <div class="radio-option">
                                <input type="radio" id="groomMotherAlive" name="groom_mother_status" value="alive" <?= $gmd === 'alive' ? 'checked' : '' ?> required>
                                <label for="groomMotherAlive">Alive</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" id="groomMotherDeceased" name="groom_mother_status" value="deceased" <?= $gmd === 'deceased' ? 'checked' : '' ?> required>
                                <label for="groomMotherDeceased">Deceased</label>
                            </div>
                        </div>
                    </div>
                    <div class="step3-field">
                        <label for="groomMotherPhone">Contact phone</label>
                        <input type="tel" id="groomMotherPhone" name="groom_mother_phone" value="<?= esc($gmp) ?>" data-parent-phone-for="groom_mother_status">
                        <small class="form-text text-muted">Required if alive</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="person-section">
        <h3 class="section-title">
            <i class="fas fa-user-friends"></i>
            Best Man &amp; Matron
        </h3>

        <div class="step3-witness-row">
            <div class="step3-witness-card">
                <div class="step3-witness-card__title"><i class="fas fa-user"></i> Best man</div>
                <div class="step3-field">
                    <label for="witness1Name">Full name *</label>
                    <input type="text" id="witness1Name" name="witness1_name" value="<?= old('witness1_name', $application['witness1_name'] ?? '') ?>" required>
                </div>
                <div class="step3-field">
                    <label for="witness1Phone">Phone *</label>
                    <input type="tel" id="witness1Phone" name="witness1_phone" value="<?= old('witness1_phone', $application['witness1_phone'] ?? '') ?>" required>
                </div>
                <div class="step3-field">
                    <label for="witness1IdNumber">ID number *</label>
                    <input type="text" id="witness1IdNumber" name="witness1_id_number" value="<?= old('witness1_id_number', $application['witness1_id_number'] ?? '') ?>" required>
                </div>
                <div class="step3-field">
                    <label for="witness1MaritalStatus">Marital status *</label>
                    <select id="witness1MaritalStatus" name="witness1_marital_status" required>
                        <option value="">Select marital status</option>
                        <?php foreach ($witnessMarital as $val => $label) : ?>
                            <option value="<?= esc($val) ?>" <?= $w1ms === $val ? 'selected' : '' ?>><?= esc($label) ?></option>
                        <?php endforeach; ?>
                        <?php if ($w1ms !== '' && ! array_key_exists($w1ms, $witnessMarital)) : ?>
                            <option value="<?= esc($w1ms) ?>" selected><?= esc(witness_marital_status_label($w1ms)) ?> — please update</option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="step3-witness-card">
                <div class="step3-witness-card__title"><i class="fas fa-user"></i> Matron</div>
                <div class="step3-field">
                    <label for="witness2Name">Full name *</label>
                    <input type="text" id="witness2Name" name="witness2_name" value="<?= old('witness2_name', $application['witness2_name'] ?? '') ?>" required>
                </div>
                <div class="step3-field">
                    <label for="witness2Phone">Phone *</label>
                    <input type="tel" id="witness2Phone" name="witness2_phone" value="<?= old('witness2_phone', $application['witness2_phone'] ?? '') ?>" required>
                </div>
                <div class="step3-field">
                    <label for="witness2IdNumber">ID number *</label>
                    <input type="text" id="witness2IdNumber" name="witness2_id_number" value="<?= old('witness2_id_number', $application['witness2_id_number'] ?? '') ?>" required>
                </div>
                <div class="step3-field">
                    <label for="witness2MaritalStatus">Marital status *</label>
                    <select id="witness2MaritalStatus" name="witness2_marital_status" required>
                        <option value="">Select marital status</option>
                        <?php foreach ($witnessMarital as $val => $label) : ?>
                            <option value="<?= esc($val) ?>" <?= $w2ms === $val ? 'selected' : '' ?>><?= esc($label) ?></option>
                        <?php endforeach; ?>
                        <?php if ($w2ms !== '' && ! array_key_exists($w2ms, $witnessMarital)) : ?>
                            <option value="<?= esc($w2ms) ?>" selected><?= esc(witness_marital_status_label($w2ms)) ?> — please update</option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    function syncParentPhoneField(statusName, phoneInput) {
        if (!phoneInput) return;
        var checked = document.querySelector('input[name="' + statusName + '"]:checked');
        var alive = checked && checked.value === 'alive';
        phoneInput.disabled = !alive;
        phoneInput.required = alive;
    }

    function initParentContactToggles() {
        document.querySelectorAll('[data-parent-phone-for]').forEach(function(phoneInput) {
            var statusName = phoneInput.getAttribute('data-parent-phone-for');
            document.querySelectorAll('input[name="' + statusName + '"]').forEach(function(r) {
                r.addEventListener('change', function() {
                    syncParentPhoneField(statusName, phoneInput);
                    if (window.scheduleAutoSave) window.scheduleAutoSave(3);
                });
            });
            syncParentPhoneField(statusName, phoneInput);
        });
    }

    document.addEventListener('DOMContentLoaded', initParentContactToggles);
    window.initStep3ParentContactToggles = initParentContactToggles;
})();

document.addEventListener('DOMContentLoaded', function() {
    const step3 = document.querySelector('[data-step="3"]');
    if (step3) {
        const inputs = step3.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                if (window.scheduleAutoSave) {
                    window.scheduleAutoSave();
                }
            });
            
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

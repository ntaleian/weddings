<?php
helper('residential_address');

$countries = $countries ?? [];
$ba        = parse_residential_address($application['bride_address'] ?? null);
$ga        = parse_residential_address($application['groom_address'] ?? null);

$brideRes = [
    'country'    => old('bride_res_country', $application['bride_res_country'] ?? $ba['country']),
    'region'     => old('bride_res_region', $application['bride_res_region'] ?? $ba['region']),
    'district'   => old('bride_res_district', $application['bride_res_district'] ?? $ba['district']),
    'sub_county' => old('bride_res_sub_county', $application['bride_res_sub_county'] ?? $ba['sub_county']),
    'parish'     => old('bride_res_parish', $application['bride_res_parish'] ?? $ba['parish']),
    'village'    => old('bride_res_village', $application['bride_res_village'] ?? $ba['village']),
];
$groomRes = [
    'country'    => old('groom_res_country', $application['groom_res_country'] ?? $ga['country']),
    'region'     => old('groom_res_region', $application['groom_res_region'] ?? $ga['region']),
    'district'   => old('groom_res_district', $application['groom_res_district'] ?? $ga['district']),
    'sub_county' => old('groom_res_sub_county', $application['groom_res_sub_county'] ?? $ga['sub_county']),
    'parish'     => old('groom_res_parish', $application['groom_res_parish'] ?? $ga['parish']),
    'village'    => old('groom_res_village', $application['groom_res_village'] ?? $ga['village']),
];

$brideNat = old('bride_nationality', $application['bride_nationality'] ?? '');
$groomNat = old('groom_nationality', $application['groom_nationality'] ?? '');
?>
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
                <label for="brideNationality">Nationality *</label>
                <select id="brideNationality" name="bride_nationality" required>
                    <option value="">Select country</option>
                    <?php foreach ($countries as $cname) : ?>
                        <option value="<?= esc($cname) ?>" <?= $brideNat === $cname ? 'selected' : '' ?>><?= esc($cname) ?></option>
                    <?php endforeach; ?>
                    <?php if ($brideNat !== '' && ! in_array($brideNat, $countries, true)) : ?>
                        <option value="<?= esc($brideNat) ?>" selected><?= esc($brideNat) ?></option>
                    <?php endif; ?>
                </select>
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
                <h4 class="subsection-title" style="margin: 0.5rem 0 0.25rem; color: var(--primary-color); font-size: 1rem;">Residential address *</h4>
                <p class="form-text text-muted" style="margin-bottom: 0.75rem;">Country, region, district, sub county, parish, and village</p>
            </div>
            <div class="form-group">
                <label for="brideResCountry">Country *</label>
                <select id="brideResCountry" name="bride_res_country" required
                        onchange="handleResCountryChange('bride', this.value)">
                    <option value="">Select country</option>
                    <?php foreach ($countries as $cname) : ?>
                        <option value="<?= esc($cname) ?>" <?= $brideRes['country'] === $cname ? 'selected' : '' ?>><?= esc($cname) ?></option>
                    <?php endforeach; ?>
                    <?php if ($brideRes['country'] !== '' && ! in_array($brideRes['country'], $countries, true)) : ?>
                        <option value="<?= esc($brideRes['country']) ?>" selected><?= esc($brideRes['country']) ?></option>
                    <?php endif; ?>
                </select>
            </div>
            <!-- Region: dropdown for Uganda, text input otherwise -->
            <div class="form-group" id="brideResRegionGroup">
                <label for="brideResRegion">Region *</label>
                <select id="brideResRegionSelect" style="display:none;"
                        onchange="handleResRegionChange('bride', this.value)">
                    <option value="">Select region</option>
                </select>
                <input type="text" id="brideResRegionText" placeholder="Enter region"
                       value="<?= esc($brideRes['region']) ?>">
                <input type="hidden" id="brideResRegion" name="bride_res_region"
                       value="<?= esc($brideRes['region']) ?>" required>
            </div>
            <!-- District: dropdown for Uganda, text input otherwise -->
            <div class="form-group" id="brideResDistrictGroup">
                <label for="brideResDistrict">District *</label>
                <select id="brideResDistrictSelect" style="display:none;"
                        onchange="handleResDistrictChange('bride', this.value)">
                    <option value="">Select district</option>
                </select>
                <input type="text" id="brideResDistrictText" placeholder="Enter district"
                       value="<?= esc($brideRes['district']) ?>">
                <input type="hidden" id="brideResDistrict" name="bride_res_district"
                       value="<?= esc($brideRes['district']) ?>" required>
            </div>
            <!-- Sub County: dropdown for Uganda, text input otherwise -->
            <div class="form-group" id="brideResSubCountyGroup">
                <label for="brideResSubCounty">Sub County *</label>
                <select id="brideResSubCountySelect" style="display:none;"
                        onchange="handleResSubCountyChange('bride', this.value)">
                    <option value="">Select sub county</option>
                </select>
                <input type="text" id="brideResSubCountyText" placeholder="Enter sub county"
                       value="<?= esc($brideRes['sub_county']) ?>">
                <input type="hidden" id="brideResSubCounty" name="bride_res_sub_county"
                       value="<?= esc($brideRes['sub_county']) ?>" required>
            </div>
            <!-- Parish: dropdown for Uganda, text input otherwise -->
            <div class="form-group" id="brideResParishGroup">
                <label for="brideResParish">Parish *</label>
                <select id="brideResParishSelect" style="display:none;"
                        onchange="handleResParishChange('bride', this.value)">
                    <option value="">Select parish</option>
                </select>
                <input type="text" id="brideResParishText" placeholder="Enter parish"
                       value="<?= esc($brideRes['parish']) ?>">
                <input type="hidden" id="brideResParish" name="bride_res_parish"
                       value="<?= esc($brideRes['parish']) ?>" required>
            </div>
            <!-- Village: dropdown for Uganda, text input otherwise -->
            <div class="form-group" id="brideResVillageGroup">
                <label for="brideResVillage">Village *</label>
                <select id="brideResVillageSelect" style="display:none;"
                        onchange="handleResVillageChange('bride', this.value)">
                    <option value="">Select village</option>
                </select>
                <input type="text" id="brideResVillageText" placeholder="Enter village"
                       value="<?= esc($brideRes['village']) ?>">
                <input type="hidden" id="brideResVillage" name="bride_res_village"
                       value="<?= esc($brideRes['village']) ?>" required>
            </div>
            <?php if (! empty($ba['legacy_plain']) && $ba['raw'] !== '') : ?>
            <div class="form-group full-width">
                <p class="form-text text-muted"><strong>Previously saved address:</strong> <?= esc($ba['raw']) ?> — please re-enter using the fields above.</p>
            </div>
            <?php endif; ?>
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
                <label for="groomNationality">Nationality *</label>
                <select id="groomNationality" name="groom_nationality" required>
                    <option value="">Select country</option>
                    <?php foreach ($countries as $cname) : ?>
                        <option value="<?= esc($cname) ?>" <?= $groomNat === $cname ? 'selected' : '' ?>><?= esc($cname) ?></option>
                    <?php endforeach; ?>
                    <?php if ($groomNat !== '' && ! in_array($groomNat, $countries, true)) : ?>
                        <option value="<?= esc($groomNat) ?>" selected><?= esc($groomNat) ?></option>
                    <?php endif; ?>
                </select>
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
                <h4 class="subsection-title" style="margin: 0.5rem 0 0.25rem; color: var(--primary-color); font-size: 1rem;">Residential address *</h4>
                <p class="form-text text-muted" style="margin-bottom: 0.75rem;">Country, region, district, sub county, parish, and village</p>
            </div>
            <div class="form-group">
                <label for="groomResCountry">Country *</label>
                <select id="groomResCountry" name="groom_res_country" required
                        onchange="handleResCountryChange('groom', this.value)">
                    <option value="">Select country</option>
                    <?php foreach ($countries as $cname) : ?>
                        <option value="<?= esc($cname) ?>" <?= $groomRes['country'] === $cname ? 'selected' : '' ?>><?= esc($cname) ?></option>
                    <?php endforeach; ?>
                    <?php if ($groomRes['country'] !== '' && ! in_array($groomRes['country'], $countries, true)) : ?>
                        <option value="<?= esc($groomRes['country']) ?>" selected><?= esc($groomRes['country']) ?></option>
                    <?php endif; ?>
                </select>
            </div>
            <!-- Region: dropdown for Uganda, text input otherwise -->
            <div class="form-group" id="groomResRegionGroup">
                <label for="groomResRegion">Region *</label>
                <select id="groomResRegionSelect" style="display:none;"
                        onchange="handleResRegionChange('groom', this.value)">
                    <option value="">Select region</option>
                </select>
                <input type="text" id="groomResRegionText" placeholder="Enter region"
                       value="<?= esc($groomRes['region']) ?>">
                <input type="hidden" id="groomResRegion" name="groom_res_region"
                       value="<?= esc($groomRes['region']) ?>" required>
            </div>
            <!-- District: dropdown for Uganda, text input otherwise -->
            <div class="form-group" id="groomResDistrictGroup">
                <label for="groomResDistrict">District *</label>
                <select id="groomResDistrictSelect" style="display:none;"
                        onchange="handleResDistrictChange('groom', this.value)">
                    <option value="">Select district</option>
                </select>
                <input type="text" id="groomResDistrictText" placeholder="Enter district"
                       value="<?= esc($groomRes['district']) ?>">
                <input type="hidden" id="groomResDistrict" name="groom_res_district"
                       value="<?= esc($groomRes['district']) ?>" required>
            </div>
            <!-- Sub County: dropdown for Uganda, text input otherwise -->
            <div class="form-group" id="groomResSubCountyGroup">
                <label for="groomResSubCounty">Sub County *</label>
                <select id="groomResSubCountySelect" style="display:none;"
                        onchange="handleResSubCountyChange('groom', this.value)">
                    <option value="">Select sub county</option>
                </select>
                <input type="text" id="groomResSubCountyText" placeholder="Enter sub county"
                       value="<?= esc($groomRes['sub_county']) ?>">
                <input type="hidden" id="groomResSubCounty" name="groom_res_sub_county"
                       value="<?= esc($groomRes['sub_county']) ?>" required>
            </div>
            <!-- Parish: dropdown for Uganda, text input otherwise -->
            <div class="form-group" id="groomResParishGroup">
                <label for="groomResParish">Parish *</label>
                <select id="groomResParishSelect" style="display:none;"
                        onchange="handleResParishChange('groom', this.value)">
                    <option value="">Select parish</option>
                </select>
                <input type="text" id="groomResParishText" placeholder="Enter parish"
                       value="<?= esc($groomRes['parish']) ?>">
                <input type="hidden" id="groomResParish" name="groom_res_parish"
                       value="<?= esc($groomRes['parish']) ?>" required>
            </div>
            <!-- Village: dropdown for Uganda, text input otherwise -->
            <div class="form-group" id="groomResVillageGroup">
                <label for="groomResVillage">Village *</label>
                <select id="groomResVillageSelect" style="display:none;"
                        onchange="handleResVillageChange('groom', this.value)">
                    <option value="">Select village</option>
                </select>
                <input type="text" id="groomResVillageText" placeholder="Enter village"
                       value="<?= esc($groomRes['village']) ?>">
                <input type="hidden" id="groomResVillage" name="groom_res_village"
                       value="<?= esc($groomRes['village']) ?>" required>
            </div>
            <?php if (! empty($ga['legacy_plain']) && $ga['raw'] !== '') : ?>
            <div class="form-group full-width">
                <p class="form-text text-muted"><strong>Previously saved address:</strong> <?= esc($ga['raw']) ?> — please re-enter using the fields above.</p>
            </div>
            <?php endif; ?>
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
    const watotoFields = ['brideCellGroupField', 'brideCellLeaderField', 'brideCellLeaderPhoneField'];
    const otherFields = ['brideOtherChurchField', 'brideSeniorPastorField', 'bridePastorPhoneField'];
    
    // Hide all fields first
    [...watotoFields, ...otherFields].forEach(id => {
        const field = document.getElementById(id);
        if (field) field.style.display = 'none';
    });
    
    if (brideChurchMember && brideChurchMember.value === 'yes') {
        watotoFields.forEach(id => {
            const field = document.getElementById(id);
            if (field) field.style.display = 'flex';
        });
    } else if (brideChurchMember && brideChurchMember.value === 'other') {
        otherFields.forEach(id => {
            const field = document.getElementById(id);
            if (field) field.style.display = 'flex';
        });
    }
    
    if (window.scheduleAutoSave) {
        window.scheduleAutoSave(2);
    }
}

// Handle groom church membership fields visibility
function toggleGroomChurchFields() {
    const groomChurchMember = document.querySelector('input[name="groom_church_member"]:checked');
    const watotoFields = ['groomCellGroupField', 'groomCellLeaderField', 'groomCellLeaderPhoneField'];
    const otherFields = ['groomOtherChurchField', 'groomSeniorPastorField', 'groomPastorPhoneField'];
    
    [...watotoFields, ...otherFields].forEach(id => {
        const field = document.getElementById(id);
        if (field) field.style.display = 'none';
    });
    
    if (groomChurchMember && groomChurchMember.value === 'yes') {
        watotoFields.forEach(id => {
            const field = document.getElementById(id);
            if (field) field.style.display = 'flex';
        });
    } else if (groomChurchMember && groomChurchMember.value === 'other') {
        otherFields.forEach(id => {
            const field = document.getElementById(id);
            if (field) field.style.display = 'flex';
        });
    }
    
    if (window.scheduleAutoSave) {
        window.scheduleAutoSave(2);
    }
}

document.querySelectorAll('input[name="bride_church_member"]').forEach(radio => {
    radio.addEventListener('change', toggleBrideChurchFields);
});

document.querySelectorAll('input[name="groom_church_member"]').forEach(radio => {
    radio.addEventListener('change', toggleGroomChurchFields);
});

window.addEventListener('DOMContentLoaded', function() {
    const brideDateField = document.getElementById('brideDateOfBirth');
    const groomDateField = document.getElementById('groomDateOfBirth');
    
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
    
    toggleBrideChurchFields();
    toggleGroomChurchFields();
});

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

window.toggleBrideChurchFields = toggleBrideChurchFields;
window.toggleGroomChurchFields = toggleGroomChurchFields;

// Uganda Cascading Location Dropdowns

const UGANDA_API_BASE = '<?= site_url('api/uganda') ?>';
const MANUAL_LOCATION_VALUE = '__manual__';

function isUganda(country) {
    return country.trim().toLowerCase() === 'uganda';
}

async function fetchUgandaRegions() {
    if (window._ugandaRegions) return window._ugandaRegions;
    const res = await fetch(UGANDA_API_BASE + '/regions');
    const data = await res.json();
    window._ugandaRegions = data.success ? data.regions : [];
    return window._ugandaRegions;
}

async function fetchUgandaDistricts(region) {
    const res = await fetch(UGANDA_API_BASE + '/districts/' + encodeURIComponent(region));
    const data = await res.json();
    return data.success ? data.districts : [];
}

async function fetchUgandaSubcounties(district) {
    const res = await fetch(UGANDA_API_BASE + '/subcounties/' + encodeURIComponent(district));
    const data = await res.json();
    return data.success ? data.subcounties : [];
}

async function fetchUgandaParishes(subCounty) {
    const res = await fetch(UGANDA_API_BASE + '/parishes/' + encodeURIComponent(subCounty));
    const data = await res.json();
    return data.success ? data.parishes : [];
}

async function fetchUgandaVillages(parish) {
    const res = await fetch(UGANDA_API_BASE + '/villages/' + encodeURIComponent(parish));
    const data = await res.json();
    return data.success ? data.villages : [];
}

function idFor(person, field) {
    // Maps field keys to element id prefixes.
    const map = {
        region:     { select: person + 'ResRegionSelect',     text: person + 'ResRegionText',     hidden: person + 'ResRegion' },
        district:   { select: person + 'ResDistrictSelect',   text: person + 'ResDistrictText',   hidden: person + 'ResDistrict' },
        sub_county: { select: person + 'ResSubCountySelect',  text: person + 'ResSubCountyText',  hidden: person + 'ResSubCounty' },
        parish:     { select: person + 'ResParishSelect',     text: person + 'ResParishText',     hidden: person + 'ResParish' },
        village:    { select: person + 'ResVillageSelect',    text: person + 'ResVillageText',    hidden: person + 'ResVillage' },
    };
    return map[field];
}

function populateSelect(selectEl, options, placeholder) {
    selectEl.innerHTML = '<option value="">' + placeholder + '</option>';
    options.forEach(function(opt) {
        const o = document.createElement('option');
        o.value = opt;
        o.textContent = opt;
        selectEl.appendChild(o);
    });

    const manualOption = document.createElement('option');
    manualOption.value = MANUAL_LOCATION_VALUE;
    manualOption.textContent = 'Not listed - type manually';
    selectEl.appendChild(manualOption);
}

function valueExists(options, value) {
    return !value || options.indexOf(value) !== -1;
}

function isManualLocationChoice(value) {
    return value === MANUAL_LOCATION_VALUE;
}

function setResMode(person, field, mode, selectedValue) {
    const ids = idFor(person, field);
    if (!ids) return;
    const sel    = document.getElementById(ids.select);
    const txt    = document.getElementById(ids.text);
    const hidden = document.getElementById(ids.hidden);
    if (!sel || !txt || !hidden) return;

    if (mode === 'dropdown') {
        sel.style.display = '';
        txt.style.display = 'none';
        if (selectedValue !== undefined) {
            sel.value  = selectedValue;
            hidden.value = selectedValue;
        }
    } else {
        sel.style.display = 'none';
        txt.style.display = '';
        if (selectedValue !== undefined) {
            txt.value    = selectedValue;
            hidden.value = selectedValue;
        }
    }
}

function setDropdownOptions(person, field, options, placeholder, selectedValue, fallbackToText) {
    const ids = idFor(person, field);
    if (!ids) return;

    const sel = document.getElementById(ids.select);
    if (!sel) return;

    populateSelect(sel, options, placeholder);

    if (fallbackToText && (options.length === 0 || !valueExists(options, selectedValue || ''))) {
        setResMode(person, field, 'text', selectedValue || '');
        return;
    }

    setResMode(person, field, 'dropdown', selectedValue || '');
}

function clearResField(person, field, mode) {
    setResMode(person, field, mode || 'dropdown', '');
    syncResField(person, field, '');
}

function enableManualResField(person, field, dependentFields) {
    setResMode(person, field, 'text', '');
    syncResField(person, field, '');

    dependentFields.forEach(function(dependentField) {
        setResMode(person, dependentField, 'text', '');
        syncResField(person, dependentField, '');
    });

    const ids = idFor(person, field);
    const txt = ids ? document.getElementById(ids.text) : null;
    if (txt) txt.focus();

    if (window.scheduleAutoSave) window.scheduleAutoSave(2);
}

function syncResField(person, field, value) {
    const ids = idFor(person, field);
    if (!ids) return;
    const hidden = document.getElementById(ids.hidden);
    if (hidden) hidden.value = value;
    if (window.scheduleAutoSave) window.scheduleAutoSave(2);
}

async function handleResCountryChange(person, country) {
    if (isUganda(country)) {
        const regions = await fetchUgandaRegions();
        setDropdownOptions(person, 'region', regions, 'Select region', '', false);
        setDropdownOptions(person, 'district', [], 'Select district', '', false);
        setDropdownOptions(person, 'sub_county', [], 'Select sub county', '', false);
        setDropdownOptions(person, 'parish', [], 'Select parish', '', false);
        setDropdownOptions(person, 'village', [], 'Select village', '', false);
    } else {
        setResMode(person, 'region',     'text', '');
        setResMode(person, 'district',   'text', '');
        setResMode(person, 'sub_county', 'text', '');
        setResMode(person, 'parish',     'text', '');
        setResMode(person, 'village',    'text', '');
    }
    syncResField(person, 'region',     '');
    syncResField(person, 'district',   '');
    syncResField(person, 'sub_county', '');
    syncResField(person, 'parish',     '');
    syncResField(person, 'village',    '');
    if (window.scheduleAutoSave) window.scheduleAutoSave(2);
}

async function handleResRegionChange(person, region) {
    if (isManualLocationChoice(region)) {
        enableManualResField(person, 'region', ['district', 'sub_county', 'parish', 'village']);
        return;
    }

    syncResField(person, 'region', region);
    clearResField(person, 'district');
    clearResField(person, 'sub_county');
    clearResField(person, 'parish');
    clearResField(person, 'village');

    if (region) {
        const districts = await fetchUgandaDistricts(region);
        setDropdownOptions(person, 'district', districts, 'Select district', '', true);

        if (districts.length === 0) {
            setResMode(person, 'sub_county', 'text', '');
            setResMode(person, 'parish', 'text', '');
            setResMode(person, 'village', 'text', '');
        }
    } else {
        setDropdownOptions(person, 'district', [], 'Select district', '', false);
        setDropdownOptions(person, 'sub_county', [], 'Select sub county', '', false);
        setDropdownOptions(person, 'parish', [], 'Select parish', '', false);
        setDropdownOptions(person, 'village', [], 'Select village', '', false);
    }
    if (window.scheduleAutoSave) window.scheduleAutoSave(2);
}

async function handleResDistrictChange(person, district) {
    if (isManualLocationChoice(district)) {
        enableManualResField(person, 'district', ['sub_county', 'parish', 'village']);
        return;
    }

    syncResField(person, 'district', district);
    clearResField(person, 'sub_county');
    clearResField(person, 'parish');
    clearResField(person, 'village');

    if (district) {
        const subcounties = await fetchUgandaSubcounties(district);
        setDropdownOptions(person, 'sub_county', subcounties, 'Select sub county', '', true);

        if (subcounties.length === 0) {
            setResMode(person, 'parish', 'text', '');
            setResMode(person, 'village', 'text', '');
        }
    } else {
        setDropdownOptions(person, 'sub_county', [], 'Select sub county', '', false);
        setDropdownOptions(person, 'parish', [], 'Select parish', '', false);
        setDropdownOptions(person, 'village', [], 'Select village', '', false);
    }
    if (window.scheduleAutoSave) window.scheduleAutoSave(2);
}

async function handleResSubCountyChange(person, subCounty) {
    if (isManualLocationChoice(subCounty)) {
        enableManualResField(person, 'sub_county', ['parish', 'village']);
        return;
    }

    syncResField(person, 'sub_county', subCounty);
    clearResField(person, 'parish');
    clearResField(person, 'village');

    if (subCounty) {
        const parishes = await fetchUgandaParishes(subCounty);
        setDropdownOptions(person, 'parish', parishes, 'Select parish', '', true);

        if (parishes.length === 0) {
            setResMode(person, 'village', 'text', '');
        }
    } else {
        setDropdownOptions(person, 'parish', [], 'Select parish', '', false);
        setDropdownOptions(person, 'village', [], 'Select village', '', false);
    }
    if (window.scheduleAutoSave) window.scheduleAutoSave(2);
}

async function handleResParishChange(person, parish) {
    if (isManualLocationChoice(parish)) {
        enableManualResField(person, 'parish', ['village']);
        return;
    }

    syncResField(person, 'parish', parish);
    clearResField(person, 'village');

    if (parish) {
        const villages = await fetchUgandaVillages(parish);
        setDropdownOptions(person, 'village', villages, 'Select village', '', true);
    } else {
        setDropdownOptions(person, 'village', [], 'Select village', '', false);
    }
    if (window.scheduleAutoSave) window.scheduleAutoSave(2);
}

function handleResVillageChange(person, village) {
    if (isManualLocationChoice(village)) {
        enableManualResField(person, 'village', []);
        return;
    }

    syncResField(person, 'village', village);
    if (window.scheduleAutoSave) window.scheduleAutoSave(2);
}

// Sync text inputs to hidden fields on each keystroke.
['bride', 'groom'].forEach(function(person) {
    [
        { suffix: 'Region', key: 'region' },
        { suffix: 'District', key: 'district' },
        { suffix: 'SubCounty', key: 'sub_county' },
        { suffix: 'Parish', key: 'parish' },
        { suffix: 'Village', key: 'village' },
    ].forEach(function(field) {
        const txt = document.getElementById(person + 'Res' + field.suffix + 'Text');
        if (txt) {
            txt.addEventListener('input', function() {
                syncResField(person, field.key, this.value);
            });
        }
    });
});

// On page load: if Uganda is already selected, restore dropdowns
window.addEventListener('DOMContentLoaded', async function() {
    for (const person of ['bride', 'groom']) {
        const countrySel = document.getElementById(person + 'ResCountry');
        if (!countrySel) continue;
        const country = countrySel.value;
        if (!isUganda(country)) continue;

        const savedRegion     = document.getElementById(person + 'ResRegion')?.value     || '';
        const savedDistrict   = document.getElementById(person + 'ResDistrict')?.value   || '';
        const savedSubCounty  = document.getElementById(person + 'ResSubCounty')?.value  || '';
        const savedParish     = document.getElementById(person + 'ResParish')?.value     || '';
        const savedVillage    = document.getElementById(person + 'ResVillage')?.value    || '';

        const regions = await fetchUgandaRegions();
        setDropdownOptions(person, 'region', regions, 'Select region', savedRegion, true);

        if (savedRegion) {
            const districts = await fetchUgandaDistricts(savedRegion);
            setDropdownOptions(person, 'district', districts, 'Select district', savedDistrict, true);

            if (savedDistrict) {
                const subcounties = await fetchUgandaSubcounties(savedDistrict);
                setDropdownOptions(person, 'sub_county', subcounties, 'Select sub county', savedSubCounty, true);

                if (savedSubCounty) {
                    const parishes = await fetchUgandaParishes(savedSubCounty);
                    setDropdownOptions(person, 'parish', parishes, 'Select parish', savedParish, true);

                    if (savedParish) {
                        const villages = await fetchUgandaVillages(savedParish);
                        setDropdownOptions(person, 'village', villages, 'Select village', savedVillage, true);
                    } else {
                        setDropdownOptions(person, 'village', [], 'Select village', savedVillage, Boolean(savedVillage));
                    }
                } else {
                    setDropdownOptions(person, 'parish', [], 'Select parish', savedParish, Boolean(savedParish));
                    setDropdownOptions(person, 'village', [], 'Select village', savedVillage, Boolean(savedVillage));
                }
            } else {
                setResMode(person, 'sub_county', 'dropdown', '');
                setDropdownOptions(person, 'parish', [], 'Select parish', savedParish, Boolean(savedParish));
                setDropdownOptions(person, 'village', [], 'Select village', savedVillage, Boolean(savedVillage));
            }
        } else {
            setResMode(person, 'district',   'dropdown', '');
            setResMode(person, 'sub_county', 'dropdown', '');
            setDropdownOptions(person, 'parish', [], 'Select parish', savedParish, Boolean(savedParish));
            setDropdownOptions(person, 'village', [], 'Select village', savedVillage, Boolean(savedVillage));
        }
    }
});
</script>

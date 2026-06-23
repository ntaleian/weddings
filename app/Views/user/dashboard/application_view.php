<?= $this->extend('layouts/user/base') ?>
<?php
helper('residential_address');
helper('marital_status');

$w1marital = $booking['witness1_marital_status'] ?? $booking['witness1_relationship'] ?? null;
$w2marital = $booking['witness2_marital_status'] ?? $booking['witness2_relationship'] ?? null;
?>

<?= $this->section('styles') ?>
    <link href="<?= asset_url('assets/css/dashboard.css') ?>" rel="stylesheet">
    <link href="<?= asset_url('assets/css/dashboard-components.css') ?>" rel="stylesheet">
    <style>
        .application-header {
            background: var(--primary-color);
            color: white;
            padding: 24px 30px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 2px 12px rgba(0, 140, 21, 0.15);
        }

        .application-header h1 {
            margin: 0 0 8px 0;
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .application-header p {
            margin: 0;
            font-size: 0.95rem;
            opacity: 0.95;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 600;
            margin-top: 12px;
            font-size: 0.9rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .status-pending {
            background-color: #FBD110;
            color: #1a1a1a;
        }

        .status-approved {
            background-color: #008C15;
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

        .application-details {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        }

        .detail-section {
            margin-bottom: 24px;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 20px;
        }

        .detail-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .section-title {
            color: var(--primary-color);
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 8px;
            border-bottom: 2px solid rgba(0, 140, 21, 0.15);
        }

        .section-title i {
            font-size: 1.1rem;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 12px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        .detail-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 0.75rem;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .detail-value {
            color: var(--text-color);
            font-size: 0.9rem;
            font-weight: 500;
            line-height: 1.4;
            word-wrap: break-word;
        }

        .detail-value:empty::after {
            content: "Not provided";
            color: #adb5bd;
            font-style: italic;
            font-weight: 400;
        }

        .detail-item h4 {
            color: var(--primary-color);
            margin: 0 0 10px 0;
            font-size: 1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            padding-bottom: 8px;
            border-bottom: 1px solid rgba(0, 140, 21, 0.12);
        }

        .detail-item h4 i {
            font-size: 0.9rem;
        }

        .action-buttons {
            display: flex;
            gap: 12px;
            margin-top: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .action-buttons .btn {
            min-width: 140px;
            padding: 10px 20px;
            font-size: 0.9rem;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .application-header {
                padding: 20px 16px;
                margin-bottom: 16px;
            }

            .application-header h1 {
                font-size: 1.3rem;
                flex-direction: column;
                gap: 6px;
            }

            .application-header p {
                font-size: 0.9rem;
            }

            .status-badge {
                padding: 6px 14px;
                font-size: 0.85rem;
                margin-top: 10px;
            }

            .application-details {
                padding: 20px 16px;
                margin-bottom: 16px;
            }

            .detail-section {
                margin-bottom: 20px;
                padding-bottom: 16px;
            }

            .section-title {
                font-size: 1.1rem;
                margin-bottom: 14px;
                padding-bottom: 6px;
            }

            .section-title i {
                font-size: 1rem;
            }

            .detail-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .detail-item {
                padding: 12px;
            }

            .detail-label {
                font-size: 0.7rem;
                margin-bottom: 4px;
            }

            .detail-value {
                font-size: 0.85rem;
            }

            .detail-item h4 {
                font-size: 0.95rem;
                margin-bottom: 8px;
                padding-bottom: 6px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 10px;
                margin-top: 16px;
            }

            .action-buttons .btn {
                width: 100%;
                min-width: auto;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .application-header {
                padding: 18px 14px;
            }

            .application-header h1 {
                font-size: 1.2rem;
            }

            .application-header p {
                font-size: 0.85rem;
            }

            .status-badge {
                padding: 5px 12px;
                font-size: 0.8rem;
            }

            .application-details {
                padding: 16px 14px;
            }

            .detail-section {
                margin-bottom: 18px;
                padding-bottom: 14px;
            }

            .section-title {
                font-size: 1rem;
                margin-bottom: 12px;
            }

            .detail-item {
                padding: 10px;
            }

            .detail-label {
                font-size: 0.7rem;
            }

            .detail-value {
                font-size: 0.85rem;
            }

            .detail-item h4 {
                font-size: 0.9rem;
                margin-bottom: 8px;
            }
        }

        @media print {
            .action-buttons,
            .dashboard-container nav,
            .dashboard-sidebar,
            .dashboard-nav,
            .application-header {
                display: none !important;
            }

            .dashboard-main {
                padding: 0 !important;
                max-width: 100% !important;
            }

            #applicationDetailsPrint {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
            }

            #applicationDetailsPrint .detail-section {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            #applicationDetailsPrint .detail-item {
                background: white;
                border: 1px solid #e9ecef;
            }
        }
    </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?= $this->include('partials/user_nav') ?>
    
    <div class="dashboard-container">
        <?= $this->include('partials/user_sidebar') ?>
        
        <main class="dashboard-main">
            <!-- Flash Messages -->
            <?= $this->include('partials/flash_messages') ?>

            <!-- Application Header -->
            <div class="application-header">
                <h1><i class="fas fa-eye"></i> Wedding Application - View Only</h1>
                <p>Your application has been submitted and is currently being reviewed</p>
                <div class="status-badge status-<?= $applicationStatus ?>">
                    Status: <?= ucfirst($applicationStatus) ?>
                </div>
            </div>

            <!-- Application Details (also used as source for Download PDF) -->
            <div class="application-details" id="applicationDetailsPrint">
                <!-- Venue & Date Information -->
                <div class="detail-section">
                    <h3 class="section-title">
                        <i class="fas fa-map-marker-alt"></i>
                        Venue & Date Information
                    </h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Campus</span>
                            <span class="detail-value"><?= esc($booking['campus_name'] ?? '') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Wedding Date</span>
                            <span class="detail-value"><?= date('F j, Y', strtotime($booking['wedding_date'] ?? '')) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Wedding Time</span>
                            <span class="detail-value"><?= date('g:i A', strtotime($booking['wedding_time'] ?? '')) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Bride Information -->
                <div class="detail-section">
                    <h3 class="section-title">
                        <i class="fas fa-female"></i>
                        Bride Information
                    </h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Full Name</span>
                            <span class="detail-value"><?= esc($booking['bride_name'] ?? '') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Date of Birth</span>
                            <span class="detail-value"><?= $booking['bride_date_of_birth'] ? date('F j, Y', strtotime($booking['bride_date_of_birth'])) : 'Not provided' ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Age</span>
                            <span class="detail-value"><?= esc($booking['bride_age'] ?? '') ?><?= $booking['bride_age'] ? ' years' : '' ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Email</span>
                            <span class="detail-value"><?= esc($booking['bride_email'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Phone</span>
                            <span class="detail-value"><?= esc($booking['bride_phone'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Occupation</span>
                            <span class="detail-value"><?= esc($booking['bride_occupation'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Nationality</span>
                            <span class="detail-value"><?= esc($booking['bride_nationality'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Marital Status</span>
                            <span class="detail-value"><?php
                                $brideStatus = $booking['bride_marital_status'] ?? '';
                                $brideStatusLabels = [
                                    'spinster' => 'Spinster',
                                    'divorced-separated' => 'Divorced/Separated',
                                    'married-traditionally' => 'Married Traditionally',
                                    'widowed' => 'Widowed',
                                    'civil-marriage' => 'Civil Marriage',
                                    'cohabiting' => 'Cohabiting',
                                    'single' => 'Single',
                                    'divorced' => 'Divorced'
                                ];
                                echo esc($brideStatusLabels[$brideStatus] ?? ucfirst(str_replace('-', ' ', $brideStatus)) ?: 'Not provided');
                            ?></span>
                        </div>
                        <div class="detail-item" style="grid-column: 1 / -1;">
                            <span class="detail-label">Residential address</span>
                            <span class="detail-value"><?= format_residential_address_html($booking['bride_address'] ?? null) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">ID/Passport Number</span>
                            <span class="detail-value"><?= esc($booking['bride_id_number'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">ID Type</span>
                            <span class="detail-value"><?php
                                $idTypes = [
                                    'national_id' => 'National ID',
                                    'passport' => 'Passport',
                                    'driving_license' => 'Driving License'
                                ];
                                echo esc($idTypes[$booking['bride_id_type'] ?? ''] ?? ucfirst(str_replace('_', ' ', $booking['bride_id_type'] ?? 'Not provided')));
                            ?></span>
                        </div>
                    </div>
                </div>

                <!-- Groom Information -->
                <div class="detail-section">
                    <h3 class="section-title">
                        <i class="fas fa-male"></i>
                        Groom Information
                    </h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Full Name</span>
                            <span class="detail-value"><?= esc($booking['groom_name'] ?? '') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Date of Birth</span>
                            <span class="detail-value"><?= $booking['groom_date_of_birth'] ? date('F j, Y', strtotime($booking['groom_date_of_birth'])) : 'Not provided' ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Age</span>
                            <span class="detail-value"><?= esc($booking['groom_age'] ?? '') ?><?= $booking['groom_age'] ? ' years' : '' ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Email</span>
                            <span class="detail-value"><?= esc($booking['groom_email'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Phone</span>
                            <span class="detail-value"><?= esc($booking['groom_phone'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Occupation</span>
                            <span class="detail-value"><?= esc($booking['groom_occupation'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Nationality</span>
                            <span class="detail-value"><?= esc($booking['groom_nationality'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Marital Status</span>
                            <span class="detail-value"><?php
                                $groomStatus = $booking['groom_marital_status'] ?? '';
                                $groomStatusLabels = [
                                    'bachelor' => 'Bachelor',
                                    'divorced-separated' => 'Divorced/Separated',
                                    'married-traditionally' => 'Married Traditionally',
                                    'widowed' => 'Widowed',
                                    'civil-marriage' => 'Civil Marriage',
                                    'cohabiting' => 'Cohabiting',
                                    'single' => 'Single',
                                    'divorced' => 'Divorced'
                                ];
                                echo esc($groomStatusLabels[$groomStatus] ?? ucfirst(str_replace('-', ' ', $groomStatus)) ?: 'Not provided');
                            ?></span>
                        </div>
                        <div class="detail-item" style="grid-column: 1 / -1;">
                            <span class="detail-label">Residential address</span>
                            <span class="detail-value"><?= format_residential_address_html($booking['groom_address'] ?? null) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">ID/Passport Number</span>
                            <span class="detail-value"><?= esc($booking['groom_id_number'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">ID Type</span>
                            <span class="detail-value"><?php
                                $idTypes = [
                                    'national_id' => 'National ID',
                                    'passport' => 'Passport',
                                    'driving_license' => 'Driving License'
                                ];
                                echo esc($idTypes[$booking['groom_id_type'] ?? ''] ?? ucfirst(str_replace('_', ' ', $booking['groom_id_type'] ?? 'Not provided')));
                            ?></span>
                        </div>
                    </div>
                </div>

                <!-- Church Membership Information -->
                <div class="detail-section">
                    <h3 class="section-title">
                        <i class="fas fa-church"></i>
                        Church Membership
                    </h3>
                    <div class="detail-grid">
                        <div class="detail-item" style="grid-column: 1 / -1;">
                            <h4 style="color: var(--primary-color); margin-bottom: 15px; font-size: 1.1rem;">
                                <i class="fas fa-female"></i> Bride's Membership
                            </h4>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Church Member</span>
                            <span class="detail-value"><?php
                                $brideChurchMember = $booking['bride_church_member'] ?? '';
                                if ($brideChurchMember === 'yes') {
                                    echo 'Yes - Watoto Church';
                                } elseif ($brideChurchMember === 'other') {
                                    echo 'Yes - Other Church';
                                } elseif ($brideChurchMember === 'no') {
                                    echo 'No';
                                } else {
                                    echo 'Not specified';
                                }
                            ?></span>
                        </div>
                        <?php if ($booking['bride_church_member'] === 'yes'): ?>
                            <div class="detail-item">
                                <span class="detail-label">Cell Family Number</span>
                                <span class="detail-value"><?= esc($booking['bride_cell_group_number'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Cell Family Leader Name</span>
                                <span class="detail-value"><?= esc($booking['bride_cell_leader_name'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Cell Family Leader Phone</span>
                                <span class="detail-value"><?= esc($booking['bride_cell_leader_phone'] ?? 'Not provided') ?></span>
                            </div>
                        <?php elseif ($booking['bride_church_member'] === 'other'): ?>
                            <div class="detail-item">
                                <span class="detail-label">Church Name</span>
                                <span class="detail-value"><?= esc($booking['bride_church_name'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Senior Pastor Name</span>
                                <span class="detail-value"><?= esc($booking['bride_senior_pastor'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Senior Pastor Phone</span>
                                <span class="detail-value"><?= esc($booking['bride_pastor_phone'] ?? 'Not provided') ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="detail-item" style="grid-column: 1 / -1; margin-top: 20px;">
                            <h4 style="color: var(--primary-color); margin-bottom: 15px; font-size: 1.1rem;">
                                <i class="fas fa-male"></i> Groom's Membership
                            </h4>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Church Member</span>
                            <span class="detail-value"><?php
                                $groomChurchMember = $booking['groom_church_member'] ?? '';
                                if ($groomChurchMember === 'yes') {
                                    echo 'Yes - Watoto Church';
                                } elseif ($groomChurchMember === 'other') {
                                    echo 'Yes - Other Church';
                                } elseif ($groomChurchMember === 'no') {
                                    echo 'No';
                                } else {
                                    echo 'Not specified';
                                }
                            ?></span>
                        </div>
                        <?php if ($booking['groom_church_member'] === 'yes'): ?>
                            <div class="detail-item">
                                <span class="detail-label">Cell Family Number</span>
                                <span class="detail-value"><?= esc($booking['groom_cell_group_number'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Cell Family Leader Name</span>
                                <span class="detail-value"><?= esc($booking['groom_cell_leader_name'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Cell Family Leader Phone</span>
                                <span class="detail-value"><?= esc($booking['groom_cell_leader_phone'] ?? 'Not provided') ?></span>
                            </div>
                        <?php elseif ($booking['groom_church_member'] === 'other'): ?>
                            <div class="detail-item">
                                <span class="detail-label">Church Name</span>
                                <span class="detail-value"><?= esc($booking['groom_church_name'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Senior Pastor Name</span>
                                <span class="detail-value"><?= esc($booking['groom_senior_pastor'] ?? 'Not provided') ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Senior Pastor Phone</span>
                                <span class="detail-value"><?= esc($booking['groom_pastor_phone'] ?? 'Not provided') ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Family Information -->
                <div class="detail-section">
                    <h3 class="section-title">
                        <i class="fas fa-users"></i>
                        Family Information
                    </h3>
                    <div class="detail-grid">
                        <div class="detail-item" style="grid-column: 1 / -1;">
                            <h4 style="color: var(--primary-color); margin-bottom: 15px; font-size: 1.1rem;">
                                <i class="fas fa-female"></i> Bride's Family
                            </h4>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Father's name</span>
                            <span class="detail-value"><?= esc($booking['bride_father'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Father's occupation</span>
                            <span class="detail-value"><?= esc($booking['bride_father_occupation'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Father's status</span>
                            <span class="detail-value"><?= esc(parent_living_label($booking['bride_father_status'] ?? null)) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Father's contact</span>
                            <span class="detail-value"><?php
                                $s = $booking['bride_father_status'] ?? '';
                                $p = trim((string) ($booking['bride_father_phone'] ?? ''));
                                if ($s === 'deceased') {
                                    echo 'N/A';
                                } elseif ($p !== '') {
                                    echo esc($p);
                                } elseif (! empty($booking['bride_family_phone'])) {
                                    echo esc($booking['bride_family_phone']);
                                } else {
                                    echo 'Not provided';
                                }
                            ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Mother's name</span>
                            <span class="detail-value"><?= esc($booking['bride_mother'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Mother's occupation</span>
                            <span class="detail-value"><?= esc($booking['bride_mother_occupation'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Mother's status</span>
                            <span class="detail-value"><?= esc(parent_living_label($booking['bride_mother_status'] ?? null)) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Mother's contact</span>
                            <span class="detail-value"><?php
                                $s = $booking['bride_mother_status'] ?? '';
                                $p = trim((string) ($booking['bride_mother_phone'] ?? ''));
                                if ($s === 'deceased') {
                                    echo 'N/A';
                                } elseif ($p !== '') {
                                    echo esc($p);
                                } elseif (! empty($booking['bride_family_phone'])) {
                                    echo esc($booking['bride_family_phone']);
                                } else {
                                    echo 'Not provided';
                                }
                            ?></span>
                        </div>
                        
                        <div class="detail-item" style="grid-column: 1 / -1; margin-top: 20px;">
                            <h4 style="color: var(--primary-color); margin-bottom: 15px; font-size: 1.1rem;">
                                <i class="fas fa-male"></i> Groom's Family
                            </h4>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Father's name</span>
                            <span class="detail-value"><?= esc($booking['groom_father'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Father's occupation</span>
                            <span class="detail-value"><?= esc($booking['groom_father_occupation'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Father's status</span>
                            <span class="detail-value"><?= esc(parent_living_label($booking['groom_father_status'] ?? null)) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Father's contact</span>
                            <span class="detail-value"><?php
                                $s = $booking['groom_father_status'] ?? '';
                                $p = trim((string) ($booking['groom_father_phone'] ?? ''));
                                if ($s === 'deceased') {
                                    echo 'N/A';
                                } elseif ($p !== '') {
                                    echo esc($p);
                                } elseif (! empty($booking['groom_family_phone'])) {
                                    echo esc($booking['groom_family_phone']);
                                } else {
                                    echo 'Not provided';
                                }
                            ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Mother's name</span>
                            <span class="detail-value"><?= esc($booking['groom_mother'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Mother's occupation</span>
                            <span class="detail-value"><?= esc($booking['groom_mother_occupation'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Mother's status</span>
                            <span class="detail-value"><?= esc(parent_living_label($booking['groom_mother_status'] ?? null)) ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Mother's contact</span>
                            <span class="detail-value"><?php
                                $s = $booking['groom_mother_status'] ?? '';
                                $p = trim((string) ($booking['groom_mother_phone'] ?? ''));
                                if ($s === 'deceased') {
                                    echo 'N/A';
                                } elseif ($p !== '') {
                                    echo esc($p);
                                } elseif (! empty($booking['groom_family_phone'])) {
                                    echo esc($booking['groom_family_phone']);
                                } else {
                                    echo 'Not provided';
                                }
                            ?></span>
                        </div>
                    </div>
                </div>

                <!-- Best Man & Matron Information -->
                <div class="detail-section">
                    <h3 class="section-title">
                        <i class="fas fa-user-friends"></i>
                        Best Man & Matron Information
                    </h3>
                    <div class="detail-grid">
                        <div class="detail-item" style="grid-column: 1 / -1;">
                            <h4 style="color: var(--primary-color); margin-bottom: 15px; font-size: 1.1rem;">
                                <i class="fas fa-user"></i> Best Man
                            </h4>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Full Name</span>
                            <span class="detail-value"><?= esc($booking['witness1_name'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Phone</span>
                            <span class="detail-value"><?= esc($booking['witness1_phone'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Occupation</span>
                            <span class="detail-value"><?= esc($booking['witness1_occupation'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">ID Number</span>
                            <span class="detail-value"><?= esc($booking['witness1_id_number'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Marital status</span>
                            <span class="detail-value"><?= esc(witness_marital_status_label($w1marital)) ?></span>
                        </div>
                        
                        <div class="detail-item" style="grid-column: 1 / -1; margin-top: 20px;">
                            <h4 style="color: var(--primary-color); margin-bottom: 15px; font-size: 1.1rem;">
                                <i class="fas fa-user"></i> Matron
                            </h4>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Full Name</span>
                            <span class="detail-value"><?= esc($booking['witness2_name'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Phone</span>
                            <span class="detail-value"><?= esc($booking['witness2_phone'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Occupation</span>
                            <span class="detail-value"><?= esc($booking['witness2_occupation'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">ID Number</span>
                            <span class="detail-value"><?= esc($booking['witness2_id_number'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Marital status</span>
                            <span class="detail-value"><?= esc(witness_marital_status_label($w2marital)) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button type="button" onclick="downloadApplicationPdf()" class="btn btn-primary">
                    <i class="fas fa-file-pdf"></i>
                    Download PDF
                </button>
                <button type="button" onclick="window.print()" class="btn btn-outline">
                    <i class="fas fa-print"></i>
                    Print
                </button>
                <a href="<?= site_url('dashboard') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Dashboard
                </a>
            </div>
        </main>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <?= $this->include('user/dashboard/partials/application_view_pdf_script') ?>
    <script>
        console.log('Application view page loaded - Status:', <?= json_encode($applicationStatus ?? '') ?>);
    </script>
<?= $this->endSection() ?>

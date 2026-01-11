<?= $this->extend('layouts/user/base') ?>

<?= $this->section('styles') ?>
    <link href="<?= base_url('assets/css/dashboard.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/dashboard-components.css') ?>" rel="stylesheet">
    <style>
        .application-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 24px 30px;
            border-radius: 12px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 2px 12px rgba(100, 1, 127, 0.12);
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
            border-bottom: 2px solid rgba(100, 1, 127, 0.1);
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
            border-bottom: 1px solid rgba(100, 1, 127, 0.1);
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
            .dashboard-nav {
                display: none !important;
            }

            .application-details {
                box-shadow: none;
                border: 1px solid #ddd;
            }

            .detail-item {
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

            <!-- Application Details -->
            <div class="application-details">
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
                            <span class="detail-label">Place of Birth</span>
                            <span class="detail-value"><?= esc($booking['bride_birth_place'] ?? 'Not provided') ?></span>
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
                            <span class="detail-label">Employer/Company</span>
                            <span class="detail-value"><?= esc($booking['bride_employer'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Education Level</span>
                            <span class="detail-value"><?php
                                $educationLevels = [
                                    'primary' => 'Primary',
                                    'secondary' => 'Secondary',
                                    'diploma' => 'Diploma',
                                    'degree' => "Bachelor's Degree",
                                    'masters' => "Master's Degree",
                                    'phd' => 'PhD'
                                ];
                                echo esc($educationLevels[$booking['bride_education_level'] ?? ''] ?? ucfirst($booking['bride_education_level'] ?? 'Not provided'));
                            ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Nationality</span>
                            <span class="detail-value"><?= esc($booking['bride_nationality'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Religion</span>
                            <span class="detail-value"><?= esc($booking['bride_religion'] ?? 'Not provided') ?></span>
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
                            <span class="detail-label">Current Address</span>
                            <span class="detail-value"><?= esc($booking['bride_address'] ?? 'Not provided') ?></span>
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
                            <span class="detail-label">Place of Birth</span>
                            <span class="detail-value"><?= esc($booking['groom_birth_place'] ?? 'Not provided') ?></span>
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
                            <span class="detail-label">Employer/Company</span>
                            <span class="detail-value"><?= esc($booking['groom_employer'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Education Level</span>
                            <span class="detail-value"><?php
                                $educationLevels = [
                                    'primary' => 'Primary',
                                    'secondary' => 'Secondary',
                                    'diploma' => 'Diploma',
                                    'degree' => "Bachelor's Degree",
                                    'masters' => "Master's Degree",
                                    'phd' => 'PhD'
                                ];
                                echo esc($educationLevels[$booking['groom_education_level'] ?? ''] ?? ucfirst($booking['groom_education_level'] ?? 'Not provided'));
                            ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Nationality</span>
                            <span class="detail-value"><?= esc($booking['groom_nationality'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Religion</span>
                            <span class="detail-value"><?= esc($booking['groom_religion'] ?? 'Not provided') ?></span>
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
                            <span class="detail-label">Current Address</span>
                            <span class="detail-value"><?= esc($booking['groom_address'] ?? 'Not provided') ?></span>
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
                                <span class="detail-label">Membership Duration</span>
                                <span class="detail-value"><?= esc($booking['bride_membership_duration'] ?? 'Not provided') ?></span>
                            </div>
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
                                <span class="detail-label">Membership Duration</span>
                                <span class="detail-value"><?= esc($booking['groom_membership_duration'] ?? 'Not provided') ?></span>
                            </div>
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
                            <span class="detail-label">Father's Name</span>
                            <span class="detail-value"><?= esc($booking['bride_father'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Father's Occupation</span>
                            <span class="detail-value"><?= esc($booking['bride_father_occupation'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Mother's Name</span>
                            <span class="detail-value"><?= esc($booking['bride_mother'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Mother's Occupation</span>
                            <span class="detail-value"><?= esc($booking['bride_mother_occupation'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Family Contact</span>
                            <span class="detail-value"><?= esc($booking['bride_family_phone'] ?? 'Not provided') ?></span>
                        </div>
                        
                        <div class="detail-item" style="grid-column: 1 / -1; margin-top: 20px;">
                            <h4 style="color: var(--primary-color); margin-bottom: 15px; font-size: 1.1rem;">
                                <i class="fas fa-male"></i> Groom's Family
                            </h4>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Father's Name</span>
                            <span class="detail-value"><?= esc($booking['groom_father'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Father's Occupation</span>
                            <span class="detail-value"><?= esc($booking['groom_father_occupation'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Mother's Name</span>
                            <span class="detail-value"><?= esc($booking['groom_mother'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Mother's Occupation</span>
                            <span class="detail-value"><?= esc($booking['groom_mother_occupation'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Family Contact</span>
                            <span class="detail-value"><?= esc($booking['groom_family_phone'] ?? 'Not provided') ?></span>
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
                            <span class="detail-label">ID Number</span>
                            <span class="detail-value"><?= esc($booking['witness1_id_number'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Relationship</span>
                            <span class="detail-value"><?php
                                $relationships = [
                                    'family' => 'Family Member',
                                    'friend' => 'Friend',
                                    'colleague' => 'Colleague',
                                    'church-member' => 'Church Member',
                                    'other' => 'Other'
                                ];
                                echo esc($relationships[$booking['witness1_relationship'] ?? ''] ?? ucfirst(str_replace('-', ' ', $booking['witness1_relationship'] ?? 'Not provided')));
                            ?></span>
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
                            <span class="detail-label">ID Number</span>
                            <span class="detail-value"><?= esc($booking['witness2_id_number'] ?? 'Not provided') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Relationship</span>
                            <span class="detail-value"><?php
                                $relationships = [
                                    'family' => 'Family Member',
                                    'friend' => 'Friend',
                                    'colleague' => 'Colleague',
                                    'church-member' => 'Church Member',
                                    'other' => 'Other'
                                ];
                                echo esc($relationships[$booking['witness2_relationship'] ?? ''] ?? ucfirst(str_replace('-', ' ', $booking['witness2_relationship'] ?? 'Not provided')));
                            ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print"></i>
                    Print Application
                </button>
                <a href="<?= site_url('dashboard') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Dashboard
                </a>
                <a href="<?= site_url('dashboard/messages') ?>" class="btn btn-outline">
                    <i class="fas fa-comments"></i>
                    Contact Coordinator
                </a>
            </div>
        </main>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        // Add any specific scripts for the view page if needed
        console.log('Application view page loaded - Status: <?= $applicationStatus ?>');
    </script>
<?= $this->endSection() ?>

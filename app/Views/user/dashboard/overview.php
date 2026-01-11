<?= $this->extend('layouts/user/base') ?>

<?= $this->section('styles') ?>
    <link href="<?= base_url('assets/css/dashboard.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/dashboard-components.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/dashboard-sections.css') ?>" rel="stylesheet">
    <style>
        /* Welcome Section - Compact & Modern */
        .welcome-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--white);
            padding: 24px 30px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 12px rgba(100, 1, 127, 0.15);
        }

        .welcome-content h1 {
            font-size: 1.6rem;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .welcome-content p {
            font-size: 0.95rem;
            opacity: 0.95;
            margin: 0;
        }

        .welcome-actions {
            display: flex;
            gap: 12px;
        }

        .welcome-actions .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Progress Overview - Refined */
        .progress-overview {
            background: var(--white);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            border: 1px solid #e9ecef;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e9ecef;
        }

        .section-header h2 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .progress-percentage {
            background: var(--success-color);
            color: var(--white);
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .progress-steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }

        .progress-step {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            background: #f8f9fa;
            transition: all 0.2s ease;
        }

        .progress-step:hover {
            border-color: var(--primary-color);
            background: rgba(100, 1, 127, 0.05);
        }

        .progress-step.completed {
            border-color: var(--success-color);
            background: rgba(46, 204, 113, 0.08);
        }

        .progress-step.current {
            border-color: var(--primary-color);
            background: rgba(100, 1, 127, 0.1);
            box-shadow: 0 2px 8px rgba(100, 1, 127, 0.15);
        }

        .step-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            background: #dee2e6;
            color: #6c757d;
            flex-shrink: 0;
        }

        .progress-step.completed .step-icon {
            background: var(--success-color);
            color: var(--white);
        }

        .progress-step.current .step-icon {
            background: var(--primary-color);
            color: var(--white);
        }

        .step-content {
            flex: 1;
            min-width: 0;
        }

        .step-content h4 {
            margin: 0 0 4px 0;
            color: var(--text-color);
            font-size: 0.95rem;
            font-weight: 600;
        }

        .step-content p {
            margin: 0;
            color: #6c757d;
            font-size: 0.8rem;
        }

        .progress-bar {
            height: 6px;
            background: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 16px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--success-color));
            transition: width 0.3s ease;
        }

        /* Quick Actions - Modern Cards */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .action-card {
            background: var(--white);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            border: 1px solid #e9ecef;
            transition: all 0.2s ease;
        }

        .action-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-color);
        }

        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .card-icon i {
            font-size: 1.3rem;
            color: var(--white);
        }

        .card-content h3 {
            margin: 0 0 8px 0;
            color: var(--text-color);
            font-size: 1.1rem;
            font-weight: 700;
        }

        .card-content p {
            margin: 0 0 16px 0;
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .card-content .btn {
            padding: 8px 16px;
            font-size: 0.85rem;
            border-radius: 6px;
        }
        
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .welcome-section {
                flex-direction: column;
                text-align: center;
                padding: 20px;
                gap: 16px;
                align-items: center;
            }
            
            .welcome-content h1 {
                font-size: 1.4rem;
                margin-bottom: 6px;
            }
            
            .welcome-content p {
                font-size: 0.9rem;
            }
            
            .welcome-actions {
                flex-direction: column;
                width: 100%;
                gap: 10px;
            }
            
            .welcome-actions .btn {
                width: 100%;
                justify-content: center;
            }
            
            .progress-overview {
                padding: 20px;
                margin-bottom: 20px;
            }
            
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
                margin-bottom: 16px;
                padding-bottom: 10px;
            }

            .section-header h2 {
                font-size: 1.2rem;
            }
            
            .progress-steps {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .progress-step {
                padding: 12px;
                gap: 10px;
            }
            
            .step-icon {
                width: 32px;
                height: 32px;
                font-size: 0.85rem;
            }
            
            .step-content h4 {
                font-size: 0.9rem;
            }
            
            .step-content p {
                font-size: 0.75rem;
            }

            .progress-bar {
                margin-top: 12px;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
                gap: 12px;
                margin-bottom: 20px;
            }
            
            .action-card {
                padding: 18px;
            }
            
            .card-icon {
                width: 45px;
                height: 45px;
                margin-bottom: 14px;
            }
            
            .card-icon i {
                font-size: 1.2rem;
            }
            
            .card-content h3 {
                font-size: 1rem;
                margin-bottom: 6px;
            }
            
            .card-content p {
                font-size: 0.85rem;
                margin-bottom: 14px;
            }
        }
        
        @media (max-width: 480px) {
            .welcome-section {
                padding: 18px 16px;
            }
            
            .welcome-content h1 {
                font-size: 1.2rem;
            }
            
            .welcome-content p {
                font-size: 0.85rem;
            }
            
            .progress-overview {
                padding: 16px;
            }
            
            .section-header h2 {
                font-size: 1.1rem;
            }
            
            .progress-step {
                padding: 10px;
            }
            
            .step-icon {
                width: 28px;
                height: 28px;
                font-size: 0.8rem;
            }

            .action-card {
                padding: 16px;
            }
            
            .card-icon {
                width: 40px;
                height: 40px;
                margin-bottom: 12px;
            }

            .card-icon i {
                font-size: 1.1rem;
            }
        }
        </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?= $this->include('partials/user_nav') ?>
    
    <!-- Dashboard Main Content -->
    <div class="dashboard-container">
        <?= $this->include('partials/user_sidebar') ?>
        
        <main class="dashboard-main">
            <!-- Flash Messages -->
            <?= $this->include('partials/flash_messages') ?>
            
            <!-- Overview Section -->
            <?= $this->include('user/dashboard/sections/overview') ?>
        </main>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
<?= $this->endSection() ?>

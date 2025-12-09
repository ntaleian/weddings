<?= $this->extend('layouts/user/base') ?>

<?= $this->section('styles') ?>
    <link href="<?= base_url('assets/css/dashboard.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/dashboard-components.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/dashboard-sections.css') ?>" rel="stylesheet">
    <style>
        /* Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--white);
            padding: 40px;
            border-radius: 16px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .welcome-content h1 {
            font-size: 2rem;
            margin-bottom: 10px;
            font-family: 'Playfair Display', serif;
        }

        .welcome-content p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .welcome-actions {
            display: flex;
            gap: 15px;
        }

        /* Progress Overview */
        .progress-overview {
            background: var(--white);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .progress-percentage {
            background: var(--success-color);
            color: var(--white);
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
        }

        .progress-steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .progress-step {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            border-radius: 12px;
            border: 2px solid var(--light-gray);
            transition: all 0.3s ease;
        }

        .progress-step.completed {
            border-color: var(--success-color);
            background: rgba(46, 204, 113, 0.1);
        }

        .progress-step.current {
            border-color: var(--primary-color);
            background: rgba(100, 1, 127, 0.1);
        }

        .step-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            background: var(--light-gray);
            color: var(--text-color);
        }

        .progress-step.completed .step-icon {
            background: var(--success-color);
            color: var(--white);
        }

        .progress-step.current .step-icon {
            background: var(--primary-color);
            color: var(--white);
        }

        .step-content h4 {
            margin: 0 0 5px 0;
            color: var(--text-color);
        }

        .step-content p {
            margin: 0;
            color: var(--gray);
            font-size: 0.9rem;
        }

        .progress-bar {
            height: 8px;
            background: var(--light-gray);
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--success-color));
            transition: width 0.3s ease;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .action-card {
            background: var(--white);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .action-card:hover {
            transform: translateY(-5px);
        }

        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .card-icon i {
            font-size: 1.5rem;
            color: var(--white);
        }

        .card-content h3 {
            margin: 0 0 10px 0;
            color: var(--text-color);
        }

        .card-content p {
            margin: 0 0 20px 0;
            color: var(--gray);
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

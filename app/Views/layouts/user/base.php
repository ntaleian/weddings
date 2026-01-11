<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wedding Dashboard - Watoto Church Wedding Booking</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url('css/style.css') ?>" rel="stylesheet">
    <?= $this->renderSection('styles') ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif !important;
        }
        /* Enhanced Dashboard Styles */
        :root {
            --primary-color: #64017f;
            --secondary-color: #8e44ad;
            --accent-color: #f39c12;
            --success-color: #2ecc71;
            --error-color: #e74c3c;
            --warning-color: #f1c40f;
            --info-color: #3498db;
            --white: #ffffff;
            --light-gray: #ecf0f1;
            --gray: #95a5a6;
            --text-color: #2c3e50;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        /* Navigation */
        .dashboard-nav {
            background: var(--white);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            z-index: 1000;
            width: 100%;
        }

        .dashboard-nav .container {
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-brand a {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--primary-color);
        }

        .logo {
            height: 40px;
        }

        .brand-text {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.9rem;
        }

        .user-email {
            font-size: 0.8rem;
            color: var(--gray);
        }

        .dropdown-toggle {
            background: none;
            border: none;
            color: var(--gray);
            cursor: pointer;
            padding: 5px;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--white);
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            display: none;
            z-index: 1000;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: var(--text-color);
            text-decoration: none;
        }

        .dropdown-menu a:hover {
            background: var(--light-gray);
        }

        /* Dashboard Container */
        .dashboard-container {
            display: flex;
            margin-top: 70px;
            min-height: calc(100vh - 70px);
        }

        /* Sidebar */
        .dashboard-sidebar {
            width: 280px;
            background: var(--white);
            border-right: 1px solid var(--light-gray);
            padding: 30px 0;
            position: fixed;
            height: calc(100vh - 70px);
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 0 30px 30px;
            border-bottom: 1px solid var(--light-gray);
            margin-bottom: 30px;
        }

        .sidebar-header h3 {
            color: var(--primary-color);
            font-size: 1.2rem;
            margin: 0;
            font-family: 'Playfair Display', serif;
        }

        .sidebar-nav {
            padding: 0 15px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            color: var(--text-color);
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 8px;
            transition: all 0.2s ease;
            position: relative;
        }

        .nav-item:hover {
            background: var(--light-gray);
            color: var(--primary-color);
        }

        .nav-item.active {
            background: var(--primary-color);
            color: var(--white);
        }

        .nav-badge {
            background: var(--accent-color);
            color: var(--white);
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: auto;
        }

        /* Main Content */
        .dashboard-main {
            flex: 1;
            margin-left: 280px;
            padding: 30px;
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        /* Buttons */
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: var(--gray);
            color: var(--white);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline:hover {
            background: var(--primary-color);
            color: var(--white);
        }
        
        /* Sidebar Toggle Button */
        .sidebar-toggle-btn {
            display: none;
            background: none;
            border: none;
            color: var(--text-color);
            font-size: 1.3rem;
            cursor: pointer;
            padding: 8px;
            margin-right: 12px;
            z-index: 1001;
        }
        
        /* Sidebar Overlay for Mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
            opacity: 0;
            visibility: hidden;
        }
        
        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        
        /* Mobile Responsive Styles */
        @media (max-width: 968px) {
            .sidebar-toggle-btn {
                display: block;
            }
            
            .dashboard-sidebar {
                transform: translateX(-100%);
                z-index: 999;
                box-shadow: 2px 0 20px rgba(0, 0, 0, 0.1);
            }
            
            .dashboard-sidebar.show {
                transform: translateX(0);
            }
            
            .sidebar-overlay {
                display: block;
            }
            
            .dashboard-main {
                margin-left: 0;
                padding: 20px;
            }
            
            .user-info {
                display: none;
            }
        }
        
        @media (max-width: 768px) {
            .dashboard-nav .container {
                padding: 10px 16px;
            }
            
            .nav-brand {
                gap: 8px;
            }
            
            .logo {
                height: 36px;
            }
            
            .user-avatar {
                width: 36px;
                height: 36px;
            }
            
            .dashboard-main {
                padding: 16px;
            }
            
            .dropdown-menu {
                right: 16px;
                min-width: 180px;
            }
        }
        
        @media (max-width: 480px) {
            .dashboard-main {
                padding: 12px;
            }
            
            .sidebar-header {
                padding: 0 20px 20px;
            }
            
            .sidebar-nav {
                padding: 0 10px;
            }
            
            .nav-item {
                padding: 10px 12px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <?= $this->renderSection('content') ?>
    
    <script>
        // Sidebar Toggle for Mobile
        function initSidebarToggle() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.dashboard-sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            if (!sidebarToggle || !sidebar || !sidebarOverlay) return;
            
            function toggleSidebar() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
                if (sidebar.classList.contains('show')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            }
            
            function closeSidebar() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }
            
            sidebarToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleSidebar();
            });
            
            sidebarOverlay.addEventListener('click', closeSidebar);
            
            // Close sidebar when clicking on a nav link (mobile only)
            const navLinks = sidebar.querySelectorAll('.nav-item');
            navLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 968) {
                        closeSidebar();
                    }
                });
            });
            
            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar && sidebar.classList.contains('show')) {
                    closeSidebar();
                }
            });
        }
        
        // Initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSidebarToggle);
        } else {
            initSidebarToggle();
        }
    </script>
    <script src="<?= base_url('js/script.js') ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>

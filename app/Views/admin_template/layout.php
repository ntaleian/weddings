<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?> - Watoto Church</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    
    <!-- Custom Admin Styles -->
    <link rel="stylesheet" href="<?= base_url('css/admin-template.css') ?>">
    
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-brand">
                <div class="brand-logo">
                    <img src="<?= base_url('images/watoto_logo.png') ?>" alt="Watoto Church">
                </div>
                <div class="brand-text">
                    <h1>Wedding Admin</h1>
                    <span>Management Portal</span>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <li class="nav-item">
                        <a href="<?= site_url('admin/dashboard') ?>" class="nav-link <?= (uri_string() == 'admin/dashboard' || uri_string() == 'admin') ? 'active' : '' ?>">
                            <i class="fas fa-chart-line"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('admin/bookings') ?>" class="nav-link <?= (strpos(uri_string(), 'admin/booking') !== false) ? 'active' : '' ?>">
                            <i class="fas fa-calendar-check"></i>
                            <span>Bookings</span>
                            <span class="badge"><?= $pendingCount ?? 0 ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('admin/calendar') ?>" class="nav-link <?= (strpos(uri_string(), 'admin/calendar') !== false) ? 'active' : '' ?>">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Calendar</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('admin/campuses') ?>" class="nav-link <?= (strpos(uri_string(), 'admin/campus') !== false) ? 'active' : '' ?>">
                            <i class="fas fa-building"></i>
                            <span>Campuses</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('admin/pastors') ?>" class="nav-link <?= (strpos(uri_string(), 'admin/pastor') !== false) ? 'active' : '' ?>">
                            <i class="fas fa-user-tie"></i>
                            <span>Pastors</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('admin/users') ?>" class="nav-link <?= (strpos(uri_string(), 'admin/user') !== false) ? 'active' : '' ?>">
                            <i class="fas fa-users"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('admin/blocked-dates') ?>" class="nav-link <?= (strpos(uri_string(), 'admin/blocked') !== false) ? 'active' : '' ?>">
                            <i class="fas fa-ban"></i>
                            <span>Blocked Dates</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('admin/reports') ?>" class="nav-link <?= (strpos(uri_string(), 'admin/report') !== false) ? 'active' : '' ?>">
                            <i class="fas fa-chart-bar"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('admin/settings') ?>" class="nav-link <?= (strpos(uri_string(), 'admin/setting') !== false) ? 'active' : '' ?>">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <a href="<?= site_url('logout') ?>" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
        
        <!-- Main Content Area -->
        <div class="admin-main">
            <!-- Top Header -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="page-breadcrumb">
                        <span class="breadcrumb-item">Admin</span>
                        <i class="fas fa-chevron-right"></i>
                        <span class="breadcrumb-item current"><?= $pageTitle ?? 'Dashboard' ?></span>
                    </div>
                </div>
                
                <div class="header-right">
                    <div class="header-search">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search...">
                    </div>
                    
                    <div class="header-notifications">
                        <button class="notification-btn">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">3</span>
                        </button>
                    </div>
                    
                    <div class="header-profile">
                        <div class="profile-avatar">
                            <img src="<?= base_url('images/user.png') ?>" alt="Admin">
                        </div>
                        <div class="profile-info">
                            <span class="profile-name"><?= esc(session()->get('user_name') ?? 'Admin') ?></span>
                            <span class="profile-role">Administrator</span>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="admin-content">
                <!-- Flash Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('warning')): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?= session()->getFlashdata('warning') ?>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('info')): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <?= session()->getFlashdata('info') ?>
                    </div>
                <?php endif; ?>
                
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
    
    <script src="<?= base_url('js/admin-template.js') ?>"></script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>


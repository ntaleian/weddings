<!-- Dashboard Header -->
<header class="dashboard-header">
    <div class="header-left">
        <button class="sidebar-toggle">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="page-title"><?= $pageTitle ?? 'Dashboard' ?></h1>
    </div>
    
    <div class="header-right">
        <div class="header-actions">
            <button class="btn btn-primary btn-sm" onclick="window.location.href='<?= base_url('dashboard/new-booking') ?>'">
                <i class="fas fa-plus"></i>
                New Booking
            </button>
        </div>
        
        <div class="notifications">
            <button class="notification-toggle" id="notificationToggle">
                <i class="fas fa-bell"></i>
                <span class="notification-count">0</span>
            </button>
            <div class="notification-dropdown" id="notificationDropdown">
                <div class="notification-header">
                    <h4>Notifications</h4>
                    <button class="mark-all-read">Mark all as read</button>
                </div>
                <div class="notification-list" id="notificationList">
                    <!-- Notifications will be loaded here -->
                </div>
            </div>
        </div>
        
        <div class="user-menu">
            <button class="user-menu-toggle" id="userMenuToggle">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <span class="user-name"><?= session()->get('first_name') ?></span>
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="user-dropdown" id="userDropdown">
                <a href="<?= base_url('dashboard/profile') ?>" class="dropdown-item">
                    <i class="fas fa-user"></i>
                    Profile
                </a>
                <a href="<?= base_url('/') ?>" class="dropdown-item">
                    <i class="fas fa-home"></i>
                    Home
                </a>
                <div class="dropdown-divider"></div>
                <a href="<?= base_url('logout') ?>" class="dropdown-item">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </div>
        </div>
    </div>
</header>

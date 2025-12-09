<!-- Admin Sidebar -->
<div class="sidebar admin-sidebar">
    <div class="sidebar-header">
        <!-- <div class="logo"> -->
            <img src="<?= base_url('images/watoto_logo.png') ?>" alt="Watoto Church" class="logo">
            <h3>Admin Panel</h3>
        <!-- </div> -->
    </div>
    
    <nav class="sidebar-nav">
        <ul class="nav-menu">
            <li class="nav-item <?= (uri_string() == 'admin' || uri_string() == 'admin/dashboard') ? 'active' : '' ?>">
                <a href="<?= site_url('admin/dashboard') ?>" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item <?= (uri_string() == 'admin/bookings') ? 'active' : '' ?>">
                <a href="<?= site_url('admin/bookings') ?>" class="nav-link">
                    <i class="fas fa-calendar-alt"></i>
                    Bookings
                </a>
            </li>
            <li class="nav-item <?= (uri_string() == 'admin/venues') ? 'active' : '' ?>">
                <a href="<?= site_url('admin/campuses') ?>" class="nav-link">
                    <i class="fas fa-church"></i>
                    Manage Campuses
                </a>
            </li>
            <li class="nav-item <?= (uri_string() == 'admin/blocked-dates') ? 'active' : '' ?>">
                <a href="<?= site_url('admin/blocked-dates') ?>" class="nav-link">
                    <i class="fas fa-ban"></i>
                    Blocked Dates
                </a>
            </li>
            <li class="nav-item <?= (strpos(uri_string(), 'admin/pastor') !== false) ? 'active' : '' ?>">
                <a href="<?= site_url('admin/pastors') ?>" class="nav-link">
                    <i class="fas fa-user-tie"></i>
                    Manage Pastors
                </a>
            </li>
            <li class="nav-item <?= (uri_string() == 'admin/users') ? 'active' : '' ?>">
                <a href="<?= site_url('admin/users') ?>" class="nav-link">
                    <i class="fas fa-users"></i>
                    Users
                </a>
            </li>
            <li class="nav-item <?= (uri_string() == 'admin/reports') ? 'active' : '' ?>">
                <a href="<?= site_url('admin/reports') ?>" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    Reports
                </a>
            </li>
            <li class="nav-item <?= (uri_string() == 'admin/settings') ? 'active' : '' ?>">
                <a href="<?= site_url('admin/settings') ?>" class="nav-link">
                    <i class="fas fa-cog"></i>
                    Settings
                </a>
            </li>
        </ul>
    </nav>
    
    <div class="sidebar-footer">
        <a href="<?= base_url('logout') ?>" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </a>
    </div>
</div>

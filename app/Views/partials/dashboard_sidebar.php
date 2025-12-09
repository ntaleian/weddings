<!-- Dashboard Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <img src="<?= base_url('images/watoto_logo.png') ?>" alt="Watoto Church">
            <span>Wedding Dashboard</span>
        </div>
    </div>
    
    <div class="sidebar-menu">
        <ul class="menu-list">
            <li class="menu-item">
                <a href="<?= base_url('dashboard') ?>" class="menu-link <?= uri_string() === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="<?= base_url('dashboard/bookings') ?>" class="menu-link <?= uri_string() === 'dashboard/bookings' ? 'active' : '' ?>">
                    <i class="fas fa-calendar-check"></i>
                    <span>My Bookings</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="<?= base_url('dashboard/new-booking') ?>" class="menu-link <?= uri_string() === 'dashboard/new-booking' ? 'active' : '' ?>">
                    <i class="fas fa-plus-circle"></i>
                    <span>New Booking</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="<?= base_url('dashboard/profile') ?>" class="menu-link <?= uri_string() === 'dashboard/profile' ? 'active' : '' ?>">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
            </li>
        </ul>
    </div>
    
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="user-details">
                <span class="user-name"><?= session()->get('first_name') ?> <?= session()->get('last_name') ?></span>
                <span class="user-email"><?= session()->get('email') ?></span>
            </div>
        </div>
        <a href="<?= base_url('logout') ?>" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</div>

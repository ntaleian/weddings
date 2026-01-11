<!-- Navigation -->
    <nav class="navbar dashboard-nav">
        <div class="container">
            <div class="nav-brand">
                <button class="sidebar-toggle-btn" id="sidebarToggle" aria-label="Toggle sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="<?= site_url('dashboard') ?>">
                    <img src="<?= base_url('images/watoto_logo.png') ?>" alt="Watoto Church" class="logo">
                    <!-- <span class="brand-text">Wedding Booking</span> -->
                </a>
            </div>
            <div class="nav-menu">
                <div class="user-menu">
                    <div class="user-avatar">
                        <img src="<?= base_url('images/user.png') ?>" alt="User Avatar">
                    </div>
                    <div class="user-info">
                        <span class="user-name"><?= esc(session()->get('user_name') ?? 'John & Sarah') ?></span>
                        <span class="user-email"><?= esc(session()->get('user_email') ?? 'john.sarah@email.com') ?></span>
                    </div>
                    <div class="user-dropdown">
                        <button class="dropdown-toggle" onclick="toggleDropdown()">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu" id="userDropdown">
                            <a href="<?= site_url('dashboard/profile') ?>"><i class="fas fa-user"></i> Profile</a>
                            <a href="<?= site_url('dashboard/settings') ?>"><i class="fas fa-cog"></i> Settings</a>
                            <a href="<?= site_url('help') ?>"><i class="fas fa-question-circle"></i> Help</a>
                            <hr>
                            <a href="<?= site_url('logout') ?>" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

<script>
function toggleDropdown() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('show');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('userDropdown');
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    
    if (!dropdownToggle.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});
</script>
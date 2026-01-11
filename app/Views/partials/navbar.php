<!-- Modern Navigation -->
<nav class="main-navbar">
    <div class="main-navbar-container">
        <a href="<?= base_url('/') ?>" class="main-nav-logo">
            <img src="<?= base_url('images/watoto_logo.png') ?>" alt="Watoto Church" class="main-logo-img">
        </a>
        
        <div class="main-nav-actions">
            <a href="<?= base_url('/') ?>" class="main-nav-link">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="<?= base_url('/#contact') ?>" class="main-nav-link">
                <i class="fas fa-envelope"></i>
                <span>Contact</span>
            </a>
            <?php if (session()->get('is_logged_in')): ?>
                <a href="<?= base_url('dashboard') ?>" class="main-nav-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= base_url('logout') ?>" class="main-nav-btn main-btn-secondary">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            <?php else: ?>
                <a href="<?= base_url('login') ?>" class="main-nav-btn main-btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Login</span>
                </a>
                <a href="<?= base_url('admin/login') ?>" class="main-nav-icon" title="Admin Login" target="_blank">
                    <i class="fas fa-user-shield"></i>
                </a>
            <?php endif; ?>
        </div>
        
        <!-- Hamburger Menu Button (Mobile Only) -->
        <button class="main-hamburger" id="main-hamburger" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
    
    <!-- Mobile Menu Overlay -->
    <div class="main-mobile-menu" id="main-mobile-menu">
        <div class="main-mobile-menu-content">
            <a href="<?= base_url('/') ?>" class="main-mobile-link">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="<?= base_url('/#contact') ?>" class="main-mobile-link">
                <i class="fas fa-envelope"></i>
                <span>Contact</span>
            </a>
            <?php if (session()->get('is_logged_in')): ?>
                <a href="<?= base_url('dashboard') ?>" class="main-mobile-link">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= base_url('logout') ?>" class="main-mobile-link main-mobile-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            <?php else: ?>
                <a href="<?= base_url('login') ?>" class="main-mobile-link main-mobile-btn main-mobile-btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Login</span>
                </a>
                <a href="<?= base_url('admin/login') ?>" class="main-mobile-link" title="Admin Login" target="_blank">
                    <i class="fas fa-user-shield"></i>
                    <span>Admin Login</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

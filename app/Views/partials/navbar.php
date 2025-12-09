<!-- Navigation -->
<nav class="navbar">
    <div class="container">
        <div class="nav-brand">
            <a href="<?= base_url('/') ?>">
                <img src="<?= base_url('images/watoto_logo.png') ?>" alt="Watoto Church" class="logo">
                <!-- <span class="brand-text">Family Office</span> -->
            </a>
        </div>
        <div class="nav-menu">
            <a href="<?= base_url('/') ?>" class="nav-link">Home</a>
            <a href="<?= base_url('/#venues') ?>" class="nav-link">Campuses</a>
            <a href="<?= base_url('/#about') ?>" class="nav-link">About</a>
            <a href="<?= base_url('/#contact') ?>" class="nav-link">Contact</a>
            <?php if (session()->get('is_logged_in')): ?>
                <a href="<?= base_url('dashboard') ?>" class="nav-link">Dashboard</a>
                <a href="<?= base_url('logout') ?>" class="nav-link">Logout</a>
            <?php else: ?>
                <a href="<?= base_url('login') ?>" class="nav-link login-btn">Login</a>
                <a href="<?= base_url('admin/login') ?>" class="nav-link admin-nav-link" title="Admin Login" target="_blank" >
                    <i class="fas fa-user-shield"></i>
                </a>
            <?php endif; ?>
        </div>
        <div class="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</nav>

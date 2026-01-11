<!-- Modern Auth Navigation -->
<nav class="auth-navbar">
    <div class="auth-navbar-container">
        <a href="<?= base_url('/') ?>" class="auth-nav-logo">
            <img src="<?= base_url('images/watoto_logo.png') ?>" alt="Watoto Church" class="auth-logo-img">
        </a>
        
        <div class="auth-nav-actions">
            <a href="<?= base_url('/') ?>" class="auth-nav-link">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <?php if (current_url() === base_url('login')): ?>
                <a href="<?= base_url('register') ?>" class="auth-nav-btn auth-btn-primary">
                    <i class="fas fa-user-plus"></i>
                    <span>Register</span>
                </a>
            <?php else: ?>
                <a href="<?= base_url('login') ?>" class="auth-nav-btn auth-btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Login</span>
                </a>
            <?php endif; ?>
            <a href="<?= base_url('admin/login') ?>" class="auth-nav-icon" title="Admin Login" target="_blank">
                <i class="fas fa-user-shield"></i>
            </a>
        </div>
    </div>
</nav>

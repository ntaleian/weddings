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
            <?php if (current_url() === base_url('login')): ?>
                <a href="<?= base_url('register') ?>" class="nav-link">Register</a>
            <?php else: ?>
                <a href="<?= base_url('login') ?>" class="nav-link">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?php
/**
 * Admin Header - Top navigation bar
 * This is the main header that appears at the top of all admin pages
 */
$pageTitle = $pageTitle ?? 'Dashboard';
?>
<!-- Admin Header -->
<header class="main-header">
    <div class="header-left">
        <button class="sidebar-toggle">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="page-title"><?= esc($pageTitle) ?></h1>
    </div>
    <div class="header-right">
        <!-- <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search...">
        </div> -->
        <div class="admin-profile">
            <img src="<?= base_url('images/user.png') ?>" alt="Admin" class="profile-avatar">
            <span class="admin-name"><?= esc(session()->get('user_name') ?? 'Admin') ?></span>
        </div>
    </div>
</header>

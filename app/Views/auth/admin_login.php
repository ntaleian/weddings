<?= $this->extend('layouts/admin_login') ?>

<?= $this->section('title') ?>
<?= $title ?? 'Admin Login' ?>
<?= $this->endSection() ?>

<?= $this->section('main_content') ?>
    <form class="login-form" method="post" action="<?= base_url('admin/login') ?>" autocomplete="off">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label for="email">Email Address</label>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="email" id="email" name="email" value="<?= old('email') ?>" required autocomplete="off">
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" required autocomplete="new-password">
                <button type="button" class="toggle-password" onclick="togglePassword()">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" id="rememberMe">
                <span class="checkmark"></span>
                Remember me
            </label>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-sign-in-alt"></i>
            Login to Admin Panel
        </button>
    </form>

    <div class="login-footer">
        <p><a href="<?= base_url('login') ?>">User Login</a> | <a href="<?= base_url('/') ?>">Back to Website</a></p>
    </div>
<?= $this->endSection() ?>

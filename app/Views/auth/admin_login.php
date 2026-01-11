<?= $this->extend('layouts/admin_login') ?>

<?= $this->section('title') ?>
<?= $title ?? 'Admin Login' ?>
<?= $this->endSection() ?>

<?= $this->section('main_content') ?>
    <div class="admin-login-content">
        <div class="admin-login-header">
            <div class="admin-login-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h1>Admin Portal</h1>
            <p class="admin-login-subtitle">Wedding Booking System</p>
        </div>

        <!-- Flash Messages -->
        <?= $this->include('partials/flash_messages') ?>

        <form class="admin-login-form" method="post" action="<?= base_url('admin/login') ?>" autocomplete="off">
            <?= csrf_field() ?>
            
            <div class="form-field">
                <label for="email" class="field-label">Email Address</label>
                <div class="input-wrapper">
                    <i class="input-icon fas fa-envelope"></i>
                    <input type="email" id="email" name="email" value="<?= old('email') ?>" required autocomplete="off" placeholder="admin@example.com">
                </div>
            </div>

            <div class="form-field">
                <label for="password" class="field-label">Password</label>
                <div class="input-wrapper">
                    <i class="input-icon fas fa-lock"></i>
                    <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="••••••••">
                    <button type="button" class="password-toggle-btn" onclick="togglePassword()">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-field checkbox-field">
                <label class="custom-checkbox">
                    <input type="checkbox" id="rememberMe" name="remember">
                    <span class="checkbox-indicator"></span>
                    <span class="checkbox-text">Remember me</span>
                </label>
            </div>

            <button type="submit" class="submit-btn">
                <i class="fas fa-sign-in-alt"></i>
                <span>Login to Admin Panel</span>
            </button>
        </form>

        <div class="admin-login-footer">
            <p><a href="<?= base_url('login') ?>">User Login</a> | <a href="<?= base_url('/') ?>">Back to Website</a></p>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const toggleBtn = document.querySelector('.password-toggle-btn');
            const icon = toggleBtn.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
<?= $this->endSection() ?>

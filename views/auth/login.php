<?php require '../views/layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <h2>Login to Quiz System</h2>
        
        <?php Session::flash('register_success'); ?>
        <?php Session::flash('auth_error'); ?>

        <form action="<?php echo URLROOT; ?>/auth/login" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>" autofocus>
                <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </div>

            <div class="text-center mt-3">
                <a href="<?php echo URLROOT; ?>/auth/register">Don't have an account? Register</a>
            </div>
        </form>
    </div>
</div>

<?php require '../views/layouts/footer.php'; ?>

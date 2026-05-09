<?php require '../views/layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <h2>Register an Account</h2>
        
        <form action="<?php echo URLROOT; ?>/auth/register" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
            
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($data['username_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['username']; ?>" autofocus>
                <span class="invalid-feedback"><?php echo $data['username_err']; ?></span>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
                <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
                <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password']; ?>">
                <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </div>

            <div class="text-center mt-3">
                <a href="<?php echo URLROOT; ?>/auth/login">Already have an account? Login</a>
            </div>
        </form>
    </div>
</div>

<?php require '../views/layouts/footer.php'; ?>

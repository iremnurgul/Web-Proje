<?php require '../views/layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Yeni Şifre Belirleme</h2>
            <p>Lütfen yeni şifrenizi girin.</p>
        </div>
        
        <form action="<?php echo URLROOT; ?>/auth/reset_password" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
            <input type="hidden" name="token" value="<?php echo isset($data['token']) ? $data['token'] : ''; ?>">
            
            <div class="form-group">
                <label for="password">Yeni Şifre</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" required autofocus>
                <span class="invalid-feedback"><?php echo isset($data['password_err']) ? $data['password_err'] : ''; ?></span>
            </div>

            <div class="form-group">
                <label for="confirm_password">Yeni Şifre (Tekrar)</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" required>
                <span class="invalid-feedback"><?php echo isset($data['confirm_password_err']) ? $data['confirm_password_err'] : ''; ?></span>
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary btn-block">Şifreyi Kaydet</button>
            </div>
        </form>
    </div>
</div>

<?php require '../views/layouts/footer.php'; ?>

<?php require '../views/layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>QUİZBOX'e Giriş</h2>
            <p>Eğitim platformuna hoş geldiniz.</p>
        </div>
        
        <?php Session::flash('register_success'); ?>
        <?php Session::flash('register_error'); ?>
        <?php Session::flash('auth_error'); ?>

        <form action="<?php echo URLROOT; ?>/auth/login" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
            
            <div class="form-group">
                <label for="user_number">Öğrenci / Öğretmen Numarası</label>
                <input type="number" inputmode="numeric" pattern="[0-9]*" name="user_number" class="form-control <?php echo (!empty($data['user_number_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($data['user_number']) ? $data['user_number'] : ''; ?>" placeholder="Örn: 1001" autofocus>
                <span class="invalid-feedback"><?php echo isset($data['user_number_err']) ? $data['user_number_err'] : ''; ?></span>
            </div>

            <div class="form-group">
                <label for="password">Şifre</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo isset($data['password_err']) ? $data['password_err'] : ''; ?></span>
            </div>

            <div class="form-group forgot-password-link">
                <a href="<?php echo URLROOT; ?>/auth/forgot_password">Şifremi Unuttum</a>
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary btn-block">Giriş Yap</button>
            </div>

            <div class="text-center mt-3 auth-footer">
                <p>Hesabınız yok mu? <a href="<?php echo URLROOT; ?>/auth/register">Kayıt Ol</a></p>
            </div>
        </form>
    </div>
</div>

<?php require '../views/layouts/footer.php'; ?>

<?php require '../views/layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Şifremi Unuttum</h2>
            <p>Sisteme kayıtlı e-posta adresinizi girin.</p>
        </div>
        
        <?php Session::flash('register_error'); ?>

        <form action="<?php echo URLROOT; ?>/auth/forgot_password" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
            
            <div class="form-group">
                <label for="email">E-posta</label>
                <input type="email" name="email" class="form-control" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>" required autofocus>
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary btn-block">Sıfırlama Linki Gönder</button>
            </div>

            <div class="text-center mt-3 auth-footer">
                <a href="<?php echo URLROOT; ?>/auth/login">Giriş Sayfasına Dön</a>
            </div>
        </form>
    </div>
</div>

<?php require '../views/layouts/footer.php'; ?>

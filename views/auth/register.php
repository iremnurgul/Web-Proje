<?php require '../views/layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-card register-card">
        <div class="auth-header">
            <h2>Öğrenci Kaydı</h2>
            <p>QUİZBOX'e katılın.</p>
        </div>
        
        <form action="<?php echo URLROOT; ?>/auth/register" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
            
            <div class="form-row">
                <div class="form-group col">
                    <label for="first_name">Ad</label>
                    <input type="text" name="first_name" class="form-control <?php echo (!empty($data['first_name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($data['first_name']) ? $data['first_name'] : ''; ?>" autofocus>
                    <span class="invalid-feedback"><?php echo isset($data['first_name_err']) ? $data['first_name_err'] : ''; ?></span>
                </div>
                <div class="form-group col">
                    <label for="last_name">Soyad</label>
                    <input type="text" name="last_name" class="form-control <?php echo (!empty($data['last_name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($data['last_name']) ? $data['last_name'] : ''; ?>">
                    <span class="invalid-feedback"><?php echo isset($data['last_name_err']) ? $data['last_name_err'] : ''; ?></span>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col">
                    <label for="user_number">Öğrenci Numarası</label>
                    <input type="number" inputmode="numeric" pattern="[0-9]*" name="user_number" class="form-control <?php echo (!empty($data['user_number_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($data['user_number']) ? $data['user_number'] : ''; ?>" placeholder="Örn: 1001">
                    <span class="invalid-feedback"><?php echo isset($data['user_number_err']) ? $data['user_number_err'] : ''; ?></span>
                </div>
                <div class="form-group col">
                    <label for="username">Kullanıcı Adı</label>
                    <input type="text" name="username" class="form-control <?php echo (!empty($data['username_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($data['username']) ? $data['username'] : ''; ?>">
                    <span class="invalid-feedback"><?php echo isset($data['username_err']) ? $data['username_err'] : ''; ?></span>
                </div>
            </div>

            <div class="form-group">
                <label for="email">E-posta</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>">
                <span class="invalid-feedback"><?php echo isset($data['email_err']) ? $data['email_err'] : ''; ?></span>
            </div>

            <div class="form-row">
                <div class="form-group col">
                    <label for="password">Şifre</label>
                    <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($data['password']) ? $data['password'] : ''; ?>">
                    <span class="invalid-feedback"><?php echo isset($data['password_err']) ? $data['password_err'] : ''; ?></span>
                </div>
                <div class="form-group col">
                    <label for="confirm_password">Şifre Tekrar</label>
                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo isset($data['confirm_password']) ? $data['confirm_password'] : ''; ?>">
                    <span class="invalid-feedback"><?php echo isset($data['confirm_password_err']) ? $data['confirm_password_err'] : ''; ?></span>
                </div>
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary btn-block">Kayıt Ol</button>
            </div>

            <div class="text-center mt-3 auth-footer">
                <p>Zaten hesabınız var mı? <a href="<?php echo URLROOT; ?>/auth/login">Giriş Yap</a></p>
            </div>
        </form>
    </div>
</div>

<?php require '../views/layouts/footer.php'; ?>

<?php require '../views/layouts/header.php'; ?>
<?php require '../views/layouts/navbar.php'; ?>

<div class="ls-dashboard-container">
    <?php require '../views/layouts/sidebar.php'; ?>

    <main class="ls-main-content">
        <div class="ls-header-section">
            <h2>Profilim</h2>
            <p>Kişisel bilgilerinizi buradan güncelleyebilirsiniz.</p>
        </div>

        <?php Session::flash('profile_success'); ?>

        <div class="ls-card profile-card">
            <?php if(!empty($data['error'])): ?>
                <div class="alert alert-danger"><?php echo $data['error']; ?></div>
            <?php endif; ?>

            <form action="<?php echo URLROOT; ?>/student/profile" method="POST">
                <div class="form-row">
                    <div class="form-group col">
                        <label>Öğrenci Numarası</label>
                        <input type="text" class="form-control" value="<?php echo $data['user']->user_number; ?>" disabled>
                    </div>
                    <div class="form-group col">
                        <label>Kullanıcı Adı</label>
                        <input type="text" class="form-control" value="<?php echo $data['user']->username; ?>" disabled>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col">
                        <label for="first_name">Ad</label>
                        <input type="text" name="first_name" class="form-control" value="<?php echo $data['user']->first_name; ?>" required>
                    </div>
                    <div class="form-group col">
                        <label for="last_name">Soyad</label>
                        <input type="text" name="last_name" class="form-control" value="<?php echo $data['user']->last_name; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">E-posta</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $data['user']->email; ?>" required>
                </div>

                <hr>
                <h5>Şifre Değiştir (İsteğe Bağlı)</h5>
                <p class="text-muted small">Şifrenizi değiştirmek istemiyorsanız boş bırakın.</p>

                <div class="form-row">
                    <div class="form-group col">
                        <label for="password">Yeni Şifre</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="form-group col">
                        <label for="confirm_password">Yeni Şifre Tekrar</label>
                        <input type="password" name="confirm_password" class="form-control">
                    </div>
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Değişiklikleri Kaydet</button>
                </div>
            </form>
        </div>
    </main>
</div>

<?php require '../views/layouts/footer.php'; ?>

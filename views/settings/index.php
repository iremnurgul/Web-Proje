<?php require '../views/layouts/header.php'; ?>
<?php require '../views/layouts/navbar.php'; ?>

<div class="ls-dashboard-container">
    <?php require '../views/layouts/sidebar.php'; ?>

    <main class="ls-main-content">
        <div class="ls-header-section">
            <h2><?php echo Language::get('settings'); ?></h2>
        </div>

        <?php Session::flash('settings_success'); ?>

        <div class="ls-card profile-card">
            <form action="<?php echo URLROOT; ?>/settings/index" method="POST">
                
                <div class="form-group">
                    <label>Dil / Language</label>
                    <select name="language" class="form-control">
                        <option value="tr" <?php echo ($data['current_lang'] == 'tr') ? 'selected' : ''; ?>>Türkçe</option>
                        <option value="en" <?php echo ($data['current_lang'] == 'en') ? 'selected' : ''; ?>>English</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tema / Theme</label>
                    <select name="theme" class="form-control">
                        <option value="dark" <?php echo ($data['current_theme'] == 'dark') ? 'selected' : ''; ?>>Koyu / Dark</option>
                        <option value="light" <?php echo ($data['current_theme'] == 'light') ? 'selected' : ''; ?>>Açık / Light</option>
                    </select>
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Kaydet / Save</button>
                </div>
            </form>
        </div>
    </main>
</div>

<?php require '../views/layouts/footer.php'; ?>

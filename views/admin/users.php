<?php require '../views/layouts/header.php'; ?>
<?php require '../views/layouts/navbar.php'; ?>

<div class="ls-dashboard-container">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <main class="ls-main-content">
        <div class="ls-header-section">
            <h2>Kullanıcı Yönetimi</h2>
            <p>Sistemdeki tüm kullanıcıları yönetin.</p>
        </div>

        <div class="ls-card">
            <table>
                <thead>
                    <tr>
                        <th>Numara</th>
                        <th>Kullanıcı Adı</th>
                        <th>Ad Soyad</th>
                        <th>E-posta</th>
                        <th>Rol</th>
                        <th>İşlem</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['users'] as $user): ?>
                    <tr>
                        <td><strong><?php echo $user->user_number; ?></strong></td>
                        <td><?php echo $user->username; ?></td>
                        <td><?php echo $user->first_name . ' ' . $user->last_name; ?></td>
                        <td><?php echo $user->email; ?></td>
                        <td>
                            <?php if($user->role == 'admin'): ?>
                                <span class="badge badge-danger">Admin</span>
                            <?php elseif($user->role == 'teacher'): ?>
                                <span class="badge badge-success">Öğretmen</span>
                            <?php else: ?>
                                <span class="badge" style="background: rgba(14, 165, 233, 0.15); color: var(--accent); border: 1px solid rgba(14, 165, 233, 0.3);">Öğrenci</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-primary" style="padding: 6px 12px; font-size: 0.85rem;">Düzenle</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php require '../views/layouts/footer.php'; ?>

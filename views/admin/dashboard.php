<?php require '../views/layouts/header.php'; ?>

<div class="ls-dashboard-container">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <main class="ls-main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="ls-header-section">
            <h2><?php echo Language::get('dashboard'); ?></h2>
            <p>Sistem genelindeki istatistikleri ve son etkinlikleri görüntüleyin.</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div class="ls-card" style="margin-bottom: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                <div style="font-size: 2.5rem; color: var(--primary); margin-bottom: 10px;"><i class="fa-solid fa-users"></i></div>
                <h3 style="margin-bottom: 5px; font-size: 1.1rem; color: var(--text-muted);">Öğrenci Sayısı</h3>
                <div style="font-size: 2rem; font-weight: bold;"><?php echo $data['stats']['students']; ?></div>
            </div>
            <div class="ls-card" style="margin-bottom: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                <div style="font-size: 2.5rem; color: var(--accent); margin-bottom: 10px;"><i class="fa-solid fa-chalkboard-user"></i></div>
                <h3 style="margin-bottom: 5px; font-size: 1.1rem; color: var(--text-muted);">Öğretmen Sayısı</h3>
                <div style="font-size: 2rem; font-weight: bold;"><?php echo $data['stats']['teachers']; ?></div>
            </div>
            <div class="ls-card" style="margin-bottom: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                <div style="font-size: 2.5rem; color: var(--success); margin-bottom: 10px;"><i class="fa-solid fa-book"></i></div>
                <h3 style="margin-bottom: 5px; font-size: 1.1rem; color: var(--text-muted);">Toplam Ders</h3>
                <div style="font-size: 2rem; font-weight: bold;"><?php echo $data['stats']['courses']; ?></div>
            </div>
            <div class="ls-card" style="margin-bottom: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                <div style="font-size: 2.5rem; color: var(--danger); margin-bottom: 10px;"><i class="fa-solid fa-stopwatch"></i></div>
                <h3 style="margin-bottom: 5px; font-size: 1.1rem; color: var(--text-muted);">Toplam Sınav (Quiz)</h3>
                <div style="font-size: 2rem; font-weight: bold;"><?php echo $data['stats']['quizzes']; ?></div>
            </div>
        </div>

        <div class="ls-card">
            <h3><i class="fa-solid fa-user-plus"></i> Son Kayıt Olan Kullanıcılar</h3>
            <div style="overflow-x: auto; margin-top: 15px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--glass-border); text-align: left;">
                            <th style="padding: 12px; color: var(--text-muted);">ID Numarası</th>
                            <th style="padding: 12px; color: var(--text-muted);">Kullanıcı Adı</th>
                            <th style="padding: 12px; color: var(--text-muted);">Ad Soyad</th>
                            <th style="padding: 12px; color: var(--text-muted);">Rol</th>
                            <th style="padding: 12px; color: var(--text-muted);">Kayıt Tarihi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['recent_users'] as $user): ?>
                        <tr style="border-bottom: 1px solid var(--glass-border); transition: background-color 0.2s;">
                            <td style="padding: 12px;"><strong><?php echo htmlspecialchars($user->user_number); ?></strong></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($user->username); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></td>
                            <td style="padding: 12px;">
                                <?php if($user->role == 'admin'): ?>
                                    <span style="background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">Admin</span>
                                <?php elseif($user->role == 'teacher'): ?>
                                    <span style="background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">Öğretmen</span>
                                <?php else: ?>
                                    <span style="background: rgba(14, 165, 233, 0.15); color: var(--accent); border: 1px solid rgba(14, 165, 233, 0.3); padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600;">Öğrenci</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px;" class="text-muted"><?php echo date('d.m.Y H:i', strtotime($user->created_at)); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php require '../views/layouts/footer.php'; ?>

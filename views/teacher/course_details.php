<?php require '../views/layouts/header.php'; ?>

<div class="ls-dashboard-container">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <div class="ls-main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="content-area">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h1 style="margin: 0;">Ders: <?php echo htmlspecialchars($data['course']->course_name); ?></h1>
                <a href="<?php echo URLROOT; ?>/teacher/dashboard" class="btn btn-primary" style="width: auto; background-color: var(--secondary);">Panele Dön</a>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                
                <!-- Kayıtlı Öğrenciler -->
                <div class="card">
                    <div class="card-title" style="color: var(--primary);">Kayıtlı Öğrenciler (<?php echo count($data['students']); ?>)</div>
                    <?php if(empty($data['students'])): ?>
                        <p style="color: var(--text-muted); text-align: center;">Henüz kayıtlı öğrenci yok.</p>
                    <?php else: ?>
                        <div style="max-height: 400px; overflow-y: auto; padding-right: 10px;">
                            <table style="width: 100%; text-align: left; border-collapse: collapse;">
                                <thead>
                                    <tr style="border-bottom: 1px solid var(--border-color);">
                                        <th style="padding: 10px;">Kullanıcı Adı</th>
                                        <th style="padding: 10px;">Kayıt Tarihi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['students'] as $student): ?>
                                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                            <td style="padding: 10px;">
                                                <i class="fa-solid fa-user-graduate" style="color: var(--text-muted); margin-right: 5px;"></i>
                                                <?php echo htmlspecialchars($student->username); ?>
                                            </td>
                                            <td style="padding: 10px; font-size: 0.85rem; color: var(--text-muted);">
                                                <?php echo date('M d, Y', strtotime($student->enrolled_at)); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Quizzes in Course -->
                <div class="card">
                    <div class="card-title" style="color: var(--accent);">Derse Ait Sınavlar (<?php echo count($data['quizzes']); ?>)</div>
                    <?php if(empty($data['quizzes'])): ?>
                        <p style="color: var(--text-muted); text-align: center;">Bu ders için henüz sınav oluşturulmadı.</p>
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            <?php foreach($data['quizzes'] as $quiz): ?>
                                <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); padding: 15px; border-radius: 8px;">
                                    <h4 style="margin-bottom: 5px;"><?php echo htmlspecialchars($quiz->quiz_name); ?></h4>
                                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 10px;">
                                        Süre: <?php echo $quiz->duration; ?> dk | Durum: 
                                        <span style="color: <?php echo $quiz->is_active ? 'var(--success)' : 'var(--danger)'; ?>;">
                                            <?php echo $quiz->is_active ? 'Aktif' : 'Pasif'; ?>
                                        </span>
                                    </p>
                                    <div style="display: flex; gap: 10px;">
                                        <a href="<?php echo URLROOT; ?>/teacher/manageQuestions/<?php echo $quiz->id; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem;">Soruları Yönet</a>
                                        <a href="<?php echo URLROOT; ?>/teacher/quizResults/<?php echo $quiz->id; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem; background-color: var(--success);">Sonuçları Gör</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

        </div>
    </div>
</div>

<?php require '../views/layouts/footer.php'; ?>

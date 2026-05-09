<?php require '../views/layouts/header.php'; ?>

<div class="ls-dashboard-container">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <div class="ls-main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="content-area">
            <h1 style="margin-bottom: 24px;">Sonuçlar ve Gözetmenlik: <?php echo htmlspecialchars($data['quiz']->quiz_name); ?></h1>
            <a href="<?php echo URLROOT; ?>/teacher/quizzes" class="btn btn-primary" style="width: auto; margin-bottom: 20px;">Sınavlara Dön</a>

            <div class="card">
                <table style="width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <th style="padding: 10px;">Öğrenci</th>
                            <th style="padding: 10px;">E-posta</th>
                            <th style="padding: 10px;">Puan</th>
                            <th style="padding: 10px;">Tamamlama Tarihi</th>
                            <th style="padding: 10px;">Gözetmenlik</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['results'])): ?>
                            <tr>
                                <td colspan="5" style="padding: 10px; text-align: center;">Bu sınavı henüz hiçbir öğrenci tamamlamadı.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($data['results'] as $res): ?>
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 10px; font-weight: bold;"><?php echo htmlspecialchars($res->username); ?></td>
                                    <td style="padding: 10px; color: var(--text-muted);"><?php echo htmlspecialchars($res->email); ?></td>
                                    <td style="padding: 10px; font-weight: bold; color: <?php echo ($res->score >= 50) ? 'var(--success)' : 'var(--danger)'; ?>;">
                                        <?php echo number_format($res->score, 2); ?> / 100
                                    </td>
                                    <td style="padding: 10px;"><?php echo date('d M Y, H:i', strtotime($res->completed_at)); ?></td>
                                    <td style="padding: 10px;">
                                        <a href="<?php echo URLROOT; ?>/teacher/snapshots/<?php echo $data['quiz_id']; ?>/<?php echo $res->student_id; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem; width: auto; background-color: var(--accent);">
                                            <i class="fa-solid fa-camera"></i> Fotoğrafları Gör
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require '../views/layouts/footer.php'; ?>

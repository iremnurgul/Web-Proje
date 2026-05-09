<?php require '../views/layouts/header.php'; ?>

<div class="ls-dashboard-container">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <div class="ls-main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="content-area">
            <h1 style="margin-bottom: 24px;">Sonuçlarım</h1>
            
            <div class="card">
                <table style="width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <th style="padding: 10px;">Sınav Adı</th>
                            <th style="padding: 10px;">Ders</th>
                            <th style="padding: 10px;">Puan</th>
                            <th style="padding: 10px;">Tamamlama Tarihi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['results'])): ?>
                            <tr>
                                <td colspan="4" style="padding: 10px; text-align: center;">Henüz hiçbir sınavı tamamlamadınız.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($data['results'] as $result): ?>
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 10px;"><?php echo htmlspecialchars($result->quiz_name); ?></td>
                                    <td style="padding: 10px; color: var(--text-muted); font-size: 0.9em;"><?php echo htmlspecialchars($result->course_name); ?></td>
                                    <td style="padding: 10px; font-weight: bold; color: <?php echo ($result->score >= 50) ? 'var(--success)' : 'var(--danger)'; ?>;">
                                        <?php echo number_format($result->score, 2); ?> / 100
                                    </td>
                                    <td style="padding: 10px; font-size: 0.9em;"><?php echo date('d M Y, H:i', strtotime($result->completed_at)); ?></td>
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

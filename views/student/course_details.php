<?php require '../views/layouts/header.php'; ?>

<div class="ls-dashboard-container">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <div class="ls-main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="content-area">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h1 style="margin: 0;">Ders: <?php echo htmlspecialchars($data['course']->course_name); ?></h1>
                <a href="<?php echo URLROOT; ?>/student/dashboard" class="btn btn-primary" style="width: auto; background-color: var(--secondary);">Panele Dön</a>
            </div>

            <div class="card" style="margin-bottom: 24px;">
                <h3 style="color: var(--primary); margin-bottom: 10px;">Eğitmen</h3>
                <p style="font-size: 1.1rem; color: var(--text-color);"><i class="fa-solid fa-chalkboard-user"></i> <?php echo htmlspecialchars($data['course']->teacher_name); ?></p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                
                <!-- Active Quizzes -->
                <div class="card">
                    <div class="card-title" style="color: var(--warning);">Bekleyen Sınavlar</div>
                    <?php if(empty($data['active_quizzes'])): ?>
                        <p style="color: var(--text-muted); text-align: center;">Bu derse ait aktif bir sınav bulunmuyor.</p>
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            <?php foreach($data['active_quizzes'] as $quiz): ?>
                                <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); padding: 15px; border-radius: 8px;">
                                    <h4 style="margin-bottom: 5px;"><?php echo htmlspecialchars($quiz->quiz_name); ?></h4>
                                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 10px;">
                                        Bitiş: <?php echo date('M d, Y H:i', strtotime($quiz->end_date)); ?><br>
                                        Süre: <?php echo $quiz->duration; ?> dk
                                    </p>
                                    <a href="<?php echo URLROOT; ?>/student/takeQuiz/<?php echo $quiz->id; ?>" class="btn btn-primary" style="padding: 8px 15px; font-size: 0.9rem;">Sınava Başla</a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Past Results -->
                <div class="card">
                    <div class="card-title" style="color: var(--success);">Sonuçlarınız</div>
                    <?php if(empty($data['results'])): ?>
                        <p style="color: var(--text-muted); text-align: center;">Bu derste henüz hiçbir sınava girmediniz.</p>
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            <?php foreach($data['results'] as $res): ?>
                                <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); padding: 15px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <h4 style="margin-bottom: 5px;"><?php echo htmlspecialchars($res->quiz_name); ?></h4>
                                        <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">
                                            Tarih: <?php echo date('M d, Y', strtotime($res->completed_at)); ?>
                                        </p>
                                    </div>
                                    <div style="text-align: right;">
                                        <div style="font-size: 1.5rem; font-weight: bold; color: <?php echo $res->score >= 50 ? 'var(--success)' : 'var(--danger)'; ?>;">
                                            <?php echo $res->score; ?>
                                        </div>
                                        <div style="font-size: 0.8rem; color: var(--text-muted);">Puan</div>
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

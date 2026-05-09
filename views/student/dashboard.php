<?php require '../views/layouts/header.php'; ?>

<div class="ls-dashboard-container">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <div class="ls-main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="ls-header-section">
            <h2><?php echo Language::get('dashboard'); ?></h2>
            <p>Öğrenci paneline hoş geldiniz, kayıtlı olduğunuz dersleri ve aktif sınavları buradan takip edebilirsiniz.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div class="ls-card" style="margin-bottom: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                <div style="font-size: 2.5rem; color: var(--primary); margin-bottom: 10px;"><i class="fa-solid fa-graduation-cap"></i></div>
                <h3 style="margin-bottom: 5px; font-size: 1.1rem; color: var(--text-muted);">Kayıtlı Derslerim</h3>
                <div style="font-size: 2rem; font-weight: bold;"><?php echo count($data['courses']); ?></div>
            </div>
            <div class="ls-card" style="margin-bottom: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                <div style="font-size: 2.5rem; color: var(--accent); margin-bottom: 10px;"><i class="fa-solid fa-gamepad"></i></div>
                <h3 style="margin-bottom: 5px; font-size: 1.1rem; color: var(--text-muted);">Bekleyen Sınavlar</h3>
                <div style="font-size: 2rem; font-weight: bold;"><?php echo count($data['quizzes']); ?></div>
            </div>
            <div class="ls-card" style="margin-bottom: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                <div style="font-size: 2.5rem; color: var(--success); margin-bottom: 10px;"><i class="fa-solid fa-chart-line"></i></div>
                <h3 style="margin-bottom: 5px; font-size: 1.1rem; color: var(--text-muted);">Sonuçlarım</h3>
                <a href="<?php echo URLROOT; ?>/student/results" class="btn btn-secondary" style="margin-top: 10px; width: 100%;">Görüntüle</a>
            </div>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem;">
            <div class="ls-card">
                <h3><i class="fa-solid fa-stopwatch"></i> Aktif Sınavlarım</h3>
                <?php if(empty($data['quizzes'])): ?>
                    <div class="alert alert-success">Şu anda çözebileceğiniz aktif bir sınav bulunmuyor.</div>
                <?php else: ?>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <?php foreach($data['quizzes'] as $quiz): ?>
                            <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--glass-border); border-radius: 8px; padding: 15px; display: flex; justify-content: space-between; align-items: center; transition: transform 0.2s;">
                                <div>
                                    <h4 style="margin: 0; font-size: 1.1rem; color: var(--text-main);"><?php echo htmlspecialchars($quiz->quiz_name); ?></h4>
                                    <span style="color: var(--text-muted); font-size: 0.9em;">Ders: <?php echo htmlspecialchars($quiz->course_name); ?></span>
                                </div>
                                <a href="<?php echo URLROOT; ?>/student/takeQuiz/<?php echo $quiz->id; ?>" class="btn btn-primary" style="padding: 8px 16px; background-color: var(--accent);"><i class="fa-solid fa-play"></i> Başla</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="ls-card">
                <h3><i class="fa-solid fa-book-open"></i> Kayıtlı Olduğum Dersler</h3>
                <?php if(empty($data['courses'])): ?>
                    <div class="alert alert-success">Henüz herhangi bir derse kayıtlı değilsiniz.</div>
                <?php else: ?>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <?php foreach($data['courses'] as $course): ?>
                            <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--glass-border); border-radius: 8px; padding: 15px; display: flex; flex-direction: column; gap: 5px;">
                                <a href="<?php echo URLROOT; ?>/student/courseDetails/<?php echo $course->id; ?>" style="color: var(--primary-hover); text-decoration: none; font-weight: bold; font-size: 1.1rem; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-bookmark"></i> <?php echo htmlspecialchars($course->course_name); ?>
                                </a>
                                <span style="color: var(--text-muted); font-size: 0.9em;"><i class="fa-solid fa-user-tie"></i> Öğretmen: <?php echo htmlspecialchars($course->teacher_name); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require '../views/layouts/footer.php'; ?>

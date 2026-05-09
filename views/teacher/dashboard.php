<?php require '../views/layouts/header.php'; ?>

<div class="ls-dashboard-container">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <div class="ls-main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="ls-header-section">
            <h2><?php echo Language::get('dashboard'); ?></h2>
            <p>Eğitmen paneline hoş geldiniz, eğitimlerinizi ve sınavlarınızı buradan yönetebilirsiniz.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div class="ls-card" style="margin-bottom: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                <div style="font-size: 2.5rem; color: var(--primary); margin-bottom: 10px;"><i class="fa-solid fa-book"></i></div>
                <h3 style="margin-bottom: 5px; font-size: 1.1rem; color: var(--text-muted);">Toplam Dersiniz</h3>
                <div style="font-size: 2rem; font-weight: bold;"><?php echo count($data['courses']); ?></div>
            </div>
            <div class="ls-card" style="margin-bottom: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                <div style="font-size: 2.5rem; color: var(--accent); margin-bottom: 10px;"><i class="fa-solid fa-stopwatch"></i></div>
                <h3 style="margin-bottom: 5px; font-size: 1.1rem; color: var(--text-muted);">Hızlı Sınav Oluştur</h3>
                <a href="<?php echo URLROOT; ?>/teacher/quizzes" class="btn btn-primary" style="margin-top: 10px; width: 100%;"><i class="fa-solid fa-plus"></i> Yeni Sınav</a>
            </div>
            <div class="ls-card" style="margin-bottom: 0; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                <div style="font-size: 2.5rem; color: var(--success); margin-bottom: 10px;"><i class="fa-solid fa-layer-group"></i></div>
                <h3 style="margin-bottom: 5px; font-size: 1.1rem; color: var(--text-muted);">Soru Bankası</h3>
                <a href="<?php echo URLROOT; ?>/teacher/questions" class="btn btn-secondary" style="margin-top: 10px; width: 100%;">Soruları Yönet</a>
            </div>
        </div>
        
        <div class="ls-card">
            <h3><i class="fa-solid fa-clock-rotate-left"></i> Son Eklenen Derslerim</h3>
            <?php if(empty($data['courses'])): ?>
                <div class="alert alert-success">Henüz adınıza atanmış bir ders bulunmuyor.</div>
            <?php else: ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px; margin-top: 15px;">
                    <?php foreach($data['courses'] as $course): ?>
                        <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--glass-border); border-radius: 8px; padding: 15px; display: flex; align-items: center; gap: 15px; transition: transform 0.2s;">
                            <div style="background: rgba(79, 70, 229, 0.1); width: 50px; height: 50px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 1.5rem;">
                                <i class="fa-solid fa-book-open"></i>
                            </div>
                            <div style="flex: 1;">
                                <h4 style="margin: 0; font-size: 1.1rem; color: var(--text-main);"><?php echo htmlspecialchars($course->course_name); ?></h4>
                                <a href="<?php echo URLROOT; ?>/teacher/courseDetails/<?php echo $course->id; ?>" style="font-size: 0.9rem; margin-top: 5px; display: inline-block;">Detayları Gör &rarr;</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require '../views/layouts/footer.php'; ?>

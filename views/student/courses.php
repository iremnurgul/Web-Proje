<?php require '../views/layouts/header.php'; ?>
<?php require '../views/layouts/navbar.php'; ?>

<div class="ls-dashboard-container">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <main class="ls-main-content">
        <div class="content-area">
            <h1 style="margin-bottom: 24px;">Derslerim</h1>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                <?php if(empty($data['courses'])): ?>
                    <p>Sistemde size atanmış herhangi bir ders bulunamadı.</p>
                <?php else: ?>
                    <?php foreach($data['courses'] as $course): ?>
                        <?php if(in_array($course->id, $data['enrolled_ids'])): ?>
                        <div class="card" style="margin-bottom: 0;">
                            <div class="card-title"><?php echo htmlspecialchars($course->course_name); ?></div>
                            <p style="color: var(--text-muted); margin-bottom: 15px;">Öğretmen: <?php echo htmlspecialchars($course->teacher_name); ?></p>
                            <a href="<?php echo URLROOT; ?>/student/courseDetails/<?php echo $course->id; ?>" class="btn btn-primary" style="background-color: var(--success); text-align: center; display: block; text-decoration: none;">Dersi Gör</a>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<?php require '../views/layouts/footer.php'; ?>

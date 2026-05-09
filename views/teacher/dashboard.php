<?php require '../views/layouts/header.php'; ?>

<div class="wrapper">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="content-area">
            <h1 style="margin-bottom: 24px;">Teacher Dashboard</h1>
            
            <div class="card">
                <div class="card-title">Quick Actions</div>
                <a href="<?php echo URLROOT; ?>/teacher/courses" class="btn btn-primary" style="width: auto; display: inline-block;">Manage Courses</a>
                <a href="<?php echo URLROOT; ?>/teacher/quizzes" class="btn btn-primary" style="width: auto; background-color: var(--accent); margin-left: 10px; display: inline-block;">Manage Quizzes</a>
            </div>
            
            <div class="card">
                <div class="card-title">My Recent Courses</div>
                <?php if(empty($data['courses'])): ?>
                    <p>No courses found. Start by creating your first course.</p>
                <?php else: ?>
                    <ul style="list-style-type: none; padding: 0;">
                        <?php foreach($data['courses'] as $course): ?>
                            <li style="margin-bottom: 10px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                                <a href="<?php echo URLROOT; ?>/teacher/courseDetails/<?php echo $course->id; ?>" style="color: var(--primary); text-decoration: none; font-weight: bold; font-size: 1.1rem; display: inline-flex; align-items: center; gap: 10px;">
                                    <i class="fa-solid fa-book"></i> <?php echo htmlspecialchars($course->course_name); ?>
                                </a>
                                <span style="color: var(--text-muted); font-size: 0.8em;"><?php echo date('d M Y', strtotime($course->created_at)); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require '../views/layouts/footer.php'; ?>

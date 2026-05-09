<?php require '../views/layouts/header.php'; ?>

<div class="wrapper">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="content-area">
            <h1 style="margin-bottom: 24px;">Student Dashboard</h1>
            
            <div class="card">
                <div class="card-title">Available Quizzes</div>
                <?php if(empty($data['quizzes'])): ?>
                    <p>You have no active quizzes at the moment.</p>
                <?php else: ?>
                    <ul style="list-style-type: none; padding: 0;">
                        <?php foreach($data['quizzes'] as $quiz): ?>
                            <li style="margin-bottom: 10px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
                                <strong><?php echo htmlspecialchars($quiz->quiz_name); ?></strong> 
                                <span style="color: var(--text-muted); font-size: 0.9em;">(<?php echo htmlspecialchars($quiz->course_name); ?>)</span>
                                <a href="<?php echo URLROOT; ?>/student/takeQuiz/<?php echo $quiz->id; ?>" class="btn btn-primary" style="padding: 5px 10px; float: right; width: auto; background-color: var(--accent);">Take Quiz</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            
            <div class="card">
                <div class="card-title">My Enrolled Courses</div>
                <?php if(empty($data['courses'])): ?>
                    <p>You are not enrolled in any courses.</p>
                    <a href="<?php echo URLROOT; ?>/student/courses" class="btn btn-primary" style="width: auto; margin-top: 10px; display: inline-block;">Browse Courses</a>
                <?php else: ?>
                    <ul style="list-style-type: disc; margin-left: 20px;">
                        <?php foreach($data['courses'] as $course): ?>
                            <li style="margin-bottom: 8px;">
                                <a href="<?php echo URLROOT; ?>/student/courseDetails/<?php echo $course->id; ?>" style="color: var(--primary); text-decoration: none; font-weight: bold; font-size: 1.1rem; display: inline-flex; align-items: center; gap: 5px;">
                                    <i class="fa-solid fa-book-open"></i> <?php echo htmlspecialchars($course->course_name); ?>
                                </a>
                                <span style="color: var(--text-muted); margin-left: 10px;">(Inst. <?php echo htmlspecialchars($course->teacher_name); ?>)</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?php echo URLROOT; ?>/student/courses" class="btn btn-primary" style="width: auto; margin-top: 15px; display: inline-block;">Browse More Courses</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require '../views/layouts/footer.php'; ?>

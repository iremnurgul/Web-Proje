<?php require '../views/layouts/header.php'; ?>

<div class="wrapper">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="content-area">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h1 style="margin: 0;">Course: <?php echo htmlspecialchars($data['course']->course_name); ?></h1>
                <a href="<?php echo URLROOT; ?>/teacher/dashboard" class="btn btn-primary" style="width: auto; background-color: var(--secondary);">Back to Dashboard</a>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                
                <!-- Enrolled Students -->
                <div class="card">
                    <div class="card-title" style="color: var(--primary);">Enrolled Students (<?php echo count($data['students']); ?>)</div>
                    <?php if(empty($data['students'])): ?>
                        <p style="color: var(--text-muted); text-align: center;">No students enrolled yet.</p>
                    <?php else: ?>
                        <div style="max-height: 400px; overflow-y: auto; padding-right: 10px;">
                            <table style="width: 100%; text-align: left; border-collapse: collapse;">
                                <thead>
                                    <tr style="border-bottom: 1px solid var(--border-color);">
                                        <th style="padding: 10px;">Username</th>
                                        <th style="padding: 10px;">Enrolled At</th>
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
                    <div class="card-title" style="color: var(--accent);">Course Quizzes (<?php echo count($data['quizzes']); ?>)</div>
                    <?php if(empty($data['quizzes'])): ?>
                        <p style="color: var(--text-muted); text-align: center;">No quizzes created for this course yet.</p>
                    <?php else: ?>
                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            <?php foreach($data['quizzes'] as $quiz): ?>
                                <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); padding: 15px; border-radius: 8px;">
                                    <h4 style="margin-bottom: 5px;"><?php echo htmlspecialchars($quiz->quiz_name); ?></h4>
                                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 10px;">
                                        Duration: <?php echo $quiz->duration; ?> mins | Status: 
                                        <span style="color: <?php echo $quiz->is_active ? 'var(--success)' : 'var(--danger)'; ?>;">
                                            <?php echo $quiz->is_active ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </p>
                                    <div style="display: flex; gap: 10px;">
                                        <a href="<?php echo URLROOT; ?>/teacher/manageQuestions/<?php echo $quiz->id; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem;">Manage Questions</a>
                                        <a href="<?php echo URLROOT; ?>/teacher/quizResults/<?php echo $quiz->id; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem; background-color: var(--success);">View Results</a>
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

<?php require '../views/layouts/header.php'; ?>

<div class="wrapper">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="content-area">
            <h1 style="margin-bottom: 24px;">Active Quizzes</h1>
            
            <div class="card">
                <table style="width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <th style="padding: 10px;">Quiz Name</th>
                            <th style="padding: 10px;">Course</th>
                            <th style="padding: 10px;">Ends At</th>
                            <th style="padding: 10px;">Duration</th>
                            <th style="padding: 10px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['quizzes'])): ?>
                            <tr>
                                <td colspan="5" style="padding: 10px; text-align: center;">No active quizzes found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($data['quizzes'] as $quiz): ?>
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 10px;"><?php echo htmlspecialchars($quiz->quiz_name); ?></td>
                                    <td style="padding: 10px;"><?php echo htmlspecialchars($quiz->course_name); ?></td>
                                    <td style="padding: 10px;"><?php echo date('d M Y H:i', strtotime($quiz->end_date)); ?></td>
                                    <td style="padding: 10px;"><?php echo $quiz->duration; ?> mins</td>
                                    <td style="padding: 10px;">
                                        <a href="<?php echo URLROOT; ?>/student/takeQuiz/<?php echo $quiz->id; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem; width: auto; background-color: var(--accent);">Take Quiz</a>
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

<?php require '../views/layouts/header.php'; ?>

<div class="wrapper">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="content-area">
            <h1 style="margin-bottom: 24px;">Manage Quizzes</h1>
            
            <div class="card">
                <div class="card-title">Add New Quiz</div>
                <form id="addQuizForm">
                    <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
                    
                    <div class="form-group">
                        <label>Select Course</label>
                        <select name="course_id" class="form-control" required>
                            <option value="">-- Select Course --</option>
                            <?php foreach($data['courses'] as $course): ?>
                                <option value="<?php echo $course->id; ?>"><?php echo htmlspecialchars($course->course_name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Quiz Name</label>
                        <input type="text" name="quiz_name" class="form-control" required>
                    </div>

                    <div style="display: flex; gap: 15px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Start Date</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <input type="datetime-local" name="start_date" class="form-control" required style="padding-right: 40px; cursor: pointer;" onclick="this.showPicker()">
                                <i class="fa-solid fa-calendar-days" style="position: absolute; right: 15px; color: var(--primary); cursor: pointer; pointer-events: none;"></i>
                            </div>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>End Date</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <input type="datetime-local" name="end_date" class="form-control" required style="padding-right: 40px; cursor: pointer;" onclick="this.showPicker()">
                                <i class="fa-solid fa-calendar-days" style="position: absolute; right: 15px; color: var(--primary); cursor: pointer; pointer-events: none;"></i>
                            </div>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Duration (Minutes)</label>
                            <input type="number" name="duration" class="form-control" min="1" value="30" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label style="display: inline-flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" name="is_active" checked>
                            Is Active?
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary">Create Quiz</button>
                    <div id="quizMessage" style="margin-top: 10px;"></div>
                </form>
            </div>
            
            <div class="card">
                <div class="card-title">Quiz List</div>
                <table style="width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <th style="padding: 10px;">Quiz Name</th>
                            <th style="padding: 10px;">Course</th>
                            <th style="padding: 10px;">Duration</th>
                            <th style="padding: 10px;">Status</th>
                            <th style="padding: 10px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['quizzes'])): ?>
                            <tr>
                                <td colspan="5" style="padding: 10px; text-align: center;">No quizzes found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($data['quizzes'] as $quiz): ?>
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 10px;"><?php echo htmlspecialchars($quiz->quiz_name); ?></td>
                                    <td style="padding: 10px;"><?php echo htmlspecialchars($quiz->course_name); ?></td>
                                    <td style="padding: 10px;"><?php echo $quiz->duration; ?> mins</td>
                                    <td style="padding: 10px;">
                                        <?php if($quiz->is_active): ?>
                                            <span style="color: var(--success);">Active</span>
                                        <?php else: ?>
                                            <span style="color: var(--danger);">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 10px;">
                                        <a href="<?php echo URLROOT; ?>/teacher/manageQuestions/<?php echo $quiz->id; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem; width: auto; background-color: var(--accent); margin-right: 5px;">Manage Questions</a>
                                        <a href="<?php echo URLROOT; ?>/teacher/quizResults/<?php echo $quiz->id; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem; width: auto; background-color: var(--success);">
                                            <i class="fa-solid fa-square-poll-vertical"></i> Results & Photos
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addQuizForm = document.getElementById('addQuizForm');
    if (addQuizForm) {
        addQuizForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(addQuizForm);
            
            try {
                const response = await AjaxHelper.post('<?php echo URLROOT; ?>/teacher/addQuiz', formData);
                const msgDiv = document.getElementById('quizMessage');
                if (response.success) {
                    msgDiv.innerHTML = `<span style="color: var(--success);">${response.message}</span>`;
                    setTimeout(() => location.reload(), 1000);
                } else {
                    msgDiv.innerHTML = `<span style="color: var(--danger);">${response.message}</span>`;
                }
            } catch (error) {
                console.error(error);
            }
        });
    }
});
</script>

<?php require '../views/layouts/footer.php'; ?>

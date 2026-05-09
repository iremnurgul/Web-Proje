<?php require '../views/layouts/header.php'; ?>

<div class="wrapper">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="content-area">
            <h1 style="margin-bottom: 24px;">Question Bank</h1>
            
            <div class="card">
                <div class="card-title">Add New Question</div>
                <form id="addQuestionForm">
                    <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
                    
                    <div class="form-group">
                        <label>Select Quiz</label>
                        <select name="quiz_id" class="form-control" required>
                            <option value="">-- Select Quiz --</option>
                            <?php foreach($data['quizzes'] as $quiz): ?>
                                <option value="<?php echo $quiz->id; ?>"><?php echo htmlspecialchars($quiz->quiz_name . ' (' . $quiz->course_name . ')'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Question Text</label>
                        <textarea name="question_text" class="form-control" rows="3" required></textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>Option A</label>
                            <input type="text" name="option_a" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Option B</label>
                            <input type="text" name="option_b" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Option C</label>
                            <input type="text" name="option_c" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Option D</label>
                            <input type="text" name="option_d" class="form-control" required>
                        </div>
                    </div>

                    <div style="display: flex; gap: 15px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Correct Answer</label>
                            <select name="correct_answer" class="form-control" required>
                                <option value="A">Option A</option>
                                <option value="B">Option B</option>
                                <option value="C">Option C</option>
                                <option value="D">Option D</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Points</label>
                            <input type="number" name="points" class="form-control" value="10" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Add Question</button>
                    <div id="questionMessage" style="margin-top: 10px;"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addQuestionForm = document.getElementById('addQuestionForm');
    if (addQuestionForm) {
        addQuestionForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(addQuestionForm);
            
            try {
                const response = await AjaxHelper.post('<?php echo URLROOT; ?>/teacher/addQuestion', formData);
                const msgDiv = document.getElementById('questionMessage');
                if (response.success) {
                    msgDiv.innerHTML = `<span style="color: var(--success);">${response.message}</span>`;
                    addQuestionForm.reset();
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

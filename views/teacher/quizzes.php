<?php require '../views/layouts/header.php'; ?>

<div class="ls-dashboard-container">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <div class="ls-main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="content-area">
            <h1 style="margin-bottom: 24px;">Sınav (Quiz) Yönetimi</h1>
            
            <div class="card">
                <div class="card-title">Yeni Sınav Oluştur</div>
                <form id="addQuizForm">
                    <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
                    
                    <div class="form-group">
                        <label>Ders Seçin</label>
                        <select name="course_id" class="form-control" required>
                            <option value="">-- Ders Seçin --</option>
                            <?php foreach($data['courses'] as $course): ?>
                                <option value="<?php echo $course->id; ?>"><?php echo htmlspecialchars($course->course_name); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Sınav (Quiz) Adı</label>
                        <input type="text" name="quiz_name" class="form-control" required>
                    </div>

                    <div style="display: flex; gap: 15px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Başlangıç Tarihi (Otomatik Aktif Olur)</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <input type="datetime-local" name="start_date" class="form-control" required style="padding-right: 40px; cursor: pointer;" onclick="this.showPicker()">
                                <i class="fa-solid fa-calendar-days" style="position: absolute; right: 15px; color: var(--primary); cursor: pointer; pointer-events: none;"></i>
                            </div>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Bitiş Tarihi</label>
                            <div style="position: relative; display: flex; align-items: center;">
                                <input type="datetime-local" name="end_date" class="form-control" required style="padding-right: 40px; cursor: pointer;" onclick="this.showPicker()">
                                <i class="fa-solid fa-calendar-days" style="position: absolute; right: 15px; color: var(--primary); cursor: pointer; pointer-events: none;"></i>
                            </div>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Süre (Dakika)</label>
                            <input type="number" name="duration" class="form-control" min="1" value="30" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label style="display: inline-flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="checkbox" name="is_active" checked>
                            Şu an aktif olsun mu? (Daha sonra değiştirebilirsiniz)
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary">Sınavı Oluştur</button>
                    <div id="quizMessage" style="margin-top: 10px;"></div>
                </form>
            </div>
            
            <div class="card">
                <div class="card-title">Sınav Listesi</div>
                <table style="width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <th style="padding: 10px;">Sınav Adı</th>
                            <th style="padding: 10px;">Ders</th>
                            <th style="padding: 10px;">Süre</th>
                            <th style="padding: 10px;">Durum</th>
                            <th style="padding: 10px;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['quizzes'])): ?>
                            <tr>
                                <td colspan="5" style="padding: 10px; text-align: center;">Kayıtlı sınav bulunmuyor.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($data['quizzes'] as $quiz): ?>
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 10px;"><?php echo htmlspecialchars($quiz->quiz_name); ?></td>
                                    <td style="padding: 10px;"><?php echo htmlspecialchars($quiz->course_name); ?></td>
                                    <td style="padding: 10px;"><?php echo $quiz->duration; ?> dk</td>
                                    <td style="padding: 10px;">
                                        <?php if($quiz->is_active): ?>
                                            <span style="color: var(--success);">Aktif</span>
                                        <?php else: ?>
                                            <span style="color: var(--danger);">Pasif</span>
                                        <?php endif; ?>
                                        
                                    </td>
                                    <td style="padding: 10px;">
                                        <a href="<?php echo URLROOT; ?>/teacher/manageQuestions/<?php echo $quiz->id; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem; width: auto; background-color: var(--accent); margin-right: 5px;">Soruları Yönet</a>
                                        <a href="<?php echo URLROOT; ?>/teacher/quizResults/<?php echo $quiz->id; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem; width: auto; background-color: var(--success);">
                                            <i class="fa-solid fa-square-poll-vertical"></i> Sonuçlar ve Fotoğraflar
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
                    setTimeout(() => window.location.href = '<?php echo URLROOT; ?>/teacher/manageQuestions/' + response.quiz_id, 500);
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

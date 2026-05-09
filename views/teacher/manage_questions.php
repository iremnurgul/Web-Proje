<?php require '../views/layouts/header.php'; ?>

<div class="ls-dashboard-container">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <div class="ls-main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="content-area">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
                <h1 style="margin: 0;">Soruları Yönet: <?php echo htmlspecialchars($data['quiz']->quiz_name); ?></h1>
                <a href="<?php echo URLROOT; ?>/teacher/quizzes" class="btn btn-primary" style="width: auto; background-color: var(--secondary);">Sınavlara Dön</a>
            </div>

            <?php 
                $total_points = 0;
                $total_questions = count($data['questions']);
                foreach($data['questions'] as $q) {
                    $total_points += $q->points;
                }
            ?>
            
            <div style="display: flex; gap: 20px; margin-bottom: 24px;">
                <div class="ls-card" style="flex: 1; text-align: center; margin-bottom: 0;">
                    <h3 style="margin-bottom: 5px; color: var(--text-muted);">Toplam Soru</h3>
                    <div style="font-size: 2rem; font-weight: bold; color: var(--primary);"><?php echo $total_questions; ?></div>
                </div>
                <div class="ls-card" style="flex: 1; text-align: center; margin-bottom: 0;">
                    <h3 style="margin-bottom: 5px; color: var(--text-muted);">Toplam Puan</h3>
                    <div style="font-size: 2rem; font-weight: bold; color: var(--accent);"><?php echo $total_points; ?> / 100</div>
                </div>
            </div>
            
            <div class="ls-card">
                <div class="card-title">Yeni Soru Ekle</div>
                <form id="addSoruForm" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
                    <input type="hidden" name="quiz_id" value="<?php echo $data['quiz']->id; ?>">
                    
                    <div class="form-group">
                        <label>Soru Metni</label>
                        <textarea name="question_text" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>(İsteğe Bağlı) Soru Görseli Ekle</label>
                        <input type="file" name="question_image" class="form-control" accept="image/*">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>A Åıkkı</label>
                            <input type="text" name="option_a" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>B Åıkkı</label>
                            <input type="text" name="option_b" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>C Åıkkı</label>
                            <input type="text" name="option_c" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>D Åıkkı</label>
                            <input type="text" name="option_d" class="form-control" required>
                        </div>
                    </div>

                    <div style="display: flex; gap: 15px;">
                        <div class="form-group" style="flex: 1;">
                            <label>Doğru Cevap</label>
                            <select name="correct_answer" class="form-control" required>
                                <option value="A">A Åıkkı</option>
                                <option value="B">B Åıkkı</option>
                                <option value="C">C Åıkkı</option>
                                <option value="D">D Åıkkı</option>
                            </select>
                        </div>
                        <div class="form-group" style="flex: 1;">
                            <label>Puan (Puan)</label>
                            <input type="number" name="points" class="form-control" value="10" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Soruyu Kaydet</button>
                    <div id="questionMessage" style="margin-top: 10px;"></div>
                </form>
            </div>

            <div class="ls-card">
                <div class="card-title">Eklenen Sorular</div>
                <?php if(empty($data['questions'])): ?>
                    <p style="text-align: center; color: var(--text-muted);">Henüz soru eklenmedi.</p>
                <?php else: ?>
                    <?php foreach($data['questions'] as $index => $q): ?>
                        <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); padding: 15px; border-radius: 8px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: flex-start;">
                            <div>
                                <h4 style="margin-bottom: 10px; color: var(--primary);">Soru <?php echo $index + 1; ?>: <?php echo htmlspecialchars($q->question_text); ?></h4>
                                <ul style="list-style-type: none; padding: 0; color: var(--text-muted); font-size: 0.9em;">
                                    <li style="margin-bottom: 5px; <?php echo $q->correct_answer == 'A' ? 'color: var(--success); font-weight: bold;' : ''; ?>">A) <?php echo htmlspecialchars($q->option_a); ?></li>
                                    <li style="margin-bottom: 5px; <?php echo $q->correct_answer == 'B' ? 'color: var(--success); font-weight: bold;' : ''; ?>">B) <?php echo htmlspecialchars($q->option_b); ?></li>
                                    <li style="margin-bottom: 5px; <?php echo $q->correct_answer == 'C' ? 'color: var(--success); font-weight: bold;' : ''; ?>">C) <?php echo htmlspecialchars($q->option_c); ?></li>
                                    <li style="margin-bottom: 5px; <?php echo $q->correct_answer == 'D' ? 'color: var(--success); font-weight: bold;' : ''; ?>">D) <?php echo htmlspecialchars($q->option_d); ?></li>
                                </ul>
                                <div style="margin-top: 10px; font-weight: bold; color: var(--accent);"><?php echo $q->points; ?> Puan</div>
                            </div>
                            <button onclick="deleteSoru(<?php echo $q->id; ?>)" class="btn btn-primary" style="width: auto; background-color: var(--danger); padding: 5px 10px; font-size: 0.8rem;"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addSoruForm = document.getElementById('addSoruForm');
    if (addSoruForm) {
        addSoruForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(addSoruForm);
            
            try {
                const response = await AjaxHelper.post('<?php echo URLROOT; ?>/teacher/addSoru', formData);
                const msgDiv = document.getElementById('questionMessage');
                if (response.success) {
                    msgDiv.innerHTML = `<span style="color: var(--success);">${response.message}</span>`;
                    setTimeout(() => location.reload(), 800);
                } else {
                    msgDiv.innerHTML = `<span style="color: var(--danger);">${response.message}</span>`;
                }
            } catch (error) {
                console.error(error);
            }
        });
    }
});

async function deleteSoru(id) {
    if(confirm('Bu soruyu silmek istediğinize emin misiniz?')) {
        const formData = new FormData();
        formData.append('csrf_token', '<?php echo Security::generateCsrfToken(); ?>');
        
        try {
            const response = await AjaxHelper.post('<?php echo URLROOT; ?>/teacher/deleteSoru/' + id, formData);
            if (response.success) {
                location.reload();
            } else {
                alert('Soru silinemedi.');
            }
        } catch (error) {
            console.error(error);
        }
    }
}
</script>

<?php require '../views/layouts/footer.php'; ?>

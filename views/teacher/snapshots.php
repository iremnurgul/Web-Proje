<?php require '../views/layouts/header.php'; ?>

<div class="wrapper">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="content-area">
            <h1 style="margin-bottom: 5px;">Proctoring: <?php echo htmlspecialchars($data['student']->username); ?></h1>
            <p style="color: var(--text-muted); margin-bottom: 24px;">Quiz: <?php echo htmlspecialchars($data['quiz']->quiz_name); ?></p>
            
            <a href="<?php echo URLROOT; ?>/teacher/quizzes" class="btn btn-primary" style="width: auto; margin-bottom: 20px;">Back to Quizzes</a>

            <div class="card">
                <?php if(empty($data['snapshots'])): ?>
                    <p style="text-align: center; color: var(--text-muted);">No snapshots available for this session.</p>
                <?php else: ?>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
                        <?php foreach($data['snapshots'] as $index => $snap): ?>
                            <div style="background: rgba(0,0,0,0.5); border: 1px solid var(--border-color); border-radius: 12px; overflow: hidden; position: relative;">
                                <div style="position: absolute; top: 10px; left: 10px; background: rgba(0,0,0,0.8); padding: 5px 10px; border-radius: 20px; font-size: 0.8em; font-weight: bold; border: 1px solid var(--primary);">
                                    <?php echo date('H:i:s', strtotime($snap->created_at)); ?>
                                </div>
                                <div style="position: absolute; top: 10px; right: 10px;">
                                    <button onclick="deleteSnapshot(<?php echo $snap->id; ?>)" class="btn btn-primary" style="background-color: var(--danger); width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                        <i class="fa-solid fa-trash" style="font-size: 0.8rem;"></i>
                                    </button>
                                </div>
                                <img src="<?php echo URLROOT . '/' . htmlspecialchars($snap->image_path); ?>" alt="Snapshot" style="width: 100%; height: auto; display: block;">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
async function deleteSnapshot(id) {
    if(confirm('Bu fotoğrafı kalıcı olarak silmek istediğinize emin misiniz?')) {
        try {
            const formData = new FormData();
            formData.append('csrf_token', '<?php echo Security::generateCsrfToken(); ?>');
            
            const response = await AjaxHelper.post('<?php echo URLROOT; ?>/teacher/deleteSnapshot/' + id, formData);
            if (response.success) {
                location.reload();
            } else {
                alert('Fotoğraf silinemedi.');
            }
        } catch (error) {
            console.error(error);
        }
    }
}
</script>

<?php require '../views/layouts/footer.php'; ?>

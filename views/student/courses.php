<?php require '../views/layouts/header.php'; ?>

<div class="wrapper">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="content-area">
            <h1 style="margin-bottom: 24px;">Browse Courses</h1>
            
            <div id="enrollMessage"></div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                <?php if(empty($data['courses'])): ?>
                    <p>No courses available right now.</p>
                <?php else: ?>
                    <?php foreach($data['courses'] as $course): ?>
                        <div class="card" style="margin-bottom: 0;">
                            <div class="card-title"><?php echo htmlspecialchars($course->course_name); ?></div>
                            <p style="color: var(--text-muted); margin-bottom: 15px;">Instructor: <?php echo htmlspecialchars($course->teacher_name); ?></p>
                            <?php if(in_array($course->id, $data['enrolled_ids'])): ?>
                                <button class="btn btn-enroll" disabled style="background-color: var(--success); color: white;">Enrolled</button>
                            <?php else: ?>
                                <button class="btn btn-primary btn-enroll" data-id="<?php echo $course->id; ?>">Enroll Now</button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const enrollButtons = document.querySelectorAll('.btn-enroll');
    
    enrollButtons.forEach(btn => {
        btn.addEventListener('click', async function() {
            const courseId = this.getAttribute('data-id');
            const btnElement = this;
            
            try {
                const response = await AjaxHelper.post('<?php echo URLROOT; ?>/student/enroll', { course_id: courseId });
                const msgDiv = document.getElementById('enrollMessage');
                
                if (response.success) {
                    btnElement.innerText = "Enrolled";
                    btnElement.disabled = true;
                    btnElement.style.backgroundColor = 'var(--success)';
                    msgDiv.innerHTML = `<div class="alert alert-success">${response.message}</div>`;
                } else {
                    msgDiv.innerHTML = `<div class="alert alert-danger">${response.message}</div>`;
                }
            } catch (error) {
                console.error(error);
            }
        });
    });
});
</script>

<?php require '../views/layouts/footer.php'; ?>

<?php require '../views/layouts/header.php'; ?>

<div class="wrapper">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="content-area">
            <h1 style="margin-bottom: 24px;">My Courses</h1>
            
            <div class="card">
                <div class="card-title">Add New Course</div>
                <form id="addCourseForm">
                    <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo Security::generateCsrfToken(); ?>">
                    <div class="form-group" style="display: flex; gap: 10px;">
                        <input type="text" name="course_name" id="course_name" class="form-control" placeholder="Course Name" required>
                        <button type="submit" class="btn btn-primary" style="width: 200px;">Add Course</button>
                    </div>
                </form>
                <div id="courseMessage" style="margin-top: 10px;"></div>
            </div>
            
            <div class="card">
                <div class="card-title">Course List</div>
                <table style="width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <th style="padding: 10px;">ID</th>
                            <th style="padding: 10px;">Course Name</th>
                            <th style="padding: 10px;">Created At</th>
                            <th style="padding: 10px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['courses'])): ?>
                            <tr>
                                <td colspan="4" style="padding: 10px; text-align: center;">No courses found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($data['courses'] as $course): ?>
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 10px;"><?php echo $course->id; ?></td>
                                    <td style="padding: 10px;">
                                        <a href="<?php echo URLROOT; ?>/teacher/courseDetails/<?php echo $course->id; ?>" style="color: var(--primary); text-decoration: none; font-weight: bold; display: inline-flex; align-items: center; gap: 5px;">
                                            <i class="fa-solid fa-book"></i> <?php echo htmlspecialchars($course->course_name); ?>
                                        </a>
                                    </td>
                                    <td style="padding: 10px;"><?php echo $course->created_at; ?></td>
                                    <td style="padding: 10px;">
                                        <button class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem; width: auto;">Edit</button>
                                        <button class="btn" style="background-color: var(--danger); color: white; padding: 5px 10px; font-size: 0.8rem; width: auto;">Delete</button>
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
    const addCourseForm = document.getElementById('addCourseForm');
    if (addCourseForm) {
        addCourseForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(addCourseForm);
            
            try {
                const response = await AjaxHelper.post('<?php echo URLROOT; ?>/teacher/addCourse', formData);
                const msgDiv = document.getElementById('courseMessage');
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

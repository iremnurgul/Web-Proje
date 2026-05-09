<?php require '../views/layouts/header.php'; ?>
<?php require '../views/layouts/navbar.php'; ?>

<div class="ls-dashboard-container">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <main class="ls-main-content">
        <div class="ls-header-section">
            <h2>Ders Atamaları</h2>
            <p>Yeni dersler oluşturun ve öğretmenlere atayın.</p>
        </div>

        <?php Session::flash('admin_success'); ?>
        <?php Session::flash('admin_error'); ?>

        <div class="form-row">
            <!-- Add Course Form -->
            <div class="col" style="flex: 1;">
                <div class="ls-card">
                    <h3>Yeni Ders Ekle</h3>
                    <form action="<?php echo URLROOT; ?>/admin/addCourse" method="POST">
                        <div class="form-group">
                            <label>Ders Adı</label>
                            <input type="text" name="course_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Sorumlu Öğretmen</label>
                            <select name="teacher_id" class="form-control" required>
                                <option value="">Öğretmen Seçiniz...</option>
                                <?php foreach($data['teachers'] as $teacher): ?>
                                    <option value="<?php echo $teacher->id; ?>">
                                        <?php echo $teacher->username; ?> (ID: <?php echo $teacher->user_number; ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary btn-block">Dersi Oluştur ve Ata</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Course List -->
            <div class="col" style="flex: 2;">
                <div class="ls-card">
                    <h3>Mevcut Dersler</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ders Adı</th>
                                <th>Sorumlu Öğretmen</th>
                                <th>Oluşturulma Tarihi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($data['courses'])): ?>
                            <tr>
                                <td colspan="4" class="text-center">Henüz ders bulunmamaktadır.</td>
                            </tr>
                            <?php else: ?>
                                <?php foreach($data['courses'] as $course): ?>
                                <tr>
                                    <td><?php echo $course->id; ?></td>
                                    <td><strong><?php echo $course->course_name; ?></strong></td>
                                    <td><?php echo $course->teacher_name; ?></td>
                                    <td class="text-muted"><?php echo date('d.m.Y', strtotime($course->created_at)); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require '../views/layouts/footer.php'; ?>

<?php require '../views/layouts/header.php'; ?>

<div class="ls-dashboard-container">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <div class="ls-main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="content-area">
            <h1 style="margin-bottom: 24px;">Derslerim</h1>
            
            <div class="card">
                <div class="card-title">Kayıtlı Ders Listesi</div>
                <table style="width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <th style="padding: 10px;">ID</th>
                            <th style="padding: 10px;">Ders Adı</th>
                            <th style="padding: 10px;">Oluşturulma Tarihi</th>
                            <th style="padding: 10px;">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['courses'])): ?>
                            <tr>
                                <td colspan="4" style="padding: 10px; text-align: center;">Size atanmış ders bulunmuyor.</td>
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
                                    <td style="padding: 10px;"><?php echo date('d.m.Y', strtotime($course->created_at)); ?></td>
                                    <td style="padding: 10px;">
                                        <a href="<?php echo URLROOT; ?>/teacher/courseDetails/<?php echo $course->id; ?>" class="btn btn-primary" style="padding: 6px 12px; font-size: 0.85rem; width: auto; background-color: var(--accent);">Dersi Gör <i class="fa-solid fa-arrow-right"></i></a>
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

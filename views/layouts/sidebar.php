<?php // views/layouts/sidebar.php ?>
<div class="sidebar">
    <div class="sidebar-brand">
        <i class="fa-solid fa-graduation-cap" style="color: var(--accent); font-size: 1.8rem; margin-right: 10px;"></i>
        <span style="background: linear-gradient(135deg, var(--text-main), var(--primary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">QUİZBOX</span>
    </div>
    <ul class="sidebar-menu">
        <?php if ($_SESSION['user_role'] == 'admin') : ?>
            <li><a href="<?php echo URLROOT; ?>/admin/dashboard"><i class="fa-solid fa-chart-pie"></i> Yönetim Paneli</a></li>
            <li><a href="<?php echo URLROOT; ?>/admin/users"><i class="fa-solid fa-users"></i> Kullanıcı Yönetimi</a></li>
            <li><a href="<?php echo URLROOT; ?>/admin/courses"><i class="fa-solid fa-book"></i> Tüm Dersler</a></li>
            <li><a href="<?php echo URLROOT; ?>/admin/logs"><i class="fa-solid fa-shield-halved"></i> Sistem Kayıtları</a></li>
        <?php elseif ($_SESSION['user_role'] == 'teacher') : ?>
            <li><a href="<?php echo URLROOT; ?>/teacher/dashboard"><i class="fa-solid fa-chart-line"></i> <?php echo Language::get('dashboard'); ?></a></li>
            <li><a href="<?php echo URLROOT; ?>/teacher/courses"><i class="fa-solid fa-book"></i> <?php echo Language::get('my_courses'); ?></a></li>
            <li><a href="<?php echo URLROOT; ?>/teacher/quizzes"><i class="fa-solid fa-stopwatch"></i> <?php echo Language::get('quizzes'); ?></a></li>
            <li><a href="<?php echo URLROOT; ?>/teacher/questions"><i class="fa-solid fa-layer-group"></i> <?php echo Language::get('question_bank'); ?></a></li>
            <li><a href="<?php echo URLROOT; ?>/teacher/profile"><i class="fa-solid fa-user"></i> <?php echo Language::get('my_profile'); ?></a></li>
        <?php else : ?>
            <li><a href="<?php echo URLROOT; ?>/student/dashboard"><i class="fa-solid fa-house"></i> <?php echo Language::get('dashboard'); ?></a></li>
            <li><a href="<?php echo URLROOT; ?>/student/courses"><i class="fa-solid fa-compass"></i> <?php echo Language::get('browse_courses'); ?></a></li>
            <li><a href="<?php echo URLROOT; ?>/student/quizzes"><i class="fa-solid fa-gamepad"></i> <?php echo Language::get('active_quizzes'); ?></a></li>
            <li><a href="<?php echo URLROOT; ?>/student/results"><i class="fa-solid fa-square-poll-vertical"></i> <?php echo Language::get('my_results'); ?></a></li>
            <li><a href="<?php echo URLROOT; ?>/student/profile"><i class="fa-solid fa-user"></i> <?php echo Language::get('my_profile'); ?></a></li>
        <?php endif; ?>
        
                <li><a href="<?php echo URLROOT; ?>/leaderboard/index" style="color: var(--accent);"><i class="fa-solid fa-trophy"></i> <?php echo Language::get('leaderboard'); ?></a></li>
        <li style="margin-top: auto;"><a href="<?php echo URLROOT; ?>/auth/logout" style="color: var(--danger);"><i class="fa-solid fa-right-from-bracket"></i> <?php echo Language::get('logout'); ?></a></li>
    </ul>
</div>


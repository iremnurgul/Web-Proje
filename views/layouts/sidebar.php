<?php // views/layouts/sidebar.php ?>
<div class="sidebar">
    <div class="sidebar-brand">
        <i class="fa-solid fa-graduation-cap"></i>
        <span>Quiz System</span>
    </div>
    <ul class="sidebar-menu">
        <?php if ($_SESSION['user_role'] == 'admin') : ?>
            <li><a href="<?php echo URLROOT; ?>/admin/dashboard"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
            <li><a href="<?php echo URLROOT; ?>/admin/users"><i class="fa-solid fa-users"></i> Users</a></li>
            <li><a href="<?php echo URLROOT; ?>/admin/logs"><i class="fa-solid fa-shield-halved"></i> System Logs</a></li>
        <?php elseif ($_SESSION['user_role'] == 'teacher') : ?>
            <li><a href="<?php echo URLROOT; ?>/teacher/dashboard"><i class="fa-solid fa-chart-line"></i> Dashboard</a></li>
            <li><a href="<?php echo URLROOT; ?>/teacher/courses"><i class="fa-solid fa-book"></i> My Courses</a></li>
            <li><a href="<?php echo URLROOT; ?>/teacher/quizzes"><i class="fa-solid fa-stopwatch"></i> Quizzes</a></li>
            <li><a href="<?php echo URLROOT; ?>/teacher/questions"><i class="fa-solid fa-layer-group"></i> Question Bank</a></li>
        <?php else : ?>
            <li><a href="<?php echo URLROOT; ?>/student/dashboard"><i class="fa-solid fa-house"></i> Dashboard</a></li>
            <li><a href="<?php echo URLROOT; ?>/student/courses"><i class="fa-solid fa-compass"></i> Browse Courses</a></li>
            <li><a href="<?php echo URLROOT; ?>/student/quizzes"><i class="fa-solid fa-gamepad"></i> Active Quizzes</a></li>
            <li><a href="<?php echo URLROOT; ?>/student/results"><i class="fa-solid fa-square-poll-vertical"></i> My Results</a></li>
        <?php endif; ?>
        
        <li style="margin-top: 20px;"><a href="<?php echo URLROOT; ?>/leaderboard/index" style="color: var(--accent);"><i class="fa-solid fa-trophy"></i> Leaderboard</a></li>
        <li style="margin-top: auto;"><a href="<?php echo URLROOT; ?>/auth/logout" style="color: var(--danger);"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
    </ul>
</div>

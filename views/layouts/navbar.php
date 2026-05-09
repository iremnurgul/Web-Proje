<?php // views/layouts/navbar.php ?>
<div class="navbar">
        <div class="user-info">
        <i class="fa-solid fa-circle-user"></i>
        <span><?php echo Language::get('welcome'); ?>, <strong style="color: var(--primary-hover);"><?php echo htmlspecialchars($_SESSION['user_first_name'] . ' ' . $_SESSION['user_last_name']); ?></strong> 
        (<span style="color: var(--accent);"><?php 
            $role_tr = ['admin' => 'Yönetici', 'teacher' => 'Öğretmen', 'student' => 'Öğrenci'];
            echo isset($role_tr[$_SESSION['user_role']]) ? $role_tr[$_SESSION['user_role']] : ucfirst($_SESSION['user_role']); 
        ?></span>)</span>
    </div>
</div>


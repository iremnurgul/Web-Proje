<?php // views/layouts/navbar.php ?>
<div class="navbar">
    <div class="user-info">
        <i class="fa-solid fa-circle-user"></i>
        <span>Welcome, <strong style="color: var(--primary-hover);"><?php echo htmlspecialchars($_SESSION['user_username']); ?></strong> 
        (<span style="color: var(--accent);"><?php echo ucfirst($_SESSION['user_role']); ?></span>)</span>
    </div>
</div>

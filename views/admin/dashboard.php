<?php require '../views/layouts/header.php'; ?>

<div class="wrapper">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="content-area">
            <h1 style="margin-bottom: 24px;">Admin Dashboard</h1>
            
            <div class="card">
                <div class="card-title">System Overview</div>
                <p>Welcome to the admin dashboard. Here you can manage users, view logs, and monitor system performance.</p>
            </div>
            
            <div style="display: flex; gap: 20px;">
                <div class="card" style="flex: 1;">
                    <div class="card-title">Total Users</div>
                    <h2>150</h2>
                </div>
                <div class="card" style="flex: 1;">
                    <div class="card-title">Active Quizzes</div>
                    <h2>12</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require '../views/layouts/footer.php'; ?>

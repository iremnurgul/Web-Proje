<?php require '../views/layouts/header.php'; ?>

<div class="wrapper">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <div class="main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="content-area">
            <h1 style="margin-bottom: 24px;">Global Leaderboard 🏆</h1>
            
            <div class="card">
                <table style="width: 100%; text-align: left; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <th style="padding: 15px 10px;">Rank</th>
                            <th style="padding: 15px 10px;">Student</th>
                            <th style="padding: 15px 10px;">Course & Quiz</th>
                            <th style="padding: 15px 10px;">Score</th>
                            <th style="padding: 15px 10px;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data['top_scores'])): ?>
                            <tr>
                                <td colspan="5" style="padding: 15px 10px; text-align: center;">No scores available yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($data['top_scores'] as $index => $score): ?>
                                <tr style="border-bottom: 1px solid var(--border-color); <?php echo ($index < 3) ? 'background-color: rgba(107, 70, 193, 0.1); font-weight: bold;' : ''; ?>">
                                    <td style="padding: 15px 10px;">
                                        <?php 
                                            if($index == 0) echo '🥇 1st';
                                            elseif($index == 1) echo '🥈 2nd';
                                            elseif($index == 2) echo '🥉 3rd';
                                            else echo ($index + 1);
                                        ?>
                                    </td>
                                    <td style="padding: 15px 10px; color: var(--accent);"><?php echo htmlspecialchars($score->username); ?></td>
                                    <td style="padding: 15px 10px;">
                                        <div style="font-size: 0.9em;"><?php echo htmlspecialchars($score->quiz_name); ?></div>
                                        <div style="font-size: 0.8em; color: var(--text-muted);"><?php echo htmlspecialchars($score->course_name); ?></div>
                                    </td>
                                    <td style="padding: 15px 10px; color: var(--success);"><?php echo $score->score; ?>%</td>
                                    <td style="padding: 15px 10px; font-size: 0.8em;"><?php echo date('d M Y', strtotime($score->completed_at)); ?></td>
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

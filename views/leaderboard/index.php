<?php require '../views/layouts/header.php'; ?>

<div class="ls-dashboard-container">
    <?php require '../views/layouts/sidebar.php'; ?>
    
    <div class="ls-main-content">
        <?php require '../views/layouts/navbar.php'; ?>
        
        <div class="content-area">
            <h1 style="margin-bottom: 24px;">Sıralamalar (Leaderboard) 🏆</h1>
            
            <?php if(empty($data['leaderboard_data'])): ?>
                <div class="card">
                    <p style="text-align: center;">Henüz hiçbir sınava ait sıralama verisi bulunmuyor.</p>
                </div>
            <?php else: ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(400px, 1fr)); gap: 20px;">
                    <?php foreach($data['leaderboard_data'] as $board): ?>
                        <div class="card">
                            <h3 style="color: var(--primary); margin-bottom: 5px;"><?php echo htmlspecialchars($board['quiz_name']); ?></h3>
                            <p style="color: var(--text-muted); font-size: 0.9em; margin-bottom: 15px;"><?php echo htmlspecialchars($board['course_name']); ?></p>
                            
                            <table style="width: 100%; text-align: left; border-collapse: collapse;">
                                <thead>
                                    <tr style="border-bottom: 1px solid var(--border-color);">
                                        <th style="padding: 10px 5px;">Derece</th>
                                        <th style="padding: 10px 5px;">Öğrenci</th>
                                        <th style="padding: 10px 5px;">Puan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($board['scores'] as $index => $score): ?>
                                        <tr style="border-bottom: 1px solid var(--border-color); <?php echo ($index == 0) ? 'background-color: rgba(250, 204, 21, 0.1); font-weight: bold;' : ''; ?>">
                                            <td style="padding: 10px 5px;">
                                                <?php 
                                                    if($index == 0) echo '🥇 1.';
                                                    elseif($index == 1) echo '🥈 2.';
                                                    elseif($index == 2) echo '🥉 3.';
                                                    else echo ($index + 1) . '.';
                                                ?>
                                            </td>
                                            <td style="padding: 10px 5px;"><?php echo htmlspecialchars($score->first_name . ' ' . $score->last_name); ?></td>
                                            <td style="padding: 10px 5px; color: var(--success);"><?php echo $score->score; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require '../views/layouts/footer.php'; ?>

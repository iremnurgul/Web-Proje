<?php
try {
    $dbh = new PDO("mysql:host=localhost;dbname=quiz_system_db;charset=utf8mb4", "root", "");
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $dbh->query("SHOW COLUMNS FROM questions LIKE 'image_path'");
    $exists = $stmt->fetch();
    
    if (!$exists) {
        $dbh->exec("ALTER TABLE questions ADD COLUMN image_path VARCHAR(255) NULL AFTER question_text");
        echo json_encode(['success' => true, 'message' => 'image_path column added']);
    } else {
        echo json_encode(['success' => true, 'message' => 'image_path already exists']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

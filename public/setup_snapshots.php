<?php
// C:\xampp\htdocs\WEBPROJE\public\setup_snapshots.php
require_once '../app/bootstrap.php';

$db = new Database();

// 1. Create directory
$dir = __DIR__ . '/uploads/snapshots';
if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
    echo "Klasör oluşturuldu: $dir <br>";
} else {
    echo "Klasör zaten mevcut. <br>";
}

// 2. Create database table
$sql = "CREATE TABLE IF NOT EXISTS exam_snapshots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    quiz_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
)";

try {
    $db->query($sql);
    $db->execute();
    echo "exam_snapshots tablosu başarıyla oluşturuldu!<br>";
    echo "<h2>Kurulum Tamamlandı! Bu sayfayı kapatabilirsiniz.</h2>";
} catch (Exception $e) {
    echo "Veritabanı hatası: " . $e->getMessage();
}

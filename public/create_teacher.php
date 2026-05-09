<?php
// C:\xampp\htdocs\WEBPROJE\public\create_teacher.php

require_once '../app/bootstrap.php';
require_once '../app/Helpers/Security.php';

$db = new Database();

// Hash password 'Teacher123!'
$hashedPassword = Security::hashPassword('Teacher123!');

$db->query('INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)');
$db->bind(':username', 'teacher_1');
$db->bind(':email', 'teacher@quizsystem.local');
$db->bind(':password', $hashedPassword);
$db->bind(':role', 'teacher');

try {
    $db->execute();
    echo "<h1>Öğretmen Hesabı Başarıyla Oluşturuldu!</h1>";
    echo "<p>Artık şu bilgilerle giriş yapabilirsiniz:</p>";
    echo "<ul>";
    echo "<li><strong>Email:</strong> teacher@quizsystem.local</li>";
    echo "<li><strong>Şifre:</strong> Teacher123!</li>";
    echo "</ul>";
    echo "<a href='http://localhost/WEBPROJE/public/auth/login'>Giriş Yap</a>";
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage() . "<br>Muhtemelen hesap zaten mevcut.";
}

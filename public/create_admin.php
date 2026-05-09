<?php
require_once __DIR__ . '/../app/bootstrap.php';

$db = new Database();
$db->query('INSERT INTO users (first_name, last_name, username, email, password, role, user_number) VALUES (:fn, :ln, :un, :em, :pass, :role, :num)');
$db->bind(':fn', 'Sistem');
$db->bind(':ln', 'Yöneticisi');
$db->bind(':un', 'admin');
$db->bind(':em', 'admin@learnsphere.com');
$db->bind(':pass', password_hash('admin123', PASSWORD_DEFAULT));
$db->bind(':role', 'admin');
$db->bind(':num', '9999');

if($db->execute()) {
    echo "Admin created successfully!";
} else {
    echo "Failed to create admin!";
}

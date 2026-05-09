<?php
require_once __DIR__ . '/../app/bootstrap.php';

$db = new Database();
$db->query("UPDATE users SET user_number = '9999', password = :pass, role = 'admin' WHERE username = 'admin' OR role = 'admin'");
$db->bind(':pass', password_hash('admin123', PASSWORD_DEFAULT));

if($db->execute()) {
    echo "Admin updated successfully!";
} else {
    echo "Failed to update admin!";
}

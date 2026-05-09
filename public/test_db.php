<?php
require_once '../app/Core/Database.php';
$db = new Database();
$db->query("DESCRIBE questions");
echo json_encode($db->resultSet());

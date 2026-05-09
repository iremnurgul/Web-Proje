<?php
require_once __DIR__ . '/../app/bootstrap.php';

$db = new Database();

// Let's just drop the database and recreate it to be clean
try {
    $db->query("DROP DATABASE IF EXISTS quiz_system_db"); $db->execute();
    $db->query("CREATE DATABASE quiz_system_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci"); $db->execute();
    $db->query("USE quiz_system_db"); $db->execute();
    
    // Read schema.sql and execute
    $sql = file_get_contents(__DIR__ . '/../database/schema.sql');
    // Basic split by ; is risky, but since it's a standard dump, let's use mysql cli instead via exec.
} catch (Exception $e) {
    echo "Error recreating db: " . $e->getMessage() . "\n";
}

// Wait, we'll execute schema via shell
$command = 'c:\xampp\mysql\bin\mysql.exe -u root quiz_system_db < ' . escapeshellarg(__DIR__ . '/../database/schema.sql');
exec($command, $output, $return_var);
if ($return_var !== 0) {
    die("Error importing schema.sql");
}
echo "Schema imported.\n";

$passHash = password_hash('password123', PASSWORD_DEFAULT);

// Seed Admin
$db->query("INSERT INTO users (first_name, last_name, user_number, username, email, password, role) VALUES ('Admin', 'Sistem', 9999, 'admin', 'admin@learnsphere.local', :pass, 'admin')");
$db->bind(':pass', $passHash);
$db->execute();

// Seed Teachers
$teachers = [
    ['Ahmet', 'Yılmaz', 1001, 'teacher1', 'ahmet@learnsphere.local'],
    ['Ayşe', 'Demir', 1002, 'teacher2', 'ayse@learnsphere.local'],
    ['Mehmet', 'Kaya', 1003, 'teacher3', 'mehmet@learnsphere.local']
];

$teacherIds = [];
foreach ($teachers as $t) {
    $db->query("INSERT INTO users (first_name, last_name, user_number, username, email, password, role) VALUES (:fn, :ln, :un, :usr, :em, :pass, 'teacher')");
    $db->bind(':fn', $t[0]);
    $db->bind(':ln', $t[1]);
    $db->bind(':un', $t[2]);
    $db->bind(':usr', $t[3]);
    $db->bind(':em', $t[4]);
    $db->bind(':pass', $passHash);
    $db->execute();
    $teacherIds[] = $db->lastInsertId();
}

// Seed Students
$students = [
    ['Ali', 'Can', 2024001, 'student1', 'ali@learnsphere.local'],
    ['Zeynep', 'Çelik', 2024002, 'student2', 'zeynep@learnsphere.local'],
    ['Mert', 'Şahin', 2024003, 'student3', 'mert@learnsphere.local']
];

$studentIds = [];
foreach ($students as $s) {
    $db->query("INSERT INTO users (first_name, last_name, user_number, username, email, password, role) VALUES (:fn, :ln, :un, :usr, :em, :pass, 'student')");
    $db->bind(':fn', $s[0]);
    $db->bind(':ln', $s[1]);
    $db->bind(':un', $s[2]);
    $db->bind(':usr', $s[3]);
    $db->bind(':em', $s[4]);
    $db->bind(':pass', $passHash);
    $db->execute();
    $studentIds[] = $db->lastInsertId();
}

// Seed Courses
$db->query("INSERT INTO courses (course_name, teacher_id) VALUES ('Matematik 101', :t1), ('Fizik 101', :t1), ('Kimya 101', :t2), ('Biyoloji 101', :t2), ('Tarih 101', :t3)");
$db->bind(':t1', $teacherIds[0]);
$db->bind(':t2', $teacherIds[1]);
$db->bind(':t3', $teacherIds[2]);
$db->execute();

$db->query("SELECT id FROM courses");
$courses = $db->resultSet();
$cMat101 = $courses[0]->id;
$cFiz101 = $courses[1]->id;
$cKim101 = $courses[2]->id;
$cBiy101 = $courses[3]->id;
$cTar101 = $courses[4]->id;

// Enrollments
// Teacher 1 -> 2 courses (Mat, Fiz) -> all 3 students
foreach ($studentIds as $sid) {
    $db->query("INSERT INTO enrollments (course_id, student_id) VALUES (:cid, :sid)");
    $db->bind(':cid', $cMat101); $db->bind(':sid', $sid); $db->execute();
    $db->query("INSERT INTO enrollments (course_id, student_id) VALUES (:cid, :sid)");
    $db->bind(':cid', $cFiz101); $db->bind(':sid', $sid); $db->execute();
}

// Teacher 2 -> 2 courses (Kim, Biy) -> 2 students (student 1 and 2)
for ($i = 0; $i < 2; $i++) {
    $db->query("INSERT INTO enrollments (course_id, student_id) VALUES (:cid, :sid)");
    $db->bind(':cid', $cKim101); $db->bind(':sid', $studentIds[$i]); $db->execute();
    $db->query("INSERT INTO enrollments (course_id, student_id) VALUES (:cid, :sid)");
    $db->bind(':cid', $cBiy101); $db->bind(':sid', $studentIds[$i]); $db->execute();
}

// Teacher 3 -> 1 course (Tar) -> 1 student (student 3)
$db->query("INSERT INTO enrollments (course_id, student_id) VALUES (:cid, :sid)");
$db->bind(':cid', $cTar101); $db->bind(':sid', $studentIds[2]); $db->execute();

echo "Database successfully seeded.\n";

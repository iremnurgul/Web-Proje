<?php
// app/Controllers/Admin.php

require_once '../app/Helpers/Middleware.php';

class Admin extends Controller {
    
    public function __construct() {
        // Enforce Admin Role
        Middleware::admin();
    }

    public function index() {
        $this->dashboard();
    }

    public function dashboard() {
        $db = new Database();
        
        // Statistics
        $db->query('SELECT COUNT(*) as count FROM users WHERE role = "student"');
        $student_count = $db->single()->count;

        $db->query('SELECT COUNT(*) as count FROM users WHERE role = "teacher"');
        $teacher_count = $db->single()->count;

        $db->query('SELECT COUNT(*) as count FROM courses');
        $course_count = $db->single()->count;

        $db->query('SELECT COUNT(*) as count FROM quizzes');
        $quiz_count = $db->single()->count;

        // Recent users
        $db->query('SELECT * FROM users ORDER BY created_at DESC LIMIT 5');
        $recent_users = $db->resultSet();

        $data = [
            'stats' => [
                'students' => $student_count,
                'teachers' => $teacher_count,
                'courses' => $course_count,
                'quizzes' => $quiz_count
            ],
            'recent_users' => $recent_users
        ];
        
        $this->view('admin/dashboard', $data);
    }

    public function users() {
        $db = new Database();
        $db->query('SELECT * FROM users ORDER BY created_at DESC');
        $users = $db->resultSet();
        
        $data = [
            'users' => $users
        ];
        
        $this->view('admin/users', $data);
    }

    public function courses() {
        $db = new Database();
        $db->query('
            SELECT c.*, u.username as teacher_name 
            FROM courses c 
            LEFT JOIN users u ON c.teacher_id = u.id 
            ORDER BY c.created_at DESC
        ');
        $courses = $db->resultSet();

        $db->query('SELECT id, username, user_number FROM users WHERE role = "teacher"');
        $teachers = $db->resultSet();
        
        $data = [
            'courses' => $courses,
            'teachers' => $teachers
        ];
        
        $this->view('admin/courses', $data);
    }

    public function addCourse() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $course_name = trim($_POST['course_name']);
            $teacher_id = trim($_POST['teacher_id']);

            $db = new Database();
            $db->query('INSERT INTO courses (course_name, teacher_id) VALUES (:course_name, :teacher_id)');
            $db->bind(':course_name', $course_name);
            $db->bind(':teacher_id', $teacher_id);
            
            if ($db->execute()) {
                Session::flash('admin_success', 'Ders başarıyla oluşturuldu ve öğretmene atandı.');
            } else {
                Session::flash('admin_error', 'Ders oluşturulurken bir hata oluştu.');
            }
            header('Location: ' . URLROOT . '/admin/courses');
            exit;
        }
    }
}

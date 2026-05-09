<?php
// app/Controllers/Leaderboard.php

require_once '../app/Helpers/Middleware.php';

class Leaderboard extends Controller {
    
    private $db;

    public function __construct() {
        Middleware::auth(); // Anyone logged in can see the leaderboard
        $this->db = new Database();
    }

    public function index() {
        if ($_SESSION['user_role'] == 'teacher') {
            // Sadece öğretmenin kendi derslerine ait aktif sınavları al
            $this->db->query('
                SELECT q.id, q.quiz_name, q.course_id 
                FROM quizzes q
                JOIN courses c ON q.course_id = c.id
                WHERE q.is_active = 1 AND c.teacher_id = :teacher_id
            ');
            $this->db->bind(':teacher_id', $_SESSION['user_id']);
        } else {
            // Admin ve Öğrenci için tüm aktif sınavları al
            $this->db->query('SELECT id, quiz_name, course_id FROM quizzes WHERE is_active = 1');
        }
        
        $quizzes = $this->db->resultSet();

        $leaderboard_data = [];

        foreach ($quizzes as $quiz) {
            $this->db->query('
                SELECT r.score, r.completed_at, u.first_name, u.last_name
                FROM results r
                JOIN users u ON r.student_id = u.id
                WHERE r.quiz_id = :quiz_id
                ORDER BY r.score DESC, r.completed_at ASC
                LIMIT 10
            ');
            $this->db->bind(':quiz_id', $quiz->id);
            $scores = $this->db->resultSet();

            if (!empty($scores)) {
                $this->db->query('SELECT course_name FROM courses WHERE id = :course_id');
                $this->db->bind(':course_id', $quiz->course_id);
                $course = $this->db->single();

                $leaderboard_data[] = [
                    'quiz_name' => $quiz->quiz_name,
                    'course_name' => $course->course_name,
                    'scores' => $scores
                ];
            }
        }

        $data = [
            'title' => 'Sıralamalar',
            'leaderboard_data' => $leaderboard_data
        ];
        
        $this->view('leaderboard/index', $data);
    }
}

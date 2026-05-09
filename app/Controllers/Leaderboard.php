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
        // Get top 10 scores globally across all quizzes
        $this->db->query('
            SELECT r.score, r.completed_at, u.username, q.quiz_name, c.course_name
            FROM results r
            JOIN users u ON r.student_id = u.id
            JOIN quizzes q ON r.quiz_id = q.id
            JOIN courses c ON q.course_id = c.id
            ORDER BY r.score DESC, r.completed_at ASC
            LIMIT 20
        ');
        
        $top_scores = $this->db->resultSet();

        $data = [
            'title' => 'Global Leaderboard',
            'top_scores' => $top_scores
        ];
        
        $this->view('leaderboard/index', $data);
    }
}

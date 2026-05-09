<?php
// app/Models/Quiz.php

class Quiz {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get quizzes by teacher (by joining courses)
    public function getQuizzesByTeacher($teacher_id) {
        $this->db->query('SELECT q.*, c.course_name FROM quizzes q JOIN courses c ON q.course_id = c.id WHERE c.teacher_id = :teacher_id ORDER BY q.created_at DESC');
        $this->db->bind(':teacher_id', $teacher_id);
        return $this->db->resultSet();
    }

    // Add Quiz
    public function addQuiz($data) {
        $this->db->query('INSERT INTO quizzes (course_id, quiz_name, start_date, end_date, duration, is_active) VALUES (:course_id, :quiz_name, :start_date, :end_date, :duration, :is_active)');
        $this->db->bind(':course_id', $data['course_id']);
        $this->db->bind(':quiz_name', $data['quiz_name']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':duration', $data['duration']);
        $this->db->bind(':is_active', $data['is_active']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Get Quiz By ID
    public function getQuizById($id) {
        $this->db->query('SELECT * FROM quizzes WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Active Quizzes for Students (based on course enrollment)
    public function getActiveQuizzesForStudent($student_id) {
        $this->db->query('
            SELECT q.*, c.course_name 
            FROM quizzes q 
            JOIN courses c ON q.course_id = c.id 
            JOIN enrollments e ON c.id = e.course_id 
            WHERE e.student_id = :student_id 
            AND q.is_active = 1 
            AND q.id NOT IN (SELECT quiz_id FROM results WHERE student_id = :student_id)
            ORDER BY q.end_date ASC
        ');
        $this->db->bind(':student_id', $student_id);
        return $this->db->resultSet();
    }
}

<?php
// app/Models/Question.php

class Question {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get questions by quiz ID
    public function getQuestionsByQuiz($quiz_id) {
        $this->db->query('SELECT * FROM questions WHERE quiz_id = :quiz_id');
        $this->db->bind(':quiz_id', $quiz_id);
        return $this->db->resultSet();
    }

    // Add Question
    public function addQuestion($data) {
        $this->db->query('INSERT INTO questions (quiz_id, question_text, image_path, type, option_a, option_b, option_c, option_d, correct_answer, points) VALUES (:quiz_id, :question_text, :image_path, :type, :option_a, :option_b, :option_c, :option_d, :correct_answer, :points)');
        
        $this->db->bind(':quiz_id', $data['quiz_id']);
        $this->db->bind(':question_text', $data['question_text']);
        $this->db->bind(':image_path', isset($data['image_path']) ? $data['image_path'] : null);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':option_a', $data['option_a']);
        $this->db->bind(':option_b', $data['option_b']);
        $this->db->bind(':option_c', $data['option_c']);
        $this->db->bind(':option_d', $data['option_d']);
        $this->db->bind(':correct_answer', $data['correct_answer']);
        $this->db->bind(':points', $data['points'] ?? 10);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

<?php
// app/Models/Result.php

class Result {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Add or update an attempt
    public function logAttempt($student_id, $quiz_id, $ip_address, $user_agent) {
        // Check if attempt exists
        $this->db->query('SELECT * FROM attempts WHERE student_id = :student_id AND quiz_id = :quiz_id');
        $this->db->bind(':student_id', $student_id);
        $this->db->bind(':quiz_id', $quiz_id);
        $row = $this->db->single();

        if ($row) {
            // Update attempt count
            $this->db->query('UPDATE attempts SET attempt_count = attempt_count + 1, last_activity = CURRENT_TIMESTAMP WHERE id = :id');
            $this->db->bind(':id', $row->id);
            $this->db->execute();
        } else {
            // Insert new attempt
            $this->db->query('INSERT INTO attempts (student_id, quiz_id, attempt_count, ip_address, user_agent) VALUES (:student_id, :quiz_id, 1, :ip, :ua)');
            $this->db->bind(':student_id', $student_id);
            $this->db->bind(':quiz_id', $quiz_id);
            $this->db->bind(':ip', $ip_address);
            $this->db->bind(':ua', $user_agent);
            $this->db->execute();
        }
    }

    // Save final result
    public function saveResult($student_id, $quiz_id, $score) {
        // Check if already completed
        $this->db->query('SELECT * FROM results WHERE student_id = :student_id AND quiz_id = :quiz_id');
        $this->db->bind(':student_id', $student_id);
        $this->db->bind(':quiz_id', $quiz_id);
        
        if ($this->db->rowCount() > 0) {
            return false; // Already submitted
        }

        $this->db->query('INSERT INTO results (student_id, quiz_id, score, status, completed_at) VALUES (:student_id, :quiz_id, :score, "completed", CURRENT_TIMESTAMP)');
        $this->db->bind(':student_id', $student_id);
        $this->db->bind(':quiz_id', $quiz_id);
        $this->db->bind(':score', $score);
        
        return $this->db->execute();
    }

    // Save individual student answer
    public function saveAnswer($result_id, $question_id, $answer, $is_correct) {
        $this->db->query('INSERT INTO student_answers (result_id, question_id, student_answer, is_correct) VALUES (:result_id, :question_id, :answer, :is_correct)');
        $this->db->bind(':result_id', $result_id);
        $this->db->bind(':question_id', $question_id);
        $this->db->bind(':answer', $answer);
        $this->db->bind(':is_correct', $is_correct ? 1 : 0);
        return $this->db->execute();
    }

    // Check if student has completed a quiz
    public function hasCompleted($student_id, $quiz_id) {
        $this->db->query('SELECT id FROM results WHERE student_id = :student_id AND quiz_id = :quiz_id');
        $this->db->bind(':student_id', $student_id);
        $this->db->bind(':quiz_id', $quiz_id);
        return $this->db->rowCount() > 0;
    }
}

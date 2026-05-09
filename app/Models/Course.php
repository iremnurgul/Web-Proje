<?php
// app/Models/Course.php

class Course {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all courses for a specific teacher
    public function getCoursesByTeacher($teacher_id) {
        $this->db->query('SELECT * FROM courses WHERE teacher_id = :teacher_id ORDER BY created_at DESC');
        $this->db->bind(':teacher_id', $teacher_id);
        return $this->db->resultSet();
    }

    // Get all courses (for admin or student listing)
    public function getAllCourses() {
        $this->db->query('SELECT c.*, u.username as teacher_name FROM courses c JOIN users u ON c.teacher_id = u.id ORDER BY c.created_at DESC');
        return $this->db->resultSet();
    }

    // Add Course
    public function addCourse($data) {
        $this->db->query('INSERT INTO courses (course_name, teacher_id) VALUES (:course_name, :teacher_id)');
        $this->db->bind(':course_name', $data['course_name']);
        $this->db->bind(':teacher_id', $data['teacher_id']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Delete Course
    public function deleteCourse($id, $teacher_id) {
        // Ensure teacher owns the course or is admin
        $this->db->query('DELETE FROM courses WHERE id = :id AND teacher_id = :teacher_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':teacher_id', $teacher_id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Get Course By ID
    public function getCourseById($id) {
        $this->db->query('SELECT * FROM courses WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}

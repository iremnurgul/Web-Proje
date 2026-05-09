<?php
// app/Models/User.php

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Register User
    public function register($data) {
        $this->db->query('INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)');
        // Bind values
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', 'student'); // Default role is student

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Login User
    public function login($email, $password) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        if ($row) {
            $hashedPassword = $row->password;
            if (Security::verifyPassword($password, $hashedPassword)) {
                // Update last login
                $this->updateLastLogin($row->id);
                return $row;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Update Last Login Time
    private function updateLastLogin($id) {
        $this->db->query('UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();
    }

    // Find user by email
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Check row
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Find user by username
    public function findUserByUsername($username) {
        $this->db->query('SELECT * FROM users WHERE username = :username');
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        // Check row
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // Get User By ID
    public function getUserById($id) {
        $this->db->query('SELECT id, username, email, role, created_at FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        
        $row = $this->db->single();
        return $row;
    }
}

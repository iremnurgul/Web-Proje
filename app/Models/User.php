<?php
// app/Models/User.php

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Register User
    public function register($data) {
        $this->db->query('INSERT INTO users (first_name, last_name, user_number, username, email, password, role) VALUES (:first_name, :last_name, :user_number, :username, :email, :password, :role)');
        
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':user_number', $data['user_number']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', 'student'); // Default role is student

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Login User
    public function login($user_number, $password) {
        $this->db->query('SELECT * FROM users WHERE user_number = :user_number');
        $this->db->bind(':user_number', $user_number);

        $row = $this->db->single();

        if ($row) {
            $hashedPassword = $row->password;
            if (Security::verifyPassword($password, $hashedPassword)) {
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

        if ($this->db->rowCount() > 0) {
            return $row; // Return object if needed, or true
        } else {
            return false;
        }
    }

    // Find user by username
    public function findUserByUsername($username) {
        $this->db->query('SELECT * FROM users WHERE username = :username');
        $this->db->bind(':username', $username);
        $row = $this->db->single();

        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Find user by User Number
    public function findUserByUserNumber($user_number) {
        $this->db->query('SELECT * FROM users WHERE user_number = :user_number');
        $this->db->bind(':user_number', $user_number);
        $row = $this->db->single();

        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // Get User By ID
    public function getUserById($id) {
        $this->db->query('SELECT id, first_name, last_name, user_number, username, email, role, created_at FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        
        $row = $this->db->single();
        return $row;
    }

    // Update Profile
    public function updateProfile($data) {
        if(!empty($data['password'])) {
            $this->db->query('UPDATE users SET first_name = :fn, last_name = :ln, email = :em, password = :pwd WHERE id = :id');
            $this->db->bind(':pwd', $data['password']);
        } else {
            $this->db->query('UPDATE users SET first_name = :fn, last_name = :ln, email = :em WHERE id = :id');
        }
        $this->db->bind(':fn', $data['first_name']);
        $this->db->bind(':ln', $data['last_name']);
        $this->db->bind(':em', $data['email']);
        $this->db->bind(':id', $data['id']);

        return $this->db->execute();
    }

    // Create Password Reset Token
    public function createPasswordResetToken($email, $token, $expires) {
        $this->db->query('UPDATE users SET reset_token = :token, reset_expires = :expires WHERE email = :email');
        $this->db->bind(':token', $token);
        $this->db->bind(':expires', $expires);
        $this->db->bind(':email', $email);
        return $this->db->execute();
    }

    // Find User by Reset Token
    public function findUserByResetToken($token) {
        $this->db->query('SELECT * FROM users WHERE reset_token = :token AND reset_expires > NOW()');
        $this->db->bind(':token', $token);
        $row = $this->db->single();
        if($this->db->rowCount() > 0){
            return $row;
        }
        return false;
    }

    // Reset Password
    public function resetPassword($id, $newPassword) {
        $this->db->query('UPDATE users SET password = :password, reset_token = NULL, reset_expires = NULL WHERE id = :id');
        $this->db->bind(':password', $newPassword);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}

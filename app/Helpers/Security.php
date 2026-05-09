<?php
// app/Helpers/Security.php

class Security {
    
    // Generate CSRF Token
    public static function generateCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // Verify CSRF Token
    public static function verifyCsrfToken($token) {
        if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
            return true;
        }
        return false;
    }

    // Sanitize Input (XSS protection)
    public static function sanitize($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitize($value);
            }
        } else {
            $data = htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }

    // Hash Password
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    }

    // Verify Password
    public static function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }
}

<?php
// app/Helpers/Middleware.php

class Middleware {
    
    // Check if user is logged in
    public static function auth() {
        if (!Session::isLoggedIn()) {
            Session::flash('auth_error', 'Please log in to access this page', 'danger');
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }
    }

    // Check if user is admin
    public static function admin() {
        self::auth();
        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: ' . URLROOT . '/student/dashboard'); // Redirect to safe place
            exit;
        }
    }

    // Check if user is teacher
    public static function teacher() {
        self::auth();
        if ($_SESSION['user_role'] !== 'teacher' && $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . URLROOT . '/student/dashboard');
            exit;
        }
    }

    // Check if user is student
    public static function student() {
        self::auth();
        if ($_SESSION['user_role'] !== 'student') {
            // Students only area
        }
    }
}

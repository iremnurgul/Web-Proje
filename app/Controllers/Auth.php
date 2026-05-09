<?php
// app/Controllers/Auth.php

require_once '../app/Helpers/Security.php';

class Auth extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('User');
    }

    public function index() {
        // Redirect to login
        header('Location: ' . URLROOT . '/auth/login');
    }

    public function register() {
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Validate CSRF
            if (!Security::verifyCsrfToken($_POST['csrf_token'])) {
                die('CSRF token validation failed');
            }

            // Sanitize POST data
            $_POST = Security::sanitize($_POST);

            // Init data
            $data = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } else {
                if ($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email is already taken';
                }
            }

            // Validate Username
            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            } else {
                if ($this->userModel->findUserByUsername($data['username'])) {
                    $data['username_err'] = 'Username is already taken';
                }
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            // Validate Confirm Password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['username_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Validated
                
                // Hash Password
                $data['password'] = Security::hashPassword($data['password']);

                // Register User
                if ($this->userModel->register($data)) {
                    Session::flash('register_success', 'You are registered and can log in');
                    header('Location: ' . URLROOT . '/auth/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('auth/register', $data);
            }

        } else {
            // Init data
            $data = [
                'username' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Load view
            $this->view('auth/register', $data);
        }
    }

    public function login() {
        // Check if logged in
        if (Session::isLoggedIn()) {
            $this->redirectBasedOnRole($_SESSION['user_role']);
        }

        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // Validate CSRF
            if (!Security::verifyCsrfToken($_POST['csrf_token'])) {
                die('CSRF token validation failed');
            }

            // Sanitize POST data
            $_POST = Security::sanitize($_POST);

            // Init data
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }

            // Check for user/email
            if ($this->userModel->findUserByEmail($data['email'])) {
                // User found
            } else {
                $data['email_err'] = 'No user found';
            }

            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['password_err'])) {
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if ($loggedInUser) {
                    // Create Session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password incorrect';
                    $this->view('auth/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('auth/login', $data);
            }

        } else {
            // Init data
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            ];

            // Load view
            $this->view('auth/login', $data);
        }
    }

    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_username'] = $user->username;
        $_SESSION['user_role'] = $user->role;

        $this->redirectBasedOnRole($user->role);
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_username']);
        unset($_SESSION['user_role']);
        session_destroy();
        header('Location: ' . URLROOT . '/auth/login');
    }

    private function redirectBasedOnRole($role) {
        if ($role === 'admin') {
            header('Location: ' . URLROOT . '/admin/dashboard');
        } elseif ($role === 'teacher') {
            header('Location: ' . URLROOT . '/teacher/dashboard');
        } else {
            header('Location: ' . URLROOT . '/student/dashboard');
        }
    }
}

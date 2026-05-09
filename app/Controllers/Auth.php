<?php
// app/Controllers/Auth.php

require_once '../app/Helpers/Security.php';

class Auth extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('User');
    }

    public function index() {
        header('Location: ' . URLROOT . '/auth/login');
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!Security::verifyCsrfToken($_POST['csrf_token'])) {
                die('CSRF token validation failed');
            }

            $_POST = Security::sanitize($_POST);

            $data = [
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'user_number' => trim($_POST['user_number']),
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'first_name_err' => '',
                'last_name_err' => '',
                'user_number_err' => '',
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            if (empty($data['first_name'])) $data['first_name_err'] = 'Lütfen adınızı girin';
            if (empty($data['last_name'])) $data['last_name_err'] = 'Lütfen soyadınızı girin';
            
            if (empty($data['user_number'])) {
                $data['user_number_err'] = 'Lütfen okul/kullanıcı numaranızı girin';
            } elseif (!is_numeric($data['user_number'])) {
                $data['user_number_err'] = 'Numara sadece rakamlardan oluşmalıdır';
            } else {
                if ($this->userModel->findUserByUserNumber($data['user_number'])) {
                    $data['user_number_err'] = 'Bu numara zaten kayıtlı';
                }
            }

            if (empty($data['email'])) {
                $data['email_err'] = 'Lütfen E-posta girin';
            } else {
                if ($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Bu e-posta zaten kayıtlı';
                }
            }

            if (empty($data['username'])) {
                $data['username_err'] = 'Lütfen kullanıcı adı girin';
            } else {
                if ($this->userModel->findUserByUsername($data['username'])) {
                    $data['username_err'] = 'Kullanıcı adı alınmış';
                }
            }

            if (empty($data['password'])) {
                $data['password_err'] = 'Lütfen şifre girin';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Şifre en az 6 karakter olmalı';
            }

            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Lütfen şifreyi doğrulayın';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Şifreler eşleşmiyor';
                }
            }

            if (empty($data['first_name_err']) && empty($data['last_name_err']) && empty($data['user_number_err']) && empty($data['email_err']) && empty($data['username_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                
                $data['password'] = Security::hashPassword($data['password']);

                if ($this->userModel->register($data)) {
                    Session::flash('register_success', 'Kayıt başarılı, giriş yapabilirsiniz.');
                    header('Location: ' . URLROOT . '/auth/login');
                } else {
                    die('Bir şeyler yanlış gitti');
                }
            } else {
                $this->view('auth/register', $data);
            }

        } else {
            $data = [
                'first_name' => '', 'last_name' => '', 'user_number' => '', 'username' => '', 'email' => '', 'password' => '', 'confirm_password' => '',
                'first_name_err' => '', 'last_name_err' => '', 'user_number_err' => '', 'username_err' => '', 'email_err' => '', 'password_err' => '', 'confirm_password_err' => ''
            ];
            $this->view('auth/register', $data);
        }
    }

    public function login() {
        if (Session::isLoggedIn()) {
            $this->redirectBasedOnRole($_SESSION['user_role']);
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!Security::verifyCsrfToken($_POST['csrf_token'])) {
                die('CSRF token validation failed');
            }

            $_POST = Security::sanitize($_POST);

            $data = [
                'user_number' => trim($_POST['user_number']),
                'password' => trim($_POST['password']),
                'user_number_err' => '',
                'password_err' => ''
            ];

            if (empty($data['user_number'])) {
                $data['user_number_err'] = 'Lütfen numaranızı girin';
            }
            if (empty($data['password'])) {
                $data['password_err'] = 'Lütfen şifrenizi girin';
            }

            if ($this->userModel->findUserByUserNumber($data['user_number'])) {
                // Found
            } else {
                $data['user_number_err'] = 'Bu numaraya ait kullanıcı bulunamadı';
            }

            if (empty($data['user_number_err']) && empty($data['password_err'])) {
                $loggedInUser = $this->userModel->login($data['user_number'], $data['password']);

                if ($loggedInUser) {
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Şifre hatalı';
                    $this->view('auth/login', $data);
                }
            } else {
                $this->view('auth/login', $data);
            }

        } else {
            $data = [
                'user_number' => '',
                'password' => '',
                'user_number_err' => '',
                'password_err' => ''
            ];
            $this->view('auth/login', $data);
        }
    }

    public function forgot_password() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = Security::sanitize($_POST);
            $email = trim($_POST['email']);
            
            $user = $this->userModel->findUserByEmail($email);
            if($user) {
                // Şifre sıfırlama token oluştur
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                if($this->userModel->createPasswordResetToken($email, $token, $expires)){
                    // Mail gönderimi simülasyonu (log dosyasına veya ekrana uyarı)
                    $resetLink = URLROOT . '/auth/reset_password?token=' . $token;
                    
                    // TODO: Gerçek bir SMTP e-posta gönderimi eklenebilir. Şimdilik session ile link veriyoruz.
                    Session::flash('register_success', 'Şifre sıfırlama linkiniz oluşturuldu. (Simülasyon için link: <a href="'.$resetLink.'">Tıklayın</a>)');
                    header('Location: ' . URLROOT . '/auth/login');
                    exit;
                }
            } else {
                Session::flash('register_error', 'Bu e-posta adresiyle kayıtlı kullanıcı bulunamadı.');
            }
            
            $this->view('auth/forgot_password', ['email' => $email]);
        } else {
            $this->view('auth/forgot_password');
        }
    }

    public function reset_password() {
        if(!isset($_GET['token']) && !isset($_POST['token'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        $token = isset($_GET['token']) ? $_GET['token'] : $_POST['token'];
        $user = $this->userModel->findUserByResetToken($token);

        if(!$user) {
            Session::flash('register_error', 'Geçersiz veya süresi dolmuş token.');
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = Security::sanitize($_POST);
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);

            if(empty($password) || strlen($password) < 6) {
                $data = ['token' => $token, 'password_err' => 'Şifre en az 6 karakter olmalı'];
                $this->view('auth/reset_password', $data);
                return;
            }

            if($password !== $confirm_password) {
                $data = ['token' => $token, 'confirm_password_err' => 'Şifreler eşleşmiyor'];
                $this->view('auth/reset_password', $data);
                return;
            }

            $hashedPassword = Security::hashPassword($password);
            if($this->userModel->resetPassword($user->id, $hashedPassword)) {
                Session::flash('register_success', 'Şifreniz başarıyla sıfırlandı. Yeni şifrenizle giriş yapabilirsiniz.');
                header('Location: ' . URLROOT . '/auth/login');
            } else {
                die("Bir hata oluştu.");
            }
        } else {
            $this->view('auth/reset_password', ['token' => $token]);
        }
    }

    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_username'] = $user->username;
        $_SESSION['user_first_name'] = $user->first_name;
        $_SESSION['user_last_name'] = $user->last_name;
        $_SESSION['user_role'] = $user->role;

        $this->redirectBasedOnRole($user->role);
    }

    public function logout() {
        session_unset();
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

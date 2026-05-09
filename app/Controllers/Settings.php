<?php

class Settings extends Controller {
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URLROOT . '/auth/login');
            exit;
        }
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['language'])) {
                $_SESSION['lang'] = $_POST['language'];
            }
            if (isset($_POST['theme'])) {
                $_SESSION['theme'] = $_POST['theme'];
            }
            
            Session::flash('settings_success', Language::get('settings') . ' güncellendi / updated.');
            header('Location: ' . URLROOT . '/settings/index');
            exit;
        }

        $data = [
            'current_lang' => isset($_SESSION['lang']) ? $_SESSION['lang'] : 'tr',
            'current_theme' => isset($_SESSION['theme']) ? $_SESSION['theme'] : 'dark'
        ];

        $this->view('settings/index', $data);
    }
}

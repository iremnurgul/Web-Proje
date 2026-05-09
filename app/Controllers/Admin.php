<?php
// app/Controllers/Admin.php

require_once '../app/Helpers/Middleware.php';

class Admin extends Controller {
    
    public function __construct() {
        // Enforce Admin Role
        Middleware::admin();
    }

    public function index() {
        $this->dashboard();
    }

    public function dashboard() {
        $data = [
            'title' => 'Admin Dashboard'
        ];
        $this->view('admin/dashboard', $data);
    }
}

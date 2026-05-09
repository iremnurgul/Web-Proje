<?php
// app/Core/Controller.php

class Controller {
    // Load model
    public function model($model) {
        require_once '../app/Models/' . $model . '.php';
        return new $model();
    }

    // Load view
    public function view($view, $data = []) {
        if (file_exists('../views/' . $view . '.php')) {
            require_once '../views/' . $view . '.php';
        } else {
            die('View does not exist: ' . $view);
        }
    }
}

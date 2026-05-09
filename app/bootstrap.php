<?php
// app/bootstrap.php

// Load Config
require_once 'Config/config.php';

// Load Core Libraries
require_once 'Core/Session.php';
require_once 'Core/Database.php';
require_once 'Core/Controller.php';
require_once 'Core/Router.php';

// Initialize Session
Session::init();

// Load Language
require_once 'Helpers/Language.php';
Language::load();

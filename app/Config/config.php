<?php
// app/Config/config.php

// Basit bir .env okuyucu fonksiyon
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value, ' "');
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// .env dosyasını yükle
$envPath = dirname(dirname(__DIR__)) . '/.env';
loadEnv($envPath);

// Sabitleri Tanımla
define('APPROOT', dirname(dirname(__FILE__)));
define('URLROOT', getenv('APP_URL') ?: 'http://localhost/WEBPROJE/public');
define('SITENAME', getenv('APP_NAME') ?: 'Online Quiz System');

// Veritabanı Ayarları
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'quiz_system_db');

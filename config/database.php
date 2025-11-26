<?php
date_default_timezone_set("Asia/Jakarta");

$host   = 'localhost';
$user   = 'root';
$pass   = '';
$dbname = 'inventory-apps';

try {
    $config = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $config->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $config->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $pdo = $config;
} catch (PDOException $e) {
    die('Connection Failed: ' . $e->getMessage());
}

spl_autoload_register(function ($class_name) {
    $file = __DIR__ . '/../models/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

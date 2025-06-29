<?php

date_default_timezone_set("Asia/Jakarta");
error_reporting(0);

$host     = 'localhost';
$user     = 'root';
$pass     = '';
$dbname = 'inventory-system';

try {
    $config = new PDO("mysql:host=$host;dbname=$dbname;", $user, $pass);
} catch (PDOException $e) {
    echo 'Connection Failed' . $e->getMessage();
}

spl_autoload_register(function ($class_name) {
    $file = __DIR__ . '/../models/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

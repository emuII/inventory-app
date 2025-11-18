<?php
require_once '../config/database.php';
require_once '../config/env.php';

$controllerName = $_GET['controller'] ?? '';
$action         = $_GET['action'] ?? '';

if (!$controllerName || !$action) {
    exit('Missing controller or action');
}

$controllerClass = ucfirst($controllerName) . 'Controller';
$controllerFile  = "../controllers/{$controllerClass}.php";

if (!file_exists($controllerFile)) {
    exit("Controller {$controllerClass} not found");
}

require_once $controllerFile;

$controller = new $controllerClass($config);

if (!method_exists($controller, $action)) {
    exit("Action {$action} not found in {$controllerClass}");
}

$controller->$action();

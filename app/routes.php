<?php
require_once 'controller/supplierController.php';
$routes = [
    '/' => [
        'method' => 'GET',
        'handler' => function () {
            (new supplierController())->index();
        }
    ]
];

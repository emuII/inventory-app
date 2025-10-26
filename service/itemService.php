<?php
session_start();

if (!empty($_SESSION['active_login'])) {
    require '../config/database.php';
}

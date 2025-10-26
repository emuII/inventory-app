<?php
@ob_start();
session_start();
if (!empty($_SESSION['active_login'])) {
    require 'config/database.php';

    $supplier_model = new supplierModel($config);
    $user_model = new userModel($config);
    $helper_model = new helperModel($config);
    $item_model = new itemModel($config);
    $approval_member = new approvalMemberModel($config);


    include 'components/header.php';
    include 'components/sidebar.php';
    if (!empty($_GET['route'])) {
        include 'views/' . $_GET['route'] . '/index.php';
    } else {
        include 'components/home.php';
    }
    include 'components/footer.php';
} else {
    echo '<script>window.location="login.php";</script>';
    exit;
}

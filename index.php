<?php
@ob_start();
session_start();
if (!empty($_SESSION['active_login'])) {
    require 'config/database.php';

    $supplier_model = new models_supplier($config);
    $category_model = new models_category($config);
    $brand_model = new models_brand($config);
    $product_model = new models_product($config);
    $user_model = new models_user($config);
    $type_model = new models_type($config);
    $product_out = new models_out($config);
    $helper_model = new heper_model($config);

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

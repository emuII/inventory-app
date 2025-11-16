<?php
@ob_start();
session_start();
if (!empty($_SESSION['active_login'])) {
    require 'config/database.php';

    $supplier_model   = new supplierModel($config);
    $user_model       = new userModel($config);
    $helper_model     = new helperModel($config);
    $item_model       = new itemModel($config);
    $approval_member  = new approvalMemberModel($config);
    $request          = new purchaseRequestModel($config);

    include 'components/header.php';
    include 'components/sidebar.php';

    $route = $_GET['route'] ?? '';

    if ($route) {
        list($name, $action) = array_pad(explode('/', $route), 2, 'index');

        $controllerFile = "controllers/{$name}Controller.php";
        if (file_exists($controllerFile)) {
            require $controllerFile;
            $class = $name . 'Controller';
            if (class_exists($class)) {
                $ctrl = new $class($config);
                if (method_exists($ctrl, $action)) {
                    $ctrl->$action(); // TIDAK include footer dan tidak exit di sini
                }
            }
        } else {
            $view = "views/{$name}/{$action}.php";
            if (file_exists($view)) {
                include $view;
            } else {
                echo "<p>Route tidak ditemukan.</p>";
            }
        }
    } else {
        include 'components/home.php';
    }

    include 'components/footer.php';
} else {
    echo '<script>window.location="login.php";</script>';
    exit;
}

<?php
session_start();
if (!empty($_SESSION['active_login'])) {
    require '../config/database.php';

    if (!empty($_GET['add_brand'])) {
        $brand_code = htmlentities($_POST['brand_code']);
        $brand_name = htmlentities($_POST['brand_name']);
        $brand_status = htmlentities($_POST['brand_status']);

        $dto = [
            $brand_code,
            $brand_name,
            $brand_status
        ];


        $query = 'INSERT INTO m_brand(brand_code, brand_name, brand_status) VALUES (?,?,?)';
        $row = $config->prepare($query);
        $row->execute($dto);
        echo '<script>window.location="../index.php?route=brand&success=tambah-data"</script>';
    }

    if (!empty($_GET['edit_brand'])) {
        $brand_code = htmlentities($_POST['brand_code']);
        $brand_name = htmlentities($_POST['brand_name']);
        $brand_status = htmlentities($_POST['brand_status']);

        $dto = [
            $brand_name,
            $brand_status,
            $brand_code
        ];

        $query = "UPDATE m_brand SET brand_name=?,brand_status=? WHERE brand_code = ?";
        $row = $config->prepare($query);
        $row->execute($dto);
        echo '<script>window.location="../index.php?route=brand/edit&brand_code=' . $brand_code . ' &success=edit-brand"</script>';
    }


    if (!empty($_GET['delete_brand'])) {
        $category_code = htmlentities($_GET['brand_code']);

        $dto = [$category_code];
        $query = 'UPDATE m_brand SET brand_status = 3 WHERE brand_code =?';
        $row = $config->prepare($query);
        $row->execute($dto);
        echo '<script>window.location="../index.php?route=brand&success=remove-brand"</script>';
    }
}

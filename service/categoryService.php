<?php
session_start();
if (!empty($_SESSION['active_login'])) {
    require '../config/database.php';

    if (!empty($_GET['add_category'])) {
        $category_code = htmlentities($_POST['category_code']);
        $category_desc = htmlentities($_POST['category_desc']);
        $category_status = htmlentities($_POST['category_status']);
        $category_name = htmlentities($_POST['category_name']);

        $dto = [
            $category_code,
            $category_desc,
            $category_status,
            $category_name
        ];


        $query = 'INSERT INTO m_category(category_code, category_desc, category_status, category_name) VALUES (?,?,?,?)';
        $row = $config->prepare($query);
        $row->execute($dto);
        echo '<script>window.location="../index.php?route=category&success=tambah-data"</script>';
    }

    if (!empty($_GET['edit_category'])) {
        $category_code = htmlentities($_POST['category_code']);
        $category_name = htmlentities($_POST['category_name']);
        $category_desc = htmlentities($_POST['category_desc']);
        $category_status = htmlentities($_POST['category_status']);

        $dto = [
            $category_desc,
            $category_status,
            $category_name,
            $category_code
        ];

        $query = "UPDATE m_category SET category_desc=?,category_status=?,category_name=? WHERE category_code = ?";
        $row = $config->prepare($query);
        $row->execute($dto);
        echo '<script>window.location="../index.php?route=category/edit&category_code=' . $category_code . ' &success=edit-category"</script>';
    }


    if (!empty($_GET['delete_category'])) {
        $category_code = htmlentities($_GET['category_code']);

        $dto = [$category_code];
        $query = 'UPDATE m_category SET category_status = 3 WHERE category_code =?';
        $row = $config->prepare($query);
        $row->execute($dto);
        echo '<script>window.location="../index.php?route=category&success=remove-category"</script>';
    }
}

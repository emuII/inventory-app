<?php
session_start();

if (!empty($_SESSION['active_login'])) {
    require '../config/database.php';
    require '../config/env.php';

    if (!empty($_GET['add_supplier'])) {
        $supplier_code = htmlentities($_POST['supplier_code']);
        $supplier_name = htmlentities($_POST['supplier_name']);
        $supplier_address = htmlentities($_POST['supplier_address']);
        $supplier_contact = htmlentities($_POST['supplier_contact']);

        $dto[] = $supplier_code;
        $dto[] = $supplier_name;
        $dto[] = $supplier_address;
        $dto[] = $supplier_contact;

        $query = 'INSERT INTO `m_supplier`(`supplier_code`, `supplier_name`, `supplier_address`, `supplier_contact`) VALUES (?,?,?,?)';
        $row = $config->prepare($query);
        $row->execute($dto);
        echo '<script>window.location="../index.php?route=supplier&&success=tambah-data"</script>';
    }
}

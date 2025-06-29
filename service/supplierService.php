<?php
session_start();

if (!empty($_SESSION['active_login'])) {
    require '../config/database.php';

    if (!empty($_GET['add_supplier'])) {
        $supplier_code = htmlentities($_POST['supplier_code']);
        $supplier_name = htmlentities($_POST['supplier_name']);
        $supplier_address = htmlentities($_POST['supplier_address']);
        $supplier_contact = htmlentities($_POST['supplier_contact']);
        $supplier_status = htmlentities($_POST['supplier_status']);

        $dto = [
            $supplier_code,
            $supplier_name,
            $supplier_address,
            $supplier_contact,
            $supplier_status
        ];


        $query = 'INSERT INTO `m_supplier`(`supplier_code`, `supplier_name`, `supplier_address`, `supplier_contact`, `supplier_status`) VALUES (?,?,?,?,?)';
        $row = $config->prepare($query);
        $row->execute($dto);
        echo '<script>window.location="../index.php?route=supplier&&success=tambah-data"</script>';
    }

    if (!empty($_GET['edit_supplier'])) {
        $supplier_code = htmlentities($_POST['supplier_code']);
        $supplier_name = htmlentities($_POST['supplier_name']);
        $supplier_address = htmlentities($_POST['supplier_address']);
        $supplier_contact = htmlentities($_POST['supplier_contact']);
        $supplier_status = htmlentities($_POST['supplier_status']);

        $dto = [
            $supplier_name,
            $supplier_address,
            $supplier_contact,
            $supplier_status,
            $supplier_code
        ];

        $query = 'UPDATE m_supplier SET supplier_name=?,supplier_address=?,supplier_contact=?,supplier_status=? WHERE supplier_code =?';
        $row = $config->prepare($query);
        $row->execute($dto);
        echo '<script>window.location="/inventory-app/index.php?route=supplier/edit&supplier_code=' . $supplier_code . ' &success=edit-supplier"</script>';
    }

    if (!empty($_GET['delete_supplier'])) {
        $supplier_code = htmlentities($_GET['supplier_code']);

        $dto = [$supplier_code];
        $query = 'UPDATE m_supplier SET supplier_status = 3 WHERE supplier_code =?';
        $row = $config->prepare($query);
        $row->execute($dto);
        echo '<script>window.location="../index.php?route=supplier&success=remove-supplier"</script>';
    }
}

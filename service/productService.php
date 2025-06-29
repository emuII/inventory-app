<?php
session_start();
if (!empty($_SESSION['active_login'])) {
    require '../config/database.php';

    if (!empty($_GET['add_product'])) {
        $product_code = htmlentities($_POST['product_code']);
        $product_name = htmlentities($_POST['product_name']);
        $product_category = htmlentities($_POST['product_category']);
        $product_supplier = htmlentities($_POST['product_supplier']);
        $product_brand = htmlentities($_POST['product_brand']);
        $product_qty = htmlentities($_POST['product_qty']);
        $product_price = htmlentities($_POST['product_price']);
        $product_status = htmlentities($_POST['product_status']);


        $dto = [
            $product_code,
            $product_name,
            $product_category,
            $product_supplier,
            $product_brand,
            $product_qty,
            $product_price,
            $product_status
        ];


        $query = 'INSERT INTO m_product(product_code, product_name, category_id, supplier_id, brand_id, product_qty, product_price, product_status)
        VALUES (?,?,?,?,?,?,?,?)';
        $row = $config->prepare($query);
        $row->execute($dto);
        echo '<script>window.location="../index.php?route=product&success=tambah-data"</script>';
    }

    if (!empty($_GET['edit_product'])) {
        $product_code = htmlentities($_POST['product_code']);
        $product_name = htmlentities($_POST['product_name']);
        $product_category = htmlentities($_POST['product_category']);
        $product_supplier = htmlentities($_POST['product_supplier']);
        $product_brand = htmlentities($_POST['product_brand']);
        $product_qty = htmlentities($_POST['product_qty']);
        $product_price = htmlentities($_POST['product_price']);
        $product_status = htmlentities($_POST['product_status']);


        $dto = [
            $product_name,
            $product_category,
            $product_supplier,
            $product_brand,
            $product_qty,
            $product_price,
            $product_status,
            $product_code
        ];

        $query = "UPDATE m_product 
        SET product_name=?, category_id=?,
        supplier_id=?,brand_id=?,product_qty=?,product_price=?,
        product_status=? WHERE product_code =?";
        $row = $config->prepare($query);
        $row->execute($dto);
        echo '<script>window.location="../index.php?route=product/edit&product_code=' . $product_code . ' &success=edit-product"</script>';
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

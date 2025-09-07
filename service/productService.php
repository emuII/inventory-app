<?php
session_start();
if (!empty($_SESSION['active_login'])) {
    require '../config/database.php';

    if (!empty($_GET['add_product'])) {
        $product_code = htmlentities($_POST['product_code']);
        $product_name = htmlentities($_POST['product_name']);
        $product_category = htmlentities($_POST['product_category']);
        $product_type = htmlentities($_POST['product_type']);
        $product_brand = htmlentities($_POST['product_brand']);
        $product_qty = htmlentities($_POST['product_qty']);
        $purchase_price = htmlentities($_POST['purchase_price']);
        $selling_price = htmlentities($_POST['selling_price']);
        $product_supplier = htmlentities($_POST['product_supplier']);
        $product_status = htmlentities($_POST['product_status']);


        $dto = [
            $product_code,
            $product_name,
            $product_category,
            $product_type,
            $product_brand,
            $product_qty,
            $purchase_price,
            $selling_price,
            $product_supplier,
            $product_status
        ];


        $query = 'INSERT INTO m_product(product_code, product_name, category_id, type_id, brand_id, product_qty, purchase_price, selling_price, supplier_id, product_status)
        VALUES (?,?,?,?,?,?,?,?,?,?)';
        $row = $config->prepare($query);
        $row->execute($dto);
        echo '<script>window.location="../index.php?route=productIn&success=tambah-data"</script>';
    }

    if (!empty($_GET['edit_product'])) {
        $product_code = htmlentities($_POST['product_code']);
        $product_name = htmlentities($_POST['product_name']);
        $product_category = htmlentities($_POST['product_category']);
        $product_type = htmlentities($_POST['product_type']);
        $product_brand = htmlentities($_POST['product_brand']);
        $product_qty = htmlentities($_POST['product_qty']);
        $purchase_price = htmlentities($_POST['purchase_price']);
        $selling_price = htmlentities($_POST['selling_price']);
        $product_supplier = htmlentities($_POST['product_supplier']);
        $product_status = htmlentities($_POST['product_status']);

        $dto = [
            $product_name,
            $product_category,
            $product_type,
            $product_brand,
            $product_qty,
            $purchase_price,
            $selling_price,
            $product_supplier,
            $product_status,
            $product_code
        ];

        $query = "UPDATE m_product 
        SET product_name=?, category_id=?,
        type_id=?,brand_id=?,product_qty=?,purchase_price=?, selling_price=?, supplier_id=?,
        product_status=? WHERE product_code =?";
        $row = $config->prepare($query);
        $row->execute($dto);
        echo '<script>window.location="../index.php?route=productIn/edit&product_code=' . $product_code . ' &success=edit-product"</script>';
    }


    if (!empty($_GET['delete_product'])) {
        $product_code = htmlentities($_GET['product_code']);

        $dto = [$product_code];
        $query = 'UPDATE m_product SET product_status = 3 WHERE product_code =?';
        $row = $config->prepare($query);
        $row->execute($dto);
        echo '<script>window.location="../index.php?route=productin&success=remove-product"</script>';
    }
}

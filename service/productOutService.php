<?php
session_start();
if (!empty($_SESSION['active_login'])) {
    require '../config/database.php';

    if (!empty($_GET['add_out'])) {
        header('Content-Type: application/json');

        $prod_id       = (int)($_POST['prod_id']   ?? 0);
        $available_qty = (int)($_POST['qty_av']  ?? 0);
        $newqty        = (int)($_POST['out_qty']        ?? 0);
        $selling_price = (int)($_POST['selling_price']  ?? 0);
        $notes         = trim($_POST['notes'] ?? '');

        if ($prod_id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Produk belum dipilih']);
            exit;
        }
        if ($newqty <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Qty keluar harus > 0']);
            exit;
        }
        if ($newqty > $available_qty) {
            echo json_encode(['status' => 'error', 'message' => 'Qty keluar melebihi stok!']);
            exit;
        }

        try {
            $stmt = $config->prepare("SELECT product_id FROM m_product WHERE product_id = ?");
            $stmt->execute([$prod_id]);
            if (!$stmt->fetchColumn()) {
                echo json_encode(['status' => 'error', 'message' => 'Produk tidak ditemukan (cek product_id)']);
                exit;
            }

            $query = "INSERT INTO product_out
                    (product_id, qty_out, selling_price, date_out, note)
                  VALUES (?,?,?,?,?)";
            $row = $config->prepare($query);
            $row->execute([$prod_id, $newqty, $selling_price, date('Y-m-d'), $notes]);

            echo json_encode(['status' => 'ok', 'message' => 'Transaksi keluar tersimpan']);
        } catch (PDOException $e) {
            // tangkap pesan dari trigger juga
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit;
    }
}

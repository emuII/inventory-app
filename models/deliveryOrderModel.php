<?php
class deliveryOrderModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getProductList()
    {
        $sql = "SELECT MI.Id AS ItemId,
                    MI.item_name AS itemName,
                    MI.category AS categoryItem,
                    MI.type AS typeCategory,
                    MI.qty AS stockQuantity,
                    MI.sales_price AS sellingPrice
                FROM m_item MI WHERE MI.qty > 0";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getDeliveryOrderByCode($deliveryCode)
    {
        $sql = "SELECT 
                    DVO.id deliveryId,
                    DVO.do_code deliveryCode,
                    DVO.invoice_number invoiceNumber,
                    DATE_FORMAT(DVO.do_date, '%d %b %Y') AS deliveryDate,
                    MST.name AS statusName,
                    FORMAT(DVO.total_amount, 0, 'id_ID') totalAmount,
                    FORMAT((DVO.total_amount - DVO.tax), 0, 'id_ID') subTotal,
                    FORMAT(DVO.tax, 0, 'id_ID') tax
                FROM delivery_order DVO
                JOIN m_status MST ON DVO.status = MST.value AND MST.code ='delivery_order'
                WHERE DVO.do_code =?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$deliveryCode]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function submitDeliveryOrder(array $data)
    {
        $doCode = 'DO-' . date('ymds');
        $invoiceNumber = 'INV/' . date('Y/m/') . str_pad(rand(1, 999), 5, '0', STR_PAD_LEFT);
        $this->pdo->beginTransaction();
        try {
            $sqlDo = "INSERT INTO delivery_order (do_code, invoice_number, do_date, status, total_amount, tax, created_at)
                VALUES (:do_code, :invoice_number,  NOW(), :status, :total_amount, :tax, NOW())";
            $stmt = $this->pdo->prepare($sqlDo);
            $stmt->execute([
                ':do_code'     => $doCode,
                ':invoice_number' => $invoiceNumber,
                ':status'      => 1,
                ':total_amount' => $data['totalAmount'],
                ':tax'         => $data['tax']
            ]);
            $doId = (int)$this->pdo->lastInsertId();

            $sqlDoDetail = "INSERT INTO delivery_order_detail (do_id, item_id, qty, subtotal, created_at)
                VALUES (:do_id, :item_id, :qty, :subtotal, NOW())";

            $stmtDoDetail = $this->pdo->prepare($sqlDoDetail);

            foreach ($data['items'] as $item) {
                $stmtDoDetail->execute([
                    ':do_id'    => $doId,
                    ':item_id'  => $item['itemId'],
                    ':qty'      => $item['quantity'],
                    ':subtotal' => $item['subtotal'],
                ]);

                $sqlUpdateStock = "UPDATE m_item SET qty = qty - :qty WHERE Id = :item_id";
                $stmtUpdateStock = $this->pdo->prepare($sqlUpdateStock);
                $stmtUpdateStock->execute([
                    ':qty'     => $item['quantity'],
                    ':item_id' => $item['itemId'],
                ]);
            }
            $this->pdo->commit();
            return $doCode;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}

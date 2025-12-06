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

    public function submitDeliveryOrder(array $data)
    {
        $doCode = 'DO-' . date('ymds');

        $this->pdo->beginTransaction();
        try {
            $sqlDo = "INSERT INTO delivery_order (do_code, do_date, status, total_amount, tax, created_at)
                VALUES (:do_code,  NOW(), :status, :total_amount, :tax, NOW())";
            $stmt = $this->pdo->prepare($sqlDo);
            $stmt->execute([
                ':do_code'     => $doCode,
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

<?php
class deliveryOrderLogModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getDeliveryOrderLogs()
    {
        $filter_number      = htmlentities($_POST['filter_number'] ?? '');
        $dateFrom           = htmlentities($_POST['dateFrom'] ?? '');
        $dateTo             = htmlentities($_POST['dateTo'] ?? '');
        $transactionStatus  = htmlentities($_POST['transaction_status'] ?? '');

        $query  = "SELECT
                        DO.id doId,
                        DO.do_code doCode,
                        DATE_FORMAT(DO.do_date, '%d %b %Y') AS doDate,
                        FORMAT(DO.total_amount, 0, 'id_ID') AS totalAmount,
                        FORMAT(DO.tax, 0, 'id_ID') AS  tax,
                        MST.name statusName
                    FROM delivery_order DO
                    LEFT JOIN m_status MST ON DO.status = MST.value AND MST.code = 'delivery_order'
                    WHERE 1=1";
        $params = [];

        if ($filter_number !== '') {
            $query .= " AND DO.do_code LIKE ?";
            $params[] = "%$filter_number%";
        }

        if (!empty($dateFrom) && !empty($dateTo)) {
            $query .= " AND DATE(DO.do_date) BETWEEN ? AND ?";
            $params[] = $dateFrom;
            $params[] = $dateTo;
        }

        if (!empty($transactionStatus) && $transactionStatus != '0') {
            $query .= " AND DO.status = ?";
            $params[] = $transactionStatus;
        }

        $query .= " ORDER BY DO.do_date DESC, DO.id DESC";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deliveryLogOrderDetails($doNumber)
    {
        $queryDetail = "SELECT DOD.id deliveryOrderId,
                            ITM.item_name itemName,
                            DOD.qty qtyOrder,
                            FORMAT(ITM.sales_price, 0, 'id_ID') salesPrice,
                            FORMAT(DOD.subtotal, 0, 'id_ID') subTotal
                        FROM delivery_order DO
                            JOIN delivery_order_detail DOD
                                ON DO.id = DOD.do_id
                            JOIN m_item ITM
                                ON DOD.item_id = ITM.Id
                        WHERE DO.do_code = :doNumber";
        $row = $this->pdo->prepare($queryDetail);
        $row->execute([':doNumber' => $doNumber]);
        return $row->fetchAll();
    }
}

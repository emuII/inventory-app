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
                            CONCAT(ITM.item_name, ' - ', ITM.type, ' - ', ITM.category) AS itemName,
                            DOD.qty qtyOrder,
                            FORMAT(ITM.sales_price, 0, 'id_ID') salesPrice,
                            FORMAT(DOD.subtotal, 0, 'id_ID') subTotal
                        FROM delivery_order DVO
                            JOIN delivery_order_detail DOD
                                ON DVO.id = DOD.do_id
                            JOIN m_item ITM
                                ON DOD.item_id = ITM.Id
                        WHERE DVO.do_code = :doNumber";
        $row = $this->pdo->prepare($queryDetail);
        $row->execute([':doNumber' => $doNumber]);
        return $row->fetchAll();
    }

    public function deliveryOrderLogHeaderReport()
    {
        $filter_number = isset($_POST['filter_number']) ? trim($_POST['filter_number']) : '';
        $dateFrom = isset($_POST['dateFrom']) ? $_POST['dateFrom'] : '';
        $dateTo = isset($_POST['dateTo']) ? $_POST['dateTo'] : '';
        $transactionStatus = isset($_POST['transaction_status']) ? $_POST['transaction_status'] : '';

        $query = "SELECT 
                DVO.do_code,
                DATE_FORMAT(DVO.do_date, '%d %b %Y') AS formatted_date,
                DVO.do_date,
                FORMAT((DVO.total_amount - DVO.tax), 0, 'id_ID') AS formatted_subtotal,
                FORMAT(DVO.total_amount, 0, 'id_ID') AS formatted_grand_total,
                FORMAT(DVO.tax, 0, 'id_ID') AS formatted_tax,
                DVO.total_amount,
                DVO.tax,
                (DVO.total_amount - DVO.tax) AS subtotal,
                (
                    SELECT SUM(qty) 
                    FROM delivery_order_detail d1 
                    WHERE d1.do_id = DVO.id
                ) AS total_qty,
                DVO.status,
                MST.name AS status_name,
                ITM.item_name,
                ITM.type,
                ITM.category,
                DOD.qty AS quantity,
                FORMAT(ITM.sales_price, 0, 'id_ID') AS formatted_unit_price,
                FORMAT((DOD.qty * ITM.sales_price), 0, 'id_ID') AS formatted_line_total,
                ITM.sales_price AS unit_price,
                (DOD.qty * ITM.sales_price) AS line_total
            FROM delivery_order DVO
            JOIN m_status MST ON DVO.status = MST.value 
                AND MST.code = 'delivery_order'
            JOIN delivery_order_detail DOD ON DVO.id = DOD.do_id
            JOIN m_item ITM ON DOD.item_id = ITM.Id
            WHERE 1=1";

        $params = [];

        if ($filter_number !== '') {
            $query .= " AND DVO.do_code LIKE ?";
            $params[] = "%" . $filter_number . "%";
        }

        if (!empty($dateFrom) && !empty($dateTo)) {
            if (
                DateTime::createFromFormat('Y-m-d', $dateFrom) !== false &&
                DateTime::createFromFormat('Y-m-d', $dateTo) !== false
            ) {
                $query .= " AND DATE(DVO.do_date) BETWEEN ? AND ?";
                $params[] = $dateFrom;
                $params[] = $dateTo;
            }
        }

        if (!empty($transactionStatus) && $transactionStatus != '0') {
            $query .= " AND DVO.status = ?";
            $params[] = $transactionStatus;
        }

        $query .= " ORDER BY DVO.do_date DESC, DVO.do_code";

        error_log("Query: " . $query);
        error_log("Params: " . print_r($params, true));

        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Total results: " . count($results));

            return $results;
        } catch (Exception $e) {
            error_log("Error in deliveryOrderLogHeaderReport: " . $e->getMessage());
            return [];
        }
    }
}

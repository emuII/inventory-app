<?php
class wareHouseModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function warehouseList()
    {
        $filter_number = htmlentities($_POST['filter_number'] ?? '');
        $dateFrom        = htmlentities($_POST['dateFrom'] ?? '');
        $dateTo        = htmlentities($_POST['dateTo'] ?? '');
        $warehouseStatus = htmlentities($_POST['warehouse_status'] ?? '');
        $supplierName   = htmlentities($_POST['supplier_name'] ?? '');

        $sql = "SELECT 
                    W.id AS warehouseId,
                    W.pr_id AS prId,
                    PR.pr_code AS requestNumber,
                    MU.username AS requestedBy,
                    DATE_FORMAT(W.date_in, '%d %b %Y') AS dateIn,
                    FORMAT(W.total_amount, 0, 'id_ID') AS totalAmount,
                    MS.supplier_name AS supplierName,
                    MST.name AS statusName,
                    MS.id AS supplierId,
                    PRD.orderQty,
                    (WHH.total_qty * PRD.countQty) AS receiveQty,
                    FORMAT(MAX(WHD.unit_price), 0, 'id_ID') AS unitPrice
                FROM warehouse W
                JOIN purchase_request PR ON W.pr_id = PR.id
                JOIN m_user MU ON PR.requester_id = MU.id
                JOIN m_supplier MS ON PR.supplier_id = MS.id
                JOIN m_status MST ON W.status = MST.value AND MST.code = 'warehouse'
                LEFT JOIN warehouse_detail WHD 
                    ON WHD.warehouse_id = W.id
                JOIN (
                    SELECT 
                        PD.pr_id,
                        COUNT(*) AS countQty,
                        SUM(PD.qty) AS orderQty
                    FROM purchase_request_detail PD
                    GROUP BY PD.pr_id
                ) PRD
                    ON PR.id = PRD.pr_id
                LEFT JOIN
                (
                    SELECT warehouse_detail_id,
                        SUM(qty) AS total_qty,
                        SUM(price * qty) AS total_amount
                    FROM warehouse_history
                    GROUP BY warehouse_detail_id
                ) WHH
                    ON WHH.warehouse_detail_id = WHD.id
                WHERE 1 = 1";

        $params = [];
        if (!empty($filter_number)) {
            $sql .= " AND PR.pr_code LIKE ?";
            $params[] = "%$filter_number%";
        }
        if (!empty($dateFrom) && !empty($dateTo)) {
            $sql .= " AND DATE(W.date_in) BETWEEN ? AND ?";
            $params[] = $dateFrom;
            $params[] = $dateTo;
        }
        if (!empty($warehouseStatus) && $warehouseStatus != '0') {
            $sql .= " AND W.status = ?";
            $params[] = $warehouseStatus;
        }
        if (!empty($supplierName) && $supplierName != '0') {
            $sql .= " AND MS.id = ?";
            $params[] = $supplierName;
        }
        $sql .= " 
            GROUP BY 
                W.id,
                W.pr_id,
                PR.pr_code,
                MU.username,
                W.date_in,
                W.total_amount,
                MS.supplier_name,
                MST.name,
                MS.id
            ORDER BY W.id DESC";
        $row = $this->pdo->prepare($sql);
        $row->execute($params);
        return $row->fetchAll();
    }

    public function wareHouseDetail($warehouseId)
    {
        $sql = "SELECT WD.id AS warehouseDetailId,
                CONCAT(MI.item_name, ' (', MI.category, ' - ', MI.type, ')') AS itemName,
                MI.Id itemId,
                PRD.qty qtyOrder,
                WD.qty qtyReceive,
                FORMAT(WD.unit_price, 0, 'id_ID') AS unitPrice,
                WD.notes notes,
                WD.line_no lineNo
            FROM warehouse_detail WD
                JOIN purchase_request_detail PRD
                    ON WD.prd_id = PRD.id
                JOIN m_item MI
                    ON WD.item_id = MI.id
            WHERE WD.warehouse_id = :warehouse_id
            ORDER BY WD.line_no ASC";

        $row = $this->pdo->prepare($sql);
        $row->execute([':warehouse_id' => $warehouseId]);
        return $row->fetchAll();
    }

    public function wareHouseHistory($whdId)
    {
        $query = "SELECT 
                    WHH.id,
                    ITM.item_name itemName,
                    PRD.qty orderQty,
                    WHH.qty receiveQty,
                    FORMAT(WHH.price, 0, 'id_ID') unitPrice,
                    WHH.notes,
                    DATE_FORMAT(WHH.created_at, '%d %b %Y') AS dateIn,
                    PRD.line_no lineNo,
                    WHH.count_id countId,
                    WHH.created_at created_at
                FROM warehouse_history WHH
                JOIN warehouse_detail WHD ON WHH.warehouse_detail_id = WHD.id
                JOIN purchase_request_detail PRD ON WHD.prd_id = PRD.id
                JOIN m_item ITM ON WHH.item_id = ITM.Id
                WHERE WHH.warehouse_id = :warehouse_id;";
        $row = $this->pdo->prepare($query);
        $row->execute([':warehouse_id' => $whdId]);
        return $row->fetchAll();
    }

    public function draftWarehouse($prId)
    {
        $query = "
            INSERT INTO warehouse (
                pr_id,
                status,
                supplier_id,
                created_at
            )
            SELECT 
                pr.id,
                1,
                pr.supplier_id,
                NOW()
            FROM purchase_request pr
            WHERE pr.id = :pr_id 
            AND pr.status = 2";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':pr_id' => $prId
        ]);

        $warehouseId = $this->pdo->lastInsertId();

        $query_detail = "INSERT INTO warehouse_detail
            (
                warehouse_id,
                prd_id,
                line_no,
                item_id,
                created_at
            )
            SELECT 
                :warehouse_id as warehouse_id,
                prd.id AS prd_id,
                prd.line_no,
                prd.item_id,
                NOW()
            FROM purchase_request_detail prd
                JOIN warehouse w
                    ON w.pr_id = prd.pr_id
                LEFT JOIN m_item i
                    ON i.id = prd.item_id
            WHERE prd.pr_id = :pr_id
            ";
        $stmt = $this->pdo->prepare($query_detail);
        $stmt->execute([
            ':warehouse_id' => $warehouseId,
            ':pr_id' => $prId
        ]);

        return $stmt;
    }

    public function submitWareHouse($data)
    {
        $this->pdo->beginTransaction();
        try {
            $warehouseId      = (int) $data['warehouseId'];
            $warehouseDetails = $data['details'];

            $updateStmt = $this->pdo->prepare("
                UPDATE warehouse_detail
                SET unit_price = :unitPrice,
                    updated_at = NOW()
                WHERE id = :whdId
            ");

            $insertHistory = $this->pdo->prepare("INSERT INTO warehouse_history
            (	
                warehouse_id,
                warehouse_detail_id,
                item_id,
                price,
                qty,
                notes,
                count_id,
                created_at
            )
            VALUES(:warehouse_id, :warehouse_detail_id, :item_id, :price, :qty, :notes, :count_id, NOW());");

            foreach ($warehouseDetails as $item) {
                $updateStmt->execute([
                    ':unitPrice'  => $item['unitPrice'],
                    ':whdId'      => $item['whdId'],
                ]);

                $countData = $this->getCountWarehouseHistory((int)$item['whdId']);;
                $insertHistory->execute([
                    ':warehouse_id' => $warehouseId,
                    ':warehouse_detail_id'  => (int)$item['whdId'],
                    ':item_id'      => (int)$item['itemId'],
                    ':price'      => $item['unitPrice'],
                    ':qty'      =>  (int)$item['qtyReceive'],
                    ':notes' => $item['notes'],
                    ':count_id' => $countData ? ((int)$countData['count_id'] + 1) : 1
                ]);

                $isStock = $this->validateForStock($warehouseId);

                if (($isStock['receiveQty'] ?? 0) > ($isStock['orderQty'] ?? 0)) {
                    throw new Exception("Received quantity for item : (" . $item['itemName'] . ") exceeds ordered quantity.");
                }

                $updateStock = $this->pdo->prepare("
                            UPDATE m_item
                            SET qty = qty + :qtyReceive,
                                buy_price = :unitPrice,
                                updated_at = NOW()
                            WHERE id = :itemId
                        ");

                $updateStock->execute([
                    ':qtyReceive' => ((int)$item['qtyReceive']),
                    ':unitPrice'  => $item['unitPrice'],
                    ':itemId'     => (int)$item['itemId']
                ]);
            }

            $isMatch = $this->validateQtyReceive($warehouseId);
            $warehouseStatus = 3;
            $amountTotal = $isMatch["amount_total"];

            if (!$isMatch["is_match"]) {
                $warehouseStatus = 2;
            } else {
                foreach ($warehouseDetails as $obj) {
                    $updateDetail = $this->pdo->prepare("
                        UPDATE warehouse_detail
                        SET unit_price = :unitPrice,
                            qty = (SELECT IFNULL(SUM(qty), 0)
                                   FROM warehouse_history
                                   WHERE warehouse_detail_id = :whdId),
                            notes = :notes,
                            updated_at = NOW()
                        WHERE id = :whdId
                    ");

                    $updateDetail->execute([
                        ':unitPrice'  => $obj['unitPrice'],
                        ':notes'      => $obj['notes'],
                        ':whdId'      => (int)$obj['whdId'],
                    ]);
                }
            }

            $updateWh = $this->pdo->prepare("   
                UPDATE warehouse
                SET status    = :warehouseStatus,
                    total_amount = :amountTotal,
                    date_in   = NOW(),
                    updated_at = NOW()
                WHERE id = :warehouseId
            ");

            $updateWh->execute([
                ':warehouseId' => $warehouseId,
                ':warehouseStatus' => $warehouseStatus,
                ':amountTotal' => $amountTotal
            ]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function validateQtyReceive($warehouseId)
    {
        $sql = "SELECT 
            SUM(
                CASE 
                    WHEN IFNULL(H.total_qty, 0) = IFNULL(PRD.qty, 0) 
                        THEN 1 
                        ELSE 0 
                END
            ) AS match_count,
            COUNT(*) AS total_row,
            SUM(IFNULL(H.total_amount, 0)) AS amount_total
        FROM (
            SELECT 
                warehouse_id,
                warehouse_detail_id,
                SUM(qty)         AS total_qty,
                SUM(price * qty) AS total_amount
            FROM warehouse_history
            GROUP BY warehouse_id, warehouse_detail_id
        ) H
        JOIN warehouse_detail WHD 
            ON WHD.id = H.warehouse_detail_id
        JOIN purchase_request_detail PRD 
            ON WHD.prd_id = PRD.id
        WHERE H.warehouse_id = :warehouse_id;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':warehouse_id' => $warehouseId]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $matchCount = (int)($result['match_count'] ?? 0);
        $totalRow   = (int)($result['total_row'] ?? 0);

        return [
            'is_match'     => ($matchCount === $totalRow),  // semua baris match → true
            'amount_total' => (float)($result['amount_total'] ?? 0),
        ];
    }

    public function getCountWarehouseHistory($warehouse_detail_id)
    {
        $sql = "SELECT count_id
                FROM warehouse_history
                WHERE warehouse_detail_id = :warehouse_detail_id
                ORDER BY created_at DESC
                LIMIT 1;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':warehouse_detail_id' => $warehouse_detail_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function validateForStock($warehouseId)
    {
        $sql = "SELECT sub.orderQty,
            sub.receiveQty,
            CASE
                WHEN sub.orderQty = sub.receiveQty THEN
                    1
                ELSE
                    0
            END AS is_match
        FROM
        (
            SELECT
                (
                    SELECT SUM(PRD.qty)
                    FROM warehouse_detail WHD
                        JOIN purchase_request_detail PRD
                            ON WHD.prd_id = PRD.id
                    WHERE WHD.warehouse_id = :warehouseId
                ) AS orderQty,
                (
                    SELECT SUM(WHH.qty)
                    FROM warehouse_detail WHD
                        JOIN warehouse_history WHH
                            ON WHD.id = WHH.warehouse_detail_id
                    WHERE WHD.warehouse_id = :warehouseId
                ) AS receiveQty
        ) sub;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':warehouseId' => $warehouseId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

<?php
class purchaseRequestModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function requestList()
    {
        $filter_number = htmlentities($_POST['filter_number'] ?? '');
        $dateFrom        = htmlentities($_POST['dateFrom'] ?? '');
        $dateTo        = htmlentities($_POST['dateTo'] ?? '');
        $transactionStatus = htmlentities($_POST['transaction_status'] ?? '');
        $supplierName   = htmlentities($_POST['supplier_name'] ?? '');

        $sql = "SELECT 
                    PR.id,
                    PR.pr_code requestNumber,
                    DATE_FORMAT(PR.request_date, '%d %b %Y') AS requestDate,
                    PR.status statusId,
                    MST.name statusName,
                    PR.supplier_id,
                    MS.supplier_name,
                    PR.requester_id,
                    MU.username
                FROM purchase_request PR
                JOIN m_status MST ON PR.status = MST.value AND MST.code = 'transaction'
                JOIN m_user MU ON PR.requester_id = MU.id
                JOIN m_supplier MS ON PR.supplier_id = MS.Id
                WHERE 1 = 1 ";

        $params = [];
        if (!empty($filter_number)) {
            $sql .= " AND PR.pr_code LIKE ?";
            $params[] = "%$filter_number%";
        }

        if (!empty($dateFrom) && !empty($dateTo)) {
            $sql .= " AND DATE(PR.request_date) BETWEEN ? AND ?";
            $params[] = $dateFrom;   // format: 2025-11-20
            $params[] = $dateTo;     // format: 2025-11-25
        }
        if (!empty($transactionStatus) && $transactionStatus != '0') {
            $sql .= " AND PR.status = ?";
            $params[] = $transactionStatus;
        }
        if (!empty($supplierName) && $supplierName != '0') {
            $sql .= " AND PR.supplier_id = ?";
            $params[] = $supplierName;
        }

        $sql .= " ORDER BY PR.id DESC";


        $row = $this->pdo->prepare($sql);
        $row->execute($params);
        return $row->fetchAll();
    }

    public function GetReportPurchaseOrder()
    {
        $filter_number = htmlentities($_POST['filter_number'] ?? '');
        $dateFrom        = htmlentities($_POST['dateFrom'] ?? '');
        $dateTo        = htmlentities($_POST['dateTo'] ?? '');
        $transactionStatus = htmlentities($_POST['transaction_status'] ?? '');
        $supplierName   = htmlentities($_POST['supplier_name'] ?? '');
        $query = "SELECT
                    PR.id AS pr_id,
                    PR.pr_code AS request_number,
                    DATE_FORMAT(PR.request_date, '%Y-%m-%d') AS request_date,
                    MU.username AS requestor,
                    MST.name AS status_name,
                    MS.supplier_name AS supplier,
                    
                    PRD.id AS detail_id,
                    PRD.line_no,
                    PRD.qty AS quantity,
                    
                    MI.item_name,
                    MI.type,
                    MI.category,
                    MI.buy_price AS unit_price
                    
                FROM purchase_request PR
                JOIN purchase_request_detail PRD 
                    ON PRD.pr_id = PR.id
                JOIN m_item MI 
                    ON MI.Id = PRD.item_id
                JOIN m_status MST 
                    ON PR.status = MST.value 
                    AND MST.code = 'transaction'
                JOIN m_user MU 
                    ON PR.requester_id = MU.id
                JOIN m_supplier MS 
                    ON PR.supplier_id = MS.Id
                WHERE 1=1";


        $params = [];
        if (!empty($filter_number)) {
            $query .= " AND PR.pr_code LIKE ?";
            $params[] = "%$filter_number%";
        }
        if (!empty($dateFrom) && !empty($dateTo)) {
            $query .= " AND DATE(PR.request_date) BETWEEN ? AND ?";
            $params[] = $dateFrom;   // format: 2025-11-20
            $params[] = $dateTo;     // format: 2025-11-25
        }
        if (!empty($transactionStatus) && $transactionStatus != '0') {
            $query .= " AND PR.status = ?";
            $params[] = $transactionStatus;
        }
        if (!empty($supplierName) && $supplierName != '0') {
            $query .= " AND PR.supplier_id = ?";
            $params[] = $supplierName;
        }

        $query .= " ORDER BY PR.request_date DESC, PR.pr_code, PRD.line_no";
        $row = $this->pdo->prepare($query);
        $row->execute($params);
        return $row->fetchAll();
    }

    public function requestHeader(string $requestNumber)
    {
        $sql = "SELECT 
                PR.id PrId,
                PR.pr_code requestNumber,
                MS.supplier_name supplierName,
                MS.supplier_address supplierAddress,
                DATE_FORMAT(PR.request_date, '%d %b %Y') AS requestDate,
                AR.approver_name approverName,
                AR.remarks remarksApprover,
                PR.store_address storeAddress,
                MU.username requesterName,
                MST.name statusName
            FROM purchase_request PR 
            JOIN m_supplier MS ON PR.supplier_id = MS.Id
            JOIN approval_request AR ON PR.id = AR.pr_id
            JOIN m_user MU ON PR.requester_id = MU.id
            JOIN m_status MST ON PR.status = MST.value AND MST.code = 'transaction'
            WHERE PR.pr_code = :requestNumber;";

        $params = [':requestNumber' => $requestNumber];

        $row = $this->pdo->prepare($sql);
        $row->execute($params);
        return $row->fetch();
    }

    public function requestDetails(string $requestNumber)
    {
        $sql = "SELECT PR.id PrId,
                    PR.pr_code requestNumber,
                    PRD.id PrdId,
                    PRD.item_id ItemId,
                    PRD.qty,
                    PRD.notes AS Notes,
                    ITM.item_name itemName,
                    ITM.type,
                    ITM.category,
                    PR.status statusId
                FROM purchase_request PR
                    JOIN purchase_request_detail PRD
                        ON PR.id = PRD.pr_id
                    JOIN m_item ITM
                        ON PRD.item_id = ITM.Id
                WHERE PR.pr_code = :requestNumber";

        $params = [':requestNumber' => $requestNumber];

        $row = $this->pdo->prepare($sql);
        $row->execute($params);
        return $row->fetchAll();
    }

    public function createAll(array $payload, int $requesterId): int
    {
        $requestDate    = $payload['requestDate'];
        $selApprover    = (int)$payload['selApprover'];
        $remarksApprover = $payload['remarksApprover'] ?? '';
        $storeAddress = $payload['storeAddress'] ?? '';
        $items          = $payload['itemDetails'] ?? [];
        $supplierId = $payload['supplierId'];
        $statusRequest = $payload['statusRequest'];
        $approverName = $payload['approverName'];

        $prCode = 'PR-' . date('ymds');

        $this->pdo->beginTransaction();
        try {
            $sqlPR = "INSERT INTO `purchase_request`
                        (`pr_code`,`request_date`,`requester_id`,`status`, `store_address` ,`created_at`, `supplier_id`)
                      VALUES
                        (:pr_code,:request_date,:requester_id,:status, :store_address,NOW(), :supplier_id)";
            $stmtPR = $this->pdo->prepare($sqlPR);
            $stmtPR->execute([
                ':pr_code'      => $prCode,
                ':request_date' => $requestDate,
                ':requester_id' => $requesterId,
                ':status'       => $statusRequest,
                ':store_address' => $storeAddress,
                ':supplier_id' => $supplierId,
            ]);
            $prId = (int)$this->pdo->lastInsertId();

            // approval_request
            $sqlApp = "INSERT INTO `approval_request`
                        (`pr_id`,`approver_id`,`approver_name`,`status`,`remarks`,`created_at`)
                       VALUES
                        (:pr_id,:approver_id,:approver_name,:status,:remarks,NOW())";
            $stmtApp = $this->pdo->prepare($sqlApp);
            $stmtApp->execute([
                ':pr_id'         => $prId,
                ':approver_id'   => $selApprover,
                ':approver_name' => $approverName,
                ':status'        => $statusRequest,
                ':remarks'       => $remarksApprover
            ]);

            $sqlDet = "INSERT INTO `purchase_request_detail`
                        (`pr_id`,`line_no`,`item_id`,`qty`,`notes`,`created_at`)
                       VALUES
                        (:pr_id,:line_no,:item_id,:qty,:notes,NOW())";
            $stmtDet = $this->pdo->prepare($sqlDet);

            $line = 1;
            foreach ($items as $it) {
                $stmtDet->execute([
                    ':pr_id'      => $prId,
                    ':line_no'    => $line++,
                    ':item_id'    => (int)$it['itemId'],
                    ':qty'        => (float)$it['qty'],
                    ':notes'      => $it['notes'] ?? ''
                ]);
            }

            $this->pdo->commit();
            return $prId;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function cancelRequest(string $requestNumber): bool
    {
        $sql = "UPDATE purchase_request
                SET status = 4
                WHERE pr_code = :requestNumber";

        $params = [':requestNumber' => $requestNumber];

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}

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
                JOIN m_supplier MS ON PR.supplier_id = MS.Id;";

        $sql .= " ORDER BY PR.id DESC";
        $params = [];
        $row = $this->pdo->prepare($sql);
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
                PR.request_date requestDate,
                AR.approver_name approverName,
                AR.remarks,
                PR.store_address storeAddress,
                MU.username requesterName
            FROM purchase_request PR 
            JOIN m_supplier MS ON PR.supplier_id = MS.Id
            JOIN approval_request AR ON PR.id = AR.pr_id
            JOIN m_user MU ON PR.requester_id = MU.id
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
                    PRD.notes Notes,
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

        $prCode = 'PR' . '-' . date('YmdHis');

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
}

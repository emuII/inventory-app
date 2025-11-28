<?php
class approvalModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function approvalList(int $approverId)
    {
        $requestNumber    = htmlentities($_POST['requestNumber'] ?? '');
        $statusId    = htmlentities($_POST['statusId'] ?? '');
        $supplierId    = htmlentities($_POST['supplierId'] ?? '');

        $sql = "SELECT 
                    PR.id,
                    PR.pr_code requestNumber,
                    DATE_FORMAT(PR.request_date, '%d %b %Y') AS requestDate,
                    PR.status statusId,
                    MST.name statusName,
                    PR.supplier_id,
                    MS.supplier_name,
                    PR.requester_id,
                    MU.username,
                    AR.approver_name,
                    AR.Id approver_id
                FROM purchase_request PR
                JOIN m_status MST ON PR.status = MST.value AND MST.code = 'transaction' AND MST.value in(1,2,3)
                JOIN m_user MU ON PR.requester_id = MU.id
                JOIN m_supplier MS ON PR.supplier_id = MS.Id
                JOIN approval_request AR ON PR.id = AR.pr_id
                JOIN approval_member ARM ON AR.approver_id = ARM.id
                WHERE ARM.user_id = :approverId";

        $params = [':approverId' => $approverId];
        $sql .= " ORDER BY PR.id DESC";

        $row = $this->pdo->prepare($sql);
        $row->execute($params);
        return $row->fetchAll();
    }


    public function submit(int $prId, int $approvalStatus)
    {
        $sql_approval = "UPDATE approval_request 
                     SET status = :status_id
                     WHERE pr_id = :pr_id";

        $stmt = $this->pdo->prepare($sql_approval);
        $response = $stmt->execute([
            ':pr_id'     => $prId,
            ':status_id' => $approvalStatus
        ]);

        $sql_req = "UPDATE purchase_request 
                SET status = :status_id
                WHERE id = :pr_id";

        $stmt = $this->pdo->prepare($sql_req);
        $response = $stmt->execute([
            ':pr_id'     => $prId,
            ':status_id' => $approvalStatus
        ]);
        return $response;
    }
}

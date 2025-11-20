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
        $sql = "SELECT 
                    W.id AS warehouseId,
                    W.pr_id AS prId,
                    PR.pr_code requestNumber,
                    mu.username AS requestedBy,
                    DATE_FORMAT(W.date_in, '%d %b %Y') AS dateIn,
                    W.total_amount AS totalAmount,
                    MS.supplier_name AS supplierName,
                    MST.name AS statusName
                FROM warehouse W
                JOIN purchase_request PR ON W.pr_id = PR.id
                JOIN m_user MU ON PR.requester_id = MU.id
                JOIN m_supplier MS ON PR.supplier_id = MS.id
                JOIN m_status MST ON W.status = MST.value AND MST.code = 'warehouse'
                ORDER BY W.id DESC";

        $row = $this->pdo->prepare($sql);
        $row->execute();
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
                w.id AS prd_id,
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
}

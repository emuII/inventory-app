<?php
class supplierModel
{
    protected $db;
    public function __construct($db)
    {
        $this->db = $db;
    }
    public function get_supplier_list()
    {
        $filter_code    = htmlentities($_POST['filter_code'] ?? '');
        $filter_name    = htmlentities($_POST['filter_name'] ?? '');
        $filter_contact = htmlentities($_POST['filter_contact'] ?? '');
        $filter_status  = htmlentities($_POST['filter_status'] ?? '');

        $sql = "SELECT mst.name as status_name ,mst.desc as status_desc, msp.* FROM m_supplier msp
            JOIN m_status mst ON msp.status = mst.value and mst.code ='general'
            WHERE mst.value != 3";

        $params = [];

        if (!empty($filter_code)) {
            $sql .= " AND msp.supplier_code LIKE ?";
            $params[] = "%$filter_code%";
        }
        if (!empty($filter_name)) {
            $sql .= " AND msp.supplier_name LIKE ?";
            $params[] = "%$filter_name%";
        }
        if (!empty($filter_contact)) {
            $sql .= " AND msp.supplier_contact LIKE ?";
            $params[] = "%$filter_contact%";
        }
        if (!empty($filter_status)) {
            $sql .= " AND msp.status = ?";
            $params[] = $filter_status;
        }

        $sql .= " ORDER BY msp.id DESC";

        $row = $this->db->prepare($sql);
        $row->execute($params);
        return $row->fetchAll();
    }

    public function getSupplierbyCode($supplier_code)
    {
        $sql = "SELECT mst.name AS status_name,
                    mst.Id AS statusId,
                    msp.Id AS supplierId,
                    msp.supplier_code AS supplierCode,
                    msp.supplier_name AS supplierName,
                    msp.supplier_address AS supplierAddress,
                    msp.supplier_contact AS supplierContact,
                    msp.status AS supplierStatus
                FROM m_supplier msp
                    JOIN m_status mst
                        ON msp.status = mst.value
                        AND mst.code = 'general'
                WHERE msp.supplier_code = ?";
        $row = $this->db->prepare($sql);
        $row->execute(array($supplier_code));
        $response = $row->fetch();
        return $response;
    }

    public function get_supplier_active()
    {
        $sql = "SELECT msp.Id supplierId, msp.supplier_code, msp.supplier_name FROM m_supplier msp
                JOIN m_status mst on msp.status = mst.id
                WHERE msp.status NOT IN (1, 3)";
        $row = $this->db->prepare($sql);
        $row->execute();
        $response = $row->fetchAll();
        return $response;
    }

    public function SupplierEncode()
    {
        header('Content-Type: application/json; charset=utf-8');

        $sql = "SELECT msp.Id supplierId, msp.supplier_code, msp.supplier_name
            FROM m_supplier msp
            JOIN m_status mst ON msp.status = mst.id
            WHERE msp.status NOT IN (1, 3)";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $results = array_map(function ($r) {
                return [
                    'id'   => $r['supplierId'],
                    'text' => $r['supplier_code'] . ' - ' . $r['supplier_name']
                ];
            }, $rows);

            echo json_encode(['results' => $results]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => true, 'message' => $e->getMessage()]);
        }
    }
    public function updateSupplier($data)
    {
        $this->db->beginTransaction();
        try {
            $sql = "UPDATE m_supplier
                SET supplier_name = :supplierName,
                    supplier_address = :supplierAddress, 
                    supplier_contact = :supplierContact, 
                    status = :supplierStatus
                WHERE supplier_code = :supplierCode";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':supplierName'  => $data['supplierName'],
                ':supplierAddress'  => $data['supplierAddress'],
                ':supplierContact'  => $data['supplierContact'],
                ':supplierStatus'  => $data['supplierStatus'],
                ':supplierCode'  => $data['supplierCode'],
            ]);
            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function deleteSupplier($supplierCode)
    {
        $this->db->beginTransaction();
        try {
            $sql = "UPDATE m_supplier
                SET status = 3
                WHERE supplier_code = :supplierCode";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':supplierCode'  => $supplierCode,
            ]);
            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}

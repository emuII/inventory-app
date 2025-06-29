<?php
class models_supplier
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

        $sql = "SELECT mst.status_name, msp.* FROM m_supplier msp
            JOIN m_status mst ON msp.supplier_status = mst.value_id
            WHERE mst.value_id != 3";

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
            $sql .= " AND msp.supplier_status = ?";
            $params[] = $filter_status;
        }

        $sql .= " ORDER BY msp.supplier_id DESC";

        $row = $this->db->prepare($sql);
        $row->execute($params);
        return $row->fetchAll();
    }

    public function get_supplier_by_code($supplier_code)
    {
        $sql = "SELECT mst.status_name, mst.status_id, msp.* FROM m_supplier msp
                JOIN m_status mst on msp.supplier_status = mst.value_id
                WHERE msp.supplier_code = ?";
        $row = $this->db->prepare($sql);
        $row->execute(array($supplier_code));
        $response = $row->fetch();
        return $response;
    }

    public function get_supplier_active()
    {
        $sql = "SELECT msp.* FROM m_supplier msp
                JOIN m_status mst on msp.supplier_status = mst.value_id
                WHERE msp.supplier_status NOT IN (1, 3)";
        $row = $this->db->prepare($sql);
        $row->execute();
        $response = $row->fetchAll();
        return $response;
    }
}

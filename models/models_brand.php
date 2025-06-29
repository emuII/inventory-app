<?php
class models_brand
{
    protected $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get_brand_list()
    {
        $filter_code    = htmlentities($_POST['filter_code'] ?? '');
        $filter_name    = htmlentities($_POST['filter_name'] ?? '');
        $filter_status  = htmlentities($_POST['filter_status'] ?? '');

        $sql = "SELECT brn.brand_id,
                       brn.brand_code,
                       brn.brand_name,
                       brn.brand_status,
                       mst.*
                 FROM m_brand brn
                 JOIN m_status mst
                      on brn.brand_status = mst.status_id
                 WHERE brn.brand_status != 3";

        $params = [];
        if (!empty($filter_code)) {
            $sql .= " AND brn.brand_code LIKE ?";
            $params[] = "%$filter_code%";
        }
        if (!empty($filter_name)) {
            $sql .= " AND brn.brand_name LIKE ?";
            $params[] = "%$filter_name%";
        }
        if (!empty($filter_status)) {
            $sql .= " AND brn.brand_status = ?";
            $params[] = $filter_status;
        }

        $sql .= " ORDER BY brn.brand_id DESC";

        $row = $this->db->prepare($sql);
        $row->execute($params);
        return $row->fetchAll();
    }

    public function get_brand_by_code($brand_code)
    {
        $sql = "SELECT mst.status_name, mst.status_id, brn.* FROM m_brand brn
                JOIN m_status mst on brn.brand_status = mst.value_id
                WHERE brn.brand_code = ?";
        $row = $this->db->prepare($sql);
        $row->execute(array($brand_code));
        $response = $row->fetch();
        return $response;
    }

    public function get_brand_active()
    {
        $sql = "SELECT mst.status_name, mst.status_id, brn.* FROM m_brand brn
                JOIN m_status mst on brn.brand_status = mst.value_id
                WHERE brn.brand_status NOT IN (1,3)";
        $row = $this->db->prepare($sql);
        $row->execute();
        $response = $row->fetchAll();
        return $response;
    }
}

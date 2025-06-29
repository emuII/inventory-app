<?php
class models_category
{
    protected $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get_category_list()
    {
        $filter_code    = htmlentities($_POST['filter_code'] ?? '');
        $filter_name    = htmlentities($_POST['filter_name'] ?? '');
        $filter_status  = htmlentities($_POST['filter_status'] ?? '');

        $sql = "SELECT mct.category_id,
       mct.category_code,
       mct.category_name,
       mct.category_desc,
       mct.category_status,
    mst.status_name,
       mst.status_id
FROM m_category mct
    JOIN m_status mst
        ON mct.category_status = mst.value_id
WHERE mct.category_status != 3
";

        $params = [];
        if (!empty($filter_code)) {
            $sql .= " AND mct.category_code LIKE ?";
            $params[] = "%$filter_code%";
        }
        if (!empty($filter_name)) {
            $sql .= " AND mct.category_name LIKE ?";
            $params[] = "%$filter_name%";
        }
        if (!empty($filter_status)) {
            $sql .= " AND mct.category_status = ?";
            $params[] = $filter_status;
        }

        $sql .= " ORDER BY mct.category_id DESC";

        $row = $this->db->prepare($sql);
        $row->execute($params);
        return $row->fetchAll();
    }

    public function get_category_by_code($category_code)
    {
        $sql = "SELECT mst.status_name, mst.status_id, mct.* FROM m_category mct
                JOIN m_status mst on mct.category_status = mst.value_id
                WHERE mct.category_code = ?";
        $row = $this->db->prepare($sql);
        $row->execute(array($category_code));
        $response = $row->fetch();
        return $response;
    }

    public function get_category_active()
    {
        $sql = "SELECT mst.status_name, mst.status_id, mct.* FROM m_category mct
                JOIN m_status mst on mct.category_status = mst.value_id
                WHERE mct.category_status NOT IN(1,3)";
        $row = $this->db->prepare($sql);
        $row->execute();
        $response = $row->fetchAll();
        return $response;
    }
}

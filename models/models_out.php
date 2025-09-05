<?php
class models_out
{
    protected $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get_product_out()
    {
        $filter_name     = htmlentities($_POST['filter_name'] ?? '');
        $filter_category     = htmlentities($_POST['filter_category'] ?? '');
        $filter_brand     = htmlentities($_POST['filter_brand'] ?? '');
        $start     = htmlentities($_POST['start'] ?? '');
        $end     = htmlentities($_POST['end'] ?? '');
        $filter_supplier     = htmlentities($_POST['filter_supplier'] ?? '');
        $filter_type     = htmlentities($_POST['filter_type'] ?? '');

        $sql = "SELECT ot.out_id,
                    ot.product_id,
                    ot.qty_out,
                    ot.selling_price,
                    ot.date_out,
                    ot.note,
                    ot.created_at,
                    ms.supplier_name,
                    mc.category_name,
                    mt.type_name,
                    pd.product_name,
                    mb.brand_id,
                    mb.brand_name
                FROM product_out ot
                    JOIN m_product pd
                        ON ot.product_id = pd.product_id
                    JOIN m_category mc
                        ON pd.category_id = mc.category_id
                    JOIN m_supplier ms
                        ON pd.supplier_id = ms.supplier_id
                    JOIN m_status st
                        ON pd.product_status = st.status_id
                    JOIN m_type mt
                        ON pd.type_id = mt.type_id
                    JOIN m_brand mb
                    ON pd.brand_id = mb.brand_id
                WHERE pd.product_status != 3";
        $params = [];

        if (!empty($filter_name)) {
            $sql .= " AND pd.product_name LIKE ?";
            $params[] = "%$filter_name%";
        }
        if (!empty($filter_category)) {
            $sql .= " AND mc.category_id = ?";
            $params[] = $filter_category;
        }
        if (!empty($filter_brand)) {
            $sql .= " AND mb.brand_id = ?";
            $params[] = $filter_brand;
        }
        if (!empty($start) || !empty($end)) {
            $sql .= " AND ot.date_out BETWEEN ? AND ?";
            $params[] = $start;
            $params[] = $end;
        }
        if (!empty($filter_supplier)) {
            $sql .= " AND ms.supplier_id = ?";
            $params[] = $filter_supplier;
        }
        if (!empty($filter_type)) {
            $sql .= " AND mt.type_id = ?";
            $params[] = $filter_type;
        }
        $sql .= " ORDER BY ot.out_id DESC";

        $row = $this->db->prepare($sql);
        $row->execute($params);
        return $row->fetchAll();
    }
}

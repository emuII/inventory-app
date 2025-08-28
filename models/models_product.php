<?php
class models_product
{
    protected $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get_product_list()
    {
        $filter_code     = htmlentities($_POST['filter_code'] ?? '');
        $filter_name     = htmlentities($_POST['filter_name'] ?? '');
        $filter_status   = htmlentities($_POST['filter_status'] ?? '');
        $filter_category = htmlentities($_POST['filter_category'] ?? '');
        $filter_supplier = htmlentities($_POST['filter_supplier'] ?? '');
        $filter_brand    = htmlentities($_POST['filter_brand'] ?? '');

        $sql = "SELECT prd.product_id,
                        prd.product_code,
                        prd.product_name,
                        prd.category_id,
                        prd.supplier_id,
                        prd.brand_id,
                        prd.product_qty,
                        prd.product_status,
                        FORMAT(prd.purchase_price, 0, 'id-ID') as purchase_price,
                        FORMAT(prd.selling_price, 0, 'id-ID') as selling_price,
                        mct.category_name,
                        mct.category_id,
                        msu.supplier_id,
                        msu.supplier_name,
                        mbr.brand_id,
                        mbr.brand_name,
                        mtp.type_name,
                        mst.*
                    FROM m_product prd
                        JOIN m_category mct
                            ON prd.category_id = mct.category_id
                        JOIN m_supplier msu
                            ON prd.supplier_id = msu.supplier_id
                        JOIN m_brand mbr
                            ON prd.brand_id = mbr.brand_id
                        JOIN m_status mst
                            ON prd.product_status = mst.status_id
                        JOIN m_type mtp
                        ON prd.type_id = mtp.type_id
                    WHERE prd.product_status != 3
                        AND mct.category_status != 3
                        AND msu.supplier_status != 3
                        AND mbr.brand_status != 3";

        $params = [];

        if (!empty($filter_code)) {
            $sql .= " AND prd.product_code LIKE ?";
            $params[] = "%$filter_code%";
        }
        if (!empty($filter_name)) {
            $sql .= " AND prd.product_name LIKE ?";
            $params[] = "%$filter_name%";
        }
        if (!empty($filter_status)) {
            $sql .= " AND prd.product_status = ?";
            $params[] = $filter_status;
        }
        if (!empty($filter_category)) {
            $sql .= " AND prd.category_id = ?";
            $params[] = $filter_category;
        }
        if (!empty($filter_supplier)) {
            $sql .= " AND prd.supplier_id = ?";
            $params[] = $filter_supplier;
        }
        if (!empty($filter_brand)) {
            $sql .= " AND prd.brand_id = ?";
            $params[] = $filter_brand;
        }

        $sql .= " ORDER BY prd.product_id DESC";

        $row = $this->db->prepare($sql);
        $row->execute($params);
        return $row->fetchAll();
    }


    public function get_product_by_code($product_code)
    {
        $sql = "SELECT prd.product_code,
                        prd.product_name,
                        prd.category_id,
                        prd.supplier_id,
                        prd.brand_id,
                        prd.product_qty,
                        prd.purchase_price,
                        prd.selling_price,
                        prd.product_status,
                        mtp.type_id
                    FROM m_product prd
                        JOIN m_category mct
                            ON prd.category_id = mct.category_id
                        JOIN m_supplier msu
                            ON prd.supplier_id = msu.supplier_id
                        JOIN m_brand mbr
                            ON prd.brand_id = mbr.brand_id
                        JOIN m_status mst
                            ON prd.product_status = mst.status_id
                        JOIN m_type mtp
                            ON prd.type_id = mtp.type_id
                        WHERE prd.product_code = ?";
        $row = $this->db->prepare($sql);
        $row->execute(array($product_code));
        $response = $row->fetch();
        return $response;
    }
}

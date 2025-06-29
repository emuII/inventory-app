<?php
class heper_model
{
    protected $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function generate_code($prefix)
    {
        $sql = "";
        $field = "";
        if ($prefix == "SPL") {
            $sql = "SELECT supplier_code FROM m_supplier ORDER BY supplier_id DESC";
            $field = "supplier_code";
        } else if ($prefix == "CAT") {
            $sql = "SELECT category_code FROM m_category ORDER BY category_id DESC";
            $field = "category_code";
        } else if ($prefix == "BRN") {
            $sql = "SELECT brand_code FROM m_brand ORDER BY brand_id DESC";
            $field = "brand_code";
        } else if ($prefix == "PRD") {
            $sql = "SELECT product_code FROM m_product ORDER BY product_id DESC";
            $field = "product_code";
        }

        $row = $this->db->prepare($sql);
        $row->execute();

        $response = $row->fetch();
        $sort = 1;
        if ($response && isset($response[$field])) {
            $number = (int) substr($response[$field], 4);
            $sort = $number + 1;
        }

        $format = $prefix . '_' . str_pad($sort, 5, '0', STR_PAD_LEFT);
        return $format;
    }

    public function get_list_status($category)
    {
        $sql = "SELECT * FROM m_status where status_code =?";
        $row = $this->db->prepare($sql);
        $row->execute(array($category));
        $response = $row->fetchAll();
        return $response;
    }
}

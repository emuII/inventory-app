<?php
class helperModel
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
            $sql = "SELECT supplier_code FROM m_supplier ORDER BY Id DESC";
            $field = "supplier_code";
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

    public function getStatus($code)
    {
        $sql = "SELECT * FROM m_status where code =?";
        $row = $this->db->prepare($sql);
        $row->execute(array($code));
        $response = $row->fetchAll();
        return $response;
    }
}

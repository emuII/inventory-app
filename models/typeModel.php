<?php

class typeModel
{
    protected $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function get_type()
    {
        $sql = "SELECT type_id, type_name, description FROM m_type";
        $row = $this->db->prepare($sql);
        $row->execute();
        $response = $row->fetchAll();
        return $response;
    }
}

<?php
class userModel
{
    protected $db;
    public function __construct($db)
    {
        $this->db = $db;
    }
    public function get_user_by_id($id)
    {
        $sql = "select * from m_user where id= ?";
        $row = $this->db->prepare($sql);
        $row->execute(array($id));
        $response = $row->fetch();
        return $response;
    }
}

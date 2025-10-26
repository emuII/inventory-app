<?php
class approvalMemberModel
{
    protected $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function GetApprovalMember()
    {
        $sql = "SELECT * FROM approval_member";
        $row = $this->db->prepare($sql);
        $row->execute();
        $response = $row->fetchAll();
        return $response;
    }
}

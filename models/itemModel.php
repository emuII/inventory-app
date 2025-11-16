<?php
class itemModel
{
    protected $db;
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function itemList()
    {
        $query = "SELECT itm.Id, itm.item_name, itm.type, itm.category, itm.qty, itm.buy_price, itm.sales_price FROM m_item itm";
        $query .= " ORDER BY itm.id DESC";
        $row = $this->db->prepare($query);
        $row->execute();
        return $row->fetchAll();
    }

    public function ItemsEncode()
    {
        header('Content-Type: application/json; charset=utf-8');

        $sql = "SELECT itm.Id, itm.item_name, itm.type, itm.category, itm.qty,
                 itm.buy_price, itm.sales_price FROM m_item itm";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Format hasil sesuai kebutuhan Select2
            $results = array_map(function ($r) {
                return [
                    'id'   => $r['Id'],
                    'text' => $r['item_name'] . ' - ' . $r['type'] . ' (' . $r['category'] . ')'
                ];
            }, $rows);

            echo json_encode(['results' => $results]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => true, 'message' => $e->getMessage()]);
        }
    }
}

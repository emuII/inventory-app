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
        $filter_name    = htmlentities($_POST['filter_name'] ?? '');
        $filert_type    = htmlentities($_POST['filter_type'] ?? '');

        $query = "SELECT itm.Id,
                    itm.item_name,
                    itm.type,
                    itm.category,
                    itm.qty,
                    FORMAT(itm.buy_price, 0, 'id_ID') AS buy_price,
                    FORMAT(itm.sales_price, 0, 'id_ID') AS sales_price
                FROM m_item itm
                WHERE 1=1 ";

        $params = [];

        if (!empty($filter_name)) {
            $query .= " AND itm.item_name LIKE ?";
            $params[] = "%$filter_name%";
        }
        if (!empty($filert_type)) {
            $query .= " AND itm.type LIKE ?";
            $params[] = "%$filert_type%";
        }
        $query .= " ORDER BY itm.id DESC";
        $row = $this->db->prepare($query);
        $row->execute($params);
        return $row->fetchAll();
    }

    public function ItemsEncode()
    {
        header('Content-Type: application/json; charset=utf-8');

        // ambil keyword dari select2
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';

        $sql = "SELECT itm.Id,
                   itm.item_name,
                   itm.type,
                   itm.category,
                   itm.qty,
                   itm.buy_price,
                   itm.sales_price
            FROM m_item itm";

        $params = [];

        if ($q !== '') {
            $sql .= " WHERE itm.item_name LIKE :q
                  OR itm.type LIKE :q
                  OR itm.category LIKE :q";
            $params[':q'] = '%' . $q . '%';
        }

        $sql .= " ORDER BY itm.item_name ASC LIMIT 50";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $results = array_map(function ($r) {
                return [
                    'id'   => (int)$r['Id'],
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

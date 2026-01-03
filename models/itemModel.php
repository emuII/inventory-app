<?php
class itemModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
        $row = $this->pdo->prepare($query);
        $row->execute($params);
        return $row->fetchAll();
    }

    public function ItemsEncode()
    {
        header('Content-Type: application/json; charset=utf-8');

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
            $stmt = $this->pdo->prepare($sql);
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

    public function getItemById($itemId)
    {
        $query = "SELECT 
                Id AS itemId,
                item_name AS itemName,
                type AS itemType,
                category AS itemCategory,
                qty,
                buy_price AS buyPrice,
                FORMAT(sales_price, 0, 'id_ID') AS salesPrice
              FROM m_item
              WHERE Id = ?";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$itemId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function checkItemExists($item_name, $category, $type, $exclude_id = null)
    {
        $query = "SELECT COUNT(*) as count FROM m_item 
              WHERE LOWER(item_name) = LOWER(:item_name) 
              AND LOWER(category) = LOWER(:category) 
              AND LOWER(type) = LOWER(:type)";

        if ($exclude_id !== null) {
            $query .= " AND id != :exclude_id";
        }

        $stmt = $this->pdo->prepare($query);
        $params = [
            ':item_name' => $item_name,
            ':category'  => $category,
            ':type'      => $type
        ];

        if ($exclude_id !== null) {
            $params[':exclude_id'] = $exclude_id;
        }

        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['count'] > 0;
    }

    public function addItem($data)
    {
        $this->pdo->beginTransaction();
        try {
            if ($this->checkItemExists($data['item_name'], $data['category'], $data['type'])) {
                $this->pdo->rollBack();
                return [
                    'success' => false,
                    'message' => "Item dengan nama '{$data['item_name']}' sudah ada dalam database dengan kategori dan tipe yang sama."
                ];
            }

            $query = "INSERT INTO m_item (item_name, type, category, qty, sales_price)
                  VALUES (:item_name, :type, :category, :qty, :sales_price)";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':item_name'  => $data['item_name'],
                ':type'       => $data['type'],
                ':category'   => $data['category'],
                ':qty'        => $data['qty'],
                ':sales_price' => $data['sales_price']
            ]);

            $this->pdo->commit();

            return [
                'success' => true,
                'message' => "Item {$data['item_name']} berhasil ditambahkan."
            ];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return [
                'success' => false,
                'message' => "Terjadi kesalahan: " . $e->getMessage()
            ];
        }
    }

    public function editItem($data)
    {
        $this->pdo->beginTransaction();
        try {
            $checkIdQuery = "SELECT item_name, type, category FROM m_item WHERE id = :id";
            $checkIdStmt = $this->pdo->prepare($checkIdQuery);
            $checkIdStmt->execute([':id' => $data['itemId']]);
            $existingItem = $checkIdStmt->fetch(PDO::FETCH_ASSOC);

            if (!$existingItem) {
                $this->pdo->rollBack();
                return [
                    'success' => false,
                    'message' => "Item dengan ID {$data['itemId']} tidak ditemukan."
                ];
            }

            $isSameData = (
                strtolower($existingItem['item_name']) === strtolower($data['item_name']) &&
                strtolower($existingItem['type']) === strtolower($data['type']) &&
                strtolower($existingItem['category']) === strtolower($data['category'])
            );

            if ($isSameData) {
                $query = "UPDATE m_item 
                      SET qty = :qty, 
                          sales_price = :sales_price,
                          updated_at = NOW()
                      WHERE id = :id";

                $stmt = $this->pdo->prepare($query);
                $stmt->execute([
                    ':qty'         => $data['qty'],
                    ':sales_price' => $data['sales_price'],
                    ':id'          => $data['itemId']
                ]);

                $this->pdo->commit();

                return [
                    'success' => true,
                    'message' => "Item {$data['item_name']} berhasil diperbarui.",
                    'is_same_item' => true
                ];
            }

            if ($this->checkItemExists($data['item_name'], $data['category'], $data['type'], $data['itemId'])) {
                $this->pdo->rollBack();
                return [
                    'success' => false,
                    'message' => "Item dengan nama '{$data['item_name']}' sudah ada dalam database dengan kategori dan tipe yang sama.",
                    'needs_confirmation' => true,
                    'duplicate_info' => [
                        'item_name' => $data['item_name'],
                        'category' => $data['category'],
                        'type' => $data['type']
                    ]
                ];
            }

            $query = "UPDATE m_item 
                  SET item_name = :item_name, 
                      type = :type, 
                      category = :category, 
                      qty = :qty, 
                      sales_price = :sales_price,
                      updated_at = NOW()
                  WHERE id = :id";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':item_name'   => $data['item_name'],
                ':type'        => $data['type'],
                ':category'    => $data['category'],
                ':qty'         => $data['qty'],
                ':sales_price' => $data['sales_price'],
                ':id'          => $data['itemId']
            ]);

            $this->pdo->commit();

            return [
                'success' => true,
                'message' => "Item {$data['item_name']} berhasil diperbarui.",
                'is_same_item' => false
            ];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return [
                'success' => false,
                'message' => "Terjadi kesalahan: " . $e->getMessage()
            ];
        }
    }

    public function confirmEditItem($data)
    {
        $this->pdo->beginTransaction();
        try {
            $query = "UPDATE m_item 
                  SET item_name = :item_name, 
                      type = :type, 
                      category = :category, 
                      qty = :qty, 
                      sales_price = :sales_price,
                      updated_at = NOW()
                  WHERE id = :id";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':item_name'   => $data['item_name'],
                ':type'        => $data['type'],
                ':category'    => $data['category'],
                ':qty'         => $data['qty'],
                ':sales_price' => $data['sales_price'],
                ':id'          => $data['itemId']
            ]);

            $this->pdo->commit();

            return [
                'success' => true,
                'message' => "Item {$data['item_name']} berhasil diperbarui."
            ];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return [
                'success' => false,
                'message' => "Terjadi kesalahan: " . $e->getMessage()
            ];
        }
    }
}

<?php
class itemController
{
    protected $model;
    public function __construct($db)
    {
        $this->model = new itemModel($db);
    }

    public function getItemList()
    {
        $data = $this->model->itemList();

        if (empty($data)) {
            echo '<tr><td colspan="8" style="text-align: center;">No product found.</td></tr>';
            return;
        }

        foreach ($data as $index => $row) {
            $qs = http_build_query(['itemId' => $row['Id']]);
            $itemName = htmlspecialchars($row['item_name'], ENT_QUOTES, 'UTF-8');
            $type = htmlspecialchars($row['type'], ENT_QUOTES, 'UTF-8');
            $category = htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8');
            $qty = htmlspecialchars($row['qty'], ENT_QUOTES, 'UTF-8');
            $buyPrice = htmlspecialchars($row['buy_price'], ENT_QUOTES, 'UTF-8');
            $salesPrice = htmlspecialchars($row['sales_price'], ENT_QUOTES, 'UTF-8');
            echo "<tr>
                <td style='width: 5%;'>" . ($index + 1) . "</td>
                    <td>$itemName</td>
                    <td>$type</td>
                    <td>$category</td>
                    <td>$qty</td>
                    <td>$buyPrice</td>
                    <td>$salesPrice</td>
                    <td>";
            if (
                isset($_SESSION['active_login']['role']) &&
                $_SESSION['active_login']['role'] === 'super_admin'
            ) {
                echo "
                        <a class='btn btn-sm btn-outline-primary action-btn'
                        href='index.php?route=item/EditSingleItem&{$qs}'>
                        <i class='fa fa-edit'></i>
                        </a>";
            }
            echo "</td></tr>";
        }
    }

    public function EditSingleItem()
    {
        $itemId = $_GET['itemId'] ?? null;
        if (!$itemId) {
            echo "item Id not found.";
            return;
        }
        $constHeader = $this->model->getItemById($itemId);
        include 'views/Items/editSingleItem.php';
    }


    public function GetItemEncode()
    {
        try {
            $this->model->ItemsEncode();
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    public function addSingleItem()
    {
        include 'views/Items/addSingleItem.php';
    }

    public function createSingleItem()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            // Validasi input
            if (empty($data['item_name']) || empty($data['category']) || empty($data['type'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Semua field harus diisi.'
                ]);
                return;
            }
            $result = $this->model->addItem($data);

            echo json_encode($result);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateSingleItem()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data['item_name']) || empty($data['category']) || empty($data['type'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Semua field harus diisi.'
                ]);
                return;
            }
            $result = $this->model->editItem($data);

            echo json_encode($result);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function confirmUpdateItem()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data['item_name']) || empty($data['category']) || empty($data['type'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Semua field harus diisi.'
                ]);
                return;
            }
            $result = $this->model->confirmEditItem($data);

            echo json_encode($result);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
class itemController
{
    protected $model;
    public function __construct($db)
    {
        $this->model = new itemModel($db);
    }


    public function getItemLists()
    {
        $role_id = ($_SESSION['active_login']['role'] ?? null);
        $items = $this->model->itemList();

        // Tambahkan role_id ke setiap item
        $data = array_map(function ($item) use ($role_id) {
            $item['role_id'] = $role_id;
            return $item;
        }, $items);

        helperModel::json(200, 'Success', $data);
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

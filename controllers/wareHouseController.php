<?php
class wareHouseController
{
    protected $warehouse;
    public function __construct($db)
    {
        $this->warehouse = new wareHouseModel($db);
    }

    public function getWarehouseList()
    {
        $data = $this->warehouse->warehouseList();
        helperModel::json(200, 'Success', $data);
    }



    public function wareHouseDetail()
    {
        $warehouseId = $_GET['warehouseId'] ?? null;
        if (!$warehouseId) {
            echo "warehouse Id not found.";
            return;
        }
        $whDetail = $this->warehouse->wareHouseDetail($warehouseId);
        $whHistory =  $this->warehouse->wareHouseHistory($warehouseId);
        include 'views/WareHouses/wareHouseDetail.php';
    }

    public function submitWareHouse()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $payload = json_decode(file_get_contents('php://input'), true);

        try {
            $warehouseId = $this->warehouse->submitWareHouse($payload);
            echo json_encode(['ok' => true, 'message' => 'Created', 'warehouseId' => $warehouseId]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
        echo "Success";
    }
}

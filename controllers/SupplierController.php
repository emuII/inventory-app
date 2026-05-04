<?php
class supplierController
{
    protected $model;
    protected $helper_model;
    public function __construct($db)
    {
        $this->model = new supplierModel($db);
        $this->helper_model = new helperModel($db);
    }

    public function getSupplierList()
    {
        $data = $this->model->get_supplier_list();
        helperModel::json(200, 'Success', $data);
    }

    public function GetSupplierEncode()
    {
        try {
            $this->model->supplierEncode();
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    public function SupplierDetail()
    {
        $supplierCode = $_GET['supplierCode'] ?? null;
        if (!$supplierCode) {
            echo "supplier Code not found.";
            return;
        }

        $response = $this->model->getSupplierbyCode($supplierCode);
        $helper = $this->helper_model->getStatus("general");
        include 'views/Suppliers/SupplierDetail.php';
    }

    public function updateSupplier()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $payload = json_decode(file_get_contents('php://input'), true);

        try {
            $supplier = $this->model->updateSupplier($payload);
            echo json_encode(['ok' => true, 'message' => 'Updated', 'supplier' => $supplier]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
        echo "Success";
    }

    public function deleteSupplier()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $supplierCode = $_POST['supplierCode'] ?? null;
        if (!$supplierCode) {
            echo json_encode(['ok' => false, 'error' => 'Supplier code is required.']);
            return;
        }

        try {
            $this->model->deleteSupplier($supplierCode);
            echo json_encode(['ok' => true, 'message' => 'Supplier deleted successfully.']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

    public function createSupplier()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $payload = json_decode(file_get_contents('php://input'), true);

        try {
            $prId = $this->model->createSupplier($payload);
            echo json_encode(['ok' => true, 'message' => 'Created', 'prId' => $prId]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }
}

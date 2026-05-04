<?php
class approvalController
{
    protected $model;
    protected $warehouse;
    private $request;
    private $helper;
    public function __construct($db)
    {
        $this->model = new approvalModel($db);
        $this->warehouse = new wareHouseModel($db);
        $this->request = new PurchaseRequestModel($db);
        $this->helper = new helperModel($db);
    }

    public function approvalList()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $approverId = (int)$_SESSION['active_login']['id'] ?? null;
        $data = $this->model->approvalList($approverId);
        helperModel::json(200, 'Success', $data);
    }

    public function submitAproval()
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $payload = json_decode(file_get_contents('php://input'), true);

        try {
            $response = $this->model->submit($payload['prId'], $payload['approvalStatus']);
            $warehouse = $this->warehouse->draftWarehouse($payload['prId']);
            echo json_encode(['success' => true, 'message' => 'Created', 'response' => $response]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}

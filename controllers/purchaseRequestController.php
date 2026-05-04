<?php
class purchaseRequestController
{

    private PurchaseRequestModel $model;
    private helperModel $helper;

    public function __construct(PDO $pdo)
    {
        $this->model = new PurchaseRequestModel($pdo);
        $this->helper = new helperModel($pdo);
    }

    public function requestList()
    {
        $data = $this->model->requestList();
        helperModel::json(200, 'Success', $data);
    }

    public function cancelRequest()
    {
        $requestNumber = $_POST['requestNumber'] ?? null;
        if (!$requestNumber) {
            echo json_encode(['ok' => false, 'error' => 'Request number is required.']);
            return;
        }

        try {
            $this->model->cancelRequest($requestNumber);
            echo json_encode(['ok' => true, 'message' => 'Request cancelled successfully.']);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

    public function previewRequest()
    {
        $requestNumber = $_GET['requestNumber'] ?? null;
        if (!$requestNumber) {
            echo "Request number not found.";
            return;
        }
        $dataHeader = $this->model->requestHeader($requestNumber);
        $dataDetail = $this->model->requestDetails($requestNumber);
        $helper = $this->helper->getStatus("transaction");
        include 'views/MyRequest/previewRequest.php';
    }

    public function requestDetail()
    {
        $requestNumber = $_GET['requestNumber'] ?? null;
        if (!$requestNumber) {
            echo "Request number not found.";
            return;
        }
        $dataHeader = $this->model->requestHeader($requestNumber);
        $dataDetail = $this->model->requestDetails($requestNumber);
        $helper = $this->helper->getStatus("transaction");
        include 'views/myapproval/approvalDetail.php';
    }

    public function store()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        $requesterId = (int)$_SESSION['active_login']['id'] ?? null;

        try {
            $prId = $this->model->createAll($payload, $requesterId);
            echo json_encode(['ok' => true, 'message' => 'Created', 'prId' => $prId]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }
}

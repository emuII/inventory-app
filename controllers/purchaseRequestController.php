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

        if (empty($data)) {
            echo '<tr><td colspan="7" style="text-align: center;">No request found.</td></tr>';
            return;
        }

        foreach ($data as $index => $row) {
            echo "<tr>
                <td style='width: 5%;'>" . ($index + 1) . "</td>
                <td>{$row['requestNumber']}</td>
                <td>{$row['requestDate']}</td>
                <td>{$row['username']}</td>
                <td><label class=" . $row['statusName'] . ">{$row['statusName']}</label></td>
                <td>{$row['supplier_name']}</td>
                <td></td>
                </tr>";
        }
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
        $helper = $this->helper->getStatus("approval");
        include 'views/myapproval/approvalDetails.php';
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
            echo json_encode(['ok' => true, 'message' => 'Created', 'pr_id' => $prId]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }
}

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
            $requestNumber = htmlspecialchars($row['requestNumber']);
            $requestDate = htmlspecialchars($row['requestDate']);
            $username = htmlspecialchars($row['username']);
            $statusName = htmlspecialchars($row['statusName']);
            $supplierName = htmlspecialchars($row['supplier_name']);

            $qs = http_build_query(['requestNumber' => $row['requestNumber']]);

            echo "<tr>
                <td style='width: 5%;'>" . ($index + 1) . "</td>
                <td>{$requestNumber}</td>
                <td>{$requestDate}</td>
                <td>{$username}</td>
                <td><label class='status-badge {$statusName}'>{$statusName}</label></td>
                <td>{$supplierName}</td>
                <td>";
            if ($row['statusId'] == '2') {
                $poUrl = base_url('export/pdf/generatePo.php?requestNumber=' . $row['requestNumber']);
                echo "<a href='" . $poUrl . "' class='btn btn-sm btn-outline-success action-btn'><i class='fa-solid fa-download'></i></a>";
            } else if ($row['statusId'] == '1') {
                echo '<a class="btn btn-sm btn-outline-danger action-btn"
                        onclick="cancelRequest(\'' . $requestNumber . '\')"
                        title="Cancel Request">
                        <i class="fa-solid fa-trash"></i>
                    </a>';
            }
            echo "<a href='index.php?route=purchaseRequest/previewRequest&{$qs}' class='btn btn-sm btn-outline-primary action-btn' title='Preview'><i class='fa-solid fa-eye'></i></a>";

            echo "</td></tr>";
        }
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

<?php
class approvalController
{
    protected $model;
    protected $warehouse;
    public function __construct($db)
    {
        $this->model = new approvalModel($db);
        $this->warehouse = new wareHouseModel($db);
    }

    public function approvalList()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $approverId = (int)$_SESSION['active_login']['id'] ?? null;
        $data = $this->model->approvalList($approverId);

        if (empty($data)) {
            echo '<tr><td colspan="7" style="text-align: center;">No request found.</td></tr>';
            return;
        }

        foreach ($data as $index => $row) {
            $reqNum   = htmlspecialchars($row['requestNumber']);
            $reqDate  = htmlspecialchars($row['requestDate']);
            $username = htmlspecialchars($row['username']);
            $status   = htmlspecialchars($row['statusName']);
            $supplier = htmlspecialchars($row['supplier_name']);

            $qs = http_build_query(['requestNumber' => $row['requestNumber']]);

            echo "<tr>
            <td style='width:5%;'>" . ($index + 1) . "</td>
            <td>{$reqNum}</td>
            <td>{$reqDate}</td>
            <td>{$username}</td>
            <td><label class='status-badge {$status}'>{$status}</label></td>
            <td>{$supplier}</td>
            <td>";
            if ($row['statusId'] != '2') {
                echo "<a class='btn btn-sm btn-outline-primary action-btn' href='index.php?route=purchaseRequest/requestDetail&{$qs}' class='btn btn-sm btn-primary'><i class='fa fa-edit'></i></a>";
            }
            if ($row['statusId'] == '2') {
                $poUrl = base_url('export/pdf/generatePo.php?requestNumber=' . $row['requestNumber']);
                echo "<a href='" . $poUrl . "' class='btn btn-sm btn-success'><i class='fa-solid fa-download'></i></a>";
            }
            echo "</td></tr>";
        }
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

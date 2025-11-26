<?php
class wareHouseController
{
    protected $warehouse;
    public function __construct($db)
    {
        $this->warehouse = new wareHouseModel($db);
    }

    public function warehouseList()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $data = $this->warehouse->warehouseList();

        if (empty($data)) {
            echo '<tr><td colspan="11" style="text-align: center;">No request found.</td></tr>';
            return;
        }

        foreach ($data as $index => $row) {

            $warehouseId   = $row['warehouseId'] ?? '';
            $requestNumber = htmlspecialchars($row['requestNumber']   ?? '');
            $dateIn        = htmlspecialchars($row['dateIn']          ?? '-');
            $username      = htmlspecialchars($row['requestedBy']     ?? '');
            $supplier      = htmlspecialchars($row['supplierName']    ?? '');
            $status        = htmlspecialchars($row['statusName']      ?? '');
            $totalAmount   = htmlspecialchars((string)($row['totalAmount'] ?? '0'));
            $unitPrice   = htmlspecialchars((string)($row['unitPrice'] ?? '0'));
            $orderQty  = htmlspecialchars((string)($row['orderQty'] ?? '0'));
            $receiveQty  = htmlspecialchars((string)($row['receiveQty'] ?? '0'));

            $qs = http_build_query(['warehouseId' => $row['warehouseId']]);

            echo "<tr style='text-align: center;'>
            <td style='width:4%;'>" . ($index + 1) . "</td>
            <td style='width:14%;'>{$requestNumber}</td>
            <td style='width:10%; text-align:right;'>{$unitPrice}</td>
            <td style='width:9%;'>{$orderQty}</td>
            <td style='width:9%;'>{$receiveQty}</td>
            <td style='width:12%; text-align:right;'>{$totalAmount}</td>
            <td style='width:9%;'>{$dateIn}</td>
            <td style='width:13%;'>{$supplier}</td>
            <td style='width:13%;'>{$username}</td>
            <td style='width:9%;'>
                <label class='status-badge {$status}'>{$status}</label>
            </td>
            <td style='width:8%;'>";

            if ($row['statusName'] != 'Complete') {
                echo "<a class='btn btn-sm btn-outline-primary action-btn' href='index.php?route=warehouse/wareHouseDetail&{$qs}' class='btn btn-sm btn-primary'><i class='fa fa-edit'></i></a>";
            }
            echo "</td></tr>";
        }
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
        include 'views/wareHouse_/wareHouseDetail.php';
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

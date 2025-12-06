<?php
class deliveryOrderLogController
{
    protected $log;
    public function __construct($db)
    {
        $this->log = new deliveryOrderLogModel($db);
    }
    public function fetchLogs()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $data = $this->log->getDeliveryOrderLogs();

        if (empty($data)) {
            echo '<tr><td colspan="11" style="text-align: center;">No request found.</td></tr>';
            return;
        }
        foreach ($data as $index => $row) {
            $doId        = $row['doId'] ?? '';
            $doCode      = htmlspecialchars($row['doCode'] ?? '');
            $doDate      = htmlspecialchars($row['doDate'] ?? '-');
            $totalAmount = htmlspecialchars((string)($row['totalAmount'] ?? '0'));
            $tax         = htmlspecialchars((string)($row['tax'] ?? '0'));
            $status      = htmlspecialchars($row['statusName'] ?? '');

            $qs = http_build_query(['doNumber' => $row['doCode']]);
            echo "<tr >
            <td style='width:4%;'>" . ($index + 1) . "</td>
            <td style='width:14%;'>{$doCode}</td>
            <td style='width:14%;'>{$doDate}</td>
            <td style='width:12%;'>{$totalAmount}</td>
            <td style='width:9%;'>{$tax}</td>
            <td style='width:13%;'><label class='status-badge {$status}'>{$status}</label></td>
            <td style='width:8%;'>
               <a href='index.php?route=deliveryOrderLog/deliveryOrderLog&{$qs}' class='btn btn-sm btn-outline-primary action-btn' title='Preview'><i class='fa-solid fa-eye'></i></a>
            </td></tr>";
        }
    }


    public function deliveryOrderLog()
    {
        $doNumber = $_GET['doNumber'] ?? null;
        if (!$doNumber) {
            echo "Request number not found.";
            return;
        }
        $logDetail = $this->log->deliveryLogOrderDetails($doNumber);
        include 'views/DeliveryOrderLogs/deliveryOrderLog.php';
    }
}

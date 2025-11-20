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
            echo '<tr><td colspan="7" style="text-align: center;">No request found.</td></tr>';
            return;
        }

        foreach ($data as $index => $row) {

            $warehouseId   = $row['warehouseId'] ?? '';
            $requestNumber = htmlspecialchars($row['requestNumber']   ?? '');
            $dateIn        = htmlspecialchars($row['dateIn']          ?? '-');
            $username      = htmlspecialchars($row['requestedBy']     ?? '');
            $supplier      = htmlspecialchars($row['supplierName']    ?? '');
            $status        = htmlspecialchars($row['statusName']      ?? '');
            $totalAmount   = htmlspecialchars((string)($row['totalAmount'] ?? '-'));
            echo "<tr style='text-align: center;'>
            <td style='width:5%;'>" . ($index + 1) . "</td>
            <td>{$requestNumber}</td>
            <td>{$dateIn}</td>
            <td>{$username}</td>
            <td>{$totalAmount}</td>
            <td>{$supplier}</td>
            <td><label class='status {$status}'>{$status}</label></td>
            <td>
                <!-- tombol action nanti di sini -->
            </td>
        </tr>";
        }
    }
}

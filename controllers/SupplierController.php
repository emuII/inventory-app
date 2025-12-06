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

    public function supplier_list()
    {
        $data = $this->model->get_supplier_list();

        if (empty($data)) {
            echo '<tr><td colspan="7" style="text-align: center;">No supplier found.</td></tr>';
            return;
        }

        foreach ($data as $index => $row) {

            $qs = http_build_query(['supplierCode' => $row['supplier_code']]);
            $supplierName = htmlspecialchars($row['supplier_name']);
            $supplierAddress = htmlspecialchars($row['supplier_address']);
            $statusName = htmlspecialchars($row['status_name']);
            $supplierContact = htmlspecialchars($row['supplier_contact']);
            $supplierCode = htmlspecialchars($row['supplier_code']);

            echo "<tr>
                <td style='width: 5%;'>" . ($index + 1) . "</td>
                <td>{$supplierCode}</td>
                <td>{$supplierName}</td>
                <td>{$supplierAddress}</td>
                <td><label class='status-badge {$statusName}'>{$statusName}</label></td>
                <td>{$supplierContact}</td>
                <td>";
            echo "<a class='btn btn-sm btn-outline-primary action-btn' href='index.php?route=supplier/SupplierDetail&{$qs}' class='btn btn-sm btn-primary'><i class='fa fa-edit'></i></a>";
            echo '<a class="btn btn-sm btn-outline-danger action-btn"
                        onclick="deleteSupplier(\'' . $supplierCode . '\')"
                        title="Cancel Request">
                        <i class="fa-solid fa-trash"></i>
                    </a>';
            echo "</td></tr>";
        }
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
}

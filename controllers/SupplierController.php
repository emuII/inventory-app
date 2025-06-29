<?php
class SupplierController
{
    protected $model;
    public function __construct($db)
    {
        $this->model = new models_supplier($db);
    }

    public function supplier_list()
    {
        $data = $this->model->get_supplier_list();

        if (empty($data)) {
            echo '<tr><td colspan="7" style="text-align: center;">No supplier found.</td></tr>';
            return;
        }

        foreach ($data as $index => $row) {
            echo "<tr>
    <td style='width: 5%;'>" . ($index + 1) . "</td>
    <td>{$row['supplier_code']}</td>
    <td>{$row['supplier_name']}</td>
    <td>{$row['supplier_address']}</td>
   <td><label class=" . $row['status_desc'] . ">{$row['status_name']}</label></td>
    <td>{$row['supplier_contact']}</td>
       <td>
                    <a href='index.php?route=supplier/edit&supplier_code={$row['supplier_code']}' class='btn btn-sm btn-primary'>Edit</a>
                    <a href='service/supplierService.php?delete_supplier=delete&supplier_code={$row['supplier_code']}'
                        onclick='return confirm(\"Delete this supplier?\")' class='btn btn-sm btn-danger'>Delete</a>
                </td>
</tr>";
        }
    }
}

<?php
class ProductOutController
{
    protected $model;
    public function __construct($db)
    {
        $this->model = new models_out($db);
    }
    public function GetProductOut()
    {
        $data = $this->model->get_product_out();
        if (empty($data)) {
            echo '<tr><td colspan="10" style="text-align: center;">No product found.</td></tr>';
            return;
        }
        foreach ($data as $index => $row) {
            echo "<tr>
                    <td style='width: 5%;'>" . ($index + 1) . "</td>
                    <td style='width: 15%;'>{$row['product_name']}</td>
                    <td style='width: 10%;'>{$row['supplier_name']}</td>
                    <td style='width: 10%;'>{$row['category_name']}</td>
                    <td style='width: 10%;'>{$row['type_name']}</td>
                    <td style='width: 10%;'>{$row['brand_name']}</td>
                    <td style='width: 5%;'>{$row['qty_out']}</td>
                    <td style='width: 10%;'>{$row['selling_price']}</td>
                    <td style='width: 10%;'>{$row['date_out']}</td>
                    <td style='width: 15%;'>
                        <a href='index.php?route=productOut/edit&out_id={$row['out_id']}' class='btn btn-sm btn-primary'>Edit</a>
                        <a href='service/productOutService.php?delete_sales=delete&out_id={$row['out_id']}'
                            onclick='return confirm(\"Delete this Sales Product?\")' class='btn btn-sm btn-danger'>Delete</a>
                    </td>
                </tr>";
        }
    }
}

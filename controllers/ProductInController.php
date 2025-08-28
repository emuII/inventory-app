<?php
class ProductInController
{
    protected $model;
    public function __construct($db)
    {
        $this->model = new models_product($db);
    }

    public function product_list()
    {
        $data = $this->model->get_product_list();

        if (empty($data)) {
            echo '<tr><td colspan="10" style="text-align: center;">No product found.</td></tr>';
            return;
        }

        foreach ($data as $index => $row) {
            echo "<tr>
    <td style='width: 5%;'>" . ($index + 1) . "</td>
    <td style='width: 10%;'>{$row['product_code']}</td>
    <td style='width: 15%;'>{$row['product_name']}</td>
    <td style='width: 10%;'>{$row['category_name']}</td>
    <td style='width: 10%;'>{$row['type_name']}</td>
    <td style='width: 10%;'>{$row['brand_name']}</td>
    <td style='width: 5%;'>{$row['product_qty']}</td>
    <td style='width: 10%;'>{$row['purchase_price']}</td>
    <td style='width: 10%;'>{$row['selling_price']}</td>
    <td style='width: 10%;'>{$row['supplier_name']}</td>
    <td style='width: 10%;'><label class='" . $row['status_desc'] . "'>{$row['status_name']}</label></td>
    <td style='width: 15%;'>
        <a href='index.php?route=productIn/edit&product_code={$row['product_code']}' class='btn btn-sm btn-primary'>Edit</a>
        <a href='service/brandService.php?delete_brand=delete&product_code={$row['product_code']}'
            onclick='return confirm(\"Delete this supplier?\")' class='btn btn-sm btn-danger'>Delete</a>
    </td>
</tr>";
        }
    }
}

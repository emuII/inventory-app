<?php
class ProductController
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
    <td>{$row['product_code']}</td>
    <td>{$row['product_name']}</td>
    <td>{$row['category_name']}</td>
    <td>{$row['supplier_name']}</td>
    <td>{$row['brand_name']}</td>
    <td>{$row['product_qty']}</td>
    <td>{$row['product_price']}</td>
    <td><label class=" . $row['status_desc'] . ">{$row['status_name']}</label></td>
       <td>
                    <a href='index.php?route=brand/edit&brand_code={$row['product_code']}' class='btn btn-sm btn-primary'>Edit</a>
                    <a href='service/brandService.php?delete_brand=delete&brand_code={$row['product_code']}'
                        onclick='return confirm(\"Delete this supplier?\")' class='btn btn-sm btn-danger'>Delete</a>
                </td>
</tr>";
        }
    }
}

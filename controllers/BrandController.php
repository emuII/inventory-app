<?php
class BrandController
{
    protected $model;
    public function __construct($db)
    {
        $this->model = new models_brand($db);
    }

    public function brand_list()
    {
        $data = $this->model->get_brand_list();

        if (empty($data)) {
            echo '<tr><td colspan="7" style="text-align: center;">No brand found.</td></tr>';
            return;
        }

        foreach ($data as $index => $row) {
            echo "<tr>
    <td style='width: 5%;'>" . ($index + 1) . "</td>
    <td>{$row['brand_code']}</td>
    <td>{$row['brand_name']}</td>
    <td><label class=" . $row['status_desc'] . ">{$row['status_name']}</label></td>
       <td>
                    <a href='index.php?route=brand/edit&brand_code={$row['brand_code']}' class='btn btn-sm btn-primary'>Edit</a>
                    <a href='service/brandService.php?delete_brand=delete&brand_code={$row['brand_code']}'
                        onclick='return confirm(\"Delete this supplier?\")' class='btn btn-sm btn-danger'>Delete</a>
                </td>
</tr>";
        }
    }
}

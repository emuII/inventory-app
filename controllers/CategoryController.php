<?php
class CategoryController
{
    protected $model;
    public function __construct($db)
    {
        $this->model = new models_category($db);
    }

    public function category_list()
    {
        $data = $this->model->get_category_list();

        if (empty($data)) {
            echo '<tr><td colspan="7" style="text-align: center;">No category found.</td></tr>';
            return;
        }

        foreach ($data as $index => $row) {
            echo "<tr>
    <td style='width: 5%;'>" . ($index + 1) . "</td>
    <td>{$row['category_code']}</td>
    <td>{$row['category_name']}</td>
    <td><label class=" . $row['status_desc'] . ">{$row['status_name']}</label></td>
       <td>
                    <a href='index.php?route=category/edit&category_code={$row['category_code']}' class='btn btn-sm btn-primary'>Edit</a>
                    <a href='service/categoryService.php?delete_category=delete&category_code={$row['category_code']}'
                        onclick='return confirm(\"Delete this category?\")' class='btn btn-sm btn-danger'>Delete</a>
                </td>
</tr>";
        }
    }
}

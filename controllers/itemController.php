<?php
class itemController
{
    protected $model;
    public function __construct($db)
    {
        $this->model = new itemModel($db);
    }

    public function getItemList()
    {
        $data = $this->model->itemList();

        if (empty($data)) {
            echo '<tr><td colspan="7" style="text-align: center;">No product found.</td></tr>';
            return;
        }

        foreach ($data as $index => $row) {
            echo "<tr>
                <td style='width: 5%;'>" . ($index + 1) . "</td>
                <td>{$row['item_name']}</td>
                <td>{$row['type']}</td>
                <td>{$row['category']}</td>
                <td>{$row['qty']}</td>
                <td>{$row['buy_price']}</td>
                <td>{$row['sales_price']}</td>
                </tr>";
        }
    }

    public function GetItemEncode()
    {
        try {
            $this->model->ItemsEncode();
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    public function addSingleItem()
    {
        include 'views/Items/addSingleItem.php';
    }
}

<?php
class deliveryOrderController
{
    protected $order;
    public function __construct($db)
    {
        $this->order = new deliveryOrderModel($db);
    }

    public function getProductList()
    {
        header('Content-Type: application/json');

        $data = $this->order->getProductList();

        echo json_encode([
            'success' => true,
            'data'    => $data
        ]);
        exit;
    }
}

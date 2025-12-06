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
    public function submitDeliveryOrder()
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input || !isset($input['items']) || empty($input['items'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid input data.'
            ]);
            exit;
        }

        try {
            $doCode = $this->order->submitDeliveryOrder($input);

            echo json_encode([
                'success' => true,
                'message' => 'Delivery Order submitted successfully.',
                'doCode'  => $doCode
            ]);
            exit;
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error submitting Delivery Order: ' . $e->getMessage()
            ]);
            exit;
        }
    }
}

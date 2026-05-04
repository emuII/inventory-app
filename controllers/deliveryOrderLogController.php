<?php
class deliveryOrderLogController
{
    protected $log;
    public function __construct($db)
    {
        $this->log = new deliveryOrderLogModel($db);
    }

    public function GetLogOrder()
    {
        $data = $this->log->getDeliveryOrderLogs();
        helperModel::json(200, 'Success', $data);
    }

    public function deliveryOrderLog()
    {
        $doNumber = $_GET['doNumber'] ?? null;
        if (!$doNumber) {
            echo "Request number not found.";
            return;
        }
        $logDetail = $this->log->deliveryLogOrderDetails($doNumber);
        include 'views/DeliveryOrderLogs/deliveryOrderLog.php';
    }
}

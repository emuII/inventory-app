<?php
class dashboardController
{
    protected $alert;
    public function __construct($db)
    {
        $this->alert = new AlertModel($db);
    }
    public function getAlertsAction()
    {
        try {
            $alerts = $this->alert->getSystemAlerts();

            echo json_encode([
                'success' => true,
                'alerts' => $alerts,
                'total_alerts' => count($alerts)
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to load alerts'
            ]);
        }
    }
}

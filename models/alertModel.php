<?php
class alertModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getSystemAlerts()
    {
        $alerts = [];

        $lowStockItems = $this->getLowStockAlerts();
        if (!empty($lowStockItems)) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'fas fa-box',
                'title' => count($lowStockItems) . ' items reached minimum stock',
                'description' => $this->formatItemNames($lowStockItems),
                'time' => 'Just now',
                'action' => 'view_low_stock',
                'priority' => 1
            ];
        }

        // 2. Critical Stock Alerts (qty <= 2)
        $criticalStockItems = $this->getCriticalStockAlerts();
        if (!empty($criticalStockItems)) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => 'fas fa-exclamation-circle',
                'title' => count($criticalStockItems) . ' items are critically low',
                'description' => $this->formatItemNames($criticalStockItems),
                'time' => 'Just now',
                'action' => 'view_critical_stock',
                'priority' => 0
            ];
        }

        // 3. Urgent Pending Orders (> 3 days)
        $urgentOrders = $this->getUrgentPendingOrders();
        if (!empty($urgentOrders)) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => 'fas fa-clock',
                'title' => count($urgentOrders) . ' Purchase Orders need approval',
                'description' => $this->formatOrderCodes($urgentOrders),
                'time' => $this->getTimeAgo($urgentOrders[0]['created_at']),
                'action' => 'view_pending_approvals',
                'priority' => 0
            ];
        }

        // 4. Pending Orders
        $pendingOrders = $this->getPendingOrders();
        if (!empty($pendingOrders)) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'fas fa-clipboard-list',
                'title' => count($pendingOrders) . ' Pending Purchase Orders',
                'description' => 'Need review and approval',
                'time' => $this->getTimeAgo($pendingOrders[0]['created_at']),
                'action' => 'view_all_pending',
                'priority' => 2
            ];
        }


        // 6. Today's Completed Orders for report
        $todayOrders = $this->getTodaysCompletedOrders();
        if (!empty($todayOrders)) {
            $alerts[] = [
                'type' => 'success',
                'icon' => 'fas fa-file-excel',
                'title' => 'Today\'s sales report ready',
                'description' => count($todayOrders) . ' orders completed today',
                'time' => 'Today',
                'action' => 'download_daily_report',
                'priority' => 3
            ];
        }

        // Sort by priority (lower number = higher priority)
        usort($alerts, function ($a, $b) {
            return $a['priority'] <=> $b['priority'];
        });

        return $alerts;
    }

    private function getLowStockAlerts($threshold = 5)
    {
        $query = "SELECT Id, item_name, qty
            FROM m_item
            WHERE qty > 0 AND qty <= :threshold
                ORDER BY qty ASC
                LIMIT 5";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':threshold' => $threshold]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getCriticalStockAlerts($threshold = 2)
    {
        $query = "SELECT Id, item_name, qty
                FROM m_item
                WHERE qty > 0 AND qty <= :threshold
                    ORDER BY qty ASC
                    LIMIT 5";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':threshold' => $threshold]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getUrgentPendingOrders($days = 3)
    {
        $query = "SELECT pr_code, created_at
                    FROM purchase_request
                    WHERE status = 1
                    AND DATEDIFF(NOW(), created_at) >= :days
                    ORDER BY created_at ASC
                    LIMIT 3";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':days' => $days]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getPendingOrders()
    {
        $query = "SELECT pr_code, created_at
                    FROM purchase_request
                    WHERE status = 1
                    ORDER BY created_at DESC
                    LIMIT 5";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getTodaysCompletedOrders()
    {
        // Asumsi ada tabel delivery_order
        $query = "SELECT do_code, total_amount
                        FROM delivery_order
                        WHERE DATE(do_date) = CURDATE()
                        AND status = 4
                        LIMIT 5";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function formatItemNames($items)
    {
        $names = array_slice(array_column($items, 'item_name'), 0, 3);
        $count = count($items);

        if ($count <= 3) {
            return implode(', ', $names);
        } else {
            return implode(' , ', $names) . ' and ' . ($count - 3) . ' more items';
        }
    }

    private function formatOrderCodes($orders)
    {
        $codes = array_slice(array_column($orders, 'pr_code'), 0, 3);

        if (count($orders) <= 3) {
            return 'Urgent: ' . implode(', ', $codes);
        } else {
            return ' Urgent: ' . implode(' , ', $codes) . ' and ' . (count($orders) - 3) . ' more';
        }
    }

    private function formatExpiringItems($items)
    {
        $names = array_slice(array_column($items, 'item_name'), 0, 3);
        $count = count($items);

        if ($count <= 3) {
            return implode(', ', $names);
        } else {
            return implode(' , ', $names) . ' and ' . ($count - 3) . ' more';
        }
    }

    private function getTimeAgo($datetime)
    {
        $time = strtotime($datetime);
        $time_diff = time() - $time;

        if ($time_diff < 60) {
            return 'Just now';
        } elseif ($time_diff < 3600) {
            $minutes = floor($time_diff / 60);
            return $minutes . ' minutes ago';
        } elseif ($time_diff < 86400) {
            $hours = floor($time_diff / 3600);
            return $hours . ' hours ago';
        } elseif ($time_diff < 2592000) {
            $days = floor($time_diff / 86400);
            return $days . ' days ago';
        } else {
            return date('M d, Y', $time);
        }
    }
}

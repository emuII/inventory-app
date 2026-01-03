<?php
class dashboardModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function GetDashboardHeader()
    {
        $query = "SELECT
                        (
                            SELECT COUNT(*) FROM m_item
                        ) AS total_items,
                        (
                            SELECT COUNT(*) FROM m_item WHERE qty > 10
                        ) AS optimal_stock_items,
                        (
                            SELECT COUNT(*) FROM m_item WHERE qty > 0 AND qty <= 10
                        ) AS low_stock_items,
                        (
                            SELECT COUNT(*) FROM m_item WHERE qty <= 0
                        ) AS out_of_stock_items,
                        ROUND(
                                (
                                    SELECT COUNT(*) FROM m_item WHERE qty > 10
                                ) * 100.0 / NULLIF(
                                            (
                                                SELECT COUNT(*) FROM m_item
                                            ), 0),
                                1
                            ) AS optimal_stock_percent,
                        ROUND(
                                (
                                    SELECT COUNT(*) FROM m_item WHERE qty > 0 AND qty <= 10
                                ) * 100.0 / NULLIF(
                                            (
                                                SELECT COUNT(*) FROM m_item
                                            ), 0),
                                1
                            ) AS low_stock_percent,
                        ROUND(
                                (
                                    SELECT COUNT(*) FROM m_item WHERE qty <= 0
                                ) * 100.0 / NULLIF(
                                            (
                                                SELECT COUNT(*) FROM m_item
                                            ), 0),
                                1
                            ) AS out_of_stock_percent,
                        (
                            SELECT COUNT(*) FROM purchase_request WHERE STATUS = 1
                        ) AS pending_orders,
                        (
                            SELECT COUNT(*)
                            FROM purchase_request
                            WHERE STATUS = 1
                                AND DATEDIFF(NOW(), created_at) >= 3
                        ) AS urgent_pending_orders";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function GetTodayRevenue()
    {
        $query = "SELECT 
                        (SELECT COALESCE(SUM(total_amount), 0) 
                        FROM delivery_order 
                        WHERE DATE(do_date) = CURDATE() 
                        AND STATUS = 1) AS today_revenue";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

<?php
class deliveryOrderModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getProductList()
    {
        $sql = "SELECT MI.Id AS ItemId,
                    MI.item_name AS itemName,
                    MI.category AS categoryItem,
                    MI.type AS typeCategory,
                    MI.sales_price AS sellingPrice
                FROM m_item MI";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

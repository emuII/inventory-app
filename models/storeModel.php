<?php
class storeModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getStore()
    {
        $configPath = __DIR__ . '/../configure.json';

        if (!file_exists($configPath)) {
            throw new Exception("configure.json tidak ditemukan: " . $configPath);
        }

        $config = json_decode(file_get_contents($configPath), true);

        if (empty($config['storeCode'])) {
            throw new Exception("storeCode di configure.json tidak valid");
        }

        $sql = "SELECT id, store_code, store_name, address, phone, email
                FROM m_store
                WHERE store_code = :storeCode
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':storeCode' => $config['storeCode']
        ]);
        $store = $stmt->fetch(PDO::FETCH_ASSOC);
        return $store ?: [];
    }
}

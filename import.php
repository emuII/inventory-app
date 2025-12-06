<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/database.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

function uploadErrorMessage($errorCode)
{
    $errors = [
        UPLOAD_ERR_INI_SIZE   => "Ukuran file melebihi upload_max_filesize di php.ini",
        UPLOAD_ERR_FORM_SIZE  => "Ukuran file melebihi batas MAX_FILE_SIZE di form",
        UPLOAD_ERR_PARTIAL    => "File hanya terupload sebagian",
        UPLOAD_ERR_NO_FILE    => "Tidak ada file yang diupload",
        UPLOAD_ERR_NO_TMP_DIR => "Folder tmp tidak tersedia",
        UPLOAD_ERR_CANT_WRITE => "Gagal menulis file ke disk",
        UPLOAD_ERR_EXTENSION  => "Upload dihentikan oleh ekstensi PHP",
    ];
    return $errors[$errorCode] ?? "Error tidak diketahui (code $errorCode)";
}

if (!isset($_FILES['file'])) {
    exit("<p>Tidak ada file yang dikirim dari form</p>");
}
if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    exit("<p>Upload gagal: " . uploadErrorMessage($_FILES['file']['error']) . "</p>");
}

try {
    $fileTmp = $_FILES['file']['tmp_name'];

    $spreadsheet = IOFactory::load($fileTmp);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray(null, true, true, false);

    $sql = "INSERT INTO m_item (item_name, type, category, qty, sales_price)
            VALUES (:item_name, :type, :category, :qty, :price)";
    $stmt = $pdo->prepare($sql);

    $pdo->beginTransaction();

    $inserted = 0;
    foreach ($rows as $i => $row) {
        if ($i === 0) continue; // skip header

        $item_name = trim((string)($row[0] ?? ''));
        $type      = trim((string)($row[1] ?? ''));
        $category  = trim((string)($row[2] ?? ''));
        $qty       = (int)($row[3] ?? 0);
        $price     = (float)($row[4] ?? 0);

        if ($item_name === '' && $type === '' && $category === '' && $qty === 0 && $price === 0.0) {
            continue;
        }

        if ($item_name === '') continue;
        if ($qty < 0) $qty = 0;
        if ($price < 0) $price = 0;

        $stmt->execute([
            ':item_name' => $item_name,
            ':type'      => $type,
            ':category'  => $category,
            ':qty'       => $qty,
            ':price'     => $price,
        ]);
        $inserted++;
    }

    $pdo->commit();
    header('Location: /Inventory-app/index.php?route=items&&success=Succes');
    exit;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo "<p>Gagal import: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
}

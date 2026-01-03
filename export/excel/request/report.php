<?php
require __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../models/purchaseRequestModel.php';
require __DIR__ . '/../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;


$purchaseOrders = [];

$purchaseRequestModel = new purchaseRequestModel($pdo);
$result = $purchaseRequestModel->GetReportPurchaseOrder();


foreach ($result as $row) {
    $prCode = $row['request_number'];

    // Jika PO baru
    if (!isset($purchaseOrders[$prCode])) {
        $purchaseOrders[$prCode] = [
            'request_number' => $row['request_number'],
            'request_date' => $row['request_date'],
            'status_name' => $row['status_name'],
            'requestor' => $row['requestor'],
            'supplier' => $row['supplier'],
            'items' => []
        ];
    }

    $purchaseOrders[$prCode]['items'][] = [
        'item_name' => $row['item_name'],
        'type' => $row['type'],
        'category' => $row['category'],
        'quantity' => (int)$row['quantity'],
        'unit_price' => (float)$row['unit_price']
    ];
}
$purchaseOrders = array_values($purchaseOrders);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Purchase Order Report');

$sheet->getPageSetup()
    ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
    ->setPaperSize(PageSetup::PAPERSIZE_A4)
    ->setFitToWidth(1);

$headerStyle = [
    'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']]
];

$columnHeaderStyle = [
    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    ],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

$poHeaderStyle = [
    'font' => ['bold' => false],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'vertical' => Alignment::VERTICAL_CENTER
    ],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DCE6F1']],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

$itemRowStyle = [
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'vertical' => Alignment::VERTICAL_CENTER
    ],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

$poTotalStyle = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F2F2F2']],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
];

/*
|--------------------------------------------------------------------------
| HEADER REPORT
|--------------------------------------------------------------------------
*/
$row = 1;
$sheet->mergeCells('A1:J1');
$sheet->setCellValue('A1', 'PURCHASE ORDER REPORT');
$sheet->getStyle('A1')->applyFromArray($headerStyle);
$row += 2;

/*
|--------------------------------------------------------------------------
| COLUMN HEADERS
|--------------------------------------------------------------------------
*/
$headers = [
    'A' => 'Request Number',
    'B' => 'Request Date',
    'C' => 'Status',
    'D' => 'Requestor',
    'E' => 'Supplier',
    'F' => 'Item Name',
    'G' => 'Type',
    'H' => 'Category',
    'I' => 'Qty',
    'J' => 'Unit Price',
];

foreach ($headers as $col => $text) {
    $sheet->setCellValue($col . $row, $text);
}

$sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray($columnHeaderStyle);
$row++;

/*
|--------------------------------------------------------------------------
| DATA LOOP - DIPERBAIKI UNTUK KONSISTENSI FORMAT
|--------------------------------------------------------------------------
*/
foreach ($purchaseOrders as $order) {
    // Hitung total untuk PO ini
    $subtotal = 0;
    $totalQty = 0;
    foreach ($order['items'] as $item) {
        $subtotal += $item['quantity'] * $item['unit_price'];
        $totalQty += $item['quantity'];
    }

    $tax = $subtotal * 0.10;
    $grandTotal = $subtotal + $tax;
    $itemCount = count($order['items']);

    // Tentukan baris awal untuk PO ini
    $poStartRow = $row;

    foreach ($order['items'] as $index => $item) {
        if ($index === 0) {
            $sheet->setCellValue('A' . $row, $order['request_number']);
            $sheet->setCellValue('B' . $row, date('d/m/Y', strtotime($order['request_date'])));
            $sheet->setCellValue('C' . $row, $order['status_name']);
            $sheet->setCellValue('D' . $row, $order['requestor']);
            $sheet->setCellValue('E' . $row, $order['supplier']);
        }

        // Tulis data item untuk semua baris
        $sheet->setCellValue('F' . $row, $item['item_name']);
        $sheet->setCellValue('G' . $row, $item['type']);
        $sheet->setCellValue('H' . $row, $item['category']);
        $sheet->setCellValue('I' . $row, $item['quantity']);
        $sheet->setCellValue('J' . $row, $item['unit_price']);

        if ($index === 0) {
            $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray($poHeaderStyle);
        } else {
            $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray($itemRowStyle);
        }

        // Format khusus untuk kolom tertentu
        $sheet->getStyle('I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('J' . $row)->getNumberFormat()->setFormatCode('"Rp" #,##0');

        $row++;
    }

    // Merge sel untuk kolom A-E di semua baris PO ini
    $poEndRow = $row - 1;
    if ($poStartRow < $poEndRow) {
        for ($col = 'A'; $col <= 'E'; $col++) {
            $sheet->mergeCells($col . $poStartRow . ':' . $col . $poEndRow);
        }
    }

    // Total row - DIPERBAIKI: Semua kolom rata kanan
    $sheet->setCellValue('A' . $row, 'Total PO:');
    $sheet->setCellValue('B' . $row, $order['request_number']);
    $sheet->setCellValue('F' . $row, 'Subtotal:');
    $sheet->setCellValue('G' . $row, $subtotal);
    $sheet->setCellValue('H' . $row, 'Tax (10%):');
    $sheet->setCellValue('I' . $row, $tax);
    $sheet->setCellValue('J' . $row, $grandTotal);

    // Merge cells
    $sheet->mergeCells('A' . $row . ':E' . $row);
    $sheet->mergeCells('F' . $row . ':G' . $row);
    $sheet->mergeCells('H' . $row . ':I' . $row);

    // Apply styling untuk total - SEMUA rata kanan
    $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray($poTotalStyle);
    $sheet->getStyle('J' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

    // Format angka - SEMUA rata kanan
    $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    $sheet->getStyle('I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

    $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('"Rp" #,##0');
    $sheet->getStyle('I' . $row)->getNumberFormat()->setFormatCode('"Rp" #,##0');
    $sheet->getStyle('J' . $row)->getNumberFormat()->setFormatCode('"Rp" #,##0');

    $row += 2; // Tambah spasi antar PO
}

/*
|--------------------------------------------------------------------------
| SET ALIGNMENT UNTUK KOLOM TERPILIH
|--------------------------------------------------------------------------
*/
// Set alignment untuk semua data (setelah row header)
$lastRow = $sheet->getHighestRow();
for ($r = 4; $r <= $lastRow; $r++) { // Mulai dari row 4 (setelah header)
    // Kolom B (Request Date) - center
    $sheet->getStyle('B' . $r)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Kolom C (Status) - center
    $sheet->getStyle('C' . $r)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Kolom I (Qty) - center (sudah diatur di loop, tapi diulang untuk konsistensi)
    $sheet->getStyle('I' . $r)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Kolom J (Unit Price) - right align untuk angka
    $sheet->getStyle('J' . $r)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
}


foreach (range('A', 'J') as $column) {
    $sheet->getColumnDimension($column)->setAutoSize(true);
}

/*
|--------------------------------------------------------------------------
| OUTPUT
|--------------------------------------------------------------------------
*/
$filename = 'purchase_order_report_' . date('Ymd_His') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;

<?php
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// ===== Helper: format =====
function numFmt($style, $code = '#,##0')
{
    $style->getNumberFormat()->setFormatCode($code);
}

// ===== Workbook & Sheet =====
$wb = new Spreadsheet();
$ws = $wb->getActiveSheet();
$ws->setTitle('Nota Service');
// Lebar kolom biar mirip
$ws->getColumnDimension('A')->setWidth(6);   // No
$ws->getColumnDimension('B')->setWidth(16);  // Product Code
$ws->getColumnDimension('C')->setWidth(26);  // Product Name
$ws->getColumnDimension('D')->setWidth(16);  // Category
$ws->getColumnDimension('E')->setWidth(14);  // Type
$ws->getColumnDimension('F')->setWidth(8);   // Qty
$ws->getColumnDimension('G')->setWidth(16);  // Selling Price
$ws->getColumnDimension('H')->setWidth(14);  // Date Out
$ws->getColumnDimension('I')->setWidth(16);  // Total

// ===== Judul =====
$ws->mergeCells('A2:I2')->setCellValue('A2', 'Laporan Barang Keluar dd/mm/yyyy');
$ws->getStyle('A2')->getFont()->setBold(true)->setSize(18);
$ws->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// ===== Header tabel =====
$headRow = 5;
$headers = ['No', 'Product Code', 'Product Name', 'Category', 'Type', 'Qty', 'Selling Price', 'Date Out', 'Total'];
$ws->fromArray($headers, null, "A{$headRow}");
$ws->getStyle("A{$headRow}:I{$headRow}")->getFont()->setBold(true);
$ws->getStyle("A{$headRow}:I{$headRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$ws->getStyle("A{$headRow}:I{$headRow}")
    ->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFF2CC'); // krem

// ===== Data dummy (baris mirip contoh) =====
$data = [
    // No, Code, Name, Category, Type, Qty, Price, DateOut, Total
    [1, 'PRD-12', 'BAN', 'SPERPART', 'MATIC', 2, 300000, '2025-09-09', 600000],
];

$row = $headRow + 1;
foreach ($data as $r) {
    [$no, $code, $name, $cat, $type, $qty, $price, $date, $total] = $r;

    // tulis sel
    $ws->setCellValue("A{$row}", $no);
    $ws->setCellValue("B{$row}", $code);
    $ws->setCellValue("C{$row}", $name);
    $ws->setCellValue("D{$row}", $cat);
    $ws->setCellValue("E{$row}", $type);
    $ws->setCellValue("F{$row}", $qty);
    $ws->setCellValue("G{$row}", $price);
    // ubah tanggal ke Excel serial
    $ws->setCellValue("H{$row}", \PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($date));
    $ws->setCellValue("I{$row}", $total);

    // alignment & format
    $ws->getStyle("A{$row}:E{$row}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $ws->getStyle("F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $ws->getStyle("G{$row}:I{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

    numFmt($ws->getStyle("F{$row}"), '#,##0');          // qty
    numFmt($ws->getStyle("G{$row}:I{$row}"), '#,##0');  // harga/total
    $ws->getStyle("H{$row}")->getNumberFormat()->setFormatCode('d/m/yyyy');

    $row++;
}

// ===== Border tabel =====
$last = $row - 1;
$ws->getStyle("A{$headRow}:I{$last}")->getBorders()->getAllBorders()
    ->setBorderStyle(Border::BORDER_THIN);

// Margin halaman rapi
$ws->getPageMargins()->setTop(0.4)->setRight(0.3)->setLeft(0.3)->setBottom(0.4);

// ===== Output =====
$filename = 'laporan_barang_keluar_dummy.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"{$filename}\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($wb);
$writer->save('php://output');
exit;

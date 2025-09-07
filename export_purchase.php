<?php
// export_nota_dummy.php
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

function rupiahFmt($range)
{
    // format Rp (angka saja, simbol “Rp” kita tulis manual di label)
    $range->getNumberFormat()->setFormatCode('#,##0');
}

$wb = new Spreadsheet();
$ws = $wb->getActiveSheet();
$ws->setTitle('Nota Service');

// Lebar kolom
foreach (['A' => 8, 'B' => 50, 'C' => 14, 'D' => 16] as $col => $w) $ws->getColumnDimension($col)->setWidth($w);

// ================= Header =================
$ws->mergeCells('A1:D1')->setCellValue('A1', 'SUMBER ABADI MOBIL');
$ws->getStyle('A1')->getFont()->setBold(true)->setSize(18);
$ws->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

$ws->mergeCells('A2:D4')->setCellValue(
    'A2',
    "Jl. Pasar Kemis Km 5 No. 9 Kp. Cilongok, Sukamantri
Kec. Pasar Kemis, Kab. Tangerang – Banten
HP. 0813 1926 2342
LAYANAN: SERVICE/TUNE UP • GANTI OLI • OVERHAUL • REM & KOPLING • KAKI KAKI • DAN LAIN LAIN"
);

// Box info kanan (Tanggal / Tuan / Toko / Nota No.)
$ws->setCellValue('B6', 'Tanggal');
$ws->setCellValue('C6', ': ' . date('d M Y'));
$ws->setCellValue('B7', 'Tuan');
$ws->setCellValue('C7', ': Andi');
$ws->setCellValue('B8', 'Toko');
$ws->setCellValue('C8', ': -');
$ws->setCellValue('B9', 'Nota No.');
$ws->setCellValue('C9', ': 000123');

// ================= Tabel Barang =================
$startRow = 11;
$ws->setCellValue("A{$startRow}", 'Banyaknya');
$ws->setCellValue("B{$startRow}", 'Nama Barang');
$ws->setCellValue("C{$startRow}", 'Harga Satuan');
$ws->setCellValue("D{$startRow}", 'Jumlah');
$ws->getStyle("A{$startRow}:D{$startRow}")->getFont()->setBold(true);
$ws->getStyle("A{$startRow}:D{$startRow}")
    ->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFF2CC');
$ws->getStyle("A{$startRow}:D{$startRow}")
    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$items = [
    ['qty' => '1 set', 'name' => 'Kampas Rem Depan',  'price' => 305000],
    ['qty' => '1 set', 'name' => 'Kampas Rem Belakang', 'price' => 395000],
    ['qty' => '1 pc',  'name' => 'Master Rem Belk LH', 'price' => 925000],
    ['qty' => '1 botol', 'name' => 'Minyak Rem DOT 4 (clear)', 'price' => 85000],
];

$row = $startRow + 1;
$subTotal = 0;
foreach ($items as $it) {
    $amount = $it['price']; // karena qty “set/botol” tak dihitung numerik di contoh
    $subTotal += $amount;

    $ws->setCellValue("A{$row}", $it['qty']);
    $ws->setCellValue("B{$row}", $it['name']);
    $ws->setCellValue("C{$row}", $it['price']);
    $ws->setCellValue("D{$row}", $amount);

    $ws->getStyle("C{$row}:D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    rupiahFmt($ws->getStyle("C{$row}:D{$row}"));
    $row++;
}

// Garis border tabel barang
$ws->getStyle("A{$startRow}:D" . ($row - 1))->getBorders()->getAllBorders()
    ->setBorderStyle(Border::BORDER_THIN);

// ================= Jasa / Ongkos =================
$ws->setCellValue("A{$row}", 'Ongkos Bengkel / Pemasangan');
$ws->mergeCells("A{$row}:C{$row}");
$ws->setCellValue("D{$row}", 450000);
$ws->getStyle("A{$row}:D{$row}")->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
$ws->getStyle("D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
rupiahFmt($ws->getStyle("D{$row}"));
$row++;

$ws->setCellValue("A{$row}", '• Servis kampas rem depan');
$ws->mergeCells("A{$row}:C{$row}");
$row++;
$ws->setCellValue("A{$row}", '• Servis master rem belakang');
$ws->mergeCells("A{$row}:C{$row}");
$row++;

// ================= Total =================
$grand = $subTotal + 450000;
$ws->setCellValue("C{$row}", 'Jumlah Rp.');
$ws->setCellValue("D{$row}", $grand);
$ws->getStyle("C{$row}:D{$row}")->getFont()->setBold(true);
$ws->getStyle("D{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
rupiahFmt($ws->getStyle("D{$row}"));
$ws->getStyle("C{$row}:D{$row}")->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
$row += 2;

// Footer: rekening + tanda tangan
$ws->mergeCells("A{$row}:B" . ($row + 2));
$ws->setCellValue(
    "A{$row}",
    "BCA No. Rek. 743.550.2512
a.n. Dede Iman Suherman"
);

$ws->mergeCells("C{$row}:D{$row}");
$ws->setCellValue("C{$row}", 'Hormat kami,');
$ws->getStyle("C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

// Rapikan margin kertas
$ws->getPageMargins()->setTop(0.4)->setRight(0.3)->setLeft(0.3)->setBottom(0.4);

// Output
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="nota_service_dummy.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($wb);
$writer->save('php://output');
exit;

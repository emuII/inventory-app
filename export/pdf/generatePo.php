<?php
require __DIR__ . '/../../config/database.php';
require __DIR__ . '/../../models/storeModel.php';
require __DIR__ . '/../../models/purchaseRequestModel.php';
require __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;

$storeModel = new storeModel($config);
$resuestModel = new purchaseRequestModel($config);

$requestNumber = $_GET['requestNumber'] ?? null;

$storeData  = $storeModel->getStore();
$requestData = $resuestModel->requestHeader($requestNumber);
$resuestDetail = $resuestModel->requestDetails($requestNumber);



$numberPr     = $requestData['requestNumber'] ?? '';
$tanggal_po   = 'Jakarta, 23 Mei 2025';

$supplier_address   =  $requestData['supplierAddress'] ?? '';

$requestTo       = "Kepada Yth.\n" . $requestData['supplierName'] . "\n";
$requestFrom         = $requestData['requesterName'] . "\nPT Perkasa Sakti ToeJoeh";
$requestorName    = $requestData['requesterName'] ?? '';

$termin       = "30 hari setelah barang diterima lengkap";
$keterangan_po = "Harap barang dikirim dalam kondisi baik, lengkap dengan dokumen pendukung (faktur, DO, dan surat jalan).";

ob_start();
?>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 30px 30px 40px 30px;
        }

        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 11px;
        }

        .header-table {
            width: 100%;
            margin-bottom: 10px;
        }

        .header-left {
            font-size: 18px;
            font-weight: bold;
        }

        .header-right {
            text-align: right;
            font-size: 11px;
        }

        .title {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .title h2 {
            margin: 0;
            font-size: 14px;
            text-decoration: underline;
        }

        .title p {
            margin: 2px 0 0 0;
            font-size: 11px;
        }

        .content {
            font-size: 11px;
        }

        .content p {
            margin: 3px 0;
        }

        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .table-items th,
        .table-items td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: top;
        }

        .table-items th {
            text-align: center;
            font-weight: bold;
        }

        .ttd {
            margin-top: 30px;
            width: 100%;
        }

        .ttd td {
            vertical-align: top;
        }

        .small {
            font-size: 10px;
        }
    </style>
</head>

<body>

    <table class="header-table">
        <tr>
            <td class="header-left">
                <strong><?= htmlspecialchars($storeResponse['store_name'] ?? '') ?></strong><br>
                <?php if (!empty($storeResponse['address'])): ?>
                    <span class="small"><?= nl2br(htmlspecialchars($storeResponse['address'])) ?></span><br>
                <?php endif; ?>
                <?php if (!empty($storeResponse['phone'])): ?>
                    <span class="small">Telp: <?= htmlspecialchars($storeResponse['phone']) ?></span><br>
                <?php endif; ?>
                <?php if (!empty($storeResponse['email'])): ?>
                    <span class="small">Email: <?= htmlspecialchars($storeResponse['email']) ?></span>
                <?php endif; ?>
            </td>
            <td class="header-right">
                <?= htmlspecialchars($tanggal_po) ?>
            </td>
        </tr>
    </table>

    <div class="title">
        <h2>PURCHASE ORDER (PO)</h2>
        <p>Nomor: <?= htmlspecialchars($numberPr) ?></p>
    </div>

    <div class="content">
        <p><?= nl2br(htmlspecialchars($requestTo)) ?></p>
        <br>
        <p>Alamat Supplier:</p>
        <p><?= nl2br(htmlspecialchars($supplier_address)) ?></p>
        <br>
        <p>Dari: <?= nl2br(htmlspecialchars($requestFrom)) ?></p>
        <br>
        <p>Dengan hormat,</p>
        <p>Melalui surat ini, kami mengajukan pesanan barang dengan rincian sebagai berikut:</p>
    </div>

    <table class="table-items">
        <tr>
            <th style="width:3%;">No</th>
            <th style="width:15%;">Request Number</th>
            <th style="width:27%;">Item Name</th>
            <th style="width:18%;">Category</th>
            <th style="width:7%;">Type</th>
        </tr>
        <?php if (!empty($resuestDetail) && is_array($resuestDetail)): ?>
            <?php foreach ($resuestDetail as $index => $row): ?>
                <tr>
                    <td align="center"><?= $index + 1 ?></td>
                    <td align="center"><?= htmlspecialchars($row['requestNumber']) ?></td>
                    <td><?= htmlspecialchars($row['itemName']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= htmlspecialchars($row['type']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" align="center">Tidak ada data</td>
            </tr>
        <?php endif; ?>
    </table>

    <div class="content">
        <p>Termin pembayaran: <?= htmlspecialchars($termin) ?></p>
        <p><?= htmlspecialchars($keterangan_po) ?></p>
        <p>Demikian purchase order ini kami sampaikan. Atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>
    </div>
    <br>
    <div class="content">
        <p>Hormat Kami</p>
        <p>Manager Pembelian</p>
        <br><br><br>
        <p>(<?= htmlspecialchars($requestorName) ?>)</p>
    </div>
    </table>

</body>

</html>
<?php
$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream('purchase_order.pdf', ['Attachment' => true]);

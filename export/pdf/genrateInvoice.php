<?php
require __DIR__ . '/../../config/database.php';
require __DIR__ . '/../../models/storeModel.php';
require __DIR__ . '/../../models/deliveryOrderModel.php';
require __DIR__ . '/../../models/deliveryOrderLogModel.php';

use Dompdf\Dompdf;
use Dompdf\Options;

require __DIR__ . '/../../vendor/autoload.php';

$doNumber = $_GET['doNumber'] ?? null;

$storeModel = new storeModel($config);
$deliveryModel = new deliveryOrderModel($config);
$deliveryLogModel = new deliveryOrderLogModel($config);

$companyData  = $storeModel->getStore();

$invoiceData = $deliveryModel->getDeliveryOrderByCode($doNumber);
$invoiceItems = $deliveryLogModel->deliveryLogOrderDetails($doNumber);

ob_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Invoice <?= htmlspecialchars($invoiceData['invoiceNumber']) ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 12px;
            color: #1a1a1a;
            line-height: 1.5;
            background-color: #ffffff;
            margin: 0;
            padding: 40px;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
        }

        /* Header Section */
        .invoice-header {
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #000;
        }

        .company-name {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #000;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .company-details {
            font-size: 11px;
            color: #666;
            line-height: 1.6;
        }

        /* Invoice Info Section */
        .invoice-info-section {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 40px;
            padding: 20px;
            background: #f8f8f8;
            border-radius: 4px;
        }

        .info-box {
            margin-bottom: 0;
        }

        .info-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #000;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #000;
            color: white;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 2px;
            margin-top: 5px;
        }

        /* Divider Line */
        .divider {
            height: 2px;
            background: #000;
            margin: 40px 0;
        }

        /* Items Section */
        .items-section {
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #000;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table thead th {
            text-align: left;
            padding: 12px 8px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #666;
            border-bottom: 2px solid #000;
        }

        .items-table tbody td {
            padding: 15px 8px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 13px;
        }

        .items-table tbody tr:last-child td {
            border-bottom: 2px solid #000;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Totals Section */
        .totals-section {
            margin-bottom: 60px;
        }

        .totals-table {
            width: 300px;
            margin-left: auto;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 10px 0;
            font-size: 13px;
        }

        .totals-table .label {
            text-align: left;
            color: #666;
            padding-right: 20px;
        }

        .totals-table .value {
            text-align: right;
            font-weight: 500;
        }

        .totals-table tr.total-row td {
            font-weight: 700;
            font-size: 16px;
            color: #000;
            border-top: 2px solid #000;
            padding: 15px 0;
        }

        /* Footer Section */
        .invoice-footer {
            margin-top: 80px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 10px;
            color: #666;
            text-align: center;
        }

        .footer-bottom {
            font-size: 9px;
            color: #999;
            padding-top: 10px;
        }

        /* Utility Classes */
        .bold {
            font-weight: 600;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .mb-1 {
            margin-bottom: 5px;
        }

        .mb-2 {
            margin-bottom: 10px;
        }

        .mb-3 {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-name"><?= htmlspecialchars($companyData['store_name']) ?></div>
            <div class="company-details">
                <p><?= htmlspecialchars($companyData['address']) ?></p>
                <p>T: <?= htmlspecialchars($companyData['phone']) ?> | E: <?= htmlspecialchars($companyData['email']) ?></p>
            </div>
        </div>

        <!-- Invoice Information -->
        <div class="invoice-info-section">
            <div class="info-box">
                <div class="info-label">Invoice No.</div>
                <div class="info-value"><?= htmlspecialchars($invoiceData['invoiceNumber']) ?></div>
            </div>
            <div class="info-box">
                <div class="info-label">Date</div>
                <div class="info-value"><?= htmlspecialchars($invoiceData['deliveryDate']) ?></div>
            </div>
            <div class="info-box">
                <div class="info-label">Status</div>
                <div class="info-value">
                    <?= htmlspecialchars($invoiceData['statusName']) ?>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="divider"></div>

        <!-- Items Section -->
        <div class="items-section">
            <div class="section-title">Description</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 55%;">Description</th>
                        <th style="width: 15%; text-align: center;">Amount</th>
                        <th style="width: 15%; text-align: center;">Unit</th>
                        <th style="width: 15%; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (
                        $invoiceItems as $item
                    ): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['itemName']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($item['qtyOrder']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($item['salesPrice']) ?></td>
                            <td class="text-right bold"><?= htmlspecialchars($item['subTotal']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Totals Section -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="label">Subtotal</td>
                    <td class="value"><?= htmlspecialchars($invoiceData['subTotal']) ?></td>
                </tr>
                <tr>
                    <td class="label">VAT (10%)</td>
                    <td class="value"><?= htmlspecialchars($invoiceData['tax']) ?></td>
                </tr>
                <tr class="total-row">
                    <td class="label">Total</td>
                    <td class="value"><?= htmlspecialchars($invoiceData['totalAmount']) ?></td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <div class="footer-bottom">
                <p>Invoice #<?= htmlspecialchars($invoiceData['invoiceNumber']) ?> | Generated on <?= date('d M Y H:i') ?></p>
                <p><?= htmlspecialchars($companyData['store_name']) ?> | <?= htmlspecialchars($companyData['address']) ?></p>
            </div>
        </div>
    </div>
</body>

</html>

<?php
$html = ob_get_clean();

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'Inter');
$options->set('isPhpEnabled', true);
$options->set('chroot', __DIR__);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Output PDF
$filename = 'Invoice_' . str_replace('/', '_', $invoiceData['invoiceNumber']) . '.pdf';
$dompdf->stream($filename, [
    'Attachment' => true,
    'compress' => true
]);

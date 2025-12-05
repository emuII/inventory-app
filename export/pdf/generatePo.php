<?php
require __DIR__ . '/../../config/database.php';
require __DIR__ . '/../../models/storeModel.php';
require __DIR__ . '/../../models/purchaseRequestModel.php';
require __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$storeModel = new storeModel($config);
$resuestModel = new purchaseRequestModel($config);

$requestNumber = $_GET['requestNumber'] ?? null;

$storeData  = $storeModel->getStore();
$requestData = $resuestModel->requestHeader($requestNumber);
$resuestDetail = $resuestModel->requestDetails($requestNumber);

// Data untuk template
$numberPr     = $requestData['requestNumber'] ?? '';
$tanggal_po = date("d F Y", strtotime("now"));
$supplier_address   =  $requestData['supplierAddress'] ?? '';
$requestTo       = $requestData['supplierName'] ?? '';
$requestFrom         = $requestData['requesterName'] ?? '';
$store_name = $storeData['store_name'] ?? 'PT Perkasa Sakti ToeJoeh';
$store_address = $storeData['address'] ?? 'Jl. Raya Kencana No. 123, Bandung';
$store_phone = $storeData['phone'] ?? '(022) 1234-5678';
$store_email = $storeData['email'] ?? 'info@perkasasakti.com';

$termin       = "30 hari setelah barang diterima lengkap";
$keterangan_po = "Harap barang dikirim dalam kondisi baik, lengkap dengan dokumen pendukung (faktur, DO, dan surat jalan).";

ob_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Purchase Order <?= htmlspecialchars($numberPr) ?></title>
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

        .po-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
        }

        /* Header Section */
        .po-header {
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

        /* PO Info Section */
        .po-info-section {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
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

        .po-number-box {
            background: #000;
            color: white;
            padding: 15px;
            border-radius: 4px;
            text-align: center;
        }

        .po-number-box .info-label {
            color: rgba(255, 255, 255, 0.8);
        }

        .po-number-box .info-value {
            color: white;
            font-size: 18px;
            letter-spacing: 1px;
        }

        /* Supplier & Company Info */
        .parties-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .party-box {
            padding: 20px;
            border: 2px solid #000;
            border-radius: 4px;
        }

        .party-title {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }

        .party-content {
            font-size: 12px;
            line-height: 1.6;
        }

        .party-content p {
            margin-bottom: 8px;
        }

        .party-content strong {
            font-size: 14px;
            color: #000;
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

        /* Terms Section */
        .terms-section {
            margin-bottom: 40px;
            padding: 20px;
            background: #f8f8f8;
            border-radius: 4px;
        }

        .terms-title {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }

        .terms-content {
            font-size: 12px;
            line-height: 1.6;
        }

        .terms-content p {
            margin-bottom: 10px;
        }

        .terms-content ul {
            list-style: none;
            padding-left: 20px;
        }

        .terms-content li {
            margin-bottom: 8px;
            position: relative;
            padding-left: 15px;
        }

        .terms-content li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #000;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 60px;
        }

        .signature-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
        }

        .signature-box {
            padding-top: 60px;
            text-align: center;
            position: relative;
        }

        .signature-line {
            width: 200px;
            height: 1px;
            background: #000;
            margin: 0 auto 10px;
        }

        .signature-name {
            font-weight: 600;
            color: #000;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .signature-title {
            font-size: 11px;
            color: #666;
        }

        /* Footer Section */
        .po-footer {
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

        .mt-1 {
            margin-top: 5px;
        }

        .mt-2 {
            margin-top: 10px;
        }

        .mt-3 {
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <div class="po-container">
        <!-- Header -->
        <div class="po-header">
            <div class="company-name"><?= htmlspecialchars($store_name) ?></div>
            <div class="company-details">
                <p><?= htmlspecialchars($store_address) ?></p>
                <p>T: <?= htmlspecialchars($store_phone) ?> | E: <?= htmlspecialchars($store_email) ?></p>
            </div>
        </div>

        <!-- PO Information -->
        <div class="po-info-section">
            <div class="po-number-box">
                <div class="info-label">Purchase Order No.</div>
                <div class="info-value"><?= htmlspecialchars($numberPr) ?></div>
            </div>
            <div class="info-box">
                <div class="info-label">Date</div>
                <div class="info-value"><?= htmlspecialchars($tanggal_po) ?></div>
            </div>
        </div>

        <!-- Supplier & Company Information -->
        <div class="parties-section">
            <div class="party-box">
                <div class="party-title">To (Supplier)</div>
                <div class="party-content">
                    <p><strong><?= htmlspecialchars($requestTo) ?></strong></p>
                    <?php if (!empty($supplier_address)): ?>
                        <p><?= nl2br(htmlspecialchars($supplier_address)) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="divider"></div>

        <!-- Items Section -->
        <div class="items-section">
            <div class="section-title">Order Items</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 20%;">Request Number</th>
                        <th style="width: 35%;">Item Name</th>
                        <th style="width: 10%; text-align: center;">Quantity</th>
                        <th style="width: 15%;">Category</th>
                        <th style="width: 15%;">Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($resuestDetail) && is_array($resuestDetail)): ?>
                        <?php foreach ($resuestDetail as $index => $row): ?>
                            <tr>
                                <td class="text-center"><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($row['requestNumber'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['itemName'] ?? '') ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['qty'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['category'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['type'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center" style="padding: 30px; color: #999;">
                                No items in this purchase order
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Terms & Conditions -->
        <div class="terms-section">
            <div class="terms-title">Terms & Conditions</div>
            <div class="terms-content">
                <p><strong>Payment Terms:</strong> <?= htmlspecialchars($termin) ?></p>
                <p><strong>Delivery Requirements:</strong> <?= htmlspecialchars($keterangan_po) ?></p>
                <ul>
                    <li>All goods must be delivered in good condition and properly packaged</li>
                    <li>Required documents: Invoice, Delivery Order, and Delivery Note</li>
                    <li>Delivery must be made to the address specified above</li>
                    <li>Any discrepancies must be reported within 24 hours of delivery</li>
                    <li>Goods must meet the quality standards specified in the order</li>
                </ul>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-grid">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-name"><?= htmlspecialchars($requestFrom) ?></div>
                    <div class="signature-title">Purchase Requester</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-name">Manager Pembelian</div>
                    <div class="signature-title">Authorized Signature</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="po-footer">
            <div class="footer-bottom">
                <p>Purchase Order #<?= htmlspecialchars($numberPr) ?> | Generated on <?= date('d M Y H:i') ?></p>
                <p><?= htmlspecialchars($store_name) ?> | <?= htmlspecialchars($store_address) ?></p>
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

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream('purchase_order_' . $numberPr . '.pdf', ['Attachment' => true]);

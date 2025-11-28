<form id="arForm" method="post" onsubmit="return false;">
    <div class="container-fluid py-4">
        <div class="box-body" id="itemContainer">
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0">Approval Details</h1>
                        <p class="mb-0 opacity-75">Manage your approval information</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="search-section">
            <h5 class="mb-3">Request Detail</h5>
            <div class="table-responsive">
                <input type="text" style="display: none;" id="pr_id" value="<?= htmlspecialchars($dataHeader['PrId']) ?>">
                <table class="table table-modern" style="text-align: center;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Supplier Name</th>
                            <th>Request Date</th>
                            <th>Approver Name</th>
                            <th>Remarks Approver</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($dataDetail)): ?>
                            <?php foreach ($dataDetail as $index => $row): ?>
                                <tr>
                                    <td style="width: 10px;"><?= ($index + 1) ?> .</td>
                                    <td><?= htmlspecialchars($row['itemName'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['qty'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($dataHeader['supplierName'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($dataHeader['requestDate'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($dataHeader['approverName'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($dataHeader['remarksApprover'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['Notes'] ?? '') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" style="text-align:center;">No detail found</td>
                            </tr>

                        <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <div class="search-section">
            <h5 class="mb-3">Action Approval</h5>
            <div class="filter-row">
                <div class="filter-group" style="width: 30%;">
                    <select class="form-control select2" name="action_approver" id="action_approver" required>
                        <option value="">-- Choose Action --</option>
                        <?php
                        if (!empty($helper)) {
                            foreach ($helper as $obj) {

                                if (!in_array($obj['name'], ['Approve', 'Reject'])) {
                                    continue;
                                }
                        ?>
                                <option value="<?= htmlspecialchars($obj['value']) ?>">
                                    <?= htmlspecialchars($obj['name']) ?>
                                </option>
                            <?php
                            }
                        } else {
                            ?>
                            <option value="">Tidak ada data approval</option>
                        <?php } ?>
                    </select>

                    </select>
                </div>

            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary-modern btn-modern" type="submit">
                    Submit
                </button> &nbsp;
                <button class="btn btn-outline-secondary btn-modern" type="button" onclick="history.back()">
                    Close
                </button>
            </div>
        </div>
    </div>
</form>
<script src="script/approval.js"></script>
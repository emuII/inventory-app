<form id="arForm" method="post" onsubmit="return false;">
    <div class="box-body" id="itemContainer">
        <?php
        if (!empty($dataDetail)) {
            foreach ($dataDetail  as $row) {
        ?>
                <div class="card card-body item-row">
                    <div class="table-responsive">
                        <table class="table table-borderles tbl-item">
                            <tbody>
                                <tr>
                                    <td>Items</td>
                                    <td>:</td>
                                    <td>
                                        <select class="form-control select2" name="select_item" disabled>
                                            <option><?= htmlspecialchars($row['itemName']) ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Quantity</td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($row['qty']) ?>" disabled>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Notes</td>
                                    <td>:</td>
                                    <td><input type="text" class="form-control" value="<?= htmlspecialchars($row['Notes']) ?>" disabled></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
        <?php
            }
        } else {
            echo "<p class='text-muted'>No items found for this request.</p>";
        }
        ?>
    </div>

    <div class="card card-body card-for-all">
        <div class="table-responsive">
            <table class="table table-borderles">
                <tbody>
                    <tr>
                        <td>Supplier</td>
                        <td>:</td>
                        <td>
                            <select class="form-control select2" name="sel_supplier" id="sel_supplier" disabled>
                                <option value=""><?= htmlspecialchars($dataHeader['supplierName']) ?></option>

                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Request Date</td>
                        <td>:</td>
                        <td>
                            <input class="form-control" type="text" name="request_date" id="request_date" autocomplete="off" value="<?= htmlspecialchars($dataHeader['requestDate']) ?>" disabled>
                        </td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>:</td>
                        <td><textarea class="form-control" name="address" id="address" style="height: 100px; resize: none;" disabled><?= htmlspecialchars($dataHeader['storeAddress']) ?></textarea></td>
                    </tr>
                    <tr>
                        <td>Approver</td>
                        <td>:</td>
                        <td>
                            <select class="form-control select2" name="sel_approver" id="sel_approver" disabled>
                                <option value=""><?= htmlspecialchars($dataHeader['approverName']) ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Remarks Approver</td>
                        <td>:</td>
                        <td>
                            <textarea class="form-control" name="remarks_approver" id="remarks_approver" style="resize: none; height: 100px;" disabled><?= htmlspecialchars($dataHeader['remarks']) ?></textarea>
                            <input type="text" style="display: none;" id="pr_id" value="<?= htmlspecialchars($dataHeader['PrId']) ?>">
                        </td>
                    </tr>
                    <tr>
                        <td>Action</td>
                        <td>:</td>
                        <td>
                            <select class="form-control select2" name="action_approver" id="action_approver" required>
                                <option value="">-- Choose Action --</option>
                                <?php
                                if (!empty($helper)) {
                                    foreach ($helper as $obj) { ?>
                                        <option value="<?= htmlspecialchars($obj['value']) ?>">
                                            <?= htmlspecialchars($obj['name']) ?>
                                        </option>
                                    <?php }
                                } else { ?>
                                    <option value="">Tidak ada data approval</option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="history.back()">Close</button>
                            <button class="btn btn-sm btn-primary" type="submit">Submit</button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</form>
<script src="script/approval.js"></script>
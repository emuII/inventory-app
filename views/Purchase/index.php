<form id="prForm" method="post" onsubmit="return false;">
    <div class="box-body" id="itemContainer">
        <div class="card card-body item-row">
            <div class="table-responsive">
                <table class="table table-borderles tbl-item">
                    <tbody>
                        <tr>
                            <td>Items</td>
                            <td>
                                <select class="form-control select2" name="select_item" required>
                                    <option value="">-- Choose Item --</option>
                                    <?php
                                    $response_data = $item_model->itemList();
                                    if (!empty($response_data)) {
                                        foreach ($response_data as $obj) { ?>
                                            <option value="<?= htmlspecialchars($obj['Id']) ?>">
                                                <?= htmlspecialchars($obj['item_name'] . ' - ' . $obj['type'] . ' - (' . $obj['category'] . ')') ?>
                                            </option>
                                        <?php }
                                    } else { ?>
                                        <option value="">Data item tidak tersedia</option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Qty</td>
                            <td><input type="text" class="form-control" name="qty" required></td>
                        </tr>
                        <tr>
                            <td>Notes</td>
                            <td>
                                <textarea class="form-control" name="notes" style="resize: none; height: 100px;"></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <br>
    </div>

    <table class="table table-borderles">
        <thead>
            <tr>
                <td colspan="2" class="text-end">
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="addRow()">+ Add Item</button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeRow()">- Remove</button>
                </td>
            </tr>
        </thead>
    </table>

    <div class="card card-body card-for-all">
        <div class="table-responsive">
            <table class="table table-borderles">
                <tbody>
                    <tr>
                        <td>Supplier</td>
                        <td>
                            <select class="form-control select2" name="sel_supplier" id="sel_supplier" required>
                                <option value="">-- Choose Supplier --</option>
                                <?php
                                $response_data = $supplier_model->get_supplier_active();
                                if (!empty($response_data)) {
                                    foreach ($response_data as $obj) { ?>
                                        <option value="<?= htmlspecialchars($obj['supplierId']) ?>">
                                            <?= htmlspecialchars($obj['supplier_code'] . ' - ' . $obj['supplier_name']) ?>
                                        </option>
                                    <?php }
                                } else { ?>
                                    <option value="">Tidak ada data supplier</option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Request Date</td>
                        <td>
                            <input class="form-control datepicker" type="text" name="request_date" id="request_date" autocomplete="off" required>
                        </td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td><textarea class="form-control" name="address" id="address" style="height: 100px; resize: none;"></textarea></td>
                    </tr>
                    <tr>
                        <td>Approver</td>
                        <td>
                            <select class="form-control select2" name="sel_approver" id="sel_approver" required>
                                <option value="">-- Choose Approver --</option>
                                <?php
                                $response_data = $approval_member->GetApprovalMember();
                                if (!empty($response_data)) {
                                    foreach ($response_data as $obj) { ?>
                                        <option value="<?= htmlspecialchars($obj['id']) ?>">
                                            <?= htmlspecialchars($obj['username']) ?>
                                        </option>
                                    <?php }
                                } else { ?>
                                    <option value="">Tidak ada data approval</option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Remarks Approver</td>
                        <td>
                            <textarea class="form-control" name="remarks_approver" id="remarks_approver" style="resize: none; height: 100px;"></textarea>
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
<script src="script/purchase.js"></script>
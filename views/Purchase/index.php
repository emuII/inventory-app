<form id="prForm" method="post" onsubmit="return false;">
    <div class="container-fluid py-4">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Request Form</h1>
                    <p class="mb-0 opacity-75">Manage your form request</p>
                </div>
            </div>
        </div>
        <div class="container-section">
            <div class="search-section">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Product Name</label>
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
                    </div>
                </div>
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Quantity</label>
                        <input type="text" class="form-control" name="qty" required>
                    </div>
                </div>
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Notes Product</label>
                        <textarea class="form-control" name="notes" style="resize: none; height: 100px;"></textarea>
                    </div>
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

        <div class="search-section">
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Supplier</label>
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
                </div>
            </div>
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Request Date</label>
                    <input class="form-control datepicker" type="text" name="request_date" id="request_date" autocomplete="off" required>
                </div>
            </div>
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Outlet Address</label>
                    <textarea class="form-control" name="address" id="address" style="height: 100px; resize: none;"></textarea>
                </div>
            </div>
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Choose Approver</label>
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
                </div>
            </div>
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Remarks Approver</label>
                    <textarea class="form-control" name="remarks_approver" id="remarks_approver" style="resize: none; height: 100px;"></textarea>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary-modern btn-modern" type="submit">Submit
                </button> &nbsp;
                <button class="btn btn-outline-secondary btn-modern" type="button" onclick="history.back();">Close
                </button>
            </div>
        </div>
    </div>
</form>
<script src=" script/purchase.js"></script>
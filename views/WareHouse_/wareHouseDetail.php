<?php
require_once 'helpers/format_helper.php';
?>
<form id="whForm" method="post" onsubmit="return false;">
    <br>
    <div class="box-body" id="itemContainer">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Warehouse Details</h1>
                    <p class="mb-0 opacity-75">Manage your history warehouse information</p>
                </div>
            </div>
        </div>

        <?php if (!empty($whHistory)) { ?>
            <?php
            $grouped = [];
            foreach ($whHistory as $row) {
                $key = $row['countId'];
                if (!isset($grouped[$key])) {
                    $grouped[$key] = [];
                }
                $grouped[$key][] = $row;
            }
            ksort($grouped);
            ?>
            <div class="card card-body card-for-all">
                <h3>Receive History</h3>
                <br>
                <div class="table-responsive">

                    <?php
                    $receiveNo = 1;
                    foreach ($grouped as $countId => $rows) {
                        $no = 1;
                    ?>
                        <span>Receiving <i><?= ordinalEn($receiveNo++) ?></i></span>
                        <table class="table table-modern" style="text-align: center;">
                            <thead style="background: #4e73df; color: white;">
                                <th>No</th>
                                <th>Product Name</th>
                                <th>Order Quantity</th>
                                <th>Receive Quantity</th>
                                <th>Receive Date</th>
                                <th>Notes</th>
                            </thead>
                            <tbody>
                                <?php foreach ($rows as $row) { ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($row['itemName']) ?></td>
                                        <td><?= htmlspecialchars($row['orderQty'] ?? 0) ?></td>
                                        <td><?= htmlspecialchars($row['receiveQty'] ?? 0) ?></td>
                                        <td><?= htmlspecialchars($row['dateIn'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($row['notes'] ?? '') ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <br>
                    <?php } ?>

                </div>

                <div class="text-center mt-3">
                    <button type="button" class="btn btn-primary btn-modern" id="addNewEntry">
                        <i class="fas fa-plus me-2"></i> Add New Entry
                    </button>
                </div>
            </div>

        <?php } ?>

        <br>
        <div class="card card-body card-for-all <?= !empty($whHistory) ? 'd-none' : '' ?>" id="currentReceiveSection">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Current Receiving</h4>
            </div>
            <div class="table-container">
                <table class="table table-modern warehouse-detail">
                    <thead style="background: #4e73df; color: white;">
                        <th>No</th>
                        <th>Product Name</th>
                        <th>Order Quantity</th>
                        <th>Receive Quantity</th>
                        <th>Price</th>
                        <th>Notes</th>
                    </thead>
                    <tbody id="warehouseDetailBody">
                        <?php
                        $no = 1;
                        if (!empty($whDetail)) {
                            foreach ($whDetail as $index => $row) {
                        ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <input type="hidden" name="itemId" value="<?= htmlspecialchars($row['itemId']) ?>">
                                        <select class="form-control select2" name="select_item" disabled>
                                            <option><?= htmlspecialchars($row['itemName']) ?></option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="hidden" name="whdId" value="<?= htmlspecialchars($row['warehouseDetailId']) ?>">
                                        <input type="text" class="form-control" value="<?= htmlspecialchars($row['qtyOrder']) ?>" disabled>
                                    </td>
                                    <td>
                                        <div class="quantity-control">
                                            <button type="button" class="btn btn-outline-danger btn-sm minus-btn">-</button>
                                            <input type="text" class="form-control" name="qtyReceive" value="<?= htmlspecialchars($row['qtyReceive'] ?? 0) ?>" required>
                                            <button type="button" class="btn btn-outline-success btn-sm plus-btn">+</button>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="unitPrice" onblur="formatMoney(this)"
                                            value="<?= htmlspecialchars($row['unitPrice'] ?? 0) ?>" <?php if (!empty($whHistory)) echo 'disabled'; ?> required>
                                    </td>
                                    <td>
                                        <textarea class="form-control" name="notes" style="height: 100px; resize: none;"><?= htmlspecialchars($row['notes'] ?? '') ?></textarea>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            $colspan = !empty($whHistory) ? '7' : '6';
                            echo "<tr><td colspan='$colspan' class='text-center text-muted'>No items found for this request.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <br>
    </div>

    <!-- Submit Buttons -->
    <div class="card card-body card-for-all" style="margin-top:-25px;">
        <div class="table-responsive">
            <button type="button" class="btn btn-secondary-modern btn-modern" onclick="history.back()">
                <i class="fas fa-times me-1"></i> Close
            </button>
            <button class="btn btn-primary-modern btn-modern" type="submit">
                <i class="fas fa-check me-1"></i> Submit
            </button>
        </div>
    </div>
</form>

<script>
    $(function() {
        $("#whForm").on("submit", function(e) {
            e.preventDefault();
            submitWareHouse();
        });

        // Plus button functionality
        $(document).on('click', '.plus-btn', function() {
            const input = $(this).siblings('input[name="qtyReceive"]');
            let value = parseInt(input.val()) || 0;
            input.val(value + 1);
        });

        $(document).on('click', '.minus-btn', function() {
            const input = $(this).siblings('input[name="qtyReceive"]');
            let value = parseInt(input.val()) || 0;
            if (value > 0) {
                input.val(value - 1);
            }
        });

        $(document).on('input', 'input[name="qtyReceive"]', function() {
            let value = $(this).val();
            if (value < 0 || isNaN(value)) {
                $(this).val(0);
            }
        });

        <?php if (!empty($whHistory)) { ?>
            $('#addNewEntry').click(function() {
                $('#currentReceiveSection').removeClass('d-none');

                if ($('#warehouseDetailBody tr').length === 1 && $('#warehouseDetailBody tr td.text-muted').length > 0) {
                    addNewRow();
                }

                $(this).hide();
            });
        <?php } ?>

        function initializeSelect2() {
            $('.select2').select2({
                disabled: true
            });
        }

        initializeSelect2();
    });

    function addNewRow() {
        const tbody = $('#warehouseDetailBody');
        const rowCount = tbody.find('tr').length;
        const newRowNumber = rowCount + 1;

        if (tbody.find('tr').length === 1 && tbody.find('tr td.text-muted').length > 0) {
            tbody.empty();
        }

        const newRow = `
            <tr>
                <td>${newRowNumber}</td>
                <td>
                    <input type="hidden" name="itemId" value="">
                    <select class="form-control select2" name="select_item">
                        <option value="">Select Product</option>
                        <?php
                        if (!empty($availableProducts)) {
                            foreach ($availableProducts as $product) {
                                echo '<option value="' . htmlspecialchars($product['id']) . '">' . htmlspecialchars($product['name']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <input type="hidden" name="whdId" value="">
                    <input type="text" class="form-control" name="orderQty" value="0" disabled>
                </td>
                <td>
                    <div class="quantity-control">
                        <button type="button" class="btn btn-outline-danger btn-sm minus-btn">-</button>
                        <input type="text" class="form-control" name="qtyReceive" value="0" required>
                        <button type="button" class="btn btn-outline-success btn-sm plus-btn">+</button>
                    </div>
                </td>
                <td>
                    <input type="text" class="form-control" name="unitPrice" onblur="formatMoney(this)"  value="0" required>
                </td>
                <td>
                    <textarea class="form-control" name="notes" style="height: 100px; resize: none;"></textarea>
                </td>
                <?php if (!empty($whHistory)) { ?>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-row">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                <?php } ?>
            </tr>
        `;

        tbody.append(newRow);

        $('.select2').select2();
    }

    function updateRowNumbers() {
        $('#warehouseDetailBody tr').each(function(index) {
            $(this).find('td:first').text(index + 1);
        });
    }

    function submitWareHouse() {
        let arrayData = new Array();
        const body = $(".container-fluid");
        const table = body.find('.warehouse-detail tbody tr');
        let hasError = false;
        if ($('#currentReceiveSection').is(':visible')) {
            table.each(function() {
                if ($(this).find('td.text-muted').length > 0) {
                    return true;
                }

                const whdId = parseInt($(this).find("input[name='whdId']").val()) || 0;
                const qtyReceive = parseInt($(this).find("input[name='qtyReceive']").val()) || 0;
                const unitPriceRaw = $(this).find("input[name='unitPrice']").val() || 0;
                const unitPrice = unformatMoneyValue(unitPriceRaw);
                const notes = $(this).find("textarea[name='notes']").val() || '';
                const itemId = parseInt($(this).find("input[name='itemId']").val()) || 0;
                const itemName = $(this).find("select[name='select_item'] option:selected").text() || '';
                const selectedItemId = $(this).find("select[name='select_item']").val();

                if (qtyReceive === 0) {
                    Swal.fire({
                        title: "Invalid Input",
                        text: "The number of Receipts cannot be 0 on the product " + (itemName),
                        icon: "warning",
                    });
                    hasError = true;
                    return false;
                }

                if (unitPrice === 0 || unitPrice === '0') {
                    Swal.fire({
                        title: "Invalid Input",
                        text: "Unit Price cannot be 0 fot the product " + (itemName),
                        icon: "warning",
                    });
                    hasError = true;
                    return false; // break dari .each
                }
                arrayData.push({
                    whdId: whdId,
                    qtyReceive: qtyReceive,
                    unitPrice: unitPrice,
                    notes: notes,
                    itemId: itemId || selectedItemId,
                    itemName: itemName
                });
            });
        }
        if (hasError) {
            return;
        }
        var dto = {
            warehouseId: <?= json_encode($warehouseId) ?>,
            details: arrayData
        };

        console.log(dto);

        $.ajax({
            url: "middleware/ajax_handler.php?controller=wareHouse&action=submitWareHouse",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(dto),
            success: function(response) {
                Swal.fire({
                    title: "Success!",
                    text: "Warehouse submitted successfully.",
                    icon: "success",
                }).then(() => {
                    window.location = "/inventory-app/index.php?route=wareHouse_";
                });
            },
            error: function(xhr, status, error) {
                console.log(xhr);

                let message = "Terjadi kesalahan tidak diketahui";

                try {
                    let raw = xhr.responseText;
                    raw = raw.replace("Success", "").trim();
                    let parsed = JSON.parse(raw);
                    if (parsed.error) {
                        message = parsed.error;
                    }
                } catch (e) {
                    console.error("Parsing error:", e);
                }
                Swal.fire({
                    title: "Failed!",
                    text: message,
                    icon: "error",
                });
            }
        });
    }
</script>
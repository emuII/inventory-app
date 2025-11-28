<?php
require_once 'helpers/format_helper.php';
?>
<form id="whForm" method="post" onsubmit="return false;">
    <br>
    <div class="box-body" id="itemContainer">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Warearehouse Details</h1>
                    <p class="mb-0 opacity-75">Manage your history warehouse information</p>
                </div>
            </div>
        </div>
        <?php if (!empty($whHistory)) { ?>

            <?php
            // Group per countId (per sekali submit)
            $grouped = [];
            foreach ($whHistory as $row) {
                $key = $row['countId'];          // 1 submit = 1 countId
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
                                <th>Price</th>
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
                                        <td><?= htmlspecialchars($row['unitPrice'] ?? 0) ?></td>
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
                        <i class="fas fa-plus me-2"></i> Add New Supplier
                    </button>
                </div>
            </div>

        <?php } ?>

        <br>
        <div class="card card-body card-for-all ">
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
                    <tbody>
                        <?php
                        $no = 1;
                        if (!empty($whDetail)) {
                            foreach ($whDetail  as $index => $row) {
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
                                        <input type="text" class="form-control" name="unitPrice"
                                            value="<?= htmlspecialchars($row['unitPrice'] ?? 0) ?>" <?php if (!empty($whHistory)) echo 'disabled'; ?> required>
                                    </td>
                                    <td>
                                        <textarea class="form-control" name="notes" style="height: 100px; resize: none;"><?= htmlspecialchars($row['notes'] ?? '') ?></textarea>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<p class='text-muted'>No items found for this request.</p>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <br>
    </div>
    <div class="card card-body card-for-all" style="margin-top:-25px;">
        <div class="table-responsive">
            <button type="button" class="btn btn-sm btn-secondary" onclick="history.back()">Close</button>
            <button class="btn btn-sm btn-primary" type="submit">Submit</button>
        </div>
    </div>
</form>

<script>
    $(function() {
        $("#whForm").on("submit", function(e) {
            e.preventDefault();
            submitWareHouse();
        });

        $('.plus-btn').click(function() {
            const input = $(this).siblings('input[name="qtyReceive"]');
            let value = parseInt(input.val()) || 0;
            input.val(value + 1);
        });

        $('.minus-btn').click(function() {
            const input = $(this).siblings('input[name="qtyReceive"]');
            let value = parseInt(input.val()) || 0;
            if (value > 0) {
                input.val(value - 1);
            }
        });

        $('input[name="qtyReceive"]').on('input', function() {
            let value = $(this).val();
            if (value < 0 || isNaN(value)) {
                $(this).val(0);
            }
        });
    });

    function submitWareHouse() {
        let arrayData = new Array();
        const body = $(".container-fluid");
        const table = body.find('.warehouse-detail tbody tr');

        table.each(function() {
            const whdId = parseInt($(this).find("input[name='whdId']").val());
            const qtyReceive = parseInt($(this).find("input[name='qtyReceive']").val());
            const unitPrice = $(this).find("input[name='unitPrice']").val();
            const notes = $(this).find("textarea[name='notes']").val();
            const itemId = parseInt($(this).find("input[name='itemId']").val());
            const itemName = $(this).find("select[name='select_item'] option:selected").text();
            arrayData.push({
                whdId: whdId,
                qtyReceive: qtyReceive,
                unitPrice: unitPrice,
                notes: notes,
                itemId: itemId,
                itemName: itemName
            });
        });

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
                    // Ambil text mentah
                    let raw = xhr.responseText;

                    // Hilangkan kata 'Success' kalau ada
                    raw = raw.replace("Success", "").trim();

                    // Parse JSON
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
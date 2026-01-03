<form id="prEdit" method="post" onsubmit="return false;">
    <div class="container-fluid py-4">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Form Edit Product</h1>
                    <p class="mb-0 opacity-75">Manage your form product</p>
                </div>
            </div>
        </div>
        <div class="container-section">
            <div class="search-section">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Product Name</label>
                        <input type="text" class="form-control form-control-modern" name="itemName" value="<?= $constHeader['itemName'] ?>" placeholder="Enter Product Name">
                    </div>
                </div>
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Product Type</label>
                        <select class="form-control form-control-modern select2" name="productType" id="productType">
                            <option value="All" <?= ($constHeader['itemType'] === 'All')   ? 'selected' : '' ?>>All</option>
                            <option value="Sport" <?= ($constHeader['itemType'] === 'Sport') ? 'selected' : '' ?>>Sport</option>
                            <option value="Matic" <?= ($constHeader['itemType'] === 'Matic') ? 'selected' : '' ?>>Matic</option>
                            <option value="Manual" <?= ($constHeader['itemType'] === 'Manual') ? 'selected' : '' ?>>Manual</option>
                        </select>

                    </div>
                </div>
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Product Category</label>
                        <label hidden id="lbId"><?= $constHeader['itemId'] ?></label>
                        <select class="form-control form-control-modern select2" name="productCategory" id="productCategory">
                            <option value="Sparepart" <?= ($constHeader['itemCategory'] === 'Sparepart')   ? 'selected' : '' ?>>Sparepart</option>
                            <option value="Aksesoris" <?= ($constHeader['itemCategory'] === 'Aksesoris')   ? 'selected' : '' ?>>Aksesoris</option>
                        </select>
                    </div>
                </div>
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Quantity</label>
                        <input type="text" class="form-control form-control-modern" name="quantity" value="<?= $constHeader['qty'] ?>" placeholder="Enter Product Qty">
                    </div>
                </div>
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Sales Price</label>
                        <input type="text" class="form-control form-control-modern" name="salesPrice" value="<?= $constHeader['salesPrice'] ?>" onblur="formatMoney(this);" placeholder="Enter Price">
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-modern" type="button" onclick="history.back();">
                        <i class="fa-solid fa-angle-left"></i>Close
                    </button>&nbsp;
                    <button class="btn btn-primary-modern btn-modern" type="submit">Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#prEdit").on("submit", function(e) {
            e.preventDefault();
            updateItem();
        });
    });

    function updateItem() {
        const data = {
            itemId: parseInt($('#lbId').text()),
            item_name: $('input[name="itemName"]').val(),
            type: $('#productType').val(),
            category: $('#productCategory').val(),
            qty: $('input[name="quantity"]').val(),
            sales_price: unformatMoneyValue($('input[name="salesPrice"]').val())
        };

        $.ajax({
            url: "middleware/ajax_handler.php?controller=item&action=updateSingleItem",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(data),
            success: function(response) {
                var response = JSON.parse(response);

                if (response.success) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                    }).then(() => {
                        window.location = "/inventory-app/index.php?route=items";
                    });
                    return;
                }

                if (response.needs_confirmation) {
                    Swal.fire({
                        title: "Konfirmasi Update",
                        text: response.message + "\n\nApakah Anda yakin ingin melanjutkan update? Ini akan membuat duplikat item.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, Update!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            confirmUpdateItem(data.itemId, data);
                        }
                    });
                    return;
                }
                Swal.fire({
                    title: "Failed!",
                    text: response.message,
                    icon: "error",
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

    function confirmUpdateItem(itemId, data) {
        $.ajax({
            url: "middleware/ajax_handler.php?controller=item&action=confirmUpdateItem",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                itemId: itemId,
                ...data
            }),
            success: function(response) {
                var response = JSON.parse(response);
                if (response.success) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                    }).then(() => {
                        window.location = "/inventory-app/index.php?route=items";
                    });
                } else {
                    Swal.fire({
                        title: "Failed!",
                        text: response.message,
                        icon: "error",
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: "Failed!",
                    text: "Terjadi kesalahan saat mengkonfirmasi update.",
                    icon: "error",
                });
            }
        });
    }
</script>
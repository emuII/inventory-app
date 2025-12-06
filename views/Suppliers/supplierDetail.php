<form id="whForm" method="post" onsubmit="return false;">
    <br>
    <div class="box-body" id="itemContainer">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Supplier Detail</h1>
                    <p class="mb-0 opacity-75">Manage your supplier information</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-section">
        <div class="search-section">
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Supplier Code</label>
                    <input type="text" class="form-control" name="supplier_code" value="<?php echo $response['supplierCode']; ?>" readonly="readonly">
                </div>
            </div>
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Supplier Name</label>
                    <input type="text" class="form-control" name="supplier_name" value="<?php echo $response['supplierName']; ?>">
                </div>
            </div>
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Supplier Address</label>
                    <textarea class="form-control" style="resize: none;" name="supplier_address"><?php echo $response['supplierAddress']; ?></textarea>
                </div>
            </div>
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Supplier Contact</label>
                    <input type="text" class="form-control" name="supplier_contact" value="<?php echo $response['supplierContact']; ?>">
                </div>
            </div>
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select class="form-control select2" name="supplier_status">
                        <option value="0"></option>
                        <?php
                        if (!empty($helper)) {
                            foreach ($helper as $obj) {

                                if (!in_array($obj['name'], ['Active', 'In active'])) {
                                    continue;
                                }
                        ?>
                                <option value="<?= htmlspecialchars($obj['value']) ?>" <?php if ($obj["value"] == $response["supplierStatus"]) echo "selected"; ?>>
                                    <?= htmlspecialchars($obj['name']) ?>
                                </option>
                            <?php
                            }
                        } else {
                            ?>
                            <option value="">Tidak ada data supplier</option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-modern" type="button" onclick="history.back()">
                    <i class="fa-solid fa-angle-left"></i> Back
                </button> &nbsp;
                <button class="btn btn-primary-modern btn-modern" type="submit">Submit
                </button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function() {
        $("#whForm").on("submit", function(e) {
            e.preventDefault();
            updateSupplier();
        });
    });

    function updateSupplier() {
        var dto = {
            supplierCode: $('input[name="supplier_code"]').val(),
            supplierName: $('input[name="supplier_name"]').val(),
            supplierAddress: $('textarea[name="supplier_address"]').val(),
            supplierContact: $('input[name="supplier_contact"]').val(),
            supplierStatus: parseInt($('select[name="supplier_status"]').val())
        };
        console.log(dto);
        $.ajax({
            url: "middleware/ajax_handler.php?controller=Supplier&action=updateSupplier",
            type: 'POST',
            data: JSON.stringify(dto),
            success: function(response) {
                Swal.fire({
                    title: "Success!",
                    text: "Supplier edit successfully.",
                    icon: "success",
                }).then(() => {
                    window.location = "/inventory-app/index.php?route=suppliers";
                });
            },
            error: function() {
                alert('An error occurred while updating the supplier.');
            }
        });
    }
</script>
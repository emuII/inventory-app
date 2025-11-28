    <div class="container-fluid py-4">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Warearehouse Management</h1>
                    <p class="mb-0 opacity-75">Manage your warehouse information and details</p>
                </div>
            </div>
        </div>
        <br />
        <div class="search-section">
            <h5 class="mb-3">Search & Filter</h5>
            <form id="searchForm">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Request Number</label>
                        <input type="text" class="form-control form-control-modern" name="filter_number" placeholder="Enter request number">
                    </div>
                    <div class="filter-group">
                        <div class="date-range-group">
                            <div class="date-input-group">
                                <label class="form-label filter-label">From Date</label>
                                <input type="date" class="form-control form-control-modern" name="dateFrom" id="filterDateFrom">
                            </div>
                            <div class="date-separator">to</div>
                            <div class="date-input-group">
                                <label class="form-label filter-label">To Date</label>
                                <input type="date" class="form-control form-control-modern" name="dateTo" id="filterDateTo">
                            </div>
                        </div>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Supplier</label>
                        <select class="form-control form-control-modern select2" name="supplier_name">
                            <option value="0">All Supplier</option>
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
                    <div class="filter-group" style="width: 27em;">
                        <label class="filter-label">Status</label>
                        <select class="form-control form-control-modern select2" name="warehouse_status">
                            <option value="0">All Statuses</option>
                            <?php $response_data = $helper_model->getStatus("warehouse");
                            foreach ($response_data as $obj) {     ?>
                                <option value="<?php echo $obj['value']; ?>"><?php echo $obj['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary-modern btn-modern" type="button" onclick="searchWareHouse();">
                        <i class="fas fa-search me-2"></i> Search
                    </button> &nbsp;
                    <button class="btn btn-outline-secondary btn-modern" type="button" onclick="clearWarehouse();">
                        <i class="fas fa-trash me-2"></i> Clear Filters
                    </button>
                </div>
            </form>
        </div>
        <br />
        <?php if (isset($_GET['success'])) { ?>
            <div class="alert alert-success">
                <p>Success !</p>
            </div>
        <?php } ?>
        <?php if (isset($_GET['remove'])) { ?>
            <div class="alert alert-danger">
                <p>Failed !</p>
            </div>
        <?php } ?>
        <div class="card card-body">
            <div class="table-responsive">
                <table class="table dt-tbl table-modern" id="tbWareHouse">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Request Number</th>
                            <th>Order Qty</th>
                            <th>Receive Qty</th>
                            <th>Total Amount</th>
                            <th>Date In</th>
                            <th>Supplier Name</th>
                            <th>Requestor Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="wareHouseTable" align="center;"></tbody>
                </table>
            </div>
        </div>

    </div>
    <script>
        $(document).ready(function() {
            searchWareHouse();
        });

        function searchWareHouse() {
            $.ajax({
                type: 'POST',
                url: 'middleware/ajax_handler.php?controller=wareHouse&action=warehouseList',
                data: $('#searchForm').serialize(),
                success: function(response) {

                    if ($.fn.DataTable.isDataTable('#tbWareHouse')) {
                        $('#tbWareHouse').DataTable().destroy();
                    }

                    $('#wareHouseTable').html(response);
                    $('#tbWareHouse').DataTable();
                },
                error: function(err) {
                    alert("Error loading data");
                }
            });
        }

        function clearWarehouse() {
            $('#searchForm')[0].reset();
            $('#searchForm select.select2').val('').trigger('change');
            $('#searchForm select.select2').val('0').trigger('change');
            searchWareHouse();
        }
    </script>
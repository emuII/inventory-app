<div class="container-fluid py-4">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">Delivery Order Log</h1>
                <p class="mb-0 opacity-75">Manage your log information and details</p>
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
            </div>
            <div class="filter-row">
                <div class="filter-group" style="width: 27em;">
                    <label class="filter-label">Status</label>
                    <select class="form-control form-control-modern select2" name="transaction_status">
                        <option value="0">All Statuses</option>
                        <?php $response_data = $helper_model->getStatus("delivery_order");
                        foreach ($response_data as $obj) {     ?>
                            <option value="<?php echo $obj['value']; ?>"><?php echo $obj['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary-modern btn-modern" type="button" onclick="searchDeliveryOrder();">
                    <i class="fas fa-search me-2"></i> Search
                </button> &nbsp;
                <button class="btn btn-outline-secondary btn-modern" type="button" onclick="clearRequest();">
                    <i class="fas fa-trash me-2"></i> Clear Filters
                </button> &nbsp;
                <button type="button" class="btn btn-primary-modern btn-md mr-2" data-toggle="modal" data-target="#myModal">
                    <i class="fa fa-plus"></i> Add Data</button>
            </div>
        </form>
    </div>

</div> <br>

<div class="card card-body">
    <div class="table-responsive">
        <table class="table dt-tbl table-modern" id="tableDeliveryOrderLogs">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Do Number</th>
                    <th>Do Date</th>
                    <th>Total Amount</th>
                    <th>Tax</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="deliveryOrderLogs">
            </tbody>
        </table>
    </div>
</div>
<script src="script/deliveryOrderLog.js"></script>
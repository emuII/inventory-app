<div class="container-fluid py-4">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">User Management</h1>
                <p class="mb-0 opacity-75">Manage your user information and details</p>
            </div>
        </div>
    </div>
    <div class="search-section">
        <h5 class="mb-3">Search & Filter</h5>
        <form id="searchForm">
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Supplier Code</label>
                    <input type="text" class="form-control form-control-modern" name="filter_code" placeholder="Enter request number">
                </div>

            </div>
            <div class="filter-row">
                <div class="filter-group" style="width: 27em;">
                    <label class="filter-label">Status</label>
                    <select class="form-control form-control-modern select2" name="warehouse_status">
                        <option value="0">All Statuses</option>
                        <?php $response_data = $helper_model->getStatus("general");
                        foreach ($response_data as $obj) {     ?>
                            <option value="<?php echo $obj['value']; ?>"><?php echo $obj['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary-modern btn-modern" type="button" onclick="searchSupplier();">
                    <i class="fas fa-search me-2"></i> Search
                </button> &nbsp;
                <button class="btn btn-outline-secondary btn-modern" type="button" onclick="clearSupplier();">
                    <i class="fas fa-trash me-2"></i> Clear Filters
                </button> &nbsp;
                <a type="button" href="index.php?route=userManagements/userAdd" class="btn btn-primary-modern btn-md mr-2">
                    <i class="fa fa-plus"></i> Add User</a>
                <div class="clearfix"></div>
            </div>
        </form>
    </div>
    <br />
    <div class="card card-body">
        <div class="table-responsive">
            <table class="table dt-tbl table-modern" id="tbUser">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="userTable">
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="script/userManagement.js"></script>
<div class="container-fluid py-4">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">Supplier Management</h1>
                <p class="mb-0 opacity-75">Manage your supplier information and details</p>
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
                <div class="filter-group">
                    <label class="filter-label">Supplier Name</label>
                    <input type="text" class="form-control form-control-modern" name="filter_name" placeholder="Enter supplier name">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Supplier Contact</label>
                    <input type="text" class="form-control form-control-modern" name="filter_contact" placeholder="Enter supplier name">
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
                <button type="button" class="btn btn-primary btn-md mr-2" data-toggle="modal" data-target="#myModal">
                    <i class="fa fa-plus"></i> Insert Data</button>
                <div class="clearfix"></div>
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
            <table class="table dt-tbl table-modern" id="tbSupplier">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Supplier Code</th>
                        <th>Supplier Name</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Contact</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="supplierTable">
                </tbody>
            </table>
        </div>
    </div>

    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content" style=" border-radius:0px;">
                <div class="modal-header">
                    <h5 class="modal-title">Add Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="service/supplierService.php?add_supplier=add_supplier" method="POST">
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <?php
                            $format = $helper_model->generate_code("SPL");
                            ?>
                            <tr>
                                <td>Supplier Code</td>
                                <td>
                                    <input type="text" readonly="readonly" required value="<?php echo $format; ?>" class="form-control" name="supplier_code">
                                </td>
                            </tr>
                            <tr>
                                <td>Supplier Name</td>
                                <td>
                                    <input type="text" placeholder="Supplier Name" required class="form-control" name="supplier_name">
                                </td>
                            </tr>
                            <tr>
                                <td>Supplier Address</td>
                                <td>
                                    <textarea placeholder="Supplier Address" style="resize: none;" class="form-control" name="supplier_address"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>Supplier Status</td>
                                <td>
                                    <select class="form-control select2" name="supplier_status">
                                        <option value="0"></option>
                                        <?php $response_data = $helper_model->getStatus("general");
                                        foreach ($response_data as $obj) {     ?>
                                            <option value="<?php echo $obj['value']; ?>"><?php echo $obj['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Supplier Contact</td>
                                <td>
                                    <input type="text" placeholder="Supplier Contact" required class="form-control" name="supplier_contact">
                                </td>
                            </tr>

                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="script/supplier.js"></script>
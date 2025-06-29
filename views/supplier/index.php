        <h3>Supplier</h3>
        <br />
        <button type="button" class="btn btn-primary btn-md mr-2" data-toggle="modal" data-target="#myModal">
            <i class="fa fa-plus"></i> Insert Data</button>
        <div class="clearfix"></div>
        <br />
        <div class="card card-body">
            <div class="table-responsive">
                <form id="searchForm">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td>Supplier Code</td>
                                <td>
                                    <input type="text" class="form-control" name="filter_code">
                                </td>
                                <td>Supplier Name</td>
                                <td>
                                    <input type="text" class="form-control" name="filter_name" id="">
                                </td>
                            </tr>
                            <tr>
                                <td>Supplier Contact</td>
                                <td>
                                    <input type="text" class="form-control" name="filter_contact" id="">
                                </td>
                                <td>Supplier Status</td>
                                <td>
                                    <select class="form-control select2" name="filter_status">
                                        <option value="0"></option>
                                        <?php $response_data = $helper_model->get_list_status("master-code");
                                        foreach ($response_data as $obj) {     ?>
                                            <option value="<?php echo $obj['status_id']; ?>"><?php echo $obj['status_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <button class="btn btn-primary" type="button" onclick="searchSupplier();">
                        <i class="fa fa-search"></i> Search
                    </button>

                    <button class="btn btn-primary" type="button" onclick="celarSupplier();">
                        <i class="fa fa-trash"></i> Clear
                    </button>
                </form>
            </div>
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
                <table class="table dt-tbl table-bordered table-striped table-sm" id="example1">
                    <thead>
                        <tr style="background:#DFF0D8;color:#333;">
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
                <!-- Modal content-->
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
                                            <?php $response_data = $helper_model->get_list_status("master-code");
                                            foreach ($response_data as $obj) {     ?>
                                                <option value="<?php echo $obj['status_id']; ?>"><?php echo $obj['status_name']; ?></option>
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

        <script>
            $(document).ready(function() {
                searchSupplier();
            });

            function searchSupplier() {
                $.ajax({
                    type: 'POST',
                    url: 'middleware/ajax_handler.php?controller=supplier&action=supplier_list',
                    data: $('#searchForm').serialize(),
                    success: function(response) {
                        console.log(response)
                        $('#supplierTable').html(response);
                    },
                    error: function(err) {
                        alert("Error loading data");
                    }
                });
            }

            function celarSupplier() {
                $('#searchForm')[0].reset();
                $('#searchForm select.select2').val('').trigger('change');
                searchSupplier();
            }
        </script>
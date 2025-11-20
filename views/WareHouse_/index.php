        <h3>WareHouse</h3>
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
                                        <?php $response_data = $helper_model->getStatus("general");
                                        foreach ($response_data as $obj) {     ?>
                                            <option value="<?php echo $obj['value']; ?>"><?php echo $obj['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                    <button class="btn btn-primary" type="button" onclick="searchWareHouse();">
                        <i class="fa fa-search"></i> Search
                    </button>

                    <button class="btn btn-primary" type="button" onclick="celarWareHouse();">
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
                            <th>Request Number</th>
                            <th>Date In</th>
                            <th>Requestor Name</th>
                            <th>Total Amount</th>
                            <th>Supplier Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="wareHouseTable" align="center;">

                    </tbody>
                </table>
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
                        console.log(response)
                        $('#wareHouseTable').html(response);
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
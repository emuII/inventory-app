        <h3>Product</h3>
        <br />
        <a href='index.php?route=product/add' class='btn btn-primary btn-md mr-2'>
            <i class="fa fa-plus"></i> Insert Data</a>
        <div class="clearfix"></div>
        <br />
        <div class="card card-body">
            <div class="table-responsive">
                <form id="searchForm">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td>Product Code</td>
                                <td>
                                    <input type="text" class="form-control" name="filter_code">
                                </td>
                                <td>Product Name</td>
                                <td>
                                    <input type="text" class="form-control" name="filter_name" id="">
                                </td>
                            </tr>
                            <tr>
                                <td>Category Name</td>
                                <td>
                                    <select class="form-control select2" name="filter_category">
                                        <option value="0"></option>
                                        <?php $response_data = $category_model->get_category_active();
                                        foreach ($response_data as $obj) {     ?>
                                            <option value="<?php echo $obj['category_id']; ?>"><?php echo $obj['category_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>Supplier Name</td>
                                <td>
                                    <select class="form-control select2" name="filter_supplier">
                                        <option value="0"></option>
                                        <?php $response_data = $supplier_model->get_supplier_active();
                                        foreach ($response_data as $obj) {     ?>
                                            <option value="<?php echo $obj['supplier_id']; ?>"><?php echo $obj['supplier_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Brand Name</td>
                                <td>
                                    <select class="form-control select2" name="filter_brand">
                                        <option value="0"></option>
                                        <?php $response_data = $brand_model->get_brand_active();
                                        foreach ($response_data as $obj) {     ?>
                                            <option value="<?php echo $obj['brand_id']; ?>"><?php echo $obj['brand_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>Product Status</td>
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
                    <button class="btn btn-primary" type="button" onclick="searchProduct();">
                        <i class="fa fa-search"></i> Search
                    </button>

                    <button class="btn btn-primary" type="button" onclick="clearProduct();">
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
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>Category Name</th>
                            <th>Supplier Name</th>
                            <th>Brand</th>
                            <th>Product Qty</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="productTable">

                    </tbody>
                </table>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                searchProduct();
            });

            function searchProduct() {
                $.ajax({
                    type: 'POST',
                    url: 'middleware/ajax_handler.php?controller=product&action=product_list',
                    data: $('#searchForm').serialize(),
                    success: function(response) {
                        console.log(response)
                        $('#productTable').html(response);
                    },
                    error: function(err) {
                        alert("Error loading data");
                    }
                });
            }

            function clearProduct() {
                $('#searchForm')[0].reset();
                $('#searchForm select.select2').val('').trigger('change');
                searchProduct();
            }
        </script>
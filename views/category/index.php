        <h4>Category</h4>
        <br />
        <button type="button" class="btn btn-primary btn-md mr-2" data-toggle="modal" data-target="#modalCategory">
            <i class="fa fa-plus"></i> Insert Data</button>
        <div class="clearfix"></div>
        <br />
        <!-- view barang -->
        <div class="card card-body">
            <div class="table-responsive">
                <form id="searchForm">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td>Category Code</td>
                                <td>
                                    <input type="text" class="form-control" name="filter_code">
                                </td>
                                <td>Category Name</td>
                                <td>
                                    <input type="text" class="form-control" name="filter_name" id="">
                                </td>
                            </tr>
                            <tr>
                                <td>Category Status</td>
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
                    <button class="btn btn-primary" type="button" onclick="searchCategory();">
                        <i class="fa fa-search"></i> Search
                    </button>

                    <button class="btn btn-primary" type="button" onclick="celarCategory();">
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
                <table class="table table-bordered table-striped table-sm" id="example1">
                    <thead>
                        <tr style="background:#DFF0D8;color:#333;">
                            <th>No.</th>
                            <th>Category Code</th>
                            <th>Category Name</th>
                            <th>Category Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="categoryTable"></tbody>
                </table>
            </div>
        </div>

        <div id="modalCategory" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content" style=" border-radius:0px;">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Category</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="service/categoryService.php?add_category=add_category" method="POST">
                        <div class="modal-body">
                            <table class="table table-borderless">
                                <?php
                                $format = $helper_model->generate_code("CAT");
                                ?>
                                <tr>
                                    <td>Category Code</td>
                                    <td>
                                        <input type="text" readonly="readonly" required value="<?php echo $format; ?>" class="form-control" name="category_code">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Category Name</td>
                                    <td>
                                        <input type="text" placeholder="Category Name" required class="form-control" name="category_name">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Category Description</td>
                                    <td>
                                        <textarea placeholder="Category Description" style="resize: none;" class="form-control" name="category_desc"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Category Status</td>
                                    <td>
                                        <select class="form-control select2" name="category_status">
                                            <option value="0"></option>
                                            <?php $response_data = $helper_model->get_list_status("master-code");
                                            foreach ($response_data as $obj) {     ?>
                                                <option value="<?php echo $obj['status_id']; ?>"><?php echo $obj['status_name']; ?></option>
                                            <?php } ?>
                                        </select>
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
                searchCategory();
            });

            function searchCategory() {
                $.ajax({
                    type: 'POST',
                    url: 'middleware/ajax_handler.php?controller=category&action=category_list',
                    data: $('#searchForm').serialize(),
                    success: function(response) {
                        $('#categoryTable').html(response);
                    },
                    error: function(err) {
                        alert("Error loading data");
                    }
                });
            }

            function celarCategory() {
                $('#searchForm')[0].reset();
                $('#searchForm select.select2').val('').trigger('change');
                searchCategory();
            }
        </script>
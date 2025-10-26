        <h3>Item Master</h3>
        <br />
        <button type="button" class="btn btn-primary btn-md mr-2" data-toggle="modal" data-target="#myModal">
            <i class="fa fa-plus"></i> Add Data</button>
        <div class="clearfix">

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
                <table class="table table-bordered table-striped" id="tbItems">
                    <thead>
                        <tr style="background:#DFF0D8;color:#333;">
                            <th>No.</th>
                            <th>Product Name</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Qty</th>
                            <th>Pice</th>
                        </tr>
                    </thead>
                    <tbody id="tableItem">

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
                    <form action="import.php" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <table class="table table-borderless">

                                <tr>
                                    <td>Import Excel</td>
                                    <td>
                                        <input type="file" class="form-control" name="file" accept=".xls,.xlsx" required>
                                        <br>
                                        <a href="template/template_import.xlsx"
                                            class="btn btn-cxs btn-primary"
                                            download>
                                            Download Template
                                        </a>
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
                searchItem();
            });

            function searchItem() {
                $.ajax({
                    type: 'POST',
                    url: 'middleware/ajax_handler.php?controller=item&action=getItemList',
                    data: $('#searchForm').serialize(),
                    success: function(response) {
                        $('#tableItem').html(response);
                        $('#tbItems').DataTable(); // inisialisasi setelah data masuk
                    },
                    error: function(err) {
                        alert("Error loading data");
                    }
                });
            }
        </script>
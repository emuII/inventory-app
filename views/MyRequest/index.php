<h3>My Request</h3>
<br />

<div class="card card-body">
    <div class="table-responsive">

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
        <table class="table dt-tbl table-bordered table-striped table-sm" id="tbRequest">
            <thead>
                <tr style="background:#DFF0D8;color:#333;">
                    <th>No.</th>
                    <th>Request Number</th>
                    <th>Request Date</th>
                    <th>Requestor Name</th>
                    <th>Status</th>
                    <th>Supplier Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="requestTable">

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

<script>
    $(document).ready(function() {
        searchRequest();
    });

    function searchRequest() {
        $.ajax({
            type: 'POST',
            url: 'middleware/ajax_handler.php?controller=purchaseRequest&action=requestList',
            data: $('#searchForm').serialize(),
            success: function(response) {
                $('#requestTable').html(response);
                $('#tbRequest').DataTable();
            },
            error: function(err) {
                alert("Error loading data");
            }
        });

    }
</script>
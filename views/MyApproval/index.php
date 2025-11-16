<h3>My Approval</h3>
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
        <table class="table dt-tbl table-bordered table-striped table-sm" id="tbApprover">
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
            <tbody id="approverTable">

            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        searchRequest();
    });

    function searchRequest() {
        $.ajax({
            type: 'POST',
            url: 'middleware/ajax_handler.php?controller=approval&action=approvalList',
            data: $('#searchForm').serialize(),
            success: function(response) {
                $('#approverTable').html(response);
                $('#tbApprover').DataTable();
            },
            error: function(err) {
                alert("Error loading data");
            }
        });

    }
</script>
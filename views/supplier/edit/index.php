 <?php
    $supplier_code = $_GET['supplier_code'];
    $response = $supplier_model->get_supplier_by_code($supplier_code);
    ?>

 <a href="index.php?route=supplier" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Back </a>
 <h4>Edit Supplier</h4>
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
         <table class="table table-striped">
             <form action="service/supplierService.php?edit_supplier=edit" method="POST">
                 <tr>
                     <td>Supplier Code</td>
                     <td>
                         <input type="text" readonly="readonly" class="form-control" name="supplier_code" value="<?php echo $response['supplier_code']; ?>">
                     </td>
                 </tr>
                 <tr>
                     <td>Supplier Name</td>
                     <td>
                         <input type="text" class="form-control" name="supplier_name" value="<?php echo $response['supplier_name']; ?>">
                     </td>
                 </tr>
                 <tr>
                     <td>Supplier Address</td>
                     <td>
                         <textarea class="form-control" name="supplier_address"><?php echo $response['supplier_address']; ?></textarea>
                     </td>
                 </tr>
                 <tr>
                     <td>Supplier Status</td>
                     <td>
                         <select class="form-control" name="supplier_status">
                             <option value="0"></option>
                             <?php $response_data = $helper_model->get_list_status("master-code");
                                foreach ($response_data as $obj) { ?>
                                 <option value="<?php echo $obj['status_id']; ?>"
                                     <?php if ($obj["status_id"] == $response["status_id"]) echo "selected"; ?>><?php echo $obj['status_name']; ?></option>
                             <?php } ?>
                         </select>
                     </td>
                 </tr>
                 <tr>
                     <td>Supplier Contact</td>
                     <td>
                         <input type="text" class="form-control" name="supplier_contact" value="<?php echo $response['supplier_contact']; ?>">
                     </td>
                 </tr>
                 <tr>
                     <td></td>
                     <td><button class="btn btn-primary">Submit</button></td>
                 </tr>
             </form>
         </table>
     </div>
 </div>
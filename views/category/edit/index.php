 <?php
    $category_code = $_GET['category_code'];
    $response = $category_model->get_category_by_code($category_code);
    ?>

 <a href="index.php?route=category" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Back </a>
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
             <form action="service/categoryService.php?edit_category=edit" method="POST">
                 <tr>
                     <td>Category Code</td>
                     <td>
                         <input type="text" readonly="readonly" class="form-control" name="category_code" value="<?php echo $response['category_code']; ?>">
                     </td>
                 </tr>
                 <tr>
                     <td>Category Name</td>
                     <td>
                         <input type="text" class="form-control" name="category_name" value="<?php echo $response['category_name']; ?>">
                     </td>
                 </tr>
                 <tr>
                     <td>Category Description</td>
                     <td>
                         <input type="text" class="form-control" name="category_desc" value="<?php echo $response['category_desc']; ?>">
                     </td>
                 </tr>
                 <tr>
                     <td>Category Status</td>
                     <td>
                         <select class="form-control select2" name="category_status">
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
                     <td></td>
                     <td><button class="btn btn-primary">Submit</button></td>
                 </tr>
             </form>
         </table>
     </div>
 </div>
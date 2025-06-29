   <?php
    $product_code = $_GET['product_code'];
    $response = $product_model->get_product_by_code($product_code);
    ?>

   <a href="index.php?route=product" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Back </a>
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
           <!--  -->
           <form action="service/productService.php?edit_product=edit_product" method="post">
               <table class="table table-borderless">
                   <tr>
                       <td>Product Code</td>
                       <td>
                           <input type="text" readonly="readonly" required value="<?php echo $response['product_code']; ?>" class="form-control" name="product_code">
                       </td>
                   </tr>
                   <tr>
                       <td>Product Name</td>
                       <td>
                           <input type="text" required class="form-control" value="<?php echo $response['product_name']; ?>" name="product_name">
                       </td>
                   </tr>
                   <tr>
                       <td>Category</td>
                       <td>
                           <select class="form-control select2" required name="product_category">
                               <option value=""></option>
                               <?php $response_data = $category_model->get_category_active();
                                foreach ($response_data as $obj) {     ?>
                                   <option value="<?php echo $obj['category_id']; ?>"
                                       <?php if ($obj["category_id"] == $response["category_id"]) echo "selected"; ?>><?php echo $obj['category_name']; ?></option>
                               <?php } ?>
                           </select>
                       </td>
                   </tr>
                   <tr>
                       <td>Supplier</td>
                       <td>
                           <select class="form-control select2" required name="product_supplier">
                               <option value=""></option>
                               <?php $response_data = $supplier_model->get_supplier_active();
                                foreach ($response_data as $obj) {     ?>
                                   <option value="<?php echo $obj['supplier_id']; ?>"
                                       <?php if ($obj["supplier_id"] == $response["supplier_id"]) echo "selected"; ?>><?php echo $obj['supplier_name']; ?></option>
                               <?php } ?>
                           </select>
                       </td>
                   </tr>
                   <tr>
                       <td>Brand</td>
                       <td>
                           <select class="form-control select2" required name="product_brand">
                               <option value=""></option>
                               <?php $response_data = $brand_model->get_brand_active();
                                foreach ($response_data as $obj) {     ?>
                                   <option value="<?php echo $obj['brand_id']; ?>"
                                       <?php if ($obj["brand_id"] == $response["brand_id"]) echo "selected"; ?>><?php echo $obj['brand_name']; ?></option>
                               <?php } ?>
                           </select>
                       </td>
                   </tr>
                   <tr>
                       <td>Product Qty</td>
                       <td>
                           <input type="number" class="form-control" name="product_qty" value="<?php echo $response['product_qty']; ?>" id="">
                       </td>
                   </tr>
                   <tr>
                       <td>Product Price</td>
                       <td>
                           <input type="text" class="form-control" name="product_price" value="<?php echo $response['product_price']; ?>">
                       </td>
                   </tr>
                   <tr>
                       <td>Product Status</td>
                       <td>
                           <select class="form-control select2" required name="product_status">
                               <option value=""></option>
                               <?php $response_data = $helper_model->get_list_status("master-code");
                                foreach ($response_data as $obj) {     ?>
                                   <option value="<?php echo $obj['status_id']; ?>"
                                       <?php if ($obj["status_id"] == $response["product_status"]) echo "selected"; ?>><?php echo $obj['status_name']; ?></option>
                               <?php } ?>
                           </select>
                       </td>
                   </tr>
               </table>
               <a href="index.php?route=product" class="btn btn-secondary mb-3"><i class="fa fa-angle-left"></i> Close </a>
               <button type="submit" class="btn btn-primary  mb-3">Submit</button>
           </form>
       </div>
   </div>
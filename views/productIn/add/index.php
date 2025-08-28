   <div class="card card-body">
       <div class="table-responsive">
           <!--  -->
           <form action="service/productService.php?add_product=add_product" method="post">
               <table class="table table-borderless">
                   <?php
                    $format = $helper_model->generate_code("PRD");
                    ?>
                   <tr>
                       <td>Product Code</td>
                       <td>
                           <input type="text" readonly="readonly" required value="<?php echo $format; ?>" class="form-control" name="product_code">
                       </td>
                   </tr>
                   <tr>
                       <td>Product Name</td>
                       <td>
                           <input type="text" required class="form-control" name="product_name">
                       </td>
                   </tr>
                   <tr>
                       <td>Category</td>
                       <td>
                           <select class="form-control select2" required name="product_category">
                               <option value=""></option>
                               <?php $response_data = $category_model->get_category_active();
                                foreach ($response_data as $obj) {     ?>
                                   <option value="<?php echo $obj['category_id']; ?>"><?php echo $obj['category_name']; ?></option>
                               <?php } ?>
                           </select>
                       </td>
                   </tr>

                   <tr>
                       <td>Type</td>
                       <td>
                           <select class="form-control select2" required name="product_type">
                               <option value=""></option>
                               <?php $response_data = $type_model->get_type();
                                foreach ($response_data as $obj) {     ?>
                                   <option value="<?php echo $obj['type_id']; ?>"><?php echo $obj['type_name']; ?></option>
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
                                   <option value="<?php echo $obj['brand_id']; ?>"><?php echo $obj['brand_name']; ?></option>
                               <?php } ?>
                           </select>
                       </td>
                   </tr>
                   <tr>
                       <td>Qty</td>
                       <td>
                           <input type="number" class="form-control" name="product_qty" id="">
                       </td>
                   </tr>
                   <tr>
                       <td>Purchase Price </td>
                       <td>
                           <input type="text" class="form-control" name="purchase_price" id="">
                       </td>
                   </tr>
                   <tr>
                       <td>Selling Price </td>
                       <td>
                           <input type="text" class="form-control" name="selling_price" id="">
                       </td>
                   </tr>
                   <tr>
                       <td>Supplier</td>
                       <td>
                           <select class="form-control select2" required name="product_supplier">
                               <option value=""></option>
                               <?php $response_data = $supplier_model->get_supplier_active();
                                foreach ($response_data as $obj) {     ?>
                                   <option value="<?php echo $obj['supplier_id']; ?>"><?php echo $obj['supplier_name']; ?></option>
                               <?php } ?>
                           </select>
                       </td>
                   </tr>
                   <tr>
                       <td>Product Status</td>
                       <td>
                           <select class="form-control select2" required name="product_status">
                               <option value=""></option>
                               <?php $response_data = $helper_model->get_list_status("master-code");
                                foreach ($response_data as $obj) {     ?>
                                   <option value="<?php echo $obj['status_id']; ?>"><?php echo $obj['status_name']; ?></option>
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
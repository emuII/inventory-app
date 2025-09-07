   <?php
    $out_id = $_GET['out_id'];
    $response = $product_out->get_product_sales_by_id($out_id);
    ?>


   <a href="index.php?route=productOut" class="btn btn-primary mb-3"><i class="fa fa-angle-left"></i> Back </a>
   <h4>Product Sales</h4>
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
           <form action="service/productOutService.php?edit_sales=edit_product" method="post">
               <table class="table table-borderless">
                   <tr>
                       <td>Product Name</td>
                       <td>
                           <input type="text" disabled class="form-control" value="<?php echo $response['product_name']; ?>" name="product_name">
                       </td>
                   </tr>
                   <tr>
                       <td style="max-width: 40px;">Category</td>
                       <td>
                           <input type="text" class="form-control" id="category" value="<?php echo $response['category_name'] ?>" disabled>
                       </td>
                   </tr>
                   <tr>
                       <td style="max-width: 40px;">Type</td>
                       <td><input type="text" class="form-control" id="type" value="<?php echo $response['type_name'] ?>" disabled></td>
                   </tr>
                   <tr>
                       <td style="max-width: 40px;">Brand</td>
                       <td><input type="text" class="form-control" id="brand" value="<?php echo $response['brand_name'] ?>" disabled></td>
                   </tr>
                   <tr>
                       <td style="max-width: 40px;">Available Qty</td>
                       <td>
                           <input type="text" class="form-control hd_qty" id="available_qty" value="<?php echo $response['product_qty'] ?>" name="qty_av" disabled>
                       </td>
                   </tr>
                   <tr>
                       <td style="max-width: 40px;">Qty</td>
                       <td><input type="text" required name="out_qty" value="<?php echo $response['qty_out'] ?>" class="form-control"></td>
                   </tr>
                   <tr>
                       <td style="max-width: 40px;">Selling Price</td>
                       <td><input type="text" required name="selling_price" value="<?php echo $response['selling_price'] ?>" id="selling_price" class="form-control"></td>
                   </tr>
                   <tr>
                       <td style="max-width: 40px;">Note</td>
                       <td>
                           <textarea class="form-control" name="notes" id=""><?php echo $response['note'] ?> </textarea>
                           <input type="text" value="<?php echo $response['out_id'] ?>" name="out_id" hidden>
                       </td>
                   </tr>
               </table>
               <a href="index.php?route=productOut" class="btn btn-secondary mb-3"><i class="fa fa-angle-left"></i> Close </a>
               <button type="submit" class="btn btn-primary  mb-3">Submit</button>
           </form>
       </div>
   </div>
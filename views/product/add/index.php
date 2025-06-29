   <div class="card card-body">
       <div class="table-responsive">
           <form action="" method="post">
               <table class="table table-borderless">
                   <?php
                    $format = $helper_model->generate_code("PRD");
                    ?>
                   <tr>
                       <td>Product Code</td>
                       <td>
                           <input type="text" readonly="readonly" required value="<?php echo $format; ?>" class="form-control" name="prod">
                       </td>
                   </tr>
                   <tr>
                       <td>Product Name</td>
                       <td>
                           <input type="text" required class="form-control" name="supplier_code">
                       </td>
                   </tr>
                   <tr>
                       <td>Supplier Status</td>
                       <td>
                           <select class="form-control select2" name="supplier_status">
                               <option value="0"></option>
                               <?php $response_data = $helper_model->get_list_status("master-code");
                                foreach ($response_data as $obj) {     ?>
                                   <option value="<?php echo $obj['status_id']; ?>"><?php echo $obj['status_name']; ?></option>
                               <?php } ?>
                           </select>
                       </td>
                   </tr>
               </table>
               <button type="submit" class="btn btn-primary">d</button>
           </form>
       </div>
   </div>
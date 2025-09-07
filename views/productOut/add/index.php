   <div class="card card-body">
       <div class="table-responsive">
           <form action="service/productOutService.php?add_out=add_out" id="outForm" method="post">
               <table class="table table-borderless">
                   <tr>
                       <td style="max-width: 40px;">Product Name</td>
                       <td>
                           <select class="form-control select2" required id="select_list" name="product_list" onchange="productSelected();">
                               <option value=""></option>
                               <?php $response_data = $product_model->get_all_product();

                                foreach ($response_data as $obj) {     ?>
                                   <option value="<?php echo $obj['product_id']; ?>" data-id="<?php echo $obj["product_code"] ?>">
                                       <?php echo $obj['product_name']; ?></option>
                               <?php } ?>
                           </select>
                       </td>
                   </tr>
                   <tr>
                       <td style="max-width: 40px;">Category</td>
                       <td>
                           <input type="text" class="form-control" id="prod_id" name="prod_id" hidden>
                           <input type="text" class="form-control" id="category" disabled>
                       </td>
                   </tr>
                   <tr>
                       <td style="max-width: 40px;">Type</td>
                       <td><input type="text" class="form-control" id="type" disabled></td>
                   </tr>
                   <tr>
                       <td style="max-width: 40px;">Brand</td>
                       <td><input type="text" class="form-control" id="brand" disabled></td>
                   </tr>
                   <tr>
                       <td style="max-width: 40px;">Available Qty</td>
                       <td><input type="text" class="form-control hd_qty" id="hide_qty" name="qty_av" hidden>
                           <input type="text" class="form-control hd_qty" id="available_qty" name="qty_av" disabled>
                       </td>
                   </tr>
                   <tr>
                       <td style="max-width: 40px;">Qty</td>
                       <td><input type="text" required name="out_qty" class="form-control"></td>
                   </tr>
                   <tr>
                       <td style="max-width: 40px;">Selling Price</td>
                       <td><input type="text" required name="selling_price" id="selling_price" class="form-control"></td>
                   </tr>
                   <tr>
                       <td style="max-width: 40px;">Note</td>
                       <td><textarea class="form-control" name="notes" id=""></textarea></td>
                   </tr>
               </table>
               <a href="index.php?route=productOut" class="btn btn-secondary mb-3"><i class="fa fa-angle-left"></i> Close </a>
               <button type="submit" class="btn btn-primary  mb-3">Submit</button>
           </form>
       </div>
   </div>

   <script>
       $('#outForm').on('submit', function(e) {
           e.preventDefault(); // cegah reload
           $.ajax({
               type: 'POST',
               url: $(this).attr('action'),
               data: $(this).serialize(),
               dataType: 'json',
               success: function(res) {
                   if (res && res.status === 'ok') {
                       Swal.fire(
                           'Success!',
                           'Transaksi keluar tersimpan',
                           'success',
                           'Ok'
                       ).then((data) => {
                           window.location = "index.php?route=productOut"
                       })
                   } else {
                       alert(res?.message || 'Gagal: format respons tidak sesuai');
                   }
               },
               error: function(xhr) {
                   try {
                       const clean = xhr.responseText.trim().replace(/^.*?(\{)/s, '$1');
                       const res = JSON.parse(clean);
                       alert(res.message || 'Gagal simpan data');
                   } catch (e) {
                       console.error(xhr.responseText);
                       Swal.fire(
                           'Failed!',
                           'Data gagal di simpan',
                           'failed',
                           'Ok'
                       ).then((data) => {
                           window.location = "../index.php?route=productOut"
                       })
                   }
               }
           });
       });

       function productSelected() {
           let code = $("#select_list option:selected").attr("data-id");
           productDetail(code);
       }

       function productDetail(param) {
           let product_code = param;
           $.ajax({
               type: 'POST',
               url: 'middleware/ajax_handler.php?controller=productin&action=get_product_code',
               data: {
                   product_code: product_code
               },
               dataType: 'json',
               success: function(response) {
                   $("#prod_id").val(response.product_id);
                   $("#category").val(response.category_name);
                   $("#type").val(response.type_name);
                   $("#brand").val(response.brand_name);
                   $(".hd_qty").val(response.product_qty);
                   $("#selling_price").val(response.selling_price);
               },
               error: function(err) {
                   alert("Error loading data");
               }
           });
       }
   </script>
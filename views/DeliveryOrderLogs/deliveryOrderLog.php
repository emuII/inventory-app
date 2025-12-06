 <div class="container-fluid py-4">
     <div class="page-header">
         <div class="d-flex justify-content-between align-items-center">
             <div>
                 <h1 class="h3 mb-0">Delivery Order Details</h1>
                 <p class="mb-0 opacity-75">Manage your order information and details</p>
             </div>
         </div>
     </div>
     <div class="search-section">
         <div class="table-responsive">
             <table class="table dt-tbl table-modern" id="example1">
                 <thead>
                     <tr>
                         <th>No.</th>
                         <th>Item Name</th>
                         <th>Quantity Order</th>
                         <th>Unit Price</th>
                         <th>Subtotal</th>
                     </tr>
                 </thead>
                 <tbody>
                     <?php
                        if (!empty($logDetail)) {
                            foreach ($logDetail as $index => $row) {
                        ?>
                             <tr>
                                 <td><?= htmlspecialchars($index + 1) ?></td>
                                 <td><?= htmlspecialchars($row['itemName']) ?></td>
                                 <td><?= htmlspecialchars($row['qtyOrder']) ?></td>
                                 <td><?= htmlspecialchars($row['salesPrice']) ?></td>
                                 <td><?= htmlspecialchars($row['subTotal']) ?></td>
                             </tr>
                         <?php
                            }
                        } else {
                            ?>
                         <tr>
                             <td colspan="6" class="text-center">No items found for this request.</td>
                         </tr>
                     <?php } ?>
                 </tbody>
             </table>
         </div>
         <div class="d-flex gap-2">
             <button class="btn btn-outline-secondary btn-modern" type="button" onclick="history.back()">
                 <i class="fa-solid fa-angle-left"></i> Back
             </button>
         </div>
     </div>
 </div>
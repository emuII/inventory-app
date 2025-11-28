 <div class="container-fluid py-4">
     <div class="page-header">
         <div class="d-flex justify-content-between align-items-center">
             <div>
                 <h1 class="h3 mb-0">Preview Request</h1>
                 <p class="mb-0 opacity-75">Manage your request information and details</p>
             </div>
             <button type="button" class="btn btn-sm btn-outline-info" onclick="history.back()">
                 <i class="fas fa-times me-1"></i> Close
             </button>
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
                         <th>Supplier Name</th>
                         <th>Approver Name</th>
                         <th>Status</th>
                         <th>Notes</th>
                     </tr>
                 </thead>
                 <tbody>
                     <?php
                        if (!empty($dataDetail)) {
                            foreach ($dataDetail as $index => $row) {
                        ?>
                             <tr>
                                 <td><?= htmlspecialchars($index + 1) ?></td>
                                 <td><?= htmlspecialchars($row['itemName']) ?></td>
                                 <td><?= htmlspecialchars($row['qty']) ?></td>
                                 <td><?= htmlspecialchars($dataHeader['supplierName']) ?></td>
                                 <td><?= htmlspecialchars($dataHeader['approverName']) ?></td>
                                 <td><label class="status-badge <?= htmlspecialchars($dataHeader['statusName']) ?>"><?= htmlspecialchars($dataHeader['statusName']) ?></label></td>
                                 <td><?= htmlspecialchars($row['Notes'] ?? '') ?></td>
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
     </div>
 </div>
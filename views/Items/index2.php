<div class="card card-body">
    <div class="table-responsive">
        <form action="import.php" method="post" enctype="multipart/form-data">
            <table class="table table-borderless" id="prTable">
                <!-- tombol aksi -->
                <thead>
                    <tr>
                        <td colspan="2" class="text-end">
                            <button type="button" class="btn btn-sm btn-success" id="btnAddItem">
                                + Add Item
                            </button>
                        </td>
                    </tr>
                </thead>

                <!-- BLOCK ITEM (TEMPLATE) -->
                <tbody class="item-block" data-index="0">
                    <tr>
                        <td colspan="2" class="fw-semibold">
                            Item #<span class="item-number">1</span>
                            <button type="button" class="btn btn-sm btn-outline-danger float-end btnRemoveItem">Remove</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Items</td>
                        <td>
                            <select class="form-control select2" name="select_item[0]" required>
                                <option value="">-- Choose Item --</option>
                                <?php
                                $response_data = $item_model->itemList();
                                if (!empty($response_data)) {
                                    foreach ($response_data as $obj) { ?>
                                        <option value="<?= htmlspecialchars($obj['Id']) ?>">
                                            <?= htmlspecialchars($obj['item_name']) ?>
                                        </option>
                                    <?php }
                                } else { ?>
                                    <option value="">Data item tidak tersedia</option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Supplier</td>
                        <td>
                            <select class="form-control select2" name="supplier_code[0]" required>
                                <option value="">-- Choose Supplier --</option>
                                <?php
                                $response_data = $supplier_model->get_supplier_active();
                                if (!empty($response_data)) {
                                    foreach ($response_data as $obj) { ?>
                                        <option value="<?= htmlspecialchars($obj['supplier_code']) ?>">
                                            <?= htmlspecialchars($obj['supplier_code'] . ' - ' . $obj['supplier_name']) ?>
                                        </option>
                                    <?php }
                                } else { ?>
                                    <option value="">Tidak ada data supplier</option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Qty</td>
                        <td><input type="number" min="1" class="form-control" name="qty[0]" required></td>
                    </tr>
                    <tr>
                        <td>Request Date</td>
                        <td>
                            <input class="form-control datepicker" type="text" name="request_date[0]" autocomplete="off" required>
                        </td>
                    </tr>
                    <tr>
                        <td>Sale Price</td>
                        <td><input type="number" min="0" step="0.01" class="form-control" name="sale_price[0]"></td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td><textarea class="form-control" name="address[0]" rows="2"></textarea></td>
                    </tr>
                    <tr>
                        <td>Approver</td>
                        <td>
                            <select class="form-control select2" name="approver[0]" required>
                                <option value="">-- Choose Approver --</option>
                                <!-- isi opsi approver di sini -->
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <hr>
                        </td>
                    </tr>
                </tbody>
                <!-- END BLOCK ITEM -->

                <tfoot>
                    <tr>
                        <td>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="history.back()">Close</button>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-primary" type="submit">Submit</button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </form>
    </div>
</div>

<script>
    // init awal
    function initPlugins(ctx) {
        $(ctx).find('.select2').select2();
        $(ctx).find('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    }
    $(document).ready(function() {
        initPlugins(document);

        // Add item
        $('#btnAddItem').on('click', function() {
            const $last = $('#prTable .item-block').last();
            const newIndex = parseInt($last.data('index'), 10) + 1;

            const $clone = $last.clone(true, true);
            $clone.attr('data-index', newIndex);

            // reset nilai input/select/textarea
            $clone.find('input').val('');
            $clone.find('textarea').val('');
            $clone.find('select').val('').trigger('change');

            // update nomor tampilan
            $clone.find('.item-number').text(newIndex + 1);

            // update name attributes ke index baru
            $clone.find('[name]').each(function() {
                const oldName = $(this).attr('name');
                const updated = oldName.replace(/\[\d+\]/, '[' + newIndex + ']');
                $(this).attr('name', updated);
            });

            // buang instance select2 lama lalu init ulang pada clone
            $clone.find('.select2').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) {
                    $(this).select2('destroy');
                }
            });

            // append ke table, lalu inisialisasi plugin
            $clone.insertAfter($last);
            initPlugins($clone);
        });

        // Remove item (delegasi)
        $('#prTable').on('click', '.btnRemoveItem', function() {
            const blocks = $('#prTable .item-block');
            if (blocks.length === 1) {
                // minimal satu blok tetap ada, cukup reset nilainya
                const $blk = blocks.first();
                $blk.find('input').val('');
                $blk.find('textarea').val('');
                $blk.find('select').val('').trigger('change');
                return;
            }
            $(this).closest('.item-block').remove();

            // re-index ulang semua block setelah remove agar urut
            $('#prTable .item-block').each(function(i) {
                $(this).attr('data-index', i);
                $(this).find('.item-number').text(i + 1);
                $(this).find('[name]').each(function() {
                    const name = $(this).attr('name');
                    const newName = name.replace(/\[\d+\]/, '[' + i + ']');
                    $(this).attr('name', newName);
                });
            });
        });
    });
</script>
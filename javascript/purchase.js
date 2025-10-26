function addRow() {
  const tpl = `
                    <div class="card card-body item-row">
                        <div class="table-responsive">
                        <table class="table table-borderles tbl-item">
                            <tbody>
                            <tr>
                                <td>Items</td>
                                <td>
                                <select class="form-control select2 select-item" name="select_item[]" required>
                                    <option value="">-- Choose Item --</option>
                                </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Supplier</td>
                                <td>
                                <select class="form-control select2 select-supplier" name="supplier_code[]" required>
                                    <option value="">-- Choose Supplier --</option>
                                </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Qty</td>
                                <td><input type="text" class="form-control" name="qty[]" required></td>
                            </tr>
                            <tr>
                                <td>Sale Price</td>
                                <td><input type="text" class="form-control" name="price[]" required></td>
                            </tr>
                             <tr>
                                <td>Notes</td>
                                <td>
                                    <textarea class="form-control" name="notes[]" style="resize: none; height: 100px;"></textarea>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                    <br>`;
  const $row = $(tpl).appendTo("#itemContainer");

  initSupplierSelect2($row);
  initItemSelect2($row);
}

function removeRow() {
  let rows = $("#itemContainer .item-row");
  if (rows.length > 1) {
    rows.last().remove();
  } else {
    alert("Minimal satu item harus ada.");
  }
}

function initSupplierSelect2(ctx) {
  const $scope = $(ctx || document);
  const $parent = $(".modal:visible").length
    ? $(".modal:visible")
    : $(document.body);

  $scope.find(".select-supplier").each(function () {
    const $el = $(this);
    if ($el.data("select2")) $el.select2("destroy");

    $el.select2({
      placeholder: "-- Choose Supplier --",
      dropdownParent: $parent,
      minimumInputLength: 0,
      language: {
        inputTooShort: () => "",
      },
      ajax: {
        url: "middleware/ajax_handler.php?controller=supplier&action=GetSupplierEncode",
        dataType: "json",
        delay: 0,
        data: (params) => ({
          q: params.term || "",
          page: params.page || 1,
        }),
        processResults: (data) => data,
        cache: true,
      },
    });

    $el.on("select2:open", function () {
      const $search = $(".select2-container--open .select2-search__field");
      $search.val(" ").trigger("input");
      setTimeout(() => {
        $search.val("").trigger("input");
      }, 0);
    });
  });
}

function initItemSelect2(ctx) {
  const $scope = $(ctx || document);
  const $parent = $(".modal:visible").length
    ? $(".modal:visible")
    : $(document.body);

  $scope.find(".select-item").each(function () {
    if ($(this).data("select2")) $(this).select2("destroy");
    $(this).select2({
      placeholder: "-- Choose Item --",
      minimumInputLength: 0,
      dropdownParent: $parent,
      ajax: {
        url: "middleware/ajax_handler.php?controller=item&action=GetItemEncode", // sesuaikan
        dataType: "json",
        delay: 300,
        data: (params) => ({
          q: params.term || "",
          page: params.page || 1,
        }),
        processResults: (data) => data,
      },
    });
  });
}

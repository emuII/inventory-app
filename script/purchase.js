$(document).ready(function () {
  $("#prForm").on("submit", function (e) {
    e.preventDefault();
    submitRequest();
  });

  // init untuk row awal
  initItemSelect2();
});

function submitRequest() {
  const body = $(".container-section .search-section");
  let items = [];
  let requestDate = $("#request_date").val();
  let storeAddress = $("#address").val();
  let selApprover = $("#sel_approver").val();
  let approverName = $("#sel_approver :selected")
    .text()
    .replace(/\s+/g, " ")
    .trim();
  let remarksApprover = $("#remarks_approver").val();
  let supplierId = $("#sel_supplier").val();

  if (!supplierId) {
    alert("Supplier harus dipilih.");
    return false;
  }

  if (!selApprover) {
    alert("Approver harus dipilih.");
    return false;
  }

  if (!requestDate) {
    alert("Request date harus diisi.");
    return false;
  }

  let isValid = true;

  body.each(function (index, el) {
    const $item = $(el);
    let itemId = $item.find("select[name='select_item']").val();
    let qty = $item.find("input[name='qty']").val();
    let notes = $item.find("textarea[name='notes']").val();

    if (!itemId || !qty) {
      alert(`Item ${index + 1} harus diisi dengan lengkap.`);
      isValid = false;
      return false; // break .each
    }

    items.push({
      itemId: parseInt(itemId, 10),
      qty: parseInt(qty, 10),
      notes: notes || "",
    });
  });

  if (!isValid) return false;

  let dto = {
    supplierId: parseInt(supplierId, 10),
    requestDate: requestDate,
    storeAddress: storeAddress,
    statusRequest: 1,
    selApprover: parseInt(selApprover, 10),
    approverName: approverName,
    remarksApprover: remarksApprover,
    itemDetails: items,
  };

  $.ajax({
    url: "middleware/ajax_handler.php?controller=purchaseRequest&action=store",
    type: "POST",
    contentType: "application/json",
    data: JSON.stringify(dto),
    success: function (response) {
      console.log(response);
      Swal.fire({
        title: "Success!",
        text: "Purchase request created successfully.",
        icon: "success",
      }).then(() => {
        window.location = "/inventory-app/index.php?route=myrequest";
      });
    },
    error: function (xhr) {
      console.error(xhr.responseText);
    },
  });
}

function addRow() {
  const tpl = `
    <div class="search-section">
        <div class="filter-row">
            <div class="filter-group">
                <label class="filter-label">Product Name</label>
                <select class="form-control select2 select-item" name="select_item" required>
                    <option value="">-- Choose Item --</option>
                </select>
            </div>
        </div>
        <div class="filter-row">
            <div class="filter-group">
                <label class="filter-label">Quantity</label>
                <input type="text" class="form-control" name="qty" required>
            </div>
        </div>
        <div class="filter-row">
            <div class="filter-group">
                <label class="filter-label">Notes Product</label>
                <textarea class="form-control" name="notes" style="resize: none; height: 100px;"></textarea>
            </div>
        </div>
    </div>
    <br>
  `;

  const $row = $(tpl).appendTo(".container-section");

  // init select2 hanya di row baru
  initItemSelect2($row);
}

function removeRow() {
  let sections = $(".container-section .search-section");
  if (sections.length > 1) {
    sections.last().next("br").remove();
    sections.last().remove();
  } else {
    alert("Minimal satu item harus ada.");
  }
}
function initItemSelect2(ctx) {
  const $scope = $(ctx || document);

  $scope.find("select.select-item").each(function () {
    const $select = $(this);

    if ($select.data("select2")) {
      $select.select2("destroy");
    }

    const $parent =
      $select.closest(".modal").length > 0
        ? $select.closest(".modal")
        : $(document.body);

    $select.select2({
      placeholder: "-- Choose Item --",
      minimumInputLength: 0,
      dropdownParent: $parent,
      ajax: {
        url: "middleware/ajax_handler.php?controller=item&action=GetItemEncode",
        dataType: "json",
        delay: 300,
        data: function (params) {
          return {
            q: params.term || "",
            page: params.page || 1,
          };
        },
        processResults: function (data) {
          return {
            results: data.results,
          };
        },
      },
    });
  });
}

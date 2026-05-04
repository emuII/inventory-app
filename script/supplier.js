$(document).ready(function () {
  searchSupplier();
});

function searchSupplier() {
  $("#tbSupplier").DataTable({
    destroy: true,
    ajax: {
      url: "middleware/ajax_handler.php?controller=supplier&action=getSupplierList",
      type: "POST",
      data: function () {
        return $("#searchForm").serializeArray();
      },
      dataSrc: "result",
    },
    columns: [
      {
        data: null,
        render: function (data, type, row, meta) {
          return meta.row + 1;
        },
      },
      { data: "supplier_code" },
      { data: "supplier_name" },
      { data: "supplier_address" },
      {
        data: "status_name",
        render: function (data) {
          return `<label class="status-badge ${data}">${data}</label>`;
        },
      },
      { data: "supplier_contact" },
      {
        data: null,
        render: function (data, type, row) {
          let btn = "";
          btn += `<a class='btn btn-sm btn-outline-primary action-btn' 
                    href='index.php?route=supplier/SupplierDetail&supplierCode=${row.supplier_code}' 
                    class='btn btn-sm btn-primary'><i class='fa fa-edit'></i></a>`;

          btn += `<a class="btn btn-sm btn-outline-danger action-btn"
                       onclick="deleteSupplier('${row.supplier_code}')"
                       title="Cancel Request">
                       <i class="fa-solid fa-trash"></i>
                   </a>`;

          return btn;
        },
      },
    ],
  });
}

function clearSupplier() {
  $("#searchForm")[0].reset();
  $("#searchForm select.select2").val("").trigger("change");
  searchSupplier();
}

function deleteSupplier(supplierCode) {
  Swal.fire({
    title: "Are you sure?",
    text: "This supplier will be deleted!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "Yes!",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        type: "POST",
        url: "middleware/ajax_handler.php?controller=supplier&action=deleteSupplier",
        data: {
          supplierCode: supplierCode,
        },
        success: function (response) {
          Swal.fire({
            title: "Success!",
            text: "Supplier deleted successfully.",
            icon: "success",
            timer: 1500,
            showConfirmButton: false,
          });

          searchSupplier();
        },
        error: function () {
          Swal.fire({
            title: "Failed!",
            text: "Error cancelling request.",
            icon: "error",
          });
        },
      });
    }
  });
}

function createSupplier() {
  var dto = {
    supplierCode: $('input[name="supplier_code"]').val(),
    supplierName: $('input[name="supplier_name"]').val(),
    supplierAddress: $('textarea[name="supplier_address"]').val(),
    supplierContact: $('input[name="supplier_contact"]').val(),
    supplierStatus: parseInt($('select[name="supplier_status"]').val()),
  };
  $.ajax({
    url: "middleware/ajax_handler.php?controller=Supplier&action=createSupplier",
    type: "POST",
    data: JSON.stringify(dto),
    success: function (response) {
      Swal.fire({
        title: "Success!",
        text: "Supplier edit successfully.",
        icon: "success",
      }).then(() => {
        window.location = "/inventory-app/index.php?route=suppliers";
      });
    },
    error: function () {
      alert("An error occurred while updating the supplier.");
    },
  });
}

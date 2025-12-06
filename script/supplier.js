$(document).ready(function () {
  searchSupplier();
});

function searchSupplier() {
  $.ajax({
    type: "POST",
    url: "middleware/ajax_handler.php?controller=supplier&action=supplier_list",
    data: $("#searchForm").serialize(),
    success: function (response) {
      if ($.fn.DataTable.isDataTable("#tbSupplier")) {
        $("#tbSupplier").DataTable().destroy();
      }

      $("#supplierTable").html(response);
      $("#tbSupplier").DataTable();
    },
    error: function (err) {
      alert("Error loading data");
    },
  });
}

function celarSupplier() {
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

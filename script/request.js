$(document).ready(function () {
  searchRequest();
});

function searchRequest() {
  $.ajax({
    type: "POST",
    url: "middleware/ajax_handler.php?controller=purchaseRequest&action=requestList",
    data: $("#searchForm").serialize(),
    success: function (response) {
      if ($.fn.DataTable.isDataTable("#tbRequest")) {
        $("#tbRequest").DataTable().destroy();
      }
      $("#requestTable").html(response);
      $("#tbRequest").DataTable();
    },
    error: function (err) {
      alert("Error loading data");
    },
  });
}

function clearRequest() {
  $("#searchForm")[0].reset();
  $("#searchForm select.select2").val("").trigger("change");
  $("#searchForm select.select2").val("0").trigger("change");
  searchRequest();
}

function cancelRequest(requestNumber) {
  Swal.fire({
    title: "Are you sure?",
    text: "This request will be cancelled!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "Yes, cancel it!",
    cancelButtonText: "No",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        type: "POST",
        url: "middleware/ajax_handler.php?controller=purchaseRequest&action=cancelRequest",
        data: {
          requestNumber: requestNumber,
        },
        success: function (response) {
          Swal.fire({
            title: "Success!",
            text: "Request cancelled successfully.",
            icon: "success",
            timer: 1500,
            showConfirmButton: false,
          });

          searchRequest();
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

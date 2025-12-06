$(document).ready(function () {
  searchDeliveryOrder();
});

function searchDeliveryOrder() {
  $.ajax({
    type: "POST",
    url: "middleware/ajax_handler.php?controller=deliveryOrderLog&action=fetchLogs",
    data: $("#searchForm").serialize(),
    success: function (response) {
      if ($.fn.DataTable.isDataTable("#tableDeliveryOrderLogs")) {
        $("#tableDeliveryOrderLogs").DataTable().destroy();
      }

      $("#deliveryOrderLogs").html(response);
      $("#tableDeliveryOrderLogs").DataTable();
    },
    error: function (err) {
      alert("Error loading data");
    },
  });
}

function clearRequest() {
  $("#searchForm")[0].reset();
  searchDeliveryOrder();
}

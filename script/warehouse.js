$(document).ready(function () {
  searchWareHouse();
});

function searchWareHouse() {
  $.ajax({
    type: "POST",
    url: "middleware/ajax_handler.php?controller=wareHouse&action=warehouseList",
    data: $("#searchForm").serialize(),
    success: function (response) {
      if ($.fn.DataTable.isDataTable("#tbWareHouse")) {
        $("#tbWareHouse").DataTable().destroy();
      }
      $("#wareHouseTable").html(response);
      $("#tbWareHouse").DataTable();
    },
    error: function (err) {
      alert("Error loading data");
    },
  });
}

function clearWarehouse() {
  $("#searchForm")[0].reset();
  $("#searchForm select.select2").val("").trigger("change");
  $("#searchForm select.select2").val("0").trigger("change");
  searchWareHouse();
}

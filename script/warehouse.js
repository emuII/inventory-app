$(document).ready(function () {
  searchWareHouse();
});

function searchWareHouse() {
  $("#tbWareHouse").DataTable({
    destroy: true,
    ajax: {
      url: "middleware/ajax_handler.php?controller=wareHouse&action=getWarehouseList",
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
      { data: "requestNumber" },
      { data: "orderQty" },
      { data: "receiveQty" },
      { data: "totalAmount" },
      { data: "dateIn" },
      { data: "supplierName" },
      { data: "requestedBy" },
      {
        data: "statusName",
        render: function (data) {
          return `<label class="status-badge ${data}">${data}</label>`;
        },
      },
      {
        data: null,
        render: function (data, type, row) {
          let btn = "";

          btn += `<a class='btn btn-sm btn-outline-primary action-btn' href='index.php?route=warehouse/wareHouseDetail&warehouseId=${row.warehouseId}' class='btn btn-sm btn-primary'><i class='fa fa-edit'></i></a>`;

          return btn;
        },
      },
    ],
  });
}

function clearWarehouse() {
  $("#searchForm")[0].reset();
  $("#searchForm select.select2").val("").trigger("change");
  $("#searchForm select.select2").val("0").trigger("change");
  searchWareHouse();
}

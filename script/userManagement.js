$(document).ready(function () {
  searchUser();
});

function searchUser() {
  $("#tbUser").DataTable({
    destroy: true,
    ajax: {
      url: "middleware/ajax_handler.php?controller=userManagement&action=GetAllUsers",
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
      { data: "username" },
      { data: "full_name" },
      { data: "role" },
      {
        data: "statusName",
        render: function (data) {
          return `<label class="status-badge ${data}">${data}</label>`;
        },
      },
      { data: "email" },
      {
        data: null,
        render: function (data, type, row) {
          let btn = "";

          btn += `<a class='btn btn-sm btn-outline-primary action-btn' href='index.php?route=UserManagement/userUpdate&userId=${row.Id}' class='btn btn-sm btn-primary'><i class='fa fa-edit'></i></a>`;

          return btn;
        },
      },
    ],
  });
}

function celarUser() {
  $("#searchForm")[0].reset();
  $("#searchForm select.select2").val("").trigger("change");
  searchSupplier();
}

function addUser() {
  $("#addUserModal").modal("show");
}

function SubmitAddUser() {
  $("#addUserForm").submit();
}

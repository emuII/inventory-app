$(document).ready(function () {
  searchUser();
});

function searchUser() {
  $.ajax({
    type: "POST",
    url: "middleware/ajax_handler.php?controller=userManagement&action=GetAllUsers",
    data: $("#searchForm").serialize(),
    success: function (response) {
      console.table(response);
      if ($.fn.DataTable.isDataTable("#tbUser")) {
        $("#tbUser").DataTable().destroy();
      }

      $("#userTable").html(response);
      $("#tbUser").DataTable();
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

function addUser() {
  $("#addUserModal").modal("show");
}

function SubmitAddUser() {
  $("#addUserForm").submit();
}

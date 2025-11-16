$(function () {
  $("#arForm").on("submit", function (e) {
    e.preventDefault();
    submitApproval();
  });
});

function submitApproval() {
  let prId = parseInt($("#pr_id").val());
  let approvalStatus = parseInt($("#action_approver :selected").val());
  var dto = {
    prId: prId,
    approvalStatus: approvalStatus,
  };

  $.ajax({
    url: "middleware/ajax_handler.php?controller=approval&action=submitaproval",
    type: "POST",
    contentType: "application/json",
    data: JSON.stringify(dto),
    dataType: "json",
    success: function (response) {
      debugger;
      if (response.success) {
        Swal.fire({
          title: "Success!",
          text: "Submit approval successfully.",
          icon: "success",
        }).then(() => {
          window.location = "/inventory-app/index.php?route=myapproval";
        });
      }
    },
    error: function (xhr) {
      console.error(xhr.responseText);
    },
  });
}

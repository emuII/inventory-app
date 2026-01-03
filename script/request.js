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

function exportPurchaseOrderToExcel() {
  const formData = $("#searchForm").serialize();
  Swal.fire({
    title: "Generating Report...",
    html: "Please wait while we generate the Excel file.",
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  $.ajax({
    type: "POST",
    url: "export/excel/request/report.php",
    data: formData,
    xhrFields: {
      responseType: "blob",
    },
    success: function (data, status, xhr) {
      Swal.close();

      let filename =
        "delivery_order_report_" +
        new Date().toISOString().slice(0, 10).replace(/-/g, "") +
        ".xls";

      const disposition = xhr.getResponseHeader("Content-Disposition");
      if (disposition && disposition.indexOf("attachment") !== -1) {
        const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
        const matches = filenameRegex.exec(disposition);
        if (matches != null && matches[1]) {
          filename = matches[1].replace(/['"]/g, "");
        }
      }

      const blob = new Blob([data], {
        type:
          xhr.getResponseHeader("Content-Type") || "application/vnd.ms-excel",
      });

      const downloadUrl = URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.href = downloadUrl;
      a.download = filename;
      document.body.appendChild(a);
      a.click();

      setTimeout(() => {
        document.body.removeChild(a);
        URL.revokeObjectURL(downloadUrl);
      }, 100);

      Swal.fire({
        icon: "success",
        title: "Export Successful",
        text: "Excel file has been downloaded successfully.",
        timer: 2000,
        showConfirmButton: false,
      });
    },
    error: function (xhr, status, error) {
      Swal.close();

      let errorMessage = "Failed to generate Excel file.";

      if (xhr.responseText) {
        try {
          if (
            xhr.responseText.includes("error") ||
            xhr.responseText.includes("Error")
          ) {
            const tempDiv = document.createElement("div");
            tempDiv.innerHTML = xhr.responseText;
            const errorText = tempDiv.textContent || tempDiv.innerText;
            errorMessage = "Error: " + errorText.substring(0, 200);
          }
        } catch (e) {
          console.error("Error parsing response:", e);
        }
      }

      Swal.fire({
        icon: "error",
        title: "Export Failed",
        text: errorMessage,
        confirmButtonText: "OK",
      });
    },
  });
}

$(document).ready(function () {
  searchDeliveryOrder();
});

function searchDeliveryOrder() {
  $("#tableDeliveryOrderLogs").DataTable({
    destroy: true,
    ajax: {
      url: "middleware/ajax_handler.php?controller=deliveryOrderLog&action=GetLogOrder",
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
      { data: "doCode" },
      { data: "doDate" },
      { data: "totalAmount" },
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

          btn += `<a href='index.php?route=deliveryOrderLog/deliveryOrderLog&doNumber=${row.doCode}' class='btn btn-sm btn-outline-primary action-btn' title='Preview'><i class='fa-solid fa-eye'></i></a>`;
          // $invUrl = base_url('export/pdf/genrateInvoice.php?doNumber=' . $row['doCode']);
          //             echo "<a href='" . $invUrl . "' class='btn btn-sm btn-outline-success action-btn'><i class='fa-solid fa-download'></i></a>";
          btn += `<a href='export/pdf/genrateInvoice.php?doNumber=${row.doCode}' class='btn btn-sm btn-outline-success action-btn'><i class='fa-solid fa-download'></i></a>`;
          return btn;
        },
      },
    ],
  });
}

function clearRequest() {
  $("#searchForm")[0].reset();
  searchDeliveryOrder();
}

function exportDeliveryOrderToExcel() {
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
    url: "export/excel/delivery-order/report.php",
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

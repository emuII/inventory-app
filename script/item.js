$(document).ready(function () {
  searchItem();
});

function searchItem() {
  $.ajax({
    type: "POST",
    url: "middleware/ajax_handler.php?controller=item&action=getItemList",
    data: $("#searchForm").serialize(),
    success: function (response) {
      $("#tableItem").html(response);
      $("#tbItems").DataTable();
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
  searchItem();
}
function updateFileName(input) {
  const fileNameElement = document.getElementById("fileName");
  const submitBtn = document.getElementById("submitBtn");

  if (input.files.length > 0) {
    const fileName = input.files[0].name;
    const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2); // Convert to MB
    if (fileSize > 10) {
      fileNameElement.textContent = `File too large (${fileSize} MB). Max 10MB allowed`;
      fileNameElement.style.color = "#dc3545";
      submitBtn.disabled = true;
      submitBtn.style.opacity = "0.5";
      submitBtn.style.cursor = "not-allowed";
      return;
    }

    // Validasi file extension
    const allowedExtensions = [".xls", ".xlsx"];
    const fileExtension = fileName
      .slice(fileName.lastIndexOf("."))
      .toLowerCase();

    if (!allowedExtensions.includes(fileExtension)) {
      fileNameElement.textContent =
        "Invalid file type. Only .xls and .xlsx files are allowed";
      fileNameElement.style.color = "#dc3545";
      submitBtn.disabled = true;
      submitBtn.style.opacity = "0.5";
      submitBtn.style.cursor = "not-allowed";
      return;
    }

    fileNameElement.textContent = `${fileName} (${fileSize} MB)`;
    fileNameElement.style.color = "#0066cc";
    fileNameElement.style.fontWeight = "500";
    submitBtn.disabled = false;
    submitBtn.style.opacity = "1";
    submitBtn.style.cursor = "pointer";
  } else {
    fileNameElement.textContent = "No file chosen";
    fileNameElement.style.color = "#666";
    fileNameElement.style.fontWeight = "normal";
  }
}

function handleDragOver(e) {
  e.preventDefault();
  e.stopPropagation();
  e.currentTarget.style.borderColor = "#0066cc";
  e.currentTarget.style.background = "#f0f7ff";
}

function handleDragLeave(e) {
  e.preventDefault();
  e.stopPropagation();
  e.currentTarget.style.borderColor = "#d1d5db";
  e.currentTarget.style.background = "#fafafa";
}

function handleDrop(e) {
  e.preventDefault();
  e.stopPropagation();

  const dropArea = e.currentTarget;
  dropArea.style.borderColor = "#d1d5db";
  dropArea.style.background = "#fafafa";

  const files = e.dataTransfer.files;
  if (files.length > 0) {
    const fileInput = document.getElementById("fileInput");
    fileInput.files = files;
    updateFileName(fileInput);
  }
}

document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("#myModal form");
  form.addEventListener("submit", function (e) {
    const fileInput = document.getElementById("fileInput");
    if (!fileInput.files.length) {
      e.preventDefault();
      alert("Please select a file to upload");
      return false;
    }
  });

  const submitBtn = document.getElementById("submitBtn");
  submitBtn.disabled = true;
  submitBtn.style.opacity = "0.5";
  submitBtn.style.cursor = "not-allowed";
});

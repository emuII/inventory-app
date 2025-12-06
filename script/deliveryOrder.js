let itemsData = [];
let cart = [];
let itemsTable;
let selectedItems = [];

$(document).ready(function () {
  const now = new Date();
  $("#current-date").text(
    `${now.getDate().toString().padStart(2, "0")}/${(now.getMonth() + 1)
      .toString()
      .padStart(2, "0")}/${now.getFullYear()}`
  );

  // Load data via AJAX
  loadItemsData();

  // Add to cart button event
  $("#add-to-cart").on("click", function (e) {
    e.preventDefault();
    if (selectedItems.length === 0) {
      Swal.fire({
        icon: "warning",
        title: "Warning",
        text: "Please select at least one item!",
        confirmButtonColor: "#4361ee",
      });
      return;
    }

    const qty = parseInt($("#item-qty").val());
    if (isNaN(qty) || qty < 1) {
      Swal.fire({
        icon: "warning",
        title: "Warning",
        text: "Quantity must be at least 1!",
        confirmButtonColor: "#4361ee",
      });
      return;
    }

    // Add all selected items to cart
    let addedCount = 0;
    let updatedCount = 0;

    selectedItems.forEach((item) => {
      const result = addToCart(item, qty);
      if (result === "added") {
        addedCount++;
      } else if (result === "updated") {
        updatedCount++;
      }
    });

    clearSelection();
    $("#qty-form").slideUp();

    // Show success message
    let message = "";
    if (addedCount > 0 && updatedCount > 0) {
      message = `${addedCount} new item(s) added, ${updatedCount} item(s) updated`;
    } else if (addedCount > 0) {
      message = `${addedCount} item(s) successfully added to cart`;
    } else if (updatedCount > 0) {
      message = `${updatedCount} item(s) successfully updated`;
    }

    Swal.fire({
      icon: "success",
      title: "Success",
      text: message || "Cart updated successfully",
      confirmButtonColor: "#4361ee",
      timer: 2000,
    });
  });

  $("#checkout-btn").on("click", checkout);
  renderCart();
});

function loadItemsData() {
  $("#items-table tbody").html(
    '<tr><td colspan="4" class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><div class="mt-2">Loading data...</div></td></tr>'
  );

  $.ajax({
    type: "POST",
    url: "middleware/ajax_handler.php?controller=deliveryOrder&action=getProductList",
    data: $("form#eys").serialize(),
    dataType: "json",
    success: function (response) {
      if (response && response.success && Array.isArray(response.data)) {
        itemsData = transformProductData(response.data);
      } else if (Array.isArray(response)) {
        itemsData = transformProductData(response);
      } else {
        itemsData = [];
      }

      initializeDataTable();
    },
    error: function (xhr, status, error) {
      itemsData = [];
      initializeDataTable();

      Swal.fire({
        icon: "warning",
        title: "Warning",
        text: "Failed to load product data from server",
        confirmButtonColor: "#4361ee",
        timer: 2000,
      });
    },
  });
}

function transformProductData(apiData) {
  if (!Array.isArray(apiData)) {
    return [];
  }

  return apiData.map((item) => {
    return {
      id: item.ItemId || item.id || 0,
      name: item.itemName || item.name || "Unknown Item",
      type: item.typeCategory || item.type || "part",
      category: item.categoryItem || item.category || "",
      price: Number(item.sellingPrice || item.price || 0),
      originalData: item,
    };
  });
}

function getBadgeClass(category) {
  switch (category.toLowerCase()) {
    case "service":
      return "badge-service";
    case "sparepart":
      return "badge-part";
    case "aksesoris":
      return "badge-accessories";
    default:
      return "badge-part";
  }
}

function getBadgeText(category) {
  switch (category.toLowerCase()) {
    case "service":
      return "Service";
    case "sparepart":
      return "Spare Part";
    case "aksesoris":
      return "Accessories";
    default:
      return "Spare Part";
  }
}
function initializeDataTable() {
  if ($.fn.DataTable.isDataTable("#items-table")) {
    itemsTable.destroy();
  }

  try {
    itemsTable = $("#items-table").DataTable({
      data: itemsData,
      columns: [
        {
          data: null,
          className: "text-center",
          orderable: false,
          render: function (data, type, row) {
            // Cek apakah item sudah dipilih sebelumnya
            const isChecked = selectedItems.some((item) => item.id === row.id);
            return `<input type="checkbox" class="item-checkbox" data-id="${
              row.id
            }" ${isChecked ? "checked" : ""}>`;
          },
        },
        {
          data: "name",
          className: "align-middle",
        },
        {
          data: "category",
          className: "align-middle text-center",
          render: function (data, type, row) {
            const badgeClass = getBadgeClass(data);
            const badgeText = getBadgeText(data);
            return `<span class="badge ${badgeClass}">${badgeText}</span>`;
          },
        },
        {
          data: "price",
          className: "align-middle text-end",
          render: function (data, type, row) {
            return `${data.toLocaleString("id-ID")}`;
          },
        },
      ],
      language: {
        search: "Search items:",
        lengthMenu: "Show _MENU_ items",
        info: "Showing _START_ to _END_ of _TOTAL_ items",
        infoEmpty: "Showing 0 to 0 of 0 items",
        infoFiltered: "(filtered from _MAX_ total items)",
        zeroRecords: "No items found",
        paginate: {
          previous: "‹",
          next: "›",
        },
      },
      pageLength: 5,
      lengthMenu: [5, 10, 20, 50],
      dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
      responsive: true,
      drawCallback: function (settings) {
        // Setelah DataTables menggambar ulang, update checkbox state
        $(".item-checkbox").each(function () {
          const itemId = parseInt($(this).data("id"));
          const isSelected = selectedItems.some((item) => item.id === itemId);
          $(this).prop("checked", isSelected);
        });
        updateSelectAllCheckbox();
      },
      initComplete: function () {
        const headerCheckbox =
          '<input type="checkbox" id="select-all" title="Select All">';
        $("#items-table thead tr th:first").html(headerCheckbox);

        $("#select-all").on("change", function () {
          const isChecked = $(this).prop("checked");

          // Dapatkan semua baris yang terlihat (setelah filtering)
          const visibleRows = itemsTable.rows({ filter: "applied" }).nodes();
          $(visibleRows)
            .find(".item-checkbox")
            .prop("checked", isChecked)
            .trigger("change");
        });

        $("#items-table tbody").on("change", ".item-checkbox", function () {
          const itemId = parseInt($(this).data("id"));
          const isChecked = $(this).is(":checked");

          if (isChecked) {
            // Tambahkan ke selectedItems jika belum ada
            const itemData = itemsData.find((item) => item.id === itemId);
            if (itemData && !selectedItems.some((item) => item.id === itemId)) {
              selectedItems.push(itemData);
            }
          } else {
            // Hapus dari selectedItems jika dicentang ulang
            selectedItems = selectedItems.filter((item) => item.id !== itemId);
          }

          updateQtyForm();
          updateSelectAllCheckbox();
        });

        $("#items-table tbody").on("click", "tr", function (e) {
          if (!$(e.target).is('input[type="checkbox"]')) {
            const checkbox = $(this).find(".item-checkbox");
            const isChecked = !checkbox.prop("checked");
            checkbox.prop("checked", isChecked);

            const itemId = parseInt(checkbox.data("id"));
            if (isChecked) {
              // Tambahkan ke selectedItems jika belum ada
              const itemData = itemsData.find((item) => item.id === itemId);
              if (
                itemData &&
                !selectedItems.some((item) => item.id === itemId)
              ) {
                selectedItems.push(itemData);
              }
            } else {
              // Hapus dari selectedItems jika dicentang ulang
              selectedItems = selectedItems.filter(
                (item) => item.id !== itemId
              );
            }

            updateQtyForm();
            updateSelectAllCheckbox();
          }
        });

        // Inisialisasi state awal checkbox
        updateSelectAllCheckbox();
      },
    });
  } catch (error) {
    renderBasicTable();
  }
}

function renderBasicTable() {
  let html = "";
  itemsData.forEach((item) => {
    const badgeClass = getBadgeClass(item.category);
    const badgeText = getBadgeText(item.category);
    const isChecked = selectedItems.some((selected) => selected.id === item.id);

    html += `
            <tr>
                <td class="text-center">
                    <input type="checkbox" class="item-checkbox" data-id="${
                      item.id
                    }" ${isChecked ? "checked" : ""}>
                </td>
                <td class="align-middle">${item.name}</td>
                <td class="align-middle text-center">
                    <span class="badge ${badgeClass}">${badgeText}</span>
                </td>
                <td class="align-middle text-end">${item.price.toLocaleString(
                  "id-ID"
                )}</td>
            </tr>
        `;
  });

  $("#items-table tbody").html(html);

  $(".item-checkbox").on("change", function () {
    const itemId = parseInt($(this).data("id"));
    const isChecked = $(this).is(":checked");

    if (isChecked) {
      // Tambahkan ke selectedItems jika belum ada
      const itemData = itemsData.find((item) => item.id === itemId);
      if (itemData && !selectedItems.some((item) => item.id === itemId)) {
        selectedItems.push(itemData);
      }
    } else {
      // Hapus dari selectedItems jika dicentang ulang
      selectedItems = selectedItems.filter((item) => item.id !== itemId);
    }

    updateQtyForm();
  });
}

function updateSelectedItems() {
  const newSelectedItems = [];

  // Iterate melalui semua item di itemsData, bukan hanya yang terlihat di UI
  $(".item-checkbox").each(function () {
    if ($(this).is(":checked")) {
      const itemId = parseInt($(this).data("id"));
      const fullItemData = itemsData.find((item) => item.id === itemId);

      if (
        fullItemData &&
        !newSelectedItems.some((selected) => selected.id === itemId)
      ) {
        newSelectedItems.push(fullItemData);
      }
    }
  });

  selectedItems = newSelectedItems;
  updateQtyForm();
}

function updateSelectAllCheckbox() {
  const totalVisibleCheckboxes = itemsTable
    ? itemsTable.rows({ filter: "applied" }).count()
    : $(".item-checkbox").length;

  const checkedVisibleCheckboxes = itemsTable
    ? itemsTable
        .rows({ filter: "applied" })
        .nodes()
        .to$()
        .find(".item-checkbox:checked").length
    : $(".item-checkbox:checked").length;

  const selectAll = $("#select-all");

  if (checkedVisibleCheckboxes === 0) {
    selectAll.prop("checked", false);
    selectAll.prop("indeterminate", false);
  } else if (checkedVisibleCheckboxes === totalVisibleCheckboxes) {
    selectAll.prop("checked", true);
    selectAll.prop("indeterminate", false);
  } else {
    selectAll.prop("checked", false);
    selectAll.prop("indeterminate", true);
  }
}

function updateQtyForm() {
  if (selectedItems.length === 0) {
    $("#qty-form").slideUp();
    return;
  }

  if (selectedItems.length === 1) {
    $("#selected-item-name").val(selectedItems[0].name);
  } else {
    $("#selected-item-name").val(`${selectedItems.length} items selected`);
  }

  $("#item-qty").val(1);
  $("#qty-form").slideDown();
}

function clearSelection() {
  selectedItems = [];
  $(".item-checkbox").prop("checked", false);
  $("#select-all").prop("checked", false);
  $("#select-all").prop("indeterminate", false);

  if (itemsTable) {
    itemsTable.draw();
  }
}
function addToCart(item, qty) {
  const existingItemIndex = cart.findIndex(
    (cartItem) => cartItem.id === item.id
  );

  if (existingItemIndex !== -1) {
    cart[existingItemIndex].qty += qty;
    cart[existingItemIndex].subtotal =
      cart[existingItemIndex].qty * cart[existingItemIndex].price;
    renderCart();
    return "updated";
  } else {
    cart.push({
      id: item.id,
      name: item.name,
      type: item.type,
      category: item.category,
      price: item.price,
      qty: qty,
      subtotal: item.price * qty,
    });
    renderCart();
    return "added";
  }
}

function renderCart() {
  const cartItems = $("#cart-items");
  cartItems.empty();

  if (cart.length === 0) {
    cartItems.append(`
            <tr>
                <td colspan="5" class="text-center py-4 text-muted">
                    Shopping cart is empty
                </td>
            </tr>
        `);
  } else {
    cart.forEach((item) => {
      const row = `
                <tr>
                    <td class="align-middle">${item.name}</td>
                    <td class="align-middle">
                        <div class="cart-quantity-control">
                            <button class="btn btn-sm btn-outline-secondary decrease-qty" data-id="${
                              item.id
                            }">-</button>
                            <input type="number" class="form-control form-control-sm text-center cart-qty-input" value="${
                              item.qty
                            }" min="1" data-id="${item.id}">
                            <button class="btn btn-sm btn-outline-secondary increase-qty" data-id="${
                              item.id
                            }">+</button>
                        </div>
                    </td>
                    <td class="align-middle text-end">${item.price.toLocaleString(
                      "id-ID"
                    )}</td>
                   
                    <td class="align-middle text-center">
                        <button class="btn btn-sm btn-outline-danger action-btn remove-item" data-id="${
                          item.id
                        }" title="Remove"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            `;
      cartItems.append(row);
    });

    attachCartEventHandlers();
  }

  $("#cart-count").text(`${cart.length} item${cart.length !== 1 ? "s" : ""}`);
  calculateTotal();
}

function attachCartEventHandlers() {
  // Increase quantity button
  $(".increase-qty")
    .off("click")
    .on("click", function () {
      const itemId = parseInt($(this).data("id"));
      const input = $(this).siblings(".cart-qty-input");
      const currentQty = parseInt(input.val()) || 0;
      const newQty = currentQty + 1;
      input.val(newQty);
      updateCartItem(itemId, newQty);
    });

  // Decrease quantity button
  $(".decrease-qty")
    .off("click")
    .on("click", function () {
      const itemId = parseInt($(this).data("id"));
      const input = $(this).siblings(".cart-qty-input");
      const currentQty = parseInt(input.val()) || 1;

      if (currentQty > 1) {
        const newQty = currentQty - 1;
        input.val(newQty);
        updateCartItem(itemId, newQty);
      }
    });

  // Direct quantity input
  $(".cart-qty-input")
    .off("change")
    .on("change", function () {
      const itemId = parseInt($(this).data("id"));
      let newQty = parseInt($(this).val());

      if (isNaN(newQty) || newQty < 1) {
        newQty = 1;
        $(this).val(1);
      }

      updateCartItem(itemId, newQty);
    });

  // Remove item button
  $(".remove-item")
    .off("click")
    .on("click", function () {
      const itemId = parseInt($(this).data("id"));
      removeFromCart(itemId);
    });
}

function updateCartItem(itemId, newQty) {
  const itemIndex = cart.findIndex((item) => item.id === itemId);
  if (itemIndex !== -1) {
    cart[itemIndex].qty = newQty;
    cart[itemIndex].subtotal = cart[itemIndex].price * newQty;
    renderCart();
  }
}

function removeFromCart(itemId) {
  Swal.fire({
    icon: "question",
    title: "Remove Item?",
    text: "Are you sure you want to remove this item from the cart?",
    showCancelButton: true,
    confirmButtonColor: "#4361ee",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "Yes, Remove!",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (result.isConfirmed) {
      cart = cart.filter((item) => item.id !== itemId);
      renderCart();

      Swal.fire({
        icon: "success",
        title: "Removed!",
        text: "Item has been removed from the cart",
        confirmButtonColor: "#4361ee",
        timer: 1500,
      });
    }
  });
}

function calculateTotal() {
  const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
  const tax = subtotal * 0.1;
  const total = subtotal + tax;

  $("#subtotal").text(`${subtotal.toLocaleString("id-ID")}`);
  $("#tax").text(`${tax.toLocaleString("id-ID")}`);
  $("#total").text(`${total.toLocaleString("id-ID")}`);
}

function checkout() {
  if (cart.length === 0) {
    Swal.fire({
      icon: "warning",
      title: "Empty Cart",
      text: "Shopping cart is empty!",
      confirmButtonColor: "#4361ee",
    });
    return;
  }

  const total = $("#total").text();

  Swal.fire({
    icon: "question",
    title: "Checkout Confirmation",
    html: `Are you sure you want to checkout with total <strong>${total}</strong>?`,
    showCancelButton: true,
    confirmButtonColor: "#4361ee",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "Yes, Checkout!",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (result.isConfirmed) {
      simulateCheckout()
        .then(() => {
          Swal.fire({
            icon: "success",
            title: "Success!",
            text: "Transaction successful! Receipt will be printed.",
            confirmButtonColor: "#4361ee",
            timer: 2000,
          });

          cart = [];
          renderCart();
          $("#qty-form").hide();
          clearSelection();
        })
        .catch((error) => {
          Swal.fire({
            icon: "error",
            title: "Failed",
            text: "An error occurred during checkout: " + error,
            confirmButtonColor: "#4361ee",
          });
        });
    }
  });
}
function simulateCheckout() {
  return new Promise((resolve, reject) => {
    const checkoutBtn = $("#checkout-btn");
    const originalText = checkoutBtn.html();

    const checkoutData = {
      items: cart.map((item) => ({
        itemId: item.id,
        quantity: item.qty,
        subtotal: item.subtotal,
      })),
      tax: cart.reduce((sum, item) => sum + item.subtotal, 0) * 0.1,
      totalAmount: cart.reduce((sum, item) => sum + item.subtotal, 0) * 1.1,
    };

    console.log("Checkout Data:", checkoutData);
    checkoutBtn
      .prop("disabled", true)
      .html(
        '<span class="spinner-border spinner-border-sm" role="status"></span> Processing...'
      );

    $.ajax({
      type: "POST",
      url: "middleware/ajax_handler.php?controller=deliveryOrder&action=submitDeliveryOrder",
      data: JSON.stringify(checkoutData),
      contentType: "application/json",
      dataType: "json",
      success: function (response) {
        checkoutBtn.prop("disabled", false).html(originalText);
        console.log("Checkout Response:", response);

        // Handle response based on the actual structure
        if (response && response.success) {
          // Success case - resolve with the complete response
          resolve({
            success: true,
            message:
              response.message || "Delivery Order submitted successfully",
            doCode: response.doCode,
          });
        } else {
          // Failure case - reject with appropriate message
          reject(
            response && response.message ? response.message : "Checkout failed"
          );
        }
      },
      error: function (xhr, status, error) {
        checkoutBtn.prop("disabled", false).html(originalText);

        let errorMessage = "Network error occurred";

        try {
          const response = JSON.parse(xhr.responseText);
          if (response && response.message) {
            errorMessage = response.message;
          }
        } catch (e) {
          if (xhr.status === 0) {
            errorMessage = "Cannot connect to server";
          } else if (xhr.status === 500) {
            errorMessage = "Server error occurred";
          } else if (xhr.responseText) {
            errorMessage = xhr.responseText;
          }
        }

        reject(errorMessage);
      },
    });
  });
}

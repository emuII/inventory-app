<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-column flex-md-row">
            <div class="text-center text-md-start mb-2 mb-md-0">
                <h1 class="h3 mb-0">Kasir Bengkel Motor</h1>
                <p class="mb-0 opacity-75">Sistem Transaksi Bengkel</p>
            </div>
            <div class="d-flex align-items-center flex-column flex-sm-row">
                <div class="me-0 me-sm-3 mb-2 mb-sm-0">
                    <strong>Kasir:</strong> Ahmad
                </div>
                <div class="bg-light text-dark px-3 py-1 rounded">
                    <strong>Tanggal:</strong> <span id="current-date">27/11/2023</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-6 mb-4 mb-lg-0">
            <form id="eys"></form>
            <div class="search-section">
                <h5 class="mb-3">Daftar Layanan & Sparepart</h5>
                <div class="table-responsive">
                    <table class="table table-modern" id="items-table">
                        <thead>
                            <tr>
                                <th width="5%"></th>
                                <th width="45%">Nama Item</th>
                                <th width="25%">Jenis</th>
                                <th width="25%">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="search-section mt-3" id="qty-form" style="display: none;">
                <h5 class="mb-3">Input Jumlah</h5>
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Item Terpilih</label>
                        <input type="text" class="form-control form-control-modern" id="selected-item-name" readonly>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Jumlah</label>
                        <input type="number" class="form-control form-control-modern" id="item-qty" min="1" value="1">
                    </div>
                </div>
                <div class="d-grid mt-2">
                    <button class="btn btn-primary-modern btn-modern" id="add-to-cart">Tambah ke Keranjang</button>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="search-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Keranjang Belanja</h5>
                    <span class="badge bg-primary" id="cart-count">0 item</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-modern" id="cart-table">
                        <thead>
                            <tr>
                                <th width="40%">Nama Item</th>
                                <th width="20%">Jumlah</th>
                                <th width="20%">Harga</th>
                                <!-- <th width="15%">Subtotal</th> -->
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody id="cart-items">
                            <!-- Item keranjang akan dimuat di sini -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="total-section">
                <div class="row mb-3">
                    <div class="col-6">
                        <strong>Subtotal:</strong>
                    </div>
                    <div class="col-6 text-end">
                        <span id="subtotal">Rp 0</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <strong>Pajak (10%):</strong>
                    </div>
                    <div class="col-6 text-end">
                        <span id="tax">Rp 0</span>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-6">
                        <h5><strong>Total:</strong></h5>
                    </div>
                    <div class="col-6 text-end">
                        <h5><strong id="total">Rp 0</strong></h5>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-primary-modern btn-modern btn-lg" id="checkout-btn">Checkout</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let itemsData = []; // Kosongkan array, akan diisi via AJAX
    let cart = [];
    let itemsTable;
    let selectedItems = [];

    $(document).ready(function() {
        // Set tanggal hari ini
        const now = new Date();
        $('#current-date').text(
            `${now.getDate().toString().padStart(2, '0')}/${(now.getMonth()+1).toString().padStart(2, '0')}/${now.getFullYear()}`
        );

        // Load data via AJAX
        loadItemsData();

        // // Event handler untuk tombol tambah ke keranjang
        $('#add-to-cart').on('click', function() {
            if (selectedItems.length === 0) {
                alert('Pilih item terlebih dahulu!');
                return;
            }

            const qty = parseInt($('#item-qty').val());
            if (qty < 1) {
                alert('Jumlah harus minimal 1!');
                return;
            }

            // Tambahkan semua item yang dipilih ke keranjang
            selectedItems.forEach(item => {
                addToCart(item, qty);
            });

            // Reset form dan selection
            clearSelection();
            $('#qty-form').slideUp();

            // Show success message
            showToast(`${selectedItems.length} item berhasil ditambahkan ke keranjang`, 'success');
        });

        // Event handler untuk tombol checkout
        $('#checkout-btn').on('click', checkout);

        // Render keranjang awal
        renderCart();
    });

    function loadItemsData() {
        // Show loading state
        $('#items-table tbody').html('<tr><td colspan="4" class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><div class="mt-2">Loading data...</div></td></tr>');

        $.ajax({
            type: 'POST',
            url: 'middleware/ajax_handler.php?controller=deliveryOrder&action=getProductList',
            data: $("form#eys").serialize(),
            success: function(response) {
                console.log('Response data:', response);
                // Transform response data ke format yang diharapkan
                itemsData = transformProductData(response.data);

                // Initialize DataTables dengan data baru
                initializeDataTable();

                showToast('Data produk berhasil dimuat', 'success');
            },
            error: function(xhr, status, error) {
                console.error('Error loading product data:', error);

                // Fallback ke data lokal jika AJAX gagal
                itemsData = getFallbackData();
                initializeDataTable();

                showToast('Gagal memuat data, menggunakan data fallback', 'warning');
            },
            complete: function() {
                // Hide loading state jika ada
            }
        });
    }

    // Transform data dari PHP ke format yang diharapkan frontend
    function transformProductData(apiData) {
        return apiData.map(item => {
            return {
                id: item.ItemId,
                name: item.itemName,
                type: item.typeCategory || 'part', // Default ke 'part' jika tidak ada
                category: item.categoryItem,
                price: parseInt(item.sellingPrice) || 0,
                originalData: item // Simpan data original jika diperlukan
            };
        });
    }

    // Fallback data jika AJAX gagal
    function getFallbackData() {
        return [{
                id: 1,
                name: "Ganti Oli Mesin",
                type: "service",
                price: 75000
            },
            {
                id: 2,
                name: "Service Karburator",
                type: "service",
                price: 50000
            },
            {
                id: 3,
                name: "Ganti Kampas Rem",
                type: "service",
                price: 80000
            }
        ];
    }

    function initializeDataTable() {
        // Pastikan DataTables belum diinisialisasi sebelumnya
        if ($.fn.DataTable.isDataTable('#items-table')) {
            itemsTable.destroy();
            $('#items-table').empty(); // Clear table
        }

        // Re-create the table structure
        $('#items-table').html(`
        <thead>
            <tr>
                <th width="5%"></th>
                <th width="45%">Nama Item</th>
                <th width="25%">Jenis</th>
                <th width="25%">Harga</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data akan diisi oleh DataTables -->
        </tbody>
    `);

        itemsTable = $('#items-table').DataTable({
            data: itemsData,
            columns: [{
                    data: null,
                    className: 'text-center',
                    orderable: false,
                    render: function(data, type, row) {
                        return `<input type="checkbox" class="item-checkbox" data-id="${row.id}">`;
                    }
                },
                {
                    data: 'name',
                    className: 'align-middle'
                },
                {
                    data: 'type',
                    className: 'align-middle text-center',
                    render: function(data, type, row) {
                        const badgeClass = data === 'service' ? 'badge-service' : 'badge-part';
                        const badgeText = data === 'service' ? 'Layanan' : 'Sparepart';
                        return `<span class="badge ${badgeClass}">${badgeText}</span>`;
                    }
                },
                {
                    data: 'price',
                    className: 'align-middle text-end',
                    render: function(data, type, row) {
                        return `Rp ${data.toLocaleString('id-ID')}`;
                    }
                }
            ],
            language: {
                search: "Cari item:",
                lengthMenu: "Tampilkan _MENU_ item",
                info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ item",
                infoEmpty: "Menampilkan 0 hingga 0 dari 0 item",
                infoFiltered: "(disaring dari _MAX_ total item)",
                zeroRecords: "Tidak ada item yang ditemukan",
                paginate: {
                    previous: "‹",
                    next: "›"
                }
            },
            pageLength: 10,
            lengthMenu: [5, 10, 20, 50],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            responsive: true,
            initComplete: function() {
                // Tambahkan header checkbox setelah table diinisialisasi
                const headerCheckbox = '<input type="checkbox" id="select-all" title="Pilih Semua">';
                $('#items-table thead tr th:first').html(headerCheckbox);

                // Event handler untuk select all
                $('#select-all').on('change', function() {
                    const isChecked = $(this).prop('checked');
                    $('.item-checkbox').prop('checked', isChecked).trigger('change');
                });

                // Event handler untuk individual checkbox
                $('#items-table tbody').on('change', '.item-checkbox', function() {
                    updateSelectedItems();
                    updateSelectAllCheckbox();
                });

                // Event handler untuk row click
                $('#items-table tbody').on('click', 'tr', function(e) {
                    if (!$(e.target).is('input[type="checkbox"]')) {
                        const checkbox = $(this).find('.item-checkbox');
                        checkbox.prop('checked', !checkbox.prop('checked'));
                        checkbox.trigger('change');
                    }
                });
            }
        });
    }

    // Update selected items array berdasarkan checkbox yang dicentang
    function updateSelectedItems() {
        selectedItems = [];
        $('.item-checkbox:checked').each(function() {
            const itemId = parseInt($(this).data('id'));
            const fullItemData = itemsData.find(item => item.id === itemId);

            if (fullItemData) {
                selectedItems.push(fullItemData);
            }
        });

        // Update form input jumlah
        updateQtyForm();
    }

    // Update select all checkbox state
    function updateSelectAllCheckbox() {
        const totalCheckboxes = $('.item-checkbox').length;
        const checkedCheckboxes = $('.item-checkbox:checked').length;

        const selectAll = $('#select-all');

        if (checkedCheckboxes === 0) {
            selectAll.prop('checked', false);
            selectAll.prop('indeterminate', false);
        } else if (checkedCheckboxes === totalCheckboxes) {
            selectAll.prop('checked', true);
            selectAll.prop('indeterminate', false);
        } else {
            selectAll.prop('checked', false);
            selectAll.prop('indeterminate', true);
        }
    }

    // Update form input jumlah berdasarkan item yang dipilih
    function updateQtyForm() {
        if (selectedItems.length === 0) {
            $('#qty-form').slideUp();
            return;
        }

        if (selectedItems.length === 1) {
            $('#selected-item-name').val(selectedItems[0].name);
        } else {
            $('#selected-item-name').val(`${selectedItems.length} item dipilih`);
        }

        $('#item-qty').val(1);
        $('#qty-form').slideDown();
    }

    // Clear selection
    function clearSelection() {
        $('.item-checkbox').prop('checked', false);
        $('#select-all').prop('checked', false);
        $('#select-all').prop('indeterminate', false);
        selectedItems = [];
    }

    // Fungsi untuk menambahkan item ke keranjang
    function addToCart(item, qty) {
        const existingItemIndex = cart.findIndex(cartItem => cartItem.id === item.id);

        if (existingItemIndex !== -1) {
            // Jika item sudah ada di keranjang, update jumlahnya
            cart[existingItemIndex].qty += qty;
            cart[existingItemIndex].subtotal = cart[existingItemIndex].qty * cart[existingItemIndex].price;
        } else {
            // Jika item belum ada di keranjang, tambahkan baru
            cart.push({
                id: item.id,
                name: item.name,
                type: item.type,
                price: item.price,
                qty: qty,
                subtotal: item.price * qty
            });
        }

        // Update tampilan keranjang
        renderCart();
    }

    // Fungsi untuk menampilkan keranjang
    function renderCart() {
        const cartItems = $('#cart-items');
        cartItems.empty();

        if (cart.length === 0) {
            cartItems.append(`
            <tr>
                <td colspan="5" class="text-center py-4 text-muted">
                    Keranjang belanja kosong
                </td>
            </tr>
        `);
        } else {
            cart.forEach(item => {
                const row = `
                <tr>
                    <td class="align-middle">${item.name}</td>
                    <td class="align-middle">
                        <div class="cart-quantity-control">
                            <button class="btn btn-sm btn-outline-secondary decrease-qty" data-id="${item.id}">-</button>
                            <input type="number" class="form-control form-control-sm text-center" value="${item.qty}" min="1" data-id="${item.id}">
                            <button class="btn btn-sm btn-outline-secondary increase-qty" data-id="${item.id}">+</button>
                        </div>
                    </td>
                    <td class="align-middle text-end">Rp ${item.price.toLocaleString('id-ID')}</td>
                    
                    <td class="align-middle text-center">
                        <button class="btn btn-sm btn-danger remove-item" data-id="${item.id}" title="Hapus">×</button>
                    </td>
                </tr>
            `;
                cartItems.append(row);
            });

            // Attach event handlers
            attachCartEventHandlers();
        }

        // Update jumlah item di keranjang
        $('#cart-count').text(`${cart.length} item`);

        // Hitung total
        calculateTotal();
    }

    // Attach event handlers untuk keranjang
    function attachCartEventHandlers() {
        // Event handler untuk tombol + dan -
        $('.increase-qty').off('click').on('click', function() {
            const itemId = parseInt($(this).data('id'));
            const input = $(`input[data-id="${itemId}"]`);
            const newQty = parseInt(input.val()) + 1;
            input.val(newQty);
            updateCartItem(itemId, newQty);
        });

        $('.decrease-qty').off('click').on('click', function() {
            const itemId = parseInt($(this).data('id'));
            const input = $(`input[data-id="${itemId}"]`);
            const newQty = parseInt(input.val()) - 1;
            if (newQty >= 1) {
                input.val(newQty);
                updateCartItem(itemId, newQty);
            }
        });

        // Event handler untuk input langsung
        $('input[data-id]').off('change').on('change', function() {
            const itemId = parseInt($(this).data('id'));
            const newQty = parseInt($(this).val());
            if (newQty < 1) {
                $(this).val(1);
                return;
            }
            updateCartItem(itemId, newQty);
        });

        // Event handler untuk tombol hapus
        $('.remove-item').off('click').on('click', function() {
            const itemId = parseInt($(this).data('id'));
            removeFromCart(itemId);
        });
    }

    // Fungsi untuk mengupdate jumlah item di keranjang
    function updateCartItem(itemId, newQty) {
        const itemIndex = cart.findIndex(item => item.id === itemId);
        if (itemIndex !== -1) {
            cart[itemIndex].qty = newQty;
            cart[itemIndex].subtotal = cart[itemIndex].price * newQty;
            renderCart();
        }
    }

    // Fungsi untuk menghapus item dari keranjang
    function removeFromCart(itemId) {
        if (confirm('Hapus item dari keranjang?')) {
            cart = cart.filter(item => item.id !== itemId);
            renderCart();
            showToast('Item berhasil dihapus dari keranjang', 'warning');
        }
    }

    // Fungsi untuk menghitung total
    function calculateTotal() {
        const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
        const tax = subtotal * 0.1; // Pajak 10%
        const total = subtotal + tax;

        $('#subtotal').text(`Rp ${subtotal.toLocaleString('id-ID')}`);
        $('#tax').text(`Rp ${tax.toLocaleString('id-ID')}`);
        $('#total').text(`Rp ${total.toLocaleString('id-ID')}`);
    }

    // Fungsi untuk checkout
    function checkout() {
        if (cart.length === 0) {
            showToast('Keranjang belanja kosong!', 'error');
            return;
        }

        const total = $('#total').text();

        if (confirm(`Konfirmasi checkout dengan total ${total}?`)) {
            // Simulasi proses checkout
            simulateCheckout()
                .then(() => {
                    showToast('Transaksi berhasil! Struk akan dicetak.', 'success');
                    cart = [];
                    renderCart();
                    $('#qty-form').hide();
                    clearSelection();
                })
                .catch(error => {
                    showToast('Terjadi kesalahan saat checkout: ' + error, 'error');
                });
        }
    }

    // Simulasi AJAX checkout
    function simulateCheckout() {
        return new Promise((resolve, reject) => {
            // Simulasi loading
            const checkoutBtn = $('#checkout-btn');
            const originalText = checkoutBtn.html();
            checkoutBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Processing...');

            // Simulasi API call
            setTimeout(() => {
                // Simulasi 5% kemungkinan error
                if (Math.random() < 0.05) {
                    checkoutBtn.prop('disabled', false).html(originalText);
                    reject('Network error');
                } else {
                    checkoutBtn.prop('disabled', false).html(originalText);
                    resolve();
                }
            }, 1500);
        });
    }

    // Fungsi untuk menampilkan toast notification
    function showToast(message, type = 'info') {
        // Hapus toast sebelumnya
        $('.custom-toast').remove();

        const bgColor = type === 'success' ? 'bg-success' :
            type === 'error' ? 'bg-danger' :
            type === 'warning' ? 'bg-warning' : 'bg-info';

        const toast = $(`
        <div class="custom-toast position-fixed top-0 end-0 m-3 ${bgColor} text-white p-3 rounded shadow" style="z-index: 9999; min-width: 300px;">
            <div class="d-flex justify-content-between align-items-center">
                <span>${message}</span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `);

        $('body').append(toast);

        // Auto remove setelah 3 detik
        setTimeout(() => {
            toast.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);

        // Bisa di-close manual
        toast.find('.btn-close').on('click', function() {
            toast.remove();
        });
    }

    // Refresh data (optional - bisa ditambahkan tombol refresh)
    function refreshProductData() {
        loadItemsData();
    }
</script>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir Bengkel Motor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --hover-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        body {
            background-color: #f5f7fb;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            box-shadow: var(--card-shadow);
        }

        .card-modern {
            border: none;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 1.5rem;
        }

        .card-modern:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .btn-modern {
            border-radius: 6px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white !important;
        }

        .btn-primary-modern:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.3);
        }

        .table-modern {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.05);
        }

        .table-modern thead {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .table-modern th {
            border: none;
            padding: 1rem;
            font-weight: 500;
        }

        .table-modern td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #eaeaea;
        }

        .table-modern tbody tr {
            transition: background-color 0.2s;
        }

        .table-modern tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }

        .form-control-modern {
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            padding: 0.6rem 0.75rem;
            transition: all 0.3s;
        }

        .form-control-modern:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        }

        .search-section {
            background-color: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .filter-group {
            margin-bottom: 0;
        }

        .filter-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #495057;
        }

        .badge-service {
            background-color: rgba(67, 97, 238, 0.15);
            color: var(--primary-color);
            border: 1px solid rgba(67, 97, 238, 0.3);
        }

        .badge-part {
            background-color: rgba(76, 201, 240, 0.15);
            color: var(--success-color);
            border: 1px solid rgba(76, 201, 240, 0.3);
        }

        .selected {
            background-color: rgba(67, 97, 238, 0.1) !important;
        }

        .quantity-control {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .quantity-control button {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
        }

        .quantity-control input {
            width: 60px;
            text-align: center;
            margin: 0 5px;
        }

        .total-section {
            background-color: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            box-shadow: var(--card-shadow);
        }

        @media (max-width: 768px) {
            .filter-row {
                grid-template-columns: 1fr;
            }
        }

        /* DataTables Customization */
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            padding: 0.375rem 0.75rem;
        }

        .dataTables_wrapper .dataTables_length select {
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            padding: 0.375rem 0.75rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 6px;
            margin: 0 2px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
        }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Kasir Bengkel Motor</h1>
                    <p class="mb-0 opacity-75">Sistem Transaksi Bengkel</p>
                </div>
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <strong>Kasir:</strong> Ahmad
                    </div>
                    <div class="bg-light text-dark px-3 py-1 rounded">
                        <strong>Tanggal:</strong> <span id="current-date">27/11/2023</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Kolom Kiri: Daftar Layanan & Sparepart -->
            <div class="col-md-6">
                <div class="card-modern">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Daftar Layanan & Sparepart</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-modern" id="items-table">
                                <thead>
                                    <tr>
                                        <th width="60%">Nama Item</th>
                                        <th width="20%">Jenis</th>
                                        <th width="20%">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan diisi oleh DataTables -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Form Input Jumlah -->
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

            <!-- Kolom Kanan: Keranjang Belanja -->
            <div class="col-md-6">
                <div class="card-modern">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Keranjang Belanja</h5>
                        <span class="badge bg-primary" id="cart-count">0 item</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-modern" id="cart-table">
                                <thead>
                                    <tr>
                                        <th width="40%">Nama Item</th>
                                        <th width="15%">Jumlah</th>
                                        <th width="20%">Harga</th>
                                        <th width="20%">Subtotal</th>
                                        <th width="5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="cart-items">
                                    <!-- Item keranjang akan dimuat di sini -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Total & Checkout -->
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

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        // Data contoh untuk layanan dan sparepart
        const itemsData = [{
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
            },
            {
                id: 4,
                name: "Tune Up Mesin",
                type: "service",
                price: 60000
            },
            {
                id: 5,
                name: "Oli Mesin 1L",
                type: "part",
                price: 45000
            },
            {
                id: 6,
                name: "Busi Motor",
                type: "part",
                price: 15000
            },
            {
                id: 7,
                name: "Kampas Rem Depan",
                type: "part",
                price: 35000
            },
            {
                id: 8,
                name: "Filter Udara",
                type: "part",
                price: 25000
            },
            {
                id: 9,
                name: "Ban Dalam",
                type: "part",
                price: 55000
            },
            {
                id: 10,
                name: "Lampu Depan",
                type: "part",
                price: 40000
            }
        ];

        // Data keranjang
        let cart = [];

        // Inisialisasi DataTables untuk daftar item
        let itemsTable;

        $(document).ready(function() {
            // Set tanggal hari ini
            const now = new Date();
            $('#current-date').text(
                `${now.getDate().toString().padStart(2, '0')}/${(now.getMonth()+1).toString().padStart(2, '0')}/${now.getFullYear()}`
            );

            // Inisialisasi DataTables
            itemsTable = $('#items-table').DataTable({
                data: itemsData,
                columns: [{
                        data: 'name',
                        render: function(data, type, row) {
                            return data;
                        }
                    },
                    {
                        data: 'type',
                        render: function(data, type, row) {
                            const badgeClass = data === 'service' ? 'badge-service' : 'badge-part';
                            const badgeText = data === 'service' ? 'Layanan' : 'Sparepart';
                            return `<span class="badge ${badgeClass}">${badgeText}</span>`;
                        }
                    },
                    {
                        data: 'price',
                        render: function(data, type, row) {
                            return `Rp ${data.toLocaleString('id-ID')}`;
                        }
                    }
                ],
                language: {
                    search: "Cari item:",
                    lengthMenu: "Tampilkan _MENU_ item",
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ item",
                    paginate: {
                        previous: "‹",
                        next: "›"
                    }
                },
                pageLength: 5,
                lengthMenu: [5, 10, 20],
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
            });

            // Event handler untuk klik baris di DataTables
            $('#items-table tbody').on('click', 'tr', function() {
                // Hapus kelas selected dari semua baris
                $('#items-table tbody tr').removeClass('selected');

                // Tambahkan kelas selected ke baris yang diklik
                $(this).addClass('selected');

                // Tampilkan form input jumlah
                const rowData = itemsTable.row(this).data();
                $('#selected-item-name').val(rowData.name);
                $('#item-qty').val(1);
                $('#qty-form').slideDown();
            });

            // Event handler untuk tombol tambah ke keranjang
            $('#add-to-cart').on('click', function() {
                const selectedRow = $('#items-table tbody tr.selected');
                if (selectedRow.length === 0) {
                    alert('Pilih item terlebih dahulu!');
                    return;
                }

                const rowData = itemsTable.row(selectedRow).data();
                const qty = parseInt($('#item-qty').val());

                if (qty < 1) {
                    alert('Jumlah harus minimal 1!');
                    return;
                }

                addToCart(rowData, qty);

                // Reset form
                selectedRow.removeClass('selected');
                $('#qty-form').slideUp();
            });

            // Event handler untuk tombol checkout
            $('#checkout-btn').on('click', checkout);

            // Render keranjang awal
            renderCart();
        });

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
                            <td>${item.name}</td>
                            <td>
                                <div class="quantity-control">
                                    <button class="btn btn-sm btn-outline-secondary decrease-qty" data-id="${item.id}">-</button>
                                    <input type="number" class="form-control form-control-sm" value="${item.qty}" min="1" data-id="${item.id}">
                                    <button class="btn btn-sm btn-outline-secondary increase-qty" data-id="${item.id}">+</button>
                                </div>
                            </td>
                            <td>Rp ${item.price.toLocaleString('id-ID')}</td>
                            <td>Rp ${item.subtotal.toLocaleString('id-ID')}</td>
                            <td>
                                <button class="btn btn-sm btn-danger remove-item" data-id="${item.id}">×</button>
                            </td>
                        </tr>
                    `;
                    cartItems.append(row);
                });

                // Event handler untuk tombol + dan -
                $('.increase-qty').on('click', function() {
                    const itemId = parseInt($(this).data('id'));
                    const input = $(`input[data-id="${itemId}"]`);
                    const newQty = parseInt(input.val()) + 1;
                    input.val(newQty);
                    updateCartItem(itemId, newQty);
                });

                $('.decrease-qty').on('click', function() {
                    const itemId = parseInt($(this).data('id'));
                    const input = $(`input[data-id="${itemId}"]`);
                    const newQty = parseInt(input.val()) - 1;
                    if (newQty >= 1) {
                        input.val(newQty);
                        updateCartItem(itemId, newQty);
                    }
                });

                // Event handler untuk input langsung
                $('input[data-id]').on('change', function() {
                    const itemId = parseInt($(this).data('id'));
                    const newQty = parseInt($(this).val());
                    if (newQty < 1) {
                        $(this).val(1);
                        return;
                    }
                    updateCartItem(itemId, newQty);
                });

                // Event handler untuk tombol hapus
                $('.remove-item').on('click', function() {
                    const itemId = parseInt($(this).data('id'));
                    removeFromCart(itemId);
                });
            }

            // Update jumlah item di keranjang
            $('#cart-count').text(`${cart.length} item`);

            // Hitung total
            calculateTotal();
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
            cart = cart.filter(item => item.id !== itemId);
            renderCart();
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
                alert('Keranjang belanja kosong!');
                return;
            }

            const total = $('#total').text();
            if (confirm(`Konfirmasi checkout dengan total ${total}?`)) {
                alert('Transaksi berhasil! Struk akan dicetak.');
                cart = [];
                renderCart();
                $('#qty-form').hide();
            }
        }
    </script>
</body>

</html>
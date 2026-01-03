<link rel="stylesheet" href="public/custom/cashier.css">

<div class="container-fluid py-4">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">Motorcycle Workshop Cashier</h1>
                <p class="mb-0 opacity-75">Workshop Transaction System</p>
            </div>
            <div class="d-flex align-items-center flex-column flex-sm-row">
                <div class="me-0 me-sm-3 mb-2 mb-sm-0" style="margin-right: 21px !important;">
                    <strong>Cashier:</strong> <?php echo $_SESSION['active_login']['username'] ?>
                </div>
                <div class="bg-light text-dark px-3 py-1 rounded">
                    <strong>Date:</strong> <span id="current-date"></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-6 mb-4 mb-lg-0">
            <form id="eys"></form>
            <div class="search-section">
                <h5 class="mb-3">Services & Spareparts List</h5>
                <div class="table-responsive">
                    <table class="table table-modern" id="items-table">
                        <thead>
                            <tr>
                                <th width="5%"></th>
                                <th width="45%">Item Name</th>
                                <th width="25%">Type</th>
                                <th width="25%">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="search-section mt-3" id="qty-form" style="display: none;">
                <h5 class="mb-3">Quantity Input</h5>
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Selected Item</label>
                        <input type="text" class="form-control form-control-modern" id="selected-item-name" readonly>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Quantity</label>
                        <input type="number" class="form-control form-control-modern" id="item-qty" min="1" value="1">
                    </div>
                </div>
                <div class="d-grid mt-2">
                    <button class="btn btn-primary-modern btn-modern" id="add-to-cart">Add to Cart</button>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="search-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Shopping Cart</h5>
                    <span class="badge " id="cart-count">0 items</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-modern" id="cart-table">
                        <thead>
                            <tr>
                                <th width="40%">Item Name</th>
                                <th width="20%">Quantity</th>
                                <th width="20%">Price</th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                        <tbody id="cart-items">
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
                        <strong>Tax (10%):</strong>
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
<script src="script/deliveryOrder.js"></script>
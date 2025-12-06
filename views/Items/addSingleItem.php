<form id="prItem" method="post" onsubmit="return false;">
    <div class="container-fluid py-4">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Form Product</h1>
                    <p class="mb-0 opacity-75">Manage your form product</p>
                </div>
            </div>
        </div>
        <div class="container-section">
            <div class="search-section">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Product Name</label>
                        <input type="text" class="form-control form-control-modern" name="itemName" placeholder="Enter Product Name">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Product Type</label>
                        <input type="text" class="form-control form-control-modern" name="itemName" placeholder="Enter Product Name">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Product Category</label>
                        <input type="text" class="form-control form-control-modern" name="itemName" placeholder="Enter Product Name">
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary-modern btn-modern" type="submit">Submit
                    </button> &nbsp;
                    <button class="btn btn-outline-secondary btn-modern" type="button" onclick="history.back();">
                        <i class="fa-solid fa-angle-left"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
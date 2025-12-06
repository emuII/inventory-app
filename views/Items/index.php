<div class="container-fluid py-4">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0">Master Product</h1>
                <p class="mb-0 opacity-75">Manage your product information and details</p>
            </div>
        </div>
    </div>
    <br />
    <?php if (isset($_GET['success'])) { ?>
        <div class="alert alert-success">
            <p>Success !</p>
        </div>
    <?php } ?>
    <?php if (isset($_GET['remove'])) { ?>
        <div class="alert alert-danger">
            <p>Failed !</p>
        </div>
    <?php } ?>
    <div class="search-section">
        <h5 class="mb-3">Search & Filter</h5>
        <form id="searchForm">
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Product Name</label>
                    <input type="text" class="form-control form-control-modern" name="filter_name" placeholder="Enter Product Name">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Type</label>
                    <select class="form-control form-control-modern select2" name="filter_type" id="filter_type">
                        <option value="All">All</option>
                        <option value="Sport">Sport</option>
                        <option value="Matic">Matic</option>
                        <option value="Manual">Manual</option>
                    </select>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-primary-modern btn-modern" type="button" onclick="searchItem();">
                    <i class="fas fa-search me-2"></i> Search
                </button> &nbsp;
                <button class="btn btn-outline-secondary btn-modern" type="button" onclick="clearRequest();">
                    <i class="fas fa-tras<h me-2"></i> Clear Filters
                </button> &nbsp;
                <button type="button" class="btn btn-primary-modern btn-md mr-2" data-toggle="modal" data-target="#myModal">
                    <i class="fa fa-plus"></i> Add Multiple Prodcut</button>
                <a type="button" href="index.php?route=item/addSingleItem" class="btn btn-primary-modern btn-md mr-2">
                    <i class="fa fa-plus"></i> Add Single Prodcut</a>
        </form>
    </div>

</div>
<br>

<div class="card card-body">
    <div class="table-responsive">
        <table class="table dt-tbl table-modern" id="tbItems">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Product Name</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Qty</th>
                    <th>Buy Pice</th>
                    <th>Sales Pice</th>
                </tr>
            </thead>
            <tbody id="tableItem">

            </tbody>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 8px; border: none; max-height: 90vh; margin: 5vh auto;">
            <div class="modal-header" style="border-bottom: 1px solid #eaeaea; padding: 20px 30px; background: #fff;">
                <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                    <h5 class="modal-title" style="margin: 0; font-size: 1.5rem; font-weight: 600; color: #1a1a1a;">Add Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" style="background: none; border: none; font-size: 1.8rem; color: #666; cursor: pointer; padding: 0; margin: 0; line-height: 1;">
                        &times;
                    </button>
                </div>
            </div>

            <form action="import.php" method="post" enctype="multipart/form-data">
                <div class="modal-body" style="padding: 40px; max-height: calc(90vh - 160px); overflow-y: auto;">
                    <div style="max-width: 600px; width: 100%; margin: 0 auto;">
                        <div style="background: white; border-radius: 8px; padding: 40px;">
                            <div style="margin-bottom: 30px; text-align: center;">
                                <div style="width: 60px; height: 60px; background: #f0f7ff; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#0066cc" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                </div>
                                <h3 style="margin: 0 0 10px 0; font-size: 1.8rem; font-weight: 600; color: #1a1a1a;">Import Supplier Data</h3>
                                <p style="margin: 0; color: #666; font-size: 1rem;">Upload an Excel file containing supplier information</p>
                            </div>

                            <div style="margin-bottom: 30px;">
                                <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Excel File</label>
                                <div style="border: 2px dashed #d1d5db; border-radius: 8px; padding: 40px 20px; text-align: center; background: #fafafa; transition: all 0.3s; cursor: pointer;"
                                    onclick="document.getElementById('fileInput').click()"
                                    ondrop="handleDrop(event)"
                                    ondragover="handleDragOver(event)"
                                    ondragleave="handleDragLeave(event)">
                                    <div style="margin-bottom: 15px;">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="1.5">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <polyline points="16 13 10 13 10 19"></polyline>
                                            <polyline points="13 16 10 13 13 10"></polyline>
                                        </svg>
                                    </div>
                                    <p style="margin: 0 0 10px 0; color: #666; font-size: 1rem;">Drag & drop your Excel file here or</p>
                                    <span style="display: inline-block; padding: 10px 20px; background: #0066cc; color: white; border-radius: 4px; font-weight: 500; font-size: 0.95rem; transition: background 0.3s; cursor: pointer;">Browse Files</span>
                                    <input type="file" id="fileInput" class="form-control" name="file" accept=".xls,.xlsx" required style="display: none;" onchange="updateFileName(this)">
                                    <p id="fileName" style="margin: 10px 0 0 0; color: #666; font-size: 0.9rem; font-style: italic;">No file chosen</p>
                                </div>
                            </div>

                            <div style="border-top: 1px solid #eaeaea; padding-top: 30px; text-align: center;">
                                <p style="margin: 0 0 20px 0; color: #666; font-size: 0.95rem;">Need help with the format? Download our template</p>
                                <a href="template/template_import.xlsx" class="btn btn-cxs btn-primary" download
                                    style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: #f0f7ff; color: #0066cc; text-decoration: none; border-radius: 4px; font-weight: 500; transition: all 0.3s; border: 1px solid #d1e7ff;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                    Download Template
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #eaeaea; padding: 20px 30px; background: #f8f9fa; border-radius: 0 0 8px 8px;">
                    <div style="display: flex; justify-content: flex-end; gap: 15px; width: 100%;">
                        <button type="button" class="btn btn-default" data-dismiss="modal"
                            style="padding: 10px 24px; background: white; color: #666; border: 1px solid #d1d5db; border-radius: 4px; font-weight: 500; cursor: pointer; transition: all 0.3s;">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn"
                            style="padding: 10px 24px; background: #0066cc; color: white; border: none; border-radius: 4px; font-weight: 500; cursor: pointer; transition: background 0.3s;">
                            Import File
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="script/item.js"></script>
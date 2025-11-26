<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Date Range Filter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 2rem;
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
        }

        .btn-primary-modern:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.3);
        }

        .btn-outline-secondary {
            border: 1px solid #6c757d;
            color: #6c757d;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: white;
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

        .date-range-group {
            display: flex;
            gap: 1rem;
            align-items: end;
        }

        .date-input-group {
            flex: 1;
        }

        .date-separator {
            display: flex;
            align-items: center;
            padding-bottom: 0.5rem;
            color: #6c757d;
            font-weight: 500;
        }

        .quick-date-options {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
        }

        .quick-date-btn {
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            background: white;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .quick-date-btn:hover {
            background-color: #e9ecef;
        }

        .quick-date-btn.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .filter-row {
                grid-template-columns: 1fr;
            }

            .date-range-group {
                flex-direction: column;
                gap: 0.5rem;
            }

            .date-separator {
                padding: 0;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="page-header">
            <h1 class="h3 mb-0">Advanced Search Filters</h1>
            <p class="mb-0 opacity-75">Filter your data with multiple criteria including date ranges</p>
        </div>

        <div class="search-section">
            <h5 class="mb-3">Search & Filter</h5>
            <form id="searchForm">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Request Number</label>
                        <input type="text" class="form-control form-control-modern" name="filter_number" placeholder="Enter request number">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Requestor Name</label>
                        <input type="text" class="form-control form-control-modern" name="filter_requestor" placeholder="Enter requestor name">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label">Supplier Status</label>
                        <select class="form-control form-control-modern select2" name="filter_status">
                            <option value="0">All Statuses</option>
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                            <option value="3">Pending</option>
                            <option value="4">Approved</option>
                        </select>
                    </div>
                </div>

                <!-- Date Range Filter -->
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Date Range</label>
                        <div class="date-range-group">
                            <div class="date-input-group">
                                <label class="form-label small text-muted">From Date</label>
                                <input type="date" class="form-control form-control-modern" name="filter_date_from" id="filterDateFrom">
                            </div>
                            <div class="date-separator">to</div>
                            <div class="date-input-group">
                                <label class="form-label small text-muted">To Date</label>
                                <input type="date" class="form-control form-control-modern" name="filter_date_to" id="filterDateTo">
                            </div>
                        </div>

                        <!-- Quick Date Options -->
                        <div class="quick-date-options">
                            <span class="small text-muted me-2">Quick select:</span>
                            <button type="button" class="quick-date-btn" data-days="7">Last 7 Days</button>
                            <button type="button" class="quick-date-btn" data-days="30">Last 30 Days</button>
                            <button type="button" class="quick-date-btn" data-days="90">Last 3 Months</button>
                            <button type="button" class="quick-date-btn" data-month="0">This Month</button>
                            <button type="button" class="quick-date-btn" data-clear="true">Clear Dates</button>
                        </div>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Request Type</label>
                        <select class="form-control form-control-modern select2" name="filter_type">
                            <option value="0">All Types</option>
                            <option value="1">Purchase Request</option>
                            <option value="2">Quotation Request</option>
                            <option value="3">Information Request</option>
                            <option value="4">Support Request</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Priority</label>
                        <select class="form-control form-control-modern select2" name="filter_priority">
                            <option value="0">All Priorities</option>
                            <option value="1">Low</option>
                            <option value="2">Medium</option>
                            <option value="3">High</option>
                            <option value="4">Urgent</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-primary-modern btn-modern" type="button" onclick="searchData()">
                        <i class="fas fa-search me-2"></i> Search Data
                    </button>
                    <button class="btn btn-outline-secondary btn-modern" type="button" onclick="clearFilters()">
                        <i class="fas fa-trash me-2"></i> Clear Filters
                    </button>
                </div>
            </form>
        </div>

        <div class="card-modern">
            <div class="card-body">
                <h5 class="card-title">Search Results</h5>
                <p class="text-muted">Your filtered results will appear here.</p>
                <div id="searchResults" class="p-3 bg-light rounded">
                    <p class="text-center text-muted mb-0">No search performed yet. Use the filters above to find data.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                width: '100%',
                placeholder: "Select an option",
                allowClear: true
            });

            // Set today's date as default for "To" date
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('filterDateTo').value = today;

            // Set default "From" date to 30 days ago
            const thirtyDaysAgo = new Date();
            thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
            document.getElementById('filterDateFrom').value = thirtyDaysAgo.toISOString().split('T')[0];

            // Quick date selection functionality
            document.querySelectorAll('.quick-date-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    document.querySelectorAll('.quick-date-btn').forEach(btn => {
                        btn.classList.remove('active');
                    });

                    // Add active class to clicked button
                    this.classList.add('active');

                    const today = new Date();
                    const fromDate = new Date();

                    if (this.dataset.days) {
                        fromDate.setDate(today.getDate() - parseInt(this.dataset.days));
                    } else if (this.dataset.month === "0") {
                        // First day of current month
                        fromDate.setDate(1);
                    } else if (this.dataset.clear) {
                        document.getElementById('filterDateFrom').value = '';
                        document.getElementById('filterDateTo').value = '';
                        return;
                    }

                    document.getElementById('filterDateFrom').value = fromDate.toISOString().split('T')[0];
                    document.getElementById('filterDateTo').value = today.toISOString().split('T')[0];
                });
            });
        });

        function searchData() {
            // Get form values
            const formData = new FormData(document.getElementById('searchForm'));
            const filters = {};

            for (let [key, value] of formData.entries()) {
                if (value) filters[key] = value;
            }

            // In a real application, you would make an AJAX call here
            // For demo purposes, we'll just display the filter values
            let resultsHTML = '<h6>Applied Filters:</h6><ul class="list-group list-group-flush">';

            if (filters.filter_number) {
                resultsHTML += `<li class="list-group-item"><strong>Request Number:</strong> ${filters.filter_number}</li>`;
            }

            if (filters.filter_requestor) {
                resultsHTML += `<li class="list-group-item"><strong>Requestor Name:</strong> ${filters.filter_requestor}</li>`;
            }

            if (filters.filter_status && filters.filter_status !== '0') {
                const statusText = document.querySelector(`select[name="filter_status"] option[value="${filters.filter_status}"]`).textContent;
                resultsHTML += `<li class="list-group-item"><strong>Status:</strong> ${statusText}</li>`;
            }

            if (filters.filter_date_from || filters.filter_date_to) {
                resultsHTML += `<li class="list-group-item"><strong>Date Range:</strong> ${filters.filter_date_from || 'Any'} to ${filters.filter_date_to || 'Any'}</li>`;
            }

            if (filters.filter_type && filters.filter_type !== '0') {
                const typeText = document.querySelector(`select[name="filter_type"] option[value="${filters.filter_type}"]`).textContent;
                resultsHTML += `<li class="list-group-item"><strong>Request Type:</strong> ${typeText}</li>`;
            }

            if (filters.filter_priority && filters.filter_priority !== '0') {
                const priorityText = document.querySelector(`select[name="filter_priority"] option[value="${filters.filter_priority}"]`).textContent;
                resultsHTML += `<li class="list-group-item"><strong>Priority:</strong> ${priorityText}</li>`;
            }

            resultsHTML += '</ul>';

            document.getElementById('searchResults').innerHTML = resultsHTML;

            // Show success message
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show mt-3';
            alert.innerHTML = `
                <i class="fas fa-check-circle me-2"></i> Search completed successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.getElementById('searchResults').prepend(alert);
        }

        function clearFilters() {
            document.getElementById('searchForm').reset();
            $('.select2').val('').trigger('change');

            // Clear quick date buttons active state
            document.querySelectorAll('.quick-date-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            document.getElementById('searchResults').innerHTML =
                '<p class="text-center text-muted mb-0">No search performed yet. Use the filters above to find data.</p>';
        }
    </script>
</body>

</html>
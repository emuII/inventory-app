<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Badge System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
        }

        .card-modern {
            border: none;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-block;
            margin: 0.25rem;
        }

        /* Approval Statuses */
        .status-approve {
            background-color: rgba(40, 167, 69, 0.15);
            color: #1e7e34;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .status-reject {
            background-color: rgba(220, 53, 69, 0.15);
            color: #c82333;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .status-cancel {
            background-color: rgba(108, 117, 125, 0.15);
            color: #6c757d;
            border: 1px solid rgba(108, 117, 125, 0.3);
        }

        /* Activity Statuses */
        .status-active {
            background-color: rgba(76, 201, 240, 0.15);
            color: #0a7ea4;
            border: 1px solid rgba(76, 201, 240, 0.3);
        }

        .status-nonactive {
            background-color: rgba(108, 117, 125, 0.15);
            color: #6c757d;
            border: 1px solid rgba(108, 117, 125, 0.3);
        }

        /* Process Statuses */
        .status-pending {
            background-color: rgba(255, 193, 7, 0.15);
            color: #e0a800;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .status-process {
            background-color: rgba(23, 162, 184, 0.15);
            color: #138496;
            border: 1px solid rgba(23, 162, 184, 0.3);
        }

        .status-complete {
            background-color: rgba(40, 167, 69, 0.15);
            color: #1e7e34;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        /* Additional Statuses for Comprehensive System */
        .status-draft {
            background-color: rgba(108, 117, 125, 0.15);
            color: #6c757d;
            border: 1px solid rgba(108, 117, 125, 0.3);
        }

        .status-review {
            background-color: rgba(255, 193, 7, 0.15);
            color: #e0a800;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .status-hold {
            background-color: rgba(253, 126, 20, 0.15);
            color: #d35400;
            border: 1px solid rgba(253, 126, 20, 0.3);
        }

        .status-expired {
            background-color: rgba(108, 117, 125, 0.15);
            color: #6c757d;
            border: 1px solid rgba(108, 117, 125, 0.3);
        }

        .status-archived {
            background-color: rgba(108, 117, 125, 0.15);
            color: #6c757d;
            border: 1px solid rgba(108, 117, 125, 0.3);
        }

        .status-urgent {
            background-color: rgba(220, 53, 69, 0.15);
            color: #c82333;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .status-info {
            background-color: rgba(23, 162, 184, 0.15);
            color: #138496;
            border: 1px solid rgba(23, 162, 184, 0.3);
        }

        .status-warning {
            background-color: rgba(255, 193, 7, 0.15);
            color: #e0a800;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .status-success {
            background-color: rgba(40, 167, 69, 0.15);
            color: #1e7e34;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .status-error {
            background-color: rgba(220, 53, 69, 0.15);
            color: #c82333;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .badge-demo {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 1rem;
        }

        .status-group {
            margin-bottom: 2rem;
        }

        .status-group h4 {
            margin-bottom: 1rem;
            color: #495057;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 0.5rem;
        }

        .usage-example {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .code-block {
            background-color: #2d3748;
            color: #e2e8f0;
            padding: 1rem;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            margin: 1rem 0;
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="page-header">
            <h1 class="h3 mb-0">Complete Status Badge System</h1>
            <p class="mb-0 opacity-75">A comprehensive collection of status indicators for your application</p>
        </div>

        <div class="card-modern">
            <div class="card-body">
                <h3 class="card-title">Status Badge Collection</h3>
                <p class="text-muted">Use these badges to indicate various states and statuses throughout your application.</p>

                <div class="status-group">
                    <h4>Approval Statuses</h4>
                    <div class="badge-demo">
                        <span class="status-badge status-approve">Approve</span>
                        <span class="status-badge status-reject">Reject</span>
                        <span class="status-badge status-cancel">Cancel</span>
                    </div>
                </div>

                <div class="status-group">
                    <h4>Activity Statuses</h4>
                    <div class="badge-demo">
                        <span class="status-badge status-active">Active</span>
                        <span class="status-badge status-nonactive">Non Active</span>
                    </div>
                </div>

                <div class="status-group">
                    <h4>Process Statuses</h4>
                    <div class="badge-demo">
                        <span class="status-badge status-pending">Pending</span>
                        <span class="status-badge status-process">Process</span>
                        <span class="status-badge status-complete">Complete</span>
                    </div>
                </div>

                <div class="status-group">
                    <h4>Additional Statuses</h4>
                    <div class="badge-demo">
                        <span class="status-badge status-draft">Draft</span>
                        <span class="status-badge status-review">Review</span>
                        <span class="status-badge status-hold">Hold</span>
                        <span class="status-badge status-expired">Expired</span>
                        <span class="status-badge status-archived">Archived</span>
                        <span class="status-badge status-urgent">Urgent</span>
                        <span class="status-badge status-info">Info</span>
                        <span class="status-badge status-warning">Warning</span>
                        <span class="status-badge status-success">Success</span>
                        <span class="status-badge status-error">Error</span>
                    </div>
                </div>

                <div class="usage-example">
                    <h4>Usage Example</h4>
                    <p>Here's how you can use these status badges in a table:</p>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Item</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>001</td>
                                    <td>Quarterly Report</td>
                                    <td><span class="status-badge status-complete">Complete</span></td>
                                    <td>2023-10-15</td>
                                </tr>
                                <tr>
                                    <td>002</td>
                                    <td>Marketing Campaign</td>
                                    <td><span class="status-badge status-process">Process</span></td>
                                    <td>2023-10-18</td>
                                </tr>
                                <tr>
                                    <td>003</td>
                                    <td>Budget Approval</td>
                                    <td><span class="status-badge status-pending">Pending</span></td>
                                    <td>2023-10-20</td>
                                </tr>
                                <tr>
                                    <td>004</td>
                                    <td>Vendor Contract</td>
                                    <td><span class="status-badge status-approve">Approve</span></td>
                                    <td>2023-10-22</td>
                                </tr>
                                <tr>
                                    <td>005</td>
                                    <td>Product Launch</td>
                                    <td><span class="status-badge status-hold">Hold</span></td>
                                    <td>2023-10-25</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h5 class="mt-4">Implementation Code</h5>
                    <div class="code-block">
                        &lt;span class="status-badge status-approve"&gt;Approve&lt;/span&gt;<br>
                        &lt;span class="status-badge status-reject"&gt;Reject&lt;/span&gt;<br>
                        &lt;span class="status-badge status-active"&gt;Active&lt;/span&gt;<br>
                        &lt;span class="status-badge status-pending"&gt;Pending&lt;/span&gt;<br>
                        &lt;span class="status-badge status-complete"&gt;Complete&lt;/span&gt;
                    </div>
                </div>
            </div>
        </div>

        <div class="card-modern">
            <div class="card-body">
                <h3 class="card-title">CSS Classes Reference</h3>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Approval Statuses</h5>
                        <ul>
                            <li><code>.status-approve</code> - For approved items</li>
                            <li><code>.status-reject</code> - For rejected items</li>
                            <li><code>.status-cancel</code> - For cancelled items</li>
                        </ul>

                        <h5>Activity Statuses</h5>
                        <ul>
                            <li><code>.status-active</code> - For active items</li>
                            <li><code>.status-nonactive</code> - For inactive items</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Process Statuses</h5>
                        <ul>
                            <li><code>.status-pending</code> - For pending items</li>
                            <li><code>.status-process</code> - For items in process</li>
                            <li><code>.status-complete</code> - For completed items</li>
                        </ul>

                        <h5>Additional Statuses</h5>
                        <ul>
                            <li><code>.status-draft</code> - For draft items</li>
                            <li><code>.status-review</code> - For items under review</li>
                            <li><code>.status-hold</code> - For items on hold</li>
                            <li><code>.status-urgent</code> - For urgent items</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
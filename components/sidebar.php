<!--sidebar start-->
<?php
$id = $_SESSION['active_login']['id'];
$profile = $user_model->GetUserById($id);
$user_role = $_SESSION['active_login']['role'] ?? 'requestor';
?>
<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-boxes"></i>
        </div>
        <div class="sidebar-brand-text mx-3">
            IMS
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="index.php?route=Dashboards">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <hr class="sidebar-divider">
    <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fas fa-fw fa-database"></i>
            <span>Data Master</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="index.php?route=suppliers">Supplier</a>
                <a class="collapse-item" href="index.php?route=items">Items</a>
            </div>
        </div>
    </li>

    <!-- Transaction (Super Admin & Requestor) -->
    <?php if (in_array($user_role, ['super_admin', 'requestor'])): ?>
        <li class="nav-item active">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true"
                aria-controls="collapseFour">
                <i class="fas fa-fw fa-shopping-cart"></i>
                <span>Transaction</span>
            </a>
            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="index.php?route=purchase">Purchase Request</a>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <!-- Orders -->
    <?php if (in_array($user_role, ['super_admin', 'approval', 'requestor'])): ?>
        <li class="nav-item active">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFive" aria-expanded="true"
                aria-controls="collapseFive">
                <i class="fas fa-fw fa-database"></i>
                <span>Orders</span>
            </a>
            <div id="collapseFive" class="collapse" aria-labelledby="headingFour" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <?php if (in_array($user_role, ['super_admin', 'requestor'])): ?>
                        <a class="collapse-item" href="index.php?route=myrequest">My Request</a>
                    <?php endif; ?>

                    <?php if (in_array($user_role, ['super_admin', 'approval'])): ?>
                        <a class="collapse-item" href="index.php?route=myapproval">My Approval</a>
                    <?php endif; ?>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <!-- Inventory (Super Admin & Warehouse) -->
    <?php if (in_array($user_role, ['super_admin', 'warehouse'])): ?>
        <li class="nav-item active">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSix" aria-expanded="true"
                aria-controls="collapseSix">
                <i class="fas fa-fw fa-warehouse"></i>
                <span>Inventory</span>
            </a>
            <div id="collapseSix" class="collapse" aria-labelledby="headingFour" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="index.php?route=Warehouses">Warehouse</a>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <!-- Delivery Order (Super Admin & Casier) -->
    <?php if (in_array($user_role, ['super_admin', 'casier'])): ?>
        <li class="nav-item active">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="true"
                aria-controls="collapseSix">
                <i class="fas fa-fw fa-cash-register"></i>
                <span>Delivery Order</span>
            </a>
            <div id="collapseSeven" class="collapse" aria-labelledby="headingFour" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="index.php?route=Pos">Point of Sale</a>
                    <a class="collapse-item" href="index.php?route=DeliveryOrderLogs">Delivery Order Log</a>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <?php if (in_array($user_role, ['super_admin'])): ?>
        <li class="nav-item active">
            <a class="nav-link" href="index.php?route=UserManagements">
                <i class="fas fa-fw fa-user"></i>
                <span>Management User</span></a>
        </li>
    <?php endif; ?>

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->
<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Role Badge -->
            <div class="d-flex align-items-center">
                <h5 class="d-lg-block d-none mt-2 mr-3"></h5>
                <span class="badge badge-primary">
                    <i class="fas fa-user-tag mr-1"></i>
                    <?php
                    $role_names = [
                        'super_admin' => 'Super Admin',
                        'approval' => 'Approval',
                        'warehouse' => 'Warehouse',
                        'casier' => 'Cashier',
                        'requestor' => 'Requestor'
                    ];
                    echo $role_names[$user_role] ?? ucfirst($user_role);
                    ?>
                </span>
            </div>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <img class="img-profile rounded-circle"
                            src="public/asset/img/ui-sam.jpg" <?php echo $profile['full_name']; ?>>
                        <span
                            class="mr-2 d-none d-lg-inline text-gray-600 small ml-2"><?php echo $profile['full_name']; ?></span>
                        <i class="fas fa-angle-down"></i>
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="index.php?route=profile/viewProfile">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                    </div>
                </li>

            </ul>
        </nav>
        <!-- End of Topbar -->
        <!-- Begin Page Content -->
        <div class="container-fluid">
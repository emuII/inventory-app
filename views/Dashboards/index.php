<?php
require_once 'helpers/format_helper.php';
$constHeader = $dashboard->GetDashboardHeader();
$Revenue = $dashboard->GetTodayRevenue();
$Revenue['today_revenue'] = formatDashboardCurrency($Revenue['today_revenue']);
$systemAlerts = $alertModel->getSystemAlerts();
$totalAlerts = count($systemAlerts);
$role = $_SESSION['active_login']['role'] ?? '';
$isSuperAdmin = ($role === 'super_admin');
?>

<link rel="stylesheet" href="public/custom/custom-dashboard.css">
<div class="dashboard-container">

    <div class="dashboard-header">
        <h1>Dashboard Overview</h1>
        <p>Real-time monitoring of your inventory management system</p>
    </div>

    <!-- QUICK STATS -->
    <div class="quick-stats-grid">

        <!-- Total Items (always visible) -->
        <div class="stat-card total-items">
            <div class="stat-icon total-items"><i class="fas fa-boxes"></i></div>
            <div class="stat-content">
                <h3><?= $constHeader['total_items'] ?></h3>
                <p>Total Items</p>
            </div>
        </div>

        <!-- Low Stock (always visible) -->
        <div class="stat-card low-stock">
            <div class="stat-icon low-stock"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-content">
                <h3><?= $constHeader['low_stock_items'] ?></h3>
                <p>Low Stock Items</p>
            </div>
        </div>

        <?php if ($isSuperAdmin): ?>

            <!-- Pending Orders -->
            <div class="stat-card pending-orders">
                <div class="stat-icon pending-orders"><i class="fas fa-clipboard-list"></i></div>
                <div class="stat-content">
                    <h3><?= $constHeader['pending_orders'] ?></h3>
                    <p>Pending Orders</p>
                    <small style="color:#1cc88a;">
                        <?= $constHeader['urgent_pending_orders'] ?> urgent
                    </small>
                </div>
            </div>

            <!-- Today Revenue -->
            <div class="stat-card today-revenue">
                <div class="stat-icon today-revenue"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stat-content">
                    <h3><?= $Revenue['today_revenue'] ?></h3>
                    <p>Today Revenue</p>
                </div>
            </div>

        <?php endif; ?>
    </div>

    <!-- DASHBOARD GRID -->
    <div class="dashboard-grid">

        <!-- Stock Overview (always visible) -->
        <div class="dashboard-card">
            <div class="card-header">
                <span><i class="fas fa-chart-pie"></i> Stock Overview</span>
            </div>
            <div class="card-body">
                <div class="stock-levels">

                    <div class="stock-item">
                        <div class="stock-header">
                            <span>Optimal Stock</span>
                            <span><?= $constHeader['optimal_stock_percent'] ?>%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill progress-optimal"
                                style="width:<?= $constHeader['optimal_stock_percent'] ?>%"></div>
                        </div>
                        <small><?= $constHeader['optimal_stock_items'] ?> items</small>
                    </div>

                    <div class="stock-item">
                        <div class="stock-header">
                            <span>Low Stock</span>
                            <span><?= $constHeader['low_stock_percent'] ?>%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill progress-low"
                                style="width:<?= $constHeader['low_stock_percent'] ?>%"></div>
                        </div>
                        <small><?= $constHeader['low_stock_items'] ?> items</small>
                    </div>

                    <div class="stock-item">
                        <div class="stock-header">
                            <span>Out of Stock</span>
                            <span style="color:#e74a3b;">
                                <?= $constHeader['out_of_stock_percent'] ?>%
                            </span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill progress-out"
                                style="width:<?= $constHeader['out_of_stock_percent'] ?>%"></div>
                        </div>
                        <small><?= $constHeader['out_of_stock_items'] ?> items</small>
                    </div>

                </div>
            </div>
        </div>

        <?php if ($isSuperAdmin): ?>
            <!-- System Alerts -->
            <div class="dashboard-card">
                <div class="card-header">
                    <span><i class="fas fa-exclamation-triangle"></i> System Alerts</span>
                    <?php if ($totalAlerts > 0): ?>
                        <span style="background:#e74a3b;color:#fff;padding:3px 8px;border-radius:20px;">
                            <?= $totalAlerts ?>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php foreach ($systemAlerts as $alert): ?>
                        <div class="alert-item <?= $alert['type'] ?>">
                            <div class="alert-icon <?= $alert['type'] ?>">
                                <i class="<?= $alert['icon'] ?>"></i>
                            </div>
                            <div class="alert-content">
                                <h4><?= htmlspecialchars($alert['title']) ?></h4>
                                <p><?= htmlspecialchars($alert['description']) ?></p>
                                <div class="alert-time"><?= $alert['time'] ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<script>
    function handleAlertAction(action) {
        switch (action) {
            case 'view_low_stock':
                window.location.href = 'index.php?route=items';
                break;
            case 'view_critical_stock':
                window.location.href = 'index.php?route=items';
                break;
            case 'view_pending_approvals':
                window.location.href = 'index.php?route=myapproval';
                break;
            case 'view_all_pending':
                window.location.href = 'index.php?route=myrequest';
                break;
            case 'view_expiring_items':
                window.location.href = 'index.php?route=items&filter=expiring';
                break;
            case 'download_daily_report':
                window.location.href = 'export/excel/daily_report.php';
                break;
            default:
                console.log('Alert action:', action);
        }
    }

    // Auto-refresh alerts setiap 30 detik
    setInterval(function() {
        fetch('middleware/ajax_handler.php?controller=dashboard&action=getAlerts')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update alert count
                    const alertCount = document.querySelector('.card-header span:last-child');
                    if (alertCount) {
                        alertCount.textContent = data.total_alerts;
                    }

                    // Jika ada alert baru, bisa tambahkan notifikasi
                    if (data.total_alerts > 0) {
                        // Optional: Show notification
                    }
                }
            });
    }, 30000); // 30 detik
</script>
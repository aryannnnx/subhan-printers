<?php
// ============================================
// ADMIN: Dashboard - Subhan Printers
// ============================================

session_start();

// Check admin login
if (!isset($_SESSION['admin_id'])) {
    header('Location: /SP/admin/login.php');
    exit;
}

// Load required files
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Quote.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Newsletter.php';
require_once __DIR__ . '/../includes/functions.php';

// Get data
$orderModel = new Order();
$quoteModel = new Quote();
$userModel = new User();
$productModel = new Product();
$newsletterModel = new Newsletter();

// Stats
$totalOrders = count($orderModel->getAll());
$totalQuotes = count($quoteModel->getAll());
$totalUsers = count($userModel->getAll());
$totalProducts = count($productModel->getAll());
$totalSubscribers = $newsletterModel->countActive();

// Order stats by status
$orderStatuses = [
    'pending' => $orderModel->getCountByStatus('pending'),
    'approved' => $orderModel->getCountByStatus('approved'),
    'in_production' => $orderModel->getCountByStatus('in_production'),
    'ready' => $orderModel->getCountByStatus('ready'),
    'delivered' => $orderModel->getCountByStatus('delivered'),
    'cancelled' => $orderModel->getCountByStatus('cancelled'),
];

// Revenue
$revenue = 0;
$orders = $orderModel->getAll(['status' => 'delivered']);
foreach ($orders as $order) {
    $revenue += (float)($order['total'] ?? 0);
}

// Recent orders (last 10)
$recentOrders = $orderModel->getAll([], 10, 0);

// Recent quotes
$recentQuotes = $quoteModel->getAll([], 5, 0);

// Recent users
$recentUsers = $userModel->getAll([], 5, 0);

// Quote status counts
$quoteStatuses = [
    'pending' => $quoteModel->getCountByStatus('pending'),
    'quoted' => $quoteModel->getCountByStatus('quoted'),
    'converted' => $quoteModel->getCountByStatus('converted'),
    'lost' => $quoteModel->getCountByStatus('lost'),
];

// Monthly orders for chart (last 6 months)
$monthlyOrders = [];
for ($i = 5; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $monthName = date('M', strtotime("-$i months"));
    $count = 0;
    foreach ($orders as $order) {
        if (date('Y-m', strtotime($order['created_at'])) === $month) {
            $count++;
        }
    }
    $monthlyOrders[] = ['month' => $monthName, 'count' => $count];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Admin Panel</title>
    <link rel="icon" href="/SP/images/logo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* ── Base ── */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: #0a0a14;
            color: #e8e8f0;
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 260px;
            background: rgba(18, 18, 31, 0.98);
            border-right: 1px solid rgba(255,255,255,0.04);
            padding: 24px 16px;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
            overflow-y: auto;
            transition: 0.3s ease;
        }
        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: #8b5cf6; border-radius: 4px; }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            margin-bottom: 20px;
        }
        .sidebar-brand img { width: 40px; height: 40px; object-fit: contain; }
        .sidebar-brand h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
        }
        .sidebar-brand h2 span { color: #8b5cf6; }
        .sidebar-brand small {
            display: block;
            font-size: 0.6rem;
            color: #555577;
            font-weight: 400;
            font-family: 'DM Sans', sans-serif;
        }

        .sidebar-menu { list-style: none; }
        .sidebar-menu li { margin-bottom: 2px; }
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 10px;
            color: #8888aa;
            text-decoration: none;
            transition: 0.3s ease;
            font-size: 0.88rem;
            font-weight: 500;
        }
        .sidebar-menu a i { width: 20px; text-align: center; font-size: 1rem; }
        .sidebar-menu a:hover { background: rgba(139,92,246,0.08); color: #e8e8f0; }
        .sidebar-menu a.active {
            background: linear-gradient(135deg, rgba(139,92,246,0.15), rgba(109,40,217,0.08));
            color: #8b5cf6;
            border: 1px solid rgba(139,92,246,0.15);
        }
        .sidebar-menu .section-title {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #444466;
            padding: 16px 14px 8px;
            font-weight: 700;
        }

        .sidebar-logout {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.04);
        }
        .sidebar-logout a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 10px;
            color: #ef4444;
            text-decoration: none;
            transition: 0.3s ease;
            font-size: 0.88rem;
            font-weight: 500;
        }
        .sidebar-logout a:hover { background: rgba(239,68,68,0.1); }

        /* ── Main Content ── */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 24px 32px 60px;
            min-height: 100vh;
        }

        /* ── Header ── */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 24px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            margin-bottom: 28px;
        }
        .top-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 800;
        }
        .top-header h1 span { color: #8b5cf6; }
        .top-header .admin-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .top-header .admin-info .avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            color: #fff;
        }
        .top-header .admin-info .name {
            font-weight: 600;
            font-size: 0.9rem;
        }
        .top-header .admin-info .role {
            font-size: 0.75rem;
            color: #555577;
        }

        /* ── Stats Grid ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }
        .stat-card {
            background: rgba(18, 18, 31, 0.8);
            border: 1px solid rgba(255,255,255,0.04);
            border-radius: 16px;
            padding: 20px 24px;
            transition: 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            border-color: rgba(139,92,246,0.2);
            box-shadow: 0 12px 40px rgba(0,0,0,0.3);
        }
        .stat-card .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 12px;
        }
        .stat-card .stat-value {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: #e8e8f0;
        }
        .stat-card .stat-label {
            color: #8888aa;
            font-size: 0.82rem;
            margin-top: 2px;
        }
        .stat-card .stat-change {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 100px;
            margin-top: 8px;
        }
        .stat-change.up { color: #22c55e; background: rgba(34,197,94,0.1); }
        .stat-change.down { color: #ef4444; background: rgba(239,68,68,0.1); }
        .stat-change.neutral { color: #f59e0b; background: rgba(245,158,11,0.1); }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            opacity: 0.05;
        }
        .stat-card:nth-child(1)::after { background: #8b5cf6; top: -20px; right: -20px; width: 80px; height: 80px; }
        .stat-card:nth-child(2)::after { background: #f59e0b; top: -20px; right: -20px; width: 80px; height: 80px; }
        .stat-card:nth-child(3)::after { background: #22c55e; top: -20px; right: -20px; width: 80px; height: 80px; }
        .stat-card:nth-child(4)::after { background: #3b82f6; top: -20px; right: -20px; width: 80px; height: 80px; }
        .stat-card:nth-child(5)::after { background: #ec4899; top: -20px; right: -20px; width: 80px; height: 80px; }

        /* ── Charts Row ── */
        .charts-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 28px;
        }
        .chart-card {
            background: rgba(18, 18, 31, 0.8);
            border: 1px solid rgba(255,255,255,0.04);
            border-radius: 16px;
            padding: 24px;
        }
        .chart-card h3 {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .chart-card h3 i { color: #8b5cf6; }
        .chart-card canvas { max-height: 220px; }

        /* ── Activity Grid ── */
        .activity-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .activity-card {
            background: rgba(18, 18, 31, 0.8);
            border: 1px solid rgba(255,255,255,0.04);
            border-radius: 16px;
            padding: 24px;
        }
        .activity-card h3 {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .activity-card h3 i { color: #8b5cf6; }
        .activity-card h3 .badge {
            margin-left: auto;
            background: rgba(139,92,246,0.15);
            color: #8b5cf6;
            padding: 2px 12px;
            border-radius: 100px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .activity-item {
            display: flex;
            gap: 14px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            align-items: center;
        }
        .activity-item:last-child { border-bottom: none; }
        .activity-item .icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        .activity-item .content { flex: 1; }
        .activity-item .content .title {
            font-weight: 600;
            font-size: 0.85rem;
            color: #e8e8f0;
        }
        .activity-item .content .meta {
            font-size: 0.75rem;
            color: #555577;
        }
        .activity-item .status {
            font-size: 0.65rem;
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 100px;
            text-transform: uppercase;
        }

        /* ── Status Colors ── */
        .status-pending { background: rgba(245,158,11,0.15); color: #f59e0b; }
        .status-approved { background: rgba(139,92,246,0.15); color: #8b5cf6; }
        .status-in_production { background: rgba(59,130,246,0.15); color: #3b82f6; }
        .status-ready { background: rgba(34,197,94,0.15); color: #22c55e; }
        .status-delivered { background: rgba(34,197,94,0.2); color: #22c55e; }
        .status-cancelled { background: rgba(239,68,68,0.15); color: #ef4444; }
        .status-quoted { background: rgba(139,92,246,0.15); color: #8b5cf6; }
        .status-converted { background: rgba(34,197,94,0.15); color: #22c55e; }
        .status-lost { background: rgba(239,68,68,0.15); color: #ef4444; }

        /* ── Responsive ── */
        @media (max-width: 1200px) {
            .stats-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 992px) {
            .sidebar { width: 72px; padding: 16px 8px; }
            .sidebar-brand h2, .sidebar-brand small, .sidebar-menu a span, .sidebar-menu .section-title, .sidebar-logout a span { display: none; }
            .sidebar-menu a { justify-content: center; padding: 12px; }
            .sidebar-logout a { justify-content: center; padding: 12px; }
            .main-content { margin-left: 72px; padding: 16px; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .charts-row { grid-template-columns: 1fr; }
            .activity-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 576px) {
            .stats-grid { grid-template-columns: 1fr; }
            .top-header { flex-direction: column; align-items: flex-start; gap: 12px; }
        }
    </style>
</head>
<body>

<!-- ── Sidebar ── -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <img src="/SP/images/logo.png" alt="Logo">
        <h2>Subhan <span>Printers</span><small>Admin Panel</small></h2>
    </div>
    <ul class="sidebar-menu">
        <li class="section-title">Main</li>
        <li><a href="/SP/admin/index.php" class="active"><i class="fas fa-th-large"></i><span>Dashboard</span></a></li>
        <li><a href="/SP/admin/orders/"><i class="fas fa-shopping-bag"></i><span>Orders</span></a></li>
        <li><a href="/SP/admin/quotes/"><i class="fas fa-file-invoice"></i><span>Quotes</span></a></li>
        <li><a href="/SP/admin/portfolio/"><i class="fas fa-images"></i><span>Portfolio</span></a></li>
        <li><a href="/SP/admin/products/"><i class="fas fa-cube"></i><span>Products</span></a></li>
        <li><a href="/SP/admin/categories/"><i class="fas fa-tags"></i><span>Categories</span></a></li>
        <li><a href="/SP/admin/services/" class="active"><i class="fas fa-cogs"></i><span>Services</span></a></li>
        <li class="section-title">Management</li>
        <li><a href="/SP/admin/newsletter/"><i class="fas fa-envelope"></i><span>Newsletter</span></a></li>
        <li><a href="/SP/admin/users/"><i class="fas fa-users"></i><span>Users</span></a></li>
        <li><a href="/SP/admin/settings/"><i class="fas fa-cog"></i><span>Settings</span></a></li>
    </ul>
    <div class="sidebar-logout">
        <a href="/SP/admin/logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
    </div>
</aside>

<!-- ── Main Content ── -->
<main class="main-content">

    <!-- Top Header -->
    <header class="top-header">
        <h1>Dashboard <span>Overview</span></h1>
        <div class="admin-info">
            <div>
                <div class="name"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></div>
                <div class="role"><?php echo ucfirst($_SESSION['admin_role'] ?? 'Admin'); ?></div>
            </div>
            <div class="avatar"><?php echo strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)); ?></div>
        </div>
    </header>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9)"><i class="fas fa-shopping-bag" style="color:#fff;"></i></div>
            <div class="stat-value"><?php echo $totalOrders; ?></div>
            <div class="stat-label">Total Orders</div>
            <span class="stat-change neutral"><i class="fas fa-sync"></i> <?php echo $orderStatuses['pending']; ?> pending</span>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706)"><i class="fas fa-file-invoice" style="color:#fff;"></i></div>
            <div class="stat-value"><?php echo $totalQuotes; ?></div>
            <div class="stat-label">Total Quotes</div>
            <span class="stat-change neutral"><i class="fas fa-clock"></i> <?php echo $quoteStatuses['pending']; ?> pending</span>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:linear-gradient(135deg,#22c55e,#16a34a)"><i class="fas fa-users" style="color:#fff;"></i></div>
            <div class="stat-value"><?php echo $totalUsers; ?></div>
            <div class="stat-label">Total Users</div>
            <span class="stat-change up"><i class="fas fa-arrow-up"></i> Active</span>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8)"><i class="fas fa-rupee-sign" style="color:#fff;"></i></div>
            <div class="stat-value">₨ <?php echo number_format($revenue, 0); ?></div>
            <div class="stat-label">Total Revenue</div>
            <span class="stat-change up"><i class="fas fa-arrow-up"></i> Lifetime</span>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:linear-gradient(135deg,#ec4899,#be185d)"><i class="fas fa-envelope" style="color:#fff;"></i></div>
            <div class="stat-value"><?php echo $totalSubscribers; ?></div>
            <div class="stat-label">Subscribers</div>
            <span class="stat-change neutral"><i class="fas fa-users"></i> Active</span>
        </div>
    </div>

    <!-- Charts -->
    <div class="charts-row">
        <div class="chart-card">
            <h3><i class="fas fa-chart-bar"></i> Monthly Orders</h3>
            <canvas id="ordersChart"></canvas>
        </div>
        <div class="chart-card">
            <h3><i class="fas fa-chart-pie"></i> Order Status</h3>
            <canvas id="statusChart"></canvas>
        </div>
    </div>

    <!-- Activity Grid -->
    <div class="activity-grid">

        <!-- Recent Orders -->
        <div class="activity-card">
            <h3>
                <i class="fas fa-shopping-bag"></i> Recent Orders
                <span class="badge"><?php echo count($recentOrders); ?></span>
            </h3>
            <?php if (count($recentOrders) > 0): ?>
                <?php foreach ($recentOrders as $order): ?>
                <div class="activity-item">
                    <div class="icon" style="background:<?php echo $order['status'] === 'delivered' ? 'rgba(34,197,94,0.15)' : 'rgba(245,158,11,0.15)'; ?>;color:<?php echo $order['status'] === 'delivered' ? '#22c55e' : '#f59e0b'; ?>;">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="content">
                        <div class="title">#<?php echo htmlspecialchars($order['order_number']); ?></div>
                        <div class="meta"><?php echo htmlspecialchars($order['customer_name']); ?> · <?php echo time_ago($order['created_at']); ?></div>
                    </div>
                    <span class="status status-<?php echo $order['status'] ?? 'pending'; ?>"><?php echo ucfirst($order['status'] ?? 'Pending'); ?></span>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color:#555577;text-align:center;padding:20px 0;">No orders yet</p>
            <?php endif; ?>
        </div>

        <!-- Recent Quotes -->
        <div class="activity-card">
            <h3>
                <i class="fas fa-file-invoice"></i> Recent Quotes
                <span class="badge"><?php echo count($recentQuotes); ?></span>
            </h3>
            <?php if (count($recentQuotes) > 0): ?>
                <?php foreach ($recentQuotes as $quote): ?>
                <div class="activity-item">
                    <div class="icon" style="background:rgba(139,92,246,0.15);color:#8b5cf6;">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <div class="content">
                        <div class="title">#<?php echo htmlspecialchars($quote['quote_number']); ?></div>
                        <div class="meta"><?php echo htmlspecialchars($quote['customer_name']); ?> · <?php echo time_ago($quote['created_at']); ?></div>
                    </div>
                    <span class="status status-<?php echo $quote['status'] ?? 'pending'; ?>"><?php echo ucfirst($quote['status'] ?? 'Pending'); ?></span>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color:#555577;text-align:center;padding:20px 0;">No quotes yet</p>
            <?php endif; ?>
        </div>

    </div>

</main>

<!-- ── Chart Scripts ── -->
<script>
// Orders Chart
const ctx1 = document.getElementById('ordersChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_column($monthlyOrders, 'month')); ?>,
        datasets: [{
            label: 'Orders',
            data: <?php echo json_encode(array_column($monthlyOrders, 'count')); ?>,
            backgroundColor: 'rgba(139,92,246,0.1)',
            borderColor: '#8b5cf6',
            borderWidth: 2,
            pointBackgroundColor: '#8b5cf6',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(255,255,255,0.04)' },
                ticks: { color: '#555577' }
            },
            x: {
                grid: { display: false },
                ticks: { color: '#555577' }
            }
        }
    }
});

// Status Chart
const ctx2 = document.getElementById('statusChart').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'In Production', 'Ready', 'Delivered', 'Cancelled'],
        datasets: [{
            data: [
                <?php echo $orderStatuses['pending']; ?>,
                <?php echo $orderStatuses['in_production']; ?>,
                <?php echo $orderStatuses['ready']; ?>,
                <?php echo $orderStatuses['delivered']; ?>,
                <?php echo $orderStatuses['cancelled']; ?>
            ],
            backgroundColor: ['#f59e0b', '#3b82f6', '#8b5cf6', '#22c55e', '#ef4444'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: '#8888aa',
                    padding: 16,
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            }
        },
        cutout: '70%'
    }
});
</script>

</body>
</html>
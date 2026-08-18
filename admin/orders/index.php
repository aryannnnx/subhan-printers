<?php
// ============================================
// ADMIN: Orders Management - Subhan Printers
// ============================================

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: /SP/admin/login.php');
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../includes/functions.php';

$orderModel = new Order();

// Get filters
$status = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';
$filters = [];

if ($status) $filters['status'] = $status;
if ($search) $filters['search'] = $search;

$orders = $orderModel->getAll($filters, 100, 0);

// Get order counts for status filter
$statusCounts = [
    'all' => count($orderModel->getAll()),
    'pending' => $orderModel->getCountByStatus('pending'),
    'approved' => $orderModel->getCountByStatus('approved'),
    'in_production' => $orderModel->getCountByStatus('in_production'),
    'ready' => $orderModel->getCountByStatus('ready'),
    'delivered' => $orderModel->getCountByStatus('delivered'),
    'cancelled' => $orderModel->getCountByStatus('cancelled'),
];

$statusLabels = [
    'pending' => 'Pending',
    'approved' => 'Approved',
    'in_production' => 'In Production',
    'ready' => 'Ready',
    'delivered' => 'Delivered',
    'cancelled' => 'Cancelled'
];

$statusColors = [
    'pending' => '#f59e0b',
    'approved' => '#8b5cf6',
    'in_production' => '#3b82f6',
    'ready' => '#8b5cf6',
    'delivered' => '#22c55e',
    'cancelled' => '#ef4444'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders | Admin Panel</title>
    <link rel="icon" href="/SP/images/logo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: #0a0a14;
            color: #e8e8f0;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
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
        .sidebar-menu a i { width: 20px; text-align: center; }
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

        /* Main */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 24px 32px 60px;
            min-height: 100vh;
        }

        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 24px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 16px;
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

        /* Filter Bar */
        .filter-bar {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .filter-bar a {
            padding: 8px 18px;
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s ease;
            color: #8888aa;
            border: 1px solid rgba(255,255,255,0.06);
            background: rgba(18,18,31,0.5);
        }
        .filter-bar a:hover { border-color: rgba(139,92,246,0.3); color: #e8e8f0; }
        .filter-bar a.active {
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
            color: #fff;
            border-color: transparent;
        }
        .filter-bar a .count {
            background: rgba(255,255,255,0.1);
            padding: 0 8px;
            border-radius: 100px;
            font-size: 0.7rem;
            margin-left: 4px;
        }
        .filter-bar a.active .count { background: rgba(255,255,255,0.2); }

        .search-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .search-bar input {
            flex: 1;
            padding: 10px 16px;
            background: #1a1a2e;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 10px;
            color: #e8e8f0;
            font-size: 0.9rem;
            font-family: inherit;
            min-width: 200px;
        }
        .search-bar input:focus {
            outline: none;
            border-color: #8b5cf6;
        }
        .search-bar button {
            padding: 10px 24px;
            background: #8b5cf6;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s ease;
            font-family: inherit;
        }
        .search-bar button:hover {
            background: #7c3aed;
            transform: translateY(-1px);
        }

        /* Table */
        .table-wrap {
            background: rgba(18, 18, 31, 0.8);
            border: 1px solid rgba(255,255,255,0.04);
            border-radius: 16px;
            overflow: hidden;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead th {
            padding: 14px 16px;
            text-align: left;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #555577;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            background: rgba(18,18,31,0.5);
        }
        tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.03);
            font-size: 0.85rem;
            color: #8888aa;
            vertical-align: middle;
        }
        tbody tr:hover { background: rgba(139,92,246,0.04); }
        tbody tr:last-child td { border-bottom: none; }

        .order-number { color: #e8e8f0; font-weight: 600; }
        .customer-name { color: #e8e8f0; }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-badge .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }

        .action-btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .action-btn.view {
            background: rgba(139,92,246,0.15);
            color: #8b5cf6;
        }
        .action-btn.view:hover { background: rgba(139,92,246,0.25); }
        .action-btn.edit {
            background: rgba(245,158,11,0.15);
            color: #f59e0b;
        }
        .action-btn.edit:hover { background: rgba(245,158,11,0.25); }
        .action-btn.delete {
            background: rgba(239,68,68,0.15);
            color: #ef4444;
        }
        .action-btn.delete:hover { background: rgba(239,68,68,0.25); }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #555577;
        }
        .empty-state i { font-size: 3rem; color: #2a2a3e; margin-bottom: 16px; }
        .empty-state h3 { color: #8888aa; font-weight: 600; }

        @media (max-width: 992px) {
            .sidebar { width: 72px; padding: 16px 8px; }
            .sidebar-brand h2, .sidebar-brand small, .sidebar-menu a span, .sidebar-menu .section-title, .sidebar-logout a span { display: none; }
            .sidebar-menu a { justify-content: center; padding: 12px; }
            .sidebar-logout a { justify-content: center; padding: 12px; }
            .main-content { margin-left: 72px; padding: 16px; }
        }
        @media (max-width: 768px) {
            .top-header { flex-direction: column; align-items: flex-start; }
            .filter-bar { gap: 4px; }
            .filter-bar a { font-size: 0.7rem; padding: 6px 12px; }
            .search-bar { flex-direction: column; }
            .search-bar input { width: 100%; }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <img src="/SP/images/logo.png" alt="Logo">
        <h2>Subhan <span>Printers</span><small>Admin Panel</small></h2>
    </div>
    <ul class="sidebar-menu">
        <li class="section-title">Main</li>
        <li><a href="/SP/admin/index.php"><i class="fas fa-th-large"></i><span>Dashboard</span></a></li>
        <li><a href="/SP/admin/orders/" class="active"><i class="fas fa-shopping-bag"></i><span>Orders</span></a></li>
        <li><a href="/SP/admin/quotes/"><i class="fas fa-file-invoice"></i><span>Quotes</span></a></li>
        <li><a href="/SP/admin/portfolio/"><i class="fas fa-images"></i><span>Portfolio</span></a></li>
        <li><a href="/SP/admin/products/"><i class="fas fa-cube"></i><span>Products</span></a></li>
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

<!-- Main -->
<main class="main-content">

    <header class="top-header">
        <h1>Order <span>Management</span></h1>
        <div class="admin-info">
            <div>
                <div class="name"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></div>
                <div class="role" style="font-size:0.75rem;color:#555577;"><?php echo ucfirst($_SESSION['admin_role'] ?? 'Admin'); ?></div>
            </div>
            <div class="avatar"><?php echo strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)); ?></div>
        </div>
    </header>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <a href="?status=all" class="<?php echo !$status ? 'active' : ''; ?>">
            All <span class="count"><?php echo $statusCounts['all']; ?></span>
        </a>
        <?php foreach (['pending', 'approved', 'in_production', 'ready', 'delivered', 'cancelled'] as $s): ?>
        <a href="?status=<?php echo $s; ?>" class="<?php echo $status === $s ? 'active' : ''; ?>">
            <?php echo ucfirst($s); ?> <span class="count"><?php echo $statusCounts[$s] ?? 0; ?></span>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Search -->
    <form class="search-bar" method="GET" action="">
        <input type="text" name="search" placeholder="Search by order number, customer name, or email..." value="<?php echo htmlspecialchars($search); ?>">
        <?php if ($status): ?>
        <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">
        <?php endif; ?>
        <button type="submit"><i class="fas fa-search"></i> Search</button>
        <?php if ($search || $status): ?>
        <a href="?status=all" style="display:flex;align-items:center;color:#555577;text-decoration:none;font-size:0.85rem;">
            <i class="fas fa-times"></i> Clear
        </a>
        <?php endif; ?>
    </form>

    <!-- Table -->
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($orders) > 0): ?>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><span class="order-number">#<?php echo htmlspecialchars($order['order_number']); ?></span></td>
                    <td><span class="customer-name"><?php echo htmlspecialchars($order['customer_name']); ?></span><br><small style="color:#555577;"><?php echo htmlspecialchars($order['customer_email']); ?></small></td>
                    <td><?php echo ucwords(str_replace('_', ' ', $order['product_type'] ?? '')); ?></td>
                    <td><?php echo $order['quantity'] ?? 0; ?></td>
                    <td style="color:#f59e0b;font-weight:600;">₨ <?php echo number_format($order['total'] ?? 0, 0); ?></td>
                    <td>
                        <span class="status-badge" style="background:<?php echo $statusColors[$order['status'] ?? 'pending'] ?? '#6b7280'; ?>20;color:<?php echo $statusColors[$order['status'] ?? 'pending'] ?? '#6b7280'; ?>;">
                            <span class="dot" style="background:<?php echo $statusColors[$order['status'] ?? 'pending'] ?? '#6b7280'; ?>;"></span>
                            <?php echo $statusLabels[$order['status'] ?? 'pending'] ?? ucfirst($order['status'] ?? ''); ?>
                        </span>
                    </td>
                    <td style="font-size:0.8rem;"><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                    <td style="text-align:center;">
                        <a href="/SP/admin/orders/view.php?id=<?php echo $order['id']; ?>" class="action-btn view"><i class="fas fa-eye"></i></a>
                        <a href="/SP/admin/orders/edit.php?id=<?php echo $order['id']; ?>" class="action-btn edit"><i class="fas fa-edit"></i></a>
                        <button onclick="deleteOrder(<?php echo $order['id']; ?>)" class="action-btn delete"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-shopping-bag"></i>
                            <h3>No orders found</h3>
                            <p>Orders will appear here once customers place them.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</main>

<script>
function deleteOrder(id) {
    if (confirm('Are you sure you want to delete this order?')) {
        fetch('/SP/api/orders?id=' + id, { method: 'DELETE' })
        .then(r => r.json())
        .then(d => {
            if (d.success) { location.reload(); }
            else { alert('Failed to delete order'); }
        })
        .catch(() => alert('Network error'));
    }
}
</script>

</body>
</html>
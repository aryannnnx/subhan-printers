<?php
// ============================================
// ADMIN: View Order - Subhan Printers
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

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$order = $orderModel->getById($id);

if (!$order) {
    header('Location: /SP/admin/orders/index.php');
    exit;
}

// Get timeline
$timeline = $orderModel->getTimeline($id);

// Handle status update
$statusMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $newStatus = $_POST['status'];
    $note = trim($_POST['note'] ?? '');
    
    $result = $orderModel->updateStatus($id, $newStatus, $note);
    
    if ($result['success']) {
        $statusMessage = 'Order status updated successfully!';
        // Refresh data
        $order = $orderModel->getById($id);
        $timeline = $orderModel->getTimeline($id);
    } else {
        $statusMessage = 'Failed to update status: ' . ($result['error'] ?? 'Unknown error');
    }
}

$statusColors = [
    'pending' => '#f59e0b',
    'quoted' => '#3b82f6',
    'approved' => '#8b5cf6',
    'in_production' => '#f59e0b',
    'quality_check' => '#8b5cf6',
    'ready' => '#22c55e',
    'delivered' => '#22c55e',
    'cancelled' => '#ef4444'
];

$statusLabels = [
    'pending' => 'Pending',
    'quoted' => 'Quoted',
    'approved' => 'Approved',
    'in_production' => 'In Production',
    'quality_check' => 'Quality Check',
    'ready' => 'Ready',
    'delivered' => 'Delivered',
    'cancelled' => 'Cancelled'
];

$paymentLabels = [
    'unpaid' => 'Unpaid',
    'deposit_paid' => 'Deposit Paid',
    'partial_paid' => 'Partial Paid',
    'paid' => 'Paid'
];

$paymentColors = [
    'unpaid' => '#ef4444',
    'deposit_paid' => '#f59e0b',
    'partial_paid' => '#3b82f6',
    'paid' => '#22c55e'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #<?php echo $order['order_number']; ?> | Admin Panel</title>
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

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #8b5cf6;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 16px;
            transition: 0.3s ease;
        }
        .back-link:hover { color: #a78bfa; transform: translateX(-4px); }

        .alert {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
        }
        .alert-success {
            background: rgba(34,197,94,0.1);
            border: 1px solid rgba(34,197,94,0.2);
            color: #22c55e;
        }
        .alert-error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.2);
            color: #ef4444;
        }

        .order-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        .order-card {
            background: rgba(18, 18, 31, 0.8);
            border: 1px solid rgba(255,255,255,0.04);
            border-radius: 16px;
            padding: 24px;
        }
        .order-card .card-title {
            font-weight: 700;
            color: #e8e8f0;
            font-size: 1rem;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .order-card .card-title i { color: #8b5cf6; }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            font-size: 0.85rem;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row .label { color: #8888aa; }
        .info-row .value { color: #e8e8f0; font-weight: 500; }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 16px;
            border-radius: 100px;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .status-badge .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: rgba(255,255,255,0.06);
        }
        .timeline-item {
            position: relative;
            padding: 12px 0 12px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.03);
        }
        .timeline-item:last-child { border-bottom: none; }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -26px;
            top: 16px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid #8b5cf6;
            background: #12121f;
        }
        .timeline-item .time {
            font-size: 0.7rem;
            color: #555577;
        }
        .timeline-item .status {
            font-weight: 600;
            color: #e8e8f0;
            font-size: 0.85rem;
        }
        .timeline-item .note {
            font-size: 0.8rem;
            color: #8888aa;
            margin-top: 2px;
        }

        .status-select {
            padding: 10px 16px;
            background: #1a1a2e;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 10px;
            color: #e8e8f0;
            font-size: 0.9rem;
            font-family: inherit;
            width: 100%;
            margin-bottom: 12px;
        }
        .status-select:focus {
            outline: none;
            border-color: #8b5cf6;
        }

        .btn-update {
            padding: 10px 24px;
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: 0.3s ease;
            font-family: inherit;
            width: 100%;
        }
        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139,92,246,0.3);
        }

        .btn-whatsapp {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: rgba(37,211,102,0.15);
            color: #25d366;
            border: 1px solid rgba(37,211,102,0.2);
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: 0.3s ease;
        }
        .btn-whatsapp:hover {
            background: rgba(37,211,102,0.25);
            transform: translateY(-2px);
        }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: rgba(59,130,246,0.15);
            color: #3b82f6;
            border: 1px solid rgba(59,130,246,0.2);
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: 0.3s ease;
            cursor: pointer;
        }
        .btn-print:hover {
            background: rgba(59,130,246,0.25);
            transform: translateY(-2px);
        }

        .action-bar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 16px;
        }

        @media (max-width: 992px) {
            .sidebar { width: 72px; padding: 16px 8px; }
            .sidebar-brand h2, .sidebar-brand small, .sidebar-menu a span, .sidebar-menu .section-title, .sidebar-logout a span { display: none; }
            .sidebar-menu a { justify-content: center; padding: 12px; }
            .sidebar-logout a { justify-content: center; padding: 12px; }
            .main-content { margin-left: 72px; padding: 16px; }
            .order-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .top-header { flex-direction: column; align-items: flex-start; }
            .action-bar { flex-direction: column; }
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
        <h1>Order <span>#<?php echo htmlspecialchars($order['order_number']); ?></span></h1>
        <div class="admin-info">
            <div>
                <div class="name"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></div>
                <div class="role" style="font-size:0.75rem;color:#555577;"><?php echo ucfirst($_SESSION['admin_role'] ?? 'Admin'); ?></div>
            </div>
            <div class="avatar"><?php echo strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)); ?></div>
        </div>
    </header>

    <a href="/SP/admin/orders/" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>

    <?php if ($statusMessage): ?>
    <div class="alert alert-<?php echo strpos($statusMessage, 'successfully') !== false ? 'success' : 'error'; ?>">
        <i class="fas fa-<?php echo strpos($statusMessage, 'successfully') !== false ? 'check-circle' : 'exclamation-circle'; ?>"></i>
        <?php echo $statusMessage; ?>
    </div>
    <?php endif; ?>

    <div class="order-grid">

        <!-- Left Column -->
        <div>

            <!-- Order Info -->
            <div class="order-card">
                <div class="card-title"><i class="fas fa-info-circle"></i> Order Information</div>
                
                <div class="info-row">
                    <span class="label">Order Number</span>
                    <span class="value">#<?php echo htmlspecialchars($order['order_number']); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Status</span>
                    <span class="value">
                        <span class="status-badge" style="background:<?php echo $statusColors[$order['status'] ?? 'pending']; ?>20;color:<?php echo $statusColors[$order['status'] ?? 'pending']; ?>;">
                            <span class="dot" style="background:<?php echo $statusColors[$order['status'] ?? 'pending']; ?>;"></span>
                            <?php echo $statusLabels[$order['status'] ?? 'pending'] ?? ucfirst($order['status'] ?? ''); ?>
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="label">Payment Status</span>
                    <span class="value">
                        <span class="status-badge" style="background:<?php echo $paymentColors[$order['payment_status'] ?? 'unpaid']; ?>20;color:<?php echo $paymentColors[$order['payment_status'] ?? 'unpaid']; ?>;">
                            <span class="dot" style="background:<?php echo $paymentColors[$order['payment_status'] ?? 'unpaid']; ?>;"></span>
                            <?php echo $paymentLabels[$order['payment_status'] ?? 'unpaid'] ?? ucfirst($order['payment_status'] ?? ''); ?>
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="label">Order Date</span>
                    <span class="value"><?php echo date('F d, Y H:i', strtotime($order['created_at'])); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Source</span>
                    <span class="value"><?php echo ucfirst($order['source'] ?? 'Website'); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Total Amount</span>
                    <span class="value" style="color:#f59e0b;font-size:1.1rem;">₨ <?php echo number_format($order['total'] ?? 0, 0); ?></span>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="order-card" style="margin-top:24px;">
                <div class="card-title"><i class="fas fa-user"></i> Customer Information</div>
                
                <div class="info-row">
                    <span class="label">Name</span>
                    <span class="value"><?php echo htmlspecialchars($order['customer_name']); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Email</span>
                    <span class="value"><a href="mailto:<?php echo htmlspecialchars($order['customer_email']); ?>" style="color:#8b5cf6;"><?php echo htmlspecialchars($order['customer_email']); ?></a></span>
                </div>
                <div class="info-row">
                    <span class="label">Phone</span>
                    <span class="value"><a href="tel:<?php echo htmlspecialchars($order['customer_phone']); ?>" style="color:#8b5cf6;"><?php echo htmlspecialchars($order['customer_phone']); ?></a></span>
                </div>
                <?php if (!empty($order['customer_address'])): ?>
                <div class="info-row">
                    <span class="label">Address</span>
                    <span class="value"><?php echo htmlspecialchars($order['customer_address']); ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($order['customer_city'])): ?>
                <div class="info-row">
                    <span class="label">City</span>
                    <span class="value"><?php echo htmlspecialchars($order['customer_city']); ?></span>
                </div>
                <?php endif; ?>
                
                <div class="action-bar">
                    <a href="https://wa.me/92<?php echo preg_replace('/[^0-9]/', '', $order['customer_phone']); ?>?text=Hi%20<?php echo urlencode($order['customer_name']); ?>,%20your%20order%20#<?php echo $order['order_number']; ?>%20is%20<?php echo urlencode($order['status'] ?? 'pending'); ?>" target="_blank" class="btn-whatsapp">
                        <i class="fab fa-whatsapp"></i> Contact on WhatsApp
                    </a>
                </div>
            </div>

            <!-- Product Details -->
            <div class="order-card" style="margin-top:24px;">
                <div class="card-title"><i class="fas fa-box"></i> Product Details</div>
                
                <div class="info-row">
                    <span class="label">Product Type</span>
                    <span class="value"><?php echo ucwords(str_replace('_', ' ', $order['product_type'] ?? '')); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Quantity</span>
                    <span class="value"><?php echo $order['quantity'] ?? 0; ?></span>
                </div>
                <?php if (!empty($order['size'])): ?>
                <div class="info-row">
                    <span class="label">Size</span>
                    <span class="value"><?php echo htmlspecialchars($order['size']); ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($order['paper_type'])): ?>
                <div class="info-row">
                    <span class="label">Paper Type</span>
                    <span class="value"><?php echo htmlspecialchars($order['paper_type']); ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($order['finishing'])): 
                    $finishing = json_decode($order['finishing'], true);
                ?>
                <div class="info-row">
                    <span class="label">Finishing</span>
                    <span class="value"><?php echo is_array($finishing) ? implode(', ', array_map('ucfirst', $finishing)) : htmlspecialchars($order['finishing']); ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($order['description'])): ?>
                <div class="info-row">
                    <span class="label">Description</span>
                    <span class="value"><?php echo nl2br(htmlspecialchars($order['description'])); ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($order['notes'])): ?>
                <div class="info-row">
                    <span class="label">Notes</span>
                    <span class="value"><?php echo nl2br(htmlspecialchars($order['notes'])); ?></span>
                </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- Right Column -->
        <div>

            <!-- Update Status -->
            <div class="order-card">
                <div class="card-title"><i class="fas fa-edit"></i> Update Status</div>
                
                <form method="POST" action="">
                    <select name="status" class="status-select">
                        <?php foreach ($statusLabels as $key => $label): ?>
                        <option value="<?php echo $key; ?>" <?php echo ($order['status'] ?? '') === $key ? 'selected' : ''; ?>>
                            <?php echo $label; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <div class="form-group" style="margin-bottom:12px;">
                        <input type="text" name="note" placeholder="Add a note (optional)" style="width:100%;padding:10px 16px;background:#1a1a2e;border:1px solid rgba(255,255,255,0.06);border-radius:10px;color:#e8e8f0;font-size:0.85rem;font-family:inherit;">
                    </div>
                    
                    <button type="submit" class="btn-update">
                        <i class="fas fa-sync"></i> Update Status
                    </button>
                </form>

                <div style="margin-top:16px;display:flex;gap:10px;flex-wrap:wrap;">
                    <button onclick="window.print()" class="btn-print">
                        <i class="fas fa-print"></i> Print Order
                    </button>
                </div>
            </div>

            <!-- Timeline -->
            <div class="order-card" style="margin-top:24px;">
                <div class="card-title"><i class="fas fa-clock"></i> Order Timeline</div>
                
                <?php if (count($timeline) > 0): ?>
                <div class="timeline">
                    <?php foreach ($timeline as $item): ?>
                    <div class="timeline-item">
                        <div class="time"><?php echo date('M d, Y H:i', strtotime($item['created_at'])); ?></div>
                        <div class="status" style="color:<?php echo $statusColors[$item['status'] ?? 'pending'] ?? '#6b7280'; ?>;">
                            <?php echo $statusLabels[$item['status'] ?? ''] ?? ucfirst($item['status'] ?? ''); ?>
                        </div>
                        <?php if (!empty($item['note'])): ?>
                        <div class="note"><?php echo htmlspecialchars($item['note']); ?></div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div style="text-align:center;padding:20px 0;color:#555577;font-size:0.85rem;">
                    <i class="fas fa-clock" style="font-size:2rem;display:block;margin-bottom:8px;color:#2a2a3e;"></i>
                    No timeline entries yet
                </div>
                <?php endif; ?>
            </div>

        </div>

    </div>

</main>

</body>
</html>
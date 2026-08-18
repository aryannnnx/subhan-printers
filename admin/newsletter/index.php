<?php
// ============================================
// ADMIN: Newsletter Management - Subhan Printers
// ============================================

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: /SP/admin/login.php');
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Newsletter.php';
require_once __DIR__ . '/../../includes/functions.php';

$newsletterModel = new Newsletter();

// Get filters
$status = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

// Get subscribers
$subscribers = $newsletterModel->getAll([
    'status' => $status ?: null,
    'search' => $search ?: null
], 200, 0);

$totalSubscribers = count($subscribers);
$activeCount = $newsletterModel->countActive();
$unsubscribedCount = $totalSubscribers - $activeCount;

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $result = $newsletterModel->unsubscribe($_GET['delete']);
    if ($result['success']) {
        header('Location: /SP/admin/newsletter/index.php?msg=deleted');
        exit;
    }
}

$message = '';
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'deleted') $message = 'Subscriber removed successfully!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter | Admin Panel</title>
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: rgba(18, 18, 31, 0.8);
            border: 1px solid rgba(255,255,255,0.04);
            border-radius: 16px;
            padding: 20px 24px;
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

        .filter-bar {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 16px;
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
        .search-bar button:hover { background: #7c3aed; }

        .action-bar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 16px;
        }
        .btn-export {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: rgba(34,197,94,0.15);
            color: #22c55e;
            border: 1px solid rgba(34,197,94,0.2);
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: 0.3s ease;
        }
        .btn-export:hover {
            background: rgba(34,197,94,0.25);
            transform: translateY(-2px);
        }

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

        .subscriber-email { color: #e8e8f0; font-weight: 600; }

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
        .status-active { background: rgba(34,197,94,0.15); color: #22c55e; }
        .status-unsubscribed { background: rgba(239,68,68,0.15); color: #ef4444; }

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
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .top-header { flex-direction: column; align-items: flex-start; }
            .filter-bar { gap: 4px; }
            .filter-bar a { font-size: 0.7rem; padding: 6px 12px; }
            .search-bar { flex-direction: column; }
            .stats-grid { grid-template-columns: 1fr; }
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
        <li><a href="/SP/admin/orders/"><i class="fas fa-shopping-bag"></i><span>Orders</span></a></li>
        <li><a href="/SP/admin/quotes/"><i class="fas fa-file-invoice"></i><span>Quotes</span></a></li>
        <li><a href="/SP/admin/portfolio/"><i class="fas fa-images"></i><span>Portfolio</span></a></li>
        <li><a href="/SP/admin/products/"><i class="fas fa-cube"></i><span>Products</span></a></li>
        <li><a href="/SP/admin/services/" class="active"><i class="fas fa-cogs"></i><span>Services</span></a></li>
        <li class="section-title">Management</li>
        <li><a href="/SP/admin/newsletter/" class="active"><i class="fas fa-envelope"></i><span>Newsletter</span></a></li>
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
        <h1>Newsletter <span>Subscribers</span></h1>
        <div class="admin-info">
            <div>
                <div class="name"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></div>
                <div class="role" style="font-size:0.75rem;color:#555577;"><?php echo ucfirst($_SESSION['admin_role'] ?? 'Admin'); ?></div>
            </div>
            <div class="avatar"><?php echo strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)); ?></div>
        </div>
    </header>

    <?php if ($message): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?php echo $message; ?>
    </div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9)"><i class="fas fa-envelope" style="color:#fff;"></i></div>
            <div class="stat-value"><?php echo $totalSubscribers; ?></div>
            <div class="stat-label">Total Subscribers</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:linear-gradient(135deg,#22c55e,#16a34a)"><i class="fas fa-check-circle" style="color:#fff;"></i></div>
            <div class="stat-value"><?php echo $activeCount; ?></div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:linear-gradient(135deg,#ef4444,#dc2626)"><i class="fas fa-times-circle" style="color:#fff;"></i></div>
            <div class="stat-value"><?php echo $unsubscribedCount; ?></div>
            <div class="stat-label">Unsubscribed</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706)"><i class="fas fa-arrow-up" style="color:#fff;"></i></div>
            <div class="stat-value"><?php echo $activeCount > 0 ? round(($activeCount / $totalSubscribers) * 100) : 0; ?>%</div>
            <div class="stat-label">Conversion Rate</div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <a href="?status=all" class="<?php echo !$status ? 'active' : ''; ?>">
            All <span class="count"><?php echo $totalSubscribers; ?></span>
        </a>
        <a href="?status=active" class="<?php echo $status === 'active' ? 'active' : ''; ?>">
            Active <span class="count"><?php echo $activeCount; ?></span>
        </a>
        <a href="?status=unsubscribed" class="<?php echo $status === 'unsubscribed' ? 'active' : ''; ?>">
            Unsubscribed <span class="count"><?php echo $unsubscribedCount; ?></span>
        </a>
    </div>

    <!-- Search & Actions -->
    <div style="display:flex;flex-wrap:wrap;gap:12px;justify-content:space-between;margin-bottom:16px;">
        <form class="search-bar" method="GET" action="" style="flex:1;min-width:200px;margin:0;">
            <input type="text" name="search" placeholder="Search by email..." value="<?php echo htmlspecialchars($search); ?>">
            <?php if ($status): ?>
            <input type="hidden" name="status" value="<?php echo htmlspecialchars($status); ?>">
            <?php endif; ?>
            <button type="submit"><i class="fas fa-search"></i></button>
            <?php if ($search || $status): ?>
            <a href="?status=all" style="display:flex;align-items:center;color:#555577;text-decoration:none;font-size:0.85rem;">
                <i class="fas fa-times"></i> Clear
            </a>
            <?php endif; ?>
        </form>
        <div class="action-bar">
            <a href="?export=csv" class="btn-export">
                <i class="fas fa-file-export"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>IP Address</th>
                    <th>Source</th>
                    <th>Subscribed At</th>
                    <th style="text-align:center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($subscribers) > 0): ?>
                <?php foreach ($subscribers as $sub): ?>
                <tr>
                    <td><?php echo $sub['id']; ?></td>
                    <td><span class="subscriber-email"><?php echo htmlspecialchars($sub['email']); ?></span></td>
                    <td>
                        <span class="status-badge <?php echo ($sub['status'] ?? 'active') === 'active' ? 'status-active' : 'status-unsubscribed'; ?>">
                            <span class="dot" style="background:<?php echo ($sub['status'] ?? 'active') === 'active' ? '#22c55e' : '#ef4444'; ?>;"></span>
                            <?php echo ucfirst($sub['status'] ?? 'Active'); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($sub['ip_address'] ?? '—'); ?></td>
                    <td><?php echo htmlspecialchars($sub['source'] ?? '—'); ?></td>
                    <td><?php echo date('M d, Y H:i', strtotime($sub['subscribed_at'])); ?></td>
                    <td style="text-align:center;">
                        <?php if (($sub['status'] ?? 'active') === 'active'): ?>
                        <button onclick="unsubscribe(<?php echo $sub['id']; ?>)" class="action-btn delete"><i class="fas fa-user-slash"></i></button>
                        <?php else: ?>
                        <button onclick="deleteSubscriber(<?php echo $sub['id']; ?>)" class="action-btn delete"><i class="fas fa-trash"></i></button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-envelope"></i>
                            <h3>No subscribers found</h3>
                            <p>Subscribers will appear here once they sign up.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</main>

<script>
function unsubscribe(id) {
    if (confirm('Unsubscribe this user?')) {
        fetch('/SP/api/newsletter?unsubscribe&id=' + id, { method: 'POST' })
        .then(r => r.json())
        .then(d => {
            if (d.success) { location.reload(); }
            else { alert('Failed to unsubscribe'); }
        })
        .catch(() => alert('Network error'));
    }
}

function deleteSubscriber(id) {
    if (confirm('Permanently delete this subscriber?')) {
        fetch('/SP/api/newsletter?id=' + id, { method: 'DELETE' })
        .then(r => r.json())
        .then(d => {
            if (d.success) { location.reload(); }
            else { alert('Failed to delete'); }
        })
        .catch(() => alert('Network error'));
    }
}
</script>

</body>
</html>
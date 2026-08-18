<?php
// ============================================
// ADMIN: Services List - Subhan Printers
// ============================================

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: /SP/admin/login.php');
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../../includes/functions.php';

$productModel = new Product();
$categoryModel = new Category();

// Get all services
$services = $productModel->getAll();

// Get categories for display
$categories = $categoryModel->getAll(true);
$categoryMap = [];
foreach ($categories as $cat) {
    $categoryMap[$cat['slug']] = $cat['name'];
}

// Handle delete
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $result = $productModel->delete($id);
    if ($result['success']) {
        header('Location: /SP/admin/services/index.php?msg=deleted');
        exit;
    }
}

$msg = $_GET['msg'] ?? '';
$successMessage = '';
if ($msg === 'added') {
    $successMessage = 'Service added successfully!';
} elseif ($msg === 'updated') {
    $successMessage = 'Service updated successfully!';
} elseif ($msg === 'deleted') {
    $successMessage = 'Service deleted successfully!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services | Admin Panel</title>
    <link rel="icon" href="/SP/images/logo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Same styles as portfolio/index.php */
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

        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }
        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: 0.3s ease;
            text-decoration: none;
        }
        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139,92,246,0.3);
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
            font-size: 0.9rem;
        }
        thead {
            background: rgba(255,255,255,0.03);
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        th {
            text-align: left;
            padding: 14px 18px;
            color: #8888aa;
            font-weight: 600;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            white-space: nowrap;
        }
        td {
            padding: 12px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.03);
            vertical-align: middle;
        }
        tr:hover td {
            background: rgba(255,255,255,0.02);
        }

        .category-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 100px;
            font-size: 0.7rem;
            font-weight: 600;
            background: rgba(139,92,246,0.15);
            color: #8b5cf6;
            border: 1px solid rgba(139,92,246,0.2);
        }

        .badge-featured {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 100px;
            font-size: 0.65rem;
            font-weight: 600;
            background: rgba(245,158,11,0.15);
            color: #f59e0b;
            border: 1px solid rgba(245,158,11,0.2);
        }

        .badge-status {
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        .badge-active {
            background: rgba(34,197,94,0.15);
            color: #22c55e;
        }
        .badge-inactive {
            background: rgba(239,68,68,0.15);
            color: #ef4444;
        }

        .action-btns {
            display: flex;
            gap: 6px;
        }
        .action-btns a {
            padding: 6px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            transition: 0.3s ease;
            text-decoration: none;
        }
        .btn-edit {
            color: #3b82f6;
            background: rgba(59,130,246,0.1);
        }
        .btn-edit:hover {
            background: rgba(59,130,246,0.2);
        }
        .btn-delete {
            color: #ef4444;
            background: rgba(239,68,68,0.1);
        }
        .btn-delete:hover {
            background: rgba(239,68,68,0.2);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-state .icon {
            font-size: 3rem;
            color: #333355;
            margin-bottom: 16px;
        }
        .empty-state h3 {
            color: #e8e8f0;
            margin-bottom: 8px;
        }
        .empty-state p {
            color: #555577;
        }

        .thumbnail {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.06);
        }

        .service-price {
            font-weight: 600;
            color: #f59e0b;
        }

        @media (max-width: 992px) {
            .sidebar { width: 72px; padding: 16px 8px; }
            .sidebar-brand h2, .sidebar-brand small, .sidebar-menu a span, .sidebar-menu .section-title, .sidebar-logout a span { display: none; }
            .sidebar-menu a { justify-content: center; padding: 12px; }
            .sidebar-logout a { justify-content: center; padding: 12px; }
            .main-content { margin-left: 72px; padding: 16px; }
        }
        @media (max-width: 768px) {
            .top-header { flex-direction: column; align-items: flex-start; }
            .action-bar { flex-direction: column; align-items: stretch; }
            .btn-add { justify-content: center; }
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
        <li><a href="/SP/admin/categories/"><i class="fas fa-tags"></i><span>Categories</span></a></li>
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
        <h1>Manage <span>Services</span></h1>
        <div class="admin-info">
            <div>
                <div class="name"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></div>
                <div class="role" style="font-size:0.75rem;color:#555577;"><?php echo ucfirst($_SESSION['admin_role'] ?? 'Admin'); ?></div>
            </div>
            <div class="avatar"><?php echo strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)); ?></div>
        </div>
    </header>

    <?php if ($successMessage): ?>
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <?php echo $successMessage; ?>
    </div>
    <?php endif; ?>

    <div class="action-bar">
        <div>
            <span style="color:#8888aa;">Total Services: <strong style="color:#e8e8f0;"><?php echo count($services); ?></strong></span>
        </div>
        <a href="/SP/admin/services/add.php" class="btn-add">
            <i class="fas fa-plus"></i> Add New Service
        </a>
    </div>

    <div class="table-wrap">
        <?php if (count($services) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Image</th>
                    <th>Service Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Badge</th>
                    <th>Status</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $index => $service): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td>
                        <?php 
                        $imageUrl = !empty($service['image_url']) 
                            ? '/SP/' . ltrim($service['image_url'], '/') 
                            : 'https://placehold.co/50x50/1a1a2e/8b5cf6?text=' . urlencode($service['name'] ?? '');
                        ?>
                        <img src="<?php echo htmlspecialchars($imageUrl); ?>" 
                             alt="<?php echo htmlspecialchars($service['name'] ?? ''); ?>" 
                             class="thumbnail"
                             onerror="this.src='https://placehold.co/50x50/1a1a2e/8b5cf6?text=<?php echo urlencode($service['name'] ?? ''); ?>'">
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($service['name'] ?? ''); ?></strong>
                    </td>
                    <td>
                        <?php 
                        $categoryName = $service['category'] ?? 'General';
                        if (!empty($categoryMap[$categoryName])) {
                            $categoryName = $categoryMap[$categoryName];
                        }
                        ?>
                        <span class="category-badge"><?php echo ucfirst(htmlspecialchars($categoryName)); ?></span>
                    </td>
                    <td>
                        <div class="service-price">₨ <?php echo number_format($service['starting_price'] ?? 0, 0); ?></div>
                        <?php if (!empty($service['price_text'])): ?>
                        <div style="font-size:0.7rem;color:#555577;"><?php echo htmlspecialchars($service['price_text']); ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($service['badge'])): ?>
                        <span class="badge-featured"><?php echo htmlspecialchars($service['badge']); ?></span>
                        <?php else: ?>
                        <span style="color:#555577;font-size:0.7rem;">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge-status <?php echo ($service['is_active'] ?? 1) ? 'badge-active' : 'badge-inactive'; ?>">
                            <?php echo ($service['is_active'] ?? 1) ? 'Active' : 'Inactive'; ?>
                        </span>
                    </td>
                    <td style="text-align:right;">
                        <div class="action-btns" style="justify-content:flex-end;">
                            <a href="/SP/admin/services/edit.php?id=<?php echo $service['id']; ?>" class="btn-edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="/SP/admin/services/index.php?delete=1&id=<?php echo $service['id']; ?>" 
                               class="btn-delete" 
                               onclick="return confirm('Are you sure you want to delete this service?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state">
            <div class="icon"><i class="fas fa-cogs"></i></div>
            <h3>No Services Yet</h3>
            <p>Start by adding your first service</p>
            <a href="/SP/admin/services/add.php" class="btn-add" style="margin-top:16px;">
                <i class="fas fa-plus"></i> Add Service
            </a>
        </div>
        <?php endif; ?>
    </div>

</main>

</body>
</html>
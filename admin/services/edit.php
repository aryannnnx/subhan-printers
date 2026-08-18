<?php
// ============================================
// ADMIN: Edit Service - Subhan Printers
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

// Fetch categories
$categories = $categoryModel->getAll(true);
$categoryDropdown = [];
foreach ($categories as $cat) {
    $categoryDropdown[$cat['slug']] = $cat['name'];
}

if (empty($categoryDropdown)) {
    $categoryDropdown = [
        'print' => 'Printing Services',
        'packaging' => 'Packaging Services',
        'design' => 'Graphic Design',
        'wedding' => 'Wedding Services',
        'large-format' => 'Large Format Printing',
        'stationery' => 'Stationery',
        'stickers' => 'Stickers & Labels'
    ];
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$service = $productModel->getById($id);

if (!$service) {
    header('Location: /SP/admin/services/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $icon = trim($_POST['icon'] ?? 'fa-print');
    $starting_price = floatval($_POST['starting_price'] ?? 0);
    $price_text = trim($_POST['price_text'] ?? '');
    $min_quantity = intval($_POST['min_quantity'] ?? 0);
    $turnaround = trim($_POST['turnaround'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $badge = trim($_POST['badge'] ?? '');
    $featured = isset($_POST['featured']) ? 1 : 0;
    $show_in_pricing = isset($_POST['show_in_pricing']) ? 1 : 0;
    
    // Features as JSON
    $features = isset($_POST['features']) ? array_filter(array_map('trim', explode(',', $_POST['features']))) : [];
    
    // Image upload
    $image_url = $service['image_url'] ?? '';
    
    if (isset($_FILES['service_image']) && $_FILES['service_image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['service_image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/jpg'];
        
        if (!in_array($file['type'], $allowedTypes)) {
            $error = 'Invalid file type. Please upload JPG, PNG, WEBP or GIF.';
        } else {
            $uploadDir = __DIR__ . '/../../images/services/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            // Delete old image
            if (!empty($service['image_url']) && file_exists(__DIR__ . '/../../' . $service['image_url'])) {
                unlink(__DIR__ . '/../../' . $service['image_url']);
            }
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'service_' . time() . '_' . uniqid() . '.' . $extension;
            $targetPath = $uploadDir . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $image_url = 'images/services/' . $filename;
            } else {
                $error = 'Failed to upload image.';
            }
        }
    }
    
    if (empty($name) || empty($category)) {
        $error = 'Name and category are required.';
    } elseif (empty($error)) {
        $data = [
            'name' => $name,
            'description' => $description,
            'icon' => $icon,
            'starting_price' => $starting_price,
            'price_text' => $price_text,
            'min_quantity' => $min_quantity,
            'turnaround' => $turnaround,
            'category' => $category,
            'badge' => $badge,
            'features' => $features,
            'featured' => $featured,
            'show_in_pricing' => $show_in_pricing,
            'image_url' => $image_url
        ];
        
        $result = $productModel->update($id, $data);
        
        if ($result['success']) {
            header('Location: /SP/admin/services/index.php?msg=updated');
            exit;
        } else {
            $error = $result['error'] ?? 'Failed to update service.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service | Admin Panel</title>
    <link rel="icon" href="/SP/images/logo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Same styles as add.php */
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

        .form-card {
            background: rgba(18, 18, 31, 0.8);
            border: 1px solid rgba(255,255,255,0.04);
            border-radius: 16px;
            padding: 32px;
            max-width: 700px;
        }

        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #8888aa;
            margin-bottom: 6px;
        }
        .form-group label .required { color: #ef4444; }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 12px 16px;
            background: #1a1a2e;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 10px;
            color: #e8e8f0;
            font-size: 0.9rem;
            transition: 0.3s ease;
            font-family: inherit;
        }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 4px rgba(139,92,246,0.08);
        }
        .form-group select option { background: #1a1a2e; }
        .form-group .hint { font-size: 0.75rem; color: #555577; margin-top: 4px; }

        .image-upload-wrap {
            border: 2px dashed rgba(255,255,255,0.08);
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            transition: 0.3s ease;
            cursor: pointer;
        }
        .image-upload-wrap:hover {
            border-color: #8b5cf6;
            background: rgba(139,92,246,0.04);
        }
        .image-upload-wrap .icon {
            font-size: 2.5rem;
            color: #555577;
            margin-bottom: 8px;
        }
        .image-upload-wrap p {
            color: #8888aa;
            font-size: 0.85rem;
        }
        .image-upload-wrap .file-name {
            color: #8b5cf6;
            font-weight: 600;
            margin-top: 6px;
        }
        #fileInput { display: none; }
        .image-preview {
            margin-top: 12px;
            display: <?php echo !empty($service['image_url']) ? 'block' : 'none'; ?>;
        }
        .image-preview img {
            max-width: 200px;
            max-height: 150px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.06);
        }
        .current-image {
            margin-top: 10px;
            font-size: 0.8rem;
            color: #8888aa;
        }
        .current-image a {
            color: #8b5cf6;
            text-decoration: none;
        }
        .current-image a:hover { text-decoration: underline; }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .checkbox-group input {
            width: 18px;
            height: 18px;
            accent-color: #8b5cf6;
        }

        .btn-submit {
            padding: 12px 32px;
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(139,92,246,0.3);
        }
        .btn-cancel {
            padding: 12px 32px;
            background: transparent;
            color: #8888aa;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-cancel:hover {
            background: rgba(255,255,255,0.05);
            color: #e8e8f0;
        }

        .alert-error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.2);
            color: #ef4444;
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
        }

        @media (max-width: 992px) {
            .sidebar { width: 72px; padding: 16px 8px; }
            .sidebar-brand h2, .sidebar-brand small, .sidebar-menu a span, .sidebar-menu .section-title, .sidebar-logout a span { display: none; }
            .sidebar-menu a { justify-content: center; padding: 12px; }
            .sidebar-logout a { justify-content: center; padding: 12px; }
            .main-content { margin-left: 72px; padding: 16px; }
            .form-row { grid-template-columns: 1fr; }
            .form-card { padding: 24px; }
        }
        @media (max-width: 768px) {
            .top-header { flex-direction: column; align-items: flex-start; }
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
        <h1>Edit <span>Service</span></h1>
        <div class="admin-info">
            <div>
                <div class="name"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></div>
                <div class="role" style="font-size:0.75rem;color:#555577;"><?php echo ucfirst($_SESSION['admin_role'] ?? 'Admin'); ?></div>
            </div>
            <div class="avatar"><?php echo strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)); ?></div>
        </div>
    </header>

    <a href="/SP/admin/services/" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Services
    </a>

    <?php if ($error): ?>
    <div class="alert-error">
        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
    </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" action="" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>Service Name <span class="required">*</span></label>
                <input type="text" name="name" placeholder="e.g., Offset Printing" required value="<?php echo htmlspecialchars($service['name'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" placeholder="Service description"><?php echo htmlspecialchars($service['description'] ?? ''); ?></textarea>
            </div>

            <!-- Image Upload -->
            <div class="form-group">
                <label>Service Image</label>
                
                <?php if (!empty($service['image_url'])): ?>
                <div class="current-image">
                    Current Image: <a href="/SP/<?php echo $service['image_url']; ?>" target="_blank"><?php echo basename($service['image_url']); ?></a>
                </div>
                <div class="image-preview" style="display:block;">
                    <img src="/SP/<?php echo $service['image_url']; ?>" alt="Current Image">
                </div>
                <?php endif; ?>
                
                <div class="image-upload-wrap" id="imageUploadWrap">
                    <div class="icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <p><?php echo !empty($service['image_url']) ? 'Click to change image' : 'Click to upload service image'; ?></p>
                    <p style="font-size:0.75rem;color:#555577;">JPG, PNG, WEBP or GIF · Max 5MB</p>
                    <div class="file-name" id="fileName"></div>
                </div>
                <input type="file" name="service_image" id="fileInput" accept="image/*">
                <div class="image-preview" id="newImagePreview" style="display:none;">
                    <img src="#" alt="Preview" id="previewImg">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Icon (FontAwesome)</label>
                    <input type="text" name="icon" placeholder="fa-print" value="<?php echo htmlspecialchars($service['icon'] ?? 'fa-print'); ?>">
                    <div class="hint">e.g., fa-print, fa-box, fa-palette</div>
                </div>
                <div class="form-group">
                    <label>Category <span class="required">*</span></label>
                    <select name="category" required>
                        <option value="">Select category</option>
                        <?php foreach ($categoryDropdown as $value => $label): ?>
                        <option value="<?php echo $value; ?>" <?php echo ($service['category'] ?? '') === $value ? 'selected' : ''; ?>>
                            <?php echo $label; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Starting Price (PKR) <span class="required">*</span></label>
                    <input type="number" name="starting_price" placeholder="0" step="0.01" required value="<?php echo htmlspecialchars($service['starting_price'] ?? 0); ?>">
                </div>
                <div class="form-group">
                    <label>Price Text</label>
                    <input type="text" name="price_text" placeholder="/ 500 pcs, starting, / piece" value="<?php echo htmlspecialchars($service['price_text'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Features / Highlights</label>
                    <input type="text" name="features" placeholder="High Quality, Bulk Pricing, Fast Delivery" value="<?php 
                        $features = !empty($service['features']) ? json_decode($service['features'], true) : [];
                        echo htmlspecialchars(implode(', ', $features)); 
                    ?>">
                    <div class="hint">Comma separated values</div>
                </div>
                <div class="form-group">
                    <label>Badge / Tag</label>
                    <input type="text" name="badge" placeholder="⭐ Popular, 🔥 Bestseller" value="<?php echo htmlspecialchars($service['badge'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Minimum Quantity</label>
                    <input type="number" name="min_quantity" placeholder="500" value="<?php echo htmlspecialchars($service['min_quantity'] ?? 0); ?>">
                </div>
                <div class="form-group">
                    <label>Turnaround Time</label>
                    <input type="text" name="turnaround" placeholder="3-5 days" value="<?php echo htmlspecialchars($service['turnaround'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="checkbox-group">
                    <input type="checkbox" name="featured" id="featured" <?php echo ($service['featured'] ?? 0) ? 'checked' : ''; ?>>
                    <label for="featured" style="margin:0;">Featured Service</label>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" name="show_in_pricing" id="show_in_pricing" <?php echo ($service['show_in_pricing'] ?? 1) ? 'checked' : ''; ?>>
                    <label for="show_in_pricing" style="margin:0;">Show in Pricing Table</label>
                </div>
            </div>

            <div style="display:flex;gap:12px;margin-top:24px;flex-wrap:wrap;">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Update Service
                </button>
                <a href="/SP/admin/services/" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadWrap = document.getElementById('imageUploadWrap');
    const fileInput = document.getElementById('fileInput');
    const fileName = document.getElementById('fileName');
    const previewDiv = document.getElementById('newImagePreview');
    const previewImg = document.getElementById('previewImg');

    uploadWrap.addEventListener('click', function() {
        fileInput.click();
    });

    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            fileName.textContent = '📎 ' + file.name;
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewDiv.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>

</body>
</html>
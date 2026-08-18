<?php
// ============================================
// ADMIN: Add Portfolio - Subhan Printers
// ============================================

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: /SP/admin/login.php');
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/Portfolio.php';
require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../../includes/functions.php';

$portfolioModel = new Portfolio();
$categoryModel = new Category();

// Fetch categories
$categories = $categoryModel->getAll(true);
$categoryDropdown = [];
foreach ($categories as $cat) {
    if (is_array($cat)) {
        $categoryDropdown[$cat['slug']] = $cat['name'];
    } else {
        $categoryDropdown[$cat->slug] = $cat->name;
    }
}

if (empty($categoryDropdown)) {
    $categoryDropdown = [
        'wedding' => 'Wedding Cards',
        'packaging' => 'Packaging',
        'flex' => 'Flex & Banners',
        'brochures' => 'Brochures',
        'stickers' => 'Stickers'
    ];
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $subtitle = trim($_POST['subtitle'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $client_name = trim($_POST['client_name'] ?? '');
    $tags = trim($_POST['tags'] ?? '');
    $featured = isset($_POST['featured']) ? 1 : 1; // Default featured
    
    $tagsArray = array_map('trim', explode(',', $tags));
    $tagsArray = array_filter($tagsArray);
    
    if (empty($title) || empty($category)) {
        $error = 'Title and category are required.';
    } else {
        $data = [
            'title' => $title,
            'subtitle' => $subtitle,
            'category' => $category,
            'description' => $description,
            'client_name' => $client_name,
            'tags' => $tagsArray,
            'featured' => $featured,
            'is_active' => 1
        ];
        
        $result = $portfolioModel->create($data);
        
        if ($result['success']) {
            $portfolioId = $result['id'];
            
            // Handle image upload
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                $uploadDir = __DIR__ . '/../../uploads/portfolio/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    if ($_FILES['images']['error'][$key] === 0) {
                        $filename = time() . '_' . basename($_FILES['images']['name'][$key]);
                        $filepath = $uploadDir . $filename;
                        
                        if (move_uploaded_file($tmp_name, $filepath)) {
                            $url = '/SP/uploads/portfolio/' . $filename;
                            $isPrimary = ($key === 0) ? 1 : 0;
                            $portfolioModel->addImage($portfolioId, $url, null, $isPrimary);
                        }
                    }
                }
            }
            
            header('Location: /SP/admin/portfolio/index.php?msg=added');
            exit;
        } else {
            $error = $result['error'] ?? 'Failed to add portfolio item.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Portfolio | Admin Panel</title>
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
        .form-group textarea { min-height: 120px; resize: vertical; }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 4px rgba(139,92,246,0.08);
        }
        .form-group select option { background: #1a1a2e; }
        .form-group .hint { font-size: 0.75rem; color: #555577; margin-top: 4px; }

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

        .file-upload {
            border: 2px dashed rgba(255,255,255,0.08);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            transition: 0.3s ease;
            cursor: pointer;
        }
        .file-upload:hover {
            border-color: rgba(139,92,246,0.3);
        }
        .file-upload i { font-size: 2rem; color: #555577; margin-bottom: 8px; display: block; }
        .file-upload p { color: #555577; font-size: 0.85rem; }

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
        <li><a href="/SP/admin/portfolio/" class="active"><i class="fas fa-images"></i><span>Portfolio</span></a></li>
        <li><a href="/SP/admin/products/"><i class="fas fa-cube"></i><span>Products</span></a></li>
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
        <h1>Add <span>Portfolio</span></h1>
        <div class="admin-info">
            <div>
                <div class="name"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></div>
                <div class="role" style="font-size:0.75rem;color:#555577;"><?php echo ucfirst($_SESSION['admin_role'] ?? 'Admin'); ?></div>
            </div>
            <div class="avatar"><?php echo strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)); ?></div>
        </div>
    </header>

    <a href="/SP/admin/portfolio/" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Portfolio
    </a>

    <?php if ($error): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
    </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" action="" enctype="multipart/form-data">
            
            <div class="form-row">
                <div class="form-group">
                    <label>Title <span class="required">*</span></label>
                    <input type="text" name="title" placeholder="Project title" required value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Category <span class="required">*</span></label>
                    <select name="category" required>
                        <option value="">Select category</option>
                        <?php foreach ($categoryDropdown as $value => $label): ?>
                        <option value="<?php echo htmlspecialchars($value); ?>" <?php echo (isset($_POST['category']) && $_POST['category'] === $value) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($label); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Subtitle</label>
                <input type="text" name="subtitle" placeholder="Short description (e.g., Gold foil · 150 cards)" value="<?php echo isset($_POST['subtitle']) ? htmlspecialchars($_POST['subtitle']) : ''; ?>">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" placeholder="Detailed project description"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Client Name</label>
                    <input type="text" name="client_name" placeholder="Client or brand name" value="<?php echo isset($_POST['client_name']) ? htmlspecialchars($_POST['client_name']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Tags</label>
                    <input type="text" name="tags" placeholder="Gold Foil, Embossed, Premium" value="<?php echo isset($_POST['tags']) ? htmlspecialchars($_POST['tags']) : ''; ?>">
                    <div class="hint">Comma separated values</div>
                </div>
            </div>

            <div class="form-group">
                <label>Images</label>
                <div class="file-upload" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Click to upload images</p>
                    <p style="font-size:0.7rem;color:#444466;">PNG, JPG, WebP (Max 5MB each)</p>
                </div>
                <input type="file" id="fileInput" name="images[]" multiple accept="image/*" style="display:none;">
                <div id="fileList" style="margin-top:10px;"></div>
            </div>

            <div class="form-group checkbox-group">
                <input type="checkbox" name="featured" id="featured" <?php echo (!isset($_POST['featured']) || $_POST['featured']) ? 'checked' : ''; ?>>
                <label for="featured" style="margin:0;">Show on Homepage</label>
            </div>

            <div style="display:flex;gap:12px;margin-top:24px;flex-wrap:wrap;">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Save Project
                </button>
                <a href="/SP/admin/portfolio/" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>

</main>

<script>
document.getElementById('fileInput').addEventListener('change', function(e) {
    const list = document.getElementById('fileList');
    list.innerHTML = '';
    const files = Array.from(this.files);
    files.forEach(file => {
        const item = document.createElement('div');
        item.style.cssText = 'display:flex;align-items:center;gap:8px;padding:6px 12px;background:rgba(255,255,255,0.04);border-radius:6px;margin-bottom:4px;font-size:0.85rem;';
        item.innerHTML = `<i class="fas fa-file-image" style="color:#8b5cf6;"></i> ${file.name} <span style="color:#555577;font-size:0.7rem;">(${(file.size/1024).toFixed(0)}KB)</span>`;
        list.appendChild(item);
    });
});
</script>

</body>
</html>
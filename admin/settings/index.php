<?php
// ============================================
// ADMIN: Settings - Subhan Printers
// ============================================

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: /SP/admin/login.php');
    exit;
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

$message = '';
$error = '';

// Get current settings
$settings = [];
try {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM settings");
    while ($row = $stmt->fetch()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
} catch (PDOException $e) {
    // Table might not exist yet - create it
    $db->exec("CREATE TABLE IF NOT EXISTS settings (
        id INT PRIMARY KEY AUTO_INCREMENT,
        setting_key VARCHAR(50) UNIQUE NOT NULL,
        setting_value TEXT NULL,
        setting_group VARCHAR(50) DEFAULT 'general',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_key (setting_key),
        INDEX idx_group (setting_group)
    )");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $general = [
        'site_name' => trim($_POST['site_name'] ?? ''),
        'site_tagline' => trim($_POST['site_tagline'] ?? ''),
        'site_description' => trim($_POST['site_description'] ?? ''),
        'admin_email' => trim($_POST['admin_email'] ?? ''),
        'admin_phone' => trim($_POST['admin_phone'] ?? ''),
    ];
    
    $social = [
        'facebook_url' => trim($_POST['facebook_url'] ?? ''),
        'instagram_url' => trim($_POST['instagram_url'] ?? ''),
        'whatsapp_number' => trim($_POST['whatsapp_number'] ?? ''),
        'youtube_url' => trim($_POST['youtube_url'] ?? ''),
        'linkedin_url' => trim($_POST['linkedin_url'] ?? ''),
    ];
    
    $business = [
        'business_address' => trim($_POST['business_address'] ?? ''),
        'business_hours' => trim($_POST['business_hours'] ?? ''),
        'map_embed' => trim($_POST['map_embed'] ?? ''),
    ];
    
    $allSettings = array_merge($general, $social, $business);
    
    try {
        $db = getDB();
        $db->beginTransaction();
        
        foreach ($allSettings as $key => $value) {
            $stmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) 
                                   VALUES (:key, :value) 
                                   ON DUPLICATE KEY UPDATE setting_value = :value");
            $stmt->execute([':key' => $key, ':value' => $value]);
        }
        
        $db->commit();
        $message = 'Settings updated successfully!';
        
        // Refresh settings
        $stmt = $db->query("SELECT * FROM settings");
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        
    } catch (Exception $e) {
        $db->rollBack();
        $error = 'Failed to update settings: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | Admin Panel</title>
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
            max-width: 800px;
        }

        .form-section {
            margin-bottom: 32px;
            padding-bottom: 32px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .form-section-title {
            font-weight: 700;
            color: #e8e8f0;
            font-size: 1.1rem;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-section-title i { color: #8b5cf6; }

        .form-group {
            margin-bottom: 16px;
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
        .form-group textarea {
            min-height: 80px;
            resize: vertical;
        }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 4px rgba(139,92,246,0.08);
        }
        .form-group select option { background: #1a1a2e; }
        .form-group .hint {
            font-size: 0.75rem;
            color: #555577;
            margin-top: 4px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
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

        .btn-demo {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            background: rgba(245,158,11,0.15);
            color: #f59e0b;
            border: none;
            cursor: pointer;
            transition: 0.3s ease;
        }
        .btn-demo:hover { background: rgba(245,158,11,0.25); }

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
        <li><a href="/SP/admin/categories/"><i class="fas fa-tags"></i><span>Categories</span></a></li>
        <li><a href="/SP/admin/services/" class="active"><i class="fas fa-cogs"></i><span>Services</span></a></li>
        <li class="section-title">Management</li>
        <li><a href="/SP/admin/newsletter/"><i class="fas fa-envelope"></i><span>Newsletter</span></a></li>
        <li><a href="/SP/admin/users/"><i class="fas fa-users"></i><span>Users</span></a></li>
        <li><a href="/SP/admin/settings/" class="active"><i class="fas fa-cog"></i><span>Settings</span></a></li>
    </ul>
    <div class="sidebar-logout">
        <a href="/SP/admin/logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
    </div>
</aside>

<!-- Main -->
<main class="main-content">

    <header class="top-header">
        <h1>Site <span>Settings</span></h1>
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
    <?php if ($error): ?>
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
    </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" action="">

            <!-- General Settings -->
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fas fa-globe"></i> General Settings
                </div>

                <div class="form-group">
                    <label>Site Name <span class="required">*</span></label>
                    <input type="text" name="site_name" placeholder="Subhan Printers" value="<?php echo htmlspecialchars($settings['site_name'] ?? 'Subhan Printers'); ?>">
                </div>

                <div class="form-group">
                    <label>Site Tagline</label>
                    <input type="text" name="site_tagline" placeholder="Professional Printing Services – Lahore" value="<?php echo htmlspecialchars($settings['site_tagline'] ?? 'Professional Printing Services – Lahore'); ?>">
                </div>

                <div class="form-group">
                    <label>Site Description</label>
                    <textarea name="site_description" rows="3" placeholder="Site meta description for SEO"><?php echo htmlspecialchars($settings['site_description'] ?? 'Subhan Printers – Professional graphic designing and printing services in Gawalmandi, Lahore, Pakistan.'); ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Admin Email <span class="required">*</span></label>
                        <input type="email" name="admin_email" placeholder="admin@subhanprinters.com" value="<?php echo htmlspecialchars($settings['admin_email'] ?? 'admin@subhanprinters.com'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Admin Phone</label>
                        <input type="text" name="admin_phone" placeholder="+92 300 1234567" value="<?php echo htmlspecialchars($settings['admin_phone'] ?? '+92 300 1234567'); ?>">
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fas fa-share-alt"></i> Social Media
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Facebook URL</label>
                        <input type="url" name="facebook_url" placeholder="https://facebook.com/yourpage" value="<?php echo htmlspecialchars($settings['facebook_url'] ?? '#'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Instagram URL</label>
                        <input type="url" name="instagram_url" placeholder="https://instagram.com/yourpage" value="<?php echo htmlspecialchars($settings['instagram_url'] ?? '#'); ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" placeholder="923001234567" value="<?php echo htmlspecialchars($settings['whatsapp_number'] ?? '923001234567'); ?>">
                        <div class="hint">Without + sign, just numbers</div>
                    </div>
                    <div class="form-group">
                        <label>YouTube URL</label>
                        <input type="url" name="youtube_url" placeholder="https://youtube.com/@channel" value="<?php echo htmlspecialchars($settings['youtube_url'] ?? '#'); ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>LinkedIn URL</label>
                    <input type="url" name="linkedin_url" placeholder="https://linkedin.com/company/yourpage" value="<?php echo htmlspecialchars($settings['linkedin_url'] ?? '#'); ?>">
                </div>
            </div>

            <!-- Business Settings -->
            <div class="form-section">
                <div class="form-section-title">
                    <i class="fas fa-store"></i> Business Information
                </div>

                <div class="form-group">
                    <label>Business Address</label>
                    <textarea name="business_address" rows="2" placeholder="Hamza Center, Gawalmandi, Lahore, Pakistan"><?php echo htmlspecialchars($settings['business_address'] ?? 'Hamza Center, Gawalmandi, Lahore, Pakistan'); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Business Hours</label>
                    <input type="text" name="business_hours" placeholder="Mon–Sat: 9 AM – 8 PM | Sun: 10 AM – 6 PM" value="<?php echo htmlspecialchars($settings['business_hours'] ?? 'Mon–Sat: 9 AM – 8 PM | Sun: 10 AM – 6 PM'); ?>">
                </div>

                <div class="form-group">
                    <label>Google Maps Embed URL</label>
                    <textarea name="map_embed" rows="3" placeholder="<iframe src='https://www.google.com/maps/embed?...'></iframe>"><?php echo htmlspecialchars($settings['map_embed'] ?? ''); ?></textarea>
                    <div class="hint">Paste the full iframe embed code from Google Maps</div>
                </div>
            </div>

            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Save Settings
                </button>
            </div>

        </form>
    </div>

</main>

</body>
</html>
<?php
// ============================================
// SUBHAN PRINTERS - Simple Header (With Google User Support)
// Use this on Login and Register pages
// ============================================

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ CHECK SESSION EXPIRY
require_once __DIR__ . '/../includes/session.php';
check_session_timeout();

// Set default page title if not set
$pageTitle = $pageTitle ?? 'Subhan Printers';
$currentPage = $currentPage ?? 'login';
$pageStyles = $pageStyles ?? 'login.css';
$pageScripts = $pageScripts ?? 'login.js';

// ✅ Check if user is logged in (for avatar display)
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? 'Guest';
$userEmail = $_SESSION['user_email'] ?? '';
$userAvatar = $_SESSION['user_avatar'] ?? '';
$userRole = $_SESSION['user_role'] ?? 'customer';

// ✅ Get first letter for avatar fallback
$firstLetter = strtoupper(substr($userName, 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($pageDescription ?? 'Subhan Printers - Login / Register'); ?>">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <!-- Favicon -->
    <link rel="icon" href="/SP/images/logo.png" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- Page-specific CSS -->
    <link rel="stylesheet" href="/SP/assets/css/<?php echo $pageStyles; ?>" />
    <?php if (isset($extraStyles)): ?>
        <?php foreach ($extraStyles as $style): ?>
            <link rel="stylesheet" href="/SP/assets/css/<?php echo $style; ?>" />
        <?php endforeach; ?>
    <?php endif; ?>
    <link rel="stylesheet" href="/SP/assets/css/payments.css">

    <style>
        :root {
            --bg: #0d0d14;
            --surface: #13131f;
            --surface2: #1a1a2e;
            --primary: #8b5cf6;
            --primary2: #6d28d9;
            --gold: #f59e0b;
            --text: #e8e8f0;
            --muted: #8888aa;
            --success: #10b981;
            --error: #ef4444;
            --border: rgba(255,255,255,0.08);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            color: white;
            overflow-x: hidden;
            position: relative;
        }

        /* Background Blobs */
        .bg-blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(120px);
            opacity: .18;
            z-index: 0;
            pointer-events: none;
            will-change: transform;
            animation: blobFloat 20s ease-in-out infinite alternate;
        }
        .bg-blob.purple {
            width: 500px;
            height: 500px;
            background: rgba(139, 92, 246, .4);
            top: -150px;
            left: -150px;
        }
        .bg-blob.gold {
            width: 450px;
            height: 450px;
            background: rgba(245, 158, 11, .25);
            bottom: -150px;
            right: -150px;
            animation-delay: -10s;
        }

        @keyframes blobFloat {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, -30px) scale(1.1); }
        }

        /* Simple Header with User Support */
        .header-simple {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            padding: 12px 0;
            background: rgba(13, 13, 20, .85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, .06);
            transition: all .4s cubic-bezier(.4, 0, .2, 1);
        }
        .header-simple.scrolled {
            padding: 8px 0;
            background: rgba(13, 13, 20, .95);
            box-shadow: 0 10px 30px rgba(0, 0, 0, .4);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: white;
            padding: 8px 16px;
            transition: all .3s ease;
        }
        .nav-logo img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            transition: transform .3s ease;
        }
        .nav-logo:hover img {
            transform: rotate(10deg) scale(1.1);
        }

        .nav-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 800;
        }
        .nav-brand .brand-first { color: white; }
        .nav-brand .brand-second { color: var(--primary); }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 5px;
            list-style: none;
        }
        .nav-links > li { position: relative; }
        .nav-links > li > a {
            color: var(--text);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            padding: 10px 18px;
            border-radius: 8px;
            position: relative;
            transition: all .3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .nav-links > li > a::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 50%;
            transform: translateX(-50%) scaleX(0);
            width: 20px;
            height: 2px;
            background: var(--primary);
            border-radius: 2px;
            transition: transform .3s ease;
        }
        .nav-links > li > a:hover::after,
        .nav-links > li > a.active::after {
            transform: translateX(-50%) scaleX(1);
        }
        .nav-links > li > a:hover { color: white; }
        .nav-links > li > a.active { color: white; }

        .dropdown-toggle .dropdown-icon {
            font-size: 12px;
            transition: transform .3s ease;
        }
        .dropdown-toggle:hover .dropdown-icon {
            transform: rotate(180deg);
        }

        .dropdown-menu {
            position: absolute;
            top: calc(100% + 10px);
            left: 0;
            min-width: 240px;
            background: rgba(19, 19, 31, .98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 14px;
            padding: 10px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all .3s cubic-bezier(.4, 0, .2, 1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, .4);
            list-style: none;
        }
        .dropdown-menu::before {
            content: '';
            position: absolute;
            top: -6px;
            left: 30px;
            width: 12px;
            height: 12px;
            background: rgba(19, 19, 31, .98);
            border-left: 1px solid rgba(255, 255, 255, .08);
            border-top: 1px solid rgba(255, 255, 255, .08);
            transform: rotate(45deg);
        }
        .nav-links > li:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .dropdown-menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-radius: 10px;
            transition: all .3s ease;
        }
        .dropdown-menu li a:hover {
            background: rgba(139, 92, 246, .1);
            color: white;
            padding-left: 20px;
        }
        .dropdown-menu li a i {
            width: 20px;
            text-align: center;
            color: var(--primary);
            font-size: 14px;
        }
        .dd-divider { height: 1px; background: var(--border); margin: 6px 10px; }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* ✅ User Avatar & Name */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px 6px 6px;
            border-radius: 50px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            transition: all .3s ease;
            cursor: default;
        }
        .user-profile:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(139, 92, 246, 0.3);
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
            flex-shrink: 0;
        }
        
        .user-avatar-placeholder {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            background: linear-gradient(135deg, var(--primary), var(--primary2));
            color: white;
            flex-shrink: 0;
            border: 2px solid var(--primary);
        }
        
        .user-name-display {
            font-size: 0.85rem;
            font-weight: 500;
            color: white;
            white-space: nowrap;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .user-name-display .highlight {
            color: var(--gold);
        }
        
        .user-role-badge {
            font-size: 0.6rem;
            padding: 2px 8px;
            border-radius: 10px;
            background: rgba(139, 92, 246, 0.2);
            color: var(--primary);
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .user-role-badge.admin {
            background: rgba(245, 158, 11, 0.2);
            color: var(--gold);
        }

        .user-logout-btn {
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            font-size: 0.8rem;
            padding: 4px 8px;
            border-radius: 4px;
            transition: all .3s ease;
        }
        .user-logout-btn:hover {
            color: var(--error);
            background: rgba(239, 68, 68, 0.1);
        }

        /* Order Now Button */
        .btn-order {
            padding: 10px 24px;
            background: linear-gradient(135deg, var(--gold), #d97706);
            border: none;
            color: white;
            font-size: 14px;
            font-weight: 600;
            font-family: inherit;
            border-radius: 50px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all .3s ease;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(245, 158, 11, .4);
        }
        .btn-order:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245, 158, 11, .6);
        }

        .hamburger-menu {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 8px;
            z-index: 1001;
        }
        .hamburger-menu span {
            width: 28px;
            height: 3px;
            background: white;
            border-radius: 3px;
            transition: all .3s ease;
            transform-origin: center;
        }
        .hamburger-menu.active span:nth-child(1) { transform: rotate(45deg) translate(6px, 6px); }
        .hamburger-menu.active span:nth-child(2) { opacity: 0; transform: scaleX(0); }
        .hamburger-menu.active span:nth-child(3) { transform: rotate(-45deg) translate(6px, -6px); }

        .mobile-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: min(320px, 80vw);
            height: 100vh;
            background: rgba(19, 19, 31, .98);
            backdrop-filter: blur(20px);
            z-index: 999;
            padding: 100px 30px 30px;
            transition: right .4s cubic-bezier(.4, 0, .2, 1);
            border-left: 1px solid rgba(255, 255, 255, .08);
            overflow-y: auto;
        }
        .mobile-menu.active { right: 0; }
        .mobile-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text);
            text-decoration: none;
            padding: 16px 0;
            font-size: 16px;
            font-weight: 500;
            border-bottom: 1px solid rgba(255, 255, 255, .05);
            transition: color .3s ease, padding-left .3s ease;
        }
        .mobile-menu a:hover {
            color: var(--primary);
            padding-left: 10px;
        }
        .mobile-menu .mobile-user-info {
            padding: 16px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .mobile-menu .mobile-user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
        }
        .mobile-menu .mobile-user-name {
            font-weight: 600;
            color: white;
        }
        .mobile-menu .mobile-user-email {
            font-size: 0.8rem;
            color: var(--muted);
        }

        .mobile-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .6);
            backdrop-filter: blur(4px);
            z-index: 998;
            opacity: 0;
            visibility: hidden;
            transition: all .3s ease;
        }
        .mobile-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .nav-links { display: none; }
            .hamburger-menu { display: flex; }
        }
        @media (max-width: 768px) {
            .nav-actions .btn-order { padding: 8px 16px; font-size: 12px; }
            .nav-brand { font-size: 1.1rem; }
            .nav-container { padding: 0 15px; }
            .user-name-display { max-width: 80px; font-size: 0.75rem; }
            .user-profile { padding: 4px 8px 4px 4px; }
            .user-avatar, .user-avatar-placeholder { width: 28px; height: 28px; }
        }
        @media (max-width: 480px) {
            .user-name-display { display: none; }
            .user-profile { padding: 4px; }
        }
    </style>
</head>
<body>

<!-- Background Blobs -->
<div class="bg-blob purple"></div>
<div class="bg-blob gold"></div>

<!-- ==========================================
     SIMPLE HEADER (With Google User Support)
     ========================================== -->
<header class="header-simple" id="headerHome">
    <nav class="nav-container">
        <a href="/SP/" class="nav-logo">
            <img src="/SP/images/logo.png" alt="Subhan Printers Logo">
            <span class="nav-brand">
                <span class="brand-first">Subhan</span>
                <span class="brand-second">Printers</span>
            </span>
        </a>
        <ul class="nav-links">
            <li><a href="/SP/">Home</a></li>
            <li><a href="/SP/services">Services</a></li>
            <li>
                <a href="/SP/portfolio" class="dropdown-toggle">
                    Portfolio <i class="fas fa-chevron-down dropdown-icon"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="/SP/portfolio"><i class="fas fa-th-large"></i> All Projects</a></li>
                    <li><a href="/SP/portfolio?cat=wedding"><i class="fas fa-heart"></i> Wedding Cards</a></li>
                    <li><a href="/SP/portfolio?cat=packaging"><i class="fas fa-box"></i> Box Packaging</a></li>
                    <li><a href="/SP/portfolio?cat=flex"><i class="fas fa-image"></i> Flex &amp; Banners</a></li>
                    <div class="dd-divider"></div>
                    <li><a href="/SP/portfolio?cat=brochures"><i class="fas fa-file-alt"></i> Brochures</a></li>
                    <li><a href="/SP/portfolio?cat=stickers"><i class="fas fa-tags"></i> Stickers</a></li>
                </ul>
            </li>
            <li><a href="/SP/about">About</a></li>
            <li><a href="/SP/contact">Contact</a></li>
        </ul>
        <div class="nav-actions">
            <?php if ($isLoggedIn): ?>
                <!-- ✅ Show User Profile when logged in -->
                <div class="user-profile">
                    <?php if ($userAvatar): ?>
                        <img src="<?php echo htmlspecialchars($userAvatar); ?>" alt="<?php echo htmlspecialchars($userName); ?>" class="user-avatar">
                    <?php else: ?>
                        <div class="user-avatar-placeholder"><?php echo $firstLetter; ?></div>
                    <?php endif; ?>
                    <span class="user-name-display">
                        <?php echo htmlspecialchars($userName); ?>
                        <?php if ($userRole === 'admin'): ?>
                            <span class="user-role-badge admin">Admin</span>
                        <?php endif; ?>
                    </span>
                    <a href="/SP/logout" class="user-logout-btn" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            <?php endif; ?>
            
            <!-- Order Now Button -->
            <a href="/SP/order" class="btn-order">
                <i class="fas fa-shopping-cart"></i> Order Now
            </a>
        </div>
        <div class="hamburger-menu" id="hamburgerMenu">
            <span></span><span></span><span></span>
        </div>
    </nav>
</header>

<div class="mobile-overlay" id="mobileOverlay"></div>
<div class="mobile-menu" id="mobileMenu">
    <?php if ($isLoggedIn): ?>
        <!-- ✅ Mobile User Info -->
        <div class="mobile-user-info">
            <?php if ($userAvatar): ?>
                <img src="<?php echo htmlspecialchars($userAvatar); ?>" alt="<?php echo htmlspecialchars($userName); ?>" class="mobile-user-avatar">
            <?php else: ?>
                <div class="user-avatar-placeholder" style="width:40px;height:40px;font-size:1.2rem;"><?php echo $firstLetter; ?></div>
            <?php endif; ?>
            <div>
                <div class="mobile-user-name"><?php echo htmlspecialchars($userName); ?></div>
                <div class="mobile-user-email"><?php echo htmlspecialchars($userEmail); ?></div>
            </div>
        </div>
    <?php endif; ?>
    
    <a href="/SP/"><i class="fas fa-home"></i> Home</a>
    <a href="/SP/services"><i class="fas fa-cogs"></i> Services</a>
    <a href="/SP/portfolio"><i class="fas fa-images"></i> Portfolio</a>
    <a href="/SP/about"><i class="fas fa-info-circle"></i> About</a>
    <a href="/SP/contact"><i class="fas fa-envelope"></i> Contact</a>
    
    <?php if ($isLoggedIn): ?>
        <!-- ✅ Mobile Logout -->
        <a href="/SP/dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="/SP/logout" style="color:var(--error);"><i class="fas fa-sign-out-alt"></i> Logout</a>
    <?php endif; ?>
    
    <a href="/SP/order" style="color:var(--gold);"><i class="fas fa-shopping-cart"></i> Order Now</a>
</div>

<!-- ==========================================
     PAGE CONTENT STARTS HERE
     ========================================== -->
<main>
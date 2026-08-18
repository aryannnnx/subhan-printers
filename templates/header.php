<?php
// ============================================
// SUBHAN PRINTERS - Full Header
// Use this on ALL pages EXCEPT login and register
// ============================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ CHECK SESSION EXPIRY ON EVERY PAGE
require_once __DIR__ . '/../includes/session.php';
check_session_timeout(); // ← This will auto-logout if session expired

// Set default page title if not set
$pageTitle = $pageTitle ?? 'Subhan Printers | Professional Printing Services – Lahore';
$currentPage = $currentPage ?? 'home';
$pageStyles = $pageStyles ?? 'index.css';
$pageScripts = $pageScripts ?? 'index.js';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? 'Guest';
$userEmail = $_SESSION['user_email'] ?? '';
$userAvatar = $_SESSION['user_avatar'] ?? '';
$userRole = $_SESSION['user_role'] ?? 'customer';

// ✅ Get first letter for avatar fallback
$firstLetter = strtoupper(substr($userName, 0, 1));
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Subhan Printers – Professional graphic designing and printing services in Gawalmandi, Lahore, Pakistan. Wedding cards, packaging, flex banners, brochures & more.">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <!-- Favicon -->
    <link rel="icon" href="/SP/images/logo.png" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <!-- CSS -->
    <link rel="stylesheet" href="/SP/assets/css/<?php echo $pageStyles; ?>" />
    
    <?php if (isset($extraStyles)): ?>
        <?php foreach ($extraStyles as $style): ?>
            <link rel="stylesheet" href="/SP/assets/css/<?php echo $style; ?>" />
        <?php endforeach; ?>
    <?php endif; ?>
    
    <link rel="stylesheet" href="/SP/assets/css/payments.css" />

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

        /* ==========================================
           ANIMATED BACKGROUND BLOBS
           ========================================== */
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

        /* ==========================================
           HEADER
           ========================================== */
        .header-home {
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
        .header-home.scrolled {
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

        /* ==========================================
           USER PROFILE DROPDOWN
           ========================================== */
        .user-profile {
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 4px 12px 4px 4px;
            border-radius: 50px;
            border: 1px solid transparent;
            transition: all .3s ease;
        }
        .user-profile:hover {
            border-color: rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.03);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--primary2));
            flex-shrink: 0;
            overflow: hidden;
        }
        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .user-avatar .online-dot {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 10px;
            height: 10px;
            background: var(--success);
            border-radius: 50%;
            border: 2px solid var(--bg);
        }

        .user-name {
            font-size: 14px;
            font-weight: 500;
            color: var(--text);
            white-space: nowrap;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-name .highlight {
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
            margin-left: 4px;
        }
        .user-role-badge.admin {
            background: rgba(245, 158, 11, 0.2);
            color: var(--gold);
        }

        .user-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            min-width: 240px;
            background: rgba(19, 19, 31, .98);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 8px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all .3s cubic-bezier(.4, 0, .2, 1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, .4);
            z-index: 200;
        }
        .user-profile:hover .user-dropdown,
        .user-dropdown.open {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown .dropdown-header {
            padding: 8px 12px 12px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 6px;
        }
        .user-dropdown .dropdown-user-name {
            font-weight: 600;
            color: white;
            font-size: 0.95rem;
        }
        .user-dropdown .dropdown-user-email {
            font-size: 0.8rem;
            color: var(--muted);
        }

        .user-dropdown .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            color: var(--muted);
            font-size: 14px;
            font-weight: 500;
            transition: all .3s ease;
            text-decoration: none;
        }
        .user-dropdown .dropdown-item:hover {
            background: rgba(139,92,246,0.1);
            color: white;
        }
        .user-dropdown .dropdown-item i {
            width: 18px;
            text-align: center;
            color: var(--primary);
            font-size: 14px;
        }
        .user-dropdown .dropdown-divider {
            height: 1px;
            background: var(--border);
            margin: 6px 10px;
        }
        .user-dropdown .dropdown-item.logout {
            color: var(--error);
        }
        .user-dropdown .dropdown-item.logout i {
            color: var(--error);
        }
        .user-dropdown .dropdown-item.logout:hover {
            background: rgba(239,68,68,0.1);
        }

        /* Buttons */
        .btn-login {
            padding: 10px 24px;
            background: #6d28d9;
            color: white;
            font-size: 14px;
            font-weight: 500;
            font-family: inherit;
            border-radius: 50px;
            cursor: pointer;
            transition: all .3s ease;
            text-decoration: none;
            border: none;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(124, 13, 222, .6);
        }

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

        /* Hamburger Menu */
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

        /* Mobile Menu */
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
        .mobile-menu .mobile-user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            margin-bottom: 16px;
        }
        .mobile-menu .mobile-user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
        }
        .mobile-menu .mobile-user-name {
            font-weight: 600;
            color: white;
            font-size: 1rem;
        }
        .mobile-menu .mobile-user-email {
            font-size: 0.8rem;
            color: var(--muted);
        }
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

        @media (max-width: 900px) {
            .nav-links { display: none; }
            .nav-actions .btn-login { display: none; }
            .hamburger-menu { display: flex; }
        }
        @media (max-width: 768px) {
            .nav-actions .btn-order { padding: 8px 16px; font-size: 12px; }
            .nav-brand { font-size: 1.1rem; }
            .nav-container { padding: 0 15px; }
            .user-name { display: none; }
            .user-profile { padding: 4px; }
        }
        @media (max-width: 480px) {
            .user-profile { padding: 2px; }
            .user-avatar { width: 30px; height: 30px; font-size: 12px; }
        }
    </style>
</head>
<body>

<!-- Background Blobs -->
<div class="bg-blob purple"></div>
<div class="bg-blob gold"></div>

<!-- ==========================================
     HEADER
     ========================================== -->
<header class="header-home" id="headerHome">
    <nav class="nav-container">
        <a href="/SP/" class="nav-logo">
            <img src="/SP/images/logo.png" alt="Subhan Printers Logo">
            <span class="nav-brand">
                <span class="brand-first">Subhan</span>
                <span class="brand-second">Printers</span>
            </span>
        </a>
        <ul class="nav-links">
            <li><a href="/SP/" class="<?php echo $currentPage === 'home' ? 'active' : ''; ?>">Home</a></li>
            <li><a href="/SP/services" class="<?php echo $currentPage === 'services' ? 'active' : ''; ?>">Services</a></li>
            <li>
                <a href="/SP/portfolio" class="dropdown-toggle <?php echo $currentPage === 'portfolio' ? 'active' : ''; ?>">
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
            <li><a href="/SP/about" class="<?php echo $currentPage === 'about' ? 'active' : ''; ?>">About</a></li>
            <li><a href="/SP/contact" class="<?php echo $currentPage === 'contact' ? 'active' : ''; ?>">Contact</a></li>
        </ul>
        <div class="nav-actions">
            <?php if ($isLoggedIn): ?>
                <!-- ==========================================
                     USER PROFILE DROPDOWN
                     ========================================== -->
                <div class="user-profile" id="userProfile">
                    <div class="user-avatar" id="userAvatar" style="position:relative;">
                        <?php if (!empty($userAvatar)): ?>
                            <img src="<?php echo htmlspecialchars($userAvatar); ?>" alt="<?php echo htmlspecialchars($userName); ?>">
                        <?php else: ?>
                            <?php echo $firstLetter; ?>
                        <?php endif; ?>
                    </div>
                    <span class="user-name">
                        <?php echo htmlspecialchars($userName); ?>
                        <?php if ($userRole === 'admin'): ?>
                            <span class="user-role-badge admin">Admin</span>
                        <?php elseif ($userRole === 'staff'): ?>
                            <span class="user-role-badge">Staff</span>
                        <?php endif; ?>
                    </span>
                    <i class="fas fa-chevron-down" style="font-size:10px;color:var(--muted);transition:transform .3s ease;"></i>
                    
                    <!-- Dropdown -->
                    <div class="user-dropdown" id="userDropdown">
                        <div class="dropdown-header">
                            <div class="dropdown-user-name"><?php echo htmlspecialchars($userName); ?></div>
                            <div class="dropdown-user-email"><?php echo htmlspecialchars($userEmail); ?></div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="/SP/dashboard" class="dropdown-item">
                            <i class="fas fa-th-large"></i> Dashboard
                        </a>
                        <a href="/SP/profile" class="dropdown-item">
                            <i class="fas fa-user"></i> My Profile
                        </a>
                        <a href="/SP/orders" class="dropdown-item">
                            <i class="fas fa-shopping-bag"></i> My Orders
                        </a>
                        <?php if ($userRole === 'admin'): ?>
                            <div class="dropdown-divider"></div>
                            <a href="/SP/admin" class="dropdown-item" style="color:var(--gold);">
                                <i class="fas fa-crown" style="color:var(--gold);"></i> Admin Panel
                            </a>
                        <?php endif; ?>
                        <div class="dropdown-divider"></div>
                        <a href="/SP/logout" class="dropdown-item logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <a href="/SP/login" class="btn-login"><i class="fas fa-user"></i> Login</a>
            <?php endif; ?>
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
        <!-- Mobile User Info -->
        <div class="mobile-user-info">
            <?php if (!empty($userAvatar)): ?>
                <img src="<?php echo htmlspecialchars($userAvatar); ?>" alt="<?php echo htmlspecialchars($userName); ?>" class="mobile-user-avatar">
            <?php else: ?>
                <div class="user-avatar" style="width:44px;height:44px;font-size:1.2rem;flex-shrink:0;">
                    <?php echo $firstLetter; ?>
                </div>
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
        <a href="/SP/dashboard" style="color:var(--primary);"><i class="fas fa-th-large"></i> Dashboard</a>
        <a href="/SP/profile" style="color:var(--text);"><i class="fas fa-user"></i> My Profile</a>
        <a href="/SP/orders" style="color:var(--text);"><i class="fas fa-shopping-bag"></i> My Orders</a>
        <?php if ($userRole === 'admin'): ?>
            <a href="/SP/admin" style="color:var(--gold);"><i class="fas fa-crown"></i> Admin Panel</a>
        <?php endif; ?>
        <a href="/SP/logout" style="color:var(--error);"><i class="fas fa-sign-out-alt"></i> Logout</a>
    <?php else: ?>
        <a href="/SP/login" style="color:var(--primary);"><i class="fas fa-user"></i> Login</a>
        <a href="/SP/register" style="color:var(--gold);"><i class="fas fa-user-plus"></i> Register</a>
    <?php endif; ?>
    
    <a href="/SP/order" style="color:var(--gold);"><i class="fas fa-shopping-cart"></i> Order Now</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Header scroll effect
    const header = document.getElementById('headerHome');
    if (header) {
        window.addEventListener('scroll', function() {
            header.classList.toggle('scrolled', window.scrollY > 50);
        }, { passive: true });
    }

    // User dropdown toggle (for click support on mobile)
    const userProfile = document.getElementById('userProfile');
    const userDropdown = document.getElementById('userDropdown');
    
    if (userProfile && userDropdown) {
        // Toggle on click for mobile
        userProfile.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('open');
        });
        
        // Close dropdown on outside click
        document.addEventListener('click', function(e) {
            if (!userProfile.contains(e.target)) {
                userDropdown.classList.remove('open');
            }
        });
    }

    // Mobile menu toggle
    const hamburger = document.getElementById('hamburgerMenu');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileOverlay = document.getElementById('mobileOverlay');

    if (hamburger && mobileMenu && mobileOverlay) {
        function toggleMobile() {
            hamburger.classList.toggle('active');
            mobileMenu.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
            document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
        }

        hamburger.addEventListener('click', toggleMobile);
        mobileOverlay.addEventListener('click', toggleMobile);

        document.querySelectorAll('.mobile-menu a').forEach(function(link) {
            link.addEventListener('click', function() {
                if (mobileMenu.classList.contains('active')) {
                    toggleMobile();
                }
            });
        });
    }

    console.log('✅ Header loaded: User = <?php echo $isLoggedIn ? htmlspecialchars($userName) : 'Guest'; ?>');
});
</script>

<!-- ==========================================
     PAGE CONTENT STARTS HERE
     ========================================== -->
<main>
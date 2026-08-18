<?php
// ============================================
// SUBHAN PRINTERS - Main Entry Point (Router)
// ============================================

// Start session
session_start();

// Load environment variables from .env
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env');
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        putenv("$key=$value");
        $_ENV[$key] = $value;
    }
}

// Load helper functions
require_once __DIR__ . '/includes/functions.php';

// Load configuration
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/firebase.php';
require_once __DIR__ . '/config/email.php';

// Debug mode
if (getenv('APP_DEBUG') === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Simple routing
$request = $_SERVER['REQUEST_URI'];
$request = strtok($request, '?');
$request = trim($request, '/');

// Remove the base folder from URL (SP/)
$baseFolder = 'SP';
if (strpos($request, $baseFolder) === 0) {
    $request = substr($request, strlen($baseFolder));
    $request = ltrim($request, '/');
}

// API routes are handled by .htaccess
if (strpos($request, 'api/') === 0) {
    $apiFile = __DIR__ . '/' . $request . '.php';
    if (file_exists($apiFile)) {
        require_once $apiFile;
        exit;
    }
}

// Admin routes
if (strpos($request, 'admin') === 0) {
    $adminFile = __DIR__ . '/' . $request . '.php';
    if (file_exists($adminFile)) {
        require_once $adminFile;
        exit;
    }
}

// Default: Load the main page
$page = $request ?: 'home';

// Map URL to PHP file
$pageMap = [
    'home' => 'pages/home.php',
    'services' => 'pages/services.php',
    'portfolio' => 'pages/portfolio.php',
    'order' => 'pages/order.php',
    'contact' => 'pages/contact.php',
    'about' => 'pages/about.php',
    'login' => 'pages/login.php',
    'register' => 'pages/register.php',
    'forgot-password' => 'pages/forgot-password.php',
    'privacy' => 'pages/privacy.php',
    'terms' => 'pages/terms.php'
];

// Check if the page exists in the map
if (isset($pageMap[$page])) {
    $pageFile = __DIR__ . '/' . $pageMap[$page];
    if (file_exists($pageFile)) {
        require_once $pageFile;
    } else {
        // Page file not found
        http_response_code(404);
        echo "404 - Page not found: " . $pageFile;
    }
} else {
    // Try loading as custom page
    $customPage = __DIR__ . '/pages/' . $page . '.php';
    if (file_exists($customPage)) {
        require_once $customPage;
    } else {
        // 404 - Page not found
        http_response_code(404);
        echo "404 - Page not found: " . $page;
    }
}
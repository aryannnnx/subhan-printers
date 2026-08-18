<?php
// ============================================
// API: Portfolio - Get portfolio items
// ============================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Load required files
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Portfolio.php';
require_once __DIR__ . '/../includes/functions.php';

try {
    $portfolio = new Portfolio();
    
    // Get single portfolio item by ID
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $data = $portfolio->getById((int)$_GET['id']);
        
        if ($data) {
            json_response([
                'success' => true,
                'data' => $data
            ]);
        } else {
            json_response([
                'success' => false,
                'message' => 'Portfolio item not found'
            ], 404);
        }
        exit;
    }
    
    // Get single portfolio item by slug
    if (isset($_GET['slug'])) {
        $data = $portfolio->getBySlug($_GET['slug']);
        
        if ($data) {
            json_response([
                'success' => true,
                'data' => $data
            ]);
        } else {
            json_response([
                'success' => false,
                'message' => 'Portfolio item not found'
            ], 404);
        }
        exit;
    }
    
    // Build filters
    $filters = [];
    
    if (isset($_GET['category']) && !empty($_GET['category']) && $_GET['category'] !== 'all') {
        $filters['category'] = $_GET['category'];
    }
    
    if (isset($_GET['featured']) && $_GET['featured'] === 'true') {
        $filters['featured'] = true;
    }
    
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $filters['search'] = $_GET['search'];
    }
    
    if (isset($_GET['limit']) && is_numeric($_GET['limit'])) {
        $filters['limit'] = (int)$_GET['limit'];
    }
    
    if (isset($_GET['offset']) && is_numeric($_GET['offset'])) {
        $filters['offset'] = (int)$_GET['offset'];
    }
    
    // Get data
    $data = $portfolio->getAll($filters);
    $categories = $portfolio->getCategories();
    $total = $portfolio->getCount($filters);
    
    json_response([
        'success' => true,
        'data' => $data,
        'categories' => $categories,
        'total' => $total,
        'filters' => $filters
    ]);
    
} catch (Exception $e) {
    error_log("Portfolio API error: " . $e->getMessage());
    json_response([
        'success' => false,
        'message' => 'Server error occurred'
    ], 500);
}
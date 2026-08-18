<?php
// ============================================
// API: Products - Get product data for order page
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
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../includes/functions.php';

try {
    $product = new Product();
    
    // Get single product by ID
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $data = $product->getById((int)$_GET['id']);
        
        if ($data) {
            json_response([
                'success' => true,
                'data' => $data
            ]);
        } else {
            json_response([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        exit;
    }
    
    // Get single product by slug
    if (isset($_GET['slug'])) {
        $data = $product->getBySlug($_GET['slug']);
        
        if ($data) {
            json_response([
                'success' => true,
                'data' => $data
            ]);
        } else {
            json_response([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
        exit;
    }
    
    // Get pricing table data
    if (isset($_GET['type']) && $_GET['type'] === 'pricing') {
        $data = $product->getPricingTable();
        json_response([
            'success' => true,
            'data' => $data
        ]);
        exit;
    }
    
    // Get products with filters
    $filters = [];
    
    if (isset($_GET['category']) && !empty($_GET['category'])) {
        $filters['category'] = $_GET['category'];
    }
    
    if (isset($_GET['featured']) && $_GET['featured'] === 'true') {
        $filters['featured'] = true;
    }
    
    if (isset($_GET['limit']) && is_numeric($_GET['limit'])) {
        $filters['limit'] = (int)$_GET['limit'];
    }
    
    $data = $product->getAll($filters);
    $categories = $product->getCategories();
    
    json_response([
        'success' => true,
        'data' => $data,
        'categories' => $categories,
        'total' => count($data)
    ]);
    
} catch (Exception $e) {
    error_log("Products API error: " . $e->getMessage());
    json_response([
        'success' => false,
        'message' => 'Server error occurred'
    ], 500);
}
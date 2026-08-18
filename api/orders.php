<?php
// ============================================
// API: Orders - Create and get orders
// ============================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Load required files
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../config/email.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validation.php';
require_once __DIR__ . '/../includes/session.php';

try {
    $order = new Order();
    
    // GET - Fetch orders
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Check if user is logged in for protected endpoints
        $isLoggedIn = is_logged_in();
        
        // Get single order by ID
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $data = $order->getById((int)$_GET['id']);
            
            if ($data) {
                // Check authorization (only owner or admin can view)
                if ($isLoggedIn && ($_SESSION['user_id'] == $data['user_id'] || is_admin())) {
                    $data['timeline'] = $order->getTimeline($data['id']);
                    json_response(['success' => true, 'data' => $data]);
                } else {
                    json_response(['success' => false, 'message' => 'Unauthorized'], 401);
                }
            } else {
                json_response(['success' => false, 'message' => 'Order not found'], 404);
            }
            exit;
        }
        
        // Get order by order number (public - for tracking)
        if (isset($_GET['order_number'])) {
            $data = $order->getByOrderNumber($_GET['order_number']);
            
            if ($data) {
                $data['timeline'] = $order->getTimeline($data['id']);
                json_response(['success' => true, 'data' => $data]);
            } else {
                json_response(['success' => false, 'message' => 'Order not found'], 404);
            }
            exit;
        }
        
        // Get orders by email (for guest tracking)
        if (isset($_GET['email']) && validate_email($_GET['email'])) {
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            
            $data = $order->getByEmail($_GET['email'], $limit, $offset);
            json_response([
                'success' => true,
                'data' => $data,
                'total' => count($data)
            ]);
            exit;
        }
        
        // Get orders by user (requires login)
        if ($isLoggedIn) {
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            
            $data = $order->getByUser($_SESSION['user_id'], $limit, $offset);
            json_response([
                'success' => true,
                'data' => $data
            ]);
            exit;
        }
        
        // Admin: Get all orders with filters
        if (is_admin()) {
            $filters = [];
            
            if (isset($_GET['status'])) $filters['status'] = $_GET['status'];
            if (isset($_GET['payment_status'])) $filters['payment_status'] = $_GET['payment_status'];
            if (isset($_GET['search'])) $filters['search'] = $_GET['search'];
            if (isset($_GET['email'])) $filters['email'] = $_GET['email'];
            if (isset($_GET['date_from'])) $filters['date_from'] = $_GET['date_from'];
            if (isset($_GET['date_to'])) $filters['date_to'] = $_GET['date_to'];
            
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            
            $data = $order->getAll($filters, $limit, $offset);
            json_response([
                'success' => true,
                'data' => $data
            ]);
            exit;
        }
        
        // Not authenticated
        json_response([
            'success' => false,
            'message' => 'Please login to view orders'
        ], 401);
        exit;
    }
    
    // POST - Create new order
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get input data
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $input = $_POST;
        }
        
        // Validation rules
        $rules = [
            'customer_name' => 'required|min:2|max:100',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|phone',
            'product_type' => 'required|in:wedding_cards,box_packaging,flex_banners,business_cards,brochures,stickers,logo_design,corrugated_boxes',
            'quantity' => 'required|numeric|min:1'
        ];
        
        $validator = validate($input, $rules);
        
        if (!$validator->validate()) {
            json_response([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->getErrors()
            ], 400);
            exit;
        }
        
        // Get validated data
        $data = $validator->getValidated();
        
        // Add optional fields
        $data['user_id'] = is_logged_in() ? $_SESSION['user_id'] : null;
        $data['customer_address'] = $input['customer_address'] ?? null;
        $data['customer_city'] = $input['customer_city'] ?? null;
        $data['size'] = $input['size'] ?? null;
        $data['paper_type'] = $input['paper_type'] ?? null;
        $data['finishing'] = $input['finishing'] ?? null;
        $data['description'] = $input['description'] ?? null;
        $data['custom_specs'] = $input['custom_specs'] ?? null;
        $data['has_design'] = $input['has_design'] ?? false;
        $data['design_file_url'] = $input['design_file_url'] ?? null;
        $data['design_notes'] = $input['design_notes'] ?? null;
        $data['delivery_method'] = $input['delivery_method'] ?? null;
        $data['notes'] = $input['notes'] ?? null;
        $data['source'] = $input['source'] ?? 'website';
        
        // Calculate totals (simplified - can be more complex)
        $data['subtotal'] = $input['subtotal'] ?? 0;
        $data['tax'] = $input['tax'] ?? 0;
        $data['delivery_charges'] = $input['delivery_charges'] ?? 0;
        $data['total'] = $data['subtotal'] + $data['tax'] + $data['delivery_charges'];
        
        // Create order
        $result = $order->create($data);
        
        if ($result['success']) {
            // Get the created order data
            $orderData = $order->getById($result['order_id']);
            
            // Send email confirmation
            try {
                $emailService = new EmailService();
                $emailService->sendOrderConfirmation($orderData);
            } catch (Exception $e) {
                error_log("Order confirmation email error: " . $e->getMessage());
            }
            
            json_response([
                'success' => true,
                'message' => 'Order created successfully!',
                'order_number' => $result['order_number'],
                'order_id' => $result['order_id']
            ]);
        } else {
            json_response([
                'success' => false,
                'message' => $result['error'] ?? 'Failed to create order'
            ], 500);
        }
        exit;
    }
    
} catch (Exception $e) {
    error_log("Orders API error: " . $e->getMessage());
    json_response([
        'success' => false,
        'message' => 'Server error occurred'
    ], 500);
}
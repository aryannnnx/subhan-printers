<?php
// ============================================
// API: Quote - Submit quote request
// ============================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    echo json_encode(['success' => true]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Load files
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Quote.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/session.php';

try {
    // Start session to get user ID
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        $input = $_POST;
    }

    if (empty($input)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No data received']);
        exit;
    }

    // ✅ Get user ID if logged in
    $userId = $_SESSION['user_id'] ?? null;
    $userEmail = $_SESSION['user_email'] ?? $input['customer_email'] ?? null;

    // Validate
    $errors = [];
    if (empty($input['customer_name']) || strlen(trim($input['customer_name'])) < 2) {
        $errors['customer_name'] = 'Name is required';
    }
    if (empty($input['customer_email']) || !filter_var($input['customer_email'], FILTER_VALIDATE_EMAIL)) {
        $errors['customer_email'] = 'Valid email is required';
    }
    if (empty($input['customer_phone'])) {
        $errors['customer_phone'] = 'Phone number is required';
    }

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Validation failed', 'errors' => $errors]);
        exit;
    }

    // ✅ Build data with user_id
    $data = [
        'user_id' => $userId,  // ← ADDED: Link quote to user
        'customer_name' => trim($input['customer_name']),
        'customer_email' => trim($input['customer_email']),
        'customer_phone' => trim($input['customer_phone']),
        'customer_company' => $input['customer_company'] ?? null,
        'customer_address' => $input['customer_address'] ?? null,
        'project_type' => $input['project_type'] ?? 'General',
        'quantity' => isset($input['quantity']) ? (int)$input['quantity'] : 0,
        'specifications' => $input['specifications'] ?? null,
        'deadline' => $input['deadline'] ?? null,
        'budget' => isset($input['budget']) ? (float)$input['budget'] : null,
        'notes' => $input['notes'] ?? null,
        'source' => $input['source'] ?? 'website'
    ];

    $quote = new Quote();
    $result = $quote->create($data);
    
    if ($result['success']) {
        echo json_encode([
            'success' => true,
            'message' => 'Quote submitted successfully!',
            'quote_number' => $result['quote_number']
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $result['error'] ?? 'Failed to submit']);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
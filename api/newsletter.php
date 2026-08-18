<?php
// ============================================
// API: Newsletter - Subscribe/Unsubscribe
// ============================================

// ✅ NO echo or output before headers!

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

// ✅ Load required files
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Newsletter.php';
require_once __DIR__ . '/../includes/functions.php';

try {
    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        $input = $_POST;
    }
    
    $email = filter_var($input['email'] ?? '', FILTER_VALIDATE_EMAIL);
    
    if (!$email) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid email address']);
        exit;
    }
    
    $newsletter = new Newsletter();
    $result = $newsletter->subscribe($email, $input['source'] ?? 'website');
    
    if ($result['success']) {
        echo json_encode([
            'success' => true,
            'message' => $result['message'] ?? 'Subscribed successfully!'
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $result['error'] ?? 'Subscription failed'
        ]);
    }
    
} catch (Exception $e) {
    error_log("Newsletter API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
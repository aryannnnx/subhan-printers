<?php
// ============================================
// API: Contact - Submit contact form
// ============================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Load required files
require_once __DIR__ . '/../config/email.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validation.php';

try {
    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        $input = $_POST;
    }
    
    // Validation rules
    $rules = [
        'name' => 'required|min:2|max:100',
        'email' => 'required|email',
        'message' => 'required|min:10|max:2000'
    ];
    
    // Subject is optional
    if (!empty($input['subject'])) {
        $rules['subject'] = 'max:200';
    }
    
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
    $data['subject'] = $input['subject'] ?? '';
    $data['phone'] = $input['phone'] ?? '';
    
    // Send email
    $emailService = new EmailService();
    $result = $emailService->sendContactEmail($data);
    
    if ($result) {
        json_response([
            'success' => true,
            'message' => 'Your message has been sent successfully! We\'ll get back to you within 24 hours.'
        ]);
    } else {
        json_response([
            'success' => false,
            'message' => 'Failed to send message. Please try again or call us directly.'
        ], 500);
    }
    
} catch (Exception $e) {
    error_log("Contact API error: " . $e->getMessage());
    json_response([
        'success' => false,
        'message' => 'Server error occurred'
    ], 500);
}
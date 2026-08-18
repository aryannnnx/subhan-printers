<?php
// ============================================
// API: Auth - Login, Register, Logout
// ============================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Cross-Origin-Opener-Policy: same-origin-allow-popups');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    echo json_encode(['success' => true]);
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/session.php';

// Initialize session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $user = new User();
    $action = $_GET['action'] ?? '';
    
    // ── GET ──
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if ($action === 'status') {
            if (isset($_SESSION['user_id'])) {
                echo json_encode([
                    'success' => true,
                    'logged_in' => true,
                    'user' => [
                        'id' => $_SESSION['user_id'],
                        'name' => $_SESSION['user_name'],
                        'email' => $_SESSION['user_email'],
                        'role' => $_SESSION['user_role']
                    ]
                ]);
            } else {
                echo json_encode(['success' => true, 'logged_in' => false]);
            }
            exit;
        }
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
    }
    
    // ── POST ──
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }
        
        // ── LOGIN ──
        if ($action === 'login') {
            if (empty($input['email']) || empty($input['password'])) {
                echo json_encode(['success' => false, 'message' => 'Email and password are required']);
                exit;
            }
            
            $email = trim($input['email']);
            $password = $input['password'];
            
            $result = $user->login($email, $password);
            
            if ($result['success']) {
                set_user_session($result['user']);
                echo json_encode([
                    'success' => true,
                    'message' => 'Login successful',
                    'user' => $result['user']
                ]);
            } else {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => $result['error'] ?? 'Invalid email or password'
                ]);
            }
            exit;
        }
        
        // ── REGISTER ──
        if ($action === 'register') {
            if (empty($input['name']) || empty($input['email']) || empty($input['password'])) {
                echo json_encode(['success' => false, 'message' => 'Name, email and password are required']);
                exit;
            }
            
            if ($input['password'] !== ($input['password_confirm'] ?? '')) {
                echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
                exit;
            }
            
            if ($user->emailExists($input['email'])) {
                echo json_encode(['success' => false, 'message' => 'Email already registered']);
                exit;
            }
            
            $result = $user->create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => $input['password'],
                'role' => 'customer'
            ]);
            
            if ($result['success']) {
                $userData = $user->getById($result['id']);
                unset($userData['password_hash']);
                set_user_session($userData);
                echo json_encode([
                    'success' => true,
                    'message' => 'Registration successful!',
                    'user' => $userData
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => $result['error'] ?? 'Registration failed']);
            }
            exit;
        }
        
        // ── LOGOUT ──
        if ($action === 'logout') {
            clear_user_session();
            echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
            exit;
        }
        
        // ── FIREBASE (Google Login) ──
        if ($action === 'firebase') {
            if (empty($input['id_token'])) {
                echo json_encode(['success' => false, 'message' => 'ID token required']);
                exit;
            }
            
            $email = $input['email'] ?? null;
            $name = $input['name'] ?? 'Google User';
            $avatar = $input['avatar'] ?? null;
            
            // Check if user exists
            $existingUser = $user->getByEmail($email);
            
            if ($existingUser) {
                if ($avatar) {
                    $db = getDB();
                    $stmt = $db->prepare("UPDATE users SET avatar = :avatar WHERE id = :id");
                    $stmt->execute([':avatar' => $avatar, ':id' => $existingUser['id']]);
                    $existingUser['avatar'] = $avatar;
                }
                set_user_session($existingUser);
                echo json_encode([
                    'success' => true,
                    'message' => 'Login successful',
                    'user' => $existingUser
                ]);
            } else {
                $result = $user->create([
                    'name' => $name,
                    'email' => $email,
                    'password' => null,
                    'avatar' => $avatar,
                    'role' => 'customer',
                    'is_active' => 1
                ]);
                
                if ($result['success']) {
                    $userData = $user->getById($result['id']);
                    set_user_session($userData);
                    echo json_encode([
                        'success' => true,
                        'message' => 'Account created successfully!',
                        'user' => $userData
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to create account']);
                }
            }
            exit;
        }
        
        // ── FIREBASE REGISTER (Google Sign-Up) ──
        if ($action === 'firebase-register') {
            if (empty($input['id_token'])) {
                echo json_encode(['success' => false, 'message' => 'ID token required']);
                exit;
            }
            
            $email = $input['email'] ?? null;
            $name = $input['name'] ?? 'Google User';
            $avatar = $input['avatar'] ?? null;
            
            if (empty($email)) {
                echo json_encode(['success' => false, 'message' => 'Email is required']);
                exit;
            }
            
            // Check if user already exists
            $existingUser = $user->getByEmail($email);
            
            if ($existingUser) {
                // User already exists - log them in
                if ($avatar) {
                    $db = getDB();
                    $stmt = $db->prepare("UPDATE users SET avatar = :avatar WHERE id = :id");
                    $stmt->execute([':avatar' => $avatar, ':id' => $existingUser['id']]);
                    $existingUser['avatar'] = $avatar;
                }
                set_user_session($existingUser);
                echo json_encode([
                    'success' => true,
                    'message' => 'Welcome back! You are now logged in.',
                    'user' => $existingUser
                ]);
            } else {
                // Create new user
                $result = $user->create([
                    'name' => $name,
                    'email' => $email,
                    'password' => null,
                    'avatar' => $avatar,
                    'role' => 'customer',
                    'is_active' => 1
                ]);
                
                if ($result['success']) {
                    $userData = $user->getById($result['id']);
                    set_user_session($userData);
                    echo json_encode([
                        'success' => true,
                        'message' => 'Account created successfully! Welcome to Subhan Printers.',
                        'user' => $userData
                    ]);
                } else {
                    echo json_encode([
                        'success' => false, 
                        'message' => $result['error'] ?? 'Failed to create account. Please try again.'
                    ]);
                }
            }
            exit;
        }
        
        // ── INVALID ACTION ──
        echo json_encode(['success' => false, 'message' => 'Invalid action. Use: login, register, logout, firebase, or firebase-register']);
        exit;
    }
    
} catch (Exception $e) {
    error_log("Auth API error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
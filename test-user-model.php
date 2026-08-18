<?php
// test-user-model.php - Test User model

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/User.php';

echo "<h2>🔍 Testing User Model</h2>";

try {
    $user = new User();
    echo "✅ User model loaded successfully!<br><br>";
    
    // Test 1: Get user by email
    $email = 'subhii@gmail.com';
    $userData = $user->getByEmail($email);
    
    if ($userData) {
        echo "✅ User found by email: $email<br>";
        echo "Name: " . $userData['name'] . "<br>";
        echo "Email: " . $userData['email'] . "<br>";
        echo "Password Hash: " . $userData['password_hash'] . "<br><br>";
        
        // Test 2: Login attempt
        $password = 'admin123';
        $result = $user->login($email, $password);
        
        if ($result['success']) {
            echo "✅✅✅ LOGIN SUCCESSFUL!<br>";
            echo "User: " . $result['user']['name'] . "<br>";
            echo "Email: " . $result['user']['email'] . "<br>";
        } else {
            echo "❌ Login failed: " . $result['error'] . "<br>";
        }
    } else {
        echo "❌ User NOT found: $email<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
?>

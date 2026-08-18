<?php
// test-login.php - Debug login

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/User.php';

echo "<h2>🔍 Testing Login</h2>";

$email = 'subhii@gmail.com';
$password = 'admin123';

echo "Testing: $email / $password<br><br>";

// Check if user exists
try {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "✅ User found!<br>";
        echo "Name: " . $user['name'] . "<br>";
        echo "Email: " . $user['email'] . "<br>";
        echo "Password Hash: " . $user['password_hash'] . "<br><br>";
        
        // Verify password
        if (password_verify($password, $user['password_hash'])) {
            echo "✅✅✅ PASSWORD IS CORRECT!<br>";
            echo "You can login with: $email / $password<br>";
        } else {
            echo "❌ PASSWORD IS INCORRECT!<br>";
            echo "The password hash does not match '$password'<br>";
            echo "Try resetting the password.<br>";
        }
    } else {
        echo "❌ User NOT found!<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
?>
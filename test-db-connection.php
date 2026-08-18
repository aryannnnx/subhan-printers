<?php
// test-db-connection.php - Test database connection and user fetch

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/database.php';

echo "<h2>🔍 Testing Database Connection & User Fetch</h2>";

$email = 'subhii@gmail.com';

try {
    $db = getDB();
    echo "✅ Database connected successfully!<br><br>";
    
    // Test 1: Check if users table exists
    $stmt = $db->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Users table exists<br>";
    } else {
        echo "❌ Users table NOT found!<br>";
    }
    
    // Test 2: Count total users
    $stmt = $db->query("SELECT COUNT(*) as total FROM users");
    $result = $stmt->fetch();
    echo "Total users in database: " . $result['total'] . "<br><br>";
    
    // Test 3: Fetch user by email
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "✅ User found!<br>";
        echo "ID: " . $user['id'] . "<br>";
        echo "Name: " . $user['name'] . "<br>";
        echo "Email: " . $user['email'] . "<br>";
        echo "Password Hash: " . $user['password_hash'] . "<br>";
        echo "Role: " . $user['role'] . "<br>";
        echo "Active: " . ($user['is_active'] ? 'Yes' : 'No') . "<br><br>";
        
        // Test 4: Verify password
        $password = 'admin123';
        if (password_verify($password, $user['password_hash'])) {
            echo "✅✅✅ PASSWORD IS CORRECT!<br>";
            echo "You can login with: $email / $password<br>";
        } else {
            echo "❌ PASSWORD IS INCORRECT!<br>";
            echo "Please reset the password.<br>";
        }
    } else {
        echo "❌ User NOT found with email: $email<br>";
        echo "Available users:<br>";
        $stmt = $db->query("SELECT id, name, email FROM users");
        while ($row = $stmt->fetch()) {
            echo "- " . $row['name'] . " (" . $row['email'] . ")<br>";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
?>
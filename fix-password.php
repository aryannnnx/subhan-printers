<?php
// fix-password.php - Fix password for all users

require_once __DIR__ . '/config/database.php';

$email = 'subhii@gmail.com';
$newPassword = 'admin123';

try {
    $db = getDB();
    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Update password
    $stmt = $db->prepare("UPDATE users SET password_hash = :hash WHERE email = :email");
    $stmt->execute([
        ':hash' => $hashed,
        ':email' => $email
    ]);
    
    echo "✅ Password reset for $email<br>";
    echo "New password: $newPassword<br>";
    echo "Hash: $hashed<br><br>";
    
    // Verify
    $stmt = $db->prepare("SELECT password_hash FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();
    
    if (password_verify($newPassword, $user['password_hash'])) {
        echo "✅✅✅ PASSWORD VERIFIED!<br>";
        echo "You can now login with: $email / $newPassword";
    } else {
        echo "❌ Password verification failed!";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
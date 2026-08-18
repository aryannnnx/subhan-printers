<?php
// reset-password.php - Reset user password

require_once __DIR__ . '/config/database.php';

$email = 'subhii@gmail.com';
$newPassword = 'admin123';

try {
    $db = getDB();
    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
    
    $stmt = $db->prepare("UPDATE users SET password_hash = :hash WHERE email = :email");
    $stmt->execute([
        ':hash' => $hashed,
        ':email' => $email
    ]);
    
    echo "✅ Password reset for $email<br>";
    echo "New password: $newPassword<br>";
    echo "Hash: $hashed<br>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
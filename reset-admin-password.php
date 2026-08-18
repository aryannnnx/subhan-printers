<?php
// reset-admin-password.php

require_once __DIR__ . '/config/database.php';

$email = 'admin@subhanprinters.com';
$newPassword = 'admin786'; // ← Change to your desired password

try {
    $db = getDB();
    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
    
    $stmt = $db->prepare("UPDATE users SET password_hash = :hash WHERE email = :email");
    $stmt->execute([
        ':hash' => $hashed,
        ':email' => $email
    ]);
    
    echo "✅ Password reset for $email<br>";
    echo "New password: $newPassword<br><br>";
    
    // Verify
    $stmt = $db->prepare("SELECT password_hash FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();
    
    if (password_verify($newPassword, $user['password_hash'])) {
        echo "✅✅✅ Password verified! You can now login with:<br>";
        echo "Email: $email<br>";
        echo "Password: $newPassword";
    } else {
        echo "❌ Password verification failed!";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
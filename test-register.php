<?php
// test-register.php - Test register API

echo "<h2>🔍 Testing Register API</h2>";

$data = [
    'name' => 'Test User ' . time(),
    'email' => 'test_' . time() . '@example.com',
    'password' => 'password123',
    'password_confirm' => 'password123'
];

$ch = curl_init('http://localhost:8080/SP/api/auth?action=register');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "HTTP Code: $httpCode<br><br>";

if (json_decode($response)) {
    echo "✅ Valid JSON!<br>";
    echo "<pre>" . print_r(json_decode($response, true), true) . "</pre>";
} else {
    echo "❌ Invalid JSON! The response is:<br>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
}
?>
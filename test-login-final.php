<?php
// test-login-final.php - Test login API

echo "<h2>🔍 Testing Login API - Final</h2>";

$data = [
    'email' => 'subhii@gmail.com',
    'password' => 'admin123'
];

$ch = curl_init('http://localhost:8080/SP/api/auth?action=login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "HTTP Code: $httpCode<br><br>";

$json = json_decode($response, true);

if ($json) {
    echo "✅ Valid JSON!<br>";
    echo "Success: " . ($json['success'] ? 'Yes' : 'No') . "<br>";
    echo "Message: " . ($json['message'] ?? 'No message') . "<br>";
    if (isset($json['user'])) {
        echo "User: " . $json['user']['name'] . " (" . $json['user']['email'] . ")<br>";
        echo "Role: " . $json['user']['role'] . "<br>";
    }
    if (isset($json['errors'])) {
        echo "Errors: " . print_r($json['errors'], true) . "<br>";
    }
} else {
    echo "❌ Invalid JSON!<br>";
    echo "Response: <pre>" . htmlspecialchars($response) . "</pre>";
}
?>
<?php
// test-api-simple.php

echo "<h2>🔍 Simple API Test</h2>";

$data = [
    'customer_name' => 'Test User',
    'customer_email' => 'test@example.com',
    'customer_phone' => '03001234567',
    'project_type' => 'Wedding Cards',
    'quantity' => 100
];

$ch = curl_init('http://localhost:8080/SP/api/quote');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "HTTP Code: $httpCode<br>";
echo "Response:<br>";
echo "<pre>" . htmlspecialchars($response) . "</pre>";
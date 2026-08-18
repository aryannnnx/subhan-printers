<?php
// test-page.php - Test if pages load

echo "<h2>🔍 Testing Page Loading</h2>";

$pages = [
    'home' => 'pages/home.php',
    'services' => 'pages/services.php',
    'portfolio' => 'pages/portfolio.php',
    'about' => 'pages/about.php',
    'contact' => 'pages/contact.php',
    'order' => 'pages/order.php'
];

foreach ($pages as $name => $file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        echo "✅ $name: $file exists<br>";
    } else {
        echo "❌ $name: $file NOT found!<br>";
    }
}

// Check if Models exist
echo "<br><strong>📋 Models:</strong><br>";
$models = ['Product', 'Portfolio', 'Order', 'Newsletter', 'Quote', 'User'];
foreach ($models as $model) {
    $path = __DIR__ . '/models/' . $model . '.php';
    if (file_exists($path)) {
        echo "✅ $model.php exists<br>";
    } else {
        echo "❌ $model.php NOT found!<br>";
    }
}
?>
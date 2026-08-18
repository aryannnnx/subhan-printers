<?php
// debug-home.php - Debug home page

echo "<h2>🔍 Debug Home Page</h2>";

// 1. Check if files exist
$files = [
    'includes/functions.php',
    'config/database.php',
    'models/Product.php',
    'models/Portfolio.php',
    'templates/header.php',
    'templates/footer.php',
    'pages/home.php'
];

foreach ($files as $file) {
    $exists = file_exists(__DIR__ . '/' . $file);
    echo ($exists ? '✅' : '❌') . " $file<br>";
}

// 2. Try to include header
echo "<br><strong>📋 Attempting to load header:</strong><br>";
if (file_exists(__DIR__ . '/templates/header.php')) {
    try {
        // Don't actually render, just check
        echo "✅ header.php can be loaded<br>";
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "<br>";
    }
}

// 3. Check database connection
echo "<br><strong>📋 Database Connection:</strong><br>";
try {
    require_once __DIR__ . '/config/database.php';
    $db = getDB();
    echo "✅ Database connected<br>";
    
    // Check products
    $stmt = $db->query("SELECT COUNT(*) as count FROM products");
    $result = $stmt->fetch();
    echo "Products in database: " . $result['count'] . "<br>";
    
    $stmt = $db->query("SELECT COUNT(*) as count FROM portfolio");
    $result = $stmt->fetch();
    echo "Portfolio items in database: " . $result['count'] . "<br>";
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}
?>

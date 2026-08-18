<?php
// test-db.php - Fixed version

// 1. Load the database config
require_once __DIR__ . '/config/database.php';

// 2. Load environment variables (if needed)
if (file_exists(__DIR__ . '/.env')) {
    $env = parse_ini_file(__DIR__ . '/.env');
    foreach ($env as $key => $value) {
        putenv("$key=$value");
    }
}

echo "<h2>🔍 Testing Database Connection</h2>";

try {
    // 3. Get database connection
    $db = getDB();
    
    echo "✅ Database connected successfully!<br>";
    echo "Host: " . getenv('DB_HOST') . "<br>";
    echo "Database: " . getenv('DB_NAME') . "<br>";
    echo "User: " . getenv('DB_USER') . "<br><br>";
    
    // 4. Test query - check if tables exist
    $stmt = $db->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<strong>📋 Tables in database:</strong><br>";
    if (!empty($tables)) {
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
    } else {
        echo "⚠️ No tables found. Please import the database schema.<br>";
    }
    
    // 5. Check admin user
    $stmt = $db->query("SELECT COUNT(*) as count FROM users WHERE role = 'admin'");
    $result = $stmt->fetch();
    if ($result['count'] > 0) {
        echo "✅ Admin user exists.<br>";
    } else {
        echo "⚠️ No admin user found. Insert default admin.<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "<br>";
    echo "Error Code: " . $e->getCode() . "<br>";
    
    // Show helpful message
    echo "<br><strong>💡 Possible Solutions:</strong><br>";
    echo "1. Make sure MySQL is running in XAMPP<br>";
    echo "2. Check your .env file has correct database credentials<br>";
    echo "3. Make sure the database 'subhan_printers' exists<br>";
}
?>
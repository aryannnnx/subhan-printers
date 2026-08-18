<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Step 1: PHP Working</h1>";

// Test header include
if (file_exists('includes/header.php')) {
    echo "<p style='color:green'>✅ includes/header.php found</p>";
    
    // Try to include it
    require_once 'includes/header.php';
    echo "<p style='color:green'>✅ header.php included successfully</p>";
} else {
    echo "<p style='color:red'>❌ includes/header.php NOT found</p>";
}

// Test database connection
if (isset($conn)) {
    echo "<p style='color:green'>✅ Database connection exists</p>";
    
    $result = mysqli_query($conn, "SELECT 1");
    if ($result) {
        echo "<p style='color:green'>✅ Database query works</p>";
    } else {
        echo "<p style='color:red'>❌ Database query failed: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p style='color:red'>❌ \$conn not defined - check db_connect.php</p>";
}
?>
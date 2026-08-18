<?php
// test-class.php - Test if Quote class loads

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Quote.php';

echo "<h2>🔍 Testing Quote Class</h2>";

if (class_exists('Quote')) {
    echo "✅ Quote class exists!<br>";
    
    try {
        $quote = new Quote();
        echo "✅ Quote object created successfully!<br>";
        echo "Class: " . get_class($quote) . "<br>";
    } catch (Exception $e) {
        echo "❌ Error creating Quote: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ Quote class does NOT exist!<br>";
    echo "Check if models/Quote.php has the correct class definition.<br>";
}

echo "<br><strong>Loaded files:</strong><br>";
echo "config/database.php: " . (file_exists(__DIR__ . '/config/database.php') ? '✅' : '❌') . "<br>";
echo "models/Quote.php: " . (file_exists(__DIR__ . '/models/Quote.php') ? '✅' : '❌') . "<br>";
?>
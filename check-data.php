<?php
// check-data.php - Check if database has data

require_once __DIR__ . '/config/database.php';

echo "<h2>🔍 Checking Database Data</h2>";

$tables = [
    'products' => 'Products',
    'portfolio' => 'Portfolio Items',
    'testimonials' => 'Testimonials',
    'faqs' => 'FAQs'
];

foreach ($tables as $table => $label) {
    try {
        $db = getDB();
        $stmt = $db->query("SELECT COUNT(*) as count FROM $table");
        $result = $stmt->fetch();
        $count = $result['count'];
        
        $status = $count > 0 ? '✅' : '❌';
        echo "$status $label: $count records<br>";
        
        // Show first record sample
        if ($count > 0) {
            $stmt = $db->query("SELECT * FROM $table LIMIT 1");
            $sample = $stmt->fetch();
            echo "&nbsp;&nbsp;Sample: " . json_encode($sample) . "<br><br>";
        }
    } catch (PDOException $e) {
        echo "❌ Error checking $table: " . $e->getMessage() . "<br>";
    }
}
?>
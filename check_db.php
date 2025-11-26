<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=gombe_ss_hub', 'root', '');
    $stmt = $pdo->query('SELECT COUNT(*) as table_count FROM information_schema.TABLES WHERE TABLE_SCHEMA = "gombe_ss_hub"');
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Tables in database: " . $result['table_count'] . "\n";
    
    if ($result['table_count'] > 0) {
        echo "✓ Database successfully imported!\n";
        $stmt = $pdo->query('SHOW TABLES');
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "Tables found: " . implode(", ", array_slice($tables, 0, 5)) . (count($tables) > 5 ? "... and more" : "") . "\n";
    } else {
        echo "✗ No tables found. Import may have failed.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

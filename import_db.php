<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=gombe_ss_hub', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sqlFile = __DIR__ . '/database/database_mysql.sql';
    $sql = file_get_contents($sqlFile);
    
    $pdo->exec('SET FOREIGN_KEY_CHECKS=0;');
    
    $statements = preg_split('/;(?=(?:[^\']*\'[^\']*\')*[^\']*$)/', $sql);
    
    $count = 0;
    foreach ($statements as $statement) {
        $statement = trim($statement);
        
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }
        
        try {
            $pdo->exec($statement);
            $count++;
        } catch (Exception $e) {
            echo "Error executing statement: " . substr($statement, 0, 100) . "...\n";
            echo "Error: " . $e->getMessage() . "\n\n";
        }
    }
    
    $pdo->exec('SET FOREIGN_KEY_CHECKS=1;');
    
    echo "Database import completed! Executed $count statements.\n";
    
} catch (Exception $e) {
    echo "Connection Error: " . $e->getMessage();
}
?>

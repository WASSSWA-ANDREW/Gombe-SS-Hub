<?php
if ($_GET['confirm'] !== 'yes') {
    echo '<h1>Database Import</h1>';
    echo '<p><a href="?confirm=yes">Click here to import the database</a></p>';
    exit;
}

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=gombe_ss_hub', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sqlFile = __DIR__ . '/../database/database_mysql.sql';
    $sql = file_get_contents($sqlFile);
    
    $pdo->exec('SET FOREIGN_KEY_CHECKS=0;');
    
    $statements = preg_split('/;(?=(?:[^\']*\'[^\']*\')*[^\']*$)/', $sql);
    
    $count = 0;
    $errors = [];
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        
        if (empty($statement) || strpos(trim($statement), '--') === 0) {
            continue;
        }
        
        try {
            $pdo->exec($statement);
            $count++;
        } catch (Exception $e) {
            $errors[] = [
                'statement' => substr($statement, 0, 100),
                'error' => $e->getMessage()
            ];
        }
    }
    
    $pdo->exec('SET FOREIGN_KEY_CHECKS=1;');
    
    $tableCount = $pdo->query('SELECT COUNT(*) as cnt FROM information_schema.TABLES WHERE TABLE_SCHEMA = "gombe_ss_hub"')->fetch(PDO::FETCH_ASSOC)['cnt'];
    
    echo '<h1>Database Import Complete!</h1>';
    echo '<p><strong>Statements executed: ' . $count . '</strong></p>';
    echo '<p><strong>Tables created: ' . $tableCount . '</strong></p>';
    
    if (!empty($errors)) {
        echo '<h2>Errors encountered:</h2>';
        echo '<ul>';
        foreach ($errors as $err) {
            echo '<li>Query: ' . htmlspecialchars($err['statement']) . '... <br> Error: ' . htmlspecialchars($err['error']) . '</li>';
        }
        echo '</ul>';
    }
    
} catch (Exception $e) {
    echo '<h1>Error</h1>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
}
?>

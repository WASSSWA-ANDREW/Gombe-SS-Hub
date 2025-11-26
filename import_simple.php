<?php
set_time_limit(300);
ini_set('max_execution_time', 300);

$mysqli = new mysqli('127.0.0.1', 'root', '', 'gombe_ss_hub');

if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

$mysqli->query('SET FOREIGN_KEY_CHECKS=0');

$sqlFile = __DIR__ . '/database/database_mysql.sql';
$filesize = filesize($sqlFile);
echo "File size: " . ($filesize / 1024 / 1024) . " MB\n";

$fp = fopen($sqlFile, 'r');
$query = '';
$lineNum = 0;
$statementCount = 0;

while (!feof($fp)) {
    $line = fgets($fp, 8192);
    $lineNum++;
    
    $line = trim($line);
    
    if (empty($line) || strpos($line, '--') === 0) {
        continue;
    }
    
    $query .= $line;
    
    if (substr($query, -1) == ';') {
        if (!$mysqli->query($query)) {
            echo "Error on line $lineNum: " . $mysqli->error . "\n";
            echo "Query: " . substr($query, 0, 100) . "...\n\n";
            break;
        } else {
            $statementCount++;
            if ($statementCount % 50 == 0) {
                echo "Processed $statementCount statements...\n";
            }
        }
        $query = '';
    }
}

fclose($fp);

$mysqli->query('SET FOREIGN_KEY_CHECKS=1');
$mysqli->close();

echo "\n✓ Import completed!\n";
echo "Total statements executed: $statementCount\n";
?>

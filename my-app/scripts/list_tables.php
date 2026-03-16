<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=topblex;charset=utf8mb4','root','toor', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $rows = $pdo->query('SHOW TABLES');
    foreach ($rows as $row) {
        echo $row[0] . PHP_EOL;
    }
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}

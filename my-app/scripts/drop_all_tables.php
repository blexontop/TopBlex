<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=topblex;charset=utf8mb4','root','toor', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Disabling foreign key checks...\n";
    $pdo->exec('SET FOREIGN_KEY_CHECKS=0;');
    $tables = [];
    foreach ($pdo->query('SHOW TABLES') as $row) {
        $tables[] = $row[0];
    }
    if (count($tables) === 0) {
        echo "No tables found.\n";
        exit(0);
    }
    echo "Dropping tables: " . implode(', ', $tables) . "\n";
    foreach ($tables as $t) {
        $pdo->exec("DROP TABLE IF EXISTS `{$t}`;");
    }
    $pdo->exec('SET FOREIGN_KEY_CHECKS=1;');
    echo "Dropped all tables.\n";
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
    exit(1);
}

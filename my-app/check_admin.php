<?php

$host = '127.0.0.1';
$user = 'root';
$password = 'toor';
$database = 'topblex';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== CUENTA ADMIN EN LA BD ===\n\n";
    
    $stmt = $pdo->prepare("SELECT id, name, email, is_admin, created_at FROM users WHERE email='frede2000sall@gmail.com'");
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "ID:         " . $user['id'] . "\n";
        echo "Nombre:     " . $user['name'] . "\n";
        echo "Email:      " . $user['email'] . "\n";
        echo "Es Admin:   " . ($user['is_admin'] ? 'SÍ ✓' : 'NO') . "\n";
        echo "Creado:     " . $user['created_at'] . "\n";
    } else {
        echo "No se encontró la cuenta\n";
    }
    
    echo "\n=== TODOS LOS USUARIOS ===\n\n";
    
    $stmt = $pdo->prepare("SELECT id, name, email, is_admin FROM users");
    $stmt->execute();
    
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($users as $u) {
        echo "- " . $u['email'] . " (Admin: " . ($u['is_admin'] ? 'SÍ' : 'NO') . ")\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

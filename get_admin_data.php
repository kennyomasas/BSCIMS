<?php
header('Content-Type: application/json');

try {
    $host = 'localhost';
    $dbname = 'barangay';
    $username = 'root'; // Default XAMPP username
    $password = '';     // Default XAMPP password (empty)
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT username, password FROM admin_login LIMIT 1");
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        echo json_encode([
            'success' => true,
            'username' => $admin['username'],
            'password' => $admin['password']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No admin found'
        ]);
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
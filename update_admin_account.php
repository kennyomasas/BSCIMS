<?php
header('Content-Type: application/json');

try {
    $host = 'localhost';
    $dbname = 'barangay';
    $username = 'root'; // Default XAMPP username
    $password = '';     // Default XAMPP password (empty)
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    $updateFields = [];
    $params = [];
    
    if (isset($input['username']) && !empty($input['username'])) {
        $updateFields[] = "username = ?";
        $params[] = $input['username'];
    }
    
    if (isset($input['password']) && !empty($input['password'])) {
    // Store password as plain text
    $updateFields[] = "password = ?";
    $params[] = $input['password'];
}
    
    if (empty($updateFields)) {
        echo json_encode([
            'success' => false,
            'message' => 'No fields to update'
        ]);
        exit;
    }
    
    $sql = "UPDATE admin_login SET " . implode(', ', $updateFields) . " WHERE id = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    echo json_encode([
        'success' => true,
        'message' => 'Admin account updated successfully'
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
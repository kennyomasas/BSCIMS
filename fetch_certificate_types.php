<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set header for JSON response
header('Content-Type: application/json');

try {
    // Database connection
    $servername = "localhost";
    $username = "root"; // Change to your database username
    $password = ""; // Change to your database password
    $dbname = "barangay"; // Your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Check if table exists
    $tableCheckSql = "SHOW TABLES LIKE 'certificate_types'";
    $tableResult = $conn->query($tableCheckSql);
    
    if ($tableResult->num_rows == 0) {
        // Table doesn't exist yet
        echo json_encode([
            'status' => 'success',
            'message' => 'No certificate types found',
            'data' => []
        ]);
        exit;
    }

    // Fetch all certificate types
    $sql = "SELECT id, name, description, requires_payment, fee_amount FROM certificate_types ORDER BY name ASC";
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }
    
    $certificateTypes = [];
    
    while ($row = $result->fetch_assoc()) {
        $certificateTypes[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'requires_payment' => (bool)$row['requires_payment'],
            'fee_amount' => floatval($row['fee_amount'])
        ];
    }
    
    echo json_encode([
        'status' => 'success',
        'message' => count($certificateTypes) > 0 ? 'Certificate types retrieved' : 'No certificate types found',
        'data' => $certificateTypes
    ]);

    $conn->close();
    
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    
    // Log error for debugging
    error_log("Fetch certificate types error: " . $e->getMessage());
}
?>
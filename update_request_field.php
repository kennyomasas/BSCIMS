<?php
// update_request_field.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

try {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "barangay";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Get form data
    $requestId = isset($_POST['request_id']) ? $_POST['request_id'] : '';
    $field = isset($_POST['field']) ? $_POST['field'] : '';
    $value = isset($_POST['value']) ? $_POST['value'] : '';
    
    if (empty($requestId) || empty($field)) {
        throw new Exception("Request ID and field are required");
    }
    
    // Validate allowed fields to prevent SQL injection
    $allowedFields = ['purpose', 'signatory', 'notes'];
    if (!in_array($field, $allowedFields)) {
        throw new Exception("Invalid field specified");
    }
    
    // Update request field
    $sql = "UPDATE certificate_requests SET $field = ? WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("si", $value, $requestId);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Request updated successfully'
            ]);
        } else {
            throw new Exception("No request found with the given ID");
        }
    } else {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>

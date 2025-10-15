<?php
header('Content-Type: application/json');

// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "barangay";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

try {
    // First, add status column if it doesn't exist
    $checkColumn = "SHOW COLUMNS FROM reg_online LIKE 'status'";
    $result = $conn->query($checkColumn);
    
    if ($result->num_rows == 0) {
        // Add status column with default value 'pending'
        $addColumn = "ALTER TABLE reg_online ADD COLUMN status ENUM('pending', 'approved', 'declined') DEFAULT 'pending'";
        $conn->query($addColumn);
    }
    
    // Fetch all pending requests
    $sql = "SELECT id, profile_upload, first_name, middle_name, last_name, nickname_alias, 
            email, mobile_number, sitio, house_number, purok, status 
            FROM reg_online 
            WHERE status = 'pending' 
            ORDER BY id DESC";
    
    $result = $conn->query($sql);
    
    $requests = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }
    }
    
    echo json_encode([
        'success' => true, 
        'requests' => $requests,
        'count' => count($requests)
    ]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>
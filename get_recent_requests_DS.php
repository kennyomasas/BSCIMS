<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set header for JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

try {
    // Database connection
    $servername = "localhost";
    $username = "root"; // Change to your database username
    $password = ""; // Change to your database password
    $dbname = "barangay"; // Your database name

    // Create connection with error handling
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Set charset to UTF-8
    $conn->set_charset("utf8");

    // Check if certificate_requests table exists
    $tableCheckSql = "SHOW TABLES LIKE 'certificate_requests'";
    $tableResult = $conn->query($tableCheckSql);
    
    if ($tableResult->num_rows == 0) {
        // Table doesn't exist, return empty result
        echo json_encode([
            'status' => 'success',
            'message' => 'No certificate requests table found',
            'data' => []
        ]);
        exit;
    }

    // SQL query to get the latest 5 certificate requests
    $sql = "SELECT 
                id,
                request_id,
                resident_name,
                certificate_type,
                purpose,
                signatory, 
                status,               
                request_date,
                processed_date,
                processed_by
            FROM certificate_requests 
            ORDER BY request_date DESC 
            LIMIT 5";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }

    $requests = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Format the data for frontend consumption
            $requests[] = [
                'id' => $row['id'],
                'request_id' => $row['request_id'],
                'resident_name' => htmlspecialchars($row['resident_name']),
                'certificate_type' => htmlspecialchars($row['certificate_type']),
                'purpose' => htmlspecialchars($row['purpose']),
                'signatory' => htmlspecialchars($row['signatory']),
                'status' => ucfirst($row['status']),
                'request_date' => $row['request_date'],
                'processed_date' => $row['processed_date'],
                'processed_by' => $row['processed_by'] ? htmlspecialchars($row['processed_by']) : null
            ];
        }
    }

    // Return successful response
    echo json_encode([
        'status' => 'success',
        'message' => 'Recent requests retrieved successfully',
        'data' => $requests,
        'total_records' => count($requests)
    ]);

    $conn->close();
    
} catch (Exception $e) {
    // Return error response
    echo json_encode([
        'status' => 'error',
        'message' => 'Error retrieving recent requests: ' . $e->getMessage(),
        'data' => []
    ]);
    
    // Log error for debugging
    error_log("Get recent requests error: " . $e->getMessage());
}
?>
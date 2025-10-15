<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "barangay";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Connection failed: ' . $conn->connect_error
    ]));
}

// Get recent document requests (limit to 5)
$requests = [];
$query = "SELECT dr.id, dr.document_type, dr.status, dr.date_requested, 
          CONCAT(r.last_name, ', ', r.first_name, ' ', SUBSTRING(r.middle_name, 1, 1), '.') as resident_name 
          FROM certificate_requests dr
          LEFT JOIN residents r ON dr.resident_id = r.id
          ORDER BY dr.date_requested DESC
          LIMIT 5";

$result = $conn->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Format the date
        $dateFormatted = date('M d, Y', strtotime($row['date_requested']));
        
        $requests[] = [
            'id' => $row['id'],
            'document_type' => $row['document_type'],
            'resident_name' => $row['resident_name'],
            'date_requested' => $dateFormatted,
            'status' => $row['status']
        ];
    }
}

// Output as JSON
header('Content-Type: application/json');
echo json_encode($requests);

$conn->close();
?>
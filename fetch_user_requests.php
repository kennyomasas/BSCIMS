<?php
session_start();

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set content type to JSON
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "error", 
        "message" => "User not logged in"
    ]);
    exit();
}

// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "barangay";

// Create connection
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode([
        "status" => "error", 
        "message" => "Database connection failed: " . $conn->connect_error
    ]);
    exit();
}

// Get the logged-in user's full name to match against resident_name
$userFullName = $_SESSION['full_name'];

// Fetch certificate requests for the logged-in user
$stmt = $conn->prepare("
    SELECT 
        
        request_id,
        resident_name,
        certificate_type,
        purpose,
        signatory,
        status,
        request_date
    FROM certificate_requests 
    WHERE resident_name = ?
    ORDER BY request_date DESC
");

if (!$stmt) {
    echo json_encode([
        "status" => "error", 
        "message" => "Database prepare failed: " . $conn->error
    ]);
    exit();
}

$stmt->bind_param("s", $userFullName);
$stmt->execute();
$result = $stmt->get_result();

$requests = [];
while ($row = $result->fetch_assoc()) {
    $requests[] = [
        
        'request_id' => $row['request_id'],
        'issued_to' => $row['resident_name'],
        'certificate_type' => $row['certificate_type'],
        'purpose' => $row['purpose'],
        'signatory' => $row['signatory'],
        'status' => $row['status'],
        'date_requested' => date('M d, Y - g:i A', strtotime($row['request_date']))
    ];
}

$stmt->close();
$conn->close();

echo json_encode([
    "status" => "success",
    "data" => $requests
]);
?>
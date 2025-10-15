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

// Get dashboard statistics
$stats = [
    'residents' => 0,
    'requests' => 0,
    'pending' => 0,
    'announcements' => 0
];

// Count total residents
$residentQuery = "SELECT COUNT(*) as total FROM residents";
$result = $conn->query($residentQuery);
if ($result) {
    $row = $result->fetch_assoc();
    $stats['residents'] = $row['total'];
}

// Count total document requests
$requestsQuery = "SELECT COUNT(*) as total FROM certificate_requests"; // Changed from document_requests
$result = $conn->query($requestsQuery);
if ($result) {
    $row = $result->fetch_assoc();
    $stats['requests'] = $row['total'];
}

// Count pending document requests
$pendingQuery = "SELECT COUNT(*) as total FROM certificate_requests WHERE status = 'Pending'";
$result = $conn->query($pendingQuery);
if ($result) {
    $row = $result->fetch_assoc();
    $stats['pending'] = $row['total'];
}

// Count announcements
$announcementsQuery = "SELECT COUNT(*) as total FROM announcements_events"; // Changed from announcements
$result = $conn->query($announcementsQuery);
if ($result) {
    $row = $result->fetch_assoc();
    $stats['announcements'] = $row['total'];
}

// Output as JSON
header('Content-Type: application/json');
echo json_encode($stats);

$conn->close();
?>
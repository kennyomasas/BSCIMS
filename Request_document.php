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
$conn = new mysqli($servername, $username, $password, "", $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Check if resident ID is provided
if (isset($_POST['residentId'])) {
    $residentId = $conn->real_escape_string($_POST['residentId']);

    // Query to fetch resident details
    $sql = "SELECT first_name FROM residents WHERE id = '$residentId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['status' => 'success', 'residentName' => $row['full_name']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Resident not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No resident ID provided']);
}

// Close connection
$conn->close();
?>

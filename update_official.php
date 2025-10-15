<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "barangay";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $official_id = $_POST['official_id'] ?? '';
    $official_name = $_POST['official_name'] ?? '';
    $official_committee = $_POST['official_committee'] ?? '';
    $official_position = $_POST['official_position'] ?? '';
    
    // Validate inputs
    if (empty($official_id) || empty($official_name) || empty($official_committee) || empty($official_position)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }
    
    // Prepare and execute update query
    $sql = "UPDATE officials SET complete_name = ?, committee = ?, position = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("sssi", $official_name, $official_committee, $official_position, $official_id);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Official information updated successfully.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No changes were made or official not found.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to execute update query.']);
        }
        
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare update query.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

$conn->close();
?>
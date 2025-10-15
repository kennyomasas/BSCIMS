<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$database = "barangay";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Get the term parameter
$term = isset($_GET['term']) ? $_GET['term'] : '';

if (empty($term)) {
    echo json_encode(["error" => "Term parameter is required"]);
    exit;
}

// Count officials for the specified term
$sql = "SELECT COUNT(*) as count FROM officials WHERE term = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $term);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(["count" => $row['count']]);
} else {
    echo json_encode(["count" => 0]);
}

$stmt->close();
$conn->close();
?>
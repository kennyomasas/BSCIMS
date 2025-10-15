<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barangay";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

$sql = "SELECT request_id, resident_name, certificate_type, purpose, signatory, status, request_date FROM certificate_requests ORDER BY request_date DESC";
$result = $conn->query($sql);

$certificates = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $certificates[] = $row;
    }
}

echo json_encode($certificates);

$conn->close();
?>

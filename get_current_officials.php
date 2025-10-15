<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$database = "barangay";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Connection failed']);
    exit();
}

// Get the current/latest term (you can modify this to get the most recent term)
// For now, getting all officials from the latest term
$sql = "SELECT * FROM officials 
        WHERE term = (SELECT MAX(term) FROM officials) 
        ORDER BY 
            CASE position
                WHEN 'Barangay Captain' THEN 1
                WHEN 'Barangay Kagawad' THEN 2
                WHEN 'SK Chairperson' THEN 3
                WHEN 'Barangay Secretary' THEN 4
                WHEN 'Barangay Treasurer' THEN 5
                ELSE 6
            END,
            id ASC";

$result = $conn->query($sql);

$officials = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $officials[] = $row;
    }
}

echo json_encode([
    'status' => 'success',
    'officials' => $officials,
    'count' => count($officials)
]);

$conn->close();
?>
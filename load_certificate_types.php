<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barangay";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Function to get all certificate types
function getCertificateTypes($conn) {
    $sql = "SELECT id, certificate_name FROM certificate_types ORDER BY certificate_name";
    $result = $conn->query($sql);
    
    $certificateTypes = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $certificateTypes[] = $row;
        }
    }
    
    return $certificateTypes;
}

// Get certificate types
$certificateTypes = getCertificateTypes($conn);
$conn->close();

// Return JSON response
header('Content-Type: application/json');
echo json_encode($certificateTypes);
?>

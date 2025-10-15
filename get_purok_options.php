<?php
// get_purok_options.php - Optional: Use this to dynamically load Purok options
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "barangay";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get distinct purok values
$sql = "SELECT DISTINCT purok FROM residents WHERE purok IS NOT NULL AND purok != '' ORDER BY purok";
$result = $conn->query($sql);

$puroks = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $puroks[] = $row['purok'];
    }
}

$conn->close();

// Return as JSON
header('Content-Type: application/json');
echo json_encode($puroks);
?>
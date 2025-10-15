<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barangay";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT certificate_name FROM certificate_types";
$result = $conn->query($sql);

$certificates = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $certificates[] = $row['certificate_name'];
    }
}

$conn->close();

echo json_encode($certificates);
?>

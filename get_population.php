<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "barangay";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to count total residents
$sql = "SELECT COUNT(*) AS total FROM residents";
$result = $conn->query($sql);

// Fetch the total count
$totalResidents = 0;
if ($result && $row = $result->fetch_assoc()) {
    $totalResidents = $row['total'];
}

// Close the database connection
$conn->close();

// Output the result as plain text
echo $totalResidents;
?>

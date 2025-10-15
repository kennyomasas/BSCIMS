<?php
$conn = new mysqli("localhost", "root", "", "barangay");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $sql = "DELETE FROM officials WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Official deleted successfully.";
    } else {
        echo "Error deleting official: " . $conn->error;
    }
} else {
    echo "Invalid ID.";
}

$conn->close();
?>

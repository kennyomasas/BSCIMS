<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barangay";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Delete the admin record
    $sql = "DELETE FROM admins WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            header("Location: Administration.php?toast=success&message=User deleted successfully");
        } else {
            header("Location: Administration.php?toast=error&message=User not found");
        }
    } else {
        header("Location: Administration.php?toast=error&message=Error deleting user");
    }
    
    $stmt->close();
} else {
    header("Location: Administration.php?toast=error&message=Invalid request");
}

$conn->close();
?>
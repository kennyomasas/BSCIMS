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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    
    // Check if username or email already exists for other users
    $sql_check = "SELECT * FROM admins WHERE (username = ? OR email = ?) AND id != ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ssi", $username, $email, $id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    
    if ($result->num_rows > 0) {
        header("Location: Administration.php?toast=error&message=Username or Email already exists");
        exit();
    }
    
    // Check if position already exists for other users
    $sql_position_check = "SELECT * FROM admins WHERE position = ? AND id != ?";
    $stmt_position_check = $conn->prepare($sql_position_check);
    $stmt_position_check->bind_param("si", $position, $id);
    $stmt_position_check->execute();
    $position_result = $stmt_position_check->get_result();
    
    if ($position_result->num_rows > 0) {
        header("Location: Administration.php?toast=error&message=Position already assigned to another user");
        exit();
    }
    
    // Update the admin record
    if (!empty($password)) {
        // Update with new password
        $sql = "UPDATE admins SET username=?, password=?, first_name=?, last_name=?, email=?, position=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $username, $password, $first_name, $last_name, $email, $position, $id);
    } else {
        // Update without changing password
        $sql = "UPDATE admins SET username=?, first_name=?, last_name=?, email=?, position=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $username, $first_name, $last_name, $email, $position, $id);
    }
    
    if ($stmt->execute()) {
        header("Location: Administration.php?toast=success&message=User updated successfully");
    } else {
        header("Location: Administration.php?toast=error&message=Error updating user");
    }
    
    $stmt->close();
    $stmt_check->close();
    $stmt_position_check->close();
} else if (isset($_GET['id'])) {
    // Fetch user data for editing
    $id = $_GET['id'];
    $sql = "SELECT * FROM admins WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
    
    $stmt->close();
}

$conn->close();
?>
<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: Personnel_login.html');
    exit();
}

// Database connection
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "barangay";

$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$user_id = $_POST['user_id'];
$first_name = trim($_POST['first_name']);
$last_name = trim($_POST['last_name']);
$email = trim($_POST['email']);
$position = trim($_POST['position']);
$password = $_POST['password'];
$current_username = $_POST['current_username'];
$current_email = $_POST['current_email'];
$current_position = $_POST['current_position'];

// Validate that this is the logged-in user
if ($user_id != $_SESSION['user_id']) {
    echo "<script>alert('Unauthorized access!'); window.location.href = 'Personnel_official.php';</script>";
    exit();
}

// Check if email is being changed and if it already exists
if ($email !== $current_email) {
    $email_check = $conn->prepare("SELECT id FROM admins WHERE email = ? AND id != ?");
    $email_check->bind_param("si", $email, $user_id);
    $email_check->execute();
    $email_result = $email_check->get_result();
    
    if ($email_result->num_rows > 0) {
        echo "<script>alert('Email is already registered by another user!'); window.history.back();</script>";
        $email_check->close();
        $conn->close();
        exit();
    }
    $email_check->close();
}

// Check if position is being changed and if it already exists
if ($position !== $current_position) {
    $position_check = $conn->prepare("SELECT id FROM admins WHERE position = ? AND id != ?");
    $position_check->bind_param("si", $position, $user_id);
    $position_check->execute();
    $position_result = $position_check->get_result();
    
    if ($position_result->num_rows > 0) {
        echo "<script>alert('Position \"" . htmlspecialchars($position) . "\" is already taken by another user!'); window.history.back();</script>";
        $position_check->close();
        $conn->close();
        exit();
    }
    $position_check->close();
}

// Prepare update query
if (!empty($password)) {
    // Update with new password
    $stmt = $conn->prepare("UPDATE admins SET first_name = ?, last_name = ?, email = ?, position = ?, password = ? WHERE id = ?");
    $stmt->bind_param("sssssi", $first_name, $last_name, $email, $position, $password, $user_id);
} else {
    // Update without changing password
    $stmt = $conn->prepare("UPDATE admins SET first_name = ?, last_name = ?, email = ?, position = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $first_name, $last_name, $email, $position, $user_id);
}

if ($stmt->execute()) {
    // Update session data
    $_SESSION['first_name'] = $first_name;
    
    echo "<script>
        alert('Profile updated successfully!');
        window.location.href = 'Personnel_official.php?profile_updated=success';
    </script>";
} else {
    echo "<script>
        alert('Error updating profile: " . $stmt->error . "');
        window.history.back();
    </script>";
}

$stmt->close();
$conn->close();
?>
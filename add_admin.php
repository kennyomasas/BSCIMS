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
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$username = $_POST['username'];
$password = $_POST['password'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$position = $_POST['position'];
$role = $_POST['role'];

// Check if the username or email already exists
$sql_check = "SELECT * FROM admins WHERE username = ? OR email = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ss", $username, $email);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    echo "<script>
        window.location.href = 'Administration.php?toast=error&message=" . urlencode('Username or email is already registered! Please choose a different one.') . "';
    </script>";
} else {
    // Check if the position already exists
    $sql_position_check = "SELECT * FROM admins WHERE position = ?";
    $stmt_position_check = $conn->prepare($sql_position_check);
    $stmt_position_check->bind_param("s", $position);
    $stmt_position_check->execute();
    $position_result = $stmt_position_check->get_result();
        
    if ($position_result->num_rows > 0) {
        echo "<script>
            window.location.href = 'Administration.php?toast=error&message=" . urlencode('Position \"' . htmlspecialchars($position) . '\" is already taken! Please choose a different position.') . "';
        </script>";
        $stmt_position_check->close();
    } else {
        // Insert with position
        $stmt = $conn->prepare("INSERT INTO admins (username, password, first_name, last_name, email, position, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $username, $password, $first_name, $last_name, $email, $position, $role);
                
        if ($stmt->execute()) {
            echo "<script>
                window.location.href = 'Administration.php?toast=success&message=" . urlencode('New User added successfully!') . "';
            </script>";
        } else {
            echo "<script>
                window.location.href = 'Administration.php?toast=error&message=" . urlencode('Error adding User: ' . $stmt->error) . "';
            </script>";
        }
                
        $stmt->close();
        $stmt_position_check->close();
    }
}

$stmt_check->close();
$conn->close();
?>
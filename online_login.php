<?php
session_start(); // Start the session

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set content type to JSON
header('Content-Type: application/json');

// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "barangay";

// Create connection
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    echo json_encode([
        "status" => "error", 
        "message" => "Database connection failed: " . $conn->connect_error
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug: Log the received data
    error_log("Login attempt - POST data: " . print_r($_POST, true));
    
    // Check if required fields are present
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        echo json_encode([
            "status" => "error", 
            "message" => "Email and password are required"
        ]);
        exit();
    }
    
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Debug: Check if values are not empty
    if (empty($email) || empty($password)) {
        echo json_encode([
            "status" => "error", 
            "message" => "Email and password cannot be empty"
        ]);
        exit();
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error", 
            "message" => "Please enter a valid email address"
        ]);
        exit();
    }
    
    // Query residents table using email only
    $stmt = $conn->prepare("SELECT * FROM residents WHERE email = ?");
    if (!$stmt) {
        echo json_encode([
            "status" => "error", 
            "message" => "Database prepare failed: " . $conn->error
        ]);
        exit();
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // Debug: Log found user
        error_log("Resident found: " . $row['email'] . " (ID: " . $row['id'] . ")");
        
        // Check if password column exists and verify password
        if (isset($row['password']) && password_verify($password, $row['password'])) {
            // Store user data in session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['full_name'] = $row['first_name'] . ' ' . $row['last_name'];
            $_SESSION['mobile_number'] = $row['mobile_number'];
            $_SESSION['gender'] = $row['gender'];
            $_SESSION['birthdate'] = $row['birthdate'];
            $_SESSION['address'] = $row['sitio'] . ', Purok ' . $row['purok'];
            
            // Optional: Store profile image if exists
            if (isset($row['profile_image']) && !empty($row['profile_image'])) {
                $_SESSION['profile_image'] = $row['profile_image'];
            }
            
            // Debug: Log successful login
            error_log("Login successful for resident ID: " . $row['id']);
            
            echo json_encode([
                "status" => "success",
                "message" => "Login successful",
                "redirect" => "landing_acc.php",
                "user_data" => [
                    "name" => $row['first_name'] . ' ' . $row['last_name'],
                    "email" => $row['email']
                ]
            ]);
        } else {
            // Check if password column doesn't exist
            if (!isset($row['password'])) {
                echo json_encode([
                    "status" => "error", 
                    "message" => "Password authentication not set up for this account"
                ]);
            } else {
                // Debug: Log password verification failure
                error_log("Password verification failed for resident: " . $email);
                
                echo json_encode([
                    "status" => "error", 
                    "message" => "Incorrect password"
                ]);
            }
        }
    } else {
        // Debug: Log user not found
        error_log("Resident not found with email: " . $email);
        
        echo json_encode([
            "status" => "error", 
            "message" => "No resident found with this email address"
        ]);
    }
    
    $stmt->close();
} else {
    echo json_encode([
        "status" => "error", 
        "message" => "Invalid request method"
    ]);
}

$conn->close();
?>
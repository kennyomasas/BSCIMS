<?php
// Start session at the beginning
session_start();

// Database connection parameters
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

// Get form data safely
$user = isset($_POST['username']) ? $_POST['username'] : '';
$pass = isset($_POST['password']) ? $_POST['password'] : '';

// Prepare and bind to prevent SQL injection
// Select first_name or extract first name from full_name
$stmt = $conn->prepare("SELECT id, username, first_name FROM admins WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $user, $pass);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Successful login - store user info in session
    $user_data = $result->fetch_assoc();
    $_SESSION['user_id'] = $user_data['id'];
    $_SESSION['username'] = $user_data['username'];
    $_SESSION['first_name'] = $user_data['first_name']; // Store only the first name
    $_SESSION['logged_in'] = true;
    
    // Successful login
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Logging In...</title>
        <style>
            body {
                margin: 0;
                padding: 0;
                background: url("image/bhall.jpg") no-repeat center center fixed;
                background-size: cover;
                font-family: Arial, sans-serif;
            }
            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 100vw;
                background-color: rgba(0, 0, 0, 0.6);
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                color: white;
                z-index: 10;
                text-align: center;
            }
            .loader {
                border: 8px solid #f3f3f3;
                border-top: 8px solid #00ADB5;
                border-radius: 50%;
                width: 60px;
                height: 60px;
                animation: spin 1s linear infinite;
                margin-bottom: 20px;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            .offline-message {
                font-size: 18px;
                display: none;
                padding: 10px 20px;
                background-color: rgba(255, 0, 0, 0.7);
                border-radius: 10px;
            }
        </style>
    </head>
    <body>
        <div class="overlay">
            <div class="loader"></div>
            <div class="offline-message" id="offline-msg">No internet connection. Please connect and try again.</div>
        </div>
        <script>
            function checkConnectionAndRedirect() {
                if (navigator.onLine) {
                    window.location.href = "Personnel_official.php";
                } else {
                    document.querySelector(".loader").style.display = "none";
                    document.getElementById("offline-msg").style.display = "block";
                    setTimeout(() => {
                        window.location.href = "Personnel_login.html";
                    }, 3000);
                }
            }

            setTimeout(checkConnectionAndRedirect, 2000);
        </script>
    </body>
    </html>
    ';
} else {
    // Failed login
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login Failed</title>
        <style>
            body {
                margin: 0;
                padding: 0;
                background: url("image/bhall.jpg") no-repeat center center fixed;
                background-size: cover;
                font-family: Arial, sans-serif;
            }
            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 100vw;
                background-color: rgba(0, 0, 0, 0.6);
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                color: white;
                z-index: 10;
            }
            .loader {
                border: 8px solid #f3f3f3;
                border-top: 8px solid #FF4444;
                border-radius: 50%;
                width: 60px;
                height: 60px;
                animation: spin 1s linear infinite;
                margin-bottom: 20px;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
    </head>
    <body>
        <div class="overlay">
            <div class="loader"></div>
        </div>
        <script>
            setTimeout(() => {
                window.location.href = "Personnel_login.html";
            }, 2000);
        </script>
    </body>
    </html>
    ';
}

$stmt->close();
$conn->close();
?>
<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "barangay";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Connection failed: ' . $conn->connect_error
    ]));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lastName = $_POST['last-name'];
    $firstName = $_POST['first-name'];
    $middleName = $_POST['middle-name'];
    $nickname = $_POST['nickname-alias'];
    $birthdate = $_POST['birthdate'];
    $birthplace = $_POST['birthplace'];
    $citizenship = $_POST['citizenship'];
    $gender = $_POST['gender'];
    $mobileNumber = $_POST['mobile-number'];
    $email = $_POST['email'];
    
    // Check if email already exists in database
    if (!empty($email)) {
        $checkEmailSql = "SELECT id FROM residents WHERE email = ?";
        if ($checkStmt = $conn->prepare($checkEmailSql)) {
            $checkStmt->bind_param("s", $email);
            $checkStmt->execute();
            $checkStmt->store_result();
            
            if ($checkStmt->num_rows > 0) {
                $checkStmt->close();
                ?>
                <!DOCTYPE html>
                <html>
                <head>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
                    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
                </head>
                <body>
                    <div class="toast-container position-fixed top-0 end-0 p-3">
                        <div class="toast show" role="alert" style="background-color: #dc3545; color: white;">
                            <div class="toast-body">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                This email address is already registered. Please use a different email.
                            </div>
                        </div>
                    </div>
                    <script>
                        setTimeout(function() {
                            window.history.back();
                        }, 3000);
                    </script>
                </body>
                </html>
                <?php
                exit();
            }
            $checkStmt->close();
        }
    }
    
    $maritalStatus = $_POST['marital-status'];
    $religion = $_POST['religion'];
    $sector = $_POST['sector'];
    $education = $_POST['education'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $sitio = $_POST['sitio'];
    $houseNumber = $_POST['house-number'];
    $purok = $_POST['purok'];
    $sinceYear = $_POST['since-year'];
    $householdNumber = $_POST['household-number'];
    $houseOwner = $_POST['house-owner'];
    $shelterType = $_POST['shelter-type'];
    $houseMaterial = $_POST['house-material'];
    
    // New field for password
    $userPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    
    // Validate password
    if (empty($userPassword)) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="toast-container position-fixed top-0 end-0 p-3">
                <div class="toast show" role="alert" style="background-color: #dc3545; color: white;">
                    <div class="toast-body">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Password is required.
                    </div>
                </div>
            </div>
            <script>
                setTimeout(function() {
                    window.history.back();
                }, 3000);
            </script>
        </body>
        </html>
        <?php
        exit();
    }
    
    // Check if passwords match
    if ($userPassword !== $confirmPassword) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="toast-container position-fixed top-0 end-0 p-3">
                <div class="toast show" role="alert" style="background-color: #dc3545; color: white;">
                    <div class="toast-body">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Passwords do not match.
                    </div>
                </div>
            </div>
            <script>
                setTimeout(function() {
                    window.history.back();
                }, 3000);
            </script>
        </body>
        </html>
        <?php
        exit();
    }
    
    // Validate password strength (minimum 6 characters)
    if (strlen($userPassword) < 6) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="toast-container position-fixed top-0 end-0 p-3">
                <div class="toast show" role="alert" style="background-color: #dc3545; color: white;">
                    <div class="toast-body">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Password must be at least 6 characters long.
                    </div>
                </div>
            </div>
            <script>
                setTimeout(function() {
                    window.history.back();
                }, 3000);
            </script>
        </body>
        </html>
        <?php
        exit();
    }
    
   
    $hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT);
    
    // Handle Image Upload
    $target_dir = "uploads/";
    $profile_image = "default-profile.jpg"; // Default image
    
   
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    if (!empty($_FILES["profile-upload"]["name"])) {
       
        $file_extension = strtolower(pathinfo($_FILES["profile-upload"]["name"], PATHINFO_EXTENSION));
        $unique_filename = uniqid() . '_' . time() . '.' . $file_extension;
        $target_file = $target_dir . $unique_filename;
        
        // Check if image file is an actual image
        $check = getimagesize($_FILES["profile-upload"]["tmp_name"]);
        if ($check !== false) {
            // Allow specific file formats
            $allowed_types = ["jpg", "jpeg", "png", "gif", "bmp", "webp"];
            
            if (in_array($file_extension, $allowed_types)) {
                // Check file size (limit to 5MB)
                if ($_FILES["profile-upload"]["size"] <= 5000000) {
                    if (move_uploaded_file($_FILES["profile-upload"]["tmp_name"], $target_file)) {
                        $profile_image = $target_file;
                    } else {
                        ?>
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
                        </head>
                        <body>
                            <div class="toast-container position-fixed top-0 end-0 p-3">
                                <div class="toast show" role="alert" style="background-color: #dc3545; color: white;">
                                    <div class="toast-body">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        Error uploading image. Please try again.
                                    </div>
                                </div>
                            </div>
                            <script>
                                setTimeout(function() {
                                    window.history.back();
                                }, 3000);
                            </script>
                        </body>
                        </html>
                        <?php
                        exit();
                    }
                } else {
                    ?>
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
                    </head>
                    <body>
                        <div class="toast-container position-fixed top-0 end-0 p-3">
                            <div class="toast show" role="alert" style="background-color: #dc3545; color: white;">
                                <div class="toast-body">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    Image file is too large. Maximum size is 5MB.
                                </div>
                            </div>
                        </div>
                        <script>
                            setTimeout(function() {
                                window.history.back();
                            }, 3000);
                        </script>
                    </body>
                    </html>
                    <?php
                    exit();
                }
            } else {
                ?>
                <!DOCTYPE html>
                <html>
                <head>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
                </head>
                <body>
                    <div class="toast-container position-fixed top-0 end-0 p-3">
                        <div class="toast show" role="alert" style="background-color: #dc3545; color: white;">
                            <div class="toast-body">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                Invalid image format. Allowed formats: JPG, JPEG, PNG, GIF, BMP, WEBP.
                            </div>
                        </div>
                    </div>
                    <script>
                        setTimeout(function() {
                            window.history.back();
                        }, 3000);
                    </script>
                </body>
                </html>
                <?php
                exit();
            }
        } else {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
            </head>
            <body>
                <div class="toast-container position-fixed top-0 end-0 p-3">
                    <div class="toast show" role="alert" style="background-color: #dc3545; color: white;">
                        <div class="toast-body">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            File is not a valid image.
                        </div>
                    </div>
                </div>
                <script>
                    setTimeout(function() {
                        window.history.back();
                    }, 3000);
                </script>
            </body>
            </html>
            <?php
            exit();
        }
    }
    
    
    $sql = "INSERT INTO residents (last_name, first_name, middle_name, nickname, birthdate, birthplace, citizenship, gender, mobile_number, email, marital_status, religion, sector, education, height, weight, sitio, house_number, purok, since_year, household_number, house_owner, shelter_type, house_material, profile_image, password)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
  
        $stmt->bind_param("ssssssssssssssssssssssssss", 
            $lastName, $firstName, $middleName, $nickname, $birthdate, 
            $birthplace, $citizenship, $gender, $mobileNumber, $email, 
            $maritalStatus, $religion, $sector, $education, $height, 
            $weight, $sitio, $houseNumber, $purok, $sinceYear, 
            $householdNumber, $houseOwner, $shelterType, $houseMaterial, 
            $profile_image, $hashedPassword);
        
        if ($stmt->execute()) {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
            </head>
            <body>
                <div class="toast-container position-fixed top-0 end-0 p-3">
                    <div class="toast show" role="alert" style="background-color: #28a745; color: white;">
                        <div class="toast-body">
                            <i class="fas fa-check-circle me-2"></i>
                            Resident added successfully!
                        </div>
                    </div>
                </div>
                <script>
                    setTimeout(function() {
                        window.location.href = 'Residents.html';
                    }, 2000);
                </script>
            </body>
            </html>
            <?php
        } else {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
            </head>
            <body>
                <div class="toast-container position-fixed top-0 end-0 p-3">
                    <div class="toast show" role="alert" style="background-color: #dc3545; color: white;">
                        <div class="toast-body">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Error adding resident: <?php echo $stmt->error; ?>
                        </div>
                    </div>
                </div>
                <script>
                    setTimeout(function() {
                        window.history.back();
                    }, 3000);
                </script>
            </body>
            </html>
            <?php
        }
        
        $stmt->close();
    } else {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="toast-container position-fixed top-0 end-0 p-3">
                <div class="toast show" role="alert" style="background-color: #dc3545; color: white;">
                    <div class="toast-body">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Database error: <?php echo $conn->error; ?>
                    </div>
                </div>
            </div>
            <script>
                setTimeout(function() {
                    window.history.back();
                }, 3000);
            </script>
        </body>
        </html>
        <?php
    }
    
    $conn->close();
} else {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div class="toast show" role="alert" style="background-color: #dc3545; color: white;">
                <div class="toast-body">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Invalid request.
                </div>
            </div>
        </div>
        <script>
            setTimeout(function() {
                window.history.back();
            }, 3000);
        </script>
    </body>
    </html>
    <?php
}
?>
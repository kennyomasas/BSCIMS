<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session to get user ID
session_start();

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
    // Get user ID from POST data or session
    $user_id = $_POST['user_id'] ?? $_SESSION['user_id'] ?? null;
    
    if (!$user_id) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
            <style>
                body { 
                    background: transparent !important; 
                    margin: 0; 
                    padding: 0; 
                }
                html { 
                    background: transparent !important; 
                }
            </style>
        </head>
        <body>
            <div class="toast-container position-fixed top-0 end-0 p-3">
                <div class="toast show" role="alert" style="background-color: #dc3545; color: white;">
                    <div class="toast-body">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        User ID not found. Please login again.
                    </div>
                </div>
            </div>
            <script>
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 3000);
            </script>
        </body>
        </html>
        <?php
        exit();
    }

    // Get form data
    $firstName = $_POST['firstName'] ?? '';
    $middleName = $_POST['middleName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $nickname = $_POST['nickname'] ?? '';
    $birthdate = $_POST['birthdate'] ?? '';
    $birthplace = $_POST['birthplace'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $maritalStatus = $_POST['maritalStatus'] ?? '';
    $citizenship = $_POST['citizenship'] ?? '';
    $religion = $_POST['religion'] ?? '';
    $sector = $_POST['sector'] ?? '';
    $education = $_POST['education'] ?? '';
    $height = $_POST['height'] ?? '';
    $weight = $_POST['weight'] ?? '';
    $sitio = $_POST['sitio'] ?? '';
    $houseNumber = $_POST['houseNumber'] ?? '';
    $purok = $_POST['purok'] ?? '';
    $sinceYear = $_POST['sinceYear'] ?? '';
    $householdNumber = $_POST['householdNumber'] ?? '';
    $houseOwner = $_POST['houseOwner'] ?? '';
    $shelterType = $_POST['shelterType'] ?? '';
    $houseMaterial = $_POST['houseMaterial'] ?? '';
    
    // Handle profile picture upload
    $profile_image = null;
    $target_dir = "uploads/";
    
    // Create uploads directory if it doesn't exist
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    // Check if a new profile picture was uploaded
    if (!empty($_FILES["profilePicture"]["name"])) {
        // Get current profile image to delete later
        $current_image_query = "SELECT profile_upload FROM residents WHERE id = ?";
        if ($stmt = $conn->prepare($current_image_query)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $current_data = $result->fetch_assoc();
            $old_image = $current_data['profile_upload'] ?? null;
            $stmt->close();
        }
        
        // Generate unique filename
        $file_extension = strtolower(pathinfo($_FILES["profilePicture"]["name"], PATHINFO_EXTENSION));
        $unique_filename = uniqid() . '_' . time() . '.' . $file_extension;
        $target_file = $target_dir . $unique_filename;
        
        // Validate image
        $check = getimagesize($_FILES["profilePicture"]["tmp_name"]);
        if ($check !== false) {
            $allowed_types = ["jpg", "jpeg", "png", "gif", "bmp", "webp"];
            
            if (in_array($file_extension, $allowed_types)) {
                if ($_FILES["profilePicture"]["size"] <= 5000000) { // 5MB limit
                    if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $target_file)) {
                        $profile_image = $target_file;
                        
                        // Delete old image if it exists and is not the default
                        if ($old_image && $old_image != 'default-profile.jpg' && file_exists($old_image)) {
                            unlink($old_image);
                        }
                    } else {
                        ?>
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
                            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
                            <style>
                                body { 
                                    background: transparent !important; 
                                    margin: 0; 
                                    padding: 0; 
                                }
                                html { 
                                    background: transparent !important; 
                                }
                            </style>
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
                        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
                    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
                </head>
                <body>
                    <div class="toast-container position-fixed top-0 end-0 p-3">
                        <div class="toast show" role="alert" style="background-color: #dc3545; color: white;">
                            <div class="toast-body">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                Invalid image format. Allowed: JPG, JPEG, PNG, GIF, BMP, WEBP.
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
                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
    
    // Prepare SQL update statement
    if ($profile_image) {
        // Update with new profile image
        $sql = "UPDATE residents SET 
                first_name = ?, middle_name = ?, last_name = ?, nickname = ?, 
                birthdate = ?, birthplace = ?, gender = ?, marital_status = ?, 
                citizenship = ?, religion = ?, sector = ?, education = ?, 
                height = ?, weight = ?, sitio = ?, house_number = ?, purok = ?, 
                since_year = ?, household_number = ?, house_owner = ?, 
                shelter_type = ?, house_material = ?, profile_upload = ?
                WHERE id = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssssssssssssssssssssi", 
                $firstName, $middleName, $lastName, $nickname, 
                $birthdate, $birthplace, $gender, $maritalStatus, 
                $citizenship, $religion, $sector, $education, 
                $height, $weight, $sitio, $houseNumber, $purok, 
                $sinceYear, $householdNumber, $houseOwner, 
                $shelterType, $houseMaterial, $profile_image, $user_id);
        }
    } else {
        // Update without changing profile image
        $sql = "UPDATE residents SET 
                first_name = ?, middle_name = ?, last_name = ?, nickname = ?, 
                birthdate = ?, birthplace = ?, gender = ?, marital_status = ?, 
                citizenship = ?, religion = ?, sector = ?, education = ?, 
                height = ?, weight = ?, sitio = ?, house_number = ?, purok = ?, 
                since_year = ?, household_number = ?, house_owner = ?, 
                shelter_type = ?, house_material = ?
                WHERE id = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssssssssssssssssssssssi", 
                $firstName, $middleName, $lastName, $nickname, 
                $birthdate, $birthplace, $gender, $maritalStatus, 
                $citizenship, $religion, $sector, $education, 
                $height, $weight, $sitio, $houseNumber, $purok, 
                $sinceYear, $householdNumber, $houseOwner, 
                $shelterType, $houseMaterial, $user_id);
        }
    }
    
    // Execute the update
    if ($stmt && $stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
                <style>
                    body { 
                        background: transparent !important; 
                        margin: 0; 
                        padding: 0; 
                    }
                    html { 
                        background: transparent !important; 
                    }
                </style>
            </head>
            <body>
                <div class="toast-container position-fixed top-0 end-0 p-3">
                    <div class="toast show" role="alert" style="background-color: #28a745; color: white;">
                        <div class="toast-body">
                            <i class="fas fa-check-circle me-2"></i>
                            Profile updated successfully!
                        </div>
                    </div>
                </div>
                <script>
                    setTimeout(function() {
                        window.location.href = 'landing_acc.php'; // Redirect to profile page
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
                <style>
                    body { 
                        background: transparent !important; 
                        margin: 0; 
                        padding: 0; 
                    }
                    html { 
                        background: transparent !important; 
                    }
                </style>
            </head>
            <body>
                <div class="toast-container position-fixed top-0 end-0 p-3">
                    <div class="toast show" role="alert" style="background-color: #ffc107; color: #212529;">
                        <div class="toast-body">
                            <i class="fas fa-info-circle me-2"></i>
                            No changes were made to your profile.
                        </div>
                    </div>
                </div>
                <script>
                    setTimeout(function() {
                        window.history.back();
                    }, 2000);
                </script>
            </body>
            </html>
            <?php
        }
    } else {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
            <style>
                body { 
                    background: transparent !important; 
                    margin: 0; 
                    padding: 0; 
                }
                html { 
                    background: transparent !important; 
                }
            </style>
        </head>
        <body>
            <div class="toast-container position-fixed top-0 end-0 p-3">
                <div class="toast show" role="alert" style="background-color: #dc3545; color: white;">
                    <div class="toast-body">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Error updating profile: <?php echo $stmt ? $stmt->error : $conn->error; ?>
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
    
    if ($stmt) {
        $stmt->close();
    }
    $conn->close();
} else {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <style>
            body { 
                background: transparent !important; 
                margin: 0; 
                padding: 0; 
            }
            html { 
                background: transparent !important; 
            }
        </style>
    </head>
    <body>
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div class="toast show" role="alert" style="background-color: #dc3545; color: white;">
                <div class="toast-body">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Invalid request method.
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
<?php
// Database configuration
$host = "localhost";
$username = "root"; // default in XAMPP
$password = "";     // default in XAMPP
$database = "barangay";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle profile image upload
    $profileFile = $_FILES['profile-upload']['name'];
    $targetDir = "uploads/";
    $targetFilePath = $targetDir . basename($profileFile);

    
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    if (!empty($profileFile)) {
        move_uploaded_file($_FILES['profile-upload']['tmp_name'], $targetFilePath);
    } else {
        $targetFilePath = ""; // Default empty if no upload
    }

    // Collect all form data
    $last_name = $_POST['last-name'];
    $first_name = $_POST['first-name'];
    $middle_name = $_POST['middle-name'];
    $nickname_alias = $_POST['nickname-alias'];
    $birthdate = $_POST['birthdate'];
    $birthplace = $_POST['birthplace'];
    $citizenship = $_POST['citizenship'];
    $gender = $_POST['gender'];
    $mobile_number = $_POST['mobile-number'];
    $email = $_POST['email'];
    $marital_status = $_POST['marital-status'];
    $religion = $_POST['religion'];
    $sector = $_POST['sector'];
    $education = $_POST['education'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $sitio = $_POST['sitio'];
    $house_number = $_POST['house-number'];
    $purok = $_POST['purok'];
    $since_year = $_POST['since-year'];
    $household_number = $_POST['household-number'];
    $house_owner = $_POST['house-owner'];
    $shelter_type = $_POST['shelter-type'];
    $house_material = $_POST['house-material'];
    
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt the password

    // Insert query
    $sql = "INSERT INTO reg_online (
        profile_upload, last_name, first_name, middle_name, nickname_alias,
        birthdate, birthplace, citizenship, gender, mobile_number, email,
        marital_status, religion, sector, education, height, weight,
        sitio, house_number, purok, since_year, household_number, house_owner,
        shelter_type, house_material, password
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
    )";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssssssssssssss",
        $targetFilePath, $last_name, $first_name, $middle_name, $nickname_alias,
        $birthdate, $birthplace, $citizenship, $gender, $mobile_number, $email,
        $marital_status, $religion, $sector, $education, $height, $weight,
        $sitio, $house_number, $purok, $since_year, $household_number, $house_owner,
        $shelter_type, $house_material, $password
    );

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location.href='your_form_page.html';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

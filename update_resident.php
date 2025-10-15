<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$database = "barangay";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $originalLastName = $_POST['original_last_name'];
    $originalFirstName = $_POST['original_first_name'];
    
    $lastName = $_POST['last_name'];
    $firstName = $_POST['first_name'];
    $middleName = $_POST['middle_name'];
    $nickname = $_POST['nickname'];
    $birthdate = $_POST['birthdate'];
    $birthplace = $_POST['birthplace'];
    $citizenship = $_POST['citizenship'];
    $gender = $_POST['gender'];
    $mobileNumber = $_POST['mobile_number'];
    $email = $_POST['email'];
    $maritalStatus = $_POST['marital_status'];
    $religion = $_POST['religion'];
    $sector = $_POST['sector'];
    $education = $_POST['education'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $sitio = $_POST['sitio'];
    $houseNumber = $_POST['house_number'];
    $purok = $_POST['purok'];
    $sinceYear = $_POST['since_year'];
    $householdNumber = $_POST['household_number'];
    $houseOwner = $_POST['house_owner'];
    $shelterType = $_POST['shelter_type'];
    $houseMaterial = $_POST['house_material'];
    
    $sql = "UPDATE residents SET 
            last_name = ?, 
            first_name = ?, 
            middle_name = ?, 
            nickname = ?, 
            birthdate = ?, 
            birthplace = ?, 
            citizenship = ?, 
            gender = ?, 
            mobile_number = ?, 
            email = ?, 
            marital_status = ?, 
            religion = ?, 
            sector = ?, 
            education = ?, 
            height = ?, 
            weight = ?, 
            sitio = ?, 
            house_number = ?, 
            purok = ?, 
            since_year = ?, 
            household_number = ?, 
            house_owner = ?, 
            shelter_type = ?, 
            house_material = ?
            WHERE last_name = ? AND first_name = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssssssssssssss", 
        $lastName, $firstName, $middleName, $nickname, $birthdate, $birthplace, 
        $citizenship, $gender, $mobileNumber, $email, $maritalStatus, $religion, 
        $sector, $education, $height, $weight, $sitio, $houseNumber, $purok, 
        $sinceYear, $householdNumber, $houseOwner, $shelterType, $houseMaterial,
        $originalLastName, $originalFirstName
    );
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Resident updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update resident']);
    }
    
    $stmt->close();
}

$conn->close();
?>
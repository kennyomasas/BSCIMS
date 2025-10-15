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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $certificateName = mysqli_real_escape_string($conn, $_POST['certificateName']);
    $certificateDescription = mysqli_real_escape_string($conn, $_POST['certificateDescription']);
    $requiresPayment = isset($_POST['requiresPayment']) ? 1 : 0;
    $feeAmount = $requiresPayment ? floatval($_POST['feeAmount']) : 0;
    $fields = isset($_POST['fields']) ? $_POST['fields'] : [];
    
    // Handle file upload if a template was provided
    $templatePath = null;
    if (isset($_FILES['certificateTemplate']) && $_FILES['certificateTemplate']['error'] == 0) {
        $uploadDir = 'uploads/templates/';
        
        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = basename($_FILES['certificateTemplate']['name']);
        $targetFilePath = $uploadDir . time() . '_' . $fileName;
        
        // Upload file
        if (move_uploaded_file($_FILES['certificateTemplate']['tmp_name'], $targetFilePath)) {
            $templatePath = $targetFilePath;
        }
    }
    
    // Insert certificate type into database
    $sql = "INSERT INTO certificate_types (certificate_name, description, template_path, requires_payment, fee_amount) 
            VALUES (?, ?, ?, ?, ?)";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssid", $certificateName, $certificateDescription, $templatePath, $requiresPayment, $feeAmount);
    
    if ($stmt->execute()) {
        $certificateTypeId = $conn->insert_id;
        
        // Insert fields for this certificate type
        if (!empty($fields)) {
            $fieldSql = "INSERT INTO certificate_fields (certificate_type_id, field_name) VALUES (?, ?)";
            $fieldStmt = $conn->prepare($fieldSql);
            
            foreach ($fields as $field) {
                if (!empty($field)) {
                    $fieldStmt->bind_param("is", $certificateTypeId, $field);
                    $fieldStmt->execute();
                }
            }
            $fieldStmt->close();
        }
        
        // Success
        echo json_encode(['status' => 'success', 'message' => 'Certificate type added successfully']);
    } else {
        // Error
        echo json_encode(['status' => 'error', 'message' => 'Error adding certificate type: ' . $stmt->error]);
    }
    
    $stmt->close();
    $conn->close();
    exit;
}
?>
<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

try {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "barangay";

    // Create connection with error handling
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $issuedTo = isset($_POST['issuedTo']) ? $_POST['issuedTo'] : '';
    $certificateTypeInput = isset($_POST['certificateType']) ? $_POST['certificateType'] : '';
    $purpose = isset($_POST['purpose']) ? $_POST['purpose'] : '';
    $signatory = isset($_POST['signatory']) ? $_POST['signatory'] : '';

    // Log received data for debugging
    error_log("Received data: " . json_encode($_POST));

    // Validate required fields
    if (empty($issuedTo) || empty($certificateTypeInput) || empty($purpose) || empty($signatory)) {
        throw new Exception("All fields are required. Missing: " . 
            (empty($issuedTo) ? "Resident Name, " : "") .
            (empty($certificateTypeInput) ? "Certificate Type, " : "") .
            (empty($purpose) ? "Purpose, " : "") .
            (empty($signatory) ? "Signatory" : ""));
    }

    // DETERMINE IF THE INPUT IS AN ID (NUMERIC) OR NAME (TEXT)
    $certificateTypeName = '';
    
    if (is_numeric($certificateTypeInput)) {
        // Input is an ID, fetch the certificate name
        $certQuery = "SELECT certificate_name FROM certificate_types WHERE id = ?";
        $certStmt = $conn->prepare($certQuery);
        
        if (!$certStmt) {
            throw new Exception("Prepare failed for certificate lookup: " . $conn->error);
        }
        
        $certStmt->bind_param("i", $certificateTypeInput);
        $certStmt->execute();
        $certResult = $certStmt->get_result();
        
        if ($certResult->num_rows === 0) {
            throw new Exception("Invalid certificate type selected");
        }
        
        $certRow = $certResult->fetch_assoc();
        $certificateTypeName = $certRow['certificate_name'];
        $certStmt->close();
        
    } else {
        // Input is already a certificate name, use it directly
        // But verify it exists in the database
        $certQuery = "SELECT certificate_name FROM certificate_types WHERE certificate_name = ?";
        $certStmt = $conn->prepare($certQuery);
        
        if (!$certStmt) {
            throw new Exception("Prepare failed for certificate verification: " . $conn->error);
        }
        
        $certStmt->bind_param("s", $certificateTypeInput);
        $certStmt->execute();
        $certResult = $certStmt->get_result();
        
        if ($certResult->num_rows === 0) {
            throw new Exception("Invalid certificate type: " . $certificateTypeInput);
        }
        
        $certificateTypeName = $certificateTypeInput;
        $certStmt->close();
    }

    // Generate request ID (simple format: current date + random number)
    $requestId = date('Ymd') . rand(1000, 9999);

    // Set default status as "Pending"
    $status = "Pending";

    // Current date and time
    $requestDate = date('Y-m-d H:i:s');

    // Insert data into certificate_requests table
    $sql = "INSERT INTO certificate_requests (request_id, resident_name, certificate_type, purpose, signatory, status, request_date)
             VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $bindResult = $stmt->bind_param("sssssss", $requestId, $issuedTo, $certificateTypeName, $purpose, $signatory, $status, $requestDate);
    
    if (!$bindResult) {
        throw new Exception("Binding parameters failed: " . $stmt->error);
    }

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Certificate request submitted successfully! Request ID: ' . $requestId
        ]);
    } else {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    
    // Log error for debugging
    error_log("Certificate request error: " . $e->getMessage());
}
?>
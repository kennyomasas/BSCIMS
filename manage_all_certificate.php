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

// Function to get all certificate types with their fields
function getCertificateTypes($conn) {
    $sql = "SELECT ct.id, ct.certificate_name, ct.description, ct.template_path, 
                   ct.requires_payment, ct.fee_amount, ct.created_at,
                   GROUP_CONCAT(cf.field_name SEPARATOR ', ') as fields
            FROM certificate_types ct
            LEFT JOIN certificate_fields cf ON ct.id = cf.certificate_type_id
            GROUP BY ct.id
            ORDER BY ct.created_at DESC";
    
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Handle AJAX requests for manage operations
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action == 'load_certificates') {
        $certificateTypes = getCertificateTypes($conn);
        echo json_encode(['status' => 'success', 'certificates' => $certificateTypes]);
        exit;
    }
    
    if ($action == 'delete') {
        $certificateId = intval($_POST['certificate_id']);
        
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // Delete associated fields first
            $deleteFields = "DELETE FROM certificate_fields WHERE certificate_type_id = ?";
            $stmt1 = $conn->prepare($deleteFields);
            $stmt1->bind_param("i", $certificateId);
            $stmt1->execute();
            
            // Delete certificate type
            $deleteCert = "DELETE FROM certificate_types WHERE id = ?";
            $stmt2 = $conn->prepare($deleteCert);
            $stmt2->bind_param("i", $certificateId);
            $stmt2->execute();
            
            // Commit transaction
            $conn->commit();
            
            echo json_encode(['status' => 'success', 'message' => 'Certificate type deleted successfully']);
        } catch (Exception $e) {
            // Rollback transaction
            $conn->rollback();
            echo json_encode(['status' => 'error', 'message' => 'Error deleting certificate type: ' . $e->getMessage()]);
        }
        
        $stmt1->close();
        $stmt2->close();
        exit;
    }
    
    if ($action == 'get_certificate') {
        $certificateId = intval($_POST['certificate_id']);
        
        // Get certificate details
        $sql = "SELECT * FROM certificate_types WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $certificateId);
        $stmt->execute();
        $result = $stmt->get_result();
        $certificate = $result->fetch_assoc();
        
        // Get certificate fields
        $fieldSql = "SELECT field_name FROM certificate_fields WHERE certificate_type_id = ?";
        $fieldStmt = $conn->prepare($fieldSql);
        $fieldStmt->bind_param("i", $certificateId);
        $fieldStmt->execute();
        $fieldResult = $fieldStmt->get_result();
        $fields = [];
        while ($row = $fieldResult->fetch_assoc()) {
            $fields[] = $row['field_name'];
        }
        
        $certificate['fields'] = $fields;
        
        echo json_encode(['status' => 'success', 'data' => $certificate]);
        
        $stmt->close();
        $fieldStmt->close();
        exit;
    }
    
    if ($action == 'update') {
        $certificateId = intval($_POST['certificate_id']);
        $certificateName = mysqli_real_escape_string($conn, $_POST['certificateName']);
        $certificateDescription = mysqli_real_escape_string($conn, $_POST['certificateDescription']);
        $requiresPayment = isset($_POST['requiresPayment']) ? 1 : 0;
        $feeAmount = $requiresPayment ? floatval($_POST['feeAmount']) : 0;
        $fields = isset($_POST['fields']) ? $_POST['fields'] : [];
        
        // Handle file upload if a new template was provided
        $templatePath = $_POST['existing_template']; // Keep existing template by default
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
                // Delete old template file if it exists
                if ($templatePath && file_exists($templatePath)) {
                    unlink($templatePath);
                }
                $templatePath = $targetFilePath;
            }
        }
        
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // Update certificate type
            $sql = "UPDATE certificate_types SET 
                    certificate_name = ?, 
                    description = ?, 
                    template_path = ?, 
                    requires_payment = ?, 
                    fee_amount = ? 
                    WHERE id = ?";
                    
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssidi", $certificateName, $certificateDescription, $templatePath, $requiresPayment, $feeAmount, $certificateId);
            $stmt->execute();
            
            // Delete existing fields
            $deleteFields = "DELETE FROM certificate_fields WHERE certificate_type_id = ?";
            $deleteStmt = $conn->prepare($deleteFields);
            $deleteStmt->bind_param("i", $certificateId);
            $deleteStmt->execute();
            
            // Insert new fields
            if (!empty($fields)) {
                $fieldSql = "INSERT INTO certificate_fields (certificate_type_id, field_name) VALUES (?, ?)";
                $fieldStmt = $conn->prepare($fieldSql);
                
                foreach ($fields as $field) {
                    if (!empty($field)) {
                        $fieldStmt->bind_param("is", $certificateId, $field);
                        $fieldStmt->execute();
                    }
                }
                $fieldStmt->close();
            }
            
            // Commit transaction
            $conn->commit();
            
            echo json_encode(['status' => 'success', 'message' => 'Certificate type updated successfully']);
        } catch (Exception $e) {
            // Rollback transaction
            $conn->rollback();
            echo json_encode(['status' => 'error', 'message' => 'Error updating certificate type: ' . $e->getMessage()]);
        }
        
        $stmt->close();
        $deleteStmt->close();
        exit;
    }
}

// Get all certificate types for display
$certificateTypes = getCertificateTypes($conn);
?>
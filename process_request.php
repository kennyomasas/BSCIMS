<?php
header('Content-Type: application/json');

// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "barangay";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Get JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || !isset($data['action']) || !isset($data['request_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request data']);
    exit();
}

$action = $data['action'];
$request_id = intval($data['request_id']);

if ($request_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid request ID']);
    exit();
}

try {
    if ($action === 'approve') {
        // First, get the request data
        $selectSql = "SELECT * FROM reg_online WHERE id = ? AND status = 'pending'";
        $selectStmt = $conn->prepare($selectSql);
        $selectStmt->bind_param("i", $request_id);
        $selectStmt->execute();
        $result = $selectStmt->get_result();
        
        if ($result->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'Request not found or already processed']);
            exit();
        }
        
        $requestData = $result->fetch_assoc();
        $selectStmt->close();
        
        // Start transaction
        $conn->autocommit(FALSE);
        
        try {
            // Transfer data to residents table including password
            $insertSql = "INSERT INTO residents (
                last_name, first_name, middle_name, nickname, birthdate, birthplace, 
                citizenship, gender, mobile_number, email, marital_status, religion, 
                sector, education, height, weight, sitio, house_number, purok, 
                since_year, household_number, house_owner, shelter_type, house_material, 
                profile_image, password
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("ssssssssssssssssssssssssss",
                $requestData['last_name'], $requestData['first_name'], $requestData['middle_name'], 
                $requestData['nickname_alias'], $requestData['birthdate'], $requestData['birthplace'], 
                $requestData['citizenship'], $requestData['gender'], $requestData['mobile_number'], 
                $requestData['email'], $requestData['marital_status'], $requestData['religion'], 
                $requestData['sector'], $requestData['education'], $requestData['height'], 
                $requestData['weight'], $requestData['sitio'], $requestData['house_number'], 
                $requestData['purok'], $requestData['since_year'], $requestData['household_number'], 
                $requestData['house_owner'], $requestData['shelter_type'], $requestData['house_material'], 
                $requestData['profile_upload'], $requestData['password']
            );
            
            if (!$insertStmt->execute()) {
                throw new Exception("Failed to create resident record: " . $insertStmt->error);
            }
            $insertStmt->close();
            
            // Remove the data from reg_online table after successful transfer
            $deleteSql = "DELETE FROM reg_online WHERE id = ?";
            $deleteStmt = $conn->prepare($deleteSql);
            $deleteStmt->bind_param("i", $request_id);
            
            if (!$deleteStmt->execute()) {
                throw new Exception("Failed to remove request from online registration table");
            }
            $deleteStmt->close();
            
            // Commit the transaction
            $conn->commit();
            echo json_encode(['success' => true, 'message' => 'Request approved, resident added with credentials, and registration request removed successfully']);
            
        } catch (Exception $e) {
            // Rollback the transaction on error
            $conn->rollback();
            throw $e;
        }
        
    } elseif ($action === 'decline') {
        // Update the status to declined
        $sql = "UPDATE reg_online SET status = 'declined' WHERE id = ? AND status = 'pending'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $request_id);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(['success' => true, 'message' => 'Request declined successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Request not found or already processed']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to decline request']);
        }
        
        $stmt->close();
        
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->autocommit(TRUE);
$conn->close();
?>
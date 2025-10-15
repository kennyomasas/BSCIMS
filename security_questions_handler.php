<?php
/**
 * Security Questions Handler
 * Handles AJAX requests for managing security questions
 */
session_start();
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barangay";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8");
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Database connection error.'
    ]);
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'User not authenticated.'
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle different actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    switch ($action) {
        case 'save_security_questions':
            handleSaveSecurityQuestions($conn, $user_id);
            break;
            
        case 'get_security_questions':
            handleGetSecurityQuestions($conn, $user_id);
            break;
            
        case 'delete_security_questions':
            handleDeleteSecurityQuestions($conn, $user_id);
            break;
            
        default:
            echo json_encode([
                'success' => false,
                'message' => 'Invalid action.'
            ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}

$conn->close();

/**
 * Save security questions to database
 */
function handleSaveSecurityQuestions($conn, $user_id) {
    // Validate required fields
    $required_fields = ['question1', 'answer1', 'question2', 'answer2', 'question3', 'answer3'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            echo json_encode([
                'success' => false,
                'message' => 'All security questions and answers are required.'
            ]);
            return;
        }
    }
    
    $question1 = trim($_POST['question1']);
    $answer1 = trim($_POST['answer1']);
    $question2 = trim($_POST['question2']);
    $answer2 = trim($_POST['answer2']);
    $question3 = trim($_POST['question3']);
    $answer3 = trim($_POST['answer3']);
    
    // Validate questions are different
    $questions = [$question1, $question2, $question3];
    if (count($questions) !== count(array_unique($questions))) {
        echo json_encode([
            'success' => false,
            'message' => 'Please select different questions for each security question.'
        ]);
        return;
    }
    
    // Validate answer lengths
    if (strlen($answer1) < 2 || strlen($answer2) < 2 || strlen($answer3) < 2) {
        echo json_encode([
            'success' => false,
            'message' => 'Security question answers must be at least 2 characters long.'
        ]);
        return;
    }
    
    try {
        // Hash the answers for security
        $hashed_answer1 = password_hash(strtolower($answer1), PASSWORD_DEFAULT);
        $hashed_answer2 = password_hash(strtolower($answer2), PASSWORD_DEFAULT);
        $hashed_answer3 = password_hash(strtolower($answer3), PASSWORD_DEFAULT);
        
        // Check if security questions already exist for this user
        $check_sql = "SELECT id FROM security_questions WHERE user_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $user_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Update existing security questions
            $update_sql = "UPDATE security_questions SET 
                          question1 = ?, answer1 = ?, 
                          question2 = ?, answer2 = ?, 
                          question3 = ?, answer3 = ?, 
                          updated_at = CURRENT_TIMESTAMP 
                          WHERE user_id = ?";
            
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ssssssi", $question1, $hashed_answer1, $question2, $hashed_answer2, $question3, $hashed_answer3, $user_id);
            
        } else {
            // Insert new security questions
            $insert_sql = "INSERT INTO security_questions (user_id, question1, answer1, question2, answer2, question3, answer3) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("issssss", $user_id, $question1, $hashed_answer1, $question2, $hashed_answer2, $question3, $hashed_answer3);
        }
        
        if ($stmt->execute()) {
            // Log the activity
            logActivity($conn, $user_id, 'security_questions_updated', 'Security questions updated');
            
            echo json_encode([
                'success' => true,
                'message' => 'Security questions saved successfully!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error saving security questions. Please try again.'
            ]);
        }
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Database error occurred while saving security questions.'
        ]);
    }
}

/**
 * Get existing security questions for a user
 */
function handleGetSecurityQuestions($conn, $user_id) {
    try {
        $sql = "SELECT question1, question2, question3, created_at, updated_at 
                FROM security_questions 
                WHERE user_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            echo json_encode([
                'success' => true,
                'message' => 'Security questions retrieved successfully.',
                'data' => [
                    'question1' => $data['question1'],
                    'question2' => $data['question2'],
                    'question3' => $data['question3'],
                    'created_at' => $data['created_at'],
                    'updated_at' => $data['updated_at']
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No security questions found for this user.',
                'data' => null
            ]);
        }
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error retrieving security questions.'
        ]);
    }
}

/**
 * Delete security questions for a user
 */
function handleDeleteSecurityQuestions($conn, $user_id) {
    try {
        $sql = "DELETE FROM security_questions WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                // Log the activity
                logActivity($conn, $user_id, 'security_questions_deleted', 'Security questions deleted');
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Security questions deleted successfully!'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No security questions found to delete.'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error deleting security questions. Please try again.'
            ]);
        }
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Database error occurred while deleting security questions.'
        ]);
    }
}

/**
 * Log user activities
 */
function logActivity($conn, $user_id, $action, $description) {
    try {
        $log_sql = "INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent, timestamp) 
                   VALUES (?, ?, ?, ?, ?, NOW())";
        
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        
        $stmt = $conn->prepare($log_sql);
        $stmt->bind_param("issss", $user_id, $action, $description, $ip_address, $user_agent);
        $stmt->execute();
    } catch (Exception $e) {
        // Log error but don't fail the main operation
        error_log("Failed to log activity: " . $e->getMessage());
    }
}

/**
 * Verify security questions answers (for password recovery)
 */
function verifySecurityAnswers($conn, $user_id, $answer1, $answer2, $answer3) {
    try {
        $sql = "SELECT answer1, answer2, answer3 FROM security_questions WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false;
        }
        
        $stored_answers = $result->fetch_assoc();
        
        // Verify all three answers
        $answer1_valid = password_verify(strtolower(trim($answer1)), $stored_answers['answer1']);
        $answer2_valid = password_verify(strtolower(trim($answer2)), $stored_answers['answer2']);
        $answer3_valid = password_verify(strtolower(trim($answer3)), $stored_answers['answer3']);
        
        return ($answer1_valid && $answer2_valid && $answer3_valid);
        
    } catch (Exception $e) {
        return false;
    }
}

?>
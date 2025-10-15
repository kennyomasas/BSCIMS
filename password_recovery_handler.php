<?php
/**
 * Password Recovery Handler
 * Handles AJAX requests for password recovery using security questions
 */
// Start session and include database connection
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
    
    // Set charset
    $conn->set_charset("utf8");
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Database connection error.'
    ]);
    exit();
}

// Handle different actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    switch ($action) {
        case 'get_security_questions':
            handleGetSecurityQuestions($conn);
            break;
            
        case 'verify_security_answers':
            handleVerifySecurityAnswers($conn);
            break;
            
        case 'reset_password':
            handleResetPassword($conn);
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
 * Get security questions for a user
 */
function handleGetSecurityQuestions($conn) {
    if (!isset($_POST['user_identifier']) || empty($_POST['user_identifier'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Username or email is required.'
        ]);
        return;
    }
    
    $user_identifier = trim($_POST['user_identifier']);
    
    try {
        // Find user by email or username (adjust field names based on your database)
        $sql = "SELECT r.id, r.full_name, r.email, r.username, 
                       sq.question1, sq.question2, sq.question3 
                FROM residents r 
                LEFT JOIN security_questions sq ON r.id = sq.user_id 
                WHERE r.email = ? OR r.username = ? OR r.full_name = ?
                LIMIT 1";
                
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $user_identifier, $user_identifier, $user_identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            echo json_encode([
                'success' => false,
                'message' => 'User not found. Please check your username or email.'
            ]);
            return;
        }
        
        $user = $result->fetch_assoc();
        
        // Check if security questions are set up
        if (empty($user['question1']) || empty($user['question2']) || empty($user['question3'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Security questions not set up for this account. Please contact administrator.'
            ]);
            return;
        }
        
        // Store user ID in session for verification
        $_SESSION['password_recovery_user_id'] = $user['id'];
        
        echo json_encode([
            'success' => true,
            'message' => 'Security questions retrieved successfully.',
            'data' => [
                'user_name' => $user['full_name'],
                'questions' => [
                    'question1' => $user['question1'],
                    'question2' => $user['question2'],
                    'question3' => $user['question3']
                ]
            ]
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error retrieving security questions.'
        ]);
    }
}

/**
 * Verify security answers
 */
function handleVerifySecurityAnswers($conn) {
    // Check if user ID is in session
    if (!isset($_SESSION['password_recovery_user_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Session expired. Please start the recovery process again.'
        ]);
        return;
    }
    
    // Validate required fields
    $required_fields = ['answer1', 'answer2', 'answer3'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            echo json_encode([
                'success' => false,
                'message' => 'All security answers are required.'
            ]);
            return;
        }
    }
    
    $user_id = $_SESSION['password_recovery_user_id'];
    $answer1 = trim($_POST['answer1']);
    $answer2 = trim($_POST['answer2']);
    $answer3 = trim($_POST['answer3']);
    
    try {
        // Get stored answers (assuming they are hashed)
        $sql = "SELECT answer1, answer2, answer3 FROM security_questions WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Security questions not found.'
            ]);
            return;
        }
        
        $stored_answers = $result->fetch_assoc();
        
        // Verify answers (assuming answers are stored as hashed values)
        $answer1_valid = password_verify(strtolower($answer1), $stored_answers['answer1']);
        $answer2_valid = password_verify(strtolower($answer2), $stored_answers['answer2']);
        $answer3_valid = password_verify(strtolower($answer3), $stored_answers['answer3']);
        
        // Alternative: If answers are stored as plain text (less secure)
        // $answer1_valid = strtolower($answer1) === strtolower($stored_answers['answer1']);
        // $answer2_valid = strtolower($answer2) === strtolower($stored_answers['answer2']);
        // $answer3_valid = strtolower($answer3) === strtolower($stored_answers['answer3']);
        
        if ($answer1_valid && $answer2_valid && $answer3_valid) {
            // All answers correct, allow password reset
            $_SESSION['security_verified'] = true;
            $_SESSION['security_verified_time'] = time();
            
            echo json_encode([
                'success' => true,
                'message' => 'Security answers verified successfully. You can now reset your password.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'One or more security answers are incorrect. Please try again.'
            ]);
        }
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error verifying security answers.'
        ]);
    }
}

/**
 * Reset password after security verification
 */
function handleResetPassword($conn) {
    // Check if user ID is in session
    if (!isset($_SESSION['password_recovery_user_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Session expired. Please start the recovery process again.'
        ]);
        return;
    }
    
    // Check if security has been verified
    if (!isset($_SESSION['security_verified']) || !$_SESSION['security_verified']) {
        echo json_encode([
            'success' => false,
            'message' => 'Security verification required.'
        ]);
        return;
    }
    
    // Check if verification is still valid (e.g., within 15 minutes)
    $verification_timeout = 900; // 15 minutes
    if (!isset($_SESSION['security_verified_time']) || 
        (time() - $_SESSION['security_verified_time']) > $verification_timeout) {
        echo json_encode([
            'success' => false,
            'message' => 'Security verification expired. Please verify your answers again.'
        ]);
        unset($_SESSION['security_verified']);
        return;
    }
    
    // Validate new password
    if (!isset($_POST['new_password']) || empty($_POST['new_password'])) {
        echo json_encode([
            'success' => false,
            'message' => 'New password is required.'
        ]);
        return;
    }
    
    if (!isset($_POST['confirm_password']) || empty($_POST['confirm_password'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Password confirmation is required.'
        ]);
        return;
    }
    
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Check if passwords match
    if ($new_password !== $confirm_password) {
        echo json_encode([
            'success' => false,
            'message' => 'Passwords do not match.'
        ]);
        return;
    }
    
    // Password strength validation
    if (strlen($new_password) < 8) {
        echo json_encode([
            'success' => false,
            'message' => 'Password must be at least 8 characters long.'
        ]);
        return;
    }
    
    $user_id = $_SESSION['password_recovery_user_id'];
    
    try {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update password in database
        $sql = "UPDATE residents SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $hashed_password, $user_id);
        
        if ($stmt->execute()) {
            // Clear recovery session variables
            unset($_SESSION['password_recovery_user_id']);
            unset($_SESSION['security_verified']);
            unset($_SESSION['security_verified_time']);
            
            // Optional: Log the password reset activity
            $log_sql = "INSERT INTO activity_logs (user_id, action, description, timestamp) VALUES (?, 'password_reset', 'Password reset via security questions', NOW())";
            $log_stmt = $conn->prepare($log_sql);
            $log_stmt->bind_param("i", $user_id);
            $log_stmt->execute();
            
            echo json_encode([
                'success' => true,
                'message' => 'Password reset successfully. You can now login with your new password.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error updating password. Please try again.'
            ]);
        }
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error resetting password.'
        ]);
    }
}

/**
 * Additional utility function to clear recovery session
 */
function clearRecoverySession() {
    unset($_SESSION['password_recovery_user_id']);
    unset($_SESSION['security_verified']);
    unset($_SESSION['security_verified_time']);
}

?>
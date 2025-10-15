<?php
/**
 * Forgot Password Handler
 * Handles password recovery process with security questions
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

// Handle different actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    switch ($action) {
        case 'find_account':
            handleFindAccount($conn);
            break;
            
        case 'get_security_questions':
            handleGetSecurityQuestions($conn);
            break;
            
        case 'verify_answers':
            handleVerifyAnswers($conn);
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
 * Find account by email or username
 */
function handleFindAccount($conn) {
    if (!isset($_POST['identifier']) || empty(trim($_POST['identifier']))) {
        echo json_encode([
            'success' => false,
            'message' => 'Please enter your email address.'
        ]);
        return;
    }
    
    $identifier = trim($_POST['identifier']);
    
    try {
        // First, let's check what columns exist in your residents table
        // You might need to adjust these column names based on your actual table structure
        
        // Try different possible column names for email
        $email_columns = ['email', 'gmail', 'user_email', 'resident_email'];
        $username_columns = ['username', 'user_name', 'resident_username'];
        $name_columns = [
            ['first_name', 'last_name'], 
            ['firstname', 'lastname'], 
            ['fname', 'lname'],
            ['resident_name', ''], // single name column
            ['name', ''] // single name column
        ];
        
        // Build dynamic query based on existing columns
        $email_conditions = [];
        $username_conditions = [];
        $params = [];
        $types = '';
        
        // Check which email column exists and build condition
        foreach ($email_columns as $col) {
            $check_sql = "SHOW COLUMNS FROM residents LIKE '$col'";
            $check_result = $conn->query($check_sql);
            if ($check_result && $check_result->num_rows > 0) {
                $email_conditions[] = "$col = ?";
                $params[] = $identifier;
                $types .= 's';
                break;
            }
        }
        
        // Check which username column exists and build condition
        foreach ($username_columns as $col) {
            $check_sql = "SHOW COLUMNS FROM residents LIKE '$col'";
            $check_result = $conn->query($check_sql);
            if ($check_result && $check_result->num_rows > 0) {
                $username_conditions[] = "$col = ?";
                $params[] = $identifier;
                $types .= 's';
                break;
            }
        }
        
        if (empty($email_conditions) && empty($username_conditions)) {
            echo json_encode([
                'success' => false,
                'message' => 'Database configuration error. Please contact administrator.'
            ]);
            return;
        }
        
        // Determine name columns
        $name_select = 'id';
        foreach ($name_columns as $cols) {
            $first_col = $cols[0];
            $last_col = $cols[1];
            
            $check_sql = "SHOW COLUMNS FROM residents LIKE '$first_col'";
            $check_result = $conn->query($check_sql);
            
            if ($check_result && $check_result->num_rows > 0) {
                if (!empty($last_col)) {
                    $check_sql2 = "SHOW COLUMNS FROM residents LIKE '$last_col'";
                    $check_result2 = $conn->query($check_sql2);
                    if ($check_result2 && $check_result2->num_rows > 0) {
                        $name_select = "id, $first_col, $last_col";
                        break;
                    }
                } else {
                    $name_select = "id, $first_col as full_name";
                    break;
                }
            }
        }
        
        // Build the WHERE clause
        $where_conditions = array_merge($email_conditions, $username_conditions);
        $where_clause = implode(' OR ', $where_conditions);
        
        // Final query
        $sql = "SELECT $name_select FROM residents WHERE $where_clause LIMIT 1";
        
        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Check if user has security questions set up
            $security_sql = "SELECT id FROM security_questions WHERE user_id = ?";
            $security_stmt = $conn->prepare($security_sql);
            $security_stmt->bind_param("i", $user['id']);
            $security_stmt->execute();
            $security_result = $security_stmt->get_result();
            
            if ($security_result->num_rows === 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'No security questions found for this account. Please contact administrator.'
                ]);
                return;
            }
            
            // Store user ID in session for the recovery process
            $_SESSION['recovery_user_id'] = $user['id'];
            
            // Determine display name
            $display_name = 'User';
            if (isset($user['full_name'])) {
                $display_name = $user['full_name'];
            } elseif (isset($user['first_name']) && isset($user['last_name'])) {
                $display_name = $user['first_name'] . ' ' . $user['last_name'];
            } elseif (isset($user['first_name'])) {
                $display_name = $user['first_name'];
            }
            
            echo json_encode([
                'success' => true,
                'message' => 'Account found successfully.',
                'data' => [
                    'user_id' => $user['id'],
                    'full_name' => $display_name,
                    'identifier' => $identifier
                ]
            ]);
            
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No account found with that email or username.'
            ]);
        }
        
    } catch (Exception $e) {
        // More detailed error for debugging
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage(),
            'debug_info' => [
                'error_code' => $e->getCode(),
                'identifier' => $identifier
            ]
        ]);
    }
}

/**
 * Get security questions for the user
 */
function handleGetSecurityQuestions($conn) {
    if (!isset($_SESSION['recovery_user_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Session expired. Please start the recovery process again.'
        ]);
        return;
    }
    
    $user_id = $_SESSION['recovery_user_id'];
    
    try {
        $sql = "SELECT question1, question2, question3 
                FROM security_questions 
                WHERE user_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $questions = $result->fetch_assoc();
            echo json_encode([
                'success' => true,
                'message' => 'Security questions retrieved successfully.',
                'data' => [
                    'question1' => $questions['question1'],
                    'question2' => $questions['question2'],
                    'question3' => $questions['question3']
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No security questions found.'
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
 * Verify security question answers
 */
function handleVerifyAnswers($conn) {
    if (!isset($_SESSION['recovery_user_id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Session expired. Please start the recovery process again.'
        ]);
        return;
    }
    
    $required_fields = ['answer1', 'answer2', 'answer3'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            echo json_encode([
                'success' => false,
                'message' => 'Please answer all security questions.'
            ]);
            return;
        }
    }
    
    $user_id = $_SESSION['recovery_user_id'];
    $answer1 = trim($_POST['answer1']);
    $answer2 = trim($_POST['answer2']);
    $answer3 = trim($_POST['answer3']);
    
    try {
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
        
        // Verify all three answers (case-insensitive)
        $answer1_valid = password_verify(strtolower($answer1), $stored_answers['answer1']);
        $answer2_valid = password_verify(strtolower($answer2), $stored_answers['answer2']);
        $answer3_valid = password_verify(strtolower($answer3), $stored_answers['answer3']);
        
        if ($answer1_valid && $answer2_valid && $answer3_valid) {
            // Mark session as verified for password reset
            $_SESSION['recovery_verified'] = true;
            
            // Log the verification attempt
            logActivity($conn, $user_id, 'password_recovery_verified', 'Security questions verified for password recovery');
            
            echo json_encode([
                'success' => true,
                'message' => 'Security questions verified successfully. You can now reset your password.'
            ]);
        } else {
            // Log failed attempt
            logActivity($conn, $user_id, 'password_recovery_failed', 'Failed security questions verification');
            
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
 * Reset password after successful verification
 */
function handleResetPassword($conn) {
    if (!isset($_SESSION['recovery_user_id']) || !isset($_SESSION['recovery_verified'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Unauthorized. Please complete the verification process first.'
        ]);
        return;
    }
    
    if (!isset($_POST['new_password']) || !isset($_POST['confirm_password'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Please provide both password fields.'
        ]);
        return;
    }
    
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate password requirements
    if (strlen($new_password) < 8) {
        echo json_encode([
            'success' => false,
            'message' => 'Password must be at least 8 characters long.'
        ]);
        return;
    }
    
    if (!preg_match('/[A-Z]/', $new_password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Password must contain at least one uppercase letter.'
        ]);
        return;
    }
    
    if (!preg_match('/[a-z]/', $new_password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Password must contain at least one lowercase letter.'
        ]);
        return;
    }
    
    if (!preg_match('/[0-9]/', $new_password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Password must contain at least one number.'
        ]);
        return;
    }
    
    if ($new_password !== $confirm_password) {
        echo json_encode([
            'success' => false,
            'message' => 'Passwords do not match.'
        ]);
        return;
    }
    
    $user_id = $_SESSION['recovery_user_id'];
    
    try {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // First, check what columns exist in the residents table
        $check_columns = [];
        $possible_password_columns = ['password', 'user_password', 'resident_password', 'pwd'];
        $possible_updated_columns = ['updated_at', 'updated_date', 'date_updated', 'last_updated'];
        
        $password_column = '';
        $updated_column = '';
        
        // Check which password column exists
        foreach ($possible_password_columns as $col) {
            $check_sql = "SHOW COLUMNS FROM residents LIKE '$col'";
            $check_result = $conn->query($check_sql);
            if ($check_result && $check_result->num_rows > 0) {
                $password_column = $col;
                break;
            }
        }
        
        // Check which updated timestamp column exists (optional)
        foreach ($possible_updated_columns as $col) {
            $check_sql = "SHOW COLUMNS FROM residents LIKE '$col'";
            $check_result = $conn->query($check_sql);
            if ($check_result && $check_result->num_rows > 0) {
                $updated_column = $col;
                break;
            }
        }
        
        if (empty($password_column)) {
            echo json_encode([
                'success' => false,
                'message' => 'Database configuration error: Password column not found. Please contact administrator.',
                'debug' => 'No password column found in residents table'
            ]);
            return;
        }
        
        // Build the UPDATE query
        if (!empty($updated_column)) {
            $sql = "UPDATE residents SET $password_column = ?, $updated_column = CURRENT_TIMESTAMP WHERE id = ?";
        } else {
            $sql = "UPDATE residents SET $password_column = ? WHERE id = ?";
        }
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo json_encode([
                'success' => false,
                'message' => 'Database prepare error: ' . $conn->error,
                'debug' => 'SQL: ' . $sql
            ]);
            return;
        }
        
        $stmt->bind_param("si", $hashed_password, $user_id);
        
        if ($stmt->execute()) {
            // Check if any row was actually updated
            if ($stmt->affected_rows > 0) {
                // Log successful password reset
                logActivity($conn, $user_id, 'password_reset_success', 'Password reset successfully via security questions');
                
                // Clear recovery session data
                unset($_SESSION['recovery_user_id']);
                unset($_SESSION['recovery_verified']);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Password reset successfully! You can now login with your new password.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No user found with the provided ID or password is already the same.',
                    'debug' => 'No rows affected. User ID: ' . $user_id
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error executing update query: ' . $stmt->error,
                'debug' => [
                    'sql' => $sql,
                    'user_id' => $user_id,
                    'mysql_error' => $stmt->error,
                    'mysql_errno' => $stmt->errno
                ]
            ]);
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Database error occurred while resetting password.',
            'debug' => [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]
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

?>
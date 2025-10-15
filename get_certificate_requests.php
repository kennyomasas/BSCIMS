    <?php
// get_certificate_requests.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

try {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "barangay";
    $conn = new mysqli($servername, $username, $password, $dbname); 
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }  
    // Get certificate type from POST data
    $certificateType = isset($_POST['certificate_type']) ? $_POST['certificate_type'] : '';
    
    if (empty($certificateType)) {
        throw new Exception("Certificate type is required");
    }  
    // Prepare SQL to get requests by certificate type
    $sql = "SELECT id, request_id, resident_name, certificate_type, purpose, signatory, status, request_date, processed_date, notes 
            FROM certificate_requests 
            WHERE certificate_type = ? 
            ORDER BY request_date DESC";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }   
    $stmt->bind_param("s", $certificateType);
    $stmt->execute();
    $result = $stmt->get_result();
    $requests = array();
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    } 
    echo json_encode([
        'status' => 'success',
        'data' => $requests
    ]);   
    $stmt->close();
    $conn->close();  
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
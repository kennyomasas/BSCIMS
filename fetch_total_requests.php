<?php
// fetch_certificate_requests_new.php
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

    // Set charset to handle special characters properly
    $conn->set_charset("utf8");

    // Base SQL query
    $sql = "SELECT 
                id,
                request_id, 
                resident_name, 
                certificate_type, 
                purpose, 
                signatory, 
                status, 
                request_date,
                processed_date,
                notes,
                DATE_FORMAT(request_date, '%Y-%m-%d %H:%i:%s') as formatted_request_date,
                DATE_FORMAT(processed_date, '%Y-%m-%d %H:%i:%s') as formatted_processed_date
            FROM certificate_requests";

    // Add optional filters if provided
    $conditions = [];
    $params = [];
    $types = "";

    // Filter by certificate type if provided
    if (isset($_GET['certificate_type']) && !empty($_GET['certificate_type'])) {
        $conditions[] = "certificate_type = ?";
        $params[] = $_GET['certificate_type'];
        $types .= "s";
    }

    // Filter by status if provided
    if (isset($_GET['status']) && !empty($_GET['status'])) {
        $conditions[] = "status = ?";
        $params[] = $_GET['status'];
        $types .= "s";
    }

    // Filter by resident name if provided (partial match)
    if (isset($_GET['resident_name']) && !empty($_GET['resident_name'])) {
        $conditions[] = "resident_name LIKE ?";
        $params[] = "%" . $_GET['resident_name'] . "%";
        $types .= "s";
    }

    // Filter by date range if provided
    if (isset($_GET['start_date']) && !empty($_GET['start_date'])) {
        $conditions[] = "DATE(request_date) >= ?";
        $params[] = $_GET['start_date'];
        $types .= "s";
    }

    if (isset($_GET['end_date']) && !empty($_GET['end_date'])) {
        $conditions[] = "DATE(request_date) <= ?";
        $params[] = $_GET['end_date'];
        $types .= "s";
    }

    // Add WHERE clause if there are conditions
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    // Add ordering and optional limit
    $sql .= " ORDER BY request_date DESC";

    // Add limit if provided
    if (isset($_GET['limit']) && is_numeric($_GET['limit'])) {
        $sql .= " LIMIT " . intval($_GET['limit']);
    }

    // Prepare and execute statement
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    // Bind parameters if any
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $requests = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Format the data for better frontend handling
            $requests[] = [
                'id' => $row['id'],
                'request_id' => $row['request_id'],
                'resident_name' => $row['resident_name'],
                'certificate_type' => $row['certificate_type'],
                'purpose' => $row['purpose'] ?? 'N/A',
                'signatory' => $row['signatory'] ?? 'N/A',
                'status' => $row['status'],
                'request_date' => $row['request_date'],
                'processed_date' => $row['processed_date'],
                'notes' => $row['notes'] ?? '',
                'formatted_request_date' => $row['formatted_request_date'],
                'formatted_processed_date' => $row['formatted_processed_date'],
                // Add some computed fields
                'days_pending' => $row['processed_date'] ? 
                    null : 
                    floor((time() - strtotime($row['request_date'])) / (60 * 60 * 24)),
                'is_urgent' => floor((time() - strtotime($row['request_date'])) / (60 * 60 * 24)) > 7 ? true : false
            ];
        }
    }

    // Return success response with data and metadata
    echo json_encode([
        'status' => 'success',
        'data' => $requests,
        'total' => count($requests),
        'filters_applied' => [
            'certificate_type' => $_GET['certificate_type'] ?? null,
            'status' => $_GET['status'] ?? null,
            'resident_name' => $_GET['resident_name'] ?? null,
            'start_date' => $_GET['start_date'] ?? null,
            'end_date' => $_GET['end_date'] ?? null,
            'limit' => $_GET['limit'] ?? null
        ],
        'timestamp' => date('Y-m-d H:i:s')
    ]);

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    // Log error for debugging
    error_log("Fetch certificate requests error: " . $e->getMessage());
    
    // Return error response
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to fetch certificate requests: ' . $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ]);
}
?>
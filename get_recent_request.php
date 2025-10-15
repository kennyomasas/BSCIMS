<?php
// FILE 2: get_recent_requests.php
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

    // Get recent requests (last 20, ordered by date)
    $query = "SELECT request_id, resident_name, certificate_type, purpose, status, request_date 
              FROM certificate_requests 
              ORDER BY request_date DESC 
              LIMIT 20";

    $result = $conn->query($query);
    $requests = array();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }
    }

    echo json_encode([
        'status' => 'success',
        'requests' => $requests
    ]);

    $conn->close();

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
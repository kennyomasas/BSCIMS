<?php
// ===== FILE 1: get_request_counts.php =====
// This file returns counts for different request statuses

header('Content-Type: application/json');

try {
    // Database connection (same as your existing config)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "barangay";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Get counts for different statuses
    $counts = array();

    // Pending requests
    $pendingQuery = "SELECT COUNT(*) as count FROM certificate_requests WHERE status = 'Pending'";
    $result = $conn->query($pendingQuery);
    $counts['pending'] = $result->fetch_assoc()['count'];

    // Processing requests
    $processingQuery = "SELECT COUNT(*) as count FROM certificate_requests WHERE status = 'Processing'";
    $result = $conn->query($processingQuery);
    $counts['processing'] = $result->fetch_assoc()['count'];

    // Completed today
    $completedTodayQuery = "SELECT COUNT(*) as count FROM certificate_requests 
                           WHERE status = 'Completed' AND DATE(request_date) = CURDATE()";
    $result = $conn->query($completedTodayQuery);
    $counts['completed_today'] = $result->fetch_assoc()['count'];

    // Total this month
    $totalMonthQuery = "SELECT COUNT(*) as count FROM certificate_requests 
                       WHERE MONTH(request_date) = MONTH(CURDATE()) 
                       AND YEAR(request_date) = YEAR(CURDATE())";
    $result = $conn->query($totalMonthQuery);
    $counts['total_month'] = $result->fetch_assoc()['count'];

    echo json_encode([
        'status' => 'success',
        'counts' => $counts
    ]);

    $conn->close();

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

// ===== FILE 2: get_recent_requests.php =====
// Create this as a separate file
?>




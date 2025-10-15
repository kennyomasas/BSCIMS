<?php
// get_certificate_stats.php
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
    
    // Get statistics
    $stats = array(
        'total' => 0,
        'pending' => 0,
        'approved' => 0,
        'issued' => 0
    );
    
    // Total requests
    $totalSql = "SELECT COUNT(*) as count FROM certificate_requests";
    $totalResult = $conn->query($totalSql);
    if ($totalResult && $row = $totalResult->fetch_assoc()) {
        $stats['total'] = $row['count'];
    }
    
    // Pending requests
    $pendingSql = "SELECT COUNT(*) as count FROM certificate_requests WHERE status = 'Pending'";
    $pendingResult = $conn->query($pendingSql);
    if ($pendingResult && $row = $pendingResult->fetch_assoc()) {
        $stats['pending'] = $row['count'];
    }
    
    // Approved requests
    $approvedSql = "SELECT COUNT(*) as count FROM certificate_requests WHERE status = 'Approved'";
    $approvedResult = $conn->query($approvedSql);
    if ($approvedResult && $row = $approvedResult->fetch_assoc()) {
        $stats['approved'] = $row['count'];
    }
    
    // Issued certificates
    $issuedSql = "SELECT COUNT(*) as count FROM certificate_requests WHERE status = 'Issued'";
    $issuedResult = $conn->query($issuedSql);
    if ($issuedResult && $row = $issuedResult->fetch_assoc()) {
        $stats['issued'] = $row['count'];
    }
    
    echo json_encode([
        'status' => 'success',
        'data' => $stats
    ]);
    
    $conn->close();
    
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>

<?php
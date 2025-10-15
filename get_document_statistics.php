<?php
// get_document_statistics.php
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
    
    // Get document type statistics
    $sql = "SELECT 
                certificate_type,
                COUNT(*) as count
            FROM certificate_requests 
            GROUP BY certificate_type
            ORDER BY count DESC";
    
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Query failed: " . $conn->error);
    }
    
    $documentTypes = array();
    $documentCounts = array();
    
    // Initialize with expected document types (in case some have 0 requests)
    $expectedTypes = [
        'Barangay Clearance' => 0,
        'Certificate of Residency' => 0,
        'Business permit' => 0,
        'Certificate of Indigency' => 0,
        'Other Documents' => 0
    ];
    
    while ($row = $result->fetch_assoc()) {
        $certType = $row['certificate_type'];
        $count = (int)$row['count'];
        
        // Map certificate types to chart labels
        switch ($certType) {
            case 'Barangay Clearance':
            case 'barangay_clearance':
                $expectedTypes['Barangay Clearance'] += $count;
                break;
            case 'Certificate of Residency':
            case 'certificate_of_residency':
                $expectedTypes['Certificate of Residency'] += $count;
                break;
            case 'Business permit':
            case 'business_permit':
                $expectedTypes['Business permit'] += $count;
                break;
            case 'Certificate of Indigency':
            case 'certificate_of_indigency':
                $expectedTypes['Certificate of Indigency'] += $count;
                break;
            default:
                $expectedTypes['Other Documents'] += $count;
                break;
        }
    }
    
    // Prepare data for Chart.js
    $chartData = array(
        'labels' => array_keys($expectedTypes),
        'data' => array_values($expectedTypes)
    );
    
    echo json_encode([
        'status' => 'success',
        'chartData' => $chartData,
        'totalRequests' => array_sum($expectedTypes)
    ]);
    
    $conn->close();
    
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
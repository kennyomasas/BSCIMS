<?php
// get_monthly_trends.php
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
    
    // Get the current year or specific year if provided
    $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
    
    // SQL to get monthly request counts
    $sql = "SELECT 
                MONTH(request_date) as month,
                MONTHNAME(request_date) as month_name,
                COUNT(*) as request_count
            FROM certificate_requests 
            WHERE YEAR(request_date) = ?
            GROUP BY MONTH(request_date), MONTHNAME(request_date)
            ORDER BY MONTH(request_date)";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("i", $year);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Initialize array with all 12 months
    $months = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
        5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
        9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
    ];
    
    $monthly_data = [];
    $labels = [];
    $data = [];
    
    // Initialize all months with 0
    for ($i = 1; $i <= 12; $i++) {
        $monthly_data[$i] = 0;
    }
    
    // Fill in actual data
    while ($row = $result->fetch_assoc()) {
        $monthly_data[intval($row['month'])] = intval($row['request_count']);
    }
    
    // Prepare final arrays for Chart.js
    foreach ($monthly_data as $month_num => $count) {
        $labels[] = $months[$month_num];
        $data[] = $count;
    }
    
    echo json_encode([
        'status' => 'success',
        'year' => $year,
        'labels' => $labels,
        'data' => $data,
        'total_requests' => array_sum($data)
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
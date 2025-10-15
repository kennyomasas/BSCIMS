<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set content type to JSON
header('Content-Type: application/json');

// Database connection (same as your add_resident.php)
$servername = "localhost";
$username = "root";
$password = "";
$database = "barangay";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Connection failed: ' . $conn->connect_error,
        'data' => []
    ]);
    exit();
}

// Check if this is a search request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'search') {
    $query = isset($_POST['query']) ? trim($_POST['query']) : '';
    
    if (empty($query)) {
        echo json_encode([]);
        exit();
    }
    
    // Prepare search query - search for names that START with the query
    $searchTerm = $query . "%";  // Changed from "%query%" to "query%"
    
    $sql = "SELECT 
                id,
                first_name,
                last_name,
                sitio,
                purok,
                CONCAT(first_name, ' ', last_name) as full_name
            FROM residents 
            WHERE 
                first_name LIKE ? OR
                last_name LIKE ? OR
                CONCAT(first_name, ' ', last_name) LIKE ?
            ORDER BY last_name, first_name
            LIMIT 10";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $residents = [];
            
            while ($row = $result->fetch_assoc()) {
                $residents[] = [
                    'id' => $row['id'],
                    'full_name' => $row['full_name'],
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'sitio' => $row['sitio'],
                    'purok' => $row['purok']
                ];
            }
            
            echo json_encode($residents);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Query execution failed: ' . $stmt->error,
                'data' => []
            ]);
        }
        
        $stmt->close();
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Query preparation failed: ' . $conn->error,
            'data' => []
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request',
        'data' => []
    ]);
}

$conn->close();
?>
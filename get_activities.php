<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$database = "barangay"; // Your database name

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'get_activities') {
        // Get filter parameters
        $month = $_POST['month'] ?? '';
        $year = $_POST['year'] ?? '';
        $type = $_POST['type'] ?? '';
        
        // Build the query
        $sql = "SELECT id, title, description, start_date, end_date, created_at,
                        activity_type, participant_count
                FROM announcements_events WHERE 1=1";
        
        $params = [];
        $types = "";
        
        // Add filters
        if (!empty($month) && !empty($year)) {
            $sql .= " AND MONTH(start_date) = ? AND YEAR(start_date) = ?";
            $params[] = $month;
            $params[] = $year;
            $types .= "ii";
        }
        
        if (!empty($type) && $type !== 'all') {
            // Filter by activity_type from database
            $sql .= " AND activity_type = ?";
            $params[] = $type;
            $types .= "s";
        }
        
        $sql .= " ORDER BY start_date DESC";
        
        $stmt = $conn->prepare($sql);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $activities = [];
        while ($row = $result->fetch_assoc()) {
            // Use the activity_type from database and format it for display
            $row['type'] = formatActivityType($row['activity_type']);
            
            // Use participant_count from database or set default
            $row['participants'] = $row['participant_count'] ?? 0;
            
            $activities[] = $row;
        }
        
        echo json_encode([
            'success' => true, 
            'activities' => $activities,
            'total' => count($activities)
        ]);
        
        $stmt->close();
        
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

function formatActivityType($activityType) {
    // Format the activity type for display
    switch($activityType) {
        case 'meetings':
            return 'Meeting';
        case 'community_programs':
            return 'Community Program';
        case 'projects':
            return 'Project';
        case 'celebrations':
            return 'Celebration';
        default:
            return ucfirst(str_replace('_', ' ', $activityType));
    }
}

$conn->close();
?>
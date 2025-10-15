<?php
// get_announcements.php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$database = "barangay"; // Your database name

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

// Calculate date 5 days ago
$fiveDaysAgo = date('Y-m-d H:i:s', strtotime('-5 days'));

$sql = "SELECT id, title, description, activity_type, participant_count, start_date, end_date, created_at 
        FROM announcements_events 
        WHERE created_at >= ? 
        ORDER BY start_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $fiveDaysAgo);
$stmt->execute();
$result = $stmt->get_result();

$events = [];

while ($row = $result->fetch_assoc()) {
    $event = [
        'id' => $row['id'],
        'title' => $row['title'],
        'start' => $row['start_date'],
        'backgroundColor' => '#667eea',
        'borderColor' => '#667eea',
        'extendedProps' => [
            'description' => $row['description'],
            'activity_type' => $row['activity_type'],
            'participant_count' => $row['participant_count'],
            'created_at' => $row['created_at']
        ]
    ];

    if (!empty($row['end_date'])) {
        $event['end'] = $row['end_date'];
    }

    $events[] = $event;
}

echo json_encode($events);

$conn->close();
?>

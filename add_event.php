<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root"; 
$password = ""; 
$database = "barangay"; 

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $activity_type = $_POST['activity_type'] ?? '';
    $participant_count = $_POST['participant_count'] ?? NULL; 
    $start_date = $_POST['start_date'] ?? '';
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : NULL;

    // Validate required fields
    if (empty($title) || empty($description) || empty($activity_type) || empty($start_date)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
        exit;
    }

    // Validate event type
    $valid_types = ['meetings', 'community_programs', 'projects', 'celebrations'];
    if (!in_array($activity_type, $valid_types)) {
        echo json_encode(['success' => false, 'message' => 'Invalid event type selected']);
        exit;
    }

    // Validate participants (if provided) - Fixed: use correct variable name
    if (!empty($participant_count) && (!is_numeric($participant_count) || $participant_count < 1)) {
        echo json_encode(['success' => false, 'message' => 'Number of participants must be a positive number']);
        exit;
    }

    // Convert empty string to NULL for database
    if (empty($participant_count)) {
        $participant_count = NULL;
    }

    $stmt = $conn->prepare("INSERT INTO announcements_events (title, description, activity_type, participant_count, start_date, end_date, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssiss", $title, $description, $activity_type, $participant_count, $start_date, $end_date);

    if ($stmt->execute()) {
        $insertId = $conn->insert_id;
        echo json_encode([
            'success' => true, 
            'message' => 'Event added successfully!',
            'id' => $insertId
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding event: ' . $stmt->error]);
    }
        
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>
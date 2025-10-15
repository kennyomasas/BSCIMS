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
    
    if ($action === 'delete_activity') {
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'Activity ID is required']);
            exit;
        }
        
        $stmt = $conn->prepare("DELETE FROM announcements_events WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(['success' => true, 'message' => 'Activity deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Activity not found']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting activity: ' . $stmt->error]);
        }
        
        $stmt->close();
        
    } elseif ($action === 'update_activity') {
        $id = $_POST['id'] ?? '';
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $start_date = $_POST['start_date'] ?? '';
        $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : NULL;
        
        if (empty($id) || empty($title) || empty($description) || empty($start_date)) {
            echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
            exit;
        }
        
        $stmt = $conn->prepare("UPDATE announcements_events SET title = ?, description = ?, start_date = ?, end_date = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $title, $description, $start_date, $end_date, $id);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(['success' => true, 'message' => 'Activity updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No changes made or activity not found']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating activity: ' . $stmt->error]);
        }
        
        $stmt->close();
        
    } elseif ($action === 'get_activity') {
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'Activity ID is required']);
            exit;
        }
        
        $stmt = $conn->prepare("SELECT * FROM announcements_events WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $activity = $result->fetch_assoc();
        
        if ($activity) {
            echo json_encode(['success' => true, 'activity' => $activity]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Activity not found']);
        }
        
        $stmt->close();
        
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>
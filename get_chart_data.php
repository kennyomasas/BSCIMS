<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "barangay";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Connection failed: ' . $conn->connect_error
    ]));
}

// Get gender distribution data
$genderData = [
    'male' => 0,
    'female' => 0
];

$genderQuery = "SELECT gender, COUNT(*) as count FROM residents GROUP BY gender";
$result = $conn->query($genderQuery);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        if (strtolower($row['gender']) == 'male') {
            $genderData['male'] = (int)$row['count'];
        } else if (strtolower($row['gender']) == 'female') {
            $genderData['female'] = (int)$row['count'];
        }
    }
}

// Get purok distribution data
$purokData = [
    'labels' => ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4', 'Purok 5', 
                'Purok 6', 'Purok 7', 'Purok 8', 'Purok 9', 'Purok 10', 'Purok 11'],
    'data' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
];

$purokQuery = "SELECT purok, COUNT(*) as count FROM residents GROUP BY purok ORDER BY purok";
$result = $conn->query($purokQuery);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $purokNumber = (int)preg_replace('/[^0-9]/', '', $row['purok']);
        if ($purokNumber >= 1 && $purokNumber <= 11) {
            $purokData['data'][$purokNumber - 1] = (int)$row['count'];
        }
    }
}

// Prepare the response
$response = [
    'gender' => $genderData,
    'purok' => $purokData
];

// Output as JSON
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
<?php
// get_landing_data.php - Dynamic content fetcher for landing page
// This file fetches all dynamic content from database

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barangay";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize data arrays
$hero_data = [
    'title' => 'Welcome to Barangay San Carlos',
    'description' => 'Your gateway to efficient community services and transparent governance in the digital age.',
    'image' => 'image/bhall.jpg'
];

$contact_data = [
    'address' => "Barangay San Carlos\nCity of Valencia\nProvince of Bukidnon",
    'phone' => '+63 123 456 7890',
    'email' => 'barangaysancarlosofficial@gmail.com'
];

$map_data = [
    'embed_url' => ''
];

// Fetch landing content (hero, contact, map)
$result = $conn->query("SELECT * FROM landing_content");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        if ($row['section'] == 'hero') {
            $hero_data[$row['field']] = $row['value'];
        }
        if ($row['section'] == 'contact') {
            $contact_data[$row['field']] = $row['value'];
        }
        if ($row['section'] == 'map') {
            $map_data[$row['field']] = $row['value'];
        }
    }
}

// Fetch features
$features = [];
$result = $conn->query("SELECT * FROM features ORDER BY id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $features[] = $row;
    }
}

// Fetch services
$services = [];
$result = $conn->query("SELECT * FROM services ORDER BY id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

// Fetch request data for tracking
$request_data = [];
$request_result = $conn->query("SELECT request_id, service_type, status, date_submitted FROM requests ORDER BY date_submitted DESC LIMIT 10");
if ($request_result) {
    while ($row = $request_result->fetch_assoc()) {
        $request_data[] = [
            'id' => $row['request_id'],
            'service' => $row['service_type'],
            'status' => $row['status'],
            'date' => $row['date_submitted']
        ];
    }
}

// Close database connection
$conn->close();

// Function to get hero data
function getHeroData() {
    global $hero_data;
    return $hero_data;
}

// Function to get contact data
function getContactData() {
    global $contact_data;
    return $contact_data;
}

// Function to get map data
function getMapData() {
    global $map_data;
    return $map_data;
}

// Function to get features
function getFeatures() {
    global $features;
    return $features;
}

// Function to get services
function getServices() {
    global $services;
    return $services;
}

// Function to get request data as JSON
function getRequestDataJSON() {
    global $request_data;
    return json_encode($request_data);
}

// API endpoint for AJAX calls
if (isset($_GET['api'])) {
    header('Content-Type: application/json');
    
    switch ($_GET['api']) {
        case 'hero':
            echo json_encode($hero_data);
            break;
        case 'contact':
            echo json_encode($contact_data);
            break;
        case 'features':
            echo json_encode($features);
            break;
        case 'services':
            echo json_encode($services);
            break;
        case 'requests':
            echo json_encode($request_data);
            break;
        case 'all':
            echo json_encode([
                'hero' => $hero_data,
                'contact' => $contact_data,
                'map' => $map_data,
                'features' => $features,
                'services' => $services,
                'requests' => $request_data
            ]);
            break;
        default:
            echo json_encode(['error' => 'Invalid API endpoint']);
    }
    exit;
}
?>
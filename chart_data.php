<?php

header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "barangay";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

$action = $_GET['action'] ?? '';

switch($action) {
    case 'age_groups':
        echo json_encode(getAgeGroupData($conn));
        break;
    case 'gender':
        echo json_encode(getGenderData($conn));
        break;
    case 'education':
        echo json_encode(getEducationData($conn));
        break;
    case 'marital_status':
        echo json_encode(getMaritalStatusData($conn));
        break;
    case 'sector':
        echo json_encode(getSectorData($conn));
        break;
    case 'purok':
        echo json_encode(getPurokData($conn));
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
}

function getAgeGroupData($conn) {
    $sql = "SELECT 
                CASE 
                    WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 0 AND 14 THEN '0-14'
                    WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 15 AND 24 THEN '15-24'
                    WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 25 AND 54 THEN '25-54'
                    WHEN TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 55 AND 64 THEN '55-64'
                    ELSE '65+'
                END as age_group,
                COUNT(*) as count
            FROM residents 
            WHERE birthdate IS NOT NULL
            GROUP BY age_group
            ORDER BY 
                CASE age_group
                    WHEN '0-14' THEN 1
                    WHEN '15-24' THEN 2
                    WHEN '25-54' THEN 3
                    WHEN '55-64' THEN 4
                    WHEN '65+' THEN 5
                END";
    
    $result = $conn->query($sql);
    $data = ['labels' => [], 'data' => []];
    
    while($row = $result->fetch_assoc()) {
        $data['labels'][] = $row['age_group'];
        $data['data'][] = (int)$row['count'];
    }
    
    return $data;
}

function getGenderData($conn) {
    $sql = "SELECT gender, COUNT(*) as count FROM residents GROUP BY gender";
    $result = $conn->query($sql);
    $data = ['labels' => [], 'data' => []];
    
    while($row = $result->fetch_assoc()) {
        $data['labels'][] = ucfirst($row['gender']);
        $data['data'][] = (int)$row['count'];
    }
    
    return $data;
}

function getEducationData($conn) {
    $sql = "SELECT education, COUNT(*) as count FROM residents WHERE education IS NOT NULL AND education != '' GROUP BY education ORDER BY count DESC";
    $result = $conn->query($sql);
    $data = ['labels' => [], 'data' => []];
    
    while($row = $result->fetch_assoc()) {
        $data['labels'][] = $row['education'];
        $data['data'][] = (int)$row['count'];
    }
    
    return $data;
}

function getMaritalStatusData($conn) {
    $sql = "SELECT marital_status, COUNT(*) as count FROM residents WHERE marital_status IS NOT NULL GROUP BY marital_status";
    $result = $conn->query($sql);
    $data = ['labels' => [], 'data' => []];
    
    while($row = $result->fetch_assoc()) {
        $data['labels'][] = ucfirst($row['marital_status']);
        $data['data'][] = (int)$row['count'];
    }
    
    return $data;
}

function getSectorData($conn) {
    $sql = "SELECT sector, COUNT(*) as count FROM residents WHERE sector IS NOT NULL AND sector != '' GROUP BY sector ORDER BY count DESC";
    $result = $conn->query($sql);
    $data = ['labels' => [], 'data' => []];
    
    while($row = $result->fetch_assoc()) {
        $data['labels'][] = $row['sector'];
        $data['data'][] = (int)$row['count'];
    }
    
    return $data;
}

function getPurokData($conn) {
    $sql = "SELECT purok, COUNT(*) as count FROM residents WHERE purok IS NOT NULL AND purok != '' GROUP BY purok ORDER BY purok";
    $result = $conn->query($sql);
    $data = ['labels' => [], 'data' => []];
    
    while($row = $result->fetch_assoc()) {
        $data['labels'][] = 'Purok ' . $row['purok'];
        $data['data'][] = (int)$row['count'];
    }
    
    return $data;
}

$conn->close();
?>
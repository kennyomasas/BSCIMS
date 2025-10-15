<?php
// functions.php - Place this in your project root directory
$servername = "localhost";
$username = "root";
$password = "";
$database = "barangay";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Function to get resident demographics summary
function getResidentDemographics($conn) {
    $demographics = [
        'total_households' => 0,
        'average_household_size' => 0,
        'senior_citizens' => 0,
        'children' => 0,
        'total_residents' => 0
    ];
    
    try {
        // Get total number of unique households
        $sql = "SELECT COUNT(DISTINCT household_number) as total_households FROM residents WHERE household_number IS NOT NULL AND household_number != ''";
        $result = $conn->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            $demographics['total_households'] = (int)$row['total_households'];
        }
        
        // Get total residents
        $sql = "SELECT COUNT(*) as total_residents FROM residents";
        $result = $conn->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            $demographics['total_residents'] = (int)$row['total_residents'];
        }
        
        // Calculate average household size
        if ($demographics['total_households'] > 0) {
            $demographics['average_household_size'] = round($demographics['total_residents'] / $demographics['total_households'], 1);
        }
        
        // Get senior citizens (65 years and older)
        $sql = "SELECT COUNT(*) as senior_citizens FROM residents 
                WHERE TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) >= 65 
                AND birthdate IS NOT NULL";
        $result = $conn->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            $demographics['senior_citizens'] = (int)$row['senior_citizens'];
        }
        
        // Get children (0-14 years old)
        $sql = "SELECT COUNT(*) as children FROM residents 
                WHERE TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) <= 14 
                AND birthdate IS NOT NULL";
        $result = $conn->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            $demographics['children'] = (int)$row['children'];
        }
        
    } catch (Exception $e) {
        error_log("Error getting demographics: " . $e->getMessage());
    }
    
    return $demographics;
}

// Function to get quarterly growth
function getQuarterlyGrowth($conn) {
    $growth = 0;
    
    try {
        // Get current quarter household count
        $sql = "SELECT COUNT(DISTINCT household_number) as current_households 
                FROM residents 
                WHERE household_number IS NOT NULL AND household_number != ''";
        $result = $conn->query($sql);
        $current_households = 0;
        if ($result && $row = $result->fetch_assoc()) {
            $current_households = (int)$row['current_households'];
        }
        
        // For now, return a placeholder growth rate
        // You can modify this when you add created_at timestamp
        $growth = 2.1;
        
    } catch (Exception $e) {
        error_log("Error calculating growth: " . $e->getMessage());
    }
    
    return $growth;
}
?>
<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set content type to JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database connection (same as add_residents.php)
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
        'message' => 'Database connection failed: ' . $conn->connect_error
    ]);
    exit();
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
    exit();
}

// Get search query
$search_query = isset($_POST['search_query']) ? trim($_POST['search_query']) : '';

if (empty($search_query)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Search query is required'
    ]);
    exit();
}

// Parse the search query for first name and last name
$search_parts = explode(' ', $search_query);
$search_parts = array_filter(array_map('trim', $search_parts)); // Remove empty parts

// Prepare SQL query to search residents
// Enhanced search: ID, individual names, and various full name combinations
$sql = "SELECT id, first_name, middle_name, last_name, mobile_number, email, 
               sitio, purok, profile_image, birthdate, gender, created_at 
        FROM residents 
        WHERE id LIKE ? 
           OR first_name LIKE ? 
           OR last_name LIKE ? 
           OR middle_name LIKE ?";

// Add conditions for full name searches
$sql .= " OR CONCAT(first_name, ' ', last_name) LIKE ?"; // First Last
$sql .= " OR CONCAT(first_name, ' ', IFNULL(middle_name, ''), ' ', last_name) LIKE ?"; // First Middle Last
$sql .= " OR CONCAT(last_name, ', ', first_name) LIKE ?"; // Last, First

// If multiple words provided, search for first name + last name separately
if (count($search_parts) >= 2) {
    $sql .= " OR (first_name LIKE ? AND last_name LIKE ?)"; // Separate first and last name match
    if (count($search_parts) >= 3) {
        $sql .= " OR (first_name LIKE ? AND middle_name LIKE ? AND last_name LIKE ?)"; // First, Middle, Last separate
    }
}

$sql .= " ORDER BY 
            CASE 
                WHEN CONCAT(first_name, ' ', last_name) LIKE ? THEN 1
                WHEN first_name LIKE ? THEN 2
                WHEN last_name LIKE ? THEN 3
                ELSE 4
            END,
            last_name, first_name
          LIMIT 20"; // Limit results and prioritize exact matches

try {
    if ($stmt = $conn->prepare($sql)) {
        // Create search patterns
        $search_pattern = '%' . $search_query . '%';
        
        // Base parameters
        $params = [
            $search_pattern, // ID
            $search_pattern, // first_name
            $search_pattern, // last_name  
            $search_pattern, // middle_name
            $search_pattern, // first_name + last_name
            $search_pattern, // first_name + middle_name + last_name
            $search_pattern, // last_name, first_name
        ];
        
        $types = "sssssss";
        
        // Add parameters for multi-word searches
        if (count($search_parts) >= 2) {
            $first_name_pattern = '%' . $search_parts[0] . '%';
            $last_name_pattern = '%' . $search_parts[count($search_parts) - 1] . '%';
            
            $params[] = $first_name_pattern;
            $params[] = $last_name_pattern;
            $types .= "ss";
            
            // If 3 or more words, assume middle name
            if (count($search_parts) >= 3) {
                $middle_name_pattern = '%' . $search_parts[1] . '%';
                $params[] = $first_name_pattern;
                $params[] = $middle_name_pattern;
                $params[] = $last_name_pattern;
                $types .= "sss";
            }
        }
        
        // Add parameters for ORDER BY clause
        $params[] = $search_pattern; // For CONCAT(first_name, ' ', last_name) LIKE ?
        $params[] = $search_pattern; // For first_name LIKE ?
        $params[] = $search_pattern; // For last_name LIKE ?
        $types .= "sss";
        
        // Bind parameters dynamically
        $stmt->bind_param($types, ...$params);
        
        // Execute query
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $residents = [];
            
            while ($row = $result->fetch_assoc()) {
                // Handle profile image path
                $profile_image = $row['profile_image'];
                if (empty($profile_image) || $profile_image === 'default-profile.jpg') {
                    $profile_image = 'uploads/default-profile.jpg';
                }
                
                // Format the resident data
                $residents[] = [
                    'id' => $row['id'],
                    'first_name' => $row['first_name'],
                    'middle_name' => $row['middle_name'],
                    'last_name' => $row['last_name'],
                    'mobile_number' => $row['mobile_number'],
                    'email' => $row['email'],
                    'sitio' => $row['sitio'],
                    'purok' => $row['purok'],
                    'profile_image' => $profile_image,
                    'birthdate' => $row['birthdate'],
                    'gender' => $row['gender'],
                    'created_at' => $row['created_at'],
                    'full_name' => trim($row['first_name'] . ' ' . ($row['middle_name'] ? $row['middle_name'] . ' ' : '') . $row['last_name'])
                ];
            }
            
            // Return successful response
            echo json_encode([
                'status' => 'success',
                'message' => 'Search completed successfully',
                'residents' => $residents,
                'total_found' => count($residents),
                'search_query' => $search_query,
                'search_parts' => $search_parts
            ]);
            
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error executing search query: ' . $stmt->error
            ]);
        }
        
        $stmt->close();
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error preparing search query: ' . $conn->error
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}

// Close connection
$conn->close();
?>
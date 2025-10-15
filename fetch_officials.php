<?php 
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "barangay";  

$conn = new mysqli($servername, $username, $password, $database);  

if ($conn->connect_error) {     
    die("Connection failed: " . $conn->connect_error); 
}  

// Get the term parameter if it exists 
$term = isset($_GET['term']) ? $_GET['term'] : '';  

// Build the query based on whether a term is selected 
if (!empty($term)) {     
    $sql = "SELECT id, complete_name, committee, position, photo FROM officials WHERE term = ? ORDER BY id ASC";     
    $stmt = $conn->prepare($sql);     
    $stmt->bind_param("s", $term); 
} else {     
    // If no term is selected, show all officials or show a message     
    $sql = "SELECT id, complete_name, committee, position, photo, term FROM officials ORDER BY term DESC, id ASC";     
    $stmt = $conn->prepare($sql); 
}  

$stmt->execute(); 
$result = $stmt->get_result();  

if ($result->num_rows > 0) {     
    while ($row = $result->fetch_assoc()) {         
        echo "<tr data-official-id='" . $row['id'] . "'>";
        
        // Hidden ID column for JavaScript access
        echo "<td style='display: none;'>" . $row['id'] . "</td>";
        
        echo "<td><img src='uploads/" . $row['photo'] . "' alt='Photo' width='50' height='50' style='border-radius: 50%;'></td>";         
        echo "<td>" . htmlspecialchars($row['complete_name']) . "</td>";         
        echo "<td>" . htmlspecialchars($row['committee']) . "</td>";         
        echo "<td>" . htmlspecialchars($row['position']) . "</td>";                  
        
        // Show term column only if no specific term is selected         
        if (empty($term)) {             
            echo "<td>" . htmlspecialchars($row['term']) . "</td>";         
        }
        
        // Actions column with edit and delete buttons
        echo "<td>";
        echo "<button class='action-btn edit-btn' onclick='enableEdit(this.closest(\"tr\"))' title='Edit'>";
        echo "<i class='fas fa-edit'></i>";
       
        echo "</td>";
        
        echo "</tr>";     
    } 
} else {     
    // Calculate colspan based on whether term column is shown
    $colspan = empty($term) ? 7 : 6; // 6 columns + actions when term is shown, 5 columns + actions when term is hidden
    
    if (!empty($term)) {         
        echo "<tr><td colspan='$colspan' class='text-center'>No officials found for term: " . htmlspecialchars($term) . "</td></tr>";     
    } else {         
        echo "<tr><td colspan='$colspan' class='text-center'>No officials found. Please select a term to view officials.</td></tr>";     
    } 
}  

$stmt->close(); 
$conn->close(); 
?>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "barangay";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['official_name'];
    $mobile = $_POST['official_mobile']; // This is actually the committee
    $position = $_POST['official_position'];
    $term = $_POST['official_term']; // Get the term from the form

    $photo = $_FILES['official_photo']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["official_photo"]["name"]);

    // Check if the term already has 11 officials
    $count_query = "SELECT COUNT(*) as official_count FROM officials WHERE term = ?";
    $stmt = $conn->prepare($count_query);
    $stmt->bind_param("s", $term);
    $stmt->execute();
    $count_result = $stmt->get_result();
    $count_row = $count_result->fetch_assoc();
    
    if ($count_row['official_count'] >= 11) {
        echo json_encode(["status" => "error", "message" => "Maximum of 11 officials allowed per term. This term already has 11 officials."]);
        $stmt->close();
        $conn->close();
        exit;
    }

    // Check if the official already exists in the same term
    $check_query = "SELECT * FROM officials WHERE complete_name = ? AND committee = ? AND position = ? AND term = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ssss", $name, $mobile, $position, $term);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If a duplicate entry exists for the same term, return a JSON response
        echo json_encode(["status" => "error", "message" => "This official already exists for the selected term."]);
    } else {
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["official_photo"]["tmp_name"], $target_file)) {
            // Insert the new official into the database with term
            $sql = "INSERT INTO officials (complete_name, committee, position, photo, term) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $name, $mobile, $position, $photo, $term);

            if ($stmt->execute()) {
                // Get the count after insertion to show in success message
                $count_query = "SELECT COUNT(*) as official_count FROM officials WHERE term = ?";
                $count_stmt = $conn->prepare($count_query);
                $count_stmt->bind_param("s", $term);
                $count_stmt->execute();
                $count_result = $count_stmt->get_result();
                $count_row = $count_result->fetch_assoc();
                $current_count = $count_row['official_count'];
                
                // Return success response with count information
                echo json_encode([
                    "status" => "success", 
                    "message" => "Official added successfully! ($current_count/11 officials for term $term)"
                ]);
                $count_stmt->close();
            } else {
                // Return error response if insertion fails
                echo json_encode(["status" => "error", "message" => "Failed to add official. Please try again."]);
            }
        } else {
            // Return error response if the file upload fails
            echo json_encode(["status" => "error", "message" => "Failed to upload the photo."]);
        }
    }
    $stmt->close();
}

$conn->close();
?>


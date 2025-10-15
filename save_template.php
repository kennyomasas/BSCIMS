<?php
$conn = new mysqli("localhost", "root", "", "barangay");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $template_name = $_POST['template_name'];
    $template_text = $_POST['template_text'];
    $signatory = $_POST['signatory'];
    $logo1_path = "";
    $logo2_path = "";

    // Handle Logo 1 Upload
    if (!empty($_FILES['logo1']['name'])) {
        $logo1_path = 'uploads/' . basename($_FILES['logo1']['name']);
        move_uploaded_file($_FILES['logo1']['tmp_name'], $logo1_path);
    }

    // Handle Logo 2 Upload
    if (!empty($_FILES['logo2']['name'])) {
        $logo2_path = 'uploads/' . basename($_FILES['logo2']['name']);
        move_uploaded_file($_FILES['logo2']['tmp_name'], $logo2_path);
    }

    // Insert into Database
    $sql = "INSERT INTO certificate_templates (template_name, logo1_path, logo2_path, template_text, signatory) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $template_name, $logo1_path, $logo2_path, $template_text, $signatory);

    if ($stmt->execute()) {
        echo "Template saved successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

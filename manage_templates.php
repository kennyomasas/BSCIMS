<?php
// manage_templates.php - Handles template CRUD operations

// Database connection
$conn = new mysqli("localhost", "root", "", "barangay");
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed']));
}

// Get action from request
$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

switch ($action) {
    case 'list':
        // List all templates
        $sql = "SELECT t.id, t.name, t.last_modified, c.certificate_name as certificate_type 
                FROM certificate_templates t
                LEFT JOIN certificate_types c ON t.certificate_type_id = c.id
                ORDER BY t.last_modified DESC";
        $result = $conn->query($sql);
        
        $templates = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $templates[] = $row;
            }
        }
        
        echo json_encode($templates);
        break;
        
    case 'get':
        // Get single template by ID
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid template ID']);
            exit;
        }
        
        $stmt = $conn->prepare("SELECT * FROM certificate_templates WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            echo json_encode(['status' => 'error', 'message' => 'Template not found']);
            exit;
        }
        
        $template = $result->fetch_assoc();
        echo json_encode(['status' => 'success', 'template' => $template]);
        break;
        
    case 'save':
        // Save or update template
        $templateId = isset($_POST['templateId']) ? intval($_POST['templateId']) : 0;
        $templateName = isset($_POST['templateName']) ? trim($_POST['templateName']) : '';
        $templateContent = isset($_POST['templateContent']) ? $_POST['templateContent'] : '';
        
        if (empty($templateName) || empty($templateContent)) {
            echo json_encode(['status' => 'error', 'message' => 'Name and content are required']);
            exit;
        }
        
        if ($templateId > 0) {
            // Update existing template
            $stmt = $conn->prepare("UPDATE certificate_templates SET name = ?, content = ?, last_modified = NOW() WHERE id = ?");
            $stmt->bind_param("ssi", $templateName, $templateContent, $templateId);
        } else {
            // Insert new template
            $stmt = $conn->prepare("INSERT INTO certificate_templates (name, content, last_modified) VALUES (?, ?, NOW())");
            $stmt->bind_param("ss", $templateName, $templateContent);
        }
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Template saved successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error saving template: ' . $conn->error]);
        }
        break;
        
    case 'delete':
        // Delete template
        $templateId = isset($_POST['templateId']) ? intval($_POST['templateId']) : 0;
        
        if ($templateId <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid template ID']);
            exit;
        }
        
        $stmt = $conn->prepare("DELETE FROM certificate_templates WHERE id = ?");
        $stmt->bind_param("i", $templateId);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Template deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error deleting template: ' . $conn->error]);
        }
        break;
        
    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
}

$conn->close();
?>
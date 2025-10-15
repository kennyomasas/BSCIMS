<?php
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

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'update_hero':
            $hero_title = $_POST['hero_title'];
            $hero_description = $_POST['hero_description'];
            $hero_image = $_POST['hero_image'];
            
            // Update or insert hero section data
            $stmt = $conn->prepare("INSERT INTO landing_content (section, field, value) VALUES ('hero', 'title', ?) ON DUPLICATE KEY UPDATE value = ?");
            $stmt->bind_param("ss", $hero_title, $hero_title);
            $stmt->execute();
            
            $stmt = $conn->prepare("INSERT INTO landing_content (section, field, value) VALUES ('hero', 'description', ?) ON DUPLICATE KEY UPDATE value = ?");
            $stmt->bind_param("ss", $hero_description, $hero_description);
            $stmt->execute();
            
            $stmt = $conn->prepare("INSERT INTO landing_content (section, field, value) VALUES ('hero', 'image', ?) ON DUPLICATE KEY UPDATE value = ?");
            $stmt->bind_param("ss", $hero_image, $hero_image);
            $stmt->execute();
            

            break;
            
        case 'add_feature':
            $feature_icon = $_POST['feature_icon'];
            $feature_title = $_POST['feature_title'];
            $feature_description = $_POST['feature_description'];
            
            $stmt = $conn->prepare("INSERT INTO features (icon, title, description) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $feature_icon, $feature_title, $feature_description);
            $stmt->execute();
            
            $success_message = "Feature added successfully!";
            break;
            
        case 'add_service':
            $service_icon = $_POST['service_icon'];
            $service_title = $_POST['service_title'];
            $service_description = $_POST['service_description'];
            
            $stmt = $conn->prepare("INSERT INTO services (icon, title, description) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $service_icon, $service_title, $service_description);
            $stmt->execute();
            
            $success_message = "Service added successfully!";
            break;
            
        case 'update_contact':
            $address = $_POST['address'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            
            $stmt = $conn->prepare("INSERT INTO landing_content (section, field, value) VALUES ('contact', 'address', ?) ON DUPLICATE KEY UPDATE value = ?");
            $stmt->bind_param("ss", $address, $address);
            $stmt->execute();
            
            $stmt = $conn->prepare("INSERT INTO landing_content (section, field, value) VALUES ('contact', 'phone', ?) ON DUPLICATE KEY UPDATE value = ?");
            $stmt->bind_param("ss", $phone, $phone);
            $stmt->execute();
            
            $stmt = $conn->prepare("INSERT INTO landing_content (section, field, value) VALUES ('contact', 'email', ?) ON DUPLICATE KEY UPDATE value = ?");
            $stmt->bind_param("ss", $email, $email);
            $stmt->execute();
            
            $success_message = "Contact information updated successfully!";
            break;
            
        case 'update_map':
            $map_embed = $_POST['map_embed'];
            
            $stmt = $conn->prepare("INSERT INTO landing_content (section, field, value) VALUES ('map', 'embed_url', ?) ON DUPLICATE KEY UPDATE value = ?");
            $stmt->bind_param("ss", $map_embed, $map_embed);
            $stmt->execute();
            
            $success_message = "Map updated successfully!";
            break;
    }
}

// Fetch existing data
$hero_data = [];
$contact_data = [];
$map_data = [];

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings - Landing Page Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        html, body {
            width: 100%;
            min-height: 100vh;
            overflow-x: hidden;        
        }
        
         /* Sidebar */
        .sidebar {
            width: 235px;
            position: fixed;
            background-color: #31363F;
            height: 100vh;
            padding: 20px;
            z-index: 100;
        }
        .sidebar-item {
            transition: background 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(10px);
        }

        .dropdown:hover .dropdown-menu {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }

        .animate-dropdown {
            visibility: hidden;
            opacity: 0;
            transform: translateY(-5px);
            transition: visibility 0s linear 0.3s, opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .dropdown:hover .animate-dropdown {
            visibility: visible;
            opacity: 1;
            transform: translateY(0);
            transition-delay: 0s;
        }

        .dropdown-menu {
            min-width: 180px;
        }

        .animated-title {
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .animated-title:hover {
            transform: scale(1.1);
            color: #eff319;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        .section-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .section-card:hover {
            transform: translateY(-5px);
        }

        .section-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 20px;
            margin: 0;
        }

        .section-body {
            padding: 25px;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e1e5e9;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-danger {
            border-radius: 10px;
            padding: 8px 15px;
            font-weight: 500;
        }

        .btn-success {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
        }

        .feature-item, .service-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .feature-content, .service-content {
            flex: 1;
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
        }

        .nav-tabs {
            border: none;
            margin-bottom: 25px;
        }

        .nav-tabs .nav-link {
            border: none;
            border-radius: 10px;
            margin-right: 10px;
            background: #f8f9fa;
            color: #666;
            font-weight: 600;
        }

        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .tab-content {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .preview-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 15px;
            border: 2px dashed #dee2e6;
        }

        .icon-preview {
            font-size: 2rem;
            color: #667eea;
            margin-bottom: 10px;
        }

        /* Notification Badge Styles */
        .notification-badge {
            position: absolute;
            top: 8px;
            right: 10px;
            background: linear-gradient(45deg, #ff4757, #ff3742);
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
            animation: pulse-notification 2s infinite;
            border: 2px solid #fff;
            box-shadow: 0 2px 8px rgba(255, 71, 87, 0.4);
        }

        @keyframes pulse-notification {
            0% { transform: scale(1); box-shadow: 0 2px 8px rgba(255, 71, 87, 0.4); }
            50% { transform: scale(1.1); box-shadow: 0 4px 12px rgba(255, 71, 87, 0.6); }
            100% { transform: scale(1); box-shadow: 0 2px 8px rgba(255, 71, 87, 0.4); }
        }

        /* Hide badge when count is 0 */
        .notification-badge.hidden {
            display: none;
        }

        /* Additional styles for new request indicator */
        .new-request-glow {
            box-shadow: 0 0 15px rgba(255, 71, 87, 0.3);
            border-left: 3px solid #ff4757;
        }

        .content-area {
            margin-left: 235px;
            padding: 20px;
        }

        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 20px;
            color: white;
            margin-bottom: 20px;
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
        }

        /* Active page indicator styles - grey theme */
.sidebar-item.active {
    background: linear-gradient(135deg, #6e6e6e 0%, #6e6e6e 100%);
    
    border-left: 4px solid #eff1f1; /* Accent color */
    transform: translateX(8px);
    color: #fff !important;
}       

        .sidebar-item.active::before {
            content: '';
            position: absolute;
            left: -15px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-top: 8px solid transparent;
            border-bottom: 8px solid transparent;
            border-left: 8px solid #eef1f3;
        }

        .animated-title {
            animation: pulse 2s infinite;
            font-weight: bold;
            color: #fff;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }

        .dropdown-menu {
            background-color: #2c3138;
            border: 1px solid #495057;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .dropdown-item {
            color: #fff;
            transition: all 0.3s ease;
            position: relative;
        }

        .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            transform: translateX(5px);
        }

        .dropdown-item.active {
            background: linear-gradient(135deg, #f7b44f 0%, #66eaac 100%);
            color: #fff;
            box-shadow: 0 4px 15px rgba(79, 195, 247, 0.3);
        }

        .animate-dropdown {
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse-notification {
            0% { transform: scale(1); box-shadow: 0 2px 8px rgba(255, 71, 87, 0.4); }
            50% { transform: scale(1.1); box-shadow: 0 4px 12px rgba(255, 71, 87, 0.6); }
            100% { transform: scale(1); box-shadow: 0 2px 8px rgba(255, 71, 87, 0.4); }
        }

        .new-request-glow {
            box-shadow: 0 0 15px rgba(255, 71, 87, 0.3);
            border-left: 3px solid #ff4757;
        }

        /* Content area styles */
        .content-area {
            margin-left: 235px;
            padding: 20px;
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        /* Page indicator styles for different sections */
        .page-indicator {
            position: absolute;
            right: -15px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-top: 10px solid transparent;
            border-bottom: 10px solid transparent;
            
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-item.active .page-indicator {
            opacity: 1;
        }

        /* Glow effect for active items */
        .sidebar-item.active {
            position: relative;
            overflow: visible;
        }

    </style>
</head>

<body class="bg-light">
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar text-white vh-100 p-3" style="width: 235px; background-color: #31363F;">
            <div class="mb-4" style="margin-left: 19px;">
                <h2 class="fs-4 animated-title">BSCIMS</h2>
            </div>

            <ul class="nav flex-column">
                <li class="nav-item mb-3" style="margin-top: 30px;">
                    <a href="dashboard.php" class="nav-link text-white d-flex align-items-center sidebar-item">
                        <i class="fas fa-home me-2"></i> Main Dashboard
                    </a>
                </li>

                <li class="nav-item dropdown mb-3">
                    <a href="#" class="nav-link text-white d-flex align-items-center sidebar-item dropdown-toggle">
                        <i class="fas fa-users me-2"></i> Barangay Officials
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="Barangay_official.php">View Officials</a></li>
                        <li><a class="dropdown-item" href="manage_official.html">Manage Officials</a></li>
                    </ul>
                </li>

                <li class="nav-item mb-3 dropdown">
                    <a class="nav-link dropdown-toggle text-white d-flex align-items-center sidebar-item" href="#" role="button">
                        <i class="fas fa-user-friends me-2"></i> Barangay Residents
                    </a>
                    <ul class="dropdown-menu animate-dropdown">
                        <li><a class="dropdown-item" href="Residents.html">Register Residents</a></li>
                        <li><a class="dropdown-item" href="view_residents.php">Registered Residents</a></li>
                    </ul>
                </li>

                <!-- ENHANCED CITIZEN REQUESTS WITH NOTIFICATION -->
            <li class="nav-item mb-3 dropdown">
                <a class="nav-link dropdown-toggle text-white d-flex align-items-center sidebar-item" id="citizen-requests-link">
                    <i class="fas fa-file-alt me-2"></i> Citizen Requests
                    <!-- Notification Badge -->
                    <span class="notification-badge" id="request-notification-badge">0</span>
                </a>
                <ul class="dropdown-menu animate-dropdown">
                    <li><a class="dropdown-item" href="Request_Documents.php">Request Documents</a></li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="Issued Certificate.html">
                            Certificate Issuance
                            <span class="badge bg-danger ms-2" id="pending-requests-mini-badge" style="display: none;">0</span>
                        </a>
                    </li>
                </ul>
            </li>

                <li class="nav-item mb-3">
                    <a class="nav-link text-white d-flex align-items-center sidebar-item" href="Administration.php">
                        <i class="fas fa-building me-2"></i> Administration
                    </a>
                </li>

                <li class="nav-item mb-3">
                    <a href="Announcement.html" class="nav-link text-white d-flex align-items-center sidebar-item">
                        <i class="fas fa-bullhorn me-2"></i> Announcements
                    </a>
                </li>

                <li class="nav-item mb-3">
                    <a href="list_admins.php" class="nav-link text-white d-flex align-items-center sidebar-item active" data-page="system-settings">
                        <i class="fas fa-cog me-2"></i> System Settings
                        <div class="page-indicator"></div>
                    </a>
                </li>

                <li class="nav-item mb-3">
                    <a class="nav-link text-white d-flex align-items-center sidebar-item" href="request_reports.php">
                        <i class="fas fa-chart-bar me-2"></i> Reports
                    </a>
                </li>
                <li class="nav-item mt-auto">
                    <a href="logout.php" class="nav-link text-white d-flex align-items-center sidebar-item">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h2 class="p-3 text-white fs-5" style="background-color: #222831; margin-top: -20px; margin-left: 203px; margin-right: -35px; ">
                            <img src="logo.png" alt="Logo" style="height: 30px; width: auto; margin-right: 10px;">
                            Landing Page Management
                        </h2>
                    </div>
                </div>
            </div>

            <?php if (isset($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo $success_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs" id="settingsTabs" role="tablist" style="margin-left: 240px;">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="hero-tab" data-bs-toggle="tab" data-bs-target="#hero" type="button">
                        <i class="fas fa-home me-2"></i>Hero Section
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="features-tab" data-bs-toggle="tab" data-bs-target="#features" type="button">
                        <i class="fas fa-star me-2"></i>Features
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="services-tab" data-bs-toggle="tab" data-bs-target="#services" type="button">
                        <i class="fas fa-cogs me-2"></i>Services
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button">
                        <i class="fas fa-phone me-2"></i>Contact Info
                    </button>
               
                <!-- Add this tab button after the contact tab in your navigation tabs -->
<li class="nav-item" role="presentation" >
    <button class="nav-link" id="map-tab" data-bs-toggle="tab" data-bs-target="#map" type="button">
        <i class="fas fa-map-marker-alt me-2"></i>Map Settings
    </button>
</li>
 </li>
              
                
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="settingsTabsContent" style="margin-left: 235px;">
                
                <!-- Hero Section Tab -->
                <div class="tab-pane fade show active" id="hero" role="tabpanel" >
                    <h4><i class="fas fa-home me-2 text-primary"></i>Hero Section Settings</h4>
                    <form method="POST" class="mt-4">
                        <input type="hidden" name="action" value="update_hero">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hero_title" class="form-label fw-bold">
                                        <i class="fas fa-heading me-2"></i>Hero Title
                                    </label>
                                    <input type="text" class="form-control" id="hero_title" name="hero_title" 
                                           value="<?php echo htmlspecialchars($hero_data['title'] ?? 'Welcome to Barangay San Carlos'); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="hero_description" class="form-label fw-bold">
                                        <i class="fas fa-align-left me-2"></i>Hero Description
                                    </label>
                                    <textarea class="form-control" id="hero_description" name="hero_description" rows="3"><?php echo htmlspecialchars($hero_data['description'] ?? 'Your gateway to efficient community services and transparent governance in the digital age.'); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="hero_image" class="form-label fw-bold">
                                        <i class="fas fa-image me-2"></i>Hero Image Path
                                    </label>
                                    <input type="text" class="form-control" id="hero_image" name="hero_image" 
                                           value="<?php echo htmlspecialchars($hero_data['image'] ?? 'image/bhall.jpg'); ?>"
                                           placeholder="e.g., image/bhall.jpg">
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Hero Section
                                </button>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="preview-box">
                                    <h6><i class="fas fa-eye me-2"></i>Preview</h6>
                                    <div class="bg-primary text-white p-4 rounded">
                                        <h3 id="hero_title_preview"><?php echo htmlspecialchars($hero_data['title'] ?? 'Welcome to Barangay San Carlos'); ?></h3>
                                        <p id="hero_desc_preview"><?php echo htmlspecialchars($hero_data['description'] ?? 'Your gateway to efficient community services...'); ?></p>
                                        <small class="text-light">Image: <span id="hero_image_preview"><?php echo htmlspecialchars($hero_data['image'] ?? 'image/bhall.jpg'); ?></span></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Features Tab -->
                <div class="tab-pane fade" id="features" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <h4><i class="fas fa-star me-2 text-primary"></i>Add New Feature</h4>
                            <form method="POST" class="mt-4">
                                <input type="hidden" name="action" value="add_feature">
                                
                                <div class="mb-3">
                                    <label for="feature_icon" class="form-label fw-bold">
                                        <i class="fas fa-icons me-2"></i>Icon Class (FontAwesome)
                                    </label>
                                    <input type="text" class="form-control" id="feature_icon" name="feature_icon" 
                                           placeholder="e.g., fas fa-user-plus" required>
                                    <small class="text-muted">Use FontAwesome classes like: fas fa-user-plus, fas fa-bullhorn, etc.</small>
                                </div>

                                <div class="mb-3">
                                    <label for="feature_title" class="form-label fw-bold">
                                        <i class="fas fa-heading me-2"></i>Feature Title
                                    </label>
                                    <input type="text" class="form-control" id="feature_title" name="feature_title" required>
                                </div>

                                <div class="mb-3">
                                    <label for="feature_description" class="form-label fw-bold">
                                        <i class="fas fa-align-left me-2"></i>Feature Description
                                    </label>
                                    <textarea class="form-control" id="feature_description" name="feature_description" rows="3" required></textarea>
                                </div>

                                <button type="submit" class="btn btn-success" style="margin-top: -27px;">
                                    <i class="fas fa-plus me-2"></i>Add Feature
                                </button>
                            </form>
                        </div>
                        
                        <div class="col-md-6">
                            <h4><i class="fas fa-list me-2 text-primary"></i>Existing Features</h4>
                            <div class="mt-4" style="max-height: 400px; overflow-y: auto;">
                                <?php foreach ($features as $feature): ?>
                                    <div class="feature-item">
                                        <div class="feature-content">
                                            <div class="icon-preview">
                                                <i class="<?php echo htmlspecialchars($feature['icon']); ?>"></i>
                                            </div>
                                            <h6><?php echo htmlspecialchars($feature['title']); ?></h6>
                                            <p class="small mb-0"><?php echo htmlspecialchars($feature['description']); ?></p>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-danger" onclick="deleteFeature(<?php echo $feature['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services Tab -->
                <div class="tab-pane fade" id="services" role="tabpanel">
                    <div class="row">
                        <div class="col-md-6">
                            <h4><i class="fas fa-cogs me-2 text-primary"></i>Add New Service</h4>
                            <form method="POST" class="mt-4">
                                <input type="hidden" name="action" value="add_service">
                                
                                <div class="mb-3">
                                    <label for="service_icon" class="form-label fw-bold">
                                        <i class="fas fa-icons me-2"></i>Icon Class (FontAwesome)
                                    </label>
                                    <input type="text" class="form-control" id="service_icon" name="service_icon" 
                                           placeholder="e.g., fas fa-certificate" required>
                                </div>

                                <div class="mb-3">
                                    <label for="service_title" class="form-label fw-bold">
                                        <i class="fas fa-heading me-2"></i>Service Title
                                    </label>
                                    <input type="text" class="form-control" id="service_title" name="service_title" required>
                                </div>

                                <div class="mb-3">
                                    <label for="service_description" class="form-label fw-bold">
                                        <i class="fas fa-align-left me-2"></i>Service Description
                                    </label>
                                    <textarea class="form-control" id="service_description" name="service_description" rows="3" required></textarea>
                                </div>

                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus me-2"></i>Add Service
                                </button>
                            </form>
                        </div>
                        
                        <div class="col-md-6">
                            <h4><i class="fas fa-list me-2 text-primary"></i>Existing Services</h4>
                            <div class="mt-4" style="max-height: 400px; overflow-y: auto;">
                                <?php foreach ($services as $service): ?>
                                    <div class="service-item">
                                        <div class="service-content">
                                            <div class="icon-preview">
                                                <i class="<?php echo htmlspecialchars($service['icon']); ?>"></i>
                                            </div>
                                            <h6><?php echo htmlspecialchars($service['title']); ?></h6>
                                            <p class="small mb-0"><?php echo htmlspecialchars($service['description']); ?></p>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-danger" onclick="deleteService(<?php echo $service['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Tab -->
                <div class="tab-pane fade" id="contact" role="tabpanel">
                    <h4><i class="fas fa-phone me-2 text-primary"></i>Contact Information Settings</h4>
                    <form method="POST" class="mt-4">
                        <input type="hidden" name="action" value="update_contact">
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="address" class="form-label fw-bold">
                                        <i class="fas fa-map-marker-alt me-2"></i>Address
                                    </label>
                                    <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($contact_data['address'] ?? 'Barangay San Carlos\nCity of Valencia\nProvince of Bukidnon'); ?></textarea>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="phone" class="form-label fw-bold">
                                        <i class="fas fa-phone me-2"></i>Phone Number
                                    </label>
                                    <input type="text" class="form-control" id="phone" name="phone" 
                                           value="<?php echo htmlspecialchars($contact_data['phone'] ?? '+63 123 456 7890'); ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-bold">
                                        <i class="fas fa-envelope me-2"></i>Email Address
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo htmlspecialchars($contact_data['email'] ?? 'barangaysancarlosofficial@gmail.com'); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="margin-top: -20px;">
                            <i class="fas fa-save me-2"></i>Update Contact Information
                        </button>
                    </form>
                </div>

               <!-- Add this tab content after the contact tab content -->
<div class="tab-pane fade" id="map" role="tabpanel" style="margin-top: -20px;">
    <h4><i class="fas fa-map-marker-alt me-2 text-primary"></i>Map Settings</h4>
    <form method="POST" class="mt-4">
        <input type="hidden" name="action" value="update_map">
        
        <div class="mb-3">
            <label for="map_embed" class="form-label fw-bold">
                <i class="fas fa-globe me-2"></i>Google Maps Embed URL
            </label>
            <textarea class="form-control" id="map_embed" name="map_embed" rows="4" placeholder="Paste your Google Maps embed URL here..."><?php echo htmlspecialchars($map_data['embed_url'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d836.0647285893109!2d125.07264386862515!3d7.960605846843486!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32ff1b2e8cbaf339%3A0xe86838c0b3fb745c!2sBarangay%20Hall%20of%20Barangay%20San%20Carlos%2C%20Valencia%20City%2C%20Bukidnon!5e1!3m2!1sfil!2sph!4v1740972448740!5m2!1sfil!2sph'); ?></textarea>
            <small class="text-muted">
                <strong>How to get Google Maps embed URL:</strong><br>
                1. Go to Google Maps and search for your location<br>
                2. Click "Share" → "Embed a map"<br>
                3. Copy the URL from the src attribute of the iframe code
            </small>
        </div>
        
        <div class="preview-box">
            <h6><i class="fas fa-eye me-2"></i>Map Preview</h6>
            <div style="height: 200px; overflow: hidden; border-radius: 8px;">
                <iframe 
                    id="map_preview"
                    src="<?php echo htmlspecialchars($map_data['embed_url'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d836.0647285893109!2d125.07264386862515!3d7.960605846843486!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32ff1b2e8cbaf339%3A0xe86838c0b3fb745c!2sBarangay%20Hall%20of%20Barangay%20San%20Carlos%2C%20Valencia%20City%2C%20Bukidnon!5e1!3m2!1sfil!2sph!4v1740972448740!5m2!1sfil!2sph'); ?>"
                    width="100%" 
                    height="200" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary mt-3">
            <i class="fas fa-save me-2"></i>Update Map Settings
        </button>
    </form>
</div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Real-time preview updates for hero section
        document.getElementById('hero_title').addEventListener('input', function() {
            document.getElementById('hero_title_preview').textContent = this.value;
        });
        
        document.getElementById('hero_description').addEventListener('input', function() {
            document.getElementById('hero_desc_preview').textContent = this.value;
        });
        
        document.getElementById('hero_image').addEventListener('input', function() {
            document.getElementById('hero_image_preview').textContent = this.value;
        });

        // Delete feature function
        function deleteFeature(id) {
            if (confirm('Are you sure you want to delete this feature?')) {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'delete_feature';
                
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'feature_id';
                idInput.value = id;
                
                form.appendChild(actionInput);
                form.appendChild(idInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Delete service function
        function deleteService(id) {
            if (confirm('Are you sure you want to delete this service?')) {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'delete_service';
                
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'service_id';
                idInput.value = id;
                
                form.appendChild(actionInput);
                form.appendChild(idInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Icon preview for features
        document.getElementById('feature_icon').addEventListener('input', function() {
            const iconClass = this.value;
            const previewContainer = document.querySelector('#features .preview-box');
            
            if (!previewContainer) {
                const newPreview = document.createElement('div');
                newPreview.className = 'preview-box mt-3';
                newPreview.innerHTML = `
                    <h6><i class="fas fa-eye me-2"></i>Icon Preview</h6>
                    <div class="icon-preview" id="feature_icon_preview">
                        <i class="${iconClass}"></i>
                    </div>
                `;
                document.querySelector('#feature_icon').parentNode.appendChild(newPreview);
            } else {
                const iconElement = previewContainer.querySelector('i');
                if (iconElement) {
                    iconElement.className = iconClass;
                }
            }
        });

        // Icon preview for services
        document.getElementById('service_icon').addEventListener('input', function() {
            const iconClass = this.value;
            const previewContainer = document.querySelector('#services .preview-box');
            
            if (!previewContainer) {
                const newPreview = document.createElement('div');
                newPreview.className = 'preview-box mt-3';
                newPreview.innerHTML = `
                    <h6><i class="fas fa-eye me-2"></i>Icon Preview</h6>
                    <div class="icon-preview" id="service_icon_preview">
                        <i class="${iconClass}"></i>
                    </div>
                `;
                document.querySelector('#service_icon').parentNode.appendChild(newPreview);
            } else {
                const iconElement = previewContainer.querySelector('i');
                if (iconElement) {
                    iconElement.className = iconClass;
                }
            }
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Smooth scroll to active tab content
        document.querySelectorAll('[data-bs-toggle="tab"]').forEach(function(tab) {
            tab.addEventListener('shown.bs.tab', function() {
                const targetPane = document.querySelector(this.getAttribute('data-bs-target'));
                if (targetPane) {
                    targetPane.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>

<script>
        // Function to fetch and update notification counts
        function updateNotificationCounts() {
            fetch('get_request_counts.php')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const pendingCount = data.counts.pending || 0;
                        const processingCount = data.counts.processing || 0;
                        const completedToday = data.counts.completed_today || 0;
                        const totalMonth = data.counts.total_month || 0;
                        
                        // Update main notification badge
                        const badge = document.getElementById('request-notification-badge');
                        const miniBadge = document.getElementById('pending-requests-mini-badge');
                        const citizenRequestsLink = document.getElementById('citizen-requests-link');
                        
                        if (pendingCount > 0) {
                            badge.textContent = pendingCount;
                            badge.classList.remove('hidden');
                            miniBadge.textContent = pendingCount;
                            miniBadge.style.display = 'inline-block';
                            citizenRequestsLink.classList.add('new-request-glow');
                        } else {
                            badge.classList.add('hidden');
                            miniBadge.style.display = 'none';
                            citizenRequestsLink.classList.remove('new-request-glow');
                        }
                        
                        // Update stats cards
                        document.getElementById('pending-count').textContent = pendingCount;
                        document.getElementById('processing-count').textContent = processingCount;
                        document.getElementById('completed-today').textContent = completedToday;
                        document.getElementById('total-month').textContent = totalMonth;
                    }
                })
                .catch(error => {
                    console.error('Error fetching notification counts:', error);
                });
        }

        // Function to load recent requests
        function loadRecentRequests() {
            fetch('get_recent_request.php')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const tbody = document.getElementById('requests-tbody');
                        tbody.innerHTML = '';
                        
                        data.requests.forEach(request => {
                            const row = document.createElement('tr');
                            const statusClass = request.status === 'Pending' ? 'bg-warning' : 
                                              request.status === 'Processing' ? 'bg-info' : 'bg-success';
                            
                            row.innerHTML = `
                                <td>${request.request_id}</td>
                                <td>${request.resident_name}</td>
                                <td>${request.certificate_type}</td>
                                <td>${request.purpose}</td>
                                <td><span class="badge ${statusClass}">${request.status}</span></td>
                                <td>${new Date(request.request_date).toLocaleDateString()}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="viewRequest('${request.request_id}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    ${request.status === 'Pending' ? 
                                        `<button class="btn btn-sm btn-success ms-1" onclick="processRequest('${request.request_id}')">
                                            <i class="fas fa-play"></i>
                                        </button>` : ''}
                                </td>
                            `;
                            tbody.appendChild(row);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading recent requests:', error);
                });
        }

        // Function to manually refresh notifications
        function refreshNotifications() {
            updateNotificationCounts();
            loadRecentRequests();
        }

        // Function to view request details
        function viewRequest(requestId) {
            // Implement view request functionality
            alert('Viewing request: ' + requestId);
        }

        // Function to process request
        function processRequest(requestId) {
            if (confirm('Start processing this request?')) {
                fetch('update_requests_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `request_id=${requestId}&status=Processing`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        refreshNotifications();
                        alert('Request status updated to Processing');
                    } else {
                        alert('Error updating request: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating request status');
                });
            }
        }

        // Initialize notifications when page loads
        document.addEventListener('DOMContentLoaded', function() {
            updateNotificationCounts();
            loadRecentRequests();
            
            // Update notifications every 30 seconds
            setInterval(updateNotificationCounts, 30000);
        });

        // Handle dropdown toggle
        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = document.querySelectorAll('.dropdown-toggle');
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', function(e) {
                    e.preventDefault();
                    const dropdownMenu = this.nextElementSibling;
                    dropdownMenu.classList.toggle('show');
                });
            });
        });
    </script>

     <script>
        // Function to set active page indicator
        function setActivePage(pageName) {
            // Remove active class from all sidebar items
            document.querySelectorAll('.sidebar-item').forEach(item => {
                item.classList.remove('active');
            });
            
            document.querySelectorAll('.dropdown-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Add active class to current page
            const activeItem = document.querySelector(`[data-page="${pageName}"]`);
            if (activeItem) {
                activeItem.classList.add('active');
                
                // If it's a dropdown item, also highlight the parent
                if (activeItem.classList.contains('dropdown-item')) {
                    const parentDropdown = activeItem.closest('.dropdown').querySelector('.dropdown-toggle');
                    if (parentDropdown) {
                        parentDropdown.classList.add('active');
                    }
                }
            }
        }

        // Auto-detect current page based on URL
        function detectCurrentPage() {
            const currentPath = window.location.pathname;
            const fileName = currentPath.split('/').pop().split('.')[0];
            
            // Map file names to page identifiers
            const pageMap = {
                'dashboard': 'dashboard',
                'Barangay_official': 'view-officials',
                'manage_official': 'manage-officials',
                'Residents': 'register-residents',
                'view_residents': 'view-residents',
                'Request_Documents': 'request-documents',
                'Issued Certificate': 'certificate-issuance',
                'Administration': 'administration',
                'Announcement': 'announcements',
                'list_admins': 'system-settings',
                'request_reports': 'reports'
            };
            
            const currentPage = pageMap[fileName];
            if (currentPage) {
                setActivePage(currentPage);
            }
        }

        

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            detectCurrentPage();
            updateNotificationCounts();
            
            // Update notifications every 30 seconds
            setInterval(updateNotificationCounts, 30000);
        });

        // Handle dropdown toggle
        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = document.querySelectorAll('.dropdown-toggle');
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', function(e) {
                    e.preventDefault();
                    const dropdownMenu = this.nextElementSibling;
                    dropdownMenu.classList.toggle('show');
                });
            });
        });

        // Call this function after a new certificate request is submitted
        function triggerNotificationUpdate() {
            updateNotificationCounts();
        }
    </script>
</body>
</html>

<?php
// Handle delete operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'delete_feature' && isset($_POST['feature_id'])) {
        $feature_id = $_POST['feature_id'];
        $stmt = $conn->prepare("DELETE FROM features WHERE id = ?");
        $stmt->bind_param("i", $feature_id);
        if ($stmt->execute()) {
            echo "<script>window.location.href = window.location.href;</script>";
        }
    }
    
    if ($action == 'delete_service' && isset($_POST['service_id'])) {
        $service_id = $_POST['service_id'];
        $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
        $stmt->bind_param("i", $service_id);
        if ($stmt->execute()) {
            echo "<script>window.location.href = window.location.href;</script>";
        }
    }
}



// Close database connection
$conn->close();


?>
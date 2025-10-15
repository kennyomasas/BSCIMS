<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "barangay";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle AJAX requests for adding announcements
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'add_announcement') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : NULL;

    // Validate required fields
    if (empty($title) || empty($description) || empty($start_date)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO announcements_events (title, description, start_date, end_date, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $title, $description, $start_date, $end_date);

    if ($stmt->execute()) {
        $insertId = $conn->insert_id;
        echo json_encode([
            'success' => true, 
            'message' => 'Announcement added successfully!',
            'id' => $insertId
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding announcement: ' . $stmt->error]);
    }
    
    $stmt->close();
    $conn->close();
    exit;
}

// Handle DELETE requests
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'delete_announcement') {
    $id = $_POST['id'] ?? '';
    
    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'Invalid announcement ID']);
        exit;
    }
    
    $stmt = $conn->prepare("DELETE FROM announcements_events WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Announcement deleted successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting announcement']);
    }
    
    $stmt->close();
    $conn->close();
    exit;
}

session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: Personnel_login.html');
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: Personnel_login.html');
    exit();
}


// Fetch current user's data
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, first_name, last_name, email, position, role FROM admins WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$stmt->close();


// Get user's first name (use first_name if available, otherwise username)
$display_name = isset($_SESSION['first_name']) && !empty($_SESSION['first_name']) 
    ? $_SESSION['first_name'] 
    : $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcement Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            --card-shadow: 0 10px 40px rgba(0,0,0,0.1);
            --hover-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Modern Top Navigation */
        .top-nav {
            background: var(--primary-gradient);
            padding: 1rem 0;
            box-shadow: var(--card-shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
        }

        .nav-brand:hover {
            color: rgba(255,255,255,0.9);
        }

        .nav-brand img {
            width: 40px;
            height: 40px;
            margin-right: 12px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .nav-title {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .nav-subtitle {
            font-size: 0.85rem;
            opacity: 0.9;
            font-weight: 300;
        }

        /* Modern Tab Navigation */
        .tab-navigation {
            background: white;
            border-radius: 15px;
            padding: 8px;
            margin: 2rem 0;
            box-shadow: var(--card-shadow);
            display: flex;
            gap: 8px;
            overflow-x: auto;
        }

        .nav-tab {
            padding: 12px 24px;
            border-radius: 10px;
            border: none;
            background: transparent;
            color: #64748b;
            font-weight: 500;
            transition: all 0.3s ease;
            white-space: nowrap;
            position: relative;
            overflow: hidden;
            text-decoration: none;
        }

        .nav-tab.active {
            background: var(--primary-gradient);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .nav-tab:not(.active):hover {
            background: #f1f5f9;
            color: #475569;
            transform: translateY(-1px);
        }

        .nav-tab i {
            margin-right: 8px;
        }

        /* Page Header */
        .page-header {
            text-align: center;
            margin: 2rem 0 3rem 0;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 1.1rem;
            font-weight: 400;
        }

        /* Add Announcement Section */
        .add-announcement-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
        }

        .add-announcement-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--success-gradient);
        }

        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
            outline: none;
        }

        .btn-gradient {
            background: var(--primary-gradient);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-danger-gradient {
            background: var(--secondary-gradient);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .btn-danger-gradient:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(240, 147, 251, 0.3);
            color: white;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary-gradient);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: #64748b;
            font-weight: 500;
            font-size: 0.9rem;
        }

        /* Announcements Grid */
        .announcements-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .announcement-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .announcement-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--secondary-gradient);
        }

        .announcement-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .announcement-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.75rem;
        }

        .announcement-description {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .announcement-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #f1f5f9;
        }

        .date-badge {
            background: var(--success-gradient);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-active {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }

        .status-upcoming {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .status-expired {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        /* Alert Messages */
        .alert-custom {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .alert-success-custom {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.05));
            color: #22c55e;
        }

        .alert-danger-custom {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
            color: #ef4444;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #64748b;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .add-announcement-section {
                padding: 1.5rem;
            }
            
            .announcements-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(102, 126, 234, 0.3);
            border-radius: 50%;
            border-top-color: #667eea;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

<!-- Modern Top Navigation -->
<nav class="top-nav bg-dark py-2">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 d-flex align-items-center">
                <a href="#" class="nav-brand d-flex align-items-center text-white text-decoration-none">
                    <img src="logo.png" alt="Logo" style="height: 40px;" class="me-2">
                    <div>
                        <div class="nav-title fw-bold">Barangay San Carlos</div>
                        <div class="nav-subtitle small">City of Valencia, Bukidnon</div>
                    </div>
                </a>
            </div>

           <!-- Updated Navbar with Profile Modal Trigger -->
<div class="col-md-6 text-end">
    <!-- Dropdown for Profile -->
    <div class="dropdown d-inline-block">
        <a class="text-white dropdown-toggle text-decoration-none" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user-circle me-2"></i>Welcome, <?php echo htmlspecialchars($display_name); ?>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                <i class="fas fa-user me-2"></i>Profile
            </a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="Personnel_logout.php">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
            </a></li>
        </ul>
    </div>
</div>
        </div>
    </div>
</nav>

<div class="container">
    <!-- Modern Tab Navigation -->
    <div class="tab-navigation">
        <a href="personnel_residence.php" class="nav-tab">
            <i class="fas fa-users"></i>
            Residents Directory
        </a>
        <a href="Personnel_official.php" class="nav-tab">
            <i class="fas fa-user-tie"></i>
            Barangay Officials
        </a>
        <a href="Personnel_Anno.php" class="nav-tab active">
            <i class="fas fa-bullhorn"></i>
            Barangay Announcements
        </a>
        <a href="Personnel_request_history.php" class="nav-tab">
            <i class="fas fa-file-alt"></i>
            Certificate Request History
        </a>
    </div>

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Announcement Management</h1>
        <p class="page-subtitle">Create and manage barangay announcements and events</p>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number" id="totalAnnouncements">
                <?php
                $countSql = "SELECT COUNT(*) as total FROM announcements_events";
                $countResult = $conn->query($countSql);
                $totalCount = $countResult->fetch_assoc()['total'];
                echo $totalCount;
                ?>
            </div>
            <div class="stat-label">Total Announcements</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="activeAnnouncements">
                <?php
                $activeSql = "SELECT COUNT(*) as active FROM announcements_events WHERE start_date <= CURDATE() AND (end_date IS NULL OR end_date >= CURDATE())";
                $activeResult = $conn->query($activeSql);
                $activeCount = $activeResult->fetch_assoc()['active'];
                echo $activeCount;
                ?>
            </div>
            <div class="stat-label">Active Announcements</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="upcomingAnnouncements">
                <?php
                $upcomingSql = "SELECT COUNT(*) as upcoming FROM announcements_events WHERE start_date > CURDATE()";
                $upcomingResult = $conn->query($upcomingSql);
                $upcomingCount = $upcomingResult->fetch_assoc()['upcoming'];
                echo $upcomingCount;
                ?>
            </div>
            <div class="stat-label">Upcoming Announcements</div>
        </div>
        <div class="stat-card">
            <div class="stat-number" id="expiredAnnouncements">
                <?php
                $expiredSql = "SELECT COUNT(*) as expired FROM announcements_events WHERE end_date IS NOT NULL AND end_date < CURDATE()";
                $expiredResult = $conn->query($expiredSql);
                $expiredCount = $expiredResult->fetch_assoc()['expired'];
                echo $expiredCount;
                ?>
            </div>
            <div class="stat-label">Expired Announcements</div>
        </div>
    </div>

    <!-- Alert Messages -->
    <div id="alertContainer"></div>

    <!-- Add Announcement Section -->
    <div class="add-announcement-section">
        <h4 class="mb-4"><i class="fas fa-plus-circle me-2"></i>Add New Announcement</h4>
        <form id="announcementForm">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date (Optional)</label>
                    <input type="date" class="form-control" id="end_date" name="end_date">
                </div>
                <div class="col-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-gradient">
                        <i class="fas fa-save me-2"></i>Add Announcement
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Announcements Display -->
    <div class="announcements-grid" id="announcementsGrid">
        <?php
        $sql = "SELECT * FROM announcements_events ORDER BY created_at DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $currentDate = date('Y-m-d');
                $startDate = $row['start_date'];
                $endDate = $row['end_date'];
                
                // Determine status
                $status = 'upcoming';
                $statusClass = 'status-upcoming';
                $statusText = 'Upcoming';
                
                if ($startDate <= $currentDate) {
                    if ($endDate === null || $endDate >= $currentDate) {
                        $status = 'active';
                        $statusClass = 'status-active';
                        $statusText = 'Active';
                    } else {
                        $status = 'expired';
                        $statusClass = 'status-expired';
                        $statusText = 'Expired';
                    }
                }
                
                echo "<div class='announcement-card' data-id='" . $row['id'] . "'>
                    <div class='announcement-title'>" . htmlspecialchars($row['title']) . "</div>
                    <div class='announcement-description'>" . htmlspecialchars($row['description']) . "</div>
                    <div class='announcement-meta'>
                        <div>
                            <div class='date-badge mb-1'>
                                <i class='fas fa-calendar me-1'></i>
                                " . date('M d, Y', strtotime($row['start_date'])) . 
                                ($row['end_date'] ? ' - ' . date('M d, Y', strtotime($row['end_date'])) : '') . "
                            </div>
                            <div class='date-badge {$statusClass}'>{$statusText}</div>
                        </div>
                        <button class='btn btn-danger-gradient delete-btn' data-id='" . $row['id'] . "'>
                            <i class='fas fa-trash'></i>
                        </button>
                    </div>
                </div>";
            }
        } else {
            echo "<div class='empty-state col-12'>
                    <i class='fas fa-bullhorn'></i>
                    <h5>No announcements found</h5>
                    <p>Start by adding your first announcement above.</p>
                  </div>";
        }
        ?>
    </div>
</div>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="profileModalLabel">
                    <i class="fas fa-user-circle me-2"></i>My Profile
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Profile View Mode -->
                <div id="profileView">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="profile-avatar">
                                <i class="fas fa-user-circle fa-8x text-primary"></i>
                            </div>
                            <h5 class="mt-3"><?php echo htmlspecialchars($user_data['first_name'] . ' ' . $user_data['last_name']); ?></h5>
                            <p class="text-muted"><?php echo htmlspecialchars($user_data['position']); ?></p>
                        </div>
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Personal Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Username:</strong></div>
                                        <div class="col-sm-8"><?php echo htmlspecialchars($user_data['username']); ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>First Name:</strong></div>
                                        <div class="col-sm-8"><?php echo htmlspecialchars($user_data['first_name']); ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Last Name:</strong></div>
                                        <div class="col-sm-8"><?php echo htmlspecialchars($user_data['last_name']); ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Email:</strong></div>
                                        <div class="col-sm-8"><?php echo htmlspecialchars($user_data['email']); ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Position:</strong></div>
                                        <div class="col-sm-8"><?php echo htmlspecialchars($user_data['position']); ?></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Role:</strong></div>
                                        <div class="col-sm-8">
                                            <span class="badge bg-info text-dark"><?php echo htmlspecialchars($user_data['role']); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Edit Mode (Initially Hidden) -->
                <div id="profileEdit" style="display: none;">
                    <form id="editProfileForm" method="POST" action="update_profile.php">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editFirstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="editFirstName" name="first_name" 
                                           value="<?php echo htmlspecialchars($user_data['first_name']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="editLastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="editLastName" name="last_name" 
                                           value="<?php echo htmlspecialchars($user_data['last_name']); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email" 
                                   value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPosition" class="form-label">Position</label>
                            <input type="text" class="form-control" id="editPosition" name="position" 
                                   value="<?php echo htmlspecialchars($user_data['position']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPassword" class="form-label">New Password (leave blank to keep current)</label>
                            <input type="password" class="form-control" id="editPassword" name="password" 
                                   placeholder="Enter new password">
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword" 
                                   placeholder="Confirm new password">
                        </div>
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <input type="hidden" name="current_username" value="<?php echo htmlspecialchars($user_data['username']); ?>">
                        <input type="hidden" name="current_email" value="<?php echo htmlspecialchars($user_data['email']); ?>">
                        <input type="hidden" name="current_position" value="<?php echo htmlspecialchars($user_data['position']); ?>">
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <div id="viewModeButtons">
                    <button type="button" class="btn btn-primary" onclick="toggleEditMode()">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                <div id="editModeButtons" style="display: none;">
                    <button type="button" class="btn btn-success" onclick="saveProfile()">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="cancelEdit()">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEditMode() {
    document.getElementById('profileView').style.display = 'none';
    document.getElementById('profileEdit').style.display = 'block';
    document.getElementById('viewModeButtons').style.display = 'none';
    document.getElementById('editModeButtons').style.display = 'block';
}

function cancelEdit() {
    document.getElementById('profileView').style.display = 'block';
    document.getElementById('profileEdit').style.display = 'none';
    document.getElementById('viewModeButtons').style.display = 'block';
    document.getElementById('editModeButtons').style.display = 'none';
    
    // Reset form
    document.getElementById('editProfileForm').reset();
    // Restore original values
    location.reload();
}

function saveProfile() {
    const password = document.getElementById('editPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    // Check if passwords match (if password is being changed)
    if (password && password !== confirmPassword) {
        alert('Passwords do not match!');
        return;
    }
    
    // Submit the form
    document.getElementById('editProfileForm').submit();
}

// Close modal and refresh page when saved successfully
<?php if (isset($_GET['profile_updated']) && $_GET['profile_updated'] == 'success'): ?>
    alert('Profile updated successfully!');
    $('#profileModal').modal('hide');
    location.reload();
<?php endif; ?>
</script>

<!-- Enhanced JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Add announcement form handler
    document.getElementById('announcementForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'add_announcement');
        
        fetch('Personnel_Anno.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                this.reset();
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showAlert('danger', data.message);
            }
        })
        .catch(error => {
            showAlert('danger', 'An error occurred. Please try again.');
            console.error('Error:', error);
        });
    });

    // Delete announcement handler
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-btn')) {
            const id = e.target.closest('.delete-btn').dataset.id;
            
            if (confirm('Are you sure you want to delete this announcement?')) {
                const formData = new FormData();
                formData.append('action', 'delete_announcement');
                formData.append('id', id);
                
                fetch('Personnel_Anno.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showAlert('danger', data.message);
                    }
                })
                .catch(error => {
                    showAlert('danger', 'An error occurred. Please try again.');
                    console.error('Error:', error);
                });
            }
        }
    });

    // Show alert function
    function showAlert(type, message) {
        const alertContainer = document.getElementById('alertContainer');
        const alertClass = type === 'success' ? 'alert-success-custom' : 'alert-danger-custom';
        const iconClass = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
        
        const alertHTML = `
            <div class="alert alert-custom ${alertClass} alert-dismissible fade show" role="alert">
                <i class="${iconClass} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        alertContainer.innerHTML = alertHTML;
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            const alert = alertContainer.querySelector('.alert');
            if (alert) {
                alert.classList.remove('show');
                setTimeout(() => {
                    alert.remove();
                }, 150);
            }
        }, 5000);
    }

    // Set minimum date to today for date inputs
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('start_date').setAttribute('min', today);
        
        // Update end_date minimum when start_date changes
        document.getElementById('start_date').addEventListener('change', function() {
            document.getElementById('end_date').setAttribute('min', this.value);
        });
        
        // Add entrance animations
        const cards = document.querySelectorAll('.announcement-card, .add-announcement-section, .stat-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>

</body>
</html>

<?php $conn->close(); ?>
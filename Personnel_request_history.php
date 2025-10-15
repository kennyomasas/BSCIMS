<?php
// Personnel_request_history.php
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

// Get available certificate types for filter
$types_query = "SELECT DISTINCT certificate_type FROM certificate_requests ORDER BY certificate_type";
$types_result = $conn->query($types_query);
$certificate_types = [];
while ($type_row = $types_result->fetch_assoc()) {
    $certificate_types[] = $type_row['certificate_type'];
}

// Get available statuses for filter
$status_query = "SELECT DISTINCT status FROM certificate_requests ORDER BY status";
$status_result = $conn->query($status_query);
$statuses = [];
while ($status_row = $status_result->fetch_assoc()) {
    $statuses[] = $status_row['status'];
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
    <title>Certificate Request History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --request-gradient: linear-gradient(135deg, rgb(69, 150, 37) 0%,rgb(69, 150, 37) 100%);
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
            background: var(--request-gradient);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(252, 182, 159, 0.3);
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
            background: var(--request-gradient);
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

        /* Search and Filter Section */
        .search-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
        }

        .search-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--request-gradient);
        }

        .search-input, .filter-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .search-input:focus, .filter-select:focus {
            border-color: #fcb69f;
            box-shadow: 0 0 0 3px rgba(252, 182, 159, 0.1);
            background: white;
            outline: none;
        }

        .search-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
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
            background: var(--request-gradient);
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

        /* Modern Data Table */
        .data-table-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            position: relative;
        }

        .data-table-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--request-gradient);
        }

        .table {
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            background: #f8fafc;
            border: none;
            padding: 1.25rem 1rem;
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f1f5f9;
        }

        .table tbody tr:hover {
            background: linear-gradient(90deg, rgba(252, 182, 159, 0.05), rgba(255, 236, 210, 0.05));
            transform: scale(1.01);
        }

        .table tbody td {
            padding: 1.25rem 1rem;
            border: none;
            vertical-align: middle;
        }

        .request-id {
            font-weight: 600;
            color: #1e293b;
            font-family: 'Courier New', monospace;
        }

        .resident-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .certificate-badge {
            background: var(--request-gradient);
            color: white;
            padding: 0.4rem 0.9rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            display: inline-block;
        }

        .purpose-text {
            color: #64748b;
            font-size: 0.9rem;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .status-badge {
            padding: 0.4rem 0.9rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-pending {
            background: rgba(251, 191, 36, 0.1);
            color: #f59e0b;
        }

        .status-approved {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }

        .status-completed {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .status-rejected {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .date-text {
            color: #64748b;
            font-size: 0.85rem;
        }

        .signatory-badge {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .notes-text {
            color: #64748b;
            font-size: 0.85rem;
            font-style: italic;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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
            
            .search-section {
                padding: 1.5rem;
            }
            
            .table-responsive {
                border-radius: 15px;
            }
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
            <a href="Personnel_Anno.php" class="nav-tab">
                <i class="fas fa-bullhorn"></i>
                Barangay Announcement
            </a>
            <a href="Personnel_request_history.php" class="nav-tab active">
                <i class="fas fa-file-alt"></i>
                Certificate Request History
            </a>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Certificate Request History</h1>
            <p class="page-subtitle">View all certificate requests submitted by residents</p>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number" id="totalRequests">
                    <?php
                    $countSql = "SELECT COUNT(*) as total FROM certificate_requests";
                    $countResult = $conn->query($countSql);
                    $totalCount = $countResult->fetch_assoc()['total'];
                    echo $totalCount;
                    ?>
                </div>
                <div class="stat-label">Total Requests</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="pendingRequests">
                    <?php
                    $pendingSql = "SELECT COUNT(*) as pending FROM certificate_requests WHERE status = 'pending'";
                    $pendingResult = $conn->query($pendingSql);
                    $pendingCount = $pendingResult->fetch_assoc()['pending'];
                    echo $pendingCount;
                    ?>
                </div>
                <div class="stat-label">Pending Requests</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="completedRequests">
                    <?php
                    $completedSql = "SELECT COUNT(*) as completed FROM certificate_requests WHERE status = 'completed'";
                    $completedResult = $conn->query($completedSql);
                    $completedCount = $completedResult->fetch_assoc()['completed'];
                    echo $completedCount;
                    ?>
                </div>
                <div class="stat-label">Completed Requests</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="certificateTypes">
                    <?php
                    $typesSql = "SELECT COUNT(DISTINCT certificate_type) as types FROM certificate_requests";
                    $typesResult = $conn->query($typesSql);
                    $typesCount = $typesResult->fetch_assoc()['types'];
                    echo $typesCount;
                    ?>
                </div>
                <div class="stat-label">Certificate Types</div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-section">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="position-relative">
                        <input type="text" id="searchInput" class="form-control search-input" placeholder="Search by name or request ID...">
                        <i class=""></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="certificateFilter" class="form-select filter-select">
                        <option value="">All Certificate Types</option>
                        <?php foreach ($certificate_types as $type): ?>
                            <option value="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="statusFilter" class="form-select filter-select">
                        <option value="">All Status</option>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?php echo htmlspecialchars($status); ?>"><?php echo ucfirst(htmlspecialchars($status)); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" id="dateFilter" class="form-control filter-select" title="Filter by request date">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-primary w-100" onclick="clearFilters()">
                        <i class="fas fa-refresh me-2"></i>Clear
                    </button>
                </div>
            </div>
        </div>

        <!-- Modern Data Table -->
        <div class="data-table-container">
            <div class="table-responsive">
                <table class="table" id="requestsTable">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag me-2"></i>Request_ID</th>
                            <th><i class="fas fa-user me-2"></i>Resident_Name</th>
                            <th><i class="fas fa-certificate me-2"></i>Certificate_Type</th>
                            <th><i class="fas fa-clipboard-list me-2"></i>Purpose</th>
                            <th><i class="fas fa-user-tie me-2"></i>Signatory</th>
                            <th><i class="fas fa-flag me-2"></i>Status</th>
                            <th><i class="fas fa-calendar me-2"></i>Request_Date</th>
                            <th><i class="fas fa-calendar-check me-2"></i>Processed_Date</th>
                            <th><i class="fas fa-sticky-note me-2"></i>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = "SELECT * FROM certificate_requests ORDER BY request_date DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $statusClass = 'status-' . strtolower($row['status']);
                            $requestDate = date('M d, Y', strtotime($row['request_date']));
                            $processedDate = $row['processed_date'] ? date('M d, Y', strtotime($row['processed_date'])) : 'Not processed';
                            
                            echo "<tr data-certificate='" . htmlspecialchars($row['certificate_type']) . "' 
                                      data-status='" . htmlspecialchars($row['status']) . "'
                                      data-date='" . htmlspecialchars($row['request_date']) . "'>
                                <td>
                                    <div class='request-id'>#" . htmlspecialchars($row['request_id']) . "</div>
                                </td>
                                <td>
                                    <div class='resident-name'>" . htmlspecialchars($row['resident_name']) . "</div>
                                </td>
                                <td>
                                    <span class='certificate-badge'>" . htmlspecialchars($row['certificate_type']) . "</span>
                                </td>
                                <td>
                                    <div class='purpose-text' title='" . htmlspecialchars($row['purpose']) . "'>" . htmlspecialchars($row['purpose']) . "</div>
                                </td>
                                <td>
                                    <span class='signatory-badge'>" . htmlspecialchars($row['signatory']) . "</span>
                                </td>
                                <td>
                                    <span class='status-badge $statusClass'>" . ucfirst(htmlspecialchars($row['status'])) . "</span>
                                </td>
                                <td>
                                    <div class='date-text'>$requestDate</div>
                                </td>
                                <td>
                                    <div class='date-text'>$processedDate</div>
                                </td>
                                <td>
                                    <div class='notes-text' title='" . htmlspecialchars($row['notes']) . "'>" . 
                                    (empty($row['notes']) ? 'No notes' : htmlspecialchars($row['notes'])) . "</div>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9' class='empty-state'>
                                <i class='fas fa-inbox'></i>
                                <h5>No certificate requests found</h5>
                                <p>Certificate requests will appear here once residents start submitting them.</p>
                              </td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
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
    // Enhanced filtering functionality with persistence
    const searchInput = document.getElementById('searchInput');
    const certificateFilter = document.getElementById('certificateFilter');
    const statusFilter = document.getElementById('statusFilter');
    const dateFilter = document.getElementById('dateFilter');
    const table = document.getElementById('requestsTable').getElementsByTagName('tbody')[0];
    const rows = table.getElementsByTagName('tr');

    // URL parameter management
    function getUrlParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    function setUrlParameter(name, value) {
        const url = new URL(window.location);
        if (value && value !== '') {
            url.searchParams.set(name, value);
        } else {
            url.searchParams.delete(name);
        }
        window.history.replaceState({}, '', url);
    }

    // Initialize filters from URL parameters on page load
    function initializeFiltersFromUrl() {
        const certFromUrl = getUrlParameter('certificate');
        const statusFromUrl = getUrlParameter('status');
        const searchFromUrl = getUrlParameter('search');
        const dateFromUrl = getUrlParameter('date');

        if (certFromUrl) {
            certificateFilter.value = certFromUrl;
        }
        if (statusFromUrl) {
            statusFilter.value = statusFromUrl;
        }
        if (searchFromUrl) {
            searchInput.value = searchFromUrl;
        }
        if (dateFromUrl) {
            dateFilter.value = dateFromUrl;
        }

        // Apply filters after setting values
        filterTable();
    }

    // Combined filter function
    function filterTable() {
        const searchFilter = searchInput.value.toLowerCase().trim();
        const selectedCertificate = certificateFilter.value.toLowerCase();
        const selectedStatus = statusFilter.value.toLowerCase();
        const selectedDate = dateFilter.value;

        // Update URL parameters
        setUrlParameter('search', searchInput.value);
        setUrlParameter('certificate', certificateFilter.value);
        setUrlParameter('status', statusFilter.value);
        setUrlParameter('date', dateFilter.value);

        let visibleCount = 0;

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const rowText = row.innerText.toLowerCase();
            const rowCertificate = row.getAttribute('data-certificate')?.toLowerCase() || '';
            const rowStatus = row.getAttribute('data-status')?.toLowerCase() || '';
            const rowDate = row.getAttribute('data-date') || '';

            const matchesSearch = !searchFilter || rowText.includes(searchFilter);
            const matchesCertificate = !selectedCertificate || rowCertificate === selectedCertificate;
            const matchesStatus = !selectedStatus || rowStatus === selectedStatus;
            const matchesDate = !selectedDate || rowDate.startsWith(selectedDate);

            const isVisible = matchesSearch && matchesCertificate && matchesStatus && matchesDate;
            row.style.display = isVisible ? '' : 'none';

            if (isVisible) visibleCount++;
        }

        updateVisibleCount(visibleCount);
    }

    function updateVisibleCount(count) {
        console.log(`Showing ${count} requests`);
    }

    function clearFilters() {
        searchInput.value = '';
        certificateFilter.value = '';
        statusFilter.value = '';
        dateFilter.value = '';
        
        // Clear URL parameters
        setUrlParameter('search', '');
        setUrlParameter('certificate', '');
        setUrlParameter('status', '');
        setUrlParameter('date', '');
        
        filterTable();
    }

    // Debounced search for better performance
    let searchTimeout;
    searchInput.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterTable, 300);
    });

    certificateFilter.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);
    dateFilter.addEventListener('change', filterTable);

    // Enhanced table interactions
    document.querySelectorAll('.table tbody tr').forEach(row => {
        row.addEventListener('click', function() {
            const requestId = this.querySelector('.request-id')?.textContent;
            console.log('Selected request:', requestId);
        });
    });

    // Initialize animations and filters
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize filters from URL first
        initializeFiltersFromUrl();

        // Then handle animations
        const cards = document.querySelectorAll('.stat-card, .search-section, .data-table-container');
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

    // Auto-refresh functionality (optional)
    function autoRefresh() {
        // Uncomment the line below to enable auto-refresh every 30 seconds
        // setTimeout(() => { location.reload(); }, 30000);
    }

    // Initialize auto-refresh
    autoRefresh();
</script>
</body>
</html>

<?php $conn->close(); ?>
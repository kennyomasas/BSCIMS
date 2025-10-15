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


$sql = "SELECT * FROM admins";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Manage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
       
        /* Prevent horizontal scrolling */
        html, body {
            width: 100%;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .btn-action {
            margin-right: 5px;
        }


 
        .sidebar{
            position: fixed;
top: 0;
left: 0;
height: 100vh;
overflow-y: auto;
z-index: 1000;
        }

        

       /* Sidebar Hover Effect */
  .sidebar-item {
        transition: background 0.3s ease-in-out, transform 0.3s ease-in-out;
    }

    .sidebar-item:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateX(10px);
    }

    /* Dropdown Menu - Show on Hover */
    .dropdown:hover .dropdown-menu {
        display: block;
        animation: fadeIn 0.3s ease-in-out;
    }

    /* Smooth Dropdown Animation */
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

    /* Optional: Adjust Dropdown Width */
    .dropdown-menu {
        min-width: 180px;
    }

     /* animate BSCIMS */
     .animated-title {
        transition: transform 0.3s ease, color 0.3s ease;
    }

    .animated-title:hover {
        transform: scale(1.1);
        color: #eff319; /* Change to any color you like */
    }

    /* Modern Table Styles */
    .modern-table-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        padding: 24px;
        margin: 20px 0;
        overflow: hidden;
    }

    .modern-table {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        background: white;
    }

    .modern-table thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .modern-table thead th {
        border: none;
        padding: 18px 16px;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: none;
    }

    .modern-table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f1f3f4;
    }

    .modern-table tbody tr:last-child {
        border-bottom: none;
    }

    .modern-table tbody tr:hover {
        background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }

    .modern-table tbody td {
        border: none;
        padding: 18px 16px;
        vertical-align: middle;
        font-size: 14px;
        color: #333;
    }

    .modern-table tbody td:first-child {
        font-weight: 600;
        color: #667eea;
    }

    /* Status Badge Styles */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-admin {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    /* Search Bar Modern Style */
    .modern-search {
        position: relative;
        margin-bottom: 24px;
    }

    .modern-search input {
        border: 2px solid #e1e5e9;
        border-radius: 12px;
        padding: 12px 16px 12px 48px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .modern-search input:focus {
        border-color: #667eea;
        box-shadow: 0 4px 20px rgba(102, 126, 234, 0.15);
        outline: none;
    }

    .modern-search .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 16px;
    }

    /* Header Section */
    .page-header {
        background: linear-gradient(135deg, #1b262c);
        color: white;
        padding: 14px;
       
        margin-bottom: 24px;
        box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
        
    }

    .page-header h2 {
        margin: 0;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* Add User Button */
    .add-user-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(102, 126, 234, 0.3);
    }

    .add-user-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
        color: white;
    }

    /* Table Stats */
    .table-stats {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        flex: 1;
        text-align: center;
    }

    .stat-number {
        font-size: 28px;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 12px;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Action Buttons */
    .action-btn {
        border: none;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 12px;
        margin: 0 2px;
        transition: all 0.3s ease;
    }

    .btn-edit {
        background: #3b82f6;
        color: white;
    }

    .btn-delete {
        background: #ef4444;
        color: white;
    }

    .action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Main Content Layout */
    .main-content {
        flex: 1;
        padding: 24px;
        background: #f8fafc;
        min-height: 100vh;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .modern-table-container {
            padding: 16px;
        }
        
        .modern-table thead th,
        .modern-table tbody td {
            padding: 12px 8px;
            font-size: 12px;
        }
        
        .table-stats {
            flex-direction: column;
        }
    }

    /* Toast Notification Styles */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
}

.toast-notification {
    display: none;
    padding: 16px 20px;
    border-radius: 12px;
    color: white;
    font-weight: 500;
    margin-bottom: 10px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    animation: slideIn 0.3s ease-out;
}

.toast-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.toast-error {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.toast-close {
    background: none;
    border: none;
    color: white;
    font-size: 18px;
    margin-left: 15px;
    cursor: pointer;
    opacity: 0.8;
}

.toast-close:hover {
    opacity: 1;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

#toast {
    visibility: hidden;
    min-width: 300px;
    margin-left: -150px;
    background-color: #28a745; /* green */
    color: white;
    text-align: center;
    border-radius: 8px;
    padding: 16px;
    position: fixed;
    z-index: 9999;
    left: 50%;
    bottom: 30px;
    font-size: 16px;
    transition: visibility 0s, opacity 0.5s linear;
    opacity: 0;
}

#toast.show {
    visibility: visible;
    opacity: 1;
}

#toast button {
    background-color: white;
    color: #28a745;
    border: none;
    padding: 8px 16px;
    margin-top: 10px;
    cursor: pointer;
    border-radius: 5px;
}

#toast button:hover {
    background-color: #f1f1f1;
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

        .update-account-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 10px;
    color: white;
    padding: 12px 20px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.update-account-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    background: linear-gradient(135deg, #5a67d8 0%, #667eea 100%);
}

.add-user-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 10px;
    color: white;
    padding: 12px 20px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.add-user-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    background: linear-gradient(135deg, #5a67d8 0%, #667eea 100%);
}

.modern-search {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    z-index: 10;
}

.modern-search .form-control {
    padding-left: 45px;
    border-radius: 25px;
    border: 2px solid #e9ecef;
    font-size: 14px;
    transition: all 0.3s ease;
}

.modern-search .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    outline: none;
}

.gap-2 {
    gap: 0.5rem;
}

/* Modal Styles */
.modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px 15px 0 0;
    border-bottom: none;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

.modal-body {
    padding: 2rem;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-control[readonly] {
    background-color: #f8f9fa;
    color: #6c757d;
}

.input-group .btn {
    border: 2px solid #e9ecef;
    border-left: none;
}

.modal-footer {
    padding: 1.5rem 2rem;
    border-top: 1px solid #e9ecef;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
}

/* Toast Styles */
.toast-container {
    z-index: 9999 !important;
}

.toast {
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    border: none !important;
}

.toast.bg-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
}

.toast.bg-danger {
    background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%) !important;
}

.toast-body {
    font-weight: 500;
    padding: 0.75rem;
}

.toast .btn-close-white {
    filter: brightness(0) invert(1);
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
            <a href="#" id="officialsBtn" class="nav-link text-white d-flex align-items-center sidebar-item dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
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
            <a class="nav-link text-white d-flex align-items-center sidebar-item" href="Administration.php" data-page="administration">
                <i class="fas fa-building me-2"></i> Administration
                <div class="page-indicator"></div>
            </a>
        </li>

        <li class="nav-item mb-3">
            <a href="Announcement.html" class="nav-link text-white d-flex align-items-center sidebar-item">
                <i class="fas fa-bullhorn me-2"></i> Announcements
            </a>
        </li>

        <li class="nav-item mb-3">
            <a href="list_admins.php" class="nav-link text-white d-flex align-items-center sidebar-item">
                <i class="fas fa-cog me-2"></i> System Settings
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
            <!-- Page Header -->
          <div class="page-header" style="margin-left: 210px; margin-right: -25px; margin-top: -25px;">
    <h2 style="font-size: 1.5rem; display: flex; align-items: center;">
        <img src="logo.png" alt="Logo" style="height: 30px; width: auto; ">
        Account Management 
    </h2>
</div>


            <!-- Table Stats -->
            <div class="table-stats" style="margin-left: 230px;">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $result->num_rows; ?></div>
                    <div class="stat-label">Total Users</div>
                </div>
              
            </div>

           <!-- Controls Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <!-- Search Bar -->
    <div class="modern-search" style="flex: 1; max-width: 350px; margin-left: 230px;">
        <i class="fas fa-search search-icon"></i>
        <input type="text" id="searchInput" class="form-control" placeholder="Search by first name, username, or email...">
    </div>
    
    <!-- Button Group -->
    <div class="d-flex gap-2">
        <!-- Update Account Button -->
        <button id="updateAccountBtn" class="update-account-btn" data-bs-toggle="modal" data-bs-target="#updateAccountModal">
            <i class="fas fa-user-edit me-2"></i> Update Account
        </button>
        
        <!-- Add User Button -->
        <button id="addUserBtn" class="add-user-btn">
            <i class="fas fa-user-plus me-2"></i> Add New User
        </button>
    </div>
</div>
            <!-- Modern Table Container -->
            <div class="modern-table-container" style="margin-left: 230px;">
                <table class="table modern-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-user me-2"></i>Username</th>
                            <th><i class="fas fa-lock me-2"></i>Password</th>
                            <th><i class="fas fa-id-card me-2"></i>First_Name</th>
                            <th><i class="fas fa-id-card me-2"></i>Last_Name</th>
                            <th><i class="fas fa-envelope me-2"></i>Email</th>
                             <th><i class="fas fa-briefcase me-2"></i>Position</th>
                            <th><i class="fas fa-cog me-2"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="adminTable">

                        <?php
                        if ($result->num_rows > 0) {
                            // Reset result pointer
                            $result->data_seek(0);
                            // Output each row
                          while ($row = $result->fetch_assoc()) {
    echo "<tr class='admin-row'>";
    echo "<td class='username-cell'>" . htmlspecialchars($row['username']) . "</td>";
    echo "<td><span class='text-muted'>••••••••</span></td>";
    echo "<td class='first-name'>" . htmlspecialchars($row['first_name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
    echo "<td>" . htmlspecialchars($row['position']) . "</td>";
    echo "<td>
            <button class='action-btn btn-edit' data-id='" . $row['id'] . "' title='Edit User'>
                <i class='fas fa-edit'></i>
            </button>
          
          </td>";
    echo "</tr>";
}
                        } else {
                            echo "<tr><td colspan='7' class='text-center py-5'>
                                    <i class='fas fa-users fa-3x text-muted mb-3'></i>
                                    <div class='text-muted'>No administrators found</div>
                                    <small class='text-muted'>Click 'Add New User' to create your first admin account</small>
                                  </td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
            <!-- Modal Header -->
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title" id="userModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Add New User
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body" style="padding: 32px;">
                <form action="add_admin.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label fw-semibold">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required style="border-radius: 8px; padding: 12px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required style="border-radius: 8px; padding: 12px;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label fw-semibold">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required style="border-radius: 8px; padding: 12px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label fw-semibold">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required style="border-radius: 8px; padding: 12px;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required style="border-radius: 8px; padding: 12px;">
                    </div>
                   
                    <div class="mb-3">
    <label for="position" class="form-label fw-semibold">Position</label>
    <select class="form-select" id="position" name="position" required style="border-radius: 8px; padding: 12px;">
        <option value="" disabled selected>Select Committee</option>
        <option value="Punong Barangay">Punong Barangay</option>
        <option value="Appropriation badac tourism">Appropriation, Budget & Tourism</option>
        <option value="Bdrrmc good governance">BDRRMC & Good Governance</option>
        <option value="Education and agriculture">Education & Agriculture</option>
        <option value="Health nutrition and environment">Health, Nutrition & Environment</option>
        <option value="Infrastructure">Infrastructure</option>
        <option value="Peace & order">Peace & Order</option>
        <option value="Secretary">Secretary</option>
        <option value="Sk Chairman">SK Chairman</option>
        <option value="Social services vawc and bcp">Social Services, VAWC & BCP</option>
        <option value="Treasurer">Treasurer</option>
    </select>
</div>

                    <!-- Modal Footer -->
                    <div class="modal-footer" style="border: none; padding-top: 24px;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; padding: 10px 20px;">Close</button>
                        <button type="submit" class="btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; padding: 10px 20px;">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="toast-container" id="toastContainer"></div>

<div id="toast">
    <span id="toast-message"></span>
    <br>
    <button id="toast-confirm">Confirm</button>
</div>


<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 16px 16px 0 0;">
                <h5 class="modal-title" id="editUserModalLabel">
                    <i class="fas fa-user-edit me-2"></i>Edit User
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 32px;">
                <form action="edit_admin.php" method="POST">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_username" class="form-label fw-semibold">Username</label>
                            <input type="text" class="form-control" id="edit_username" name="username" required style="border-radius: 8px; padding: 12px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_password" class="form-label fw-semibold">Password</label>
                            <input type="password" class="form-control" id="edit_password" name="password" placeholder="Leave blank to keep current password" style="border-radius: 8px; padding: 12px;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_first_name" class="form-label fw-semibold">First Name</label>
                            <input type="text" class="form-control" id="edit_first_name" name="first_name" required style="border-radius: 8px; padding: 12px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_last_name" class="form-label fw-semibold">Last Name</label>
                            <input type="text" class="form-control" id="edit_last_name" name="last_name" required style="border-radius: 8px; padding: 12px;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required style="border-radius: 8px; padding: 12px;">
                    </div>
                    <div class="mb-3">
                        <label for="edit_position" class="form-label fw-semibold">Position</label>
                        <select class="form-select" id="edit_position" name="position" required style="border-radius: 8px; padding: 12px;">
                            <option value="" disabled>Select Committee</option>
                            <option value="Punong Barangay">Punong Barangay</option>
                            <option value="Appropriation badac tourism">Appropriation, Budget & Tourism</option>
                            <option value="Bdrrmc good governance">BDRRMC & Good Governance</option>
                            <option value="Education and agriculture">Education & Agriculture</option>
                            <option value="Health nutrition and environment">Health, Nutrition & Environment</option>
                            <option value="Infrastructure">Infrastructure</option>
                            <option value="Peace & order">Peace & Order</option>
                            <option value="Secretary">Secretary</option>
                            <option value="Sk Chairman">SK Chairman</option>
                            <option value="Social services vawc and bcp">Social Services, VAWC & BCP</option>
                            <option value="Treasurer">Treasurer</option>
                        </select>
                    </div>
                    <div class="modal-footer" style="border: none; padding-top: 24px;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; padding: 10px 20px;">Close</button>
                        <button type="submit" class="btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; padding: 10px 20px;">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Update Account Modal -->
<div class="modal fade" id="updateAccountModal" tabindex="-1" aria-labelledby="updateAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateAccountModalLabel">
                    <i class="fas fa-user-edit me-2"></i>Update Account Information
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateAccountForm">
                    <div class="mb-3">
                        <label for="currentUsername" class="form-label">
                            <i class="fas fa-user me-2"></i>Current Username
                        </label>
                        <input type="text" class="form-control" id="currentUsername" value="admin" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="newUsername" class="form-label">New Username</label>
                        <input type="text" class="form-control" id="newUsername" placeholder="Enter new username">
                    </div>
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">
                            <i class="fas fa-lock me-2"></i>Current Password
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="currentPassword" value="********" readonly>
                            <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="newPassword" placeholder="Enter new password">
                            <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm new password">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveChangesBtn">
                    <i class="fas fa-save me-2"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>


<script>
// Toast Notification Function
function showToast(type, message) {
    const toastContainer = document.getElementById('toastContainer');
    
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.innerHTML = `
        <span>${message}</span>
        <button class="toast-close" onclick="closeToast(this)">&times;</button>
    `;
    
    toastContainer.appendChild(toast);
    toast.style.display = 'flex';
    toast.style.alignItems = 'center';
    toast.style.justifyContent = 'space-between';
    
    setTimeout(() => {
        closeToast(toast.querySelector('.toast-close'));
    }, 5000);
}

function closeToast(button) {
    const toast = button.parentElement;
    toast.style.animation = 'slideOut 0.3s ease-out';
    
    setTimeout(() => {
        toast.remove();
    }, 300);
}

// Check for toast parameters in URL
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const toastType = urlParams.get('toast');
    const toastMessage = urlParams.get('message');
    
    if (toastType && toastMessage) {
        showToast(toastType, decodeURIComponent(toastMessage));
        
        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
    }
});

// Add User Modal
document.getElementById("addUserBtn").addEventListener("click", function () {
    var userModal = new bootstrap.Modal(document.getElementById("userModal"));
    userModal.show();
});

// Search functionality
document.getElementById("searchInput").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll(".admin-row");
    
    rows.forEach(row => {
        let firstName = row.querySelector(".first-name").textContent.toLowerCase();
        let username = row.querySelector(".username-cell").textContent.toLowerCase();
        let email = row.cells[4].textContent.toLowerCase();
        
        if (firstName.includes(filter) || username.includes(filter) || email.includes(filter) || filter === "") {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});

// Edit and Delete functionality
document.addEventListener('DOMContentLoaded', function() {
    // Edit button handlers
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-id');
            
            // Fetch user data
            fetch(`edit_admin.php?id=${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        showToast('error', 'Error loading user data');
                        return;
                    }
                    
                    // Populate the edit modal
                    document.getElementById('edit_id').value = data.id;
                    document.getElementById('edit_username').value = data.username;
                    document.getElementById('edit_first_name').value = data.first_name;
                    document.getElementById('edit_last_name').value = data.last_name;
                    document.getElementById('edit_email').value = data.email;
                    document.getElementById('edit_position').value = data.position;
                    document.getElementById('edit_password').value = '';
                    
                    // Show the modal
                    var editModal = new bootstrap.Modal(document.getElementById("editUserModal"));
                    editModal.show();
                })
                .catch(error => {
                    showToast('error', 'Error loading user data');
                });
        });
    });


        });
 
</script>

<!-- Add this JavaScript at the end of your file, before closing </body> -->
<script>
// Toast Notification Function
function showToast(type, message) {
    const toastContainer = document.getElementById('toastContainer');
    
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.innerHTML = `
        <span>${message}</span>
        <button class="toast-close" onclick="closeToast(this)">&times;</button>
    `;
    
    toastContainer.appendChild(toast);
    toast.style.display = 'flex';
    toast.style.alignItems = 'center';
    toast.style.justifyContent = 'space-between';
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        closeToast(toast.querySelector('.toast-close'));
    }, 5000);
}

function closeToast(button) {
    const toast = button.parentElement;
    toast.style.animation = 'slideOut 0.3s ease-out';
    
    setTimeout(() => {
        toast.remove();
    }, 300);
}

// Check for toast parameters in URL
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const toastType = urlParams.get('toast');
    const toastMessage = urlParams.get('message');
    
    if (toastType && toastMessage) {
        showToast(toastType, decodeURIComponent(toastMessage));
        
        // Clean up URL parameters
        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
    }
});
</script>

<script>
    document.getElementById("addUserBtn").addEventListener("click", function () {
        var userModal = new bootstrap.Modal(document.getElementById("userModal"));
        userModal.show();
    });
</script>

     <!-- JavaScript for Enhanced Search Functionality -->
     <script>
        document.getElementById("searchInput").addEventListener("keyup", function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll(".admin-row");
            
            rows.forEach(row => {
                let firstName = row.querySelector(".first-name").textContent.toLowerCase();
                let username = row.querySelector(".username-cell").textContent.toLowerCase();
                let email = row.cells[4].textContent.toLowerCase(); // Email column
                
                if (firstName.includes(filter) || username.includes(filter) || email.includes(filter) || filter === "") {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });

        // Add click handlers for action buttons
        document.addEventListener('DOMContentLoaded', function() {
            // Edit button handlers
            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', function() {
                    // Add your edit functionality here
                    console.log('Edit button clicked');
                });
            });

           // Delete button handlers
document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', function() {
        // Show toast notification asking for confirmation
        showToast('Are you sure you want to delete this user?', 'warning', {
            showConfirm: true,
            onConfirm: () => {
                // Add your delete functionality here
                console.log('Delete button clicked');
                // Show success message after deletion
                showToast('User deleted successfully!', 'success');
            }
        });
    });
});

// Toast notification function
function showToast(message, type = 'info', options = {}) {
    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        `;
        document.body.appendChild(toastContainer);
    }
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.style.cssText = `
        background: ${type === 'warning' ? '#d3260fff' : type === 'success' ? '#4caf50' : '#2196f3'};
        color: white;
        padding: 16px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        margin-bottom: 10px;
        min-width: 300px;
        max-width: 400px;
        word-wrap: break-word;
        transform: translateX(100%);
        transition: transform 0.3s ease-in-out;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-family: Arial, sans-serif;
        font-size: 14px;
    `;
    
    // Create message content
    const messageDiv = document.createElement('div');
    messageDiv.textContent = message;
    messageDiv.style.flex = '1';
    toast.appendChild(messageDiv);
    
    // Add confirm/cancel buttons if needed
    if (options.showConfirm) {
        const buttonContainer = document.createElement('div');
        buttonContainer.style.cssText = `
            margin-left: 15px;
            display: flex;
            gap: 8px;
        `;
        
        const confirmBtn = document.createElement('button');
        confirmBtn.textContent = 'Delete';
        confirmBtn.style.cssText = `
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 4px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
        `;
        
        const cancelBtn = document.createElement('button');
        cancelBtn.textContent = 'Cancel';
        cancelBtn.style.cssText = `
            background: transparent;
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 4px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        `;
        
        confirmBtn.addEventListener('click', () => {
            if (options.onConfirm) options.onConfirm();
            removeToast(toast);
        });
        
        cancelBtn.addEventListener('click', () => {
            removeToast(toast);
        });
        
        buttonContainer.appendChild(confirmBtn);
        buttonContainer.appendChild(cancelBtn);
        toast.appendChild(buttonContainer);
    } else {
        // Add close button for regular toasts
        const closeBtn = document.createElement('button');
        closeBtn.innerHTML = '×';
        closeBtn.style.cssText = `
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            margin-left: 10px;
            padding: 0;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        `;
        closeBtn.addEventListener('click', () => removeToast(toast));
        toast.appendChild(closeBtn);
    }
    
    // Add to container and animate in
    toastContainer.appendChild(toast);
    
    // Trigger animation
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 10);
    
    // Auto remove after delay (only for non-confirmation toasts)
    if (!options.showConfirm) {
        setTimeout(() => {
            removeToast(toast);
        }, options.duration || 5000);
    }
}

function removeToast(toast) {
    toast.style.transform = 'translateX(100%)';
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 300);
}
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

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




    <script>
      
// JavaScript for modal functionality
document.addEventListener('DOMContentLoaded', function() {
    
    // Load current admin data when modal opens
    document.getElementById('updateAccountModal').addEventListener('show.bs.modal', function() {
        loadCurrentAdminData();
    });
    
    // Function to load current admin data from database
    async function loadCurrentAdminData() {
        try {
            const response = await fetch('get_admin_data.php');
            const data = await response.json();
            
            if (data.success) {
                document.getElementById('currentUsername').value = data.username;
                // Keep password masked for security
                document.getElementById('currentPassword').value = '********';
                document.getElementById('currentPassword').setAttribute('data-actual-password', data.password);
            }
        } catch (error) {
            console.error('Error loading admin data:', error);
        }
    }

    // Toggle password visibility for current password
    document.getElementById('toggleCurrentPassword').addEventListener('click', function() {
        const passwordField = document.getElementById('currentPassword');
        const icon = this.querySelector('i');
        const actualPassword = passwordField.getAttribute('data-actual-password');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            passwordField.value = actualPassword || 'admin123'; // Show actual password
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            passwordField.value = '********';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    // Toggle password visibility for new password
    document.getElementById('toggleNewPassword').addEventListener('click', function() {
        const passwordField = document.getElementById('newPassword');
        const icon = this.querySelector('i');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    // Handle save changes
    document.getElementById('saveChangesBtn').addEventListener('click', function() {
        const newUsername = document.getElementById('newUsername').value.trim();
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        // Basic validation
        if (newPassword && newPassword !== confirmPassword) {
            showErrorToast('New passwords do not match!');
            return;
        }

        if (!newUsername && !newPassword) {
            showErrorToast('Please enter new username or password to update.');
            return;
        }

        // Prepare data to send
        const updateData = {};
        if (newUsername) updateData.username = newUsername;
        if (newPassword) updateData.password = newPassword;

        // Send update request to server
        updateAdminAccount(updateData);
    });

    // Function to update admin account in database
    async function updateAdminAccount(updateData) {
        try {
            const response = await fetch('update_admin_account.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(updateData)
            });

            const result = await response.json();

            if (result.success) {
                showSuccessToast('Account updated successfully!');
                
                // Update current username display if changed
                if (updateData.username) {
                    document.getElementById('currentUsername').value = updateData.username;
                }
                
                // Close modal and reset form
                const modal = bootstrap.Modal.getInstance(document.getElementById('updateAccountModal'));
                modal.hide();
                document.getElementById('updateAccountForm').reset();
            } else {
                showErrorToast('Error updating account: ' + (result.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error updating admin account:', error);
            showErrorToast('Error updating account. Please try again.');
        }
    }

    // Function to show success toast notification
    function showSuccessToast(message) {
        const toastHtml = `
            <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-check-circle me-2"></i>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        showToast(toastHtml);
    }

    // Function to show error toast notification
    function showErrorToast(message) {
        const toastHtml = `
            <div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-exclamation-circle me-2"></i>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        showToast(toastHtml);
    }

    // Function to create and show toast
    function showToast(toastHtml) {
        // Create toast container if it doesn't exist
        let toastContainer = document.getElementById('toastContainer');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toastContainer';
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }

        // Create toast element
        const toastElement = document.createElement('div');
        toastElement.innerHTML = toastHtml;
        const toast = toastElement.firstElementChild;
        
        // Add to container and show
        toastContainer.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 4000
        });
        bsToast.show();

        // Remove toast element after it's hidden
        toast.addEventListener('hidden.bs.toast', function() {
            toast.remove();
        });
    }

    // Reset form when modal is closed
    document.getElementById('updateAccountModal').addEventListener('hidden.bs.modal', function() {
        document.getElementById('updateAccountForm').reset();
        // Reset password field to hidden state
        const currentPasswordField = document.getElementById('currentPassword');
        const currentPasswordIcon = document.getElementById('toggleCurrentPassword').querySelector('i');
        currentPasswordField.type = 'password';
        currentPasswordField.value = '********';
        currentPasswordIcon.classList.remove('fa-eye-slash');
        currentPasswordIcon.classList.add('fa-eye');
        
        // Reset new password field
        const newPasswordField = document.getElementById('newPassword');
        const newPasswordIcon = document.getElementById('toggleNewPassword').querySelector('i');
        newPasswordField.type = 'password';
        newPasswordIcon.classList.remove('fa-eye-slash');
        newPasswordIcon.classList.add('fa-eye');
    });
});
    </script>

</body>
</html>

<?php
// Close connection
$conn->close();
?>
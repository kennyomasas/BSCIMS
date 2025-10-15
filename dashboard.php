


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay San Carlos Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add jQuery for AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <style>
        body {
            overflow: hidden; /* Prevents scrolling */
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }

        .dropdown .btn-link {
            color: white !important;
        }

        /* Sidebar Hover Effect */
        .sidebar-item {
            transition: background 0.3s ease-in-out, padding-left 0.3s ease-in-out;
        }

        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.2);
            padding-left: 10px;
        }

        /* Dropdown Menu - Show on Hover */
        .dropdown:hover .dropdown-menu {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }

        /* Smooth Dropdown Animation */
        .animate-dropdown {
            display: none;
            opacity: 0;
            transform: translateY(-5px);
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .dropdown:hover .animate-dropdown {
            display: block;
            opacity: 1;
            transform: translateY(0);
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

        /* Dashboard specific styles */
        .dashboard-container {
            padding: 20px;
            overflow-y: auto;
            height: calc(100vh - 70px);
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .stat-card {
            border-left: 4px solid;
        }

        .stat-card.residents {
            border-left-color: #4e73df;
        }

        .stat-card.requests {
            border-left-color: #1cc88a;
        }

        .stat-card.pending {
            border-left-color: #f6c23e;
        }

        .stat-card.announcements {
            border-left-color: #e74a3b;
        }

        .stat-card .card-body {
            padding: 1rem;
        }

        .stat-card .text-muted {
            font-size: 0.8rem;
        }

        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }

        .quick-actions a {
            display: block;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 5px;
            color: white;
            text-decoration: none;
            transition: transform 0.2s;
        }

        .quick-actions a:hover {
            transform: scale(1.03);
        }

        .recent-table th, .recent-table td {
            padding: 0.75rem;
            vertical-align: middle;
        }

        .badge {
            font-weight: 500;
            padding: 5px 10px;
        }

        .news-item {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .news-item:last-child {
            border-bottom: none;
        }

        .demographic-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .demographic-bar {
            flex-grow: 1;
            height: 12px;
            border-radius: 6px;
            margin: 0 10px;
            position: relative;
        }

        .demographic-bar-fill {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            border-radius: 6px;
        }

        .dashboard-section {
            margin-bottom: 20px;
        }

        .section-header {
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .section-header i {
            margin-right: 10px;
        }

        /* Add responsive adjustments */
        @media (max-width: 992px) {
            .dashboard-container {
                padding: 10px;
            }
        }

        /* Status Badge Styles */
.badge {
    display: inline-block;
    padding: 0.25em 0.6em;
    font-size: 0.75em;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.375rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-pending {
    color: #856404;
    background-color: #fff3cd;
    border: 1px solid #ffeaa7;
}

.badge-approved {
    color: #155724;
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
}

.badge-rejected {
    color: #721c24;
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
}

.badge-processing {
    color: #004085;
    background-color: #cce7ff;
    border: 1px solid #b3d7ff;
}

/* Loading spinner */
.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 10px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
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
        

        /* card percentage */
        .stat-card {
            border-left: 4px solid;
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
            bottom: 0;
            opacity: 0.1;
            transition: all 0.3s ease;
            z-index: 1;
        }
        
        .stat-card .card-body {
            position: relative;
            z-index: 2;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .progress-info {
            font-size: 0.75rem;
            margin-top: 5px;
            font-weight: 500;
        }
        
        .progress-bar-custom {
            height: 4px;
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
            background-color: rgba(255,255,255,0.3);
        }
        
        .progress-fill {
            height: 100%;
            border-radius: 2px;
            transition: width 0.8s ease;
        }

        /* Generic progress info styling */
        .progress-info {
            font-size: 0.75rem;
            margin-top: 5px;
            font-weight: 500;
        }
        
        /* Color classes for different progress levels */
        .progress-very-low {
            border-left-color: #dc3545;
        }
        .progress-very-low::before {
            background-color: #dc3545;
        }
        .progress-very-low .progress-fill {
            background-color: #dc3545;
        }
        
        .progress-low {
            border-left-color: #fd7e14;
        }
        .progress-low::before {
            background-color: #fd7e14;
        }
        .progress-low .progress-fill {
            background-color: #fd7e14;
        }
        
        .progress-medium {
            border-left-color: #ffc107;
        }
        .progress-medium::before {
            background-color: #ffc107;
        }
        .progress-medium .progress-fill {
            background-color: #ffc107;
        }
        
        .progress-good {
            border-left-color: #20c997;
        }
        .progress-good::before {
            background-color: #20c997;
        }
        .progress-good .progress-fill {
            background-color: #20c997;
        }
        
        .progress-high {
            border-left-color: #28a745;
        }
        .progress-high::before {
            background-color: #28a745;
        }
        .progress-high .progress-fill {
            background-color: #28a745;
        }
        
        .progress-complete {
            border-left-color: #007bff;
        }
        .progress-complete::before {
            background-color: #007bff;
        }
        .progress-complete .progress-fill {
            background-color: #007bff;
        }

        /* Static card colors for other stats */
        .stat-card.requests {
            border-left-color: #007bff;
        }
        .stat-card.requests::before {
            background-color: #007bff;
        }
        
        .stat-card.pending {
            border-left-color: #ffc107;
        }
        .stat-card.pending::before {
            background-color: #ffc107;
        }
        
        .stat-card.announcements {
            border-left-color: #28a745;
        }
        .stat-card.announcements::before {
            background-color: #5ba728;
        }

        
    </style>
</head>
<body class="bg-light">
    <div class="d-flex">

       <!-- Sidebar -->
<nav class="text-white vh-100 p-3 sidebar" style="width: 235px; background-color: #31363F;">
    <div class="mb-4" style="margin-left: 19px;">
        <h2 class="fs-4 animated-title">BSCIMS</h2>
    </div>
    
    <ul class="nav flex-column">
        <li class="nav-item mb-3" style="margin-top: 30px;">
            <a href="dashboard.html" class="nav-link text-white d-flex align-items-center sidebar-item" data-page="dashboard">
                <i class="fas fa-home me-2"></i> Main Dashboard
                <div class="page-indicator"></div>
            </a>
        </li>

        <li class="nav-item dropdown mb-3">
            <a href="#" id="officialsBtn" class="nav-link text-white d-flex align-items-center sidebar-item dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" >
                <i class="fas fa-users me-2"></i> Barangay Officials
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="Barangay_official.php">View Officials</a></li>
                <li><a class="dropdown-item" href="manage_official.html">Manage Officials</a></li>
            </ul>
        </li>

        <li class="nav-item mb-3 dropdown">
            <a class="nav-link dropdown-toggle text-white d-flex align-items-center sidebar-item">
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
<div class="flex-grow-1" style="margin-left: -18px; margin-right: -33px; margin-top: -24px; height: 100vh;">
    <!-- Header -->
    <div class="d-flex align-items-center text-white p-3" style="background-color: #222831; margin-right: 2px; margin-left: 18px; margin-top: 24px;">
        <img src="logo.png" alt="Logo" style="width: auto; height: 35px; margin-right: 10px;">
        <h1 class="fs-5 mb-0">Barangay San Carlos, City of Valencia, Province of Bukidnon</h1>
        <div class="d-flex align-items-center ms-auto">
           
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="dashboard-container" >
        <div class="row mb-4">
            <div class="col-md-12">
                <h2 class="mb-3">&nbsp;Dashboard</h2>
                <p class="text-muted">&nbsp;&nbsp;Welcome to the Barangay San Carlos Information Management System</p>
            </div>
        </div>
        
        <!-- Stat Cards Row -->
  <div class="row mb-4">
            <!-- Residents Card -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stat-card residents h-100" id="residents-card" style="cursor: pointer; transition: transform 0.2s;">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col">
                                <div class="text-xs text-muted text-uppercase mb-1">Total Residents</div>
                                <div class="h5 mb-0 font-weight-bold" id="total-residents">Loading...</div>
                                <div class="progress-info" id="progress-text">
                                    <span id="percentage">0%</span> 
                                </div>
                                <div class="progress-bar-custom">
                                    <div class="progress-fill" id="progress-fill" style="width: 0%"></div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Requests Card -->
            <div class="col-xl-3 col-md-6 mb-3">
                <a href="Issued Certificate.html" style="text-decoration: none; color: inherit;">
                    <div class="card stat-card requests h-100" id="requests-card">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col">
                                    <div class="text-xs text-muted text-uppercase mb-1">Document Requests</div>
                                    <div class="h5 mb-0 font-weight-bold" id="total-requests">Loading...</div>
                                    <div class="progress-info" id="requests-progress-text">
                                        <span id="requests-percentage">0%</span> 
                                    </div>
                                    <div class="progress-bar-custom">
                                        <div class="progress-fill" id="requests-progress-fill" style="width: 0%"></div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-file-alt fa-2x text-muted"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Pending Requests Card -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stat-card pending h-100" id="pending-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col">
                                <div class="text-xs text-muted text-uppercase mb-1">Pending Requests</div>
                                <div class="h5 mb-0 font-weight-bold" id="pending-requests">Loading...</div>
                                <div class="progress-info" id="pending-progress-text">
                                    <span id="pending-percentage">0%</span> 
                                </div>
                                <div class="progress-bar-custom">
                                    <div class="progress-fill" id="pending-progress-fill" style="width: 0%"></div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-muted"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Announcements Card -->
            <div class="col-xl-3 col-md-6 mb-3">
                <a href="Announcement.html" style="text-decoration: none; color: inherit;">
                    <div class="card stat-card announcements h-100" id="announcements-card">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col">
                                    <div class="text-xs text-muted text-uppercase mb-1">Announcements</div>
                                    <div class="h5 mb-0 font-weight-bold" id="total-announcements">Loading...</div>
                                    <div class="progress-info" id="announcements-progress-text">
                                        <span id="announcements-percentage">0%</span> 
                                    </div>
                                    <div class="progress-bar-custom">
                                        <div class="progress-fill" id="announcements-progress-fill" style="width: 0%"></div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-bullhorn fa-2x text-muted"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        
        <!-- Charts and Quick Actions Row -->
        <div class="row mb-4">
            <!-- Gender Distribution Chart -->
            <div class="col-xl-6 col-lg-12 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-white">
                        <h6 class="m-0 font-weight-bold">Resident Gender Distribution</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Purok Distribution Chart -->
            <div class="col-xl-6 col-lg-12 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-white">
                        <h6 class="m-0 font-weight-bold">Residents by Purok</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="purokChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
       <!-- Recent Certificate Requests Row -->
        <div class="row">
            <!-- Recent Requests -->
            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold">Recent Certificate Requests</h6>
                        <div class="d-flex align-items-center">
                            <button id="refreshBtn" class="btn btn-sm btn-outline-secondary mr-2" onclick="loadRecentRequests()">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                            <a href="Issued Certificate.html" class="btn btn-sm btn-primary">View All</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover recent-table">
                                <thead>
                                    <tr>
                                        <th>Request_ID</th>
                                        <th>Certificate_Type</th>
                                        <th>Resident_Name</th>
                                        <th>Date_Requested</th>
                                         <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="recent-requests">
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <div class="loading-spinner"></div>
                                            Loading recent requests...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        
   
            
            <!-- Quick Actions -->
            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-white">
                        <h6 class="m-0 font-weight-bold">Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="quick-actions">
                            <a href="Residents.html" class="bg-primary mb-3">
                                <i class="fas fa-user-plus me-2"></i> Register New Resident
                            </a>
                            <a href="Request_Documents.php" class="bg-success mb-3">
                                <i class="fas fa-file me-2"></i> Create Document Request
                            </a>
                            <a href="Announcement.html" class="bg-info mb-3">
                                <i class="fas fa-bullhorn me-2"></i> Post Announcement
                            </a>
                            <a href="request_reports.php" class="bg-warning">
                                <i class="fas fa-chart-line me-2"></i> Generate Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Latest Announcements Row -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold">Latest Announcements</h6>
                        <a href="Announcement.html" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="card-body">
                        <div id="announcements-container">
                            <p class="text-center">Loading announcements...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load statistics
        loadStatistics();
        
        // Load charts
        loadChartData();
        
        // Load recent requests
        loadRecentRequests();
        
        // Load announcements
        loadAnnouncements();
    });
    
    function loadStatistics() {
        // Fetch dashboard statistics from the server via AJAX
        fetch('get_dashboard_stats.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('total-residents').textContent = data.residents || '0';
                document.getElementById('total-requests').textContent = data.requests || '0';
                document.getElementById('pending-requests').textContent = data.pending || '0';
                document.getElementById('total-announcements').textContent = data.announcements || '0';
            })
            .catch(error => {
                console.error('Error fetching statistics:', error);
                document.getElementById('total-residents').textContent = '0';
                document.getElementById('total-requests').textContent = '0';
                document.getElementById('pending-requests').textContent = '0';
                document.getElementById('total-announcements').textContent = '0';
            });
    }
    
    function loadChartData() {

        
        // Fetch chart data from the server
        fetch('get_chart_data.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                initCharts(data);
            })
            .catch(error => {
                console.error('Error fetching chart data:', error);
                
                // Initialize charts with default data if fetch fails
                initCharts({
                    gender: {male: 0, female: 0},
                    purok: {
                        labels: ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4', 'Purok 5', 
                                'Purok 6', 'Purok 7', 'Purok 8', 'Purok 9', 'Purok 10', 'Purok 11'],
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    }
                });
            });
    }
    
    function initCharts(chartData) {
        // Gender Distribution Chart
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        const genderChart = new Chart(genderCtx, {
            type: 'pie',
            data: {
                labels: ['Male', 'Female'],
                datasets: [{
                    data: [chartData.gender.male, chartData.gender.female],
                    backgroundColor: ['#28a745', '#e74a3b'],
                    hoverBackgroundColor: ['#2e8b57', '#d52a1a']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        
        // Purok Distribution Chart
        const purokCtx = document.getElementById('purokChart').getContext('2d');
        const purokChart = new Chart(purokCtx, {
            type: 'bar',
            data: {
                labels: chartData.purok.labels,
                datasets: [{
                    label: 'Number of Residents',
                    data: chartData.purok.data,
                    backgroundColor: '#36b9cc',
                    barThickness: 25
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
    
    function loadRecentRequests() {
        // Fetch recent document requests
        fetch('get_recent_requests.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const tableBody = document.getElementById('recent-requests');
                tableBody.innerHTML = '';
                
                if (data.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="4" class="text-center">No recent requests found</td></tr>';
                    return;
                }
                
                data.forEach(request => {
                    const row = document.createElement('tr');
                    
                    // Set status color
                    let statusClass = '';
                    if (request.status === 'Pending') {
                        statusClass = 'text-warning';
                    } else if (request.status === 'Approved') {
                        statusClass = 'text-success';
                    } else if (request.status === 'Rejected') {
                        statusClass = 'text-danger';
                    }
                    
                    row.innerHTML = `
                        <td>${request.document_type}</td>
                        <td>${request.resident_name}</td>
                        <td>${request.date_requested}</td>
                        <td><span class="${statusClass}">${request.status}</span></td>
                    `;
                    
                    tableBody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error fetching recent requests:', error);
                document.getElementById('recent-requests').innerHTML = 
                    '<tr><td colspan="4" class="text-center">Error loading requests</td></tr>';
            });
    }
    
  function loadAnnouncements() {
    // Fetch announcements from server
    fetch('get_announcements.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const container = document.getElementById('announcements-container');
            container.innerHTML = '';
            
            if (data.length === 0) {
                container.innerHTML = '<p class="text-center">No announcements found</p>';
                return;
            }
            
            // Create table structure
            const tableHTML = `
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <tr>
                                <th>EVENT NAME</th>
                                <th>DESCRIPTION</th>
                                <th>ACTIVITY TYPE</th>
                                <th>PARTICIPANTS</th>
                                <th>START DATE</th>
                                <th>END DATE</th>
                                <th>CREATED AT</th>
                            </tr>
                        </thead>
                        <tbody id="announcements-tbody">
                        </tbody>
                    </table>
                </div>
            `;
            
            container.innerHTML = tableHTML;
            const tbody = document.getElementById('announcements-tbody');
            
            data.forEach(announcement => {
                const row = document.createElement('tr');
                
                // Format dates
                const startDate = new Date(announcement.start);
                const formattedStartDate = startDate.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
                
                let formattedEndDate = '-';
                if (announcement.end && announcement.end !== announcement.start) {
                    const endDate = new Date(announcement.end);
                    formattedEndDate = endDate.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                }
                
                // Format created at date
                let formattedCreatedAt = '-';
                if (announcement.extendedProps.created_at) {
                    const createdDate = new Date(announcement.extendedProps.created_at);
                    formattedCreatedAt = createdDate.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                }
                
                // Format activity type
                const activityType = announcement.extendedProps.activity_type
                    ? announcement.extendedProps.activity_type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
                    : '-';
                
                // Format participant count
                const participantCount = announcement.extendedProps.participant_count || '-';
                
                row.innerHTML = `
                    <td><strong>${announcement.title}</strong></td>
                    <td class="text-truncate" style="max-width: 200px;" title="${announcement.extendedProps.description}">${announcement.extendedProps.description}</td>
                    <td><span class="badge bg-secondary">${activityType}</span></td>
                    <td>${participantCount}</td>
                    <td>${formattedStartDate}</td>
                    <td>${formattedEndDate}</td>
                    <td>${formattedCreatedAt}</td>
                `;
                
                tbody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error fetching announcements:', error);
            document.getElementById('announcements-container').innerHTML =
                '<p class="text-center">Error loading announcements</p>';
        });
}
    // Logout functionality
    document.getElementById("logoutButton").addEventListener("click", function () {
        sessionStorage.clear();
        fetch('logout.php')
            .then(() => {
                alert("Logging out...");
                window.location.href = "admin.html";
            })
            .catch(error => {
                console.error('Error during logout:', error);
                alert("Logging out...");
                window.location.href = "admin.html";
            });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const residentsCard = document.getElementById('residents-card');
    
    // Add click event listener
    residentsCard.addEventListener('click', function() {
        window.location.href = 'view_residents.php';
    });
    
    // Add hover effects
    residentsCard.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px)';
        this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.15)';
    });
    
    residentsCard.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
        this.style.boxShadow = '';
    });
});
</script>

 <script>
        // Function to load recent certificate requests
        function loadRecentRequests() {
            const tbody = document.getElementById('recent-requests');
            const refreshBtn = document.getElementById('refreshBtn');
            
            // Show loading state
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center">
                        <div class="loading-spinner"></div>
                        Loading recent requests...
                    </td>
                </tr>
            `;
            
            // Disable refresh button temporarily
            refreshBtn.disabled = true;
            refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
            
            // Make AJAX request to fetch recent requests
            fetch('get_recent_requests_DS.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.data && data.data.length > 0) {
                    let html = '';
                    data.data.forEach(request => {
                        // Format date
                        const requestDate = new Date(request.request_date);
                        const formattedDate = requestDate.toLocaleDateString('en-US', {
                            month: 'short',
                            day: '2-digit',
                            year: 'numeric'
                        });
                        
                        // Determine status badge class
                        let statusClass = 'badge-pending';
                        switch(request.status.toLowerCase()) {
                            case 'approved':
                                statusClass = 'badge-approved';
                                break;
                            case 'rejected':
                                statusClass = 'badge-rejected';
                                break;
                            case 'processing':
                                statusClass = 'badge-processing';
                                break;
                            default:
                                statusClass = 'badge-pending';
                        }
                        
                        html += `
                            <tr>
                                <td><span class="font-weight-bold text-primary">${request.request_id}</span></td>
                                <td>${request.certificate_type}</td>
                                <td>${request.resident_name}</td>
                                <td>${formattedDate}</td>
                                <td><span class="badge ${statusClass}">${request.status}</span></td>
                            </tr>
                        `;
                    });
                    tbody.innerHTML = html;
                } else {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                No recent certificate requests found
                            </td>
                        </tr>
                    `;
                }
            })
            .catch(error => {
                console.error('Error fetching recent requests:', error);
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                            Error loading recent requests. Please try again.
                        </td>
                    </tr>
                `;
            })
            .finally(() => {
                // Re-enable refresh button
                refreshBtn.disabled = false;
                refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh';
            });
        }
        
        // Load recent requests when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadRecentRequests();
            
            // Auto-refresh every 30 seconds
            setInterval(loadRecentRequests, 30000);
        });
    </script>

    <script>
$(document).ready(function() {
    $.ajax({
        url: 'get_population.php',
        method: 'GET',
        success: function(data) {
            $('#total-residents').text(data);
        },
        error: function() {
            $('#total-residents').text('Error loading');
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
    // for color in card
        const ESTIMATED_TOTAL = 5000;
        const REQUESTS_ESTIMATE = 50;
        const PENDING_ESTIMATE = 50;
        const ANNOUNCEMENTS_ESTIMATE = 50;
        
        function updateResidentCard(residentCount) {
            const percentage = Math.min((residentCount / ESTIMATED_TOTAL) * 100, 100);
            const card = document.getElementById('residents-card');
            const totalResidentsElement = document.getElementById('total-residents');
            const percentageElement = document.getElementById('percentage');
            const progressFill = document.getElementById('progress-fill');
            
            // Update numbers
            totalResidentsElement.textContent = residentCount.toLocaleString();
            percentageElement.textContent = Math.round(percentage) + '%';
            progressFill.style.width = percentage + '%';
            
            // Remove all progress classes
            card.classList.remove('progress-very-low', 'progress-low', 'progress-medium', 'progress-good', 'progress-high', 'progress-complete');
            
            // Add appropriate class based on percentage
            if (percentage >= 100) {
                card.classList.add('progress-complete');
            } else if (percentage >= 80) {
                card.classList.add('progress-high');
            } else if (percentage >= 60) {
                card.classList.add('progress-good');
            } else if (percentage >= 40) {
                card.classList.add('progress-medium');
            } else if (percentage >= 20) {
                card.classList.add('progress-low');
            } else {
                card.classList.add('progress-very-low');
            }
        }

        function updateRequestsCard(requestCount) {
            const percentage = Math.min((requestCount / REQUESTS_ESTIMATE) * 100, 100);
            const card = document.getElementById('requests-card');
            const percentageElement = document.getElementById('requests-percentage');
            const progressFill = document.getElementById('requests-progress-fill');
            
            percentageElement.textContent = Math.round(percentage) + '%';
            progressFill.style.width = percentage + '%';
            
            // Remove all progress classes
            card.classList.remove('progress-very-low', 'progress-low', 'progress-medium', 'progress-good', 'progress-high', 'progress-complete');
            
            // Add appropriate class based on percentage
            if (percentage >= 100) {
                card.classList.add('progress-complete');
            } else if (percentage >= 80) {
                card.classList.add('progress-high');
            } else if (percentage >= 60) {
                card.classList.add('progress-good');
            } else if (percentage >= 40) {
                card.classList.add('progress-medium');
            } else if (percentage >= 20) {
                card.classList.add('progress-low');
            } else {
                card.classList.add('progress-very-low');
            }
        }

        function updatePendingCard(pendingCount) {
            const percentage = Math.min((pendingCount / PENDING_ESTIMATE) * 100, 100);
            const card = document.getElementById('pending-card');
            const percentageElement = document.getElementById('pending-percentage');
            const progressFill = document.getElementById('pending-progress-fill');
            
            percentageElement.textContent = Math.round(percentage) + '%';
            progressFill.style.width = percentage + '%';
            
            // Remove all progress classes
            card.classList.remove('progress-very-low', 'progress-low', 'progress-medium', 'progress-good', 'progress-high', 'progress-complete');
            
            // Add appropriate class based on percentage
            if (percentage >= 100) {
                card.classList.add('progress-complete');
            } else if (percentage >= 80) {
                card.classList.add('progress-high');
            } else if (percentage >= 60) {
                card.classList.add('progress-good');
            } else if (percentage >= 40) {
                card.classList.add('progress-medium');
            } else if (percentage >= 20) {
                card.classList.add('progress-low');
            } else {
                card.classList.add('progress-very-low');
            }
        }

        function updateAnnouncementsCard(announcementCount) {
            const percentage = Math.min((announcementCount / ANNOUNCEMENTS_ESTIMATE) * 100, 100);
            const card = document.getElementById('announcements-card');
            const percentageElement = document.getElementById('announcements-percentage');
            const progressFill = document.getElementById('announcements-progress-fill');
            
            percentageElement.textContent = Math.round(percentage) + '%';
            progressFill.style.width = percentage + '%';
            
            // Remove all progress classes
            card.classList.remove('progress-very-low', 'progress-low', 'progress-medium', 'progress-good', 'progress-high', 'progress-complete');
            
            // Add appropriate class based on percentage
            if (percentage >= 100) {
                card.classList.add('progress-complete');
            } else if (percentage >= 80) {
                card.classList.add('progress-high');
            } else if (percentage >= 60) {
                card.classList.add('progress-good');
            } else if (percentage >= 40) {
                card.classList.add('progress-medium');
            } else if (percentage >= 20) {
                card.classList.add('progress-low');
            } else {
                card.classList.add('progress-very-low');
            }
        }
        
        function loadStatistics() {
            // Fetch dashboard statistics from the server via AJAX
            fetch('get_dashboard_stats.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const residentCount = parseInt(data.residents) || 0;
                    const requestCount = parseInt(data.requests) || 0;
                    const pendingCount = parseInt(data.pending) || 0;
                    const announcementCount = parseInt(data.announcements) || 0;
                    
                    // Update all cards with their respective progress systems
                    updateResidentCard(residentCount);
                    updateRequestsCard(requestCount);
                    updatePendingCard(pendingCount);
                    updateAnnouncementsCard(announcementCount);
                    
                    // Update card numbers with formatted display
                    document.getElementById('total-requests').textContent = requestCount.toLocaleString();
                    document.getElementById('pending-requests').textContent = pendingCount.toLocaleString();
                    document.getElementById('total-announcements').textContent = announcementCount.toLocaleString();
                })
                .catch(error => {
                    console.error('Error fetching statistics:', error);
                    updateResidentCard(0);
                    updateRequestsCard(0);
                    updatePendingCard(0);
                    updateAnnouncementsCard(0);
                    document.getElementById('total-requests').textContent = '0';
                    document.getElementById('pending-requests').textContent = '0';
                    document.getElementById('total-announcements').textContent = '0';
                });
        }
        
        // Demo function for testing (remove in production)
        function simulateData(residents, requests, pending, announcements) {
            updateResidentCard(residents);
            updateRequestsCard(requests);
            updatePendingCard(pending);
            updateAnnouncementsCard(announcements);
            
            // Update the displayed numbers
            document.getElementById('total-residents').textContent = residents.toLocaleString();
            document.getElementById('total-requests').textContent = requests.toLocaleString();
            document.getElementById('pending-requests').textContent = pending.toLocaleString();
            document.getElementById('total-announcements').textContent = announcements.toLocaleString();
        }
        
        // Load statistics when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadStatistics();
        });
    </script>

</body>
</html>
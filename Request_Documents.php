<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Documents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>      
        body {
           
                width: 100%;
            min-height: 100vh;
            overflow-x: hidden;
        }
        
           /* Sidebar Hover Effect */
           .sidebar-item {
                transition: background-color 0.3s ease-in-out, padding-left 0.3s ease-in-out;
            }
        
            .sidebar-item:hover {
                background-color: rgba(255, 255, 255, 0.2);
                padding-left: 10px;
            }
        
            /* Dropdown Menu - Show on Hover */
            .dropdown:hover .dropdown-menu {
                display: block;
                animation: fadeIn 0.3s ease-in-out, slideDown 0.3s ease-in-out;
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
        
            /* Keyframe Animations */
            @keyframes fadeIn {
                from {
                    opacity: 0;
                }
                to {
                    opacity: 1;
                }
            }
        
            @keyframes slideDown {
                from {
                    transform: translateY(-10px);
                }
                to {
                    transform: translateY(0);
                }
            }
             /* animate BSCIMS */
             .animated-title {
                transition: transform 0.3s ease, color 0.3s ease;
            }
        
            .animated-title:hover {
                transform: scale(1.1);
                color: #eff319; 
            }
             
             /* search name css */
            .search-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }
        
        .search-item {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .search-item:hover {
            background-color: #f8f9fa;
        }
        
        .search-item:last-child {
            border-bottom: none;
        }
        
        .no-results {
            padding: 10px 15px;
            color: #6c757d;
            font-style: italic;
        }
        
        .input-container {
            position: relative;
        }
        
        .loading {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            color: #6c757d;
        }

        

        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --danger-gradient: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --hover-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            animation: slideInUp 0.6s ease-out;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .card-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px 20px 0 0 !important;
            padding: 1.5rem;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .card-header:hover::before {
            left: 100%;
        }

        .card-title {
            font-weight: 600;
            font-size: 1.4rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .table {
            margin-bottom: 0;
            border-radius: 15px;
            overflow: hidden;
            animation: fadeIn 0.8s ease-out 0.3s both;
        }

        .table thead th {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
            padding: 1rem;
            border: none;
            position: relative;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            position: relative;
        }

        .table tbody tr:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            transform: scale(1.01);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border: none;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .btn {
            border-radius: 25px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.5rem 1.2rem;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.3s, height 0.3s;
        }

        .btn:hover::before {
            width: 200px;
            height: 200px;
        }

        .btn-outline-primary {
            background: var(--primary-gradient);
            color: white;
            border: 2px solid transparent;
        }

        .btn-outline-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-outline-danger {
            background: var(--danger-gradient);
            color: white;
            border: 2px solid transparent;
        }

        .btn-outline-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 154, 158, 0.4);
        }

        .badge {
            border-radius: 20px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            animation: pulse 2s infinite;
        }

        .bg-warning {
            background: var(--warning-gradient) !important;
            color: white !important;
        }

        .bg-success {
            background: var(--success-gradient) !important;
            color: white !important;
        }

        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            animation: modalSlideIn 0.4s ease-out;
        }

        .modal-header {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 1.5rem 2rem;
        }

        .modal-title {
            font-weight: 600;
            font-size: 1.3rem;
        }

        .form-control {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.8);
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.2);
            transform: scale(1.02);
            background: white;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .btn-group {
            gap: 0.5rem;
        }

        .btn-sm {
            padding: 0.4rem 1rem;
            font-size: 0.85rem;
        }

        .table-responsive {
            border-radius: 15px;
            background: white;
        }

        .text-muted {
            color: #6c757d !important;
            font-style: italic;
        }

        .loading-spinner {
            display: none;
            text-align: center;
            padding: 2rem;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: 0.3rem;
        }

        /* Animations */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes rowSlideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .table tbody tr {
            animation: rowSlideIn 0.5s ease-out;
        }

        .table tbody tr:nth-child(1) { animation-delay: 0.1s; }
        .table tbody tr:nth-child(2) { animation-delay: 0.2s; }
        .table tbody tr:nth-child(3) { animation-delay: 0.3s; }
        .table tbody tr:nth-child(4) { animation-delay: 0.4s; }
        .table tbody tr:nth-child(5) { animation-delay: 0.5s; }

        /* Hover effects for interactive elements */
        .form-check-input:checked {
            background: var(--primary-gradient);
            border-color: #667eea;
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: white;
            opacity: 0.8;
            transition: all 0.3s ease;
        }

        .btn-close:hover {
            opacity: 1;
            transform: rotate(90deg);
        }

        /* Custom scrollbar */
        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: var(--primary-gradient);
            border-radius: 10px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #5a6fd8;
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
            background: linear-gradient(135deg, #6e6e6e 0%, #6e6e6e 100%);
            color: #fff;
            
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
<nav class="text-white vh-100 p-3" style="width: 235px; background-color: #31363F;">

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
                <a class="nav-link dropdown-toggle text-white d-flex align-items-center sidebar-item" id="citizen-requests-link" data-page="citizen-requests">
                    <i class="fas fa-file-alt me-2"></i> Citizen Requests
                   
                    <!-- Notification Badge -->
                    <span class="notification-badge" id="request-notification-badge">0</span>
                     <div class="page-indicator"></div>
                </a>
                <ul class="dropdown-menu animate-dropdown">
                    <li><a class="dropdown-item" href="Request_Documents.php" data-page="request-documents">Request Documents</a></li>
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
                 <div class="flex-grow-1 p-4" style="margin-left: -26px; margin-right: -15px; margin-top: -24px; overflow-y: auto; height: 100vh;">
                    <!-- Header -->
                    <div class="d-flex  align-items-center text-white p-3 " style="background-color: #222831; margin-right: -24px; border-radius: 0px;">
                        <img src="logo.png" alt="Logo" style="width: 40px; height: 40px; margin-right: 10px; ">
                        <h1 class="fs-5 mb-1">Barangay San Carlos, City of Valencia, Province of Bukidnon</h1>
        
      </div>
     </head>
    <body>
    
    <!-- Main Content -->
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-dark mb-4"><i class="fas fa-file-alt me-2"></i>Request Documents</h2>      
                </div>

                
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs" id="documentTabs" role="tablist" style="margin-left: 12px;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="request-tab" data-bs-toggle="tab" data-bs-target="#request-tab-pane" type="button" role="tab" aria-controls="request-tab-pane" aria-selected="true">Request Certificate</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="add-tab" data-bs-toggle="tab" data-bs-target="#add-tab-pane" type="button" role="tab" aria-controls="add-tab-pane" aria-selected="false">Add Certificate Type</button>
                    </li>
                     <li class="nav-item" role="presentation">
                   <button class="nav-link" id="manage-tab" data-bs-toggle="tab" data-bs-target="#manage-tab-pane" type="button" role="tab" aria-controls="manage-tab-pane" aria-selected="false">Manage Certificate</button>
                </li></ul>
                
                
               <!-- Tabs Content -->
            <div class="tab-content" id="documentTabsContent">

                <!-- Request Certificate Tab -->
<div class="tab-pane fade show active p-4 bg-white border border-top-0 rounded-bottom" id="request-tab-pane" role="tabpanel" aria-labelledby="request-tab" tabindex="0">
     <form id="requestForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="issuedTo" class="form-label">Resident Name</label>
                        <div class="input-container">
                            <input type="text" 
                                   class="form-control" 
                                   id="issuedTo" 
                                   name="issuedTo" 
                                   placeholder="Type to search resident name..." 
                                   autocomplete="off">
                            <div class="loading" id="loadingIndicator" style="display: none;">
                                Searching...
                            </div>
                            <div class="search-dropdown" id="searchDropdown"></div>
                        </div>
                        <input type="hidden" id="residentId" name="residentId" value="">
                    </div>
                </div>


        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="certificateType" class="form-label">Certificate Type</label>
                <select class="form-select" id="certificateType" name="certificateType" required>
                    <option value="" selected disabled>Select Certificate</option>
                    <!-- Options will be populated dynamically -->
                </select>
            </div>

           <!-- Add this button next to your purpose dropdown -->
<div class="col-md-6 mb-3" >
    <div class="d-flex align-items-end" >
        <div class="flex-grow-1 me-2" >
            <label for="purpose" class="form-label">Purpose</label>
            <select class="form-select" id="purpose" name="purpose" required>
                <option value="" selected disabled>Select Purpose</option>
                <option value="Custom Purposes">Custom Purposes</option>
                <option value="Overseas Employment">Overseas Employment</option>
                <option value="Loan Application">Loan Application</option>
                <option value="PHILIPPINE I.D SYSTEM REQUIREMENTS">PHILIPPINE I.D SYSTEM REQUIREMENTS</option>
                <option value="Medical Assistance">Medical Assistance</option>
                <option value="Burial Assistance">Burial Assistance</option>
                <option value="Educational Assistance">Educational Assistance</option>
                <option value="Medicine Assistance">Medicine Assistance</option>
                <option value="Local Employment">Local Employment</option>
                <option value="Vehicle Assistance">Vehicle Assistance</option>
                <option value="PAG-IBIG REQUIREMENTS">PAG-IBIG REQUIREMENTS</option>
                <option value="VACCINATION REQUIREMENTS">VACCINATION REQUIREMENTS</option>
                <option value="Scholarship Requirements (UNIFAST)">Scholarship Requirements (UNIFAST)</option>
                <option value="BUSINESS REGISTRATION">BUSINESS REGISTRATION</option>
                <option value="MOTORELA PERMIT/REGISTRATION REQUIREMENTS">MOTORELA PERMIT/REGISTRATION REQUIREMENTS</option>
                <option value="IDENTIFICATIONAL PURPOSES">IDENTIFICATIONAL PURPOSES</option>
            </select>
        </div>
        <div>
            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#purposeModal" >
                <i class="fas fa-cog" ></i>
            </button>
        </div>
    </div>
</div>

<!-- Purpose Management Modal -->
<div class="modal fade" id="purposeModal" tabindex="-1" aria-labelledby="purposeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="purposeModalLabel">Manage Purposes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add New Purpose -->
                <div class="mb-4">
                    <h6>Add New Purpose</h6>
                    <div class="input-group">
                        <input type="text" class="form-control" id="newPurpose" placeholder="Enter purpose name">
                        <button class="btn btn-success" type="button" id="addPurposeBtn">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>
                </div>
                
                <!-- Current Purposes List -->
                <div>
                    <h6>Current Purposes</h6>
                    <div id="purposeList" class="list-group">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

        <!-- Add this button next to your signatory dropdown -->
<div class="mb-3">
    <div class="d-flex align-items-end">
        <div class="flex-grow-1 me-2">
            <label for="signatory" class="form-label">Signatory</label>
            <select class="form-select" id="signatory" name="signatory" required>
                <option value="" selected disabled>Select Signatory</option>
                <option value="Hon. Junnie P. Gabucan">Hon. Junnie P. Gabucan</option>
                <option value="Hon. Jacqueline O. Canete">Hon. Jacqueline O. Canete</option>
                <option value="Hon. Ceasar Ayan A. Sibog">Hon. Ceasar Ayan A. Sibog</option>
                <option value="Hon. Lydia O. Devila">Hon. Lydia O. Devila</option>
                <option value="Hon. Marilou Q. Erazo">Hon. Marilou Q. Erazo</option>
                <option value="Hon. Barry C. Denuyo">Hon. Barry C. Denuyo</option>
                <option value="Hon. Dizon P. Tagupa">Hon. Dizon P. Tagupa</option>
                <option value="Hon. Christian Jay Malunis">Hon. Christian Jay Malunis</option>
                <option value="Hon. Geralyn C. Baliling">Hon. Geralyn C. Baliling</option>
                <option value="Hon. Rodel Faye L. Solinap">Hon. Rodel Faye L. Solinap</option>
            </select>
        </div>
        <div>
            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#signatoryModal">
                <i class="fas fa-cog"></i>
            </button>
        </div>
    </div>
</div>

<!-- Signatory Management Modal -->
<div class="modal fade" id="signatoryModal" tabindex="-1" aria-labelledby="signatoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signatoryModalLabel">Manage Signatories</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add New Signatory -->
                <div class="mb-4">
                    <h6>Add New Signatory</h6>
                    <div class="input-group">
                        <input type="text" class="form-control" id="newSignatory" placeholder="Enter signatory name">
                        <button class="btn btn-success" type="button" id="addSignatoryBtn">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>
                </div>
                
                <!-- Current Signatories List -->
                <div>
                    <h6>Current Signatories</h6>
                    <div id="signatoryList" class="list-group">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
        
        <div class="d-flex justify-content-end">
            <button type="reset" class="btn btn-light me-2">
                <i class="fas fa-times me-1"></i> Cancel
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane me-1"></i> Submit Request
            </button>
        </div>
    </form>

 
</div>
            </div>    
                <!-- Add Certificate Type Tab -->
                <div class="tab-pane fade p-4 bg-white border border-top-0 rounded-bottom" id="add-tab-pane" role="tabpanel" aria-labelledby="add-tab" tabindex="0">
                    <form id="addCertificateForm" method="post" action="add_certificate_handler.php" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="certificateName" class="form-label">Certificate Name</label>
                            <input type="text" class="form-control" id="certificateName" name="certificateName" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="certificateDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="certificateDescription" name="certificateDescription" rows="2"></textarea>
                        </div>
                        
                        
                        
                        <div class="mb-3">
                            <label class="form-label">Required Fields</label>
                            <div class="row" id="fieldContainer">
                                <div class="col-md-11 mb-2">
                                    <input type="text" class="form-control" placeholder="Field name (e.g. Full Name, Address)" name="fields[]">
                                </div>
                                <div class="col-md-1 mb-2">
                                    <button type="button" class="btn btn-success w-100" id="addField">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="requiresPayment" name="requiresPayment">
                                <label class="form-check-label" for="requiresPayment">
                                    Requires Payment
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3 payment-details" style="display: none;">
                            <label for="feeAmount" class="form-label">Fee Amount (₱)</label>
                            <input type="number" class="form-control" id="feeAmount" name="feeAmount" placeholder="0.00" step="0.01">
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="reset" class="btn btn-light me-2">
                                <i class="fas fa-times me-1"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-plus me-1"></i> Add Certificate Type
                            </button>
                        </div>
                    </form>
                    <!-- Success/Error Alert -->
                    <div id="formAlert" class="alert mt-3" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>
  
 <!-- Manage Certificate Tab Content -->
    <div class="tab-pane fade show active" id="manage-tab-pane" role="tabpanel" aria-labelledby="manage-tab" tabindex="0" style="margin-top: -8px;">
        <div class="container-fluid mt-4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-certificate me-2"></i>
                                Manage Certificate Types
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="loading-spinner" id="loadingSpinner">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2 text-muted">Loading certificates...</p>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="certificateTable">
                                    <thead class="table-dark">
                                        <tr>                                 
                                            <th><i class="fas fa-id-card me-2"></i>Certificate Name</th>                                  
                                            <th><i class="fas fa-credit-card me-2"></i>Payment Required</th>
                                            <th><i class="fas fa-money-bill me-2"></i>Fee Amount</th>                                
                                            <th><i class="fas fa-calendar me-2"></i>Created</th>
                                            <th><i class="fas fa-cogs me-2"></i>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="certificateTableBody">                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   
    
    <!-- Edit Certificate Modal -->
    <div class="modal fade" id="editCertificateModal" tabindex="-1" aria-labelledby="editCertificateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCertificateModalLabel">
                        <i class="fas fa-edit me-2"></i>Edit Certificate Type
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCertificateForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="editCertificateId" name="certificate_id">
                        <input type="hidden" id="editExistingTemplate" name="existing_template">
                        
                        <div class="mb-4">
                            <label for="editCertificateName" class="form-label">
                                <i class="fas fa-certificate me-2"></i>Certificate Name
                            </label>
                            <input type="text" class="form-control" id="editCertificateName" name="certificateName" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="editCertificateDescription" class="form-label">
                                <i class="fas fa-align-left me-2"></i>Description
                            </label>
                            <textarea class="form-control" id="editCertificateDescription" name="certificateDescription" rows="3" required></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label for="editCertificateTemplate" class="form-label">
                                <i class="fas fa-file me-2"></i>Certificate Template (optional)
                            </label>
                            <input type="file" class="form-control" id="editCertificateTemplate" name="certificateTemplate" accept=".pdf,.doc,.docx">
                            <div class="form-text">Current template: <span id="currentTemplate" class="fw-bold text-primary">None</span></div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="editRequiresPayment" name="requiresPayment">
                                <label class="form-check-label" for="editRequiresPayment">
                                    <i class="fas fa-money-bill me-2"></i>Requires Payment
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-4" id="editFeeAmountGroup" style="display: none;">
                            <label for="editFeeAmount" class="form-label">
                                <i class="fas fa-peso-sign me-2"></i>Fee Amount (₱)
                            </label>
                            <input type="number" class="form-control" id="editFeeAmount" name="feeAmount" step="0.01" min="0">
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="fas fa-list me-2"></i>Required Fields
                            </label>
                            <div id="editFieldsContainer">
                                <!-- Dynamic fields will be added here -->
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="addEditField()">
                                <i class="fas fa-plus me-1"></i>Add Field
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Certificate
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
          


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        window.onload = function () {
            const params = new URLSearchParams(window.location.search);
            const issuedTo = params.get('issuedTo');
            if (issuedTo) {
                document.getElementById('issuedTo').value = issuedTo;
            }
        }
    </script>

    <script>
        // Toggle payment details when checkbox is clicked
        document.getElementById('requiresPayment').addEventListener('change', function() {
            const paymentDetails = document.querySelector('.payment-details');
            if (this.checked) {
                paymentDetails.style.display = 'block';
            } else {
                paymentDetails.style.display = 'none';
            }
        });
        
        // Add new field functionality
        document.getElementById('addField').addEventListener('click', function() {
            const fieldContainer = document.getElementById('fieldContainer');
            const newRow = document.createElement('div');
            newRow.className = 'row mt-2';
            
            newRow.innerHTML = `
                <div class="col-md-11 mb-2">
                    <input type="text" class="form-control" placeholder="Field name" name="fields[]">
                </div>
                <div class="col-md-1 mb-2">
                    <button type="button" class="btn btn-danger w-100 remove-field">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            `;           
            fieldContainer.appendChild(newRow);
            
            // Add event listener to remove button
            newRow.querySelector('.remove-field').addEventListener('click', function() {
                fieldContainer.removeChild(newRow);
            });
        });
      
    </script>



    <script>
       // Toast notification function
function showToast(message, type = 'success') {
    // Remove existing toast if any
    const existingToast = document.querySelector('.custom-toast');
    if (existingToast) {
        existingToast.remove();
    }

    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'custom-toast';
    
    // Set styles based on type
    const bgColor = type === 'success' ? '#28a745' : '#dc3545';
    const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
    
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: ${bgColor};
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        z-index: 9999;
        max-width: 400px;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease-in-out;
    `;
    
    toast.innerHTML = `
        <i class="${icon}"></i>
        <span>${message}</span>
    `;
    
    // Add to document
    document.body.appendChild(toast);
    
    // Trigger animation
    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 5000);
}

// Updated form submission handler
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("requestForm").addEventListener("submit", function (e) {
        e.preventDefault();
        
        // Show loading indicator or disable submit button
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        
        // Get form values
        const issuedTo = document.getElementById("issuedTo").value;
        const certificateType = document.getElementById("certificateType").value;
        const purpose = document.getElementById("purpose").value;
        const signatory = document.getElementById("signatory").value;
        
        // Log form values to console for debugging
        console.log("Form data:", {
            issuedTo,
            certificateType,
            purpose,
            signatory
        });
        
        // Create FormData object
        const formData = new FormData();
        formData.append("issuedTo", issuedTo);
        formData.append("certificateType", certificateType);
        formData.append("purpose", purpose);
        formData.append("signatory", signatory);
        
        // Send data to server
        fetch("handle_certificate_request.php", {
            method: "POST",
            body: formData
        })
        .then(response => {
            console.log("Response status:", response.status);
            // Check if response is OK
            if (!response.ok) {
                throw new Error(`Server returned ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Server response:", data);
            
            if (data.status === "success") {
                // Show success toast instead of alert
                showToast(data.message, 'success');
                
                // Reset form
                document.getElementById("requestForm").reset();
                
                // Keep the resident name if it was pre-filled
                if (new URLSearchParams(window.location.search).get('issuedTo')) {
                    document.getElementById('issuedTo').value = new URLSearchParams(window.location.search).get('issuedTo');
                }
            } else {
                // Show error toast instead of alert
                showToast("Error: " + data.message, 'error');
            }
        })
        .catch(error => {
            console.error("Error details:", error);
            // Show error toast instead of alert
            showToast("An error occurred while submitting the request: " + error.message, 'error');
        })
        .finally(() => {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        });
    });
});
    </script>
 
 <script>
    // Add this to your existing JavaScript code
document.addEventListener('DOMContentLoaded', function() {
    // Form submission handling
    const addCertificateForm = document.getElementById('addCertificateForm');
    if (addCertificateForm) {
        addCertificateForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(addCertificateForm);
            
            // Submit via AJAX
            fetch('add_certificate_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const formAlert = document.getElementById('formAlert');
                
                if (data.status === 'success') {
                    // Show success message
                    formAlert.classList.add('alert-success');
                    formAlert.classList.remove('alert-danger');
                    formAlert.textContent = data.message;
                    formAlert.style.display = 'block';
                    
                    // Reset form
                    addCertificateForm.reset();
                    
                    // Refresh page after 2 seconds to update certificate types dropdown
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    // Show error message
                    formAlert.classList.add('alert-danger');
                    formAlert.classList.remove('alert-success');
                    formAlert.textContent = data.message;
                    formAlert.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Show error message
                const formAlert = document.getElementById('formAlert');
                formAlert.classList.add('alert-danger');
                formAlert.classList.remove('alert-success');
                formAlert.textContent = 'An error occurred while processing your request.';
                formAlert.style.display = 'block';
            });
        });
    }
});
 </script>

<script>
 document.addEventListener("DOMContentLoaded", function () {
    fetchCertificateTypes();
});

function fetchCertificateTypes() {
    fetch('load_certificate_types.php') // Fetch from PHP script
        .then(response => response.json()) // Convert response to JSON
        .then(data => {
            let certificateTypeSelect = document.getElementById("certificateType");
            certificateTypeSelect.innerHTML = '<option value="" selected disabled>Select Certificate</option>'; // Reset options
            
            data.forEach(type => {
                let option = document.createElement("option");
                option.value = type.id;
                option.textContent = type.certificate_name;
                certificateTypeSelect.appendChild(option);
            });
        })
        .catch(error => console.error("Error fetching certificate types:", error));
}

    </script>

    <script>
document.addEventListener("DOMContentLoaded", function () {
    fetchCertificateTypes();
    loadRequests();
});

// Fetch certificate types from database
function fetchCertificateTypes() {
    fetch('load_certificate_types.php')
        .then(response => response.json())
        .then(data => {
            let certificateTypeSelect = document.getElementById("certificateType");
            certificateTypeSelect.innerHTML = '<option value="" selected disabled>Select Certificate</option>';
            data.forEach(type => {
                let option = document.createElement("option");
                option.value = type.certificate_name;
                option.textContent = type.certificate_name;
                certificateTypeSelect.appendChild(option);
            });
        })
        .catch(error => console.error("Error fetching certificate types:", error));
}

// Fetch submitted requests
function loadRequests() {
    fetch('fetch_certificate_requests.php')
        .then(response => response.json())
        .then(data => {
            let requestTableBody = document.getElementById("requestTableBody");
            requestTableBody.innerHTML = ''; // Clear table before adding new rows

            data.forEach(request => {
                let row = `
                    <tr>
                        <td>${request.request_id}</td>
                        <td>${request.resident_name}</td>
                        <td>${request.certificate_type}</td>
                        <td>${request.purpose}</td>
                        <td>${request.signatory}</td>                 
                        <td>${request.request_date}</td>
                      
                        </tr>
                `;
                requestTableBody.innerHTML += row;
            });
        })
        .catch(error => console.error("Error fetching requests:", error));
}


</script>



   <script>
// Signatory Management with Persistence
document.addEventListener('DOMContentLoaded', function() {
    initializeSignatories();
    loadSignatoryList();
    
    // Add new signatory
    document.getElementById('addSignatoryBtn').addEventListener('click', function() {
        const newSignatory = document.getElementById('newSignatory').value.trim();
        if (newSignatory) {
            addSignatoryToDropdown(newSignatory);
            saveSignatoryToStorage(newSignatory);
            document.getElementById('newSignatory').value = '';
            loadSignatoryList();
        }
    });

    // Allow Enter key to add signatory
    document.getElementById('newSignatory').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('addSignatoryBtn').click();
        }
    });
});

// Default signatories that should always be available
const defaultSignatories = [
    "Hon. Junnie P. Gabucan",
    "Hon. Jacqueline O. Canete",
    "Hon. Ceasar Ayan A. Sibog",
    "Hon. Lydia O. Devila",
    "Hon. Marilou Q. Erazo",
    "Hon. Barry C. Denuyo",
    "Hon. Dizon P. Tagupa",
    "Hon. Christian Jay Malunis",
    "Hon. Geralyn C. Baliling",
    "Hon. Rodel Faye L. Solinap"
];

function initializeSignatories() {
    const signatorySelect = document.getElementById('signatory');
    
    // Clear existing options except placeholder
    signatorySelect.innerHTML = '<option value="" selected disabled>Select Signatory</option>';
    
    // Get removed default signatories
    const removedDefaults = getRemovedDefaultSignatoriesFromStorage();
    
    // Add default signatories (except removed ones)
    defaultSignatories.forEach(signatory => {
        if (!removedDefaults.includes(signatory)) {
            const option = document.createElement('option');
            option.value = signatory;
            option.textContent = signatory;
            signatorySelect.appendChild(option);
        }
    });
    
    // Add custom signatories from storage
    const customSignatories = getCustomSignatoriesFromStorage();
    customSignatories.forEach(signatory => {
        const option = document.createElement('option');
        option.value = signatory;
        option.textContent = signatory;
        signatorySelect.appendChild(option);
    });
}

function getRemovedDefaultSignatoriesFromStorage() {
    try {
        const stored = localStorage.getItem('removedDefaultSignatories');
        return stored ? JSON.parse(stored) : [];
    } catch (e) {
        console.error('Error reading removed default signatories from storage:', e);
        return [];
    }
}

function removeDefaultSignatoryFromStorage(signatory) {
    try {
        const removedDefaults = getRemovedDefaultSignatoriesFromStorage();
        if (!removedDefaults.includes(signatory)) {
            removedDefaults.push(signatory);
            localStorage.setItem('removedDefaultSignatories', JSON.stringify(removedDefaults));
        }
    } catch (e) {
        console.error('Error saving removed default signatory to storage:', e);
    }
}

function getCustomSignatoriesFromStorage() {
    try {
        const stored = localStorage.getItem('customSignatories');
        return stored ? JSON.parse(stored) : [];
    } catch (e) {
        console.error('Error reading custom signatories from storage:', e);
        return [];
    }
}

function saveSignatoryToStorage(signatory) {
    try {
        const customSignatories = getCustomSignatoriesFromStorage();
        if (!customSignatories.includes(signatory)) {
            customSignatories.push(signatory);
            localStorage.setItem('customSignatories', JSON.stringify(customSignatories));
        }
    } catch (e) {
        console.error('Error saving signatory to storage:', e);
    }
}

function removeSignatoryFromStorage(signatory) {
    try {
        const customSignatories = getCustomSignatoriesFromStorage();
        const updatedSignatories = customSignatories.filter(s => s !== signatory);
        localStorage.setItem('customSignatories', JSON.stringify(updatedSignatories));
    } catch (e) {
        console.error('Error removing signatory from storage:', e);
    }
}

function loadSignatoryList() {
    const signatorySelect = document.getElementById('signatory');
    const signatoryList = document.getElementById('signatoryList');
    signatoryList.innerHTML = '';
    
    // Get all options except the first placeholder
    for (let i = 1; i < signatorySelect.options.length; i++) {
        const option = signatorySelect.options[i];
        const isDefault = defaultSignatories.includes(option.value);
        const listItem = document.createElement('div');
        listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
        
        // All signatories can now be deleted
        listItem.innerHTML = `
            <span>${option.text} ${isDefault ? '<small class="text-muted">(Original)</small>' : ''}</span>
            <button class="btn btn-danger btn-sm" onclick="removeSignatory('${option.value}')">
                <i class="fas fa-trash"></i>
            </button>
        `;
        signatoryList.appendChild(listItem);
    }
}

function addSignatoryToDropdown(signatoryName) {
    const signatorySelect = document.getElementById('signatory');
    
    // Check if signatory already exists
    for (let i = 0; i < signatorySelect.options.length; i++) {
        if (signatorySelect.options[i].value === signatoryName) {
            alert('This signatory already exists!');
            return;
        }
    }
    
    const option = document.createElement('option');
    option.value = signatoryName;
    option.textContent = signatoryName;
    signatorySelect.appendChild(option);
}

function removeSignatory(signatoryValue) {
    const isDefault = defaultSignatories.includes(signatoryValue);
    const confirmMessage = isDefault 
        ? 'Are you sure you want to remove this signatory?.'
        : 'Are you sure you want to remove this signatory?';
    
    if (confirm(confirmMessage)) {
        const signatorySelect = document.getElementById('signatory');
        for (let i = 0; i < signatorySelect.options.length; i++) {
            if (signatorySelect.options[i].value === signatoryValue) {
                signatorySelect.remove(i);
                break;
            }
        }
        
        if (isDefault) {
            // Remove from defaults and add to removed list
            removeDefaultSignatoryFromStorage(signatoryValue);
        } else {
            // Remove custom signatory
            removeSignatoryFromStorage(signatoryValue);
        }
        loadSignatoryList();
    }
}
</script>


<script>
// Purpose Management JavaScript with Persistence
document.addEventListener('DOMContentLoaded', function() {
    initializePurposes();
    loadPurposeList();
    
    // Add new purpose
    document.getElementById('addPurposeBtn').addEventListener('click', function() {
        const newPurpose = document.getElementById('newPurpose').value.trim();
        if (newPurpose) {
            addPurposeToDropdown(newPurpose);
            savePurposeToStorage(newPurpose);
            document.getElementById('newPurpose').value = '';
            loadPurposeList();
        }
    });
});

// Default purposes that should always be available
const defaultPurposes = [
    "Custom Purposes",
    "Overseas Employment",
    "Loan Application",
    "PHILIPPINE I.D SYSTEM REQUIREMENTS",
    "Medical Assistance",
    "Burial Assistance",
    "Educational Assistance",
    "Medicine Assistance",
    "Local Employment",
    "Vehicle Assistance",
    "PAG-IBIG REQUIREMENTS",
    "VACCINATION REQUIREMENTS",
    "Scholarship Requirements (UNIFAST)",
    "BUSINESS REGISTRATION",
    "MOTORELA PERMIT/REGISTRATION REQUIREMENTS",
    "IDENTIFICATIONAL PURPOSES"
];

function initializePurposes() {
    const purposeSelect = document.getElementById('purpose');
    
    // Clear existing options except placeholder
    purposeSelect.innerHTML = '<option value="" selected disabled>Select Purpose</option>';
    
    // Add default purposes
    defaultPurposes.forEach(purpose => {
        const option = document.createElement('option');
        option.value = purpose;
        option.textContent = purpose;
        purposeSelect.appendChild(option);
    });
    
    // Add custom purposes from storage
    const customPurposes = getCustomPurposesFromStorage();
    customPurposes.forEach(purpose => {
        const option = document.createElement('option');
        option.value = purpose;
        option.textContent = purpose;
        purposeSelect.appendChild(option);
    });
}

function getCustomPurposesFromStorage() {
    try {
        const stored = localStorage.getItem('customPurposes');
        return stored ? JSON.parse(stored) : [];
    } catch (e) {
        console.error('Error reading custom purposes from storage:', e);
        return [];
    }
}

function savePurposeToStorage(purpose) {
    try {
        const customPurposes = getCustomPurposesFromStorage();
        if (!customPurposes.includes(purpose)) {
            customPurposes.push(purpose);
            localStorage.setItem('customPurposes', JSON.stringify(customPurposes));
        }
    } catch (e) {
        console.error('Error saving purpose to storage:', e);
    }
}

function removePurposeFromStorage(purpose) {
    try {
        const customPurposes = getCustomPurposesFromStorage();
        const updatedPurposes = customPurposes.filter(p => p !== purpose);
        localStorage.setItem('customPurposes', JSON.stringify(updatedPurposes));
    } catch (e) {
        console.error('Error removing purpose from storage:', e);
    }
}

function loadPurposeList() {
    const purposeSelect = document.getElementById('purpose');
    const purposeList = document.getElementById('purposeList');
    purposeList.innerHTML = '';
    
    // Get all options except the first placeholder
    for (let i = 1; i < purposeSelect.options.length; i++) {
        const option = purposeSelect.options[i];
        const isDefault = defaultPurposes.includes(option.value);
        const listItem = document.createElement('div');
        listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
        
        if (isDefault) {
            // Default purposes cannot be deleted
            listItem.innerHTML = `
                <span>${option.text}</span>
                <span class="badge bg-secondary">Default</span>
            `;
        } else {
            // Custom purposes can be deleted
            listItem.innerHTML = `
                <span>${option.text}</span>
                <button class="btn btn-danger btn-sm" onclick="removePurpose('${option.value}')">
                    <i class="fas fa-trash"></i>
                </button>
            `;
        }
        purposeList.appendChild(listItem);
    }
}

function addPurposeToDropdown(purposeName) {
    const purposeSelect = document.getElementById('purpose');
    
    // Check if purpose already exists
    for (let i = 0; i < purposeSelect.options.length; i++) {
        if (purposeSelect.options[i].value === purposeName) {
            alert('This purpose already exists!');
            return;
        }
    }
    
    const option = document.createElement('option');
    option.value = purposeName;
    option.textContent = purposeName;
    purposeSelect.appendChild(option);
}

function removePurpose(purposeValue) {
    // Check if it's a default purpose
    if (defaultPurposes.includes(purposeValue)) {
        alert('Cannot delete default purposes!');
        return;
    }
    
    if (confirm('Are you sure you want to remove this purpose?')) {
        const purposeSelect = document.getElementById('purpose');
        for (let i = 0; i < purposeSelect.options.length; i++) {
            if (purposeSelect.options[i].value === purposeValue) {
                purposeSelect.remove(i);
                break;
            }
        }
        removePurposeFromStorage(purposeValue);
        loadPurposeList();
    }
}

// Allow Enter key to add purpose
document.getElementById('newPurpose').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        document.getElementById('addPurposeBtn').click();
    }
});
</script>

 <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('issuedTo');
            const searchDropdown = document.getElementById('searchDropdown');
            const loadingIndicator = document.getElementById('loadingIndicator');
            const residentIdInput = document.getElementById('residentId');
            
            let searchTimeout;
            
            // Search functionality
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                // Clear previous timeout
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    hideDropdown();
                    return;
                }
                
                // Debounce search to avoid too many requests
                searchTimeout = setTimeout(() => {
                    searchResidents(query);
                }, 300);
            });
            
            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.input-container')) {
                    hideDropdown();
                }
            });
            
            // Prevent form submission on Enter in search field
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                }
            });
            
            function searchResidents(query) {
                showLoading();
                
                // Create FormData for the AJAX request
                const formData = new FormData();
                formData.append('action', 'search');
                formData.append('query', query);
                
                fetch('search_residents.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    displayResults(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    hideLoading();
                    showError('An error occurred while searching.');
                });
            }
            
            function displayResults(residents) {
                searchDropdown.innerHTML = '';
                
                if (residents.length === 0) {
                    searchDropdown.innerHTML = '<div class="no-results">No residents found</div>';
                } else {
                    residents.forEach(resident => {
                        const item = document.createElement('div');
                        item.className = 'search-item';
                        item.innerHTML = `
                            <strong>${resident.full_name}</strong><br>
                            <small class="text-muted">
                                ${resident.sitio ? resident.sitio + ', ' : ''}
                                ${resident.purok ? ' ' + resident.purok : ''}
                            </small>
                        `;
                        
                        item.addEventListener('click', function() {
                            selectResident(resident);
                        });
                        
                        searchDropdown.appendChild(item);
                    });
                }
                
                showDropdown();
            }
            
            function selectResident(resident) {
                searchInput.value = resident.full_name;
                residentIdInput.value = resident.id;
                hideDropdown();
            }
            
            function showDropdown() {
                searchDropdown.style.display = 'block';
            }
            
            function hideDropdown() {
                searchDropdown.style.display = 'none';
            }
            
            function showLoading() {
                loadingIndicator.style.display = 'block';
            }
            
            function hideLoading() {
                loadingIndicator.style.display = 'none';
            }
            
            function showError(message) {
                searchDropdown.innerHTML = `<div class="no-results text-danger">${message}</div>`;
                showDropdown();
            }
            
            
        });
    </script>

   
<script>
// JavaScript functions for manage certificate functionality

// Load certificates when the manage tab is clicked
document.addEventListener('DOMContentLoaded', function() {
    // Load certificates when manage tab is shown
    const manageTab = document.getElementById('manage-tab');
    if (manageTab) {
        manageTab.addEventListener('click', function() {
            loadCertificates();
        });
    }
});

// Function to load certificates via AJAX
function loadCertificates() {
    fetch('manage_all_certificate.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=load_certificates'
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const tbody = document.getElementById('certificateTableBody');
            tbody.innerHTML = '';
            
            if (data.certificates && data.certificates.length > 0) {
                data.certificates.forEach(cert => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        
                        <td>${cert.certificate_name}</td>
                       
                        <td>
                            <span class="badge ${cert.requires_payment == 1 ? 'bg-warning' : 'bg-success'}">
                                ${cert.requires_payment == 1 ? 'Yes' : 'No'}
                            </span>
                        </td>
                        <td>${cert.requires_payment == 1 ? '₱' + parseFloat(cert.fee_amount).toFixed(2) : 'Free'}</td>
                       
                        <td>${new Date(cert.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="editCertificate(${cert.id})">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteCertificate(${cert.id}, '${cert.certificate_name}')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted">No certificate types found</td></tr>';
            }
        } else {
            console.error('Error loading certificates:', data.message);
            document.getElementById('certificateTableBody').innerHTML = 
                '<tr><td colspan="9" class="text-center text-danger">Error loading certificates</td></tr>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('certificateTableBody').innerHTML = 
            '<tr><td colspan="9" class="text-center text-danger">Error loading certificates</td></tr>';
    });
}

// Toggle fee amount field for edit form
document.getElementById('editRequiresPayment').addEventListener('change', function() {
    const feeGroup = document.getElementById('editFeeAmountGroup');
    feeGroup.style.display = this.checked ? 'block' : 'none';
    if (!this.checked) {
        document.getElementById('editFeeAmount').value = '';
    }
});

// Add field function for edit form
function addEditField() {
    const container = document.getElementById('editFieldsContainer');
    const fieldCount = container.children.length;
    
    const fieldDiv = document.createElement('div');
    fieldDiv.className = 'input-group mb-2';
    fieldDiv.innerHTML = `
        <input type="text" class="form-control" name="fields[]" placeholder="Enter field name">
        <button type="button" class="btn btn-outline-danger" onclick="removeEditField(this)">Remove</button>
    `;
    
    container.appendChild(fieldDiv);
}

// Remove field function for edit form
function removeEditField(button) {
    button.parentElement.remove();
}

// Edit certificate function
function editCertificate(certificateId) {
    fetch('manage_all_certificate.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=get_certificate&certificate_id=' + certificateId
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const cert = data.data;
            
            // Populate form fields
            document.getElementById('editCertificateId').value = cert.id;
            document.getElementById('editCertificateName').value = cert.certificate_name;
            document.getElementById('editCertificateDescription').value = cert.description;
            document.getElementById('editExistingTemplate').value = cert.template_path || '';
            document.getElementById('editRequiresPayment').checked = cert.requires_payment == 1;
            document.getElementById('editFeeAmount').value = cert.fee_amount;
            
            // Show/hide fee amount field
            const feeGroup = document.getElementById('editFeeAmountGroup');
            feeGroup.style.display = cert.requires_payment == 1 ? 'block' : 'none';
            
            // Show current template
            const templateSpan = document.getElementById('currentTemplate');
            templateSpan.textContent = cert.template_path ? cert.template_path.split('/').pop() : 'None';
            
            // Clear and populate fields
            const fieldsContainer = document.getElementById('editFieldsContainer');
            fieldsContainer.innerHTML = '';
            
            if (cert.fields && cert.fields.length > 0) {
                cert.fields.forEach(field => {
                    const fieldDiv = document.createElement('div');
                    fieldDiv.className = 'input-group mb-2';
                    fieldDiv.innerHTML = `
                        <input type="text" class="form-control" name="fields[]" value="${field}" placeholder="Enter field name">
                        <button type="button" class="btn btn-outline-danger" onclick="removeEditField(this)">Remove</button>
                    `;
                    fieldsContainer.appendChild(fieldDiv);
                });
            }
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('editCertificateModal'));
            modal.show();
        } else {
            alert('Error loading certificate data: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error loading certificate data');
    });
}

// Delete certificate function
function deleteCertificate(certificateId, certificateName) {
    if (confirm(`Are you sure you want to delete "${certificateName}"? This action cannot be undone.`)) {
        fetch('manage_all_certificate.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=delete&certificate_id=' + certificateId
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                loadCertificates(); // Reload table data
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting certificate');
        });
    }
}



// Handle edit form submission
document.getElementById('editCertificateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('action', 'update');
    
    fetch('manage_all_certificate.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            loadCertificates(); // Reload table data
            // Hide modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editCertificateModal'));
            modal.hide();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating certificate');
    });
});
</script>


 <script>
        // Simulate loading
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.getElementById('loadingSpinner').style.display = 'none';
            }, 1000);
        });

        // Enhanced animations for buttons
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                let ripple = document.createElement('span');
                let rect = this.getBoundingClientRect();
                let size = Math.max(rect.width, rect.height);
                let x = e.clientX - rect.left - size / 2;
                let y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

       

        // Add slide out animation for deletions
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideOut {
                to {
                    transform: translateX(-100%);
                    opacity: 0;
                }
            }
            
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(255,255,255,0.6);
                transform: scale(0);
                animation: rippleEffect 0.6s linear;
                pointer-events: none;
            }
            
            @keyframes rippleEffect {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
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
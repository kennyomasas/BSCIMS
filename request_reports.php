<?php

require_once 'functions.php';

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

// Query to count total residents
$sql = "SELECT COUNT(*) AS total FROM residents";
$result = $conn->query($sql);

// Fetch the total count
$totalResidents = 0;
if ($result && $row = $result->fetch_assoc()) {
    $totalResidents = $row['total'];
}

$sql = "SELECT COUNT(*) AS total FROM certificate_requests";
$result = $conn->query($sql);

$totalRequests = 0;
if ($result && $row = $result->fetch_assoc()) {
     $totalRequests = $row['total'];
 }


$sql = "SELECT COUNT(*) AS total FROM certificate_requests WHERE status = 'Pending'";
$result = $conn->query($sql);

$pendingRequests = 0;
if ($result && $row = $result->fetch_assoc()) {
$pendingRequests = $row['total'];
}


$sql = "SELECT COUNT(*) AS total FROM announcements_events";
$result = $conn->query($sql);

$totalAnnouncements = 0;
if ($result && $row = $result->fetch_assoc()) {
                            $totalAnnouncements = $row['total'];
}

// Get demographics data
$demographics = getResidentDemographics($conn);
$growth = getQuarterlyGrowth($conn);

// Close the database connection
$conn->close();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Reports Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Global Styles */
        html, body {
            width: 100%;
            min-height: 100vh;
            overflow-x: hidden;           
        }

         /* Fixed header styles */
        .header-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: #fff;
            border-bottom: 1px solid #ddd;
            padding: 10px 20px;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-left: 235px;
        }

        
        .main-content {
            margin-top: 70px; /* Adjust this value based on your header height */
            padding: 20px;
        }

        /* Scrollable content area */
        .content-area {
            height: calc(100vh - 70px); /* Full viewport height minus header */
            overflow-y: auto;
            padding: 20px;
        }

      
        .content-section {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .logo-placeholder {
            width: 30px;
            height: 30px;
            background-color: #007bff;
            border-radius: 4px;
            display: inline-block;
            margin-right: 10px;
        }

     
        .sidebar {
            width: 235px;
            position: fixed;
            background-color: #31363F;
            height: 100vh;
            padding: 20px;
            z-index: 100;
        }

        /* Sidebar Hover Effect */
        .sidebar-item {
            transition: background 0.3s ease-in-out, transform 0.3s ease-in-out;
        }

        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(10px);
        }

        /* Animated Title */
        .animated-title {
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .animated-title:hover {
            transform: scale(1.1);
            color: #eff319;
        }

        /* Dropdown Menu */
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

        /* Main Content Area */
        .main-content {
            margin-left: 235px;
            padding: 20px;
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        /* Header Bar */
        .header-bar {
            background-color: #222831;
            color: white;
            padding: 15px;
            margin-bottom: 20px;
        }

        /* Report Card Styles */
        .report-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
        }

        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.15);
        }

        .report-card .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            font-weight: 600;
            border-radius: 10px 10px 0 0;
        }

        /* Stats Cards */
        .stats-card {
            border-left: 4px solid;
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: scale(1.03);
        }

        /* Filter Form */
        .filter-form {
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        /* Data Tables */
        .table-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            animation: fadeIn 0.5s ease-in-out;
        }

        .data-table th {
            background-color: #f8f9fa;
            position: sticky;
            top: 0;
            z-index: 1;
            padding: 12px 15px;
            border-bottom: 2px solid #dee2e6;
        }

        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .data-table tbody tr {
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .data-table tbody tr:hover {
            background-color: #f1f1f1;
            transform: scale(1.01);
        }

        /* Chart Container */
        .chart-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-bottom: 20px;
            height: 300px;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .animate-slide-in {
            animation: slideIn 0.5s ease-in-out forwards;
        }

        /* Print Button */
        .print-btn {
            background-color: #28a745;
            color: white;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .print-btn:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        /* Export Button */
        .export-btn {
            background-color: #007bff;
            color: white;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .export-btn:hover {
            background-color: #0069d9;
            transform: translateY(-2px);
        }

        /* Tab Navigation */
        .custom-tabs .nav-link {
            color: #495057;
            border: none;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .custom-tabs .nav-link.active {
            color: #007bff;
            background-color: transparent;
            border-bottom: 3px solid #007bff;
        }

        .custom-tabs .nav-link:hover:not(.active) {
            border-bottom: 3px solid #e9ecef;
        }

        /* Progress Bar */
        .progress {
            height: 10px;
            border-radius: 5px;
        }

         .chart-container {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        canvas {
            max-height: 400px;
        }
        .year-selector {
            text-align: center;
            margin-bottom: 20px;
        }
        .year-selector select {
            padding: 5px 10px;
            font-size: 16px;
        }
        .loading {
            text-align: center;
            color: #666;
        }
        .error {
            color: #d32f2f;
            text-align: center;
            padding: 10px;
            background-color: #ffebee;
            border-radius: 4px;
            margin: 10px 0;
        }
        .stats {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }


        
         .chart-container {
            padding: 20px;
            max-width: 605px;
            margin: 0 auto;
          
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
           
        }
        .chart-wrapper {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }
        .chart-section {
            flex: 1;
            max-height: 400px;
        }
        .stats-table {
            flex: 1;
            max-width: 300px;
        }
        canvas {
            max-height: 400px;
            margin-bottom: 60px;
        }
        .filters {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .filters select, .filters input {
            margin: 5px;
            padding: 5px 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        .filters button {
            margin: 5px;
            padding: 5px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .filters button:hover {
            background-color: #0056b3;
        }
        .loading {
            text-align: center;
            color: #666;
        }
        .error {
            color: #d32f2f;
            text-align: center;
            padding: 10px;
            background-color: #ffebee;
            border-radius: 4px;
            margin: 10px 0;
        }
        .stats-summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .stats-table table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stats-table th, .stats-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .stats-table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }
        .stats-table tr:hover {
            background-color: #f5f5f5;
        }
        .percentage {
            font-weight: bold;
            color: #007bff;
        }
        @media (max-width: 768px) {
            .chart-wrapper {
                flex-direction: column;
            }
            .stats-table {
                max-width: none;
            }
        }

         .table-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin: 20px;
        }
        


         .certificate-filter {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .data-table {
            font-size: 14px;
        }
        
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .loading {
            text-align: center;
            padding: 40px;
        }
        
        .export-btn {
            background-color: #198754;
            border-color: #198754;
            color: white;
        }
        
        .export-btn:hover:not(:disabled) {
            background-color: #157347;
            border-color: #146c43;
            color: white;
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }
        }

        .export-btn {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 5px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.export-btn:hover {
    background-color: #218838;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.export-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.export-btn:active {
    transform: translateY(0);
}

/* Loading animation for Font Awesome spinner */
.fa-spin {
    animation: fa-spin 1s infinite linear;
}

@keyframes fa-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.toast {
    visibility: hidden;
    min-width: 250px;
    background-color: #28a745; /* green for success */
    color: #fff;
    text-align: center;
    border-radius: 8px;
    padding: 16px;
    position: fixed;
    z-index: 999;
    top: 20px;
    right: 20px;
    font-size: 17px;
    opacity: 0;
    transition: opacity 0.5s, top 0.5s;
}

.toast.error {
    background-color: #dc3545; /* red for error */
}

.toast.show {
    visibility: visible;
    opacity: 1;
    top: 40px;
}

/* Activity Reports Styles */
.activity-reports .filter-form {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.activity-reports .report-card {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border: none;
}

.activity-reports .report-card .card-header {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    border-bottom: none;
}

.activity-reports .table-container {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.activity-reports .description-cell {
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.activity-reports .btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.activity-reports .print-btn,
.activity-reports .export-btn {
    background: #28a745;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.activity-reports .print-btn:hover,
.activity-reports .export-btn:hover {
    background: #218838;
    color: white;
}

.activity-reports .export-btn {
    background: #17a2b8;
}

.activity-reports .export-btn:hover {
    background: #138496;
}

/* Status badges */
.badge.bg-warning {
    color: #000;
}

/* Summary cards */
.activity-reports .report-card .card-body h4 {
    color: #007bff;
    font-weight: bold;
}

.activity-reports .report-card .card-body h6 {
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.activity-reports .report-card .card-body small {
    font-size: 0.8rem;
}

/* DataTable customization */
.activity-reports .dataTables_wrapper .dataTables_length,
.activity-reports .dataTables_wrapper .dataTables_filter {
    margin-bottom: 1rem;
}

.activity-reports .dataTables_wrapper .dataTables_info,
.activity-reports .dataTables_wrapper .dataTables_paginate {
    margin-top: 1rem;
}

/* Responsive design */
@media (max-width: 768px) {
    .activity-reports .filter-form .col-md-3 {
        margin-bottom: 1rem;
    }
    
    .activity-reports .report-card .col-md-3 {
        margin-bottom: 1rem;
        text-align: center;
    }
    
    .activity-reports .btn-group {
        flex-direction: column;
    }
    
    .activity-reports .btn-group .btn {
        margin-bottom: 0.25rem;
        border-radius: 4px !important;
    }
}

/* Print styles */
@media print {
    .activity-reports .filter-form,
    .activity-reports .print-btn,
    .activity-reports .export-btn,
    .activity-reports .btn-group {
        display: none !important;
    }
    
    .activity-reports .table {
        font-size: 12px;
    }
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
            right: -20px;
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
<body>

   <!-- Sidebar -->
<div class="sidebar text-white p-3">
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
            <a class="nav-link text-white d-flex align-items-center sidebar-item" href="request_reports.php" data-page="reports">
                <i class="fas fa-chart-bar me-2"></i> Reports
                <div class="page-indicator"></div>
            </a>
        </li>
         <li class="nav-item mt-auto">
            <a href="logout.php" class="nav-link text-white d-flex align-items-center sidebar-item">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </li>

    </ul>
</div>


    <!-- Main Content -->
    <div class="main-content"   >
         <!-- Fixed Header -->
    <div class="header-bar d-flex align-items-center" >
        <img src="logo.png" alt="Logo" style="height: 30px; width: auto; margin-right: 10px;"> 
        <h5 class="mb-0">Barangay San Carlos, City of Valencia, Province of Bukidnon</h5>
    </div>

        <!-- Reports Dashboard -->
        <div class="container-fluid px-0">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="fs-4 mb-3">Barangay Reports Dashboard</h2>
                    <p class="text-muted">Comprehensive overview of barangay activities, demographics, and services</p>
                </div>
            </div>

           <!-- Quick Stats -->
<div class="row mb-4 animate-slide-in" style="animation-delay: 0.1s;">
    <div class="col-md-3 mb-3">
        <div class="card stats-card h-100" style="border-left-color: #007bff;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted">Total Residents</h6>
                        <h3 class="fw-bold">                           
                           <?php echo number_format($totalResidents); ?>
                        </h3>
                    </div>
                    <i class="fas fa-users fa-2x text-muted"></i>
                </div>
                <div class="mt-2">
                    <span class="badge bg-success"></span>
                    <small class="text-muted ms-2"></small>
                </div>
            </div>
        </div>
    </div>
                
               <div class="col-md-3 mb-3">
    <div class="card stats-card h-100" style="border-left-color: #28a745;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted">Document Requests</h6>
                    <h3 class="fw-bold">
                       
                         <?php echo number_format($totalRequests);?>
                    
                    </h3>
                </div>
                <i class="fas fa-file-alt fa-2x text-muted"></i>
            </div>
            <div class="mt-2">
                <span class="badge bg-success"></span>
                <small class="text-muted ms-2"></small>
            </div>
        </div>
    </div>
</div>

                
               <div class="col-md-3 mb-3">
    <div class="card stats-card h-100" style="border-left-color: #ffc107;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted">Pending Requests</h6>
                    <h3 class="fw-bold">
                        

                       <?php echo number_format($pendingRequests);?>
                    </h3>
                </div>
                <i class="fas fa-clock fa-2x text-muted"></i>
            </div>
            <div class="mt-2">
                <span class="badge bg-danger"></span>
                <small class="text-muted ms-2"></small>
            </div>
        </div>
    </div>
</div>


                <div class="col-md-3 mb-3">                     
    <div class="card stats-card h-100" style="border-left-color: #dc3545;">                         
        <div class="card-body">                             
            <div class="d-flex justify-content-between align-items-center">                                 
                <div>                                     
                    <h6 class="text-muted">Announcements</h6>                                     
                    <h3 class="fw-bold">
                       
                         <?php echo number_format($totalAnnouncements);?>
                    </h3>                                 
                </div>                                 
                <i class="fas fa-bullhorn fa-2x text-muted"></i>                             
            </div>                             
            <div class="mt-2">                                 
                <span class="badge bg-success"></span>                                 
                <small class="text-muted ms-2"></small>                             
            </div>                         
        </div>                     
    </div>                 
</div>

            <!-- Tab Navigation -->
            <ul class="nav nav-tabs custom-tabs mb-4 animate-slide-in" style="animation-delay: 0.2s;" id="reportTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="document-tab" data-bs-toggle="tab" data-bs-target="#document-reports" type="button" role="tab" aria-controls="document-reports" aria-selected="true">
                        <i class="fas fa-file-alt me-2"></i>Document Requests
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="resident-tab" data-bs-toggle="tab" data-bs-target="#resident-reports" type="button" role="tab" aria-controls="resident-reports" aria-selected="false">
                        <i class="fas fa-user-friends me-2"></i>Resident Demographics
                    </button>
                </li>
                
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity-reports" type="button" role="tab" aria-controls="activity-reports" aria-selected="false">
                        <i class="fas fa-calendar-alt me-2"></i>Activity Reports
                    </button>
                </li>
            </ul>

            <div class="tab-content animate-slide-in" style="animation-delay: 0.3s;" id="reportTabsContent">
            <!-- Document Request Reports Tab -->
            <div class="tab-pane fade show active" id="document-reports" role="tabpanel" aria-labelledby="document-tab">
                <!-- Filter Form for Statistics -->
                <div class="filter-form mb-4">
                   
                </div>
                    
                    <!-- Document Request Statistics -->
                    <div class="row mb-4" >
                        <div class="col-md-6">
                            <div class="chart-container">
                                <h5 class="card-title">Document Requests by Type</h5>
                                <canvas id="documentTypeChart"></canvas>
                            </div>
                        </div>

                        <!-- Monthly Request Trends -->
                        <div class="chart-container" style="margin-left: 515px;  margin-top: -300px;">
                     <h5 class="card-title">Monthly Request Trends</h5>
        
                    <div class="year-selector">
            <label for="yearSelect">Select Year: </label>
            <select id="yearSelect">
                
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
            </select>
        </div>
        
        <div id="loading" class="loading">Loading chart data...</div>
        <div id="error" class="error" style="display: none;"></div>
        
        <canvas id="requestTrendsChart" style="display: none;"></canvas>
        
        <div id="stats" class="stats" style="display: none;"></div>
    </div>
    </div>
                    
                   <div class="container-fluid">
        <!-- Certificate Type Filter -->
        <div class="certificate-filter">
            <div class="row">
                <div class="col-md-4">
                    <label for="certificateTypeFilter" class="form-label">Select Certificate Type:</label>
                    <select class="form-select" id="certificateTypeFilter">
                        <option value="">Select Certificate Type</option>
                        <option value="all">All Certificate Types</option>
                        <option value="Barangay Clearance">Barangay Clearance</option>
                        <option value="Certificate of Residency">Certificate of Residency</option>
                        <option value="Certificate of Indigency">Certificate of Indigency</option>
                        <option value="Business Permit">Business Permit</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button class="btn btn-primary d-block" onclick="loadDocumentRequests()">
                        <i class="fas fa-search me-2"></i>Load Records
                    </button>
                </div>
            </div>
        </div>

        <!-- Document Request Table -->
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
              
                <div>
                    <button class="btn export-btn" onclick="exportToExcel()" disabled id="exportBtn">
                        <i class="fas fa-file-excel me-2"></i>Export to Excel
                    </button>
                </div>
            </div>
            
            <div id="loadingDiv" class="loading" style="display: none;">
                <i class="fas fa-spinner fa-spin fa-2x"></i>
                <p>Loading records...</p>
            </div>
            
            <table class="data-table table table-striped" id="documentTable" style="display: none;">
                <thead class="table-white">
                    <tr>
                        <th>Request_ID</th>
                        <th>Resident_Name</th>
                        <th>Certificate_Type</th>
                        <th>Purpose</th>
                        <th>Signatory</th>
                        <th>Status</th>
                        <th>Request_Date</th>
                        <th>Processed_Date</th>
                       
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            
            <div id="noDataDiv" style="display: none;">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle me-2"></i>
                    No records found. Please select a certificate type and click "Load Records".
                </div>
            </div>
        </div>
    </div>

                    
                  
                </div>
                
                <!-- Resident Demographics Tab -->
                <div class="tab-pane fade" id="resident-reports" role="tabpanel" aria-labelledby="resident-tab">
                    <!-- Filter Form -->
                    <div class="filter-form mb-4">
                      
                    </div>
                    

                      <!-- Demographic Charts -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="chart-container">
                    <div class="chart-controls">
                        <button class="btn btn-sm btn-outline-primary btn-chart" onclick="toggleChartType('ageGroupChart', 'bar')">Bar</button>
                        <button class="btn btn-sm btn-outline-primary btn-chart" onclick="toggleChartType('ageGroupChart', 'line')">Line</button>
                        <button class="btn btn-sm btn-outline-success btn-chart" onclick="refreshChart('age_groups')">↻</button>
                    </div>
                    <div class="chart-title">Population by Age Group</div>
                    <canvas id="ageGroupChart"></canvas>
                    <div class="chart-info" id="ageGroupInfo"></div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="chart-container">
                    <div class="chart-controls">
                        <button class="btn btn-sm btn-outline-primary btn-chart" onclick="toggleChartType('genderChart', 'doughnut')">Donut</button>
                        <button class="btn btn-sm btn-outline-primary btn-chart" onclick="toggleChartType('genderChart', 'pie')">Pie</button>
                        <button class="btn btn-sm btn-outline-success btn-chart" onclick="refreshChart('gender')">↻</button>
                    </div>
                    <div class="chart-title">Gender Distribution</div>
                    <canvas id="genderChart"></canvas>
                    <div class="chart-info" id="genderInfo"></div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="chart-container">
                    <div class="chart-controls">
                        <button class="btn btn-sm btn-outline-primary btn-chart" onclick="toggleChartType('educationChart', 'bar')">Bar</button>
                       
                        <button class="btn btn-sm btn-outline-success btn-chart" onclick="refreshChart('education')">↻</button>
                    </div>
                    <div class="chart-title">Education Level Distribution</div>
                    <canvas id="educationChart"></canvas>
                    <div class="chart-info" id="educationInfo"></div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="chart-container">
                    <div class="chart-controls">
                        <button class="btn btn-sm btn-outline-primary btn-chart" onclick="toggleChartType('maritalChart', 'doughnut')">Donut</button>
                        <button class="btn btn-sm btn-outline-primary btn-chart" onclick="toggleChartType('maritalChart', 'polarArea')">Polar</button>
                        <button class="btn btn-sm btn-outline-success btn-chart" onclick="refreshChart('marital_status')">↻</button>
                    </div>
                    <div class="chart-title">Marital Status Distribution</div>
                    <canvas id="maritalChart"></canvas>
                    <div class="chart-info" id="maritalInfo"></div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="chart-container">
                    <div class="chart-controls">
                        <button class="btn btn-sm btn-outline-success btn-chart" onclick="refreshChart('sector')">↻</button>
                    </div>
                    <div class="chart-title">Population by Sector</div>
                    <canvas id="sectorChart"></canvas>
                    <div class="chart-info" id="sectorInfo"></div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="chart-container">
                    <div class="chart-controls">
                        <button class="btn btn-sm btn-outline-success btn-chart" onclick="refreshChart('purok')">↻</button>
                    </div>
                    <div class="chart-title">Population by Purok</div>
                    <canvas id="purokChart"></canvas>
                    <div class="chart-info" id="purokInfo"></div>
                </div>
            </div>
        </div>
   



                        <!-- Resident Statistics Summary -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card report-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Resident Demographics Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div >
                               
                            </div>
                          
                            <div class="col-md-3 mb-3">
                                <h6 class="text-muted">Senior Citizens</h6>
                                <h4><?php echo number_format($demographics['senior_citizens']); ?></h4>
                            </div>
                            <div class="col-md-3 mb-3">
                                <h6 class="text-muted">Children (0-14)</h6>
                                <h4><?php echo number_format($demographics['children']); ?></h4>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3 mb-3">
                                <h6 class="text-muted">Total Residents</h6>
                                <h4><?php echo number_format($demographics['total_residents']); ?></h4>
                            </div>
                            <div class="col-md-3 mb-3">
                                <h6 class="text-muted">Adults (15-64)</h6>
                                <h4><?php echo number_format($demographics['total_residents'] - $demographics['children'] - $demographics['senior_citizens']); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                    
             
           <!-- Resident Table -->
<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="card-title">Resident Records</h5>
        <div class="d-flex align-items-center gap-2">
            <!-- Filter Dropdown -->
            <select class="form-select" id="exportFilter" style="width: 200px;">
                <option value="all">All Residents</option>
                <optgroup label="Gender">
                    <option value="gender_male">Male Only</option>
                    <option value="gender_female">Female Only</option>
                </optgroup>
                <optgroup label="Age Groups">
                    <option value="age_children">Children (0-17)</option>
                    <option value="age_adults">Adults (18-59)</option>
                    <option value="age_seniors">Seniors (60+)</option>
                </optgroup>
                <optgroup label="Purok">
                    <option value="purok_1">Purok 1</option>
                    <option value="purok_2">Purok 2</option>
                    <option value="purok_3">Purok 3</option>
                    <option value="purok_4">Purok 4</option>
                    <option value="purok_5">Purok 5</option>
                    <option value="purok_6">Purok 6</option>
                    <option value="purok_7">Purok 7</option>
                   
                </optgroup>
            </select>
            
            <button class="btn export-btn" onclick="exportToExcel()" id="exportBtn">
                <i class="fas fa-file-excel me-2"></i>Export to Excel
            </button>
        </div>
    </div>
    <table class="data-table table" id="residentTable">
        <thead>
           
        </thead>
        <tbody>
           
        </tbody>
    </table>
</div>
    
    <table class="data-table table" id="residentTable">
        <thead>
           
        </thead>
        <tbody>
           
        </tbody>
    </table>
</div>


                
                <!-- Financial Reports Tab -->
                <div class="tab-pane fade" id="financial-reports" role="tabpanel" aria-labelledby="financial-tab">
                    <!-- Filter Form -->
                    <div class="filter-form mb-4">
                        <form class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label for="financial-month" class="form-label">Month</label>
                                <select class="form-select" id="financial-month" name="financial_month">
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="financial-year" class="form-label">Year</label>
                                <select class="form-select" id="financial-year" name="financial_year">
                                    <option value="2025">2025</option>
                                    <option value="2024">2024</option>
                                    <option value="2023">2023</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="fund-source" class="form-label">Fund Source</label>
                                <select class="form-select" id="fund-source" name="fund_source">
                                    <option value="all">All Sources</option>
                                    <option value="iba">Internal Revenue Allotment</option>
                                    <option value="local">Local Fees</option>
                                    <option value="donations">Donations</option>
                                    <option value="projects">Project Funds</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-2"></i>Apply Filters
                                </button>
                            </div>
                        </form>
                    </div>
            
                    <!-- Financial Summary Card -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card report-card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Financial Summary (March 2025)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <h6 class="text-muted">Total Income</h6>
                                            <h4>₱0</h4>
                                            <small class="text-success">+5.2% from last month</small>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h6 class="text-muted">Total Expenditure</h6>
                                            <h4>₱0</h4>
                                            <small class="text-danger">+3.1% from last month</small>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h6 class="text-muted">Balance</h6>
                                            <h4>₱0</h4>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h6 class="text-muted">Budget Utilization</h6>
                                            <h4>0%</h4>
                                            <div class="progress mt-2">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 88.3%" aria-valuenow="88.3" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Financial Transactions Table -->
                    <div class="table-container">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Financial Transactions</h5>
                            <div>
                                <button class="btn print-btn me-2">
                                    <i class="fas fa-print me-2"></i>Print Report
                                </button>
                                <button class="btn export-btn">
                                    <i class="fas fa-file-excel me-2"></i>Export to Excel
                                </button>
                            </div>
                        </div>
                        <table class="data-table table" id="financialTable">
                            <thead>
                               
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Activity Reports Tab -->
                <div class="tab-pane fade" id="activity-reports" role="tabpanel" aria-labelledby="activity-tab">
                    <!-- Filter Form -->
                    <div class="filter-form mb-4">
                        <form class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label for="activity-month" class="form-label">Month</label>
                                <select class="form-select" id="activity-month" name="activity_month">
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="activity-year" class="form-label">Year</label>
                                <select class="form-select" id="activity-year" name="activity_year">
                                    <option value="2025">2025</option>
                                    <option value="2024">2024</option>
                                    <option value="2023">2023</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="activity-type" class="form-label">Activity Type</label>
                                <select class="form-select" id="activity-type" name="activity_type">
                                    <option value="all">All Activities</option>
                                    <option value="meeting">Meetings</option>
                                    <option value="program">Community Programs</option>
                                    <option value="project">Projects</option>
                                    <option value="celebration">Celebrations</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-2"></i>Apply Filters
                                </button>
                            </div>
                        </form>
                    </div>
                    
                 
                    
                    <!-- Activity Summary Card -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card report-card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Activities Summary (March 2025)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <h6 class="text-muted">Total Activities</h6>
                                            <h4>0</h4>
                                            <small class="text-success">+0 from last month</small>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h6 class="text-muted">Total Participation</h6>
                                            <h4>0 residents</h4>
                                            <small class="text-success">0% of population</small>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h6 class="text-muted">Most Popular Program</h6>
                                            <h4>Health Fair</h4>
                                            <small class="text-muted">0 participants</small>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <h6 class="text-muted">Upcoming Activities</h6>
                                            <h4>0</h4>
                                            <small class="text-muted">Next: Clean-up Drive ()</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Activities Table -->
                    <div class="table-container">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title">Activities and Programs</h5>
                            <div>
                                <button class="btn print-btn me-2">
                                    <i class="fas fa-print me-2"></i>Print Report
                                </button>
                                <button class="btn export-btn">
                                    <i class="fas fa-file-excel me-2"></i>Export to Excel
                                </button>
                            </div>
                        </div>
                        <table class="data-table table" id="activityTable">
                            <thead>
                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="toast" class="toast"></div>

    <!-- JavaScript for Charts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

            // Age Group Chart
            const ageGroupCtx = document.getElementById('ageGroupChart').getContext('2d');
            const ageGroupChart = new Chart(ageGroupCtx, {
                type: 'bar',
                data: {
                    labels: ['0-14', '15-24', '25-54', '55-64', '65+'],
                    datasets: [{
                        label: 'Number of Residents',
                        data: [289, 231, 485, 153, 187],
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });


            

            // Gender Chart
            const genderCtx = document.getElementById('genderChart').getContext('2d');
            const genderChart = new Chart(genderCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Male', 'Female'],
                    datasets: [{
                        data: [612, 633],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 99, 132, 0.7)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });

            // Initialize other charts similarly
            // This is a placeholder for other chart initializations

            // Add row highlight effect
            const tableRows = document.querySelectorAll('.data-table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('click', function() {
                    tableRows.forEach(r => r.classList.remove('highlight'));
                    this.classList.add('highlight');
                });
            });
        });
    </script>

      
   
 <script>
        let requestTrendsChart = null;
        
        // Set current year as default
        document.getElementById('yearSelect').value = new Date().getFullYear();
        
        // Load chart data
        async function loadChartData(year = new Date().getFullYear()) {
            const loadingDiv = document.getElementById('loading');
            const errorDiv = document.getElementById('error');
            const canvas = document.getElementById('requestTrendsChart');
            const statsDiv = document.getElementById('stats');
            
            // Show loading
            loadingDiv.style.display = 'block';
            errorDiv.style.display = 'none';
            canvas.style.display = 'none';
            statsDiv.style.display = 'none';
            
            try {
                const response = await fetch(`get_monthly_trends.php?year=${year}`);
                const result = await response.json();
                
                if (result.status === 'error') {
                    throw new Error(result.message);
                }
                
                // Hide loading
                loadingDiv.style.display = 'none';
                canvas.style.display = 'block';
                statsDiv.style.display = 'block';
                
                
                
                // Destroy existing chart if it exists
                if (requestTrendsChart) {
                    requestTrendsChart.destroy();
                }
                
                // Create new chart
                const ctx = canvas.getContext('2d');
                requestTrendsChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: result.labels,
                        datasets: [{
                            label: `Request Volume (${result.year})`,
                            data: result.data,
                            fill: false,
                            borderColor: 'rgb(75, 192, 192)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            tension: 0.1,
                            pointBackgroundColor: 'rgb(75, 192, 192)',
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: 'rgb(75, 192, 192)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: `Certificate Requests by Month - ${result.year}`
                            },
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                },
                                title: {
                                    display: true,
                                    text: 'Number of Requests'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: ''
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        elements: {
                            point: {
                                radius: 5,
                                hoverRadius: 7
                            }
                        }
                    }
                });
                
            } catch (error) {
                console.error('Error loading chart data:', error);
                loadingDiv.style.display = 'none';
                errorDiv.style.display = 'block';
                errorDiv.textContent = 'Error loading chart data: ' + error.message;
            }
        }
        
        // Handle year selection change
        document.getElementById('yearSelect').addEventListener('change', function() {
            const selectedYear = this.value;
            loadChartData(selectedYear);
        });
        
        // Load initial data
        loadChartData();
    </script>

    <script>
        // Initialize all charts when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Fetch document statistics from database
    fetch('get_document_statistics.php', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Create Document Type Chart with real data
            const documentTypeCtx = document.getElementById('documentTypeChart').getContext('2d');
            const documentTypeChart = new Chart(documentTypeCtx, {
                type: 'pie',
                data: {
                    labels: data.chartData.labels,
                    datasets: [{
                        data: data.chartData.data,
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(255, 205, 86, 0.7)',
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(153, 102, 255, 0.7)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 205, 86, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const total = data.totalRequests;
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
            
            console.log('Chart loaded successfully with', data.totalRequests, 'total requests');
        } else {
            console.error('Error fetching chart data:', data.message);
            // Fallback to static data if database fails
            createFallbackChart();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Fallback to static data if fetch fails
        createFallbackChart();
    });
});

// Fallback function with static data
function createFallbackChart() {
    const documentTypeCtx = document.getElementById('documentTypeChart').getContext('2d');
    const documentTypeChart = new Chart(documentTypeCtx, {
        type: 'pie',
        data: {
            labels: ['Barangay Clearance', 'Certificate of Residency', 'Business Permit', 'Certificate of Indigency', 'Other Documents'],
            datasets: [{
                data: [38, 25, 12, 18, 7],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 205, 86, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 205, 86, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
    console.log('Fallback chart loaded with static data');
}
    </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="path/to/activity-reports.js"></script>

    <script>
        let currentData = [];
        let currentCertificateType = '';
        let dataTable = null;

        function loadDocumentRequests() {
            const certificateType = document.getElementById('certificateTypeFilter').value;
            
           if (!certificateType) {
    showToast('Please select a certificate type first.', true); // isError = true (red)
    return;
}

            
            currentCertificateType = certificateType;
            
            // Show loading and hide other elements
            showLoading();
            
            // Destroy existing DataTable if it exists
            destroyDataTable();
            
            // Choose the appropriate PHP endpoint
            const phpEndpoint = (certificateType === 'all') ? 'get_all_certificate_requests.php' : 'get_certificate_requests.php';
            const requestData = (certificateType === 'all') ? {} : { certificate_type: certificateType };
            
            // AJAX request to PHP
            $.ajax({
                url: phpEndpoint,
                method: 'POST',
                data: requestData,
                dataType: 'json',
                success: function(response) {
                    hideLoading();
                    
                    if (response.status === 'success') {
                        currentData = response.data || [];
                        populateTable(currentData);
                        enableButtons(currentData.length > 0);
                        
                        // Log success for debugging
                        console.log('Data loaded successfully:', currentData.length, 'records');
                    } else {
                        console.error('Server error:', response.message);
                        showNoDataMessage();
                        enableButtons(false);
                        alert('Error: ' + (response.message || 'Unknown error occurred'));
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error Details:', {
                        status: status,
                        error: error,
                        responseText: xhr.responseText,
                        statusCode: xhr.status
                    });
                    
                    hideLoading();
                    showNoDataMessage();
                    enableButtons(false);
                    
                    // More detailed error message
                    let errorMessage = 'Error loading data. ';
                    if (xhr.status === 404) {
                        errorMessage += 'PHP file not found. Please check if ' + phpEndpoint + ' exists.';
                    } else if (xhr.status === 500) {
                        errorMessage += 'Server error. Please check your PHP code for syntax errors.';
                    } else if (xhr.status === 0) {
                        errorMessage += 'Network error. Please check your connection.';
                    } else {
                        errorMessage += 'Please check the console for details.';
                    }
                    
                    alert(errorMessage);
                }
            });
        }

        function showLoading() {
            document.getElementById('loadingDiv').style.display = 'block';
            document.getElementById('documentTable').style.display = 'none';
            document.getElementById('noDataDiv').style.display = 'none';
        }

        function hideLoading() {
            document.getElementById('loadingDiv').style.display = 'none';
        }

        function showNoDataMessage() {
            document.getElementById('noDataDiv').style.display = 'block';
            document.getElementById('documentTable').style.display = 'none';
        }

        function enableButtons(enable) {
            const exportBtn = document.getElementById('exportBtn');
            
            exportBtn.disabled = !enable;
            
            // Force button state update
            if (enable) {
                exportBtn.classList.remove('disabled');
            } else {
                exportBtn.classList.add('disabled');
            }
        }

        function destroyDataTable() {
            if (dataTable) {
                dataTable.destroy();
                dataTable = null;
            }
        }

        function populateTable(data) {
            const tbody = document.querySelector('#documentTable tbody');
            tbody.innerHTML = '';
            
            if (!data || data.length === 0) {
                showNoDataMessage();
                return;
            }
            
            data.forEach(function(row) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.request_id || ''}</td>
                    <td>${row.resident_name || ''}</td>
                    <td>${row.certificate_type || ''}</td>
                    <td>${row.purpose || ''}</td>
                    <td>${row.signatory || ''}</td>
                    <td><span class="status-badge status-${(row.status || 'pending').toLowerCase()}">${row.status || 'Pending'}</span></td>
                    <td>${formatDate(row.request_date)}</td>
                    <td>${formatDate(row.processed_date)}</td>
                    <td>${row.notes || ''}</td>
                `;
                tbody.appendChild(tr);
            });
            
            document.getElementById('documentTable').style.display = 'table';
            
            // Initialize DataTable
            try {
                dataTable = $('#documentTable').DataTable({
                    responsive: true,
                    pageLength: 25,
                    order: [[6, 'desc']], // Sort by request date descending
                    language: {
                        search: "Search records:",
                        lengthMenu: "Show _MENU_ records per page",
                        info: "Showing _START_ to _END_ of _TOTAL_ records",
                        paginate: {
                            first: "First",
                            last: "Last",
                            next: "Next",
                            previous: "Previous"
                        }
                    }
                });
            } catch (error) {
                console.error('Error initializing DataTable:', error);
            }
        }

        function formatDate(dateString) {
            if (!dateString) return '';
            try {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: '2-digit'
                });
            } catch (error) {
                return dateString;
            }
        }

        function exportToExcel() {
            if (!currentData || currentData.length === 0) {
                alert('No data to export.');
                return;
            }
            
            try {
                // Prepare data for Excel
                const excelData = currentData.map(row => ({
                    'Request ID': row.request_id || '',
                    'Resident Name': row.resident_name || '',
                    'Certificate Type': row.certificate_type || '',
                    'Purpose': row.purpose || '',
                    'Signatory': row.signatory || '',
                    'Status': row.status || 'Pending',
                    'Request Date': formatDate(row.request_date),
                    'Processed Date': formatDate(row.processed_date),
                    'Notes': row.notes || ''
                }));
                
                // Create workbook and worksheet
                const wb = XLSX.utils.book_new();
                const ws = XLSX.utils.json_to_sheet(excelData);
                
                // Set column widths
                const colWidths = [
                    { wch: 15 }, // Request ID
                    { wch: 25 }, // Resident Name
                    { wch: 20 }, // Certificate Type
                    { wch: 30 }, // Purpose
                    { wch: 20 }, // Signatory
                    { wch: 12 }, // Status
                    { wch: 15 }, // Request Date
                    { wch: 15 }, // Processed Date
                    { wch: 30 }  // Notes
                ];
                ws['!cols'] = colWidths;
                
                // Add worksheet to workbook
                XLSX.utils.book_append_sheet(wb, ws, "Document Requests");
                
                // Generate filename with current date and certificate type
                const currentDate = new Date().toISOString().split('T')[0];
                const filenamePart = currentCertificateType === 'all' ? 'All_Certificates' : currentCertificateType.replace(/\s+/g, '_');
                const filename = `Document_Requests_${filenamePart}_${currentDate}.xlsx`;
                
                // Save file
                XLSX.writeFile(wb, filename);
                
            } catch (error) {
                console.error('Error exporting to Excel:', error);
                alert('Error exporting to Excel. Please try again.');
            }
        }

        // Initialize on page load
        $(document).ready(function() {
            console.log('Document Request Table initialized');
            
            // Ensure buttons start disabled
            enableButtons(false);
        });
    </script>




     <script>
        // Global chart instances
        let charts = {};
        
        // Color schemes
        const colorSchemes = {
            blue: ['rgba(54, 162, 235, 0.7)', 'rgba(54, 162, 235, 1)'],
            red: ['rgba(255, 99, 132, 0.7)', 'rgba(255, 99, 132, 1)'],
            green: ['rgba(75, 192, 192, 0.7)', 'rgba(75, 192, 192, 1)'],
            yellow: ['rgba(255, 205, 86, 0.7)', 'rgba(255, 205, 86, 1)'],
            purple: ['rgba(153, 102, 255, 0.7)', 'rgba(153, 102, 255, 1)'],
            orange: ['rgba(255, 159, 64, 0.7)', 'rgba(255, 159, 64, 1)']
        };

        // Generate random colors for charts
        function generateColors(count) {
            const colors = [];
            const borderColors = [];
            const schemes = Object.values(colorSchemes);
            
            for(let i = 0; i < count; i++) {
                const scheme = schemes[i % schemes.length];
                colors.push(scheme[0]);
                borderColors.push(scheme[1]);
            }
            
            return { backgroundColor: colors, borderColor: borderColors };
        }

        // Fetch chart data
        async function fetchChartData(type) {
            try {
                const response = await fetch(`chart_data.php?action=${type}`);
                const data = await response.json();
                return data;
            } catch (error) {
                console.error('Error fetching chart data:', error);
                return { labels: [], data: [] };
            }
        }

        // Create chart
        function createChart(canvasId, type, data, options = {}) {
            const ctx = document.getElementById(canvasId).getContext('2d');
            
            // Destroy existing chart if it exists
            if (charts[canvasId]) {
                charts[canvasId].destroy();
            }
            
            const colors = generateColors(data.labels.length);
            
            const chartConfig = {
                type: type,
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: options.label || 'Count',
                        data: data.data,
                        backgroundColor: colors.backgroundColor,
                        borderColor: colors.borderColor,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: function(event, elements) {
                        if (elements.length > 0) {
                            const elementIndex = elements[0].index;
                            const label = this.data.labels[elementIndex];
                            const value = this.data.datasets[0].data[elementIndex];
                            showDetailedInfo(canvasId, label, value);
                        }
                    },
                    plugins: {
                        legend: {
                            display: type !== 'bar' && type !== 'line',
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                afterLabel: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((context.raw / total) * 100).toFixed(1);
                                    return `Percentage: ${percentage}%`;
                                }
                            }
                        }
                    },
                    scales: type === 'bar' || type === 'line' ? {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    } : {},
                    ...options.chartOptions
                }
            };
            
            charts[canvasId] = new Chart(ctx, chartConfig);
            updateChartInfo(canvasId, data);
        }

        // Toggle chart type
        function toggleChartType(canvasId, newType) {
            if (charts[canvasId]) {
                const currentData = charts[canvasId].data;
                charts[canvasId].destroy();
                
                const ctx = document.getElementById(canvasId).getContext('2d');
                const colors = generateColors(currentData.labels.length);
                
                charts[canvasId] = new Chart(ctx, {
                    type: newType,
                    data: {
                        labels: currentData.labels,
                        datasets: [{
                            label: currentData.datasets[0].label,
                            data: currentData.datasets[0].data,
                            backgroundColor: colors.backgroundColor,
                            borderColor: colors.borderColor,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        onClick: function(event, elements) {
                            if (elements.length > 0) {
                                const elementIndex = elements[0].index;
                                const label = this.data.labels[elementIndex];
                                const value = this.data.datasets[0].data[elementIndex];
                                showDetailedInfo(canvasId, label, value);
                            }
                        },
                        plugins: {
                            legend: {
                                display: newType !== 'bar' && newType !== 'line',
                                position: 'bottom'
                            }
                        },
                        scales: newType === 'bar' || newType === 'line' ? {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        } : {}
                    }
                });
            }
        }

        // Refresh specific chart
        async function refreshChart(type) {
            const chartMap = {
                'age_groups': 'ageGroupChart',
                'gender': 'genderChart',
                'education': 'educationChart',
                'marital_status': 'maritalChart',
                'sector': 'sectorChart',
                'purok': 'purokChart'
            };
            
            const canvasId = chartMap[type];
            if (canvasId) {
                const data = await fetchChartData(type);
                const currentType = charts[canvasId] ? charts[canvasId].config.type : getDefaultChartType(type);
                createChart(canvasId, currentType, data);
            }
        }

        // Get default chart type for data type
        function getDefaultChartType(type) {
            const defaults = {
                'age_groups': 'bar',
                'gender': 'doughnut',
                'education': 'bar',
                'marital_status': 'doughnut',
                'sector': 'bar',
                'purok': 'bar'
            };
            return defaults[type] || 'bar';
        }

        // Update chart info
        function updateChartInfo(canvasId, data) {
            const infoId = canvasId.replace('Chart', 'Info');
            const infoElement = document.getElementById(infoId);
            
            if (infoElement && data.data.length > 0) {
                const total = data.data.reduce((a, b) => a + b, 0);
                const max = Math.max(...data.data);
                const maxIndex = data.data.indexOf(max);
                const maxLabel = data.labels[maxIndex];
                
                infoElement.innerHTML = `
                    <strong>Total:</strong> ${total} | 
                    <strong>Highest:</strong> ${maxLabel} (${max}) | 
                    <strong>Categories:</strong> ${data.labels.length}
                `;
            }
        }

        // Show detailed info on click
        function showDetailedInfo(canvasId, label, value) {
            const total = charts[canvasId].data.datasets[0].data.reduce((a, b) => a + b, 0);
            const percentage = ((value / total) * 100).toFixed(1);
            
            alert(`${label}\nCount: ${value}\nPercentage: ${percentage}%\nTotal Population: ${total}`);
        }

        // Update statistics
        async function updateStatistics() {
            try {
                const genderData = await fetchChartData('gender');
                let maleCount = 0, femaleCount = 0;
                
                genderData.labels.forEach((label, index) => {
                    if (label.toLowerCase() === 'male') {
                        maleCount = genderData.data[index];
                    } else if (label.toLowerCase() === 'female') {
                        femaleCount = genderData.data[index];
                    }
                });
                
                const totalResidents = maleCount + femaleCount;
                
                document.getElementById('totalResidents').textContent = totalResidents;
                document.getElementById('maleCount').textContent = maleCount;
                document.getElementById('femaleCount').textContent = femaleCount;
                
                // Calculate average age (simplified - you might want to get this from server)
                const ageData = await fetchChartData('age_groups');
                let weightedSum = 0, totalPeople = 0;
                
                ageData.labels.forEach((label, index) => {
                    const count = ageData.data[index];
                    let avgAge = 0;
                    
                    switch(label) {
                        case '0-14': avgAge = 7; break;
                        case '15-24': avgAge = 19.5; break;
                        case '25-54': avgAge = 39.5; break;
                        case '55-64': avgAge = 59.5; break;
                        case '65+': avgAge = 70; break;
                    }
                    
                    weightedSum += avgAge * count;
                    totalPeople += count;
                });
                
                const averageAge = totalPeople > 0 ? Math.round(weightedSum / totalPeople) : 0;
                document.getElementById('averageAge').textContent = averageAge;
                
            } catch (error) {
                console.error('Error updating statistics:', error);
            }
        }

        // Initialize dashboard
        async function initializeDashboard() {
            // Update statistics first
            await updateStatistics();
            
            // Initialize all charts
            const chartTypes = [
                { type: 'age_groups', canvasId: 'ageGroupChart', chartType: 'bar' },
                { type: 'gender', canvasId: 'genderChart', chartType: 'doughnut' },
                { type: 'education', canvasId: 'educationChart', chartType: 'bar' },
                { type: 'marital_status', canvasId: 'maritalChart', chartType: 'doughnut' },
                { type: 'sector', canvasId: 'sectorChart', chartType: 'bar' },
                { type: 'purok', canvasId: 'purokChart', chartType: 'bar' }
            ];
            
            for (const chart of chartTypes) {
                const data = await fetchChartData(chart.type);
                createChart(chart.canvasId, chart.chartType, data);
            }
        }

        // Auto-refresh dashboard every 5 minutes
        function startAutoRefresh() {
            setInterval(async () => {
                console.log('Auto-refreshing dashboard...');
                await initializeDashboard();
            }, 300000); // 5 minutes
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeDashboard();
            startAutoRefresh();
        });
    </script>

   <script>
function exportToExcel() {
    // Get the export button and filter value
    const button = document.getElementById('exportBtn');
    const filter = document.getElementById('exportFilter').value;
    const originalContent = button.innerHTML;
    
    // Show loading state
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Exporting...';
    button.disabled = true;
    
    // Create a temporary form to trigger the download
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'export_residents.php';
    form.style.display = 'none';
    
    // Add filter parameter
    const filterInput = document.createElement('input');
    filterInput.type = 'hidden';
    filterInput.name = 'filter';
    filterInput.value = filter;
    form.appendChild(filterInput);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
    
    // Reset button after 2 seconds
    setTimeout(() => {
        button.innerHTML = originalContent;
        button.disabled = false;
    }, 2000);
}

// Optional: Add keyboard shortcut for export (Ctrl+E)
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'e') {
        e.preventDefault();
        exportToExcel();
    }
});


</script>

<script>
function showToast(message, isError = false, callback = null) {
    var toast = document.getElementById("toast");
    toast.textContent = message;
    toast.className = "toast show";
    if (isError) {
        toast.classList.add("error");
    } else {
        toast.classList.remove("error");
    }
    setTimeout(function() {
        toast.className = toast.className.replace("show", "");
        if (callback) callback();
    }, 3000);
}
</script>

<script>
    // Activity Reports Functions
class ActivityReports {
    constructor() {
        this.currentData = [];
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadActivities();
        this.setCurrentMonth();
    }

    bindEvents() {
        // Filter form submission
        const filterForm = document.querySelector('#activity-reports form');
        if (filterForm) {
            filterForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.applyFilters();
            });
        }

        // Print button
        const printBtn = document.querySelector('#activity-reports .print-btn');
        if (printBtn) {
            printBtn.addEventListener('click', () => this.printReport());
        }

        // Export button
        const exportBtn = document.querySelector('#activity-reports .export-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportToExcel());
        }
    }

    setCurrentMonth() {
        const currentDate = new Date();
        const monthSelect = document.getElementById('activity-month');
        const yearSelect = document.getElementById('activity-year');
        
        if (monthSelect) monthSelect.value = currentDate.getMonth() + 1;
        if (yearSelect) yearSelect.value = currentDate.getFullYear();
    }

    async loadActivities(filters = {}) {
        try {
            const formData = new FormData();
            formData.append('action', 'get_activities');
            
            // Add filters if provided
            Object.keys(filters).forEach(key => {
                formData.append(key, filters[key]);
            });

            const response = await fetch('get_activities.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            
            if (data.success) {
                this.currentData = data.activities || [];
                this.displayActivities();
                this.updateSummary();
            } else {
                this.showAlert('Error loading activities: ' + data.message, 'danger');
            }
        } catch (error) {
            console.error('Error loading activities:', error);
            this.showAlert('Error loading activities. Please try again.', 'danger');
        }
    }

    applyFilters() {
        const month = document.getElementById('activity-month')?.value;
        const year = document.getElementById('activity-year')?.value;
        const type = document.getElementById('activity-type')?.value;

        const filters = {
            month: month,
            year: year,
            type: type
        };

        this.loadActivities(filters);
    }

    displayActivities() {
        const tableContainer = document.querySelector('#activity-reports .table-container table');
        if (!tableContainer) return;

        // Create table structure if it doesn't exist
        if (!tableContainer.querySelector('thead tr')) {
            tableContainer.innerHTML = `
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Start_Date</th>
                        <th>End_Date</th>
                        <th>Status</th>
                        <th>Participants</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            `;
        }

        const tbody = tableContainer.querySelector('tbody');
        tbody.innerHTML = '';

        if (this.currentData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No activities found for the selected criteria.</p>
                    </td>
                </tr>
            `;
            return;
        }

        this.currentData.forEach(activity => {
            const row = this.createActivityRow(activity);
            tbody.appendChild(row);
        });

        // Initialize DataTable if available
        if (typeof $.fn.DataTable !== 'undefined') {
            $('#activityTable').DataTable({
                destroy: true,
                responsive: true,
                pageLength: 10,
                order: [[3, 'desc']], // Sort by start date
                columnDefs: [
                    { orderable: false, targets: [7] } // Disable sorting for actions column
                ]
            });
        }
    }

    createActivityRow(activity) {
        const row = document.createElement('tr');
        
        const status = this.getActivityStatus(activity.start_date, activity.end_date);
        const statusClass = this.getStatusClass(status);
        
        row.innerHTML = `
            <td>
                <strong>${this.escapeHtml(activity.title)}</strong>
            </td>
            <td>
                ${activity.type ? `<span class="badge bg-secondary">${this.escapeHtml(activity.type)}</span>` : '-'}
            </td>
            <td>
                <div class="description-cell" title="${this.escapeHtml(activity.description)}">
                    ${this.truncateText(activity.description, 50)}
                </div>
            </td>
            <td>${this.formatDate(activity.start_date)}</td>
            <td>${activity.end_date ? this.formatDate(activity.end_date) : '-'}</td>
            <td>
                <span class="badge ${statusClass}">${status}</span>
            </td>
            <td class="text-center">
                <span class="badge bg-info">${activity.participants || 0}</span>
            </td>
            <td>
                <div class="btn-group btn-group-sm" role="group">
                    <button class="btn btn-outline-primary" onclick="activityReports.viewActivity(${activity.id})" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                  
                   
                </div>
            </td>
        `;
        
        return row;
    }

    updateSummary() {
        const summaryCard = document.querySelector('#activity-reports .report-card .card-body .row');
        if (!summaryCard) return;

        const totalActivities = this.currentData.length;
        const totalParticipants = this.currentData.reduce((sum, activity) => sum + (parseInt(activity.participants) || 0), 0);
        
        // Find most popular program (only count activities with types)
        const programCounts = {};
        this.currentData.forEach(activity => {
            if (activity.type) {
                programCounts[activity.type] = (programCounts[activity.type] || 0) + (parseInt(activity.participants) || 0);
            }
        });
        
        const mostPopular = Object.keys(programCounts).length > 0 
            ? Object.keys(programCounts).reduce((a, b) => 
                programCounts[a] > programCounts[b] ? a : b
              )
            : 'None';

        // Count upcoming activities
        const now = new Date();
        const upcoming = this.currentData.filter(activity => 
            new Date(activity.start_date) > now
        ).length;

        const nextActivity = this.currentData
            .filter(activity => new Date(activity.start_date) > now)
            .sort((a, b) => new Date(a.start_date) - new Date(b.start_date))[0];

        // Update summary values
        const summaryItems = summaryCard.querySelectorAll('.col-md-3');
        if (summaryItems[0]) {
            summaryItems[0].querySelector('h4').textContent = totalActivities;
        }
        if (summaryItems[1]) {
            summaryItems[1].querySelector('h4').textContent = `${totalParticipants} residents`;
        }
        if (summaryItems[2]) {
            summaryItems[2].querySelector('h4').textContent = mostPopular;
            summaryItems[2].querySelector('small').textContent = `${programCounts[mostPopular] || 0} participants`;
        }
        if (summaryItems[3]) {
            summaryItems[3].querySelector('h4').textContent = upcoming;
            summaryItems[3].querySelector('small').textContent = 
                nextActivity ? `Next: ${nextActivity.title} (${this.formatDate(nextActivity.start_date)})` : 'No upcoming activities';
        }

        // Update month in header
        const monthSelect = document.getElementById('activity-month');
        const yearSelect = document.getElementById('activity-year');
        if (monthSelect && yearSelect) {
            const monthName = monthSelect.options[monthSelect.selectedIndex].text;
            const year = yearSelect.value;
            const headerTitle = document.querySelector('#activity-reports .report-card .card-title');
            if (headerTitle) {
                headerTitle.textContent = `Activities Summary (${monthName} ${year})`;
            }
        }
    }

    getActivityStatus(startDate, endDate) {
        const now = new Date();
        const start = new Date(startDate);
        const end = endDate ? new Date(endDate) : start;

        if (now < start) return 'Upcoming';
        if (now > end) return 'Completed';
        return 'Ongoing';
    }

    getStatusClass(status) {
        switch (status) {
            case 'Upcoming': return 'bg-warning';
            case 'Ongoing': return 'bg-success';
            case 'Completed': return 'bg-secondary';
            default: return 'bg-secondary';
        }
    }

    async viewActivity(id) {
        const activity = this.currentData.find(a => a.id == id);
        if (!activity) return;

        // Create modal for viewing activity details
        const modal = this.createViewModal(activity);
        document.body.appendChild(modal);
        
        // Show modal using Bootstrap
        if (typeof bootstrap !== 'undefined') {
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            
            modal.addEventListener('hidden.bs.modal', () => {
                document.body.removeChild(modal);
            });
        }
    }

    createViewModal(activity) {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Activity Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Activity Title</h6>
                                <p>${this.escapeHtml(activity.title)}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Type</h6>
                                <p>${activity.type ? `<span class="badge bg-secondary">${this.escapeHtml(activity.type)}</span>` : 'Not specified'}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Start Date</h6>
                                <p>${this.formatDate(activity.start_date)}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>End Date</h6>
                                <p>${activity.end_date ? this.formatDate(activity.end_date) : 'Not specified'}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Status</h6>
                                <p><span class="badge ${this.getStatusClass(this.getActivityStatus(activity.start_date, activity.end_date))}">${this.getActivityStatus(activity.start_date, activity.end_date)}</span></p>
                            </div>
                            <div class="col-md-6">
                                <h6>Participants</h6>
                                <p><span class="badge bg-info">${activity.participants || 0}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h6>Description</h6>
                                <p>${this.escapeHtml(activity.description)}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        `;
        return modal;
    }

    async deleteActivity(id) {
        if (!confirm('Are you sure you want to delete this activity? This action cannot be undone.')) {
            return;
        }

        try {
            const formData = new FormData();
            formData.append('action', 'delete_activity');
            formData.append('id', id);

            const response = await fetch('manage_activities.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            
            if (data.success) {
                this.showAlert('Activity deleted successfully!', 'success');
                this.applyFilters(); // Reload data
            } else {
                this.showAlert('Error deleting activity: ' + data.message, 'danger');
            }
        } catch (error) {
            console.error('Error deleting activity:', error);
            this.showAlert('Error deleting activity. Please try again.', 'danger');
        }
    }

    editActivity(id) {
        // This would typically open an edit modal or redirect to edit page
        // For now, we'll show an alert
        this.showAlert('Edit functionality would be implemented here.', 'info');
    }

    printReport() {
        const printContent = this.generatePrintContent();
        const printWindow = window.open('', '_blank');
        printWindow.document.write(printContent);
        printWindow.document.close();
        printWindow.print();
    }

    generatePrintContent() {
        const monthSelect = document.getElementById('activity-month');
        const yearSelect = document.getElementById('activity-year');
        const monthName = monthSelect.options[monthSelect.selectedIndex].text;
        const year = yearSelect.value;

        return `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Activity Report - ${monthName} ${year}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .header { text-align: center; margin-bottom: 30px; }
                    .summary { margin-bottom: 30px; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    .status { padding: 4px 8px; border-radius: 4px; }
                    .upcoming { background-color: #fff3cd; }
                    .ongoing { background-color: #d1edff; }
                    .completed { background-color: #f8d7da; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>Barangay Activity Report</h1>
                    <h2>${monthName} ${year}</h2>
                </div>
                <div class="summary">
                    <h3>Summary</h3>
                    <p>Total Activities: ${this.currentData.length}</p>
                    <p>Total Participants: ${this.currentData.reduce((sum, activity) => sum + (parseInt(activity.participants) || 0), 0)}</p>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Start_Date</th>
                            <th>End_Date</th>
                            <th>Status</th>
                            <th>Participants</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${this.currentData.map(activity => `
                            <tr>
                                <td>${this.escapeHtml(activity.title)}</td>
                                <td>${activity.type ? this.escapeHtml(activity.type) : '-'}</td>
                                <td>${this.formatDate(activity.start_date)}</td>
                                <td>${activity.end_date ? this.formatDate(activity.end_date) : '-'}</td>
                                <td class="status ${this.getActivityStatus(activity.start_date, activity.end_date).toLowerCase()}">${this.getActivityStatus(activity.start_date, activity.end_date)}</td>
                                <td>${activity.participants || 0}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </body>
            </html>
        `;
    }

    exportToExcel() {
        // Create CSV content
        const headers = ['Title', 'Type', 'Description', 'Start_Date', 'End_Date', 'Status', 'Participants'];
        const csvContent = [
            headers.join(','),
            ...this.currentData.map(activity => [
                `"${activity.title}"`,
                `"${activity.type || ''}"`,
                `"${activity.description}"`,
                activity.start_date,
                activity.end_date || '',
                this.getActivityStatus(activity.start_date, activity.end_date),
                activity.participants || 0
            ].join(','))
        ].join('\n');

        // Create and download file
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `activity_report_${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }

    // Utility functions
    formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }

    truncateText(text, length) {
        if (!text) return '';
        return text.length > length ? text.substring(0, length) + '...' : text;
    }

    escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    showAlert(message, type = 'info') {
        const alertContainer = document.getElementById('alert-container') || document.body;
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        alertContainer.appendChild(alert);
        
        setTimeout(() => {
            if (alert.parentNode) {
                alert.parentNode.removeChild(alert);
            }
        }, 5000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.activityReports = new ActivityReports();
});


document.addEventListener('shown.bs.tab', function (e) {
    if (e.target.id === 'activity-tab') {
        if (!window.activityReports) {
            window.activityReports = new ActivityReports();
        }
    }
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
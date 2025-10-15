<?php
// this file named view_residents.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

$sql1 = "SELECT * FROM residents";
$sql2 = "SELECT * FROM reg_online";

$result1 = $conn->query($sql1);
$result2 = $conn->query($sql2);




// Get the search term from the request
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Modify the query to filter based on the search term
$query = "SELECT * FROM residents WHERE CONCAT(last_name, first_name, middle_name, nickname, birthdate, birthplace, citizenship, gender, mobile_number, email, marital_status, religion, sector, education, height, weight, sitio, house_number, purok, since_year, household_number, house_owner, shelter_type, house_material) LIKE '%$searchTerm%'";

$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Residents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Prevent horizontal scrolling */
        html, body {
            width: 100%;
            min-height: 100vh;
            overflow-x: hidden;
        }

        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9998;
            display: none;
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            position: fixed;
            background-color: #31363F;
            height: 100vh;
            padding: 20px;
        }

        /* Main Content Adjustments */
        .main-content {
            margin-left: 220px;
            padding: 20px;
        }

        /* Floating Edit Form */
        #editForm {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            z-index: 9999;
            display: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
            /* Add max height and enable scrolling */
            max-height: 80vh; /* Limits height to 80% of the viewport */
            overflow-y: auto; /* Enables vertical scrolling */
            border-radius: 8px; /* Optional: Rounds corners */
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
        color: #eff319; /* Change to any color you like */
    }

    /* view information style */
/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
    animation: fadeIn 0.3s ease-in-out;
}

.modal-content {
    position: relative;
    background-color: #fff;
    margin: 5% auto;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    width: 60%;
    max-width: 800px;
    animation: slideIn 0.3s ease-in-out;
}

/* Close Button */
.close {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 24px;
    color: #888;
    cursor: pointer;
    transition: color 0.2s ease;
}

.close:hover {
    color: #333;
}

/* Overlay Styles */
#overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
    animation: fadeIn 0.3s ease-in-out;
}

/* Biodata Styles */
.biodata {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.biodata-header {
    display: flex;
    align-items: center;
    gap: 20px;
    border-bottom: 2px solid #ddd;
    padding-bottom: 20px;
}

.biodata-header img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 4px solid #ddd;
}

.biodata-header h3 {
    margin: 0;
    font-size: 24px;
    color: #222;
}

.biodata-header p {
    margin: 5px 0;
    font-size: 16px;
    color: #555;
}

.biodata-section {
    margin-bottom: 20px;
}

.biodata-section h4 {
    margin: 0 0 10px 0;
    font-size: 18px;
    color: #222;
    border-bottom: 1px solid #ddd;
    padding-bottom: 5px;
}

.biodata-section p {
    margin: 5px 0;
    font-size: 16px;
    color: #333;
}

.biodata-section p strong {
    color: #222;
    font-weight: 600;
}



/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .modal-content {
        width: 90%;
        margin: 10% auto;
    }

    .biodata-header {
        flex-direction: column;
        text-align: center;
    }

    .biodata-header img {
        width: 100px;
        height: 100px;
    }
}

/* Table Wrapper */
.table-wrapper {
    max-height: 500px;
    overflow-y: auto;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    position: relative;
    
}

/* Scrollbar Styling */
.table-wrapper::-webkit-scrollbar {
    width: 8px;
}

.table-wrapper::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.table-wrapper::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.table-wrapper::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Table Styling */
.table {
    width: 100%;
    border-collapse: collapse;
    animation: fadeIn 1s ease-in-out;
}

.table th, .table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.table th {
    background-color: #f8f9fa;
    position: sticky;
    top: 0;
    z-index: 10;
}

/* Hover Effect */
.table tbody tr:hover {
    background-color: #f1f1f1;
    transition: background-color 0.3s ease;
}

/* Fade-in Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Scroll to Top Button */
#scrollToTopBtn {
    display: none;
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 99;
    border: none;
    outline: none;
    background-color: #007bff;
    color: white;
    cursor: pointer;
    padding: 12px;
    border-radius: 50%;
    font-size: 18px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease, transform 0.3s ease;
}

#scrollToTopBtn:hover {
    background-color: #0056b3;
    transform: scale(1.1);
}


/* Modern Table Enhancements - Add these to your existing CSS */

/* Enhanced Table Wrapper with Glass Effect */
.table-wrapper {
    max-height: 500px;
    overflow-y: auto;
    border: none;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    position: relative;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Modern Table Styling */
.table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    animation: fadeIn 1s ease-in-out;
    background: transparent;
}

/* Enhanced Table Header */
.table th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 16px 12px;
    text-align: left;
    border: none;
    position: sticky;
    top: 0;
    z-index: 10;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
    border-bottom: 3px solid rgba(255, 255, 255, 0.2);
}

/* First and last header cells rounded corners */
.table th:first-child {
    border-top-left-radius: 16px;
}

.table th:last-child {
    border-top-right-radius: 16px;
}

/* Enhanced Table Cells */
.table td {
    padding: 14px 12px;
    border: none;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    background: rgba(255, 255, 255, 0.8);
    transition: all 0.3s ease;
    vertical-align: middle;
}

/* Modern Row Hover Effect */
.table tbody tr {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 8px;
}

.table tbody tr:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

.table tbody tr:hover td {
    background: transparent;
    border-bottom-color: rgba(102, 126, 234, 0.2);
}

/* Selected Row Enhancement */
.table tbody tr.table-primary {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%) !important;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
    border-left: 4px solid #667eea;
}

.table tbody tr.table-primary td {
    background: transparent;
    border-bottom-color: rgba(102, 126, 234, 0.3);
    font-weight: 500;
}

/* Profile Image Enhancement */
.table td img {
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
    object-fit: cover;
}

.table td img:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
}

/* Enhanced Scrollbar */
.table-wrapper::-webkit-scrollbar {
    width: 12px;
}

.table-wrapper::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 6px;
}

.table-wrapper::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 6px;
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.table-wrapper::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #5a6fd8, #6a42a6);
}

/* Zebra Striping with Modern Touch */
.table tbody tr:nth-child(even) td {
    background: rgba(102, 126, 234, 0.02);
}

/* Action Buttons Container Enhancement */
.text-end {
    margin-bottom: 16px;
}

.text-end .btn {
    margin-left: 8px;
    border-radius: 25px;
    padding: 10px 20px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.85rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.text-end .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
}

.text-end .btn-success {
    background: linear-gradient(135deg, #11998e, #38ef7d);
}

.text-end .btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
}

.text-end .btn-info {
    background: linear-gradient(135deg, #36d1dc, #5b86e5);
}

/* Loading Animation for Table Rows */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.table tbody tr {
    animation: slideInUp 0.6s ease-out backwards;
}

.table tbody tr:nth-child(1) { animation-delay: 0.1s; }
.table tbody tr:nth-child(2) { animation-delay: 0.2s; }
.table tbody tr:nth-child(3) { animation-delay: 0.3s; }
.table tbody tr:nth-child(4) { animation-delay: 0.4s; }
.table tbody tr:nth-child(5) { animation-delay: 0.5s; }

/* Modern Pagination Enhancement */
.pagination .page-item .page-link {
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 4px;
    background: rgba(255, 255, 255, 0.8);
    color: #667eea;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.pagination .page-item .page-link:hover {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(102, 126, 234, 0.4);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    box-shadow: 0 4px 16px rgba(102, 126, 234, 0.4);
}

/* Add subtle glow effect to the table */
.table-wrapper::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 18px;
    z-index: -1;
    opacity: 0.1;
}

/* Modern Search Bar Enhancement */
.mb-4.mt-3 {
    position: relative;
}

#search {
    border: none;
    border-radius: 25px;
    padding: 14px 20px 14px 50px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    font-size: 16px;
    color: #333;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    outline: none;
    width: 100% !important;
}

#search:focus {
    background: rgba(255, 255, 255, 1);
    box-shadow: 0 12px 40px rgba(102, 126, 234, 0.2);
    border: 2px solid #667eea;
    transform: translateY(-2px);
}

#search::placeholder {
    color: #888;
    font-weight: 400;
}

/* Search Icon */
.mb-4.mt-3::before {
    content: '🔍';
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 18px;
    z-index: 10;
    opacity: 0.6;
    transition: all 0.3s ease;
}

.mb-4.mt-3:focus-within::before {
    opacity: 1;
    color: #667eea;
}

/* Search Container Enhancement */
.mb-4.mt-3 {
    margin-bottom: 2rem !important;
    margin-top: .6rem !important;
}

#toast {
    visibility: hidden;
    min-width: 250px;
    margin-left: -125px;
    background-color: green;
    color: white;
    text-align: center;
    border-radius: 8px;
    padding: 16px;
    position: fixed;
    z-index: 1;
    left: 50%;
    bottom: 30px;
    font-size: 16px;
    opacity: 0;
    transition: opacity 0.5s, bottom 0.5s;
}

#toast.show {
    visibility: visible;
    opacity: 1;
    bottom: 370px;
    
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


        
     /* Enhanced Modal Design */
.modal-dialog {
    max-width: 95vw;
    width: 95vw;
    height: 85vh;
    margin: 2.5vh auto;
}

.modal-content {
    height: 100%;
    display: flex;
    flex-direction: column;
    border: none;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    background: #ffffff;
    overflow: hidden;
}

/* Modern Header */
.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem 2rem;
    border-bottom: none;
    position: relative;
    z-index: 10;
}

.modal-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    z-index: -1;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
}

.modal-title i {
    background: rgba(255,255,255,0.2);
    padding: 8px;
    border-radius: 8px;
    margin-right: 0.75rem;
}

/* Custom Close Button */
.btn-close {
    background: rgba(255,255,255,0.2);
    border: 2px solid rgba(255,255,255,0.3);
    border-radius: 8px;
    padding: 8px;
    width: 36px;
    height: 36px;
    opacity: 1;
    transition: all 0.3s ease;
    position: relative;
}

.btn-close:hover {
    background: rgba(255,255,255,0.3);
    transform: rotate(90deg);
    opacity: 1;
}

.btn-close::before {
    content: '×';
    color: white;
    font-size: 20px;
    font-weight: bold;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

/* Enhanced Body */
.modal-body {
    max-height: calc(85vh - 140px);
    overflow-y: auto;
    overflow-x: hidden;
    width: 100%;
    flex: 1;
    padding: 2rem;
    background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
    position: relative;
}

/* Custom Scrollbar */
.modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #5a67d8, #6b46c1);
}

/* Enhanced Profile Image */
.profile-img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 12px;
    border: 3px solid #e2e8f0;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.profile-img:hover {
    transform: scale(1.1);
    border-color: #667eea;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

/* Modern Table Design */
.table {
    border-radius: 12px;
    overflow: hidden;
    background-color: #ffffff;
    width: 100%;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
}

.table th {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    font-weight: 600;
    text-align: center;
    color: #475569;
    font-size: 0.9rem;
    padding: 1rem 0.75rem;
    border-bottom: 2px solid #cbd5e1;
}

.table td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
    text-align: center;
    border-bottom: 1px solid #f1f5f9;
    transition: background-color 0.2s ease;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: #f8fafc;
    transform: scale(1.01);
}

.table tbody tr:last-child td {
    border-bottom: none;
}

/* Enhanced Buttons */
.btn-sm {
    padding: 0.4rem 0.8rem;
    font-size: 0.8rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    margin: 0 2px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}

.btn-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
}

/* Modern Status Badges */
.status-badge {
    font-size: 0.75rem;
    padding: 0.4rem 0.8rem;
    width: fit-content;
    border-radius: 20px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.status-badge::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}

.badge-pending {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
}

.badge-approved {
    background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    color: #166534;
}

.badge-rejected {
    background: linear-gradient(135deg, #fecaca, #fca5a5);
    color: #991b1b;
}

/* Enhanced Table Responsive */
.table-responsive {
    font-size: 0.9rem;
    overflow-x: auto;
    border-radius: 12px;
    background: white;
}

/* Modal Footer */
.modal-footer {
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    padding: 1rem 2rem;
    border-bottom-left-radius: 16px;
    border-bottom-right-radius: 16px;
}

.modal-footer .btn {
    padding: 0.6rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
}

.modal-footer .btn-secondary {
    background: #e2e8f0;
    color: #475569;
}

.modal-footer .btn-secondary:hover {
    background: #cbd5e1;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Remove Default Backdrop */
.modal-backdrop {
    display: none !important;
}

/* Loading States */
.loading-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 300px;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
    border-width: 0.3em;
    color: #667eea;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem;
    color: #64748b;
}

.empty-state i {
    font-size: 4rem;
    color: #cbd5e1;
    margin-bottom: 1rem;
}

.empty-state h4 {
    color: #475569;
    margin-bottom: 0.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .modal-dialog {
        max-width: 98vw;
        width: 98vw;
        height: 90vh;
        margin: 5vh auto;
    }
    
    .modal-header {
        padding: 1rem 1.5rem;
    }
    
    .modal-body {
        padding: 1rem 1.5rem;
    }
    
    .modal-footer {
        padding: 1rem 1.5rem;
    }
    
    .modal-title {
        font-size: 1.25rem;
    }
    
    .table th,
    .table td {
        padding: 0.5rem;
        font-size: 0.8rem;
    }
    
    .profile-img {
        width: 40px;
        height: 40px;
    }
    
    .btn-sm {
        padding: 0.3rem 0.6rem;
        font-size: 0.75rem;
    }
}

/* Remove all horizontal scrolling */
#pendingRequestsModal * {
    overflow-x: hidden !important;
}

#pendingRequestsModal .modal-dialog {
    overflow: hidden;
}

#pendingRequestsModal .modal-content {
    overflow: hidden;
}

#pendingRequestsModal .modal-body {
    overflow-x: hidden !important;
    overflow-y: auto;
    width: 100%;
    box-sizing: border-box;
}

#pendingRequestsModal #pendingRequestsContent {
    width: 100%;
    overflow: hidden !important;
}

/* Table container without horizontal scroll */
#pendingRequestsModal .table-responsive {
    overflow-x: hidden !important;
    overflow-y: visible;
    width: 100%;
}

/* Table optimization */
#pendingRequestsModal .table {
    width: 100%;
    table-layout: fixed;
    margin: 0;
    overflow: hidden;
}

#pendingRequestsModal .table th,
#pendingRequestsModal .table td {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    padding: 0.5rem 0.25rem;
    font-size: 0.8rem;
}

/* Fixed column widths */
#pendingRequestsModal .table th:nth-child(1),
#pendingRequestsModal .table td:nth-child(1) {
    width: 8%;
    min-width: 60px;
}

#pendingRequestsModal .table th:nth-child(2),
#pendingRequestsModal .table td:nth-child(2) {
    width: 12%;
}

#pendingRequestsModal .table th:nth-child(3),
#pendingRequestsModal .table td:nth-child(3) {
    width: 20%;
}

#pendingRequestsModal .table th:nth-child(4),
#pendingRequestsModal .table td:nth-child(4) {
    width: 12%;
}

#pendingRequestsModal .table th:nth-child(5),
#pendingRequestsModal .table td:nth-child(5) {
    width: 18%;
}

#pendingRequestsModal .table th:nth-child(6),
#pendingRequestsModal .table td:nth-child(6) {
    width: 10%;
}

#pendingRequestsModal .table th:nth-child(7),
#pendingRequestsModal .table td:nth-child(7) {
    width: 20%;
    white-space: normal;
}

/* Action buttons styling */
#pendingRequestsModal .btn-sm {
    padding: 0.2rem 0.4rem;
    font-size: 0.7rem;
    margin: 0 1px;
    display: inline-block;
}

/* Profile image */
#pendingRequestsModal .profile-img {
    width: 35px;
    height: 35px;
    object-fit: cover;
    border-radius: 4px;
}

/* Status badges */
#pendingRequestsModal .status-badge {
    font-size: 0.65rem;
    padding: 0.15rem 0.4rem;
    border-radius: 10px;
    display: inline-block;
}

/* Ensure no content exceeds container */
#pendingRequestsModal .table td {
    max-width: 0;
    word-wrap: break-word;
}

#pendingRequestsModal .table td:nth-child(7) {
    max-width: none;
    white-space: normal;
}

/* Remove any potential scrollbars */
#pendingRequestsModal ::-webkit-scrollbar-horizontal {
    display: none;
}

#pendingRequestsModal {
    scrollbar-width: none;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    #pendingRequestsModal .table {
        font-size: 0.7rem;
    }
    
    #pendingRequestsModal .table th,
    #pendingRequestsModal .table td {
        padding: 0.3rem 0.15rem;
    }
    
    #pendingRequestsModal .btn-sm {
        font-size: 0.65rem;
        padding: 0.15rem 0.3rem;
    }
    
    #pendingRequestsModal .profile-img {
        width: 30px;
        height: 30px;
    }
}


/* Animation for modal appearance */
.modal.fade .modal-dialog {
    transform: translateY(-50px) scale(0.95);
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.modal.show .modal-dialog {
    transform: translateY(0) scale(1);
}

    </style>
</head>
<body class="bg-light">

   <!-- Sidebar -->
<div class="sidebar text-white p-3" style="width: 235px; background-color: #31363F;">
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
            <a class="nav-link dropdown-toggle text-white d-flex align-items-center sidebar-item" data-page="residents">
                <i class="fas fa-user-friends me-2"></i> Barangay Residents
                <div class="page-indicator"></div>
            </a>
            <ul class="dropdown-menu animate-dropdown">
                <li><a class="dropdown-item" href="Residents.html">Register Residents</a></li>
                <li><a class="dropdown-item" href="view_residents.php" data-page="view-residents">Registered Residents</a></li>
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
</div>


    <!-- Main Content -->
    <div class="container mt-5" style="margin-left: 223px;">
        <h2 class="p-3 text-white fs-5" style="background-color: #222831; margin-top: -50px; margin-right: -27px;">
            <img src="logo.png" alt="Logo" style="height: 30px; width: auto; margin-right: 10px;">
            Barangay San Carlos, City of Valencia, Province of Bukidnon
        </h2>
    </div>

  <!-- Search Bar -->
<div class="mb-4 mt-3" style="margin-left: 245px; margin-right: 650px;">
    <input type="text" id="search" class="form-control w-75" placeholder="Search for residents...">
</div>


  <!-- Residents Table -->
<div class="table-responsive" style="margin-left: 226px; margin-right: 5px; margin-top: -80px;">

<!-- Buttons (Fixed Position) -->
<div class="text-end mt-3 mb-2" >
     <button class="btn btn-success" onclick="openPendingRequestsModal()">Pending Request</button>
   <button class="btn btn-primary" onclick="openUpdateModal()" id="updateBtn" disabled>
    <i class="fas fa-edit"></i> Update
</button>

    
</div>

<!-- Scrollable Table Wrapper -->
<div class="table-wrapper" style="margin-left: 10px;">
    <table class="table table-hover table-bordered">
        <thead class="table-light">
            <tr>
                <th>Profile</th>
                <th>Last_Name</th>
                <th>First_Name</th>
                <th>Middle_Name</th>
                <th>Nickname</th>
                <th>Birthdate</th>
                <th>Birth_Place</th>
                <th>Citizenship</th>
                <th>Gender</th>
                <th>Mobile_Number</th>
                <th>Email</th>
                <th>Marital_Status</th>
                <th>Religion</th>
                <th>Sector</th>
                <th>Education</th>
                <th>Height(cm)</th>
                <th>Weight(kg)</th>
                <th>Sitio</th>
                <th>House/Bldg.#</th>
                <th>Purok</th>
                <th>Since_Year</th>
                <th>Household_No.</th>
                <th>House_Owner?</th>
                <th>Shelter_Type</th>
                <th>House_Material</th>
            </tr>
        </thead>
        <tbody>  
           
          <?php
            // Pagination Variables
            $limit = 30;  // Number of records per page
            $page = isset($_GET['page']) ? $_GET['page'] : 1;  // Current page
            $offset = ($page - 1) * $limit;

            // Get the total number of rows
            $result_count = $conn->query("SELECT COUNT(*) AS total FROM residents");
            $total_rows = $result_count->fetch_assoc()['total'];
            $total_pages = ceil($total_rows / $limit);
            

            // Fetch the data for the current page
            $result = $conn->query("SELECT * FROM residents LIMIT $limit OFFSET $offset");

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr onclick='selectRow(this, event)'>
                       <td><img src='" . (!empty($row['profile_image']) ? $row['profile_image'] : 'uploads/default-profile.jpg') . "' width='50' height='50'></td>
                        <td>{$row['last_name']}</td>
                        <td>{$row['first_name']}</td>
                        <td>{$row['middle_name']}</td>
                        <td>{$row['nickname']}</td>
                        <td>{$row['birthdate']}</td>
                        <td>{$row['birthplace']}</td>
                        <td>{$row['citizenship']}</td>
                        <td>{$row['gender']}</td>
                        <td>{$row['mobile_number']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['marital_status']}</td>
                        <td>{$row['religion']}</td>
                        <td>{$row['sector']}</td>
                        <td>{$row['education']}</td>
                        <td>{$row['height']}</td>
                        <td>{$row['weight']}</td>
                        <td>{$row['sitio']}</td>
                        <td>{$row['house_number']}</td>
                        <td>{$row['purok']}</td>
                        <td>{$row['since_year']}</td>
                        <td>{$row['household_number']}</td>
                        <td>{$row['house_owner']}</td>
                        <td>{$row['shelter_type']}</td>
                        <td>{$row['house_material']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='26' class='text-center'>No residents found</td></tr>";
            }
            ?>
        </tbody>
    </table>

   <!-- Pagination Dropdown -->
<div class="d-flex justify-content-between align-items-center mt-3" style="margin-left: 10px;">
    <div class="d-flex align-items-center">
        <span class="me-2">Page:</span>
        <select class="form-select" style="width: auto;" onchange="changePage(this.value)">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <option value="<?php echo $i; ?>" <?php echo ($i == $page) ? 'selected' : ''; ?>>
                    <?php echo $i; ?>
                </option>
            <?php endfor; ?>
        </select>
        <span class="ms-2">of <?php echo $total_pages; ?> pages</span>
    </div>
    
    <div class="d-flex align-items-center">
        <?php if ($page > 1): ?>
            <button class="btn btn-sm btn-outline-primary me-2" onclick="changePage(<?php echo $page - 1; ?>)">
                <i class="fas fa-chevron-left"></i> Previous
            </button>
        <?php endif; ?>
        
        <?php if ($page < $total_pages): ?>
            <button class="btn btn-sm btn-outline-primary" onclick="changePage(<?php echo $page + 1; ?>)">
                Next <i class="fas fa-chevron-right"></i>
            </button>
        <?php endif; ?>
    </div>
</div>


    

    <!-- Overlay -->
    <div id="overlay"></div>

  

<!-- Overlay for the modal -->
<div id="overlay" onclick="closeModal()"></div>
<div id="toast"></div>


<!-- Modal -->
<div class="modal fade" id="pendingRequestsModal" tabindex="-1" aria-labelledby="pendingRequestsModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog" style="max-width: 99vw; width: 99vw; margin: 0.5vh auto; margin-top: -50px; ">
        <div class="modal-content" style="height: 98vh; margin-right: 400px; margin-top: 70px;" >
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="pendingRequestsModalLabel">
                    <i class="fas fa-clock me-2"></i>Pending Registration Requests
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="height: calc(98vh - 140px); overflow-y: auto; overflow-x: hidden; padding: 1rem;">
                <div id="pendingRequestsContent" style="width: 100%; overflow: hidden;">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading pending requests...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="refreshPendingRequests()">
                    <i class="fas fa-sync-alt me-1"></i>Refresh
                </button>
            </div>
        </div>
    </div>
</div>



<!-- Update Modal -->
<div class="modal fade" id="updateResidentModal" tabindex="-1" aria-labelledby="updateResidentModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-lg" style="margin-left: -100px; margin-top: -50px;">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="updateResidentModalLabel">
                    <i class="fas fa-user-edit me-2"></i>Update Resident Information
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <form id="updateResidentForm">
                    <input type="hidden" id="resident_id" name="resident_id">
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="update_last_name" name="last_name" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">First Name</label>
                            <input type="text" class="form-control" id="update_first_name" name="first_name" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="update_middle_name" name="middle_name">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Nickname</label>
                            <input type="text" class="form-control" id="update_nickname" name="nickname">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Birthdate</label>
                            <input type="date" class="form-control" id="update_birthdate" name="birthdate" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gender</label>
                            <select class="form-control" id="update_gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Birthplace</label>
                            <input type="text" class="form-control" id="update_birthplace" name="birthplace">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Citizenship</label>
                            <input type="text" class="form-control" id="update_citizenship" name="citizenship">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" id="update_mobile" name="mobile_number">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="update_email" name="email">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Marital Status</label>
                            <select class="form-control" id="update_marital" name="marital_status">
                                <option value="">Select Status</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Religion</label>
                            <input type="text" class="form-control" id="update_religion" name="religion">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sector</label>
                            <input type="text" class="form-control" id="update_sector" name="sector">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Education</label>
                            <input type="text" class="form-control" id="update_education" name="education">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Height (cm)</label>
                            <input type="number" class="form-control" id="update_height" name="height">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Weight (kg)</label>
                            <input type="number" class="form-control" id="update_weight" name="weight">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Sitio</label>
                            <input type="text" class="form-control" id="update_sitio" name="sitio">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">House Number</label>
                            <input type="text" class="form-control" id="update_house_number" name="house_number">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Purok</label>
                            <input type="text" class="form-control" id="update_purok" name="purok">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Since Year</label>
                            <input type="number" class="form-control" id="update_since_year" name="since_year">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Household Number</label>
                            <input type="text" class="form-control" id="update_household" name="household_number">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">House Owner?</label>
                            <select class="form-control" id="update_house_owner" name="house_owner">
                                <option value="">Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Shelter Type</label>
                            <input type="text" class="form-control" id="update_shelter" name="shelter_type">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">House Material</label>
                            <input type="text" class="form-control" id="update_house_material" name="house_material">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveUpdatedResident()">
                    <i class="fas fa-save me-1"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let selectedResidentRow = null;
let updateResidentModal;

document.addEventListener('DOMContentLoaded', function() {
    updateResidentModal = new bootstrap.Modal(document.getElementById('updateResidentModal'));
});

// Modified selectRow function to enable update button
function selectRow(row, event) {
    // Prevent selection if clicking on buttons or images
    if (event.target.tagName === 'BUTTON' || event.target.tagName === 'IMG') {
        return;
    }
    
    // Remove previous selection
    const previousSelected = document.querySelector('tbody tr.table-primary');
    if (previousSelected) {
        previousSelected.classList.remove('table-primary');
    }
    
    // Add selection to current row
    row.classList.add('table-primary');
    selectedResidentRow = row;
    
    // Enable update button
    document.getElementById('updateBtn').disabled = false;
}

function openUpdateModal() {
    if (!selectedResidentRow) {
        showToast('Please select a resident first', 'warning');
        return;
    }
    
    // Get data from selected row
    const cells = selectedResidentRow.cells;
    
    // Populate form fields
    document.getElementById('update_last_name').value = cells[1].textContent;
    document.getElementById('update_first_name').value = cells[2].textContent;
    document.getElementById('update_middle_name').value = cells[3].textContent;
    document.getElementById('update_nickname').value = cells[4].textContent;
    document.getElementById('update_birthdate').value = cells[5].textContent;
    document.getElementById('update_birthplace').value = cells[6].textContent;
    document.getElementById('update_citizenship').value = cells[7].textContent;
    document.getElementById('update_gender').value = cells[8].textContent;
    document.getElementById('update_mobile').value = cells[9].textContent;
    document.getElementById('update_email').value = cells[10].textContent;
    document.getElementById('update_marital').value = cells[11].textContent;
    document.getElementById('update_religion').value = cells[12].textContent;
    document.getElementById('update_sector').value = cells[13].textContent;
    document.getElementById('update_education').value = cells[14].textContent;
    document.getElementById('update_height').value = cells[15].textContent;
    document.getElementById('update_weight').value = cells[16].textContent;
    document.getElementById('update_sitio').value = cells[17].textContent;
    document.getElementById('update_house_number').value = cells[18].textContent;
    document.getElementById('update_purok').value = cells[19].textContent;
    document.getElementById('update_since_year').value = cells[20].textContent;
    document.getElementById('update_household').value = cells[21].textContent;
    document.getElementById('update_house_owner').value = cells[22].textContent;
    document.getElementById('update_shelter').value = cells[23].textContent;
    document.getElementById('update_house_material').value = cells[24].textContent;
    
    
    
    updateResidentModal.show();
}

function saveUpdatedResident() {
    const form = document.getElementById('updateResidentForm');
    const formData = new FormData(form);
    
    
    const originalLastName = selectedResidentRow.cells[1].textContent;
    const originalFirstName = selectedResidentRow.cells[2].textContent;
    formData.append('original_last_name', originalLastName);
    formData.append('original_first_name', originalFirstName);
    
    showConfirmDialog('Are you sure you want to update this resident\'s information?', () => {
        fetch('update_resident.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                
                updateResidentModal.hide();
                
                // Refresh page after short delay
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showToast('Error updating resident: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error updating resident information', 'error');
        });
    });
}
</script>

   

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        let pendingRequestsModal;
        let viewDetailsModal;

        document.addEventListener('DOMContentLoaded', function() {
            pendingRequestsModal = new bootstrap.Modal(document.getElementById('pendingRequestsModal'));
           
        });

        function openPendingRequestsModal() {
            pendingRequestsModal.show();
            loadPendingRequests();
        }

        function loadPendingRequests() {
            // Show loading spinner
            document.getElementById('pendingRequestsContent').innerHTML = `
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading pending requests...</p>
                </div>
            `;

            // Fetch pending requests
            fetch('get_pending_requests.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayPendingRequests(data.requests);
                    } else {
                        document.getElementById('pendingRequestsContent').innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Error loading requests: ${data.message}
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('pendingRequestsContent').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error loading requests. Please try again.
                        </div>
                    `;
                });
        }

        function displayPendingRequests(requests) {
            if (requests.length === 0) {
                document.getElementById('pendingRequestsContent').innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Pending Requests</h5>
                        <p class="text-muted">All registration requests have been processed.</p>
                    </div>
                `;
                return;
            }

            let html = `
                <div class="table-responsive" style="margin-right:">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Photo</th>
                                <th>FUll_Name</th>
                                <th>Email</th>
                              
                                <th>Address</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            requests.forEach(request => {
                const profileImg = request.profile_upload ? request.profile_upload : 'https://via.placeholder.com/50x50?text=No+Image';
                const fullName = `${request.first_name} ${request.middle_name ? request.middle_name + ' ' : ''}${request.last_name}`;
                const address = `${request.house_number ? request.house_number + ', ' : ''}${request.sitio}, ${request.purok}`;
                
                html += `
                    <tr>
                        <td>
                            <img src="${profileImg}" alt="Profile" class="profile-img" onerror="this.src='https://via.placeholder.com/50x50?text=No+Image'">
                        </td>
                        <td>
                            <strong>${fullName}</strong><br>
                            <small class="text-muted">${request.nickname_alias || ''}</small>
                        </td>
                        <td>${request.email}</td>
                       
                        <td>
                            <small>${address}</small>
                        </td>
                        <td>
                            <span class="badge bg-warning status-badge">Pending</span>
                        </td>
                        <td>
                            <div class="btn-group-vertical btn-group-sm" role="group">
                               
                                <button class="btn btn-success btn-sm mb-1" onclick="approveRequest(${request.id})">
                                    <i class="fas fa-check"></i> Accept
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="declineRequest(${request.id})">
                                    <i class="fas fa-times"></i> Decline
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            html += `
                        </tbody>
                    </table>
                </div>
            `;

            document.getElementById('pendingRequestsContent').innerHTML = html;
        }

      
      // Custom confirmation dialog
function showConfirmDialog(message, onConfirm, onCancel = null) {
    // Create overlay
    const overlay = document.createElement('div');
    overlay.className = 'confirm-overlay';
    Object.assign(overlay.style, {
        position: 'fixed',
        top: '0',
        left: '0',
        width: '100%',
        height: '100%',
        backgroundColor: 'rgba(0,0,0,0.5)',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        zIndex: '10000',
        opacity: '0',
        transition: 'opacity 0.3s ease'
    });
    
    // Create dialog
    const dialog = document.createElement('div');
    dialog.className = 'confirm-dialog';
    Object.assign(dialog.style, {
        backgroundColor: 'white',
        borderRadius: '12px',
        padding: '24px',
        maxWidth: '400px',
        width: '90%',
        boxShadow: '0 10px 25px rgba(0,0,0,0.2)',
        transform: 'scale(0.9)',
        transition: 'transform 0.3s ease'
    });
    
    // Create message
    const messageEl = document.createElement('p');
    messageEl.textContent = message;
    Object.assign(messageEl.style, {
        margin: '0 0 20px 0',
        fontSize: '16px',
        color: '#374151',
        lineHeight: '1.5'
    });
    
    // Create buttons container
    const buttonsContainer = document.createElement('div');
    Object.assign(buttonsContainer.style, {
        display: 'flex',
        gap: '12px',
        justifyContent: 'flex-end'
    });
    
    // Create cancel button
    const cancelBtn = document.createElement('button');
    cancelBtn.textContent = 'Cancel';
    cancelBtn.className = 'btn-cancel';
    Object.assign(cancelBtn.style, {
        padding: '10px 20px',
        border: '1px solid #d1d5db',
        borderRadius: '6px',
        backgroundColor: 'white',
        color: '#374151',
        cursor: 'pointer',
        fontSize: '14px',
        fontWeight: '500'
    });
    
    // Create confirm button
    const confirmBtn = document.createElement('button');
    confirmBtn.textContent = 'Confirm';
    confirmBtn.className = 'btn-confirm';
    Object.assign(confirmBtn.style, {
        padding: '10px 20px',
        border: 'none',
        borderRadius: '6px',
        backgroundColor: '#3b82f6',
        color: 'white',
        cursor: 'pointer',
        fontSize: '14px',
        fontWeight: '500'
    });
    
    // Add hover effects
    cancelBtn.onmouseenter = () => cancelBtn.style.backgroundColor = '#f9fafb';
    cancelBtn.onmouseleave = () => cancelBtn.style.backgroundColor = 'white';
    confirmBtn.onmouseenter = () => confirmBtn.style.backgroundColor = '#2563eb';
    confirmBtn.onmouseleave = () => confirmBtn.style.backgroundColor = '#3b82f6';
    
    // Function to close dialog
    const closeDialog = () => {
        overlay.style.opacity = '0';
        dialog.style.transform = 'scale(0.9)';
        setTimeout(() => {
            if (overlay.parentNode) {
                overlay.parentNode.removeChild(overlay);
            }
        }, 300);
    };
    
    // Add event listeners
    cancelBtn.onclick = () => {
        closeDialog();
        if (onCancel) onCancel();
    };
    
    confirmBtn.onclick = () => {
        closeDialog();
        onConfirm();
    };
    
    // Close on overlay click
    overlay.onclick = (e) => {
        if (e.target === overlay) {
            closeDialog();
            if (onCancel) onCancel();
        }
    };
    
    // Build dialog
    buttonsContainer.appendChild(cancelBtn);
    buttonsContainer.appendChild(confirmBtn);
    dialog.appendChild(messageEl);
    dialog.appendChild(buttonsContainer);
    overlay.appendChild(dialog);
    
    // Add to page
    document.body.appendChild(overlay);
    
    // Animate in
    setTimeout(() => {
        overlay.style.opacity = '1';
        dialog.style.transform = 'scale(1)';
    }, 10);
}

// Toast notification function
function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    
    // Toast styles (you can customize these or use your existing CSS)
    Object.assign(toast.style, {
        position: 'fixed',
        top: '20px',
        right: '20px',
        padding: '12px 20px',
        borderRadius: '8px',
        color: 'white',
        fontSize: '14px',
        fontWeight: '500',
        zIndex: '9999',
        minWidth: '250px',
        maxWidth: '400px',
        boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
        transform: 'translateX(100%)',
        opacity: '0',
        transition: 'all 0.3s ease-in-out'
    });
    
    // Set background color based on type
    const colors = {
        success: '#10b981',
        error: '#ef4444',
        warning: '#f59e0b',
        info: '#3b82f6'
    };
    toast.style.backgroundColor = colors[type] || colors.info;
    
    // Add to page
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
        toast.style.opacity = '1';
    }, 10);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        toast.style.opacity = '0';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 4000);
}

function approveRequest(requestId) {
    showConfirmDialog('Are you sure you want to approve this registration request?', () => {
        fetch('process_request.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'approve',
                request_id: requestId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadPendingRequests(); // Refresh the list
            } else {
                showToast('Error approving request: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error processing request', 'error');
        });
    });
}

function declineRequest(requestId) {
    showConfirmDialog('Are you sure you want to decline this registration request?', () => {
        fetch('process_request.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'decline',
                request_id: requestId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadPendingRequests(); // Refresh the list
            } else {
                showToast('Error declining request: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error processing request', 'error');
        });
    });
}

function refreshPendingRequests() {
    loadPendingRequests();
}
    </script>







<script>
    function showToast(message) {
    const toast = document.getElementById("toast");
    toast.textContent = message;
    toast.className = "show";
    setTimeout(() => {
        toast.className = toast.className.replace("show", "");
    }, 3000); // Hide after 3 seconds
}



</script>

<script>
    // Smooth Scroll for Table
document.querySelector('.table-wrapper').addEventListener('scroll', function() {
    const scrollTopBtn = document.getElementById('scrollToTopBtn');
    if (this.scrollTop > 100) {
        scrollTopBtn.style.display = 'block';
    } else {
        scrollTopBtn.style.display = 'none';
    }
});

// Scroll to Top Functionality
document.getElementById('scrollToTopBtn').addEventListener('click', function() {
    document.querySelector('.table-wrapper').scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});
</script>

    <script>
       

        document.getElementById('search').addEventListener('keyup', function () {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll("tbody tr");
    let foundItems = 0;

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchValue)) {
            row.style.display = "";
            foundItems++;
        } else {
            row.style.display = "none";
        }
    });

    // Handle the pagination buttons dynamically based on the search results
    const paginationLinks = document.querySelectorAll(".pagination .page-item");
    if (foundItems === 0) {
        paginationLinks.forEach(link => link.style.display = "none");  // Hide pagination if no match
    } else {
        paginationLinks.forEach(link => link.style.display = "");  // Show pagination if results exist
    }
});



    </script>

    


<script>
    // Enhanced search script that can search by first name, last name, or full name
document.getElementById('search').addEventListener('keyup', function () {
    const searchValue = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll("tbody tr");
    let foundItems = 0;

    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        
        // Extract name components (assuming column order: Profile, Last_Name, First_Name, Middle_Name...)
        const lastName = cells[1] ? cells[1].textContent.toLowerCase().trim() : '';
        const firstName = cells[2] ? cells[2].textContent.toLowerCase().trim() : '';
        const middleName = cells[3] ? cells[3].textContent.toLowerCase().trim() : '';
        
        // Create various name combinations for searching
        const fullName = `${firstName} ${middleName} ${lastName}`.toLowerCase();
        const firstLast = `${firstName} ${lastName}`.toLowerCase();
        const lastFirst = `${lastName} ${firstName}`.toLowerCase();
        const allRowText = row.textContent.toLowerCase();
        
        // Check if search matches any name combination or any cell content
        const isMatch = firstName.includes(searchValue) || 
                       lastName.includes(searchValue) ||
                       middleName.includes(searchValue) ||
                       fullName.includes(searchValue) ||
                       firstLast.includes(searchValue) ||
                       lastFirst.includes(searchValue) ||
                       allRowText.includes(searchValue);
        
        if (isMatch) {
            row.style.display = "";
            foundItems++;
        } else {
            row.style.display = "none";
        }
    });

    // Handle pagination visibility based on search results
    const paginationContainer = document.querySelector(".pagination");
    if (paginationContainer) {
        if (foundItems === 0 && searchValue !== '') {
            paginationContainer.style.display = "none";
        } else {
            paginationContainer.style.display = "";
        }
    }
    
    // Optional: Show "No results found" message
    const existingNoResults = document.getElementById('noResultsMessage');
    if (existingNoResults) {
        existingNoResults.remove();
    }
    
    if (foundItems === 0 && searchValue !== '') {
        const tableWrapper = document.querySelector('.table-wrapper');
        const noResultsDiv = document.createElement('div');
        noResultsDiv.id = 'noResultsMessage';
        noResultsDiv.className = 'text-center p-4';
        noResultsDiv.innerHTML = '<p class="text-muted">No residents found matching your search.</p>';
        tableWrapper.appendChild(noResultsDiv);
    }
});
</script>

<script>
function changePage(pageNumber) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('page', pageNumber);
    window.location.href = currentUrl.toString();
}
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

    </script>
</body>
</html>
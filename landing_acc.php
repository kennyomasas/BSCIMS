<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page
    header("Location: online_login.php");
    exit();
}

$fullName = $_SESSION['full_name'];
$profileImage = !empty($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'default.png'; // fallback image

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

// Fetch certificate types from database
$certificateTypes = [];
$sql = "SELECT id, certificate_name FROM certificate_types ORDER BY certificate_name ASC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $certificateTypes[] = $row;
    }
}


if (!isset($_SESSION['user_id'])) {
    // Redirect to login if not logged in
    header("Location: online_login.php");
    exit();
}

// Get logged-in user's information
$loggedInUserName = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : '';
$loggedInUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

// Fetch user data from database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM residents WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
} else {
    // Handle case where user data is not found
    echo "<script>alert('User data not found!'); window.location.href='online_login.php';</script>";
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: Online_login.php'); // Redirect to login if not logged in
    exit();
}

// Get user information from session
$user_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'Guest';
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay San Carlos Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar h2 {
            padding: 0 20px;
            margin-bottom: 30px;
            font-size: 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 15px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar li {
            padding: 12px 20px;
            cursor: pointer;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar li:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: white;
        }

        .sidebar li.active {
            background-color: rgba(255, 255, 255, 0.2);
            border-left-color: white;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            background-color: white;
            margin: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f1f5f9;
        }

        h1 {
            color: #1e293b;
            font-size: 28px;
            font-weight: 600;
        }

        .user-info {
            color: #64748b;
            font-size: 14px;
            background: #f8fafc;
            padding: 8px 16px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
        }

        /* Form Styles */
        .form-container {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 30px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .form-title {
            color: #1e293b;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-title i {
            color: #6366f1;
        }

        .row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .row:last-child {
            margin-bottom: 0;
        }

        .col-md-6 {
            flex: 1;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: 500;
            font-size: 14px;
        }

        .form-control, .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            background: white;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        /* Input Container for Search */
        .input-container {
            position: relative;
        }

        .loading {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6366f1;
            font-size: 12px;
        }

        .search-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 8px 8px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }

        .search-dropdown.show {
            display: block;
        }

        .search-dropdown-item {
            padding: 10px 16px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .search-dropdown-item:hover {
            background-color: #f3f4f6;
        }

        /* Purpose Selection with Button */
        .purpose-container {
            display: flex;
            gap: 10px;
            align-items: end;
        }

        .purpose-select {
            flex: 1;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .btn-outline-primary {
            background: white;
            color: #6366f1;
            border: 2px solid #6366f1;
        }

        .btn-outline-primary:hover {
            background: #6366f1;
            color: white;
        }

        .btn-sm {
            padding: 6px 10px;
            font-size: 12px;
        }

        /* Submit Button */
        .submit-btn {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            padding: 14px 32px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Table Styles */
        .requests-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .section-title {
            color: #1e293b;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: #6366f1;
        }

        .requests-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .requests-table th,
        .requests-table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .requests-table th {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            font-weight: 500;
            font-size: 14px;
        }

        .requests-table tr:hover {
            background-color: #f9fafb;
        }

        .status-pending {
            color: #f59e0b;
            font-weight: 500;
            background: #fef3c7;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        .status-approved {
            color: #10b981;
            font-weight: 500;
            background: #d1fae5;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        .status-processing {
            color: #6366f1;
            font-weight: 500;
            background: #e0e7ff;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #9ca3af;
            font-style: italic;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                height: auto;
            }

            .sidebar {
                width: 100%;
                padding: 15px 0;
            }

            .main-content {
                margin: 10px;
                padding: 15px;
            }

            .row {
                flex-direction: column;
                gap: 0;
            }

            .form-container {
                padding: 20px;
            }

            .purpose-container {
                flex-direction: column;
                align-items: stretch;
            }

            .requests-table {
                font-size: 12px;
            }

            .requests-table th,
            .requests-table td {
                padding: 8px;
            }
        }
        
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
        }

        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 1.5rem;
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar li {
            margin: 10px 0;
            padding: 15px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sidebar li:hover, .sidebar li.active {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateX(5px);
        }

        .sidebar li i {
            margin-right: 10px;
            width: 20px;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        .header {
            background: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #667eea;
            font-size: 1.8rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #666;
            font-weight: 500;
        }

        .section {
            display: none;
        }

        .section.active {
            display: block;
        }

        /* Profile Section Styles */
        .profile-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 3rem;
            border: 4px solid rgba(255, 255, 255, 0.3);
        }

        .profile-name {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .profile-role {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .profile-content {
            padding: 30px;
        }

        .profile-tabs {
            display: flex;
            border-bottom: 2px solid #f0f0f0;
            margin-bottom: 30px;
        }

        .profile-tab {
            padding: 15px 25px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            color: #666;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .profile-tab.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }

        .profile-tab-content {
            display: none;
        }

        .profile-tab-content.active {
            display: block;
        }

        .profile-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        .info-card {
            background: #f8f9ff;
            border-radius: 12px;
            padding: 25px;
            border-left: 4px solid #667eea;
        }

        .info-card h4 {
            color: #333;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #666;
            font-weight: 500;
        }

        .info-value {
            color: #333;
            font-weight: 600;
        }

        .edit-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .edit-btn:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }

        .password-form {
            max-width: 500px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .submit-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }

        /* Request Form Styles */
        .form-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .form-title {
            color: #667eea;
            margin-bottom: 25px;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .col-md-6 {
            flex: 1;
        }

        .form-select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            background-color: white;
            transition: all 0.3s ease;
        }

        .form-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .input-container {
            position: relative;
        }

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

        .loading {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
        }

        .requests-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            color: #667eea;
            margin-bottom: 25px;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .requests-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .requests-table th,
        .requests-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .requests-table th {
            background: #f8f9ff;
            color: #667eea;
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            color: #999;
            font-style: italic;
            padding: 40px;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 15px;
            }

            .sidebar ul {
                display: flex;
                overflow-x: auto;
                gap: 10px;
            }

            .sidebar li {
                white-space: nowrap;
                min-width: 120px;
            }

            .row {
                flex-direction: column;
                gap: 15px;
            }

            .header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .profile-info-grid {
                grid-template-columns: 1fr;
            }
        }

   .user-info {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 12px;
    background: #f5f5f5;
    border-radius: 25px;
    border: 1px solid #ddd;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.user-info img.profile-pic {
    width: 30px;                 /* Increased to match name height */
    height: 30px;                /* Same as name height */
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 2px solid #ccc;
}

.user-info .profile-name {
    max-width: 220px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    height: 30px;                /* Same height as profile pic */
    line-height: 40px;           /* Center text vertically */
    font-size: 14px;
    font-weight: 500;
    color: #333;                 /* Black text */
}

/* Alternative version with both elements exactly the same size */
.user-info.equal-size img.profile-pic {
    width: 35px;
    height: 35px;
}

.user-info.equal-size .profile-name {
    height: 35px;
    line-height: 35px;
    font-size: 13px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .user-info img.profile-pic {
        width: 32px;
        height: 32px;
    }
    
    .user-info .profile-name {
        height: 32px;
        line-height: 32px;
        font-size: 12px;
        max-width: 150px;
    }
}

/* Status Badge Styles */
.status-badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.85em;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-block;
}

.status-pending {
    background-color: #fff3cd;
    color: #856404;
    border: 1px solid #ffeaa7;
}

.status-approved {
    background-color: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.status-ready {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status-completed {
    background-color: #e2e3e5;
    color: #383d41;
    border: 1px solid #d6d8db;
}

.status-rejected {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.status-default {
    background-color: #f8f9fa;
    color: #6c757d;
    border: 1px solid #dee2e6;
}

/* Empty state styling */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
    font-style: italic;
}

.empty-state i {
    font-size: 1.2em;
    margin-right: 8px;
}

.empty-state.error {
    color: #dc3545;
}

/* Refresh button styling */
.refresh-btn {
    background: none;
    border: none;
    color: #007bff;
    cursor: pointer;
    padding: 5px;
    margin-left: 10px;
    border-radius: 3px;
    transition: all 0.3s ease;
}

.refresh-btn:hover {
    background-color: #f8f9fa;
    color: #0056b3;
}

.refresh-btn i {
    font-size: 0.9em;
}



 

        .form-container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .form-title {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-title i {
            color: #667eea;
        }

        .announcements-container {
            grid-column: span 2;
        }

        .announcements-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .announcement-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .announcement-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .announcement-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ff6b6b, #ffd93d, #6bcf7f, #4ecdc4);
        }

        .announcement-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .announcement-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
            line-height: 1.3;
            flex: 1;
        }

        .announcement-type {
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-left: 10px;
            white-space: nowrap;
        }

        .announcement-description {
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .announcement-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .detail-item i {
            width: 16px;
            text-align: center;
            opacity: 0.8;
        }

        .announcement-dates {
            background: rgba(255, 255, 255, 0.1);
            padding: 12px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .date-range {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }

        .date-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #667eea;
            font-size: 16px;
        }

        .loading i {
            animation: spin 1s linear infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .no-announcements {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
            grid-column: span 2;
        }

        .no-announcements i {
            font-size: 48px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            border-left: 4px solid #667eea;
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .announcements-container {
                grid-column: span 1;
            }

            .announcements-grid {
                grid-template-columns: 1fr;
            }

            .announcement-details {
                grid-template-columns: 1fr;
            }

            .date-range {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
        }

        .badge {
    background-color: red;
    color: white;
    padding: 2px 6px;
    font-size: 11px;
    border-radius: 50%;
    margin-left: 0px;
    margin-top: -19px;
    vertical-align: middle;
}

 /* Modal Overlay */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            backdrop-filter: blur(3px);
        }

        /* Modal Container */
        .modal-container {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            animation: modalSlideIn 0.3s ease-out;
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

        /* Modal Header */
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px;
            border-radius: 50%;
            transition: background-color 0.2s;
        }

        .modal-close:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Modal Body */
        .modal-body {
            padding: 30px;
        }

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .form-section h4 {
            margin: 0 0 20px 0;
            color: #333;
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Form Groups */
        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e5e9;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            background-color: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control:hover {
            border-color: #c5c9d1;
        }

        select.form-control {
            cursor: pointer;
        }

        /* Profile Picture Section */
        .profile-picture-section {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .current-picture {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #e1e5e9;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            border: 3px solid white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .current-picture i {
            font-size: 2.5rem;
            color: #6c757d;
        }

        .picture-upload {
            margin-top: 15px;
        }

        .upload-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.2s;
        }

        .upload-btn:hover {
            background: #5a67d8;
        }

        /* Modal Footer */
        .modal-footer {
            padding: 20px 30px;
            border-top: 1px solid #e1e5e9;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            background: #f8f9fa;
            border-radius: 0 0 12px 12px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
        }

        .btn-cancel:hover {
            background: #5a6268;
        }

        .btn-save {
            background: #28a745;
            color: white;
        }

        .btn-save:hover {
            background: #218838;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .modal-container {
                width: 95%;
                margin: 10px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .modal-body {
                padding: 20px;
            }

            .modal-footer {
                padding: 15px 20px;
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Demo button styling */
        .edit-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .edit-btn:hover {
            background: #5a67d8;
            transform: translateY(-1px);
        }

        /* Toast Notification Styles */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 10px;
            transform: translateX(400px);
            transition: transform 0.3s ease-in-out;
            min-width: 300px;
        }

        .toast.show {
            transform: translateX(0);
        }

        .toast.error {
            background: #dc3545;
        }

        .toast i {
            font-size: 1.2rem;
        }

        .toast-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.1rem;
            cursor: pointer;
            margin-left: auto;
            padding: 0 5px;
        }

         /* Security Questions Styles */
        .security-section {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #2196F3;
            margin-bottom: 20px;
        }

        .question-group {
            margin-bottom: 15px;
        }

        .question-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .security-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .security-input:focus {
            outline: none;
            border-color: #2196F3;
            box-shadow: 0 0 5px rgba(33, 150, 243, 0.3);
        }

        .save-btn {
            background: #2196F3;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s ease;
        }

        .save-btn:hover {
            background: #1976D2;
        }

        .requirements-list {
            list-style: none;
            padding: 0;
        }

        .requirements-list li {
            padding: 5px 0;
            color: #4CAF50;
        }

        .requirements-list i {
            margin-right: 8px;
        }

        .success-message {
            background: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
            text-align: center;
        }

        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
        }

        .status-set {
            background: #4CAF50;
            color: white;
        }

        .status-not-set {
            background: #FF9800;
            color: white;
        }

        @media (max-width: 600px) {
            .profile-tabs {
                flex-direction: column;
            }
            
            .profile-tab {
                border-radius: 8px;
                border: 1px solid #ddd;
            }
        }

        .form-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }

        .form-section h3 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.3em;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .btn {
            background: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            background: #0056b3;
        }

        .btn-success {
            background: #28a745;
        }

        .btn-success:hover {
            background: #218838;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            display: none;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .security-questions {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .security-questions h4 {
            color: #856404;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .question-item {
            margin-bottom: 15px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }

        .question-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.9em;
            margin-top: 10px;
        }

        .status-configured {
            color: #28a745;
        }

        .status-not-configured {
            color: #dc3545;
        }

        .recovery-info {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .recovery-info h4 {
            color: #0066cc;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .profile-tabs {
                flex-direction: column;
            }
            
            .profile-tab {
                flex: none;
            }
            
            .container {
                margin: 10px;
                border-radius: 10px;
            }
            
            .profile-header {
                padding: 20px;
            }
            
            .profile-header h1 {
                font-size: 2em;
            }
            
            .tab-content {
                padding: 20px;
            }
        }

        .tab-content {
    display: none;
}
.tab-content.active {
    display: block;
}
/* Mobile Responsive Table Styles */
        @media screen and (max-width: 768px) {
            body {
                padding: 10px;
            }

            .requests-section {
                padding: 15px;
                border-radius: 8px;
            }

            .section-title {
                font-size: 1.2rem;
                margin-bottom: 15px;
            }

            .claim-notice {
                font-size: 12px !important;
                margin-left: 0 !important;
                margin-top: 5px;
                flex-basis: 100%;
                line-height: 1.4;
            }

            .requests-table {
                border: 0;
                width: 100%;
                font-size: 14px;
                box-shadow: none;
            }

            .requests-table thead {
                border: none;
                clip: rect(0 0 0 0);
                height: 1px;
                margin: -1px;
                overflow: hidden;
                padding: 0;
                position: absolute;
                width: 1px;
            }

            .requests-table tbody,
            .requests-table tr,
            .requests-table td {
                display: block;
                border: none;
            }

            .requests-table tr {
                background: white;
                border: 1px solid #ddd;
                border-radius: 12px;
                margin-bottom: 15px;
                padding: 15px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                position: relative;
                transform: none;
            }

            .requests-table tr:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            }

            .requests-table tr:before {
                content: "Request #" attr(data-request-id);
                font-weight: bold;
                font-size: 16px;
                color: #667eea;
                display: block;
                margin-bottom: 12px;
                padding-bottom: 10px;
                border-bottom: 2px solid #f0f0f0;
            }

            .requests-table td {
                border: none;
                position: relative;
                padding: 10px 0 10px 40%;
                text-align: left;
                min-height: 40px;
                display: flex;
                align-items: center;
            }

            .requests-table td:before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 35%;
                font-weight: 600;
                color: #333;
                font-size: 13px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                display: flex;
                align-items: center;
                line-height: 1.3;
            }

            /* Hide empty state colspan on mobile */
            .requests-table .empty-state {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 40px 20px;
                text-align: center;
                background: white;
                border-radius: 12px;
                border: 2px dashed #ddd;
            }

            .requests-table .empty-state:before {
                display: none;
            }

            /* Status badges mobile styling */
            .requests-table td .status-badge {
                padding: 6px 10px;
                border-radius: 15px;
                font-size: 11px;
                font-weight: 600;
                text-transform: uppercase;
            }
        }

        /* Very small screens */
        @media screen and (max-width: 480px) {
            .requests-section {
                padding: 12px;
            }
            
            .requests-table tr {
                padding: 12px;
                margin-bottom: 12px;
            }
            
            .requests-table td {
                padding: 8px 0 8px 40%;
                font-size: 13px;
                min-height: 35px;
            }

            .requests-table td:before {
                font-size: 12px;
                width: 38%;
            }
        }


        

        
        
    </style>
</head>
 <div class="container">
    <div class="sidebar">
        <h2>
    <img src="logo.png" alt="Barangay Icon" style="width: 32px; height: 32px; vertical-align: middle; margin-right: 0px;">
    Barangay San Carlos IMS
    </h2>

        <ul>
            <li onclick="showSection('dashboard')" id="dashboardMenu">
                <i class="fas fa-tachometer-alt"></i> Dashboard
                <span class="badge" id="announcementBadge">2</span> 
            </li>
            <li onclick="showSection('request')">
                <i class="fas fa-file-alt"></i> Request
            </li>
            <li onclick="showSection('profile')">
                <i class="fas fa-user"></i> Profile
            </li>
            <li onclick="logout()">
                <i class="fas fa-sign-out-alt"></i> Logout
            </li>
        </ul>
    </div>


        <div class="main-content">
        <!-- Dashboard Section -->
        <div id="dashboard" class="section">
           
 <div class="user-info">
        <div class="user-avatar">
            <i class="fas fa-user-circle"></i>
        </div>
        <div class="user-details">
            <span class="user-name"><?php echo htmlspecialchars($user_name); ?></span>
            
        </div>
    </div>
          
            <div class="dashboard-grid">
    <div class="form-container">
        <h3 class="form-title">
            <i class="fas fa-chart-line"></i>
            Welcome to Dashboard
        </h3>
        <p>This dashboard provides an overview of all upcoming announcements and events in barangay san carlos.</p>
    </div>
    
   

               

                <!-- Announcements Section -->
                <div class="form-container announcements-container">
                    <h3 class="form-title">
                        <i class="fas fa-bullhorn"></i>
                        Recent Announcements & Events
                    </h3>
                    
                    <div id="loadingIndicator" class="loading">
                        <i class="fas fa-spinner"></i>
                        Loading announcements...
                    </div>

                    <div id="announcementsGrid" class="announcements-grid" style="display: none;">
                        <!-- Announcements will be loaded here dynamically -->
                    </div>

                    <div id="noAnnouncements" class="no-announcements" style="display: none;">
                        <i class="fas fa-calendar-times"></i>
                        <h4>No Announcements Available</h4>
                        <p>There are currently no announcements or events to display.</p>
                    </div>
                </div>
            </div>
        </div>
   


            <!-- Request Section -->
            <div id="request" class="section active">
                <div class="header">
                    <h1 style="font-size: 18px;"><i class="fas fa-certificate"></i> Request Certificates</h1>
                    <div class="user-info">
                        <i class="fas fa-user-circle"></i> Resident Portal
                    </div>
                </div>

                <div class="form-container">
                    <h3 class="form-title">
                        <i class="fas fa-plus-circle"></i>
                        New Certificate Request
                    </h3>
                    
                    <form id="requestForm">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="issuedTo" class="form-label">
                    <i class="fas fa-user"></i> Resident Name
                </label>
                <div class="input-container">
                    <input type="text"
                            class="form-control"
                            id="issuedTo"
                            name="issuedTo"
                            value="<?php echo htmlspecialchars($loggedInUserName); ?>"
                            placeholder="Type to search resident name..."
                            autocomplete="off"
                            readonly>
                    <div class="loading" id="loadingIndicator" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <div class="search-dropdown" id="searchDropdown"></div>
                </div>
                <input type="hidden" id="residentId" name="residentId" value="<?php echo htmlspecialchars($loggedInUserId); ?>">
                                </div>
                            </div>
                            

                           <div class="col-md-6">
    <div class="form-group">
        <label for="certificateType" class="form-label">
            <i class="fas fa-certificate"></i> Certificate Type
        </label>
        <select class="form-select" id="certificateType" name="certificateType" required>
            <option value="" selected disabled>Select Certificate Type</option>
            <?php foreach($certificateTypes as $type): ?>
                <option value="<?php echo htmlspecialchars($type['id']); ?>">
                    <?php echo htmlspecialchars($type['certificate_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
</div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="purpose" class="form-label">
                                        <i class="fas fa-clipboard-list"></i> Purpose
                                    </label>
                                    <div class="purpose-container">
                                        <div class="purpose-select">
                                            <select class="form-select" id="purpose" name="purpose" required>
                                                <option value="" selected disabled>Select Purpose</option>
                                                <option value="Custom Purposes">Custom Purposes</option>
                                                <option value="Overseas Employment">Overseas Employment</option>
                                                <option value="Loan Application">Loan Application</option>
                                                <option value="PHILIPPINE I.D SYSTEM REQUIREMENTS">Philippine ID System Requirements</option>
                                                <option value="Medical Assistance">Medical Assistance</option>
                                                <option value="Burial Assistance">Burial Assistance</option>
                                                <option value="Educational Assistance">Educational Assistance</option>
                                                <option value="Medicine Assistance">Medicine Assistance</option>
                                                <option value="Local Employment">Local Employment</option>
                                                <option value="Vehicle Assistance">Vehicle Assistance</option>
                                                <option value="PAG-IBIG REQUIREMENTS">Pag-IBIG Requirements</option>
                                                <option value="VACCINATION REQUIREMENTS">Vaccination Requirements</option>
                                                <option value="Scholarship Requirements (UNIFAST)">Scholarship Requirements (UNIFAST)</option>
                                                <option value="BUSINESS REGISTRATION">Business Registration</option>
                                                <option value="MOTORELA PERMIT/REGISTRATION REQUIREMENTS">Motorela Permit/Registration Requirements</option>
                                                <option value="IDENTIFICATIONAL PURPOSES">Identificational Purposes</option>
                                            </select>
                                        </div>                               
                                    </div>  
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="signatory" class="form-label">
                                        <i class="fas fa-user-tie"></i> Signatory
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="signatory" 
                                           name="signatory" 
                                           value="Officer In-Charge" 
                                           readonly>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="submit-btn">
                            <i class="fas fa-paper-plane"></i>
                            Submit Request
                        </button>
                    </form>
                </div>

                <div class="requests-section">
    <h3 class="section-title" style="font-size: 18px;">
        <i class="fas fa-history" > </i>
        Certificate Requests History
        
         <span style="color: red; font-size: 10px; margin-left: 10px;">
        *Once the certificate is approved, you can claim it in the barangay office.
    </span>
    </h3>
    
    <table class="requests-table">
        <thead>
            <tr>
               
                <th><i class="fas fa-id-card"></i> Request ID</th>
                <th><i class="fas fa-user"></i> Resident_Name</th>
                <th><i class="fas fa-certificate"></i> Certificate_Type</th>
                <th><i class="fas fa-clipboard-list"></i> Purpose</th>
                <th><i class="fas fa-user-tie"></i> Signatory</th>
                <th><i class="fas fa-info-circle"></i> Status</th>
                <th><i class="fas fa-calendar"></i> Date_Requested</th>
            </tr>
        </thead>
        <tbody id="requests-tbody">
            <tr>
                <td colspan="8" class="empty-state">
                    
                    Loading certificate requests...
                </td>
            </tr>
        </tbody>
    </table>
</div>
</div>

            <!-- Profile Section -->
<div id="profile" class="section">
    <div class="header">
        <h1 style="font-size: 18px;"><i class="fas fa-user"></i> Profile Management</h1>
        <div class="user-info">
            <i class="fas fa-user-circle"></i> Resident Portal
        </div>
    </div>

    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                <?php if (!empty($user_data['profile_upload']) && file_exists($user_data['profile_upload'])): ?>
                    <img src="<?php echo htmlspecialchars($user_data['profile_upload']); ?>" alt="Profile Picture" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                <?php else: ?>
                    <i class="fas fa-user"></i>
                <?php endif; ?>
            </div>
            <div class="profile-name">
                <span id="firstName"><?php echo htmlspecialchars($user_data['first_name'] ?? ''); ?></span> 
                <span id="lastName"><?php echo htmlspecialchars($user_data['last_name'] ?? ''); ?></span>
            </div>
            <div class="profile-role">Resident</div>
        </div>

        <div class="profile-content">
            <div class="profile-tabs">
    <div class="profile-tab active" onclick="showProfileTab('personal')">
        <i class="fas fa-user"></i> Personal Info
    </div>
    <div class="profile-tab" onclick="showProfileTab('contact')">
        <i class="fas fa-address-book"></i> Contact Details
    </div>
   

    <div class="profile-tab" onclick="showProfileTab('password')">
        <i class="fas fa-key"></i> Password Security
    </div>
</div>
 
            <!-- Personal Info Tab -->
            <div id="personal" class="profile-tab-content active">
                <div class="profile-info-grid">
                    <div class="info-card">
                        <h4><i class="fas fa-id-card"></i> Basic Information</h4>
                        <div class="info-item">
                            <span class="info-label">First Name:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['first_name'] ?? 'Not provided'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Middle Name:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['middle_name'] ?? 'Not provided'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Last Name:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['last_name'] ?? 'Not provided'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Nickname / Alias:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['nickname_alias'] ?? 'Not provided'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Date of Birth:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['birthdate'] ?? 'Not provided'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Birthplace:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['birthplace'] ?? 'Not provided'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Gender:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['gender'] ?? 'Not provided'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Civil Status:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['marital_status'] ?? 'Not provided'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Citizenship:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['citizenship'] ?? 'Not provided'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Religion:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['religion'] ?? 'Not provided'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Sector:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['sector'] ?? 'Not provided'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Education:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['education'] ?? 'Not provided'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Height:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['height'] ?? 'Not provided'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Weight:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['weight'] ?? 'Not provided'); ?></span>
                        </div>
                        <button class="edit-btn" onclick="editPersonalInfo()">
                            <i class="fas fa-edit"></i> Update Information
                        </button>
                    </div>

                    <div class="info-card">
                        <h4><i class="fas fa-map-marker-alt"></i> Address Information</h4>
                        <div class="info-item">
                            <span class="info-label">Sitio:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['sitio'] ?? 'Not provided'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">House No.:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['house_number'] ?? 'Not provided'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Purok:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['purok'] ?? 'Not provided'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">City/Municipality:</span>
                            <span class="info-value">Tangke</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Province:</span>
                            <span class="info-value">Central Visayas</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Since Year:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['since_year'] ?? 'Not provided'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Household Number:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['household_number'] ?? 'Not provided'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">House Owner:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['house_owner'] ?? 'Not provided'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Shelter Type:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['shelter_type'] ?? 'Not provided'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">House Material:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['house_material'] ?? 'Not provided'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Details Tab -->
            <div id="contact" class="profile-tab-content">
                <div class="profile-info-grid">
                    <div class="info-card">
                        <h4><i class="fas fa-phone"></i> Contact Information</h4>
                        <div class="info-item">
                            <span class="info-label">Mobile Number:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['mobile_number'] ?? 'Not provided'); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email Address:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user_data['email'] ?? 'Not provided'); ?></span>
                        </div>
                        <button class="edit-btn" onclick="editContactInfo()">
                            <i class="fas fa-edit"></i> Update Contact
                        </button>
                    </div>
                </div>
            </div>

            <!-- Account Info Tab -->
            <div id="account" class="profile-tab-content">
                <div class="info-card">
                    <h4><i class="fas fa-user-lock"></i> Account Information</h4>
                    <div class="info-item">
                        <span class="info-label">Username:</span>
                        <span class="info-value"><?php echo htmlspecialchars($user_data['username'] ?? 'Not provided'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Security Tab -->
            <div id="security" class="profile-tab-content">
                <div class="info-card">
                    <h4><i class="fas fa-key"></i> Change Password</h4>
                    <form class="password-form" id="passwordForm">
                        <div class="form-group">
                            <label for="currentPassword" class="form-label">
                                <i class="fas fa-lock"></i> Current Password
                            </label>
                            <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                        </div>
                        <div class="form-group">
                            <label for="newPassword" class="form-label">
                                <i class="fas fa-key"></i> New Password
                            </label>
                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword" class="form-label">
                                <i class="fas fa-check"></i> Confirm New Password
                            </label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                        </div>
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-save"></i> Update Password
                        </button>                     
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


 <!-- Modal Overlay (hidden by default) -->
    <div class="modal-overlay" id="editPersonalModal" style="display: none;">
        <div class="modal-container">
            <!-- Modal Header -->
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-edit"></i>
                    Update Personal Information
                </h3>
                <button class="modal-close" type="button" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <!-- Profile Picture Section -->
                <div class="profile-picture-section">
                    <div class="current-picture">
                        <?php if (!empty($user_data['profile_upload']) && file_exists($user_data['profile_upload'])): ?>
                            <img src="<?php echo htmlspecialchars($user_data['profile_upload']); ?>" alt="Profile Picture" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                        <?php else: ?>
                            <i class="fas fa-user"></i>
                        <?php endif; ?>
                    </div>
                    <div class="picture-upload">
                        <input type="file" id="profilePicture" name="profilePicture" accept="image/*" style="display: none;">
                        <button type="button" class="upload-btn" onclick="document.getElementById('profilePicture').click();">
                            <i class="fas fa-camera"></i> Change Picture
                        </button>
                    </div>
                </div>

                <form id="editPersonalInfoForm" method="POST" action="edit_landing_acc.php" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_data['id'] ?? ''); ?>">
                    <div class="form-grid">
                        <!-- Basic Information Section -->
                        <div class="form-section">
                            <h4><i class="fas fa-id-card"></i> Basic Information</h4>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="firstName">First Name</label>
                                    <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter first name" value="<?php echo htmlspecialchars($user_data['first_name'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="middleName">Middle Name</label>
                                    <input type="text" class="form-control" id="middleName" name="middleName" placeholder="Enter middle name" value="<?php echo htmlspecialchars($user_data['middle_name'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="lastName">Last Name</label>
                                    <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter last name" value="<?php echo htmlspecialchars($user_data['last_name'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="nickname">Nickname / Alias</label>
                                    <input type="text" class="form-control" id="nickname" name="nickname" placeholder="Enter nickname" value="<?php echo htmlspecialchars($user_data['nickname_alias'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="birthdate">Date of Birth</label>
                                    <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($user_data['birthdate'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="birthplace">Birthplace</label>
                                    <input type="text" class="form-control" id="birthplace" name="birthplace" placeholder="Enter birthplace" value="<?php echo htmlspecialchars($user_data['birthplace'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="gender">Gender</label>
                                    <select class="form-control" id="gender" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="Male" <?php echo (isset($user_data['gender']) && $user_data['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo (isset($user_data['gender']) && $user_data['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                        <option value="Other" <?php echo (isset($user_data['gender']) && $user_data['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="maritalStatus">Civil Status</label>
                                    <select class="form-control" id="maritalStatus" name="maritalStatus">
                                        <option value="">Select Status</option>
                                        <option value="Single" <?php echo (isset($user_data['marital_status']) && $user_data['marital_status'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                                        <option value="Married" <?php echo (isset($user_data['marital_status']) && $user_data['marital_status'] == 'Married') ? 'selected' : ''; ?>>Married</option>
                                        <option value="Divorced" <?php echo (isset($user_data['marital_status']) && $user_data['marital_status'] == 'Divorced') ? 'selected' : ''; ?>>Divorced</option>
                                        <option value="Widowed" <?php echo (isset($user_data['marital_status']) && $user_data['marital_status'] == 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="citizenship">Citizenship</label>
                                    <input type="text" class="form-control" id="citizenship" name="citizenship" placeholder="Enter citizenship" value="<?php echo htmlspecialchars($user_data['citizenship'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="religion">Religion</label>
                                    <input type="text" class="form-control" id="religion" name="religion" placeholder="Enter religion" value="<?php echo htmlspecialchars($user_data['religion'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="sector">Sector</label>
                                    <input type="text" class="form-control" id="sector" name="sector" placeholder="Enter sector" value="<?php echo htmlspecialchars($user_data['sector'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="education">Education</label>
                                    <select class="form-control" id="education" name="education">
                                        <option value="">Select Education Level</option>
                                        <option value="Elementary" <?php echo (isset($user_data['education']) && $user_data['education'] == 'Elementary') ? 'selected' : ''; ?>>Elementary</option>
                                        <option value="High School" <?php echo (isset($user_data['education']) && $user_data['education'] == 'High School') ? 'selected' : ''; ?>>High School</option>
                                        <option value="College" <?php echo (isset($user_data['education']) && $user_data['education'] == 'College') ? 'selected' : ''; ?>>College</option>
                                        <option value="Graduate" <?php echo (isset($user_data['education']) && $user_data['education'] == 'Graduate') ? 'selected' : ''; ?>>Graduate</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="height">Height (cm)</label>
                                    <input type="number" class="form-control" id="height" name="height" placeholder="Enter height in cm" value="<?php echo htmlspecialchars($user_data['height'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="weight">Weight (kg)</label>
                                    <input type="number" class="form-control" id="weight" name="weight" placeholder="Enter weight in kg" value="<?php echo htmlspecialchars($user_data['weight'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Address Information Section -->
                        <div class="form-section">
                            <h4><i class="fas fa-map-marker-alt"></i> Address Information</h4>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="sitio">Sitio</label>
                                    <input type="text" class="form-control" id="sitio" name="sitio" placeholder="Enter sitio" value="<?php echo htmlspecialchars($user_data['sitio'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="houseNumber">House No.</label>
                                    <input type="text" class="form-control" id="houseNumber" name="houseNumber" placeholder="Enter house number" value="<?php echo htmlspecialchars($user_data['house_number'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="purok">Purok</label>
                                <input type="text" class="form-control" id="purok" name="purok" placeholder="Enter purok" value="<?php echo htmlspecialchars($user_data['purok'] ?? ''); ?>">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="city">City/Municipality</label>
                                    <input type="text" class="form-control" id="city" name="city" value="Tangke" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="province">Province</label>
                                    <input type="text" class="form-control" id="province" name="province" value="Central Visayas" readonly>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="sinceYear">Since Year</label>
                                    <input type="number" class="form-control" id="sinceYear" name="sinceYear" placeholder="Enter year" min="1900" max="2024" value="<?php echo htmlspecialchars($user_data['since_year'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="householdNumber">Household Number</label>
                                    <input type="text" class="form-control" id="householdNumber" name="householdNumber" placeholder="Enter household number" value="<?php echo htmlspecialchars($user_data['household_number'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="houseOwner">House Owner</label>
                                <input type="text" class="form-control" id="houseOwner" name="houseOwner" placeholder="Enter house owner name" value="<?php echo htmlspecialchars($user_data['house_owner'] ?? ''); ?>">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="shelterType">Shelter Type</label>
                                    <select class="form-control" id="shelterType" name="shelterType">
                                        <option value="">Select Shelter Type</option>
                                        <option value="Own House" <?php echo (isset($user_data['shelter_type']) && $user_data['shelter_type'] == 'Own House') ? 'selected' : ''; ?>>Own House</option>
                                        <option value="Rented" <?php echo (isset($user_data['shelter_type']) && $user_data['shelter_type'] == 'Rented') ? 'selected' : ''; ?>>Rented</option>
                                        <option value="Shared" <?php echo (isset($user_data['shelter_type']) && $user_data['shelter_type'] == 'Shared') ? 'selected' : ''; ?>>Shared</option>
                                        <option value="Boarder" <?php echo (isset($user_data['shelter_type']) && $user_data['shelter_type'] == 'Boarder') ? 'selected' : ''; ?>>Boarder</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="houseMaterial">House Material</label>
                                    <select class="form-control" id="houseMaterial" name="houseMaterial">
                                        <option value="">Select Material</option>
                                        <option value="Concrete" <?php echo (isset($user_data['house_material']) && $user_data['house_material'] == 'Concrete') ? 'selected' : ''; ?>>Concrete</option>
                                        <option value="Wood" <?php echo (isset($user_data['house_material']) && $user_data['house_material'] == 'Wood') ? 'selected' : ''; ?>>Wood</option>
                                        <option value="Mixed" <?php echo (isset($user_data['house_material']) && $user_data['house_material'] == 'Mixed') ? 'selected' : ''; ?>>Mixed</option>
                                        <option value="Light Materials" <?php echo (isset($user_data['house_material']) && $user_data['house_material'] == 'Light Materials') ? 'selected' : ''; ?>>Light Materials</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                    Cancel
                </button>
                <button type="submit" class="btn btn-save" form="editPersonalInfoForm" onclick="handleFormSubmit(event)">
                    <i class="fas fa-save"></i>
                    Save Changes
                </button>
            </div>
        </div>
    </div>



     <!-- Updated Password Security Tab HTML -->
<div id="password" class="tab-content">
    <div class="alert alert-success" id="successAlert" style="display: none;">
        <i class="fas fa-check-circle"></i> <span id="successMessage">Security questions updated successfully!</span>
    </div>
    <div class="alert alert-error" id="errorAlert" style="display: none;">
        <i class="fas fa-exclamation-triangle"></i> <span id="errorMessage"></span>
    </div>

    <div class="form-section">
        <h3><i class="fas fa-key"></i> Password Recovery Settings</h3>
        
        <div class="security-questions">
            <h4>
                <i class="fas fa-question-circle"></i>
                Security Questions for Password Recovery
            </h4>
            <p>Set up security questions to help recover your password if you forget it. Please choose questions only you would know the answers to.</p>
        </div>

        <form id="securityQuestionsForm">
            <div class="question-item">
                <div class="form-group">
                    <label for="question1">Security Question 1 <span style="color: red;">*</span></label>
                    <select id="question1" name="question1" class="form-control" required>
                        <option value="">Select a question...</option>
                        <option value="mother_maiden_name">What is your mother's maiden name?</option>
                        <option value="first_pet_name">What was the name of your first pet?</option>
                        <option value="childhood_friend">What is the name of your childhood best friend?</option>
                        <option value="birth_city">In what city were you born?</option>
                        <option value="first_school">What was the name of your elementary school?</option>
                        <option value="favorite_teacher">What was the name of your favorite teacher?</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="answer1">Answer 1 <span style="color: red;">*</span></label>
                    <input type="text" id="answer1" name="answer1" class="form-control" placeholder="Enter your answer..." required>
                </div>
                <div class="question-status">
                    <i class="fas fa-circle status-not-configured" id="status1"></i>
                    <span id="statusText1">Not configured</span>
                </div>
            </div>

            <div class="question-item">
                <div class="form-group">
                    <label for="question2">Security Question 2 <span style="color: red;">*</span></label>
                    <select id="question2" name="question2" class="form-control" required>
                        <option value="">Select a question...</option>
                        <option value="first_car">What was the make of your first car?</option>
                        <option value="favorite_book">What is your favorite book?</option>
                        <option value="high_school_mascot">What was your high school mascot?</option>
                        <option value="father_middle_name">What is your father's middle name?</option>
                        <option value="favorite_food">What is your favorite food?</option>
                        <option value="dream_job">What was your dream job as a child?</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="answer2">Answer 2 <span style="color: red;">*</span></label>
                    <input type="text" id="answer2" name="answer2" class="form-control" placeholder="Enter your answer..." required>
                </div>
                <div class="question-status">
                    <i class="fas fa-circle status-not-configured" id="status2"></i>
                    <span id="statusText2">Not configured</span>
                </div>
            </div>

            <div class="question-item">
                <div class="form-group">
                    <label for="question3">Security Question 3 <span style="color: red;">*</span></label>
                    <select id="question3" name="question3" class="form-control" required>
                        <option value="">Select a question...</option>
                        <option value="street_grew_up">What street did you grow up on?</option>
                        <option value="favorite_movie">What is your favorite movie?</option>
                        <option value="first_job">Where was your first job?</option>
                        <option value="grandparent_name">What is your grandmother's first name?</option>
                        <option value="favorite_color">What is your favorite color?</option>
                        <option value="nickname">What was your childhood nickname?</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="answer3">Answer 3 <span style="color: red;">*</span></label>
                    <input type="text" id="answer3" name="answer3" class="form-control" placeholder="Enter your answer..." required>
                </div>
                <div class="question-status">
                    <i class="fas fa-circle status-not-configured" id="status3"></i>
                    <span id="statusText3">Not configured</span>
                </div>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Save Security Questions
            </button>
        </form>

        <div class="recovery-info">
            <h4><i class="fas fa-info-circle"></i> How Password Recovery Works</h4>
            <p><strong>1.</strong> Click "Forgot Password?" on the login page</p>
            <p><strong>2.</strong> Enter your email address</p>
            <p><strong>3.</strong> Answer your security questions correctly</p>
            <p><strong>4.</strong> Create a new password</p>
            <p><strong>Note:</strong> All three questions must be answered correctly to reset your password.</p>
        </div>
    </div>
</div>


   <script>
        function showProfileTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(tab => {
                tab.classList.remove('active');
            });

            // Remove active class from all tabs
            const tabs = document.querySelectorAll('.profile-tab');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            // Show selected tab content
            document.getElementById(tabName).classList.add('active');

            // Add active class to clicked tab
            event.target.closest('.profile-tab').classList.add('active');
        }

        function changePassword() {
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            if (!currentPassword || !newPassword || !confirmPassword) {
                alert('Please fill in all password fields.');
                return;
            }

            if (newPassword !== confirmPassword) {
                alert('New passwords do not match.');
                return;
            }

            if (newPassword.length < 8) {
                alert('New password must be at least 8 characters long.');
                return;
            }

            // AJAX request to update password
            const formData = new FormData();
            formData.append('action', 'change_password');
            formData.append('current_password', currentPassword);
            formData.append('new_password', newPassword);

            fetch('profile_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Password updated successfully!', 'success');
                    // Clear the form
                    document.getElementById('currentPassword').value = '';
                    document.getElementById('newPassword').value = '';
                    document.getElementById('confirmPassword').value = '';
                } else {
                    showToast(data.message || 'Error updating password', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Network error. Please try again.', 'error');
            });
        }

        function updateQuestionStatus(questionNum, configured) {
            const statusIcon = document.getElementById(`status${questionNum}`);
            const statusText = document.getElementById(`statusText${questionNum}`);
            
            if (configured) {
                statusIcon.className = 'fas fa-check-circle status-configured';
                statusText.textContent = 'Configured';
                statusIcon.style.color = '#28a745';
            } else {
                statusIcon.className = 'fas fa-circle status-not-configured';
                statusText.textContent = 'Not configured';
                statusIcon.style.color = '#dc3545';
            }
        }

        function showAlert(type, message) {
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            
            // Hide all alerts first
            if (successAlert) successAlert.style.display = 'none';
            if (errorAlert) errorAlert.style.display = 'none';
            
            if (type === 'success' && successAlert) {
                successAlert.style.display = 'block';
                setTimeout(() => {
                    successAlert.style.display = 'none';
                }, 5000);
            } else if (type === 'error' && errorAlert) {
                const errorMessage = document.getElementById('errorMessage');
                if (errorMessage) errorMessage.textContent = message;
                errorAlert.style.display = 'block';
                setTimeout(() => {
                    errorAlert.style.display = 'none';
                }, 5000);
            }
        }

        // Handle security questions form submission
        document.addEventListener('DOMContentLoaded', function() {
            const securityForm = document.getElementById('securityQuestionsForm');
            if (securityForm) {
                securityForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const question1 = document.getElementById('question1').value;
                    const answer1 = document.getElementById('answer1').value.trim();
                    const question2 = document.getElementById('question2').value;
                    const answer2 = document.getElementById('answer2').value.trim();
                    const question3 = document.getElementById('question3').value;
                    const answer3 = document.getElementById('answer3').value.trim();
                    
                    // Validation
                    if (!question1 || !answer1 || !question2 || !answer2 || !question3 || !answer3) {
                        showAlert('error', 'Please fill in all security questions and answers.');
                        showToast('Please fill in all security questions and answers.', 'error');
                        return;
                    }
                    
                    // Check for duplicate questions
                    const questions = [question1, question2, question3];
                    const uniqueQuestions = [...new Set(questions)];
                    if (questions.length !== uniqueQuestions.length) {
                        showAlert('error', 'Please select different questions for each security question.');
                        showToast('Please select different questions for each security question.', 'error');
                        return;
                    }
                    
                    // Check answer length
                    if (answer1.length < 2 || answer2.length < 2 || answer3.length < 2) {
                        showAlert('error', 'Security question answers must be at least 2 characters long.');
                        showToast('Security question answers must be at least 2 characters long.', 'error');
                        return;
                    }

                    // Show loading state
                    const submitBtn = document.querySelector('#securityQuestionsForm button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                    submitBtn.disabled = true;
                    
                    // Prepare form data for AJAX request
                    const formData = new FormData();
                    formData.append('action', 'save_security_questions');
                    formData.append('question1', question1);
                    formData.append('answer1', answer1);
                    formData.append('question2', question2);
                    formData.append('answer2', answer2);
                    formData.append('question3', question3);
                    formData.append('answer3', answer3);
                    
                    // Send AJAX request to save security questions
                    fetch('security_questions_handler.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Reset button state
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;

                        if (data.success) {
                            // Update status indicators
                            updateQuestionStatus(1, true);
                            updateQuestionStatus(2, true);
                            updateQuestionStatus(3, true);
                            
                            // Show success messages
                            showAlert('success', 'Security questions saved successfully!');
                            showToast('Security questions saved successfully!', 'success');
                            
                            // Clear answer fields for security (keep questions selected)
                            document.getElementById('answer1').value = '';
                            document.getElementById('answer2').value = '';
                            document.getElementById('answer3').value = '';
                            
                        } else {
                            showAlert('error', data.message || 'Error saving security questions.');
                            showToast(data.message || 'Error saving security questions.', 'error');
                        }
                    })
                    .catch(error => {
                        // Reset button state
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                        
                        console.error('Error:', error);
                        showAlert('error', 'Network error. Please check your connection and try again.');
                        showToast('Network error. Please check your connection and try again.', 'error');
                    });
                });
            }

            // Load existing security questions on page load
            loadExistingSecurityQuestions();
        });

        function loadExistingSecurityQuestions() {
            // AJAX request to load existing security questions
            const formData = new FormData();
            formData.append('action', 'get_security_questions');
            
            fetch('security_questions_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    const questions = data.data;
                    
                    // Set question dropdowns and update status
                    if (questions.question1) {
                        const q1Select = document.getElementById('question1');
                        if (q1Select) {
                            q1Select.value = questions.question1;
                            updateQuestionStatus(1, true);
                        }
                    }
                    
                    if (questions.question2) {
                        const q2Select = document.getElementById('question2');
                        if (q2Select) {
                            q2Select.value = questions.question2;
                            updateQuestionStatus(2, true);
                        }
                    }
                    
                    if (questions.question3) {
                        const q3Select = document.getElementById('question3');
                        if (q3Select) {
                            q3Select.value = questions.question3;
                            updateQuestionStatus(3, true);
                        }
                    }
                    
                    console.log('Existing security questions loaded successfully');
                } else {
                    // No existing questions found - this is normal for new users
                    console.log('No existing security questions found');
                    updateQuestionStatus(1, false);
                    updateQuestionStatus(2, false);
                    updateQuestionStatus(3, false);
                }
            })
            .catch(error => {
                console.error('Error loading security questions:', error);
                // Set all to not configured on error
                updateQuestionStatus(1, false);
                updateQuestionStatus(2, false);
                updateQuestionStatus(3, false);
            });
        }

        // Function to show the modal
        function editPersonalInfo() {
            const modal = document.getElementById('editPersonalModal');
            if (modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            }
        }

        // Function to close the modal
        function closeModal() {
            const modal = document.getElementById('editPersonalModal');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto'; // Restore background scrolling
            }
        }

        // Initialize modal event listeners when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Close modal when clicking outside of it
            const modal = document.getElementById('editPersonalModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeModal();
                    }
                });
            }

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal();
                }
            });

            // Profile picture preview functionality
            const profilePictureInput = document.getElementById('profilePicture');
            if (profilePictureInput) {
                profilePictureInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Validate file type
                        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                        if (!allowedTypes.includes(file.type)) {
                            showToast('Please select a valid image file (JPEG, PNG, GIF)', 'error');
                            return;
                        }

                        // Validate file size (5MB max)
                        if (file.size > 5 * 1024 * 1024) {
                            showToast('File size must be less than 5MB', 'error');
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const currentPicture = document.querySelector('.current-picture');
                            if (currentPicture) {
                                currentPicture.innerHTML = `<img src="${e.target.result}" alt="Profile Picture" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">`;
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });

        // Show toast notification
        function showToast(message, type = 'success') {
            // Remove existing toast if any
            const existingToast = document.querySelector('.toast');
            if (existingToast) {
                existingToast.remove();
            }

            // Create toast element
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;

            // Add styles if not already present
            if (!document.getElementById('toast-styles')) {
                const styles = document.createElement('style');
                styles.id = 'toast-styles';
                styles.innerHTML = `
                    .toast {
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        background: #099934ff;
                        border: 1px solid #ddd;
                        border-radius: 8px;
                        padding: 15px 20px;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                        z-index: 10000;
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        min-width: 300px;
                        transform: translateX(400px);
                        transition: transform 0.3s ease;
                    }
                    .toast.show {
                        transform: translateX(0);
                    }
                    .toast-success {
                        border-left: 4px solid #28a745;
                    }
                    .toast-success i {
                        color: #28a745;
                    }
                    .toast-error {
                        border-left: 4px solid #dc3545;
                    }
                    .toast-error i {
                        color: #dc3545;
                    }
                    .toast-close {
                        background: none;
                        border: none;
                        cursor: pointer;
                        margin-left: auto;
                        padding: 0;
                        color: #999;
                    }
                    .toast-close:hover {
                        color: #666;
                    }
                `;
                document.head.appendChild(styles);
            }

            // Add to page
            document.body.appendChild(toast);

            // Show toast
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.classList.remove('show');
                    setTimeout(() => {
                        if (toast.parentElement) {
                            toast.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }

        // Function to delete/reset security questions
        function resetSecurityQuestions() {
            if (confirm('Are you sure you want to reset all security questions? This action cannot be undone.')) {
                const formData = new FormData();
                formData.append('action', 'delete_security_questions');
                
                fetch('security_questions_handler.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reset form
                        document.getElementById('securityQuestionsForm').reset();
                        
                        // Update status indicators
                        updateQuestionStatus(1, false);
                        updateQuestionStatus(2, false);
                        updateQuestionStatus(3, false);
                        
                        showToast('Security questions reset successfully!', 'success');
                    } else {
                        showToast(data.message || 'Error resetting security questions.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Network error. Please try again.', 'error');
                });
            }
        }
</script>

    
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    function logout() {
        // Show loading text (optional visual feedback)
        const logoutItem = event.target.closest('li');
        if (logoutItem) {
            logoutItem.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging out...';
        }

        // Redirect after a short delay (optional for user experience)
        setTimeout(() => {
            window.location.href = "online_logout.php";
        }, 1000); // 1 second delay for loading effect
    }
</script>



    <script>
        // Navigation Functions
        function showSection(sectionName) {
            // Hide all sections
            const sections = document.querySelectorAll('.section');
            sections.forEach(section => section.classList.remove('active'));

            // Show selected section
            document.getElementById(sectionName).classList.add('active');

            // Update sidebar active state
            const sidebarItems = document.querySelectorAll('.sidebar li');
            sidebarItems.forEach(item => item.classList.remove('active'));
            event.target.classList.add('active');
        }

        function showProfileTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.profile-tab-content');
            tabContents.forEach(content => content.classList.remove('active'));

            // Show selected tab content
            document.getElementById(tabName).classList.add('active');

            // Update tab active state
            const tabs = document.querySelectorAll('.profile-tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');
        }

       
       

        // Function to save profile data (placeholder for server communication)
        function saveProfileData(section, data) {
            // In a real application, you would send this data to your server
            console.log('Saving profile data for section:', section, data);
            
           
        }

        // Load profile data on page load (placeholder function)
        function loadProfileData() {
           
        }

       

        // Certificate Request Form Handler
    document.getElementById('requestForm').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent normal form submission

        const form = e.target;
        const formData = new FormData(form);

        fetch('handle_certificate_request.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: result.message,
                    showConfirmButton: false,
                    timer: 3000
                });
                form.reset(); // Reset the form
                loadRequestsHistory(); // Refresh requests
            } else {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: "Error: " + result.message,
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: "An unexpected error occurred.",
                showConfirmButton: false,
                timer: 3000
            });
        });
    });

    // Function to load requests history (placeholder)
    function loadRequestsHistory() {
        // TODO: Add logic to update table or request list
    }

    // Initialize the page
    document.addEventListener('DOMContentLoaded', function () {
        loadProfileData();
        loadRequestsHistory();
    });

    // Add some CSS for status badges
    const style = document.createElement('style');
    style.textContent = `
        .status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status.pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status.approved {
            background-color: #d1edff;
            color: #0c5460;
        }
        .status.completed {
            background-color: #d4edda;
            color: #155724;
        }
        .status.rejected {
            background-color: #f8d7da;
            color: #721c24;
        }
    `;
    document.head.appendChild(style);
    </script>


<script>
    // Function to load certificate requests
function loadCertificateRequests() {
    const tbody = document.getElementById('requests-tbody');
    
    // Show loading state
    tbody.innerHTML = `
        <tr>
            <td colspan="8" class="empty-state">
                <i class="fas fa-spinner fa-spin"></i> Loading certificate requests...
            </td>
        </tr>
    `;

    fetch('fetch_user_requests.php', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            if (data.data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="empty-state">
                            <i class="fas fa-inbox"></i> No certificate requests found
                        </td>
                    </tr>
                `;
            } else {
                tbody.innerHTML = '';
                data.data.forEach(request => {
                    const statusClass = getStatusClass(request.status);
                    const row = `
                        <tr>
                           
                            <td><strong>${request.request_id}</strong></td>
                            <td>${request.issued_to}</td>
                            <td>${request.certificate_type}</td>
                            <td>${request.purpose}</td>
                            <td>${request.signatory}</td>
                            <td><span class="status-badge ${statusClass}">${request.status}</span></td>
                            <td>${request.date_requested}</td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
            }
        } else {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="empty-state error">
                        <i class="fas fa-exclamation-triangle"></i> Error: ${data.message}
                    </td>
                </tr>
            `;
        }
    })
    .catch(error => {
        console.error('Error loading requests:', error);
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="empty-state error">
                    <i class="fas fa-exclamation-triangle"></i> Failed to load certificate requests
                </td>
            </tr>
        `;
    });
}



// Function to get status class for styling
function getStatusClass(status) {
    switch(status.toLowerCase()) {
        case 'pending':
            return 'status-pending';
        case 'approved':
            return 'status-approved';
        case 'ready':
            return 'status-ready';
        case 'completed':
            return 'status-completed';
        case 'rejected':
            return 'status-rejected';
        default:
            return 'status-default';
    }
}

// Function to refresh requests
function refreshRequests() {
    loadCertificateRequests();
}

// Load requests when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadCertificateRequests();
});

// Auto-refresh every 30 seconds (optional)
setInterval(loadCertificateRequests, 30000);
</script>



 <script>
    // Function to format date
    function formatDate(dateString) {
        if (!dateString) return 'Not specified';
        const date = new Date(dateString);
        const options = {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        return date.toLocaleDateString('en-US', options);
    }

    // Function to get activity type icon
    function getActivityIcon(activityType) {
        const icons = {
            'meeting': 'fas fa-users',
            'event': 'fas fa-calendar-alt',
            'announcement': 'fas fa-bullhorn',
            'workshop': 'fas fa-tools',
            'seminar': 'fas fa-chalkboard-teacher',
            'default': 'fas fa-calendar'
        };
        return icons[activityType?.toLowerCase()] || icons.default;
    }

    // Function to create announcement card
    function createAnnouncementCard(announcement) {
        const startDate = new Date(announcement.start);
        const endDate = announcement.end ? new Date(announcement.end) : null;
        const now = new Date();

        // Determine if event is active, upcoming, or past
        let statusClass = '';
        let statusText = '';

        if (endDate && now > endDate) {
            statusClass = 'past';
            statusText = 'Completed';
        } else if (now >= startDate && (!endDate || now <= endDate)) {
            statusClass = 'active';
            statusText = 'Active';
        } else {
            statusClass = 'upcoming';
            statusText = 'Upcoming';
        }

        return `
            <div class="announcement-card ${statusClass}">
                <div class="announcement-header">
                    <h4 class="announcement-title">${announcement.title}</h4>
                    <span class="announcement-type">${announcement.extendedProps.activity_type || 'Event'}</span>
                </div>

                <p class="announcement-description">
                    ${announcement.extendedProps.description || 'No description available.'}
                </p>

                <div class="announcement-details">
                    <div class="detail-item">
                        <i class="${getActivityIcon(announcement.extendedProps.activity_type)}"></i>
                        <span>${announcement.extendedProps.activity_type || 'General'}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-users"></i>
                        <span>${announcement.extendedProps.participant_count || 0} participants</span>
                    </div>
                </div>

                <div class="announcement-dates">
                    <div class="date-range">
                        <div class="date-item">
                            <i class="fas fa-play"></i>
                            <span><strong>Start:</strong> ${formatDate(announcement.start)}</span>
                        </div>
                        ${endDate ? `
                            <div class="date-item">
                                <i class="fas fa-stop"></i>
                                <span><strong>End:</strong> ${formatDate(announcement.end)}</span>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    }

    // Function to update statistics
    function updateStatistics(announcements) {
        const now = new Date();
        let totalEvents = announcements.length;
        let upcomingEvents = 0;
        let activeEvents = 0;
        let totalParticipants = 0;

        announcements.forEach(announcement => {
            const startDate = new Date(announcement.start);
            const endDate = announcement.end ? new Date(announcement.end) : null;

            totalParticipants += parseInt(announcement.extendedProps.participant_count) || 0;

            if (now < startDate) {
                upcomingEvents++;
            }

            if (now >= startDate && (!endDate || now <= endDate)) {
                activeEvents++;
            }
        });

        document.getElementById('totalEvents').textContent = totalEvents;
        document.getElementById('upcomingEvents').textContent = upcomingEvents;
        document.getElementById('totalParticipants').textContent = totalParticipants;
        document.getElementById('activeEvents').textContent = activeEvents;
    }

    // Function to load announcements
    async function loadAnnouncements() {
        try {
            const response = await fetch('get_announcements.php');

            if (!response.ok) {
                throw new Error('Failed to fetch announcements');
            }

            const announcements = await response.json();

            // Hide loading indicator
            document.getElementById('loadingIndicator').style.display = 'none';

            if (announcements.length === 0) {
                document.getElementById('noAnnouncements').style.display = 'block';
                return;
            }

            // Sort announcements: latest start date first
            announcements.sort((a, b) => new Date(b.start) - new Date(a.start));

            // Show announcements grid
            document.getElementById('announcementsGrid').style.display = 'grid';

            // Generate announcement cards
            const announcementsHTML = announcements.map(createAnnouncementCard).join('');
            document.getElementById('announcementsGrid').innerHTML = announcementsHTML;

            // Update statistics
            updateStatistics(announcements);

        } catch (error) {
            console.error('Error loading announcements:', error);
            document.getElementById('loadingIndicator').innerHTML = `
                <i class="fas fa-exclamation-triangle"></i>
                Failed to load announcements. Please try again later.
            `;
        }
    }

    // Load announcements on page load
    document.addEventListener('DOMContentLoaded', loadAnnouncements);

    // Refresh announcements every 5 minutes
    setInterval(loadAnnouncements, 300000);
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        showSection('dashboard');
    });
</script>

<script>
    fetch('get_announcements.php')
  .then(res => res.json())
  .then(data => {
    const badge = document.getElementById('announcementBadge');
    if (data.length > 0) {
      badge.textContent = data.length;
      badge.style.display = 'inline-block';
    } else {
      badge.style.display = 'none';
    }
  });

</script>


<script>
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    switch ($_POST['action']) {
        case 'save_security_questions':
            $response = saveSecurityQuestions($conn, $_SESSION['user_id'], $_POST);
            echo json_encode($response);
            exit();
            
        case 'load_security_questions':
            $response = loadSecurityQuestions($conn, $_SESSION['user_id']);
            echo json_encode($response);
            exit();
            
        case 'check_questions_configured':
            $response = checkQuestionsConfigured($conn, $_SESSION['user_id']);
            echo json_encode($response);
            exit();
    }
}

function saveSecurityQuestions($conn, $user_id, $data) {
    try {
        // Validate input
        $required_fields = ['question1', 'answer1', 'question2', 'answer2', 'question3', 'answer3'];
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                return ['success' => false, 'message' => 'All fields are required.'];
            }
        }
        
        // Check for duplicate questions
        $questions = [$data['question1'], $data['question2'], $data['question3']];
        if (count($questions) !== count(array_unique($questions))) {
            return ['success' => false, 'message' => 'Please select different questions for each security question.'];
        }
        
        // Hash the answers for security
        $answer1 = password_hash(trim(strtolower($data['answer1'])), PASSWORD_DEFAULT);
        $answer2 = password_hash(trim(strtolower($data['answer2'])), PASSWORD_DEFAULT);
        $answer3 = password_hash(trim(strtolower($data['answer3'])), PASSWORD_DEFAULT);
        
        // Check if user already has security questions
        $check_sql = "SELECT id FROM security_questions WHERE user_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $user_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Update existing questions
            $sql = "UPDATE security_questions SET 
                    question1 = ?, answer1 = ?, 
                    question2 = ?, answer2 = ?, 
                    question3 = ?, answer3 = ?,
                    updated_at = CURRENT_TIMESTAMP 
                    WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", 
                $data['question1'], $answer1,
                $data['question2'], $answer2,
                $data['question3'], $answer3,
                $user_id
            );
        } else {
            // Insert new questions
            $sql = "INSERT INTO security_questions (user_id, question1, answer1, question2, answer2, question3, answer3) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issssss", 
                $user_id,
                $data['question1'], $answer1,
                $data['question2'], $answer2,
                $data['question3'], $answer3
            );
        }
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Security questions saved successfully!'];
        } else {
            return ['success' => false, 'message' => 'Failed to save security questions.'];
        }
        
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'An error occurred while saving security questions.'];
    }
}

function loadSecurityQuestions($conn, $user_id) {
    try {
        $sql = "SELECT question1, question2, question3 FROM security_questions WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $questions = $result->fetch_assoc();
            return [
                'success' => true,
                'questions' => [
                    'question1' => $questions['question1'],
                    'question2' => $questions['question2'],
                    'question3' => $questions['question3']
                ]
            ];
        } else {
            return ['success' => true, 'questions' => null];
        }
        
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Failed to load security questions.'];
    }
}

function checkQuestionsConfigured($conn, $user_id) {
    try {
        $sql = "SELECT COUNT(*) as count FROM security_questions WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return [
            'success' => true,
            'configured' => $row['count'] > 0
        ];
        
    } catch (Exception $e) {
        return ['success' => false, 'configured' => false];
    }
}

// Function to verify security questions during password recovery
function verifySecurityAnswers($conn, $user_id, $answers) {
    try {
        $sql = "SELECT answer1, answer2, answer3 FROM security_questions WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'No security questions found for this user.'];
        }
        
        $stored_answers = $result->fetch_assoc();
        
        // Verify each answer
        $answer1_correct = password_verify(trim(strtolower($answers['answer1'])), $stored_answers['answer1']);
        $answer2_correct = password_verify(trim(strtolower($answers['answer2'])), $stored_answers['answer2']);
        $answer3_correct = password_verify(trim(strtolower($answers['answer3'])), $stored_answers['answer3']);
        
        if ($answer1_correct && $answer2_correct && $answer3_correct) {
            return ['success' => true, 'message' => 'All answers are correct.'];
        } else {
            return ['success' => false, 'message' => 'One or more answers are incorrect.'];
        }
        
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'An error occurred while verifying answers.'];
    }
}

// Function to get security questions for password recovery
function getSecurityQuestionsForRecovery($conn, $user_identifier) {
    try {
        // Find user by email or username
        $sql = "SELECT r.id, r.full_name, sq.question1, sq.question2, sq.question3 
                FROM residents r 
                LEFT JOIN security_questions sq ON r.id = sq.user_id 
                WHERE r.email = ? OR r.username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $user_identifier, $user_identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'User not found.'];
        }
        
        $user_data = $result->fetch_assoc();
        
        if (empty($user_data['question1'])) {
            return ['success' => false, 'message' => 'No security questions configured for this account.'];
        }
        
        return [
            'success' => true,
            'user_id' => $user_data['id'],
            'user_name' => $user_data['full_name'],
            'questions' => [
                'question1' => $user_data['question1'],
                'question2' => $user_data['question2'],
                'question3' => $user_data['question3']
            ]
        ];
        
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'An error occurred while retrieving security questions.'];
    }
}

<?php
// Function to reset password after successful security question verification
function resetPasswordWithSecurityQuestions($conn, $user_id, $new_password) {
    try {
        // Validate password strength
        if (strlen($new_password) < 8) {
            return ['success' => false, 'message' => 'Password must be at least 8 characters long.'];
        }
        
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update the password in the database
        $sql = "UPDATE residents SET password = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $hashed_password, $user_id);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Password reset successfully!'];
        } else {
            return ['success' => false, 'message' => 'Failed to reset password.'];
        }
        
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'An error occurred while resetting password.'];
    }
}

// Add this JavaScript to your existing profile page for AJAX functionality
?>

<script>
// Enhanced JavaScript for security questions with AJAX
document.addEventListener('DOMContentLoaded', function() {
    loadExistingSecurityQuestions();
});

function loadExistingSecurityQuestions() {
    fetch(window.location.href, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=load_security_questions'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.questions) {
            document.getElementById('question1').value = data.questions.question1 || '';
            document.getElementById('question2').value = data.questions.question2 || '';
            document.getElementById('question3').value = data.questions.question3 || '';
            
            // Update status indicators
            updateQuestionStatus(1, data.questions.question1 ? true : false);
            updateQuestionStatus(2, data.questions.question2 ? true : false);
            updateQuestionStatus(3, data.questions.question3 ? true : false);
        }
    })
    .catch(error => {
        console.error('Error loading security questions:', error);
    });
}

// Enhanced form submission with AJAX
document.getElementById('securityQuestionsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('action', 'save_security_questions');
    formData.append('question1', document.getElementById('question1').value);
    formData.append('answer1', document.getElementById('answer1').value);
    formData.append('question2', document.getElementById('question2').value);
    formData.append('answer2', document.getElementById('answer2').value);
    formData.append('question3', document.getElementById('question3').value);
    formData.append('answer3', document.getElementById('answer3').value);
    
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            updateQuestionStatus(1, true);
            updateQuestionStatus(2, true);
            updateQuestionStatus(3, true);
            
            // Clear answer fields for security
            document.getElementById('answer1').value = '';
            document.getElementById('answer2').value = '';
            document.getElementById('answer3').value = '';
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'An error occurred while saving security questions.');
        console.error('Error:', error);
    });
});
</script>
</script>


</body>
</html>

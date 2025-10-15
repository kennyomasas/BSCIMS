<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Barangay Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            overflow-x: hidden;
        }
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            background-color: #222831;
            padding-top: 20px;
            color: #fff;
        }
        .sidebar a {
            color: #fff;
            padding: 15px 20px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color:rgb(58, 64, 73);
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .header {
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            background-color: #fff;
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1000;
            background-color: #222831;
            
        }
        .content-area {
            padding-top: 70px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h5 class="text-center">Barangay Dashboard</h5>
        <a href="personnel_official.php"><i class="fas fa-user-tie me-2"></i> Barangay Officials</a>
        <a href="personnel_residence.php"><i class="fas fa-users me-2"></i> Registered Residents</a>
        <a href="#"><i class="fas fa-cog me-2"></i> Settings</a>
        <a href="#"><i class="fas fa-chart-line me-2"></i> Reports</a>
    </div>

    <!-- Header -->
    <div class="header d-flex align-items-center text-white">
        <img src="logo.png" alt="Logo" style="height: 30px; margin-right: 10px;">
        <h5 class="mb-0">Barangay San Carlos, City of Valencia, Bukidnon</h5>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content-area">
            <h4>Welcome, Barangay Personnel!</h4>
            <p></p>
          
</body>
</html>

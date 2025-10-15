<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "barangay";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$term = isset($_GET['term']) ? $_GET['term'] : '';


if (!empty($term)) {
    $sql = "SELECT * FROM officials WHERE term = ? ORDER BY id ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $term);
} else {
    $sql = "SELECT * FROM officials ORDER BY term DESC, id ASC";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

// Generate random Bootstrap background colors
$colors = ['primary', 'secondary', 'success', 'danger',  'info'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">   
    <title>Barangay Officials</title>
    <link rel="stylesheet" href="style.css"> <!-- Add your own CSS file if necessary -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add Animate.css for Animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        body {
            overflow: hidden; /* Prevents scrolling */
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 220px;
            background-color: #31363F;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 10;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
            color: white;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .official-photo {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 10px;
            border: 3px solid white;
        }
        .sidebar-item {
            transition: background 0.3s ease-in-out, padding-left 0.3s ease-in-out;
        }
        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.2);
            padding-left: 10px;
        }
        .dropdown:hover .dropdown-menu {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }
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
        .dropdown-menu {
            min-width: 180px;
        }
        .animated-title {
            transition: transform 0.3s ease, color 0.3s ease;
        }
        .animated-title:hover {
            transform: scale(1.1);
            color: #eff319; /* Change to any color you like */
        }

        /* Term Filter Styles */
        .term-filter {
            margin: 10px 0;
            padding: 15px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-left: 20px;
           
        }
        
        .term-info {
            background-color: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 5px;
            padding: 5px 10px;
            margin-left: 10px;
            font-size: 12px;
            color: #1976d2;
            margin-left: 150px;
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<body class="bg-light">
    <div class="d-flex">

      <!-- Sidebar -->
<nav class="sidebar text-white p-3" id="sidebar" style="width: 235px; transition: all 0.3s ease-in-out;">
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
            <a href="Barangay_official.php" id="officialsBtn" class="nav-link text-white d-flex align-items-center sidebar-item dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-page="officials">
                <i class="fas fa-users me-2"></i> Barangay Officials
                <div class="page-indicator"></div>
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="Barangay_official.php" data-page="view-officials">View Officials</a></li>
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
        <div class="container mt-5" style="margin-left: 205px;">
            <h2 class="p-3 text-white fs-4" style="background-color: #222831; margin-top: -50px; margin-right: -27px;">
                <img src="logo.png" alt="Logo" style="height: 35px; width: auto; margin-right: 10px; margin-left: 13px;">
                Barangay Officials and Personnels
            </h2>

            <!-- Term Filter Section -->
            <div class="term-filter" style="margin-right: -27px;">
                <div class="d-flex align-items-center" >
                    <label for="termDropdown" class="form-label me-2 mb-0"><strong>Filter by Term:</strong></label>
                    <select id="termDropdown" class="form-select" style="width: 200px;" >
                        <option value="">All Terms</option >
                        <option value="2022-2025">2022-2025</option>
                        <option value="2025-2028">2025-2028</option>
                        <option value="2028-2031">2028-2031</option>
                    </select>
                    <span id="termInfo" class="term-info" style="display: none;"></span>
                </div>
            </div>

            <!-- Officials Cards Container -->
            <div id="officialsContainer" class="row" style="margin-left: 20px; margin-top: 20px; max-height: 470px; overflow-y: auto; width: 100%;">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <?php $randomColor = $colors[array_rand($colors)]; ?>
                        <div class="col-md-4 mb-4">
                            <div class="card text-center p-3 bg-<?php echo $randomColor; ?>">
                                <img src="uploads/<?php echo $row['photo']; ?>" alt="Official Photo" class="official-photo">
                                <h5><?php echo $row['complete_name']; ?></h5>
                                <p class="mb-1"><strong></strong> <?php echo $row['position']; ?></p>
                                <p class="text-muted"><strong></strong> <?php echo $row['committee']; ?></p>
                                <?php if (empty($_GET['term'])): ?>
                                    <small class="badge bg-light text-dark mb-2">Term: <?php echo $row['term']; ?></small>
                                <?php endif; ?>
                                <button class="btn btn btn-sm" data-bs-toggle="modal" data-bs-target="#officialModal" data-name="<?php echo $row['complete_name']; ?>" data-position="<?php echo $row['position']; ?>" data-mobile="<?php echo $row['committee']; ?>" data-photo="uploads/<?php echo $row['photo']; ?>" data-term="<?php echo $row['term']; ?>">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <?php if (!empty($_GET['term'])): ?>
                                No officials found for term: <?php echo htmlspecialchars($_GET['term']); ?>
                            <?php else: ?>
                                No officials found in the database.
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

  <!-- Modal -->
<div class="modal fade" id="officialModal" tabindex="-1" aria-labelledby="officialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="officialModalLabel">Official Information</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body text-center">
                <!-- Official Photo with Animation -->
                <img src="" id="modalPhoto" alt="Official Photo" class="official-photo mb-3 animate__animated animate__zoomIn">

                <!-- Official Details -->
                <div class="text-start">
                    <p><strong>Name:</strong> <span id="modalName" class=""></span></p>
                    <p><strong>Position:</strong> <span id="modalPosition" class=""></span></p>
                    <p><strong>Committee:</strong> <span id="modalMobile" class=""></span></p>
                    <p><strong>Term:</strong> <span id="modalTerm" class=""></span></p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Modal functionality
        document.addEventListener('DOMContentLoaded', function () {
            var modal = document.getElementById('officialModal');
            modal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var name = button.getAttribute('data-name');
                var position = button.getAttribute('data-position');
                var mobile = button.getAttribute('data-mobile');
                var photo = button.getAttribute('data-photo');
                var term = button.getAttribute('data-term');

                var modalName = modal.querySelector('#modalName');
                var modalPosition = modal.querySelector('#modalPosition');
                var modalMobile = modal.querySelector('#modalMobile');
                var modalPhoto = modal.querySelector('#modalPhoto');
                var modalTerm = modal.querySelector('#modalTerm');

                modalName.textContent = name;
                modalPosition.textContent = position;
                modalMobile.textContent = mobile;
                modalPhoto.src = photo;
                modalTerm.textContent = term;
            });
        });

        // Term filter functionality
        function loadOfficials() {
            const selectedTerm = document.getElementById('termDropdown').value;
            let url = 'Barangay_official.php';
            
            if (selectedTerm) {
                url += '?term=' + encodeURIComponent(selectedTerm);
                updateTermInfo(selectedTerm);
            } else {
                document.getElementById('termInfo').style.display = 'none';
            }
            
            window.location.href = url;
        }

        function updateTermInfo(term) {
            fetch('count_officials.php?term=' + encodeURIComponent(term))
                .then(response => response.json())
                .then(data => {
                    const termInfo = document.getElementById('termInfo');
                    const count = data.count;
                    
                    termInfo.textContent = `${count} official(s) found`;
                    termInfo.style.display = 'inline-block';
                })
                .catch(error => {
                    console.error('Error fetching term info:', error);
                });
        }

        // Event listener for term dropdown
        document.getElementById('termDropdown').addEventListener('change', loadOfficials);

        // Load saved term on page load and set dropdown value
        window.addEventListener('load', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const currentTerm = urlParams.get('term');
            
            if (currentTerm) {
                document.getElementById('termDropdown').value = currentTerm;
                updateTermInfo(currentTerm);
            }
            
            // Also check localStorage for persistence
            const savedTerm = localStorage.getItem('selectedTerm');
            if (savedTerm && !currentTerm) {
                document.getElementById('termDropdown').value = savedTerm;
                loadOfficials();
            }
        });

        // Save selected term to localStorage
        document.getElementById('termDropdown').addEventListener('change', function() {
            const selectedTerm = this.value;
            if (selectedTerm) {
                localStorage.setItem('selectedTerm', selectedTerm);
            } else {
                localStorage.removeItem('selectedTerm');
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
<?php
$stmt->close();
$conn->close();
?>
<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Clear all session variables
$_SESSION = array();

// Delete the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Destroy the session
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out - BSCIMS</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #31363F 0%, #222831 100%);
            overflow: hidden;
        }

        .blur-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                backdrop-filter: blur(0px);
                -webkit-backdrop-filter: blur(0px);
            }
            to {
                opacity: 1;
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
            }
        }

        .logout-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 400px;
            width: 90%;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .logo-section {
            margin-bottom: 25px;
        }

        .logo-section h2 {
            color: #31363F;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .logo-section p {
            color: #666;
            font-size: 0.9rem;
        }

        .loader-wrapper {
            margin: 30px 0;
        }

        .loader {
            width: 60px;
            height: 60px;
            border: 4px solid #f0f0f0;
            border-top: 4px solid #31363F;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .status-message {
            color: #31363F;
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .sub-message {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .offline-icon {
            color: #e74a3b;
            font-size: 3rem;
            margin-bottom: 20px;
            display: none;
        }

        .success-icon {
            color: #1cc88a;
            font-size: 3rem;
            margin-bottom: 20px;
            display: none;
        }

        .progress-bar {
            width: 100%;
            height: 4px;
            background: #f0f0f0;
            border-radius: 2px;
            margin-top: 20px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #31363F, #222831);
            width: 0%;
            border-radius: 2px;
            animation: progress 2s ease-in-out;
        }

        @keyframes progress {
            0% { width: 0%; }
            100% { width: 100%; }
        }

        .connection-status {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 15px;
            font-size: 0.85rem;
            color: #888;
        }

        .connection-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 8px;
            background: #1cc88a;
            animation: pulse 2s infinite;
        }

        .connection-dot.offline {
            background: #e74a3b;
            animation: none;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .logout-container {
                padding: 30px 20px;
                margin: 20px;
            }

            .logo-section h2 {
                font-size: 1.5rem;
            }

            .status-message {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="blur-overlay">
        <div class="logout-container">
            <div class="logo-section">
                <h2>BSCIMS</h2>
                <p>Barangay San Carlos Information Management System</p>
            </div>

            <div class="loader-wrapper">
                <i class="fas fa-wifi offline-icon" id="offlineIcon"></i>
                <i class="fas fa-check-circle success-icon" id="successIcon"></i>
                <div class="loader" id="loader"></div>
            </div>

            <div class="status-message" id="statusMessage">
                Checking Connection...
            </div>
            
            <div class="sub-message" id="subMessage">
                Please wait while we securely log you out
            </div>

            <div class="progress-bar" id="progressBar" style="display: none;">
                <div class="progress-fill"></div>
            </div>

            <div class="connection-status">
                <div class="connection-dot" id="connectionDot"></div>
                <span id="connectionText">Checking network status...</span>
            </div>
        </div>
    </div>

    <script>
        const statusMessage = document.getElementById('statusMessage');
        const subMessage = document.getElementById('subMessage');
        const loader = document.getElementById('loader');
        const offlineIcon = document.getElementById('offlineIcon');
        const successIcon = document.getElementById('successIcon');
        const progressBar = document.getElementById('progressBar');
        const connectionDot = document.getElementById('connectionDot');
        const connectionText = document.getElementById('connectionText');

        function updateConnectionStatus() {
            if (navigator.onLine) {
                connectionDot.classList.remove('offline');
                connectionText.textContent = 'Connected';
                return true;
            } else {
                connectionDot.classList.add('offline');
                connectionText.textContent = 'Offline';
                return false;
            }
        }

        function showOfflineState() {
            loader.style.display = 'none';
            offlineIcon.style.display = 'block';
            statusMessage.textContent = 'No Internet Connection';
            subMessage.textContent = 'Please check your network connection and try again';
            updateConnectionStatus();
        }

        function showSuccessState() {
            loader.style.display = 'none';
            successIcon.style.display = 'block';
            statusMessage.textContent = 'Logout Successful';
            subMessage.textContent = 'Redirecting to login page...';
            progressBar.style.display = 'block';
        }

        function performLogout() {
            // Check initial connection
            if (!updateConnectionStatus()) {
                setTimeout(showOfflineState, 1000);
                return;
            }

            // Update status for online connection
            setTimeout(() => {
                statusMessage.textContent = 'Logging Out...';
                subMessage.textContent = 'Clearing session data and securing your account';
                updateConnectionStatus();
            }, 500);

            // Show success and redirect
            setTimeout(() => {
                showSuccessState();
                updateConnectionStatus();
                
                // Redirect after progress animation
                setTimeout(() => {
                    window.location.href = 'Personnel_login.html';
                }, 2000);
            }, 1500);
        }

        // Listen for online/offline events
        window.addEventListener('online', () => {
            if (offlineIcon.style.display === 'block') {
                location.reload(); // Restart the logout process
            }
            updateConnectionStatus();
        });

        window.addEventListener('offline', () => {
            updateConnectionStatus();
        });

        // Start logout process
        performLogout();

        // Prevent back button during logout
        history.pushState(null, null, location.href);
        window.addEventListener('popstate', function () {
            history.pushState(null, null, location.href);
        });
    </script>
</body>
</html>
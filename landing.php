<?php
// Database connection (add this at the top of your landing.html file, rename it to landing.php)
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

// Fetch hero section data
$hero_data = [];
$result = $conn->query("SELECT * FROM landing_content WHERE section = 'hero'");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $hero_data[$row['field']] = $row['value'];
    }
}

// Set default values if no data exists
$hero_title = $hero_data['title'] ?? 'Welcome to Barangay San Carlos';
$hero_description = $hero_data['description'] ?? 'Your gateway to efficient community services and transparent governance in the digital age.';
$hero_image = $hero_data['image'] ?? 'image/bhall.jpg';


// Fetch features data
$features = [];
$result = $conn->query("SELECT * FROM features ORDER BY id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $features[] = $row;
    }
}

// Default features if none exist in database
if (empty($features)) {
    $features = [
        ['icon' => 'fas fa-user-plus', 'title' => 'Citizen Registration', 'description' => 'Quick and secure registration process to access all barangay services with just a few clicks.'],
        ['icon' => 'fas fa-bullhorn', 'title' => 'Live Announcements', 'description' => 'Stay informed with real-time updates on community events, news, and important notices.'],
        ['icon' => 'fas fa-file-alt', 'title' => 'Document Requests', 'description' => 'Request and track certificates like Indigency, Residency, and Barangay Clearance online.'],
       
    ];
}

// Fetch services data
$services = [];
$result = $conn->query("SELECT * FROM services ORDER BY id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
}

// Default services if none exist in database
if (empty($services)) {
    $services = [
        ['icon' => 'fas fa-certificate', 'title' => 'Barangay Clearance', 'description' => 'Get your barangay clearance certificate quickly and securely. View requirements and submit your application online.'],
        ['icon' => 'fas fa-home', 'title' => 'Residency Certificate', 'description' => 'Obtain your certificate of residency with ease. Complete the process from the comfort of your home.'],
        ['icon' => 'fas fa-hand-holding-heart', 'title' => 'Indigency Certificate', 'description' => 'Apply for your certificate of indigency online. Fast processing with transparent status tracking.'],
      
    ];
}

// Fetch map data
$map_data = [];
$result = $conn->query("SELECT * FROM landing_content WHERE section = 'map'");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $map_data[$row['field']] = $row['value'];
    }
}

// Set default map embed if no data exists
$map_embed = $map_data['embed_url'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d836.0647285893109!2d125.07264386862515!3d7.960605846843486!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32ff1b2e8cbaf339%3A0xe86838c0b3fb745c!2sBarangay%20Hall%20of%20Barangay%20San%20Carlos%2C%20Valencia%20City%2C%20Bukidnon!5e1!3m2!1sfil!2sph!4v1740972448740!5m2!1sfil!2sph';


// Close connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay San Carlos IMS</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
     
   
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
        }

        /* Header */
        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
         
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: #4c63d2;
        }

        .logo i {
            margin-right: 0.5rem;
            font-size: 2rem;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-menu a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-menu a:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transform: translateY(-2px);
        }

        

        .register-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.1)" points="0,1000 1000,0 1000,1000"/></svg>');
            background-size: cover;
        }

        .hero-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            animation: fadeInUp 1s ease-out;
        }

        .hero-content p {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease-out 0.2s both;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            animation: fadeInUp 1s ease-out 0.4s both;
        }

        .btn-primary, .btn-secondary {
            padding: 1rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: white;
            color: #4c63d2;
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-3px);
        }

        .hero-image {
            text-align: center;
            animation: fadeInUp 1s ease-out 0.6s both;
        }

        .hero-image img {
            width: 100%;
            max-width: 400px;
            height: auto;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        /* Section Styling */
        .section {
            padding: 5rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 3rem;
            color: #333;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 2px;
        }

        /* Features Section */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-card i {
            font-size: 3rem;
            color: #4c63d2;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #333;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }

        /* Services Section */
        .services-background {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 5rem 0;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .service-card {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.6s ease;
            opacity: 0;
        }

        .service-card:hover::before {
            opacity: 1;
            transform: rotate(45deg) translate(50%, 50%);
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .service-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
        }

        .service-card h3 {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #333;
        }

        .service-card p {
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .service-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .service-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        /* Map Section */
        .map-container {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            height: 450px;
            margin-top: 2rem;
        }

        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Track Request Section */
        .track-section {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 5rem 2rem;
            text-align: center;
        }

        .track-form {
            max-width: 500px;
            margin: 2rem auto 0;
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }

        .track-form input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            margin-bottom: 1rem;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
        }

        .track-form input::placeholder {
            color: #666;
        }

        .track-form button {
            width: 100%;
            padding: 1rem;
            background: white;
            color: #4c63d2;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .track-form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.3);
        }

        #request-status {
            margin-top: 2rem;
            padding: 1rem;
            border-radius: 10px;
            font-weight: 600;
        }

        /* Contact Section */
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .contact-card {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: all 0.3s ease;
        }

        .contact-card:hover {
            transform: translateY(-5px);
        }

        .contact-card i {
            font-size: 2.5rem;
            color: #4c63d2;
            margin-bottom: 1rem;
        }

        .contact-card h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }

        /* Footer */
        footer {
            background: #1a1a1a;
            color: white;
            text-align: center;
            padding: 3rem 2rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: #4c63d2;
            transform: translateY(-2px);
        }

       /* Navigation Styles */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 15px 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .navbar ul {
            list-style: none;
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .navbar li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .navbar li a:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        .register-btn {
            background: white
            color: white !important;
            font-weight: 600 !important;
        }

        .register-btn:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4) !important;
        }

        .modal {
            display: none !important;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            overflow-y: auto;
            padding: 20px;
        }

        .modal.show {
            display: block !important;
        }

        .modal-content {
            background: white;
            margin: 20px auto;
            padding: 0;
            border-radius: 20px;
            width: 90%;
            max-width: 1200px;
            min-height: calc(100vh - 40px);
            max-height: none;
            overflow: visible;
            box-shadow: 0 20px 60px rgba(56, 37, 124, 0.3)
        position: relative;
            display: flex;
            flex-direction: column;
        }

        .close-btn {
            position: absolute;
            right: 20px;
            top: 20px;
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #666;
            z-index: 10;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            background: #f0f0f0;
            color: #333;
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px 30px;
            border-radius: 20px 20px 0 0;
            text-align: center;
        }

        .modal-header h2 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .modal-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .form-container {
            padding: 30px;
        }

        .profile-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-upload-container {
            position: relative;
            display: inline-block;
        }

        .profile-image-wrapper {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid #667eea;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .profile-image-wrapper:hover {
            transform: scale(1.05);
        }

        .profile-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .upload-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .profile-image-wrapper:hover .upload-overlay {
            opacity: 1;
        }

        .upload-overlay i {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .upload-overlay span {
            font-size: 12px;
        }

        .profile-input {
            display: none;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .section-header i {
            font-size: 1.5rem;
            color: #667eea;
            margin-right: 15px;
        }

        .section-header h3 {
            font-size: 1.3rem;
            color: #333;
            font-weight: 600;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
            font-size: 0.9rem;
        }

        .required {
            color: #e74c3c;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            color: #667eea;
            font-size: 1rem;
            z-index: 1;
        }

        .input-wrapper input,
        .input-wrapper select {
            width: 100%;
            padding: 15px 15px 15px 50px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: white;
        }

        .input-wrapper input:focus,
        .input-wrapper select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .input-wrapper input::placeholder {
            color: #999;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #f0f0f0;
        }

        .btn-secondary,
        .btn-primary {
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
                margin: 1% auto;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .modal-header h2 {
                font-size: 2rem;
            }
            
            .form-actions {
                flex-direction: column;
            }
        }


        /* login modal */

       
        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .nav-menu a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-menu a:hover {
            color: #667eea;
        }

        .register-btn {
            background: white
            color: white !important;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 400px;
            width: 90%;
            transform: scale(0.8) translateY(50px);
            transition: all 0.3s ease;
        }

        .modal-overlay.active .modal-container {
            transform: scale(1) translateY(0);
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }

        .modal-close:hover {
            opacity: 1;
        }

        .modal-title {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .modal-subtitle {
            opacity: 0.9;
            font-size: 1rem;
        }

        .modal-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
        }

        .input-icon .form-input {
            padding-left: 2.5rem;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .checkbox-group input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #667eea;
        }

        .forgot-password {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #764ba2;
        }

        .login-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
            color: #666;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e1e5e9;
        }

        .divider span {
            background: white;
            padding: 0 1rem;
        }

        .social-login {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .social-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            background: white;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-btn:hover {
            border-color: #667eea;
            background: #f8f9fa;
        }

        .signup-link {
            text-align: center;
            color: #666;
        }

        .signup-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .signup-link a:hover {
            color: #764ba2;
        }

        .demo-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .demo-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        @media (max-width: 768px) {
            .nav-menu {
                gap: 1rem;
            }

            .modal-container {
                width: 95%;
            }

            .modal-header, .modal-body {
                padding: 1.5rem;
            }
        }




        /* Mobile Responsive CSS for BSCIM System */
/* Add these styles to your existing CSS file */

/* Mobile First Approach - Base styles for mobile */
@media (max-width: 768px) {
    
    /* Header Navigation */
    .nav-container {
        flex-direction: column;
        padding: 0.75rem 1rem;
        align-items: center;
    }
    
    .nav-menu {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        width: 100%;
        margin-top: 0.75rem;
        gap: 0.25rem;
    }
    
    .nav-menu li {
        flex: 0 0 auto;
    }
    
    .nav-menu a {
        padding: 0.5rem 0.75rem;
        display: block;
        font-size: 0.85rem;
        white-space: nowrap;
        border-radius: 4px;
        transition: all 0.3s ease;
    }
    
    .nav-menu a:hover {
        background-color: rgba(0, 0, 0, 0.1);
    }
    
   
    
    .logo {
        font-size: 1.25rem;
        text-align: center;
        font-weight: bold;
    }
    
    .logo i {
        margin-right: 0.5rem;
        font-size: 1.1rem;
    }
    
    /* Hero Section */
    .hero-container {
        flex-direction: column;
        text-align: center;
        padding: 2rem 1rem;
    }
    
    .hero-content h1 {
        font-size: 2rem;
        margin-bottom: 1rem;
    }
    
    .hero-content p {
        font-size: 1rem;
        margin-bottom: 2rem;
    }
    
    .hero-buttons {
        flex-direction: column;
        gap: 1rem;
        align-items: center;
    }
    
    .hero-buttons .btn-primary,
    .hero-buttons .btn-secondary {
        width: 100%;
        max-width: 250px;
        padding: 1rem;
        font-size: 1rem;
    }
    
    .hero-image {
        margin-top: 2rem;
        width: 100%;
    }
    
    .hero-image img {
        max-width: 100%;
        height: auto;
    }
    
    /* Features Section */
    .features-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
        padding: 0 1rem;
    }
    
    .feature-card {
        padding: 1.5rem;
        text-align: center;
    }
    
    .feature-card i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    
    /* Services Section */
    .services-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
        padding: 0 1rem;
    }
    
    .service-card {
        padding: 1.5rem;
        text-align: center;
    }
    
    .service-icon i {
        font-size: 2.5rem;
    }
    
    /* Section Titles */
    .section-title {
        font-size: 2rem;
        margin-bottom: 2rem;
        text-align: center;
    }
    
    /* Map Section */
    .map-container {
        margin: 0 1rem;
        height: 300px;
    }
    
    .map-container iframe {
        width: 100%;
        height: 100%;
    }
    
    /* Track Section */
    .track-section {
        padding: 3rem 1rem;
    }
    
    .track-form {
        flex-direction: column;
        gap: 1rem;
        max-width: 100%;
    }
    
    .track-form input {
        width: 100%;
        padding: 1rem;
        font-size: 1rem;
    }
    
    .track-form button {
        width: 100%;
        padding: 1rem;
        font-size: 1rem;
    }
    
    /* Contact Section */
    .contact-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
        padding: 0 1rem;
    }
    
    .contact-card {
        padding: 1.5rem;
        text-align: center;
    }
    
    /* Footer */
    .footer-content {
        text-align: center;
        padding: 2rem 1rem;
    }
    
    .footer-links {
        justify-content: center;
        margin-bottom: 1rem;
    }
    
    /* Login Modal */
    .modal-container {
        width: 95%;
        max-width: 400px;
        margin: 2rem auto;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .modal-title {
        font-size: 1.5rem;
    }
    
    .modal-subtitle {
        font-size: 1rem;
    }
    
    .form-input {
        padding: 0.75rem;
        font-size: 1rem;
    }
    
    .login-btn {
        padding: 0.75rem;
        font-size: 1rem;
    }
    
    .social-login {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .social-btn {
        width: 100%;
        justify-content: center;
    }
    
    /* Registration Modal */
    #register-modal .modal-content {
        width: 95%;
        max-width: 500px;
        margin: 1rem auto;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .profile-section {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .profile-image-wrapper {
        width: 100px;
        height: 100px;
        margin: 0 auto;
    }
    
    .section-header {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    
    .section-header h3 {
        font-size: 1.25rem;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .form-label {
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .input-wrapper {
        position: relative;
    }
    
    .input-wrapper i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #666;
    }
    
    .input-wrapper input,
    .input-wrapper select {
        width: 100%;
        padding: 0.75rem 0.75rem 0.75rem 2.5rem;
        font-size: 1rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-sizing: border-box;
    }
    
    .form-actions {
        flex-direction: column;
        gap: 1rem;
        margin-top: 2rem;
    }
    
    .btn-primary,
    .btn-secondary {
        width: 100%;
        padding: 1rem;
        font-size: 1rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    /* Demo Button */
    .demo-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        font-size: 1rem;
        z-index: 1000;
    }
    
    /* Forgot Password Modal */
    #forgotPasswordModal .modal-container {
        width: 95%;
        max-width: 400px;
    }
    
    /* General Mobile Utilities */
    .section {
        padding: 3rem 1rem;
    }
    
    /* Hide/Show elements on mobile */
    .mobile-hide {
        display: none;
    }
    
    .mobile-show {
        display: block;
    }
}

/* Extra small devices (phones in portrait mode) */
@media (max-width: 480px) {
    
    /* More compact header for very small screens */
    .nav-container {
        padding: 0.5rem 0.75rem;
    }
    
    .nav-menu {
        margin-top: 0.5rem;
        gap: 0.15rem;
    }
    
    .nav-menu a {
        padding: 0.4rem 0.6rem;
        font-size: 0.8rem;
    }
    
    .register-btn {
        padding: 0.4rem 0.8rem !important;
        font-size: 0.8rem !important;
    }
    
    .logo {
        font-size: 1.1rem;
    }
    
    .logo i {
        font-size: 1rem;
    }
    
    .hero-content h1 {
        font-size: 1.75rem;
    }
    
    .section-title {
        font-size: 1.75rem;
    }
    
    .modal-container {
        width: 98%;
        margin: 1rem auto;
    }
    
    .form-input,
    .login-btn {
        padding: 0.875rem;
    }
    
    .track-form input,
    .track-form button {
        padding: 0.875rem;
    }
    
    .demo-btn {
        bottom: 15px;
        right: 15px;
        padding: 0.875rem 1.25rem;
    }
    
    .feature-card,
    .service-card,
    .contact-card {
        padding: 1.25rem;
    }
    
    .hero-container {
        padding: 1.5rem 0.75rem;
    }
}

/* Landscape phones */
@media (max-width: 768px) and (orientation: landscape) {
    
    .hero-container {
        flex-direction: row;
        align-items: center;
    }
    
    .hero-content {
        flex: 1;
        text-align: left;
    }
    
    .hero-image {
        flex: 1;
        margin-top: 0;
        margin-left: 2rem;
    }
    
    .modal-container {
        max-height: 85vh;
    }
    
    #register-modal .modal-content {
        max-height: 85vh;
    }
}

/* Touch-friendly improvements */
@media (max-width: 768px) {
    
    /* Larger touch targets */
    button,
    .btn-primary,
    .btn-secondary,
    .login-btn,
    .nav-menu a {
        min-height: 44px;
        min-width: 44px;
    }
    
    /* Better form spacing */
    .form-group {
        margin-bottom: 1.25rem;
    }
    
    /* Improved modal close button */
    .modal-close,
    .close-btn {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    /* Better checkbox and radio styling */
    input[type="checkbox"],
    input[type="radio"] {
        transform: scale(1.2);
        margin-right: 0.5rem;
    }
    
    /* Improved select dropdowns */
    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }
}

        
.steps-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.step-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease;
}

.step-card:hover {
    transform: translateY(-5px);
}

.step-number {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
    margin: 0 auto 1rem;
}

.step-content h3 {
    margin-bottom: 0.5rem;
    color: #333;
}

.step-content p {
    color: #666;
    line-height: 1.6;
}

/* Your existing login modal styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            animation: fadeIn 0.3s ease;
        }

        .modal-overlay.active {
            display: flex;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-container {
            background: white;
            border-radius: 20px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            animation: slideIn 0.4s ease;
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            text-align: center;
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .modal-title {
            font-size: 1.8em;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .modal-subtitle {
            font-size: 1em;
            opacity: 0.9;
        }

        .modal-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .toggle-password {
            position: absolute !important;
            right: 15px !important;
            top: 25px !important;
            cursor: pointer;
            color: #888 !important;
            left: auto !important;
            transform: none !important;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
        }

        .checkbox-group label {
            font-size: 14px;
            color: #6c757d;
        }

        .forgot-password {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .login-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            justify-content: center;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        /* Forgot Password Modal Styles */
        .modal-icon {
            font-size: 3em;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .step-container {
            display: none;
        }

        .step-container.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: #e9ecef;
            border-radius: 3px;
            margin-bottom: 25px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 3px;
            transition: width 0.4s ease;
        }

        .step-title {
            font-size: 1.4em;
            color: #2c3e50;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .step-description {
            color: #6c757d;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .form-select {
            width: 100%;
            padding: 12px 45px 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 16px;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-select:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .question-container {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }

        .question-number {
            background: #667eea;
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9em;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .question-text {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            justify-content: center;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-secondary {
            background: #6c757d;
            margin-right: 10px;
            width: auto;
            flex: 1;
        }

        .btn-secondary:hover {
            background: #545b62;
        }

        .btn-container {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: none;
            align-items: center;
            gap: 10px;
        }

        .alert.show {
            display: flex;
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

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #b3d9e6;
        }

        .password-requirements {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 10px;
            padding: 15px;
            margin-top: 15px;
        }

        .password-requirements h4 {
            color: #0066cc;
            margin-bottom: 10px;
            font-size: 1em;
        }

        .requirement {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 5px;
            font-size: 0.9em;
        }

        .requirement i {
            width: 16px;
        }

        .requirement.valid i {
            color: #28a745;
        }

        .requirement.invalid i {
            color: #dc3545;
        }

        .demo-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .modal-container {
                width: 95%;
                margin: 10px;
            }

            .modal-body {
                padding: 20px;
            }

            .modal-header {
                padding: 20px;
            }

            .btn-container {
                flex-direction: column;
            }

            .btn-secondary {
                width: 100%;
                margin-right: 0;
                margin-bottom: 10px;
            }

            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }

         /* Search Section Styles */
        .search-container {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
        }

        .search-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .search-header i {
            font-size: 3rem;
            color: #27ae60;
            margin-bottom: 1rem;
            display: block;
        }

        .search-header h3 {
            font-size: 1.5rem;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .search-header p {
            color: #666;
            font-size: 0.95rem;
        }

        .search-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .search-input-group {
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            background-color: #f8f9fa;
        }

        .search-input:focus {
            outline: none;
            border-color: #27ae60;
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
            background-color: white;
        }

        .search-input-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 1.1rem;
        }

        .search-btn {
            padding: 1rem 2rem;
            background: linear-gradient(45deg, #27ae60, #2ecc71);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(39, 174, 96, 0.3);
        }

        .search-btn:active {
            transform: translateY(0);
        }

        .search-results {
            margin-top: 1.5rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #27ae60;
            display: none;
        }

        .search-results.show {
            display: block;
        }

        .result-item {
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .result-item:last-child {
            border-bottom: none;
        }

        .result-status {
            font-weight: 600;
            color: #27ae60;
        }

        .result-status.pending {
            color: #f39c12;
        }

        .result-status.rejected {
            color: #e74c3c;
        }

         /* Animation */
        .fade-in {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <nav class="nav-container">
           <div class="logo">
        <img src="logo.png" alt="Logo" style="height: 30px; vertical-align: middle;">
        BSCIM SYSTEM
    </div>
            <ul class="nav-menu">
                <li><a href="#home">Home</a></li>
                <li><a href="#features">Features</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#map">Location</a></li>
                 <li><a href="#search">Status</a></li>
                <li><a href="#" id="register-btn" class="register-btn">Register</a></li>
            </ul>
        </nav>
    </header>

   <!-- Hero Section -->
<section id="home" class="hero">
    <div class="hero-container">
        <div class="hero-content">
            <h1><?php echo htmlspecialchars($hero_title); ?></h1>
            <p><?php echo htmlspecialchars($hero_description); ?></p>
            <div class="hero-buttons">
                <a href="#services" class="btn-primary">
                    <i class="fas fa-rocket"></i>
                    Get Started
                </a>
                <a href="#features" class="btn-secondary">
                    <i class="fas fa-info-circle"></i>
                    Learn More
                </a>
            </div>
        </div>
        <div class="hero-image">
            <img src="<?php echo htmlspecialchars($hero_image); ?>" alt="<?php echo htmlspecialchars($hero_title); ?>" />
        </div>
    </div>
</section>

<!-- How to Use the System Section -->
<section id="how-to-use" class="section fade-in">
    <h2 class="section-title">How to Use the System</h2>
    <div class="steps-container">
        <div class="step-card">
            <div class="step-number">1</div>
            <div class="step-content">
                <h3>Register</h3>
                <p>Create an account by filling out the registration form with your details.</p>
            </div>
        </div>
        
        <div class="step-card">
            <div class="step-number">2</div>
            <div class="step-content">
                <h3>Check Registration Status</h3>
                <p>Use the Registration Lookup below to check if your account has been approved by the admin.</p>
            </div>
        </div>
        
        <div class="step-card">
            <div class="step-number">3</div>
            <div class="step-content">
                <h3>Login</h3>
                <p>Once approved, log in using your registered credentials to access the system.</p>
            </div>
        </div>
        
        <div class="step-card">
            <div class="step-number">4</div>
            <div class="step-content">
                <h3>Submit a Request</h3>
                <p>Choose a service and complete the document request form.</p>
            </div>
        </div>

        <div class="step-card">
            <div class="step-number">5</div>
            <div class="step-content">
                <h3>Track Your Request</h3>
                <p>Monitor your request status and receive updates through your account dashboard.</p>
            </div>
        </div>

        <div class="step-card">
            <div class="step-number">6</div>
            <div class="step-content">
                <h3>Get Your Document</h3>
                <p>Pick up your documents at the Barangay office once your request is approved.</p>
            </div>
        </div>
    </div>
</section>

   <!-- Features Section -->
<section id="features" class="section fade-in">
    <h2 class="section-title">Key Features</h2>
    <div class="features-grid">
        <?php foreach ($features as $feature): ?>
            <div class="feature-card">
                <i class="<?php echo htmlspecialchars($feature['icon']); ?>"></i>
                <h3><?php echo htmlspecialchars($feature['title']); ?></h3>
                <p><?php echo htmlspecialchars($feature['description']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Services Section -->
<div class="services-background">
    <section id="services" class="section fade-in">
        <h2 class="section-title">Our Services</h2>
        <div class="services-grid">
            <?php foreach ($services as $service): ?>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="<?php echo htmlspecialchars($service['icon']); ?>"></i>
                    </div>
                    <h3><?php echo htmlspecialchars($service['title']); ?></h3>
                    <p><?php echo htmlspecialchars($service['description']); ?></p>
                    
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<!-- Map Section -->
<section id="map" class="section fade-in">
    <h2 class="section-title">Find Us</h2>
    <div class="map-container">
        <iframe 
            src="<?php echo htmlspecialchars($map_embed); ?>" 
            allowfullscreen="" 
            loading="lazy">
        </iframe>
    </div>
</section>

  

    <!-- Contact Section -->
    <section id="contact" class="section fade-in">
        <h2 class="section-title">Contact Information</h2>
        <div class="contact-grid">
            <div class="contact-card">
                <i class="fas fa-map-marker-alt"></i>
                <h3>Address</h3>
                <p>Barangay San Carlos<br>City of Valencia<br>Province of Bukidnon</p>
            </div>
            <div class="contact-card">
                <i class="fas fa-phone"></i>
                <h3>Phone</h3>
                <p>+63 123 456 7890</p>
            </div>
            <div class="contact-card">
                <i class="fas fa-envelope"></i>
                <h3>Email</h3>
                <p>barangaysancarlos<br>official@gmail.com</p>
            </div>
        </div>
    </section>

 <!-- Registration Search Section -->
        <section id="search" class="section fade-in">
            <h2 class="section-title">Check Registration Status</h2>
            <div class="search-container">
                <div class="search-header">
                    <i class="fas fa-search"></i>
                    <h3>Registration Lookup</h3>
                    <p>Please Enter your Full Name to check your registration status</p>
                </div>
                
                <form class="search-form" onsubmit="performSearch(event)">
                    <div class="search-input-group">
                        <i class="fas fa-id-card"></i>
                        <input type="text" class="search-input" id="searchInput" placeholder="Enter Full Name" required>
                    </div>
                    
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i> Search Registration
                    </button>
                </form>

                <div class="search-results" id="searchResults">
                    <!-- Results will be displayed here -->
                </div>
            </div>
        </section>
    </div>
    

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-links">
                <a href="#" title="Facebook"><i class="fab fa-facebook"></i></a>
               
            </div>
            <p>&copy; 2025 Barangay San Carlos Information Management System. All rights reserved.</p>
            <p style="margin-top: 0.5rem; color: #999;">Building stronger communities through technology</p>
        </div>
    </footer>

 <!-- Login Modal -->
    <div class="modal-overlay" id="loginModal">
        <div class="modal-container">
            <div class="modal-header">
                <button class="modal-close" id="closeModal">
                    <i class="fas fa-times"></i>
                </button>
                <div class="modal-title">Welcome Back!</div>
                <div class="modal-subtitle">Please Log in your account</div>
            </div>
            <div class="modal-body">
                <form id="loginForm">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <div class="input-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" class="form-input" placeholder="Enter your email address" required>
                        </div>
                    </div>
                    
                    <div class="form-group" style="position: relative;">
                        <label class="form-label">Password</label>
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" id="password" class="form-input" placeholder="Enter your password" required>
                            <i class="fas fa-eye toggle-password" id="togglePassword" style="margin-top: -10px;"></i>
                        </div>
                    </div>
                                              
                    <div class="form-options">
                        <div class="checkbox-group">
                            <input type="checkbox" id="rememberMe">
                            <label for="rememberMe">Remember me</label>
                        </div>
                        <a href="#" class="forgot-password" id="forgotPassBtn">Forgot password?</a>
                    </div>
                                             
                    <button type="submit" class="login-btn">
                        <i class="fas fa-sign-in-alt"></i> Log In
                    </button>
                </form>
            </div>
        </div>
    </div>


     <button class="demo-btn" id="demoBtn">Click to Login </button>

     <!-- Forgot Password Modal -->
    <div class="modal-overlay" id="forgotPasswordModal">
        <div class="modal-container">
            <div class="modal-header">
               
                <div class="modal-icon">
                    <i class="fas fa-key"></i>
                </div>
                <div class="modal-title">Password Recovery</div>
                <div class="modal-subtitle">Let's help you get back into your account</div>
            </div>
            
            <div class="modal-body">
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill" style="width: 33%;"></div>
                </div>

                <!-- Step 1: Enter Username/Email -->
                <div class="step-container active" id="step1">
                    <div class="step-title">
                        <i class="fas fa-user"></i>
                        Step 1: Account Identification
                    </div>
                    <div class="step-description">
                        Enter your Registered email address to begin the recovery process.
                    </div>
                    
                    <div class="alert alert-error" id="step1Error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span id="step1ErrorText"></span>
                    </div>

                    <div class="form-group">
                       
                        <div class="input-icon">
                            <i class="fas fa-envelope"></i>
                            <input type="text" id="userIdentifier" class="form-input" placeholder="Enter your email address" required>
                        </div>
                    </div>

                    <div class="btn-container">
                        <button type="button" class="btn" id="findAccountBtn">
                            <i class="fas fa-search"></i> Find My Account
                        </button>
                    </div>

                    <div style="text-align: center; margin-top: 20px;">
                        <a href="#" id="backToLogin" style="color: #667eea; text-decoration: none; font-size: 14px;">
                            <i class="fas fa-arrow-left"></i> Back to Login
                        </a>
                    </div>
                </div>

                <!-- Step 2: Security Questions -->
                <div class="step-container" id="step2">
                    <div class="step-title">
                        <i class="fas fa-question-circle"></i>
                        Step 2: Security Questions
                    </div>
                    <div class="step-description">
                        Please answer the following security questions to verify your identity.
                    </div>

                    <div class="alert alert-error" id="step2Error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span id="step2ErrorText"></span>
                    </div>

                    <div class="alert alert-info show" id="userInfo">
                        <i class="fas fa-info-circle"></i>
                        <span>Account found: <strong id="foundUserName">John Doe</strong></span>
                    </div>

                    <div id="securityQuestionsContainer">
                        <!-- Questions will be populated dynamically -->
                    </div>

                    <div class="btn-container">
                        <button type="button" class="btn btn-secondary" id="backToStep1">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        <button type="button" class="btn" id="verifyAnswersBtn">
                            <i class="fas fa-check"></i> Verify Answers
                        </button>
                    </div>
                </div>

                <!-- Step 3: Reset Password -->
                <div class="step-container" id="step3">
                    <div class="step-title">
                        <i class="fas fa-lock"></i>
                        Step 3: Create New Password
                    </div>
                    <div class="step-description">
                        Create a strong, secure password for your account.
                    </div>

                    <div class="alert alert-success" id="step3Success">
                        <i class="fas fa-check-circle"></i>
                        <span>Password reset successfully! You can now login with your new password.</span>
                    </div>

                    <div class="alert alert-error" id="step3Error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span id="step3ErrorText"></span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="newPassword" class="form-input" placeholder="Enter new password">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="confirmPassword" class="form-input" placeholder="Confirm new password">
                        </div>
                    </div>

                    <div class="password-requirements">
                        <h4><i class="fas fa-shield-alt"></i> Password Requirements:</h4>
                        <div class="requirement invalid" id="lengthReq">
                            <i class="fas fa-times"></i>
                            <span>At least 8 characters long</span>
                        </div>
                        <div class="requirement invalid" id="uppercaseReq">
                            <i class="fas fa-times"></i>
                            <span>Contains uppercase letter</span>
                        </div>
                        <div class="requirement invalid" id="lowercaseReq">
                            <i class="fas fa-times"></i>
                            <span>Contains lowercase letter</span>
                        </div>
                        <div class="requirement invalid" id="numberReq">
                            <i class="fas fa-times"></i>
                            <span>Contains number</span>
                        </div>
                        <div class="requirement invalid" id="matchReq">
                            <i class="fas fa-times"></i>
                            <span>Passwords match</span>
                        </div>
                    </div>

                    <div class="btn-container">
                        <button type="button" class="btn btn-secondary" id="backToStep2">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                        <button type="button" class="btn" id="resetPasswordBtn" disabled>
                            <i class="fas fa-save"></i> Reset Password
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Registration Modal -->
    <div id="register-modal" class="modal" >
        <div class="modal-content">
            <button class="close-btn">&times;</button>
            <div class="modal-header">
                <h3>Register for Services</h3>
                <p>Join our community portal and access all barangay services</p>
            </div>
            
            <div class="form-container">

                <form id="registration-form" action="online_reg.php" method="POST" enctype="multipart/form-data">
                    
                    <!-- Profile Section -->
                    <div class="profile-section">
                        <div class="profile-upload-container">
                            <div class="profile-image-wrapper">
                                <img id="profile-preview" src="https://via.placeholder.com/120x120/667eea/ffffff?text=Photo" alt="Profile Image" class="profile-image">
                                <div class="upload-overlay">
                                    <i class="fas fa-camera"></i>
                                    <span>Upload Photo</span>
                                </div>
                                <input type="file" id="profile-upload" name="profile-upload" accept="image/*" class="profile-input">
                            </div>
                        </div>
                    </div>

                    <!-- Personal Information -->
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-user-circle"></i>
                            <h3>Personal Information</h3>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Last Name <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <i class="fas fa-user"></i>
                                    <input type="text" name="last-name" placeholder="Enter last name" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">First Name <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <i class="fas fa-user"></i>
                                    <input type="text" name="first-name" placeholder="Enter first name" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Middle Name <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <i class="fas fa-user"></i>
                                    <input type="text" name="middle-name" placeholder="Enter middle name" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Nickname/Alias</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-user-tag"></i>
                                    <input type="text" name="nickname-alias" placeholder="Enter nickname">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Birth Date <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <i class="fas fa-calendar-alt"></i>
                                    <input type="date" name="birthdate" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Birth Place</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <input type="text" name="birthplace" placeholder="Enter birth place">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Citizenship <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <i class="fas fa-flag"></i>
                                    <select name="citizenship" required>
                                        <option value="" disabled selected>Select citizenship</option>
                                        <option value="Filipino">Filipino</option>
                                        
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Gender <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <i class="fas fa-venus-mars"></i>
                                    <select name="gender" required>
                                        <option value="" disabled selected>Select gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Mobile Number <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <i class="fas fa-phone"></i>
                                    <input type="tel" name="mobile-number" placeholder="Enter mobile number" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                               <label class="form-label">Email <span class="required">*</span></label>
                                <div class="input-wrapper">
                               <i class="fas fa-envelope"></i>
                                <input type="email" name="email" placeholder="Enter email address" required>
                            </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Marital Status <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <i class="fas fa-heart"></i>
                                    <select name="marital-status" required>
                                        <option value="" disabled selected>Select marital status</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                    </select>
                                </div>
                            </div>
                            
                           <div class="form-group">
    <label class="form-label">Religion <span class="required">*</span></label>
    <div class="input-wrapper" style="position: relative;">
        <i class="fas fa-praying-hands"></i>
        <select name="religion" id="religionSelect" required>
            <option value="" disabled selected>Select religion</option>
            <option value="Roman Catholic">Roman Catholic</option>
            <option value="Muslim">Muslim</option>
            <option value="Other">Other</option>
        </select>
        <!-- Hidden input for custom religion -->
        <input type="text" name="customReligion" id="customReligionInput" placeholder="Please specify" style="display:none; margin-top:8px; width: 100%; padding: 6px; box-sizing: border-box;">
    </div>
</div>
                            
                            <div class="form-group">
                                <label class="form-label">Sector <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <i class="fas fa-users"></i>
                                    <select name="sector" required>
                                        <option value="" disabled selected>Select sector</option>
                                        <option value="Youth">Youth</option>
                                        <option value="Senior Citizen">Senior Citizen</option>
                                        <option value="PWD (Persons with Disabilities)">PWD (Persons with Disabilities)</option>
                                        <option value="Indigenous Peoples">Indigenous Peoples</option>
                                        <option value="LGBTQ+">LGBTQ+</option>
                                        <option value="Farmer">Farmer</option>
                                        <option value="Business Owner">Business Owner</option>
                                        <option value="Unemployed">Unemployed</option>
                                        <option value="Student">Student</option>
                                        <option value="Employed">Employed</option>
                                        
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Education Level <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <i class="fas fa-graduation-cap"></i>
                                    <select name="education" required>
                                        <option value="" disabled selected>Select education level</option>
                                        <option value="No Formal Education">No Formal Education</option>
                                        <option value="Elementary Level">Elementary Level</option>
                                        <option value="Elementary Graduate">Elementary Graduate</option>
                                        <option value="High School Level">High School Level</option>
                                        <option value="High School Graduate">High School Graduate</option>
                                        <option value="College Level">College Level</option>
                                        <option value="College Graduate">College Graduate</option>
                                     
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Height (cm)</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-ruler-vertical"></i>
                                    <input type="number" name="height" placeholder="Enter height">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Weight (kg)</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-weight"></i>
                                    <input type="number" name="weight" placeholder="Enter weight">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="form-section">
                        <div class="section-header">
                            <i class="fas fa-home"></i>
                            <h3>Address Information</h3>
                        </div>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Sitio</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <select name="sitio">
                                        <option value="">Select sitio (optional)</option>
                                        <option value="Sitio 1">Sitio 1</option>
                                        <option value="Sitio 2">Sitio 2</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">House/Building Number</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-home"></i>
                                    <input type="text" name="house-number" placeholder="Enter house/building number">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Purok <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <i class="fas fa-road"></i>
                                    <select name="purok" required>
                                        <option value="" disabled selected>Select purok</option>
                                        <option value="Purok 1">Purok 1</option>
                                        <option value="Purok 2">Purok 2</option>
                                        <option value="Purok 3">Purok 3</option>
                                        <option value="Purok 4">Purok 4</option>
                                        <option value="Purok 5">Purok 5</option>
                                        <option value="Purok 6">Purok 6</option>
                                        <option value="Purok 7">Purok 7</option>
                                        <option value="Purok 8">Purok 8</option>
                                        <option value="Purok 9">Purok 9</option>
                                        <option value="Purok 10">Purok 10</option>
                                        <option value="Purok 11">Purok 11</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Resident Since Year</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-calendar-alt"></i>
                                    <input type="number" name="since-year" placeholder="Enter year" min="1900" max="2024">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Household Number</label>
                                <div class="input-wrapper">
                                    <i class="fas fa-users"></i>
                                    <input type="text" name="household-number" placeholder="Enter household number">
                                </div>
                            </div>
                            
                           <div class="form-group">
    <label class="form-label">House Owner <span class="required">*</span></label>
    <div class="input-wrapper" style="position: relative;">
        <i class="fas fa-user-check"></i>
        <select name="house-owner" id="houseOwnerSelect" required>
            <option value="" disabled selected>Are you the house owner?</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select>
        <!-- Hidden input for owner name if "No" is selected -->
        <input type="text" name="ownerName" id="ownerNameInput" placeholder="Specify owner name" style="display:none; margin-top:8px; width: 100%; padding: 6px; box-sizing: border-box;">
    </div>
</div>
                            
                            <div class="form-group">
                                <label class="form-label">Shelter Type <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <i class="fas fa-home"></i>
                                    <select name="shelter-type" required>
                                        <option value="" disabled selected>Select shelter type</option>
                                        <option value="Owned">Owned</option>
                                        <option value="Rented">Rented</option>
                                        <option value="Shared">Shared</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">House Material <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <i class="fas fa-tools"></i>
                                    <select name="house-material" required>
                                        <option value="" disabled selected>Select house material</option>
                                        <option value="Concrete">Concrete</option>
                                        <option value="Wood">Wood</option>
                                    </select>
                                </div>
                            </div>

                           <!-- Username Field -->

<!-- Password Field -->
<div class="form-group">
    <label class="form-label">Password <span class="required">*</span></label>
    <div class="input-wrapper">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" placeholder="Enter password" required>
    </div>
</div>
</div>

<!-- Hidden dummy input to trick autofill -->
<input type="text" name="fakeuser" autocomplete="off" style="display:none;">
<input type="password" name="fakepass" autocomplete="new-password" style="display:none;">

                    <!-- Action Buttons -->
                    <div class="form-actions">
                        <button type="reset" class="btn-secondary" style= "margin-left: 700px;">
                            <i class="fas fa-undo"></i>
                            Reset Form
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-user-plus"></i>
                            Register Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="toast" style="
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #4BB543;
    color: white;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    font-family: sans-serif;
    display: none;
    z-index: 9999;
">
    ✅ Registration successful!
</div>

<!-- Toast Notification -->
<div id="toast" style="
    position: fixed;
    top: 20px;
    right: 20px;
    min-width: 250px;
    background-color: #4BB543; /* default success */
    color: white;
    padding: 15px 20px;
    border-radius: 8px;
    font-family: Arial, sans-serif;
    display: none;
    z-index: 9999;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    transition: opacity 0.3s ease;
"></div>



    <script>
        // Show modal when register button is clicked
        document.getElementById('register-btn').addEventListener('click', function(e) {
            e.preventDefault();
            const modal = document.getElementById('register-modal');
            modal.classList.add('show');
        });

        // Close modal functionality
        document.querySelector('.close-btn').addEventListener('click', function() {
            const modal = document.getElementById('register-modal');
            modal.classList.remove('show');
        });

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('register-modal');
            if (event.target === modal) {
                modal.classList.remove('show');
            }
        });

        // Profile image upload preview
        document.getElementById('profile-upload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Profile image click handler
        document.querySelector('.profile-image-wrapper').addEventListener('click', function() {
            document.getElementById('profile-upload').click();
        });

      
        // Input focus effects
        document.querySelectorAll('.input-wrapper input, .input-wrapper select').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });
    </script>

     
    

    <script>
       

        // Modal Functionality
        const registerBtn = document.getElementById('register-btn');
        const modal = document.getElementById('register-modal');
        const closeBtn = document.querySelector('.close-btn');

        registerBtn.addEventListener('click', function(event) {
            event.preventDefault();
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
        });

        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });

      
         
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const headerHeight = document.querySelector('header').offsetHeight;
                    const targetPosition = target.offsetTop - headerHeight - 20;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.style.background = 'rgba(255, 255, 255, 0.98)';
                header.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.15)';
            } else {
                header.style.background = 'rgba(255, 255, 255, 0.95)';
                header.style.boxShadow = '0 2px 30px rgba(0, 0, 0, 0.1)';
            }
        });

        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Service card interactions
        document.querySelectorAll('.service-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // You can add service-specific functionality here
                alert('Service application will be implemented soon!');
            });
        });

        // Add loading animation to buttons
        function addLoadingState(button) {
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            button.disabled = true;
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 2000);
        }

        // Enhanced form validation
        document.querySelectorAll('input[required]').forEach(input => {
            input.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    this.style.borderColor = '#e74c3c';
                    this.style.boxShadow = '0 0 0 3px rgba(231, 76, 60, 0.1)';
                } else {
                    this.style.borderColor = '#27ae60';
                    this.style.boxShadow = '0 0 0 3px rgba(39, 174, 96, 0.1)';
                }
            });
        });

        // Add typing animation to hero text
        function typeWriter(element, text, speed = 100) {
            let i = 0;
            element.innerHTML = '';
            function type() {
                if (i < text.length) {
                    element.innerHTML += text.charAt(i);
                    i++;
                    setTimeout(type, speed);
                }
            }
            type();
        }

        // Initialize typing animation when page loads
        window.addEventListener('load', function() {
            const heroTitle = document.querySelector('.hero-content h1');
            const originalText = heroTitle.textContent;
            setTimeout(() => {
                typeWriter(heroTitle, originalText, 80);
            }, 500);
        });

        // Add parallax effect to hero section
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero');
            const rate = scrolled * -0.5;
            
            if (hero) {
                hero.style.transform = `translateY(${rate}px)`;
            }
        });

        // Add counter animation for statistics (if you want to add stats later)
        function animateCounter(element, target, duration = 2000) {
            let start = 0;
            const increment = target / (duration / 16);
            
            function updateCounter() {
                start += increment;
                if (start < target) {
                    element.textContent = Math.floor(start);
                    requestAnimationFrame(updateCounter);
                } else {
                    element.textContent = target;
                }
            }
            updateCounter();
        }

        // Add hover effects to feature cards
        document.querySelectorAll('.feature-card, .service-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Add search functionality (placeholder for future implementation)
        function initializeSearch() {
            // This can be expanded to search through services, announcements, etc.
            console.log('Search functionality ready for implementation');
        }

        // Initialize all features when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializeSearch();
            console.log('Barangay San Carlos IMS - Modern Version Loaded Successfully');
        });
    </script>

    

  <script>
document.getElementById("registration-form").addEventListener("submit", function (e) {
    e.preventDefault(); // Stop default form behavior

    const form = this;
    const formData = new FormData(form);
    const password = form.querySelector('input[name="password"]').value;
    const email = form.querySelector('input[name="email"]').value;

    // ===== Validation =====
    if (password.length < 6) {
        showToast("❌ Password must be at least 6 characters long.", false);
        return;
    }

    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,}$/i;
    if (!email.match(emailPattern)) {
        showToast("❌ Invalid email format.", false);
        return;
    }

   
    // ===== Submit using fetch =====
    fetch("online_reg.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.text())
    .then(result => {
        if (result.toLowerCase().includes("success")) {
            form.reset();
            showToast("✅ Registration successful!, Please wait for Approval", true);
        } else {
            showToast("❌ Submission failed: " + result, false);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        showToast("❌ Error submitting form.", false);
    });
});

// ===== Toast Function =====
function showToast(message, isSuccess = true) {
    const toast = document.getElementById("toast");
    toast.textContent = message;
    toast.style.backgroundColor = isSuccess ? "#4BB543" : "#e74c3c"; // green or red
    toast.style.display = "block";
    toast.style.opacity = "1";

    setTimeout(() => {
        toast.style.opacity = "0";
        setTimeout(() => {
            toast.style.display = "none";
        }, 300);
    }, 3000);
}
</script>


<script>
// Get DOM elements
const loginModal = document.getElementById('loginModal');
const loginBtn = document.getElementById('login-btn');
const closeModal = document.getElementById('closeModal');
const demoBtn = document.getElementById('demoBtn');
const loginForm = document.getElementById('loginForm');

// Enhanced Toast Notification System
function createToast() {
    // Remove any existing toast
    const existingToast = document.getElementById('toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    const toast = document.createElement('div');
    toast.id = 'toast';
    
    // Set comprehensive styles
    Object.assign(toast.style, {
        position: 'fixed',
        top: '20px',
        right: '20px',
        padding: '15px 20px',
        borderRadius: '8px',
        backgroundColor: '#4BB543',
        color: 'white',
        fontFamily: 'Arial, sans-serif',
        fontSize: '16px',
        fontWeight: 'bold',
        zIndex: '999999',
        boxShadow: '0 4px 15px rgba(0,0,0,0.3)',
        opacity: '0',
        transform: 'translateX(100%)',
        transition: 'all 0.3s ease',
        maxWidth: '300px',
        minWidth: '200px',
        wordWrap: 'break-word',
        pointerEvents: 'none'
    });
    
    document.body.appendChild(toast);
    return toast;
}

function showToast(message, isSuccess = true) {
    console.log('Showing toast:', message); // Debug log
    
    const toast = createToast();
    toast.innerHTML = message;
    
    // Set color based on success/error
    toast.style.backgroundColor = isSuccess ? '#4BB543' : '#e74c3c';
    
    // Force reflow then animate in
    requestAnimationFrame(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateX(0)';
    });
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 300);
    }, 3000);
}

// Modal Functions
function openModal() {
    if (loginModal) {
        loginModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModalFunc() {
    if (loginModal) {
        loginModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

// Event Listeners
if (loginBtn) {
    loginBtn.addEventListener('click', (e) => {
        e.preventDefault();
        openModal();
    });
}

if (demoBtn) {
    demoBtn.addEventListener('click', (e) => {
        e.preventDefault();
        openModal();
    });
}

if (closeModal) {
    closeModal.addEventListener('click', closeModalFunc);
}

if (loginModal) {
    loginModal.addEventListener('click', (e) => {
        if (e.target === loginModal) {
            closeModalFunc();
        }
    });
}

// Escape key to close modal
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && loginModal && loginModal.classList.contains('active')) {
        closeModalFunc();
    }
});

// Enhanced Login Form Submission
if (loginForm) {
    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        console.log('Form submitted'); // Debug log
        
        const submitBtn = loginForm.querySelector('.login-btn');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Logging in...';
        }
        
        // Show initial toast
        showToast('🔄 Submitting login...', true);
        
        const formData = new FormData(loginForm);
        
        fetch('online_login.php', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // Help server identify AJAX request
            }
        })
        .then(response => {
            console.log('Response status:', response.status); // Debug log
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Server did not return JSON response');
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Login response:', data); // Debug log
            
            if (data.status === 'success') {
                // Close modal immediately
                closeModalFunc();
                
                // Show success toast
                showToast('✅ Login successful! ', true);
                
                // Redirect after showing toast
                setTimeout(() => {
                    showToast('🔄 Redirecting now...', true);
                    setTimeout(() => {
                        window.location.href = data.redirect || 'landing_acc.php';
                    }, 500);
                }, 1000);
                
            } else {
                // Show error message
                const errorMessage = data.message || 'Login failed. Please try again.';
                showToast(`❌ ${errorMessage}`, false);
            }
        })
        .catch(error => {
            console.error('Login error:', error); // Debug log
            
            let errorMessage = '❌ Connection error. Please try again.';
            
            if (error.message.includes('JSON')) {
                errorMessage = '❌ Server error. Please contact support.';
            } else if (error.message.includes('HTTP error')) {
                errorMessage = '❌ Server unavailable. Please try again later.';
            }
            
            showToast(errorMessage, false);
        })
        .finally(() => {
            // Re-enable submit button
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Login';
            }
        });
    });
}

// Enhanced Social Login Buttons
document.addEventListener('DOMContentLoaded', () => {
    const socialButtons = document.querySelectorAll('.social-btn');
    
    socialButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            
            const provider = btn.textContent.trim().replace('Continue with ', '');
            showToast(`🔄 Connecting to ${provider}...`, true);
            
            // Simulate social login delay
            setTimeout(() => {
                showToast(`⚠️ ${provider} login is currently under development`, false);
            }, 1500);
        });
    });
});

// Test function (remove in production)
function testToast() {
    console.log('Testing toast notification...');
    showToast('🧪 Toast test successful!', true);
}

// Optional: Add keyboard shortcut for testing (remove in production)
document.addEventListener('keydown', (e) => {
    if (e.ctrlKey && e.shiftKey && e.key === 'T') {
        testToast();
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    console.log('Login modal script initialized');
    
   
});

// Global error handler for uncaught promise rejections
window.addEventListener('unhandledrejection', (event) => {
    console.error('Unhandled promise rejection:', event.reason);
    showToast('❌ An unexpected error occurred', false);
});
</script>

<script>
    const forgotBtn = document.getElementById('forgotPassBtn');
const forgotModal = document.getElementById('forgotPasswordModal');
const closeForgot = document.getElementById('closeForgotModal');
const forgotForm = document.getElementById('forgotPasswordForm');

// Open Forgot Modal
forgotBtn.addEventListener('click', (e) => {
    e.preventDefault();
    forgotModal.classList.add('active');
    document.body.style.overflow = 'hidden';
});

// Close Forgot Modal
closeForgot.addEventListener('click', () => {
    forgotModal.classList.remove('active');
    document.body.style.overflow = 'auto';
});

// Send recovery email
forgotForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(forgotForm);

    fetch('send_recovery.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        showToast(data.message, data.status === 'success');
        if (data.status === 'success') {
            forgotModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    })
    .catch(error => {
        console.error(error);
        showToast("An error occurred.", false);
    });
});

</script>

<script>
    // Mobile Menu Script for BSCIM System


// Mobile Menu Script for BSCIM System with Fixed Register Modal
document.addEventListener('DOMContentLoaded', function() {
    // Create mobile menu toggle button
    function createMobileMenuToggle() {
        const navContainer = document.querySelector('.nav-container');
        if (!navContainer) return;
        
        // Create hamburger button
        const mobileToggle = document.createElement('button');
        mobileToggle.className = 'mobile-menu-toggle';
        mobileToggle.innerHTML = `
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        `;
        
        // Insert after logo
        const logo = document.querySelector('.logo');
        if (logo) {
            logo.parentNode.insertBefore(mobileToggle, logo.nextSibling);
        }
        
        return mobileToggle;
    }
    
    // Add mobile menu styles
    function addMobileMenuStyles() {
        const style = document.createElement('style');
        style.textContent = `
            /* Mobile Menu Styles */
            .mobile-menu-toggle {
                display: none;
                background: none;
                border: none;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                width: 40px;
                height: 40px;
                cursor: pointer;
                padding: 5px;
                border-radius: 8px;
                transition: all 0.3s ease;
                z-index: 1000;
            }
            
            .mobile-menu-toggle:hover {
                background: rgba(102, 126, 234, 0.1);
            }
            
            .hamburger-line {
                display: block;
                width: 25px;
                height: 3px;
                background: #4c63d2;
                margin: 3px 0;
                border-radius: 2px;
                transition: all 0.3s ease;
                transform-origin: center;
            }
            
            .mobile-menu-toggle.active .hamburger-line:nth-child(1) {
                transform: rotate(45deg) translate(6px, 6px);
            }
            
            .mobile-menu-toggle.active .hamburger-line:nth-child(2) {
                opacity: 0;
            }
            
            .mobile-menu-toggle.active .hamburger-line:nth-child(3) {
                transform: rotate(-45deg) translate(6px, -6px);
            }
            
            /* Mobile Menu Overlay */
            .mobile-menu-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(5px);
                z-index: 1500;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }
            
            .mobile-menu-overlay.active {
                opacity: 1;
                visibility: visible;
            }
            
            .mobile-menu {
                position: fixed;
                top: 0;
                right: -100%;
                width: 280px;
                height: 100vh;
                background: white;
                z-index: 1600;
                transition: right 0.3s ease;
                box-shadow: -2px 0 20px rgba(0, 0, 0, 0.1);
                overflow-y: auto;
            }
            
            .mobile-menu.active {
                right: 0;
            }
            
            .mobile-menu-header {
                padding: 1.5rem;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .mobile-menu-logo {
                font-size: 1.25rem;
                font-weight: bold;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
            
            .mobile-menu-close {
                background: none;
                border: none;
                color: white;
                font-size: 1.5rem;
                cursor: pointer;
                padding: 0.5rem;
                border-radius: 50%;
                transition: background 0.3s ease;
            }
            
            .mobile-menu-close:hover {
                background: rgba(255, 255, 255, 0.2);
            }
            
            .mobile-menu-nav {
                padding: 1rem 0;
            }
            
            .mobile-menu-nav ul {
                list-style: none;
                margin: 0;
                padding: 0;
            }
            
            .mobile-menu-nav li {
                border-bottom: 1px solid #f0f0f0;
            }
            
            .mobile-menu-nav a {
                display: block;
                padding: 1rem 1.5rem;
                color: #333;
                text-decoration: none;
                font-weight: 500;
                transition: all 0.3s ease;
                position: relative;
            }
            
            .mobile-menu-nav a:hover {
                background: #f8f9fa;
                color: #4c63d2;
                padding-left: 2rem;
            }
            
            .mobile-menu-nav a:before {
                content: '';
                position: absolute;
                left: 0;
                top: 0;
                bottom: 0;
                width: 4px;
                background: #4c63d2;
                transform: scaleY(0);
                transition: transform 0.3s ease;
            }
            
            .mobile-menu-nav a:hover:before {
                transform: scaleY(1);
            }
            
            .mobile-register-btn {
                margin: 1rem 1.5rem;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 1rem 2rem;
                border: none;
                border-radius: 50px;
                font-weight: 600;
                cursor: pointer;
                width: calc(100% - 3rem);
                transition: all 0.3s ease;
                text-align: center;
                text-decoration: none;
                display: block;
            }
            
            .mobile-register-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            }
            
            /* Modal fixes for mobile */
            .modal {
                z-index: 2000 !important; /* Higher than mobile menu */
            }
            
           
            
            /* Ensure modal content is responsive on mobile */
            @media (max-width: 768px) {
                .modal-content {
                    width: 95% !important;
                    max-width: 400px !important;
                    margin: 5% auto !important;
                    max-height: 90vh !important;
                    overflow-y: auto !important;
                }
            }
            
            /* Desktop view - keep original navigation visible */
            @media (min-width: 769px) {
                .mobile-menu-toggle,
                .mobile-menu-overlay,
                .mobile-menu {
                    display: none !important;
                }
            }
            
            /* Mobile view - show mobile menu, hide desktop nav */
            @media (max-width: 768px) {
                .mobile-menu-toggle {
                    display: flex;
                }
                
                .nav-container {
                    flex-direction: row;
                    justify-content: space-between;
                    align-items: center;
                    padding: 1rem 2rem;
                }
                
                .nav-menu {
                    display: none;
                }
                
                .register-btn {
                    display: none;
                }
            }
        `;
        
        document.head.appendChild(style);
    }

    
    
    // Modal functions - Define these globally so they work from anywhere
    window.openRegisterModal = function() {
        const modal = document.getElementById('register-modal');
        if (modal) {
            modal.style.display = 'flex';
            modal.classList.add('show');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
            
            // Close mobile menu if open
            closeMobileMenu();
        }
    };
    
    window.closeRegisterModal = function() {
        const modal = document.getElementById('register-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.classList.remove('show');
            document.body.style.overflow = ''; // Restore scrolling
        }
    };
    
    // Mobile menu functions
    function openMobileMenu() {
        const overlay = document.querySelector('.mobile-menu-overlay');
        const menu = document.querySelector('.mobile-menu');
        const toggle = document.querySelector('.mobile-menu-toggle');
        
        if (overlay && menu && toggle) {
            overlay.classList.add('active');
            menu.classList.add('active');
            toggle.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }
    
    function closeMobileMenu() {
        const overlay = document.querySelector('.mobile-menu-overlay');
        const menu = document.querySelector('.mobile-menu');
        const toggle = document.querySelector('.mobile-menu-toggle');
        
        if (overlay && menu && toggle) {
            overlay.classList.remove('active');
            menu.classList.remove('active');
            toggle.classList.remove('active');
            document.body.style.overflow = '';
        }
    }
    
    // Create mobile menu structure
    function createMobileMenu() {
        const navMenu = document.querySelector('.nav-menu');
        if (!navMenu) return;
        
        // Create mobile menu overlay
        const overlay = document.createElement('div');
        overlay.className = 'mobile-menu-overlay';
        
        // Create mobile menu
        const mobileMenu = document.createElement('div');
        mobileMenu.className = 'mobile-menu';
        
        // Get logo content
        const logo = document.querySelector('.logo');
        const logoContent = logo ? logo.innerHTML : '<i class="fas fa-truck"></i>BSCIM';
        
        // Create mobile menu content
        mobileMenu.innerHTML = `
            <div class="mobile-menu-header">
                <div class="mobile-menu-logo">${logoContent}</div>
                <button class="mobile-menu-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <nav class="mobile-menu-nav">
                <ul>
                    ${Array.from(navMenu.children).map(li => {
                        const link = li.querySelector('a');
                        if (link && !link.classList.contains('register-btn')) {
                            return `<li><a href="${link.href}">${link.textContent}</a></li>`;
                        }
                        return '';
                    }).join('')}
                </ul>
            </nav>
            <button class="mobile-register-btn" onclick="openRegisterModal()">
                <i class="fas fa-user-plus"></i> Register Now
            </button>
        `;
        
        // Add to body
        document.body.appendChild(overlay);
        document.body.appendChild(mobileMenu);
        
        // Add event listeners
        overlay.addEventListener('click', closeMobileMenu);
        
        const closeBtn = mobileMenu.querySelector('.mobile-menu-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', closeMobileMenu);
        }
    }
    
    // Initialize mobile menu
    function initMobileMenu() {
        addMobileMenuStyles();
        
        const toggle = createMobileMenuToggle();
        if (toggle) {
            createMobileMenu();
            
            // Add toggle event listener
            toggle.addEventListener('click', function() {
                if (this.classList.contains('active')) {
                    closeMobileMenu();
                } else {
                    openMobileMenu();
                }
            });
        }
    }
    
    // Initialize modal event listeners
    function initModalEventListeners() {
        // Close modal when clicking the X button
        const closeBtn = document.querySelector('.close-btn');
        if (closeBtn) {
            closeBtn.addEventListener('click', closeRegisterModal);
        }
        
        // Close modal when clicking outside of it
        const modal = document.getElementById('register-modal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeRegisterModal();
                }
            });
        }
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeRegisterModal();
            }
        });
        
        // Handle existing register button clicks (desktop)
        const existingRegisterBtns = document.querySelectorAll('.register-btn');
        existingRegisterBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                openRegisterModal();
            });
        });
    }
    
    // Initialize everything
    initMobileMenu();
    initModalEventListeners();
    
    // Close mobile menu when window is resized to desktop size
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            closeMobileMenu();
        }
    });
});



// Additional utility functions for modal integration
function openLoginModalFromMobile() {
    // Close mobile menu first
    const mobileMenu = document.querySelector('.mobile-menu');
    const overlay = document.querySelector('.mobile-menu-overlay');
    const toggleButton = document.querySelector('.mobile-menu-toggle');
    
    if (mobileMenu && overlay && toggleButton) {
        mobileMenu.classList.remove('active');
        overlay.classList.remove('active');
        toggleButton.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    // Then open login modal (if you have this function)
    if (typeof openLoginModal === 'function') {
        setTimeout(openLoginModal, 300);
    }
}

function openRegisterModalFromMobile() {
    // Close mobile menu first
    const mobileMenu = document.querySelector('.mobile-menu');
    const overlay = document.querySelector('.mobile-menu-overlay');
    const toggleButton = document.querySelector('.mobile-menu-toggle');
    
    if (mobileMenu && overlay && toggleButton) {
        mobileMenu.classList.remove('active');
        overlay.classList.remove('active');
        toggleButton.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    // Then open register modal (if you have this function)
    if (typeof openRegisterModal === 'function') {
        setTimeout(openRegisterModal, 300);
    }
}
</script>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;

        // Toggle icon class
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
</script>

<script>
    const religionSelect = document.getElementById('religionSelect');
    const customReligionInput = document.getElementById('customReligionInput');

    religionSelect.addEventListener('change', function() {
        if (this.value === 'Other') {
            customReligionInput.style.display = 'block';
            customReligionInput.setAttribute('required', 'required');
            // Clear select value so form validation won't fail if user only types custom religion
            religionSelect.removeAttribute('required');
        } else {
            customReligionInput.style.display = 'none';
            customReligionInput.removeAttribute('required');
            religionSelect.setAttribute('required', 'required');
        }
    });
</script>

<script>
    const houseOwnerSelect = document.getElementById('houseOwnerSelect');
    const ownerNameInput = document.getElementById('ownerNameInput');

    houseOwnerSelect.addEventListener('change', function() {
        if (this.value === 'No') {
            ownerNameInput.style.display = 'block';
            ownerNameInput.setAttribute('required', 'required');
            houseOwnerSelect.removeAttribute('required');
        } else {
            ownerNameInput.style.display = 'none';
            ownerNameInput.removeAttribute('required');
            houseOwnerSelect.setAttribute('required', 'required');
        }
    });
</script>

<script>
    /**
 * Forgot Password Modal Functionality
 * Handles the multi-step password recovery process
 */

class ForgotPasswordModal {
    constructor() {
        this.currentStep = 1;
        this.maxSteps = 3;
        this.initializeEventListeners();
        this.predefinedQuestions = [
            "What was the name of your first pet?",
            "In what city were you born?",
            "What was the name of your elementary school?",
            "What was your childhood nickname?",
            "What was the first company where you worked?",
            "What was the model of your first car?",
            "What was the name of the street you grew up on?",
            "What was your mother's maiden name?",
            "What was the name of your favorite teacher?",
            "In what city did you meet your spouse/significant other?"
        ];
    }

    initializeEventListeners() {
        // Modal controls
        document.getElementById('closeForgotModal')?.addEventListener('click', () => {
            this.closeModal();
        });

        document.getElementById('backToLogin')?.addEventListener('click', (e) => {
            e.preventDefault();
            this.closeModal();
        });

        // Step 1: Find Account
        document.getElementById('findAccountBtn')?.addEventListener('click', () => {
            this.findAccount();
        });

        document.getElementById('userIdentifier')?.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.findAccount();
            }
        });

        // Step 2: Security Questions
        document.getElementById('backToStep1')?.addEventListener('click', () => {
            this.goToStep(1);
        });

        document.getElementById('verifyAnswersBtn')?.addEventListener('click', () => {
            this.verifyAnswers();
        });

        // Step 3: Reset Password
        document.getElementById('backToStep2')?.addEventListener('click', () => {
            this.goToStep(2);
        });

        document.getElementById('resetPasswordBtn')?.addEventListener('click', () => {
            this.resetPassword();
        });

        // Password validation
        document.getElementById('newPassword')?.addEventListener('input', () => {
            this.validatePassword();
        });

        document.getElementById('confirmPassword')?.addEventListener('input', () => {
            this.validatePassword();
        });

        // Close modal when clicking outside
        document.getElementById('forgotPasswordModal')?.addEventListener('click', (e) => {
            if (e.target.id === 'forgotPasswordModal') {
                this.closeModal();
            }
        });
    }

    openModal() {
        document.getElementById('forgotPasswordModal').style.display = 'flex';
        this.resetModal();
    }

    closeModal() {
        document.getElementById('forgotPasswordModal').style.display = 'none';
        this.resetModal();
    }

    resetModal() {
        this.currentStep = 1;
        this.goToStep(1);
        this.clearAllFields();
        this.hideAllAlerts();
    }

    goToStep(step) {
        // Hide all steps
        for (let i = 1; i <= this.maxSteps; i++) {
            const stepElement = document.getElementById(`step${i}`);
            if (stepElement) {
                stepElement.classList.remove('active');
            }
        }

        // Show current step
        const currentStepElement = document.getElementById(`step${step}`);
        if (currentStepElement) {
            currentStepElement.classList.add('active');
        }

        this.currentStep = step;
        this.updateProgressBar();
        this.hideAllAlerts();
    }

    updateProgressBar() {
        const progressFill = document.getElementById('progressFill');
        const percentage = (this.currentStep / this.maxSteps) * 100;
        progressFill.style.width = `${percentage}%`;
    }

    async findAccount() {
        const identifier = document.getElementById('userIdentifier').value.trim();
        
        if (!identifier) {
            this.showError('step1', 'Please enter your username or email address.');
            return;
        }

        const findBtn = document.getElementById('findAccountBtn');
        this.setButtonLoading(findBtn, true);

        try {
            const formData = new FormData();
            formData.append('action', 'find_account');
            formData.append('identifier', identifier);

            const response = await fetch('forgot_password_handler.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                // Store user info and proceed to step 2
                this.userInfo = result.data;
                document.getElementById('foundUserName').textContent = result.data.full_name;
                await this.loadSecurityQuestions();
                this.goToStep(2);
            } else {
                this.showError('step1', result.message);
            }

        } catch (error) {
            this.showError('step1', 'Network error. Please check your connection and try again.');
            console.error('Find account error:', error);
        } finally {
            this.setButtonLoading(findBtn, false);
        }
    }

    async loadSecurityQuestions() {
        try {
            const formData = new FormData();
            formData.append('action', 'get_security_questions');

            const response = await fetch('forgot_password_handler.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.renderSecurityQuestions(result.data);
            } else {
                this.showError('step2', result.message);
            }

        } catch (error) {
            this.showError('step2', 'Error loading security questions.');
            console.error('Load questions error:', error);
        }
    }

    renderSecurityQuestions(questions) {
        const container = document.getElementById('securityQuestionsContainer');
        container.innerHTML = '';

        const questionsArray = [
            { question: questions.question1, id: 'answer1' },
            { question: questions.question2, id: 'answer2' },
            { question: questions.question3, id: 'answer3' }
        ];

        questionsArray.forEach((q, index) => {
            const questionDiv = document.createElement('div');
            questionDiv.className = 'form-group';
            questionDiv.innerHTML = `
                <label class="form-label">Question ${index + 1}: ${q.question}</label>
                <div class="input-icon">
                    <i class="fas fa-key"></i>
                    <input type="text" id="${q.id}" class="form-input" 
                           placeholder="Enter your answer" required>
                </div>
            `;
            container.appendChild(questionDiv);
        });
    }

    async verifyAnswers() {
        const answer1 = document.getElementById('answer1')?.value.trim();
        const answer2 = document.getElementById('answer2')?.value.trim();
        const answer3 = document.getElementById('answer3')?.value.trim();

        if (!answer1 || !answer2 || !answer3) {
            this.showError('step2', 'Please answer all security questions.');
            return;
        }

        const verifyBtn = document.getElementById('verifyAnswersBtn');
        this.setButtonLoading(verifyBtn, true);

        try {
            const formData = new FormData();
            formData.append('action', 'verify_answers');
            formData.append('answer1', answer1);
            formData.append('answer2', answer2);
            formData.append('answer3', answer3);

            const response = await fetch('forgot_password_handler.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.goToStep(3);
            } else {
                this.showError('step2', result.message);
            }

        } catch (error) {
            this.showError('step2', 'Network error. Please check your connection and try again.');
            console.error('Verify answers error:', error);
        } finally {
            this.setButtonLoading(verifyBtn, false);
        }
    }

    validatePassword() {
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        // Length requirement
        const lengthReq = document.getElementById('lengthReq');
        if (newPassword.length >= 8) {
            lengthReq.classList.remove('invalid');
            lengthReq.classList.add('valid');
            lengthReq.querySelector('i').className = 'fas fa-check';
        } else {
            lengthReq.classList.remove('valid');
            lengthReq.classList.add('invalid');
            lengthReq.querySelector('i').className = 'fas fa-times';
        }

        // Uppercase requirement
        const uppercaseReq = document.getElementById('uppercaseReq');
        if (/[A-Z]/.test(newPassword)) {
            uppercaseReq.classList.remove('invalid');
            uppercaseReq.classList.add('valid');
            uppercaseReq.querySelector('i').className = 'fas fa-check';
        } else {
            uppercaseReq.classList.remove('valid');
            uppercaseReq.classList.add('invalid');
            uppercaseReq.querySelector('i').className = 'fas fa-times';
        }

        // Lowercase requirement
        const lowercaseReq = document.getElementById('lowercaseReq');
        if (/[a-z]/.test(newPassword)) {
            lowercaseReq.classList.remove('invalid');
            lowercaseReq.classList.add('valid');
            lowercaseReq.querySelector('i').className = 'fas fa-check';
        } else {
            lowercaseReq.classList.remove('valid');
            lowercaseReq.classList.add('invalid');
            lowercaseReq.querySelector('i').className = 'fas fa-times';
        }

        // Number requirement
        const numberReq = document.getElementById('numberReq');
        if (/[0-9]/.test(newPassword)) {
            numberReq.classList.remove('invalid');
            numberReq.classList.add('valid');
            numberReq.querySelector('i').className = 'fas fa-check';
        } else {
            numberReq.classList.remove('valid');
            numberReq.classList.add('invalid');
            numberReq.querySelector('i').className = 'fas fa-times';
        }

        // Match requirement
        const matchReq = document.getElementById('matchReq');
        if (newPassword && confirmPassword && newPassword === confirmPassword) {
            matchReq.classList.remove('invalid');
            matchReq.classList.add('valid');
            matchReq.querySelector('i').className = 'fas fa-check';
        } else {
            matchReq.classList.remove('valid');
            matchReq.classList.add('invalid');
            matchReq.querySelector('i').className = 'fas fa-times';
        }

        // Enable/disable reset button
        const resetBtn = document.getElementById('resetPasswordBtn');
        const allValid = document.querySelectorAll('.requirement.valid').length === 5;
        resetBtn.disabled = !allValid;
    }

    async resetPassword() {
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if (!newPassword || !confirmPassword) {
            this.showError('step3', 'Please fill in both password fields.');
            return;
        }

        const resetBtn = document.getElementById('resetPasswordBtn');
        this.setButtonLoading(resetBtn, true);

        try {
            const formData = new FormData();
            formData.append('action', 'reset_password');
            formData.append('new_password', newPassword);
            formData.append('confirm_password', confirmPassword);

            const response = await fetch('forgot_password_handler.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                this.showSuccess('step3', result.message);
                // Auto-close modal after 3 seconds
                setTimeout(() => {
                    this.closeModal();
                }, 3000);
            } else {
                this.showError('step3', result.message);
            }

        } catch (error) {
            this.showError('step3', 'Network error. Please check your connection and try again.');
            console.error('Reset password error:', error);
        } finally {
            this.setButtonLoading(resetBtn, false);
        }
    }

    showError(step, message) {
        const errorElement = document.getElementById(`${step}Error`);
        const errorText = document.getElementById(`${step}ErrorText`);
        
        if (errorElement && errorText) {
            errorText.textContent = message;
            errorElement.classList.add('show');
        }
    }

    showSuccess(step, message) {
        const successElement = document.getElementById(`${step}Success`);
        
        if (successElement) {
            successElement.classList.add('show');
        }
    }

    hideAllAlerts() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => alert.classList.remove('show'));
    }

    setButtonLoading(button, loading) {
        if (!button) return;

        if (loading) {
            button.disabled = true;
            const originalText = button.innerHTML;
            button.setAttribute('data-original', originalText);
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        } else {
            button.disabled = false;
            const originalText = button.getAttribute('data-original');
            if (originalText) {
                button.innerHTML = originalText;
            }
        }
    }

    clearAllFields() {
        const inputs = document.querySelectorAll('#forgotPasswordModal input[type="text"], #forgotPasswordModal input[type="password"]');
        inputs.forEach(input => {
            input.value = '';
        });
    }
}

// Initialize the forgot password modal when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.forgotPasswordModal = new ForgotPasswordModal();
});

// Function to open the modal (call this from your login page)
function openForgotPasswordModal() {
    if (window.forgotPasswordModal) {
        window.forgotPasswordModal.openModal();
    }
}
</script>




  <script>
      // Disable browser's automatic scroll restoration
if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
}

// Force scroll to top BEFORE page loads
window.addEventListener('load', function() {
    window.scrollTo(0, 0);
});

function performSearch(event) {
    event.preventDefault();
    
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const query = searchInput.value.trim();
    
    if (!query) {
        searchResults.innerHTML = `
            <div style="text-align: center; padding: 1rem; color: #666;">
                <p>Please enter a complete name to search</p>
            </div>
        `;
        searchResults.classList.add('show');
        return;
    }

    // Client-side validation: Check if it's a complete name or ID
    const isNumeric = !isNaN(query) && !isNaN(parseFloat(query));
    const queryParts = query.trim().split(/\s+/);
    
    if (!isNumeric && queryParts.length < 2) {
        searchResults.innerHTML = `
            <div style="text-align: center; padding: 2rem; background: white; border-radius: 10px; border: 2px solid #f39c12;">
                <i class="fas fa-info-circle" style="font-size: 3rem; color: #f39c12; margin-bottom: 1rem;"></i>
                <h4 style="margin-bottom: 1rem; color: #f39c12;">Complete Name Required</h4>
            </div>
        `;
        searchResults.classList.add('show');
        return;
    }

    // Show loading message
    searchResults.innerHTML = `
        <div style="text-align: center; padding: 1rem;">
            <i class="fas fa-spinner fa-spin" style="font-size: 1.5rem; color: #27ae60;"></i>
            <p style="margin-top: 0.5rem; color: #666;">Searching for "${query}"...</p>
        </div>
    `;
    searchResults.classList.add('show');

    // Create FormData object
    const formData = new FormData();
    formData.append('search_query', query);

    // Send AJAX request to PHP backend
    fetch('landing_status.php', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status} - ${response.statusText}`);
        }
        
        return response.text().then(text => {
            console.log('Raw response:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('JSON parse error:', e);
                console.error('Response text:', text);
                throw new Error('Invalid JSON response from server');
            }
        });
    })
    .then(data => {
        if (data.status === 'success') {
            if (data.residents.length > 0) {
                let resultsHTML = '';
                
                data.residents.forEach((resident, index) => {
                    const fullName = resident.full_name || `${resident.first_name} ${resident.middle_name ? resident.middle_name + ' ' : ''}${resident.last_name}`;
                    const registrationDate = new Date(resident.created_at || Date.now()).toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                    
                    // Calculate age from birthdate
                    let ageInfo = '';
                    if (resident.birthdate) {
                        const birthDate = new Date(resident.birthdate);
                        const today = new Date();
                        const age = Math.floor((today - birthDate) / (365.25 * 24 * 60 * 60 * 1000));
                        ageInfo = `<strong>Age:</strong> ${age} years<br>`;
                    }
                    
                    resultsHTML += `
                        <div class="result-item" style="
                            background: white; 
                            border: 2px solid #27ae60; 
                            border-radius: 10px; 
                            padding: 1rem; 
                            margin-bottom: 1rem;
                            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                            transition: transform 0.2s ease;
                        " onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <h5 style="margin: 0; color: #2c3e50; font-size: 1.2rem;">${fullName}</h5>
                                <span style="background-color: #27ae60; color: white; padding: 0.2rem 0.8rem; border-radius: 15px; font-size: 0.8rem; font-weight: bold;">REGISTERED</span>
                            </div>
                            <div style="color: #555; line-height: 1.4;">
                                <strong>Date Registered:</strong> ${registrationDate}
                            </div>
                        </div>
                    `;
                });
                
                searchResults.innerHTML = resultsHTML;
            } else {
                searchResults.innerHTML = `
                    <div style="text-align: center; padding: 2rem; background: white; border-radius: 10px; border: 2px solid #e74c3c;">
                        <i class="fas fa-search" style="font-size: 3rem; color: #e74c3c; margin-bottom: 1rem;"></i>
                        <h4 style="margin-bottom: 1rem; color: #e74c3c;">No Results Found</h4>
                        <p style="color: #666; margin-bottom: 1rem;">No resident found matching "<strong>${query}</strong>"</p>
                        <div style="background-color: #f8f9fa; padding: 1rem; border-radius: 5px; text-align: left;">
                            <strong>Search tips:</strong>
                            <ul style="margin: 0.5rem 0; padding-left: 1.5rem;">
                                <li>Make sure to use the complete name (e.g., "Juan Dela Cruz")</li>
                                <li>Check the spelling of both first and last name</li>
                                <li>Try using the resident's ID number if known</li>
                                <li>Contact the barangay office if the resident should be registered</li>
                            </ul>
                        </div>
                    </div>
                `;
            }
        } else {
            // Handle specific error for incomplete names
            if (data.requires_complete_name) {
                searchResults.innerHTML = `
                    <div style="text-align: center; padding: 2rem; background: white; border-radius: 10px; border: 2px solid #f39c12;">
                        <i class="fas fa-info-circle" style="font-size: 3rem; color: #f39c12; margin-bottom: 1rem;"></i>
                        <h4 style="margin-bottom: 1rem; color: #f39c12;">Complete Name Required</h4>
                        <p style="color: #666; margin-bottom: 1rem;">${data.message}</p>
                        <div style="background-color: #fff3cd; padding: 1rem; border-radius: 5px; text-align: left; border: 1px solid #f39c12;">
                            <strong>Valid search examples:</strong>
                            <ul style="margin: 0.5rem 0; padding-left: 1.5rem; color: #856404;">
                                <li><strong>Complete Names:</strong> "Juan Cruz", "Maria Santos", "Jose Dela Cruz"</li>
                                <li><strong>ID Numbers:</strong> "12345", "67890"</li>
                            </ul>
                            <small style="color: #856404;"><strong>Note:</strong> Single names are not allowed to protect resident privacy.</small>
                        </div>
                    </div>
                `;
            } else {
                searchResults.innerHTML = `
                    <div style="text-align: center; padding: 2rem; background: white; border-radius: 10px; border: 2px solid #e74c3c;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: #e74c3c; margin-bottom: 1rem;"></i>
                        <h4 style="margin-bottom: 1rem; color: #e74c3c;">Search Error</h4>
                        <p style="color: #666;">${data.message}</p>
                    </div>
                `;
            }
        }
    })
    .catch(error => {
        console.error('Search Error Details:', error);
        console.error('Error message:', error.message);
        console.error('Error stack:', error.stack);
        
        let errorMessage = '';
        if (error.message.includes('Failed to fetch')) {
            errorMessage = `
                <p style="color: #666;">Cannot connect to the server. Please check:</p>
                <ul style="text-align: left; margin: 1rem 0;">
                    <li>Make sure your web server (Apache/Nginx) is running</li>
                    <li>Verify that PHP is properly configured</li>
                    <li>Check if 'landing_status.php' file exists in the same directory</li>
                    <li>Look at browser console (F12) for more details</li>
                </ul>
            `;
        } else if (error.message.includes('Invalid JSON')) {
            errorMessage = `
                <p style="color: #666;">Server returned invalid response. This usually means:</p>
                <ul style="text-align: left; margin: 1rem 0;">
                    <li>PHP syntax error in landing_status.php</li>
                    <li>Database connection issues</li>
                    <li>Check server error logs for PHP errors</li>
                </ul>
            `;
        } else {
            errorMessage = `<p style="color: #666;">Error: ${error.message}</p>`;
        }
        
        searchResults.innerHTML = `
            <div style="text-align: center; padding: 2rem; background: white; border-radius: 10px; border: 2px solid #e74c3c;">
                <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: #e74c3c; margin-bottom: 1rem;"></i>
                <h4 style="margin-bottom: 1rem; color: #e74c3c;">Connection Error</h4>
                ${errorMessage}
                <button onclick="performSearch(event)" style="
                    background-color: #27ae60; 
                    color: white; 
                    border: none; 
                    padding: 0.5rem 1rem; 
                    border-radius: 5px; 
                    cursor: pointer;
                    margin-top: 1rem;
                ">Try Again</button>
            </div>
        `;
    });
}

// Enhanced search functionality
function setupSearchEnhancements() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    
    if (!searchInput || !searchResults) return;
    
    // Clear results when input is empty
    searchInput.addEventListener('input', function() {
        if (this.value.trim() === '') {
            searchResults.classList.remove('show');
            searchResults.innerHTML = '';
        }
    });
    
    // Enable Enter key search
    searchInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            performSearch(event);
        }
    });
    
    // Add placeholder text with better guidance
    searchInput.placeholder = "Enter complete name (e.g., Juan Dela Cruz)...";
    
    // REMOVED: Auto-focus removed to prevent scroll on page load
    // searchInput.focus();
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Force scroll to top immediately
    window.scrollTo(0, 0);
    
    // Clear URL hash if present (removes #search from URL)
    if (window.location.hash) {
        history.replaceState(null, null, window.location.pathname);
    }
    
    // Clear any previous search state
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    
    if (searchInput) {
        searchInput.value = '';
    }
    
    if (searchResults) {
        searchResults.classList.remove('show');
        searchResults.innerHTML = '';
    }
    
    setupSearchEnhancements();
    
    // Add some helpful styling if not already present
    if (!document.getElementById('search-styles')) {
        const style = document.createElement('style');
        style.id = 'search-styles';
        style.textContent = `
            .result-item:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 15px rgba(0,0,0,0.15) !important;
            }
            
            #searchResults.show {
                display: block;
                animation: fadeIn 0.3s ease-in;
            }
            
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            #searchInput {
                transition: border-color 0.3s ease, box-shadow 0.3s ease;
            }
            
            #searchInput:focus {
                border-color: #27ae60;
                box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
                outline: none;
            }
        `;
        document.head.appendChild(style);
    }
});

    </script>




<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileInput = document.querySelector('input[name="mobile-number"]');
    
    if (mobileInput) {
        // Only allow numbers to be typed
        mobileInput.addEventListener('input', function(e) {
            // Remove any non-digit characters
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Limit to 11 digits
            if (this.value.length > 11) {
                this.value = this.value.slice(0, 11);
            }
        });
        
        // Prevent non-numeric keys from being entered
        mobileInput.addEventListener('keypress', function(e) {
            // Allow only numbers (0-9)
            if (e.which < 48 || e.which > 57) {
                e.preventDefault();
            }
        });
        
        // Prevent pasting non-numeric content
        mobileInput.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedText = (e.clipboardData || window.clipboardData).getData('text');
            const numericText = pastedText.replace(/[^0-9]/g, '').slice(0, 11);
            this.value = numericText;
        });
    }
});
</script>


<script>
    // Add this script to your page
document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.querySelector('input[name="email"]');
    
    if (emailInput) {
        // Add custom validation on form submit
        emailInput.closest('form').addEventListener('submit', function(e) {
            const emailValue = emailInput.value.trim();
            
            if (!emailValue.includes('@gmail.com')) {
                e.preventDefault(); // Prevent form submission
                alert('Please enter a valid Gmail address (must end with @gmail.com)');
                emailInput.focus();
                return false;
            }
        });
        
        // Optional: Real-time validation feedback
        emailInput.addEventListener('blur', function() {
            const emailValue = this.value.trim();
            
            if (emailValue && !emailValue.includes('@gmail.com')) {
                this.setCustomValidity('Email must be a Gmail address (@gmail.com)');
                this.reportValidity();
            } else {
                this.setCustomValidity('');
            }
        });
        
        // Clear error when user types
        emailInput.addEventListener('input', function() {
            this.setCustomValidity('');
        });
    }
});
</script>



</body>
</html>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "olshcoslms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OLSHCO: My Requests</title>
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f5f5f5;
        }
        .header {
            background-color: #800000;
            color: white;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .logo {
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
        }
        .header h1 {
            font-size: 1.5rem;
            margin: 0;
        }
        .container {
            display: flex;
            flex: 1;
            transition: all 0.3s ease;
        }
        .sidebar {
            width: 250px;
            background-color: #4B0082;
            color: white;
            padding: 1rem 0;
            transition: all 0.3s ease;
        }
        .sidebar.closed {
            width: 0;
            padding: 0;
            overflow: hidden;
        }
        .toggle-button {
            cursor: pointer;
        }
        .sidebar-menu {
            list-style: none;
        }
        .sidebar-menu li a {
            color: white;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            display: block;
            transition: background-color 0.3s;
        }
        .sidebar-menu li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .main-content {
            flex: 1;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            transition: margin-left 0.3s ease;
        }
        .sidebar.closed + .main-content {
            margin-left: 0;
        }
        .grid {
            display: grid;
            gap: 20px;
        }
        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card-header {
            background-color: #f8f8f8;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        .card-title {
            margin: 0;
            font-size: 1.5rem;
            color: #333;
        }
        .card-content {
            padding: 20px;
        }
        .profile-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .avatar {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .user-name {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .user-info {
            color: #666;
            margin-bottom: 5px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 10px;
        }
        .info-label {
            font-weight: bold;
        }
        @media (min-width: 768px) {
            .grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo toggle-button">
            <img src="logo.png" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <h1>OLSHCO: School Research Manager</h1>
    </header>

    <div class="container">
        <aside class="sidebar">
            <nav>
                <ul class="sidebar-menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="discover.php">Discover</a></li>
                    <li><a href="category.php">Categories</a></li>
                    <li><a href="bookmark.php">Bookmarks</a></li>
                    <li><a href="myrequests.php">Requests</a></li>
                    <li><a href="accountdetails.php">My Account</a></li>
                    <li><a href="feedback">Feedback</a></li>
                    <li><a href="logout.php">Sign Out</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
        <div class="grid">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">User Profile</h2>
                </div>
                <div class="card-content profile-section">
                    <div class="avatar">
                        <img src="images/profile.png?height=200&width=200" alt="User profile">
                    </div>
                    <h2 class="user-name">John Doe</h2>
                    <p class="user-info">User ID: 12345</p>
                    <p class="user-info">Year Level: Senior</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Personal Information</h2>
                </div>
                <div class="card-content">
                    <div class="info-grid">
                        <span class="info-label">Full Name:</span>
                        <span>John Patrick Doe</span>
                        <span class="info-label">Gender:</span>
                        <span>Male</span>
                        <span class="info-label">Birthdate:</span>
                        <span>January 1, 1990</span>
                        <span class="info-label">Email:</span>
                        <span>john.doe@example.com</span>
                        <span class="info-label">Mobile:</span>
                        <span>+1 (555) 123-4567</span>
                        <span class="info-label">Address:</span>
                        <span>123 Main St, Anytown, USA 12345</span>
                    </div>
                </div>
            </div>
</main>

    </div>
    <div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal()">&times;</span>
        <h2>Edit Personal Information</h2>
        <form id="editForm">
            <div class="form-group">
                <label for="edit-fullname">Full Name:</label>
                <input type="text" id="edit-fullname" name="fullname" value="John Patrick Doe">
            </div>
            <!-- Additional form fields as shown in the modal structure -->
            <button type="submit" class="save-button">Save Changes</button>
        </form>
    </div>
</div>
    <script>
        const toggleButton = document.querySelector('.toggle-button');
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');

        toggleButton.addEventListener('click', () => {
            sidebar.classList.toggle('closed');
            if (sidebar.classList.contains('closed')) {
                mainContent.style.marginLeft = '0';
            } else {
                mainContent.style.marginLeft = '';
            }
        });

        window.embeddedChatbotConfig = {
chatbotId: "GMQjwAN64dWbXsYzzjsk_",
domain: "www.chatbase.co"
}
</script>
<script
src="https://www.chatbase.co/embed.min.js"
chatbotId="GMQjwAN64dWbXsYzzjsk_"
domain="www.chatbase.co"
defer>
    </script>
</body>
</html>

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

// Fetch requests
$sql = "SELECT Title, Author, dateRequest, status FROM requests ORDER BY dateRequest DESC";
$result = $conn->query($sql);
$requests = $result->fetch_all(MYSQLI_ASSOC); // Fetch all requests into an array
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OLSHCO:School Research Manager</title>
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
            transition: margin-left 0.3s ease;
        }
        .sidebar.closed + .main-content {
            margin-left: 0;
        }
        .requests-section {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .requests-section h2 {
            color: #800000;
            margin-bottom: 1rem;
        }
        .tab-container {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            border-bottom: 2px solid #eee;
        }
        .tab-button {
            padding: 0.5rem 1rem;
            background: transparent;
            border: none;
            cursor: pointer;
            color: gray;
            transition: color 0.3s, border-bottom 0.3s;
        }
        .tab-button:hover {
            color: #8B0000;
        }
        .tab-button.active {
            border-bottom: 2px solid #8B0000;
            color: #8B0000;
            font-weight: bold;
        }
        .requests-list {
            list-style: none;
            padding: 0;
        }
        .requests-list li {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }
        .requests-list li:last-child {
            border-bottom: none;
        }
        .requests-list a {
            color: #800000;
            text-decoration: none;
        }
        .requests-list a:hover {
            text-decoration: underline;
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
            <section class="requests-section">
                <h2>Your Requests</h2>
                <div class="tab-container">
                    <button onclick="setActiveTab('all')" class="tab-button active">All</button>
                    <button onclick="setActiveTab('pending')" class="tab-button">Pending</button>
                    <button onclick="setActiveTab('confirmed')" class="tab-button">Confirmed</button>
                    <button onclick="setActiveTab('cancelled')" class="tab-button">Cancelled</button>
                </div>
                <ul class="requests-list" id="requestsList">
                    <?php
                    if ($result->num_rows > 0) {
                        foreach ($requests as $row) {
                            echo "<li data-status='" . htmlspecialchars($row['status']) . "' style='color: ";
                            
                            // Set text color based on status
                            switch ($row['status']) {
                                case 'confirmed':
                                    echo "green;";
                                    break;
                                case 'pending':
                                    echo "yellow;";
                                    break;
                                case 'cancelled':
                                    echo "red;";
                                    break;
                                default:
                                    echo "black;"; // Fallback color
                                    break;
                            }
                            echo "'>";
                            echo "<a href='#'>" . htmlspecialchars($row['Title']) . "</a><br>";
                            echo "<small>Author: " . htmlspecialchars($row['Author']) . " | Date Requested: " . htmlspecialchars($row['dateRequest']) . " | Status: " . htmlspecialchars($row['status']) . "</small>";
                            echo "</li>";
                        }
                    } else {
                        echo "<li>No requests found.</li>";
                    }
                    ?>
                </ul>
            </section>
        </main>
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
        let activeTab = 'all';

        function setActiveTab(tab) {
            activeTab = tab;

            // Remove active class from all buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active');
            });

            // Add active class to the clicked button
            const activeButton = document.querySelector(`.tab-button[onclick="setActiveTab('${tab}')"]`);
            if (activeButton) {
                activeButton.classList.add('active');
            }

            // Filter requests based on the active tab
            const requestsList = document.getElementById('requestsList');
            const requests = requestsList.querySelectorAll('li');

            requests.forEach(request => {
                if (tab === 'all' || request.getAttribute('data-status') === tab) {
                    request.style.display = ''; // Show request
                } else {
                    request.style.display = 'none'; // Hide request
                }
            });
        }

        // Initialize to show all requests by default
        document.addEventListener('DOMContentLoaded', () => {
            setActiveTab('all');
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

<?php
$servername = "localhost"; // Change if your server is different
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP
$dbname = "olshcoslms"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the five most recent entries from the research table
$sql_recent = "SELECT ResearchID, Title, Author, Keywords, Category, datePublication FROM research ORDER BY datePublication DESC LIMIT 5";
$result_recent = $conn->query($sql_recent);

// Fetch five random entries from the research table
$sql_random = "SELECT ResearchID, Title, Author, Keywords, Category, datePublication FROM research ORDER BY RAND() LIMIT 5";
$result_random = $conn->query($sql_random);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OLSHCO: School Research Manager - Dashboard</title>
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
            background-color: #400080;
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

        /* Grid for categories */
        .grid {
            display: grid;
            gap: 24px;
        }

        @media (min-width: 768px) {
            .grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        .welcome-section {
            margin-bottom: 2rem;
        }
        .welcome-section h2 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .welcome-section p {
            color: #666;
        }
        .research-section {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .research-section h3 {
            color: #800000;
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }
        .research-list, .random-researches {
            list-style: none;
            padding: 0;
        }
        .research-list li, .random-researches li {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }
        .research-list li:last-child, .random-researches li:last-child {
            border-bottom: none;
        }
        .research-list a, .random-researches a {
            color: #800000;
            text-decoration: none;
        }
        .research-list a:hover, .random-researches a:hover {
            text-decoration: underline;
        }
        .keywords {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .keyword {
            background-color: #f0f0f0;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo toggle-button">
            <img src="logo.png" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <h1>Olshco: School Research Manager</h1>
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
            <section class="welcome-section">
                <h2>Welcome, <span style="color: #800000;">Odie Catabona</span></h2>
                <p>BSIT 2nd Year College</p>
            </section>

            <!-- New section for Random Researches -->
            <h3>Random Research (5 Entries)</h3>
            <section class="research-section">
                <ul class="random-researches">
                    <?php
                    if ($result_random->num_rows > 0) {
                        while($row = $result_random->fetch_assoc()) {
                            echo "<li>";
                            echo "<a href='researchdetails.php?researchid=" . urlencode($row['ResearchID']) . "'>" . htmlspecialchars($row['Title']) . "</a><br>";
                            echo "<strong>Author:</strong> " . htmlspecialchars($row['Author']) . "<br>";
                            echo "<strong>Keywords:</strong> <div class='keywords'>";
                            $keywords = explode(',', $row['Keywords']);
                            foreach ($keywords as $keyword) {
                                echo "<span class='keyword'>" . htmlspecialchars(trim($keyword)) . "</span>";
                            }
                            echo "</div>";
                            echo "<strong>Category:</strong> " . htmlspecialchars($row['Category']) . "<br>";
                            echo "<strong>Date of Publication:</strong> " . htmlspecialchars($row['datePublication']) . "<br>";
                            echo "<strong>Research ID:</strong> " . htmlspecialchars($row['ResearchID']);
                            echo "</li>";
                        }
                    } else {
                        echo "<li>No research found.</li>";
                    }
                    ?>
                </ul>
            </section>

            <!-- Most Recent Research Section -->
            <h3>Most Recent Research (5 Entries)</h3>
            <section class="research-section">
                <ul class="research-list">
                    <?php
                    if ($result_recent->num_rows > 0) {
                        while($row = $result_recent->fetch_assoc()) {
                            echo "<li>";
                            echo "<a href='researchdetails.php?researchid=" . urlencode($row['ResearchID']) . "'>" . htmlspecialchars($row['Title']) . "</a><br>";
                            echo "<strong>Author:</strong> " . htmlspecialchars($row['Author']) . "<br>";
                            echo "<strong>Keywords:</strong> <div class='keywords'>";
                            $keywords = explode(',', $row['Keywords']);
                            foreach ($keywords as $keyword) {
                                echo "<span class='keyword'>" . htmlspecialchars(trim($keyword)) . "</span>";
                            }
                            echo "</div>";
                            echo "<strong>Category:</strong> " . htmlspecialchars($row['Category']) . "<br>";
                            echo "<strong>Date of Publication:</strong> " . htmlspecialchars($row['datePublication']) . "<br>";
                            echo "<strong>Research ID:</strong> " . htmlspecialchars($row['ResearchID']);
                            echo "</li>";
                        }
                    } else {
                        echo "<li>No recent research found.</li>";
                    }
                    ?>
                </ul>
            </section>
        </main>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');

    // Ensure the sidebar is visible initially
    sidebar.classList.remove('closed');

    // Automatically close the sidebar after 1 second with a transition
    setTimeout(function() {
        sidebar.classList.add('closed');
    }, 1000); // Adjust the delay as needed
});

document.querySelector('.toggle-button').addEventListener('click', function() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('closed');
    
    // Adjust main content width based on sidebar visibility
    const mainContent = document.querySelector('.main-content');
    if (sidebar.classList.contains('closed')) {
        mainContent.style.width = '100%'; // Sidebar is closed, main content takes full width
    } else {
        mainContent.style.width = 'calc(100% - 250px)'; // Sidebar is open, adjust main content width
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
<?php
$conn->close();
?>

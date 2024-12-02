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


// Fetch bookmarked entries
$research_id = isset($_GET['researchid']) ? $_GET['researchid'] : null;

$sql_detail = "SELECT Title, Author, Category, datePublication, Abstract, Keywords FROM research WHERE ResearchID = ?";
$stmt = $conn->prepare($sql_detail);
$stmt->bind_param("s", $research_id);
$stmt->execute();
$result_detail = $stmt->get_result();

if ($result_detail->num_rows > 0) {
    $row_detail = $result_detail->fetch_assoc();
    // Assign values to variables
    $title = $row_detail['Title'];
    $author = $row_detail['Author'];
    $category = $row_detail['Category'];
    $datePublication = $row_detail['datePublication'];
    $abstract = $row_detail['Abstract'];
    $keywords = $row_detail['Keywords'];

    $keywords_array = !empty($keywords) ? explode(',', $keywords) : [];

} else {
    // Handle the case when no results are found
    // You can set default values or redirect
    $title = "Not Found";
    $author = "Not Found";
    $category = "Not Found";
    $datePublication = "Not Found";
    $abstract = "Not Found";
    $keywords = "Not Found";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OLSHCO: Bookmarked Research</title>
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
        .research-section {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            line-height: 1.6;
        }
        .author {
            color: #c81d25;
            font-weight: 500;
        }
        .date {
            margin-bottom: 4px;
        }
        .keywords {
            margin-top: 16px;
        }
        .button {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 8px;
            font-weight: bold;
        }
        .button-destructive {
            background-color: #c81d25;
            color: white;
        }
        .button-outline {
            background-color: transparent;
            border: 1px solid #c81d25;
            color: #c81d25;
        }
        .text-gray {
            color: #4b5563;
        }
        .text-blue {
            color: #1d4ed8;
        }
        .text-red {
            color: maroon;
        }
        .card {
            max-width: 800px;
            margin: auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 16px;
            line-height: 1.6;
        }
        .card-header {
            margin-bottom: 16px;
        }
        .card-title {
            font-size: 24px;
            margin-bottom: 8px;
        }
        .research-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
        }
        .tag {
            background-color: #f5f5f5;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 14px;
            color: var(--color-text);
            text-decoration: none;
        }
        .tag:hover {
            background-color: #e5e5e5;
        }
    
</style>

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
    <section class="research-section">
        <div class="card-header">
            <h1 class="card-title"><?php echo $title; ?></h1>
            <div class="space-y-2 mt-4">
            <p>
                    Author: <span class="text-red"><?php echo $author; ?></span>
                </p>
                <p class="date">Date Publication: <?php echo $datePublication; ?></p>
                <p class="font-medium"><?php echo $category; ?></p>
               

                <div class="research-tags">
    
    <?php if (!empty($keywords_array)): ?>
        <?php foreach ($keywords_array as $keyword): ?>
            <a href="search.php?keyword=<?php echo urlencode(trim($keyword)); ?>" class="tag">
                <?php echo htmlspecialchars(trim($keyword)); ?>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-gray">No keywords available.</p>
    <?php endif; ?>
</div>

            </div>
        </div>
        <div class="card-content">
            <div>
                <h2 class="font-bold mb-2">Abstract:</h2>
                <p class="text-gray leading-relaxed">
    <?php echo nl2br(htmlspecialchars($abstract)); ?>
</p>

            </div>
            
          
        </div>
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

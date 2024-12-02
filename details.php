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
    header("Location: search.php");
    exit(); // Ensure the script stops execution after redirecting
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OLSHCO: Discover Research</title>
    <style>
        /* General Styles */
        :root {
            --color-primary: #800000;
            --color-primary-dark: #600000;
            --color-background: #f8f9fa;
            --color-text: #333;
            --color-text-light: #fff;
            --color-link: #1a0dab;
            --color-border: #dfe1e5;
        }
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
            background-color: var(--color-primary);
            color: var(--color-text-light);
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap:10px;
        }
        .header-logo {
            width: auto;
            height: 30px;
            margin-right: 10px;
        }
        .header-title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .logo {
            cursor: pointer;
            width: auto;
            height: 30px;
          
            overflow: hidden;
        }
   
        .container {
            display: flex;
            flex: 1;
            transition: all 0.3s ease;
        }
        .sidebar {
            width: 250px;
            background-color: #600000;
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
        .toggle-sidebar {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
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
            background-color: #8B0000;
    color: white;
    border: 1px solid #8B0000;
        }
    


    </style>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<header class="header">
        <div class="header-left">
            <button id="toggle-button" class="toggle-sidebar" aria-label="Toggle-button">☰</button>
            <img src="images/olshcolar.png" class="logo" alt="Olshcolar Logo" onclick="window.location.href='index.html';" >
        </div>
       
    </header>

    <div class="container">
    <aside class="sidebar closed">
            <nav>
            <ul class="sidebar-menu">
            <li><a href="search.php"><i class="fas solid fa-magnifying-glass"></i> Search</a></li>
    <li><a href="myaccount.php"><i class="fas fa-user"></i> My Profile</a></li>
    
    <li><a href="category.php"><i class="fa-solid fa-layer-group"></i> Categories</a></li>
    <li><a href="analytics.php"><i class="fas fa-chart-line"></i> Analytics</a></li>
    <li><a href="mylib.php"><i class="fas fa-book"></i> My Library</a></li>
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


    <section class="research-section"></section>
        </main>
    </div>

    <script>
       document.getElementById("toggle-button").addEventListener("click", function() {
        const sidebar = document.querySelector(".sidebar");
        sidebar.classList.toggle("closed");
    });
  
    </script>
</body>
</html>

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
        }
        .card-header {
            margin-bottom: 16px;
        }
        .card-title {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .close-button {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #800000;
        }
        .close-button:hover {
            color: #600000;
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
      

        <main class="main-content">
            
    <section class="research-section">
    <button class="close-button" aria-label="Close"  onclick="window.location.href='addresearch.php'">&times;</button>
        <div class="card-header">
            <h1 class="card-title"><?php echo $title; ?></h1>
            
            <div class="space-y-2 mt-4">
            <p>
                    Author: <span class="text-red"><?php echo $author; ?></span>
                </p>
                <p class="date">Date Publication: <?php echo $datePublication; ?></p>
                <p class="font-medium"><?php echo $category; ?></p>
                <p>
                    Research ID: <span class="text-blue"><?php echo htmlspecialchars($research_id); ?></span>
                </p>
            </div>
        </div>
        <div class="card-content">
            <div>
                <h2 class="font-bold mb-2">Abstract:</h2>
                <p class="text-gray leading-relaxed">
                    <?php echo $abstract; ?>
                </p>
            </div>
            <div class="keywords">
                <h2 class="font-bold mb-2">Keywords:</h2>
                <p><?php echo $keywords; ?></p>
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
    </script>
</body>
</html>

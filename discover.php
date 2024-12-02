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

// Set default values for search, sort, limit, and page
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$sort_option = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Construct SQL query based on search and sort options
$sql = "SELECT ResearchID, Title, Author, Keywords, Category, datePublication FROM research";
$params = [];
$types = "";

// Add search conditions if a search query is provided
if (!empty($search_query)) {
    $sql .= " WHERE Title LIKE ? OR Author LIKE ?";
    $search_param = '%' . $search_query . '%';
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ss";
}

// Add sorting option
switch ($sort_option) {
    case 'az':
        $sql .= " ORDER BY Title ASC";
        break;
    case 'za':
        $sql .= " ORDER BY Title DESC";
        break;
    case 'oldest':
        $sql .= " ORDER BY datePublication ASC";
        break;
    default:
        $sql .= " ORDER BY datePublication DESC";
        break;
}

// Add limit and offset for pagination
$sql .= " LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Fetch total number of entries for pagination
$sql_count = "SELECT COUNT(*) AS total FROM research";
if (!empty($search_query)) {
    $sql_count .= " WHERE Title LIKE ? OR Author LIKE ?";
}

$stmt_count = $conn->prepare($sql_count);
if (!empty($search_query)) {
    $stmt_count->bind_param("ss", $search_param, $search_param);
}
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$row_count = $result_count->fetch_assoc();
$total_entries = $row_count['total'];
$total_pages = ceil($total_entries / $limit);

// Close the prepared statements
$stmt->close();
$stmt_count->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OLSHCO: Discover Research</title>
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
        .research-section h2 {
            color: #800000;
            margin-bottom: 1rem;
        }
        .research-list {
            list-style: none;
            padding: 0;
        }
        .research-list li {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }
        .research-list li:last-child {
            border-bottom: none;
        }
        .research-list a {
            color: #800000;
            text-decoration: none;
        }
        .research-list a:hover {
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
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }
        .pagination a {
            margin: 0 5px;
            padding: 10px 15px;
            border: 1px solid #800000;
            color: #800000;
            text-decoration: none;
            border-radius: 4px;
        }
        .pagination a.active {
            background-color: #800000;
            color: white;
        }
        .pagination a:hover {
            background-color: #800000;
            color: white;
        }

        /* Search bar and sorting */
        .search-container {
         
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .search-container input[type="text"] {
            width: 70%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .search-container select {
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .search-container button {
            background-color: #800000;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-container button:hover {
            background-color: #660000;
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
            <section class="research-section">
                <h2>Discover Research</h2>

                <!-- Search Bar and Sorting Options -->
                <div class="search-container">
                    <form action="discover.php" method="GET">
                        <input type="text" name="search" placeholder="Search by title or author..." value="<?php echo htmlspecialchars($search_query); ?>">
                        <select name="sort">
                            <option value="newest" <?php echo $sort_option == 'newest' ? 'selected' : ''; ?>>Newest</option>
                            <option value="oldest" <?php echo $sort_option == 'oldest' ? 'selected' : ''; ?>>Oldest</option>
                            <option value="az" <?php echo $sort_option == 'az' ? 'selected' : ''; ?>>A-Z</option>
                            <option value="za" <?php echo $sort_option == 'za' ? 'selected' : ''; ?>>Z-A</option>
                        </select>
                        <button type="submit">Search</button>
                    </form>
                </div>

                <ul class="research-list">
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<li>";
            // Pass the ResearchID in the query string
            echo "<a href='researchdetails.php?researchid=" . urlencode($row['ResearchID']) . "'>" . htmlspecialchars($row['Title']) . "</a><br>";
            echo "<small>Author: " . htmlspecialchars($row['Author']) . " | Published: " . htmlspecialchars($row['datePublication']) . "</small>";
            echo "<div class='keywords'>";
            foreach (explode(',', $row['Keywords']) as $keyword) {
                echo "<span class='keyword'>" . htmlspecialchars($keyword) . "</span>";
            }
            echo "</div>";
            echo "</li>";
        }
        
    } else {
        echo "<li>No research entries found.</li>";
    }
    ?>
</ul>


                <!-- Pagination -->
                <div class="pagination">
                    <?php
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo "<a href='discover.php?page=$i&search=$search_query&sort=$sort_option' " . ($i == $page ? "class='active'" : "") . ">$i</a>";
                    }
                    ?>
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

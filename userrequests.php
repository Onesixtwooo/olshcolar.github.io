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

// Modify SQL query based on search and sort options
$search_sql = $search_query ? "WHERE Title LIKE '%$search_query%' OR Author LIKE '%$search_query%'" : '';
switch ($sort_option) {
    case 'az':
        $order_sql = "ORDER BY Title ASC";
        break;
    case 'za':
        $order_sql = "ORDER BY Title DESC";
        break;
    case 'oldest':
        $order_sql = "ORDER BY datePublication ASC";
        break;
    default:
        $order_sql = "ORDER BY datePublication DESC";
}

// Fetch 10 research entries based on the search query and sort options
$sql = "SELECT ResearchID, Title, Author, Keywords, Category, datePublication FROM research $search_sql $order_sql LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Fetch total number of entries for pagination
$sql_count = "SELECT COUNT(*) AS total FROM research $search_sql";
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_entries = $row_count['total'];
$total_pages = ceil($total_entries / $limit);
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

         .buttons .button {
      display: inline-block;
      padding: 0.5rem 1rem;
      margin-right: 0.5rem;
      background-color: maroon;
      color: white;
      text-decoration: none;
      border-radius: 0.25rem;
      cursor: pointer;
    }

    .user-requests {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .user-requests h2 {
            color: #800000;
            margin-bottom: 1rem;
        }
        .requests-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        .sort-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .requests-table {
            width: 100%;
            border-collapse: collapse;
        }
        .requests-table th {
            background-color: #800000;
            color: white;
            padding: 0.75rem;
            text-align: left;
        }
        .requests-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #eee;
        }
        .requests-table tr:nth-child(even) {
            background-color: #fff8dc;
        }
        .requests-table tr:hover {
            background-color: #f5f5f5;
        }
        .request-link {
            color: #0066cc;
            text-decoration: none;
            font-weight: 500;
        }
        .request-link:hover {
            text-decoration: underline;
        }
        .status {
            color: #28a745;
            font-weight: 500;
        }
        .file-format {
            background-color: #f8f9fa;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.9rem;
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
                    <li><a href="sudo.php">Dashboard</a></li>
                    <li><a href="addresearch.php">Add Research</a></li>
                    <li><a href="adduser.php">Add User</a></li>
                    <li><a href="bookmark.php">User Requests</a></li>
                    <li><a href="dataanalytics.php">Data Analytics</a></li>
                    <li><a href="accountdetails.php">User Feedbacks</a></li>
                    <li><a href="logout.php">Logout</a></li>
                  
                </ul>
            </nav>
        </aside>
        <main class="main-content">
        <section class="user-requests">
            <div class="requests-header">
                <h2>User Requests</h2>
                <div class="sort-container">
                    <label for="sort">Sort:</label>
                    <select id="sort">
                        <option selected>To Newest</option>
                        <option>To Oldest</option>
                    </select>
                </div>
            </div>
            <table class="requests-table">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Date Request</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>File Format</th>
                        <th>Time Need</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><a href="#" class="request-link">REQ-2024-05-30-2496</a></td>
                        <td>2024-05-30</td>
                        <td>Instructional Strategies for Teaching Pre-Algebra to a Diverse Group of Learners</td>
                        <td><span class="status">Confirmed</span></td>
                        <td><span class="file-format">Soft Copy(.pdf)</span></td>
                        <td>I'm in no Hurry</td>
                    </tr>
                    <tr>
                        <td><a href="#" class="request-link">REQ-2024-06-02-3760</a></td>
                        <td>2024-06-02</td>
                        <td>Effects of the COVID-19 pandemic on medical students: a multicenter quantitative study</td>
                        <td><span class="status">Confirmed</span></td>
                        <td><span class="file-format">Soft Copy(.pdf)</span></td>
                        <td>I'm in no Hurry</td>
                    </tr>
                    <tr>
                        <td><a href="#" class="request-link">REQ-2024-06-03-5856</a></td>
                        <td>2024-06-03</td>
                        <td>Teacher leadership in public schools in the Philippines</td>
                        <td><span class="status">Confirmed</span></td>
                        <td><span class="file-format">Soft Copy(.pdf)</span></td>
                        <td>As soon as possible</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- ... (keep existing content if any) ... -->
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

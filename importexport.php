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
        .main-content .card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card h2, .card p {
            margin-bottom: 10px;
        }
        .input-group {
            margin-bottom: 15px;
        }
        .input-group label {
            display: block;
            margin-bottom: 5px;
        }
        .input-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn {
            background-color: #800000;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .btn:disabled {
            background-color: #999;
            cursor: not-allowed;
        }
        .btn svg {
            width: 16px;
            height: 16px;
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
                    <li><a href="requests.php">User Requests</a></li>
                   
                    <li><a href="userfeedbacks.php">User Feedbacks</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <!-- Import Users -->
            <div class="card">
                <h2>Import Users</h2>
                <p>Upload a CSV file to import new users</p>
                <div class="input-group">
                    <label for="importFile">CSV File</label>
                    <input id="importFile" type="file" accept=".csv" />
                </div>
                <button class="btn" id="importBtn" disabled>
                    <svg viewBox="0 0 24 24"><path d="M3 13v7a1 1 0 001 1h16a1 1 0 001-1v-7"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    Import Users
                </button>
            </div>

            <!-- Export Users -->
            <div class="card">
                <h2>Export Users</h2>
                <p>Download a CSV file of all users</p>
                <button class="btn" id="exportBtn">
                    <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    Export Users
                </button>
            </div>

            <!-- Archive Users -->
            <div class="card">
                <h2>Archive Users</h2>
                <p>Archive inactive 4th-year students</p>
                <form id="archiveForm">
                    <div class="input-group">
                        <label for="archiveYear">Academic Year</label>
                        <input id="archiveYear" type="text" placeholder="e.g. 2023-2024" required />
                    </div>
                    <button class="btn" type="submit">
                        <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="10" x2="21" y2="10"></line><line x1="10" y1="14" x2="14" y2="14"></line><line x1="12" y1="14" x2="12" y2="18"></line></svg>
                        Archive Inactive 4th Year Students
                    </button>
                </form>
            </div>
        </main>
    </div>
  



    <!-- Modal for Edit Research -->
   

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

        const importFileInput = document.getElementById('importFile');
        const importBtn = document.getElementById('importBtn');
        const archiveForm = document.getElementById('archiveForm');

        importFileInput.addEventListener('change', () => {
            importBtn.disabled = !importFileInput.files.length;
        });

        importBtn.addEventListener('click', () => {
            const file = importFileInput.files[0];
            if (file) {
                console.log('Importing users from file:', file.name);
            }
        });

        document.getElementById('exportBtn').addEventListener('click', () => {
            console.log('Exporting users');
        });

        archiveForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const year = document.getElementById('archiveYear').value;
            console.log('Archiving inactive 4th-year students from year:', year);
        });
    </script>
</body>
</html>

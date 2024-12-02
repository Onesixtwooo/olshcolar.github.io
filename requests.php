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
$sort_option = isset($_GET['sort']) ? $_GET['sort'] : 'az';
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Modify SQL query based on search options
$search_sql = $search_query ? "WHERE CONCAT(fname, ' ', midname, ' ', lname) LIKE '%$search_query%' OR phoneno LIKE '%$search_query%'" : '';
$order_sql = $sort_option === 'az' ? "ORDER BY fname ASC" : "ORDER BY fname DESC";

// Fetch user details based on search and sort options
$sql = "SELECT userid, CONCAT(fname, ' ', midname, ' ', lname) AS Name, dateofbirth, phoneno, yearlevel, CONCAT(department, ' - ', block) AS section, accstatus 
        FROM userdetails 
        $search_sql 
        $order_sql 
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);


// Fetch total number of entries for pagination
$sql_count = "SELECT COUNT(*) AS total FROM userdetails $search_sql";
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
        .card {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      margin-bottom: 16px;
      padding: 16px;
    }
    .card-header {
      margin-bottom: 16px;
    }
    .card-title {
      font-size: 1.5em;
      font-weight: bold;
    }
    .card-content {
      display: flex;
      gap: 24px;
    }
    .info {
      text-align: center;
    }
    .info p:first-child {
      font-size: 2em;
      font-weight: bold;
    }
    .info p:last-child {
      color: #6b7280;
      font-size: 0.9em;
    }
    .table-container {
      overflow-x: auto;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #e5e7eb;
    }
    th {
      font-weight: bold;
      color: #374151;
    }
    td {
      color: #6b7280;
    }
    .status {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 9999px;
      font-size: 0.75em;
      font-weight: bold;
    }
    .status.pending {
      background-color: #fef3c7;
      color: #b45309;
    }
    .status.approved {
      background-color: #d1fae5;
      color: #065f46;
    }
    .status.rejected {
      background-color: #fee2e2;
      color: #b91c1c;
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
        <div class="card">
      <div class="card-header">
        <h2 class="card-title">Request Overview</h2>
      </div>
      <div class="card-content">
        <div class="info">
          <p>5</p>
          <p>Total Requests</p>
        </div>
        <div class="info">
          <p>2</p>
          <p>Pending Requests</p>
        </div>
      </div>
    </div>

    <!-- User Requests Card -->
    <div class="card">
      <div class="card-header">
        <h2 class="card-title">User Requests</h2>
      </div>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Date Requested</th>
              <th>User ID</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="4" style="text-align: center; color: #6b7280;">No data available</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
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

        document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');

    // Ensure the sidebar is visible initially
    sidebar.classList.remove('closed');

    // Automatically close the sidebar after 1 second with a transition
    setTimeout(function() {
        sidebar.classList.add('closed');
    }, 1000); // Adjust the delay as needed
});

    </script>
</body>
</html>

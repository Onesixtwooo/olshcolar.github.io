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
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --accent-color: #e74c3c;
            --background-color: #f4f4f4;
            --text-color: #333;
            
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
            border-radius: 1px;
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

    h1 {
            color: var(--header-bg);
            text-align: center;
            margin-bottom: 30px;
        }

        .btn-group {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 20px;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 20px;
            font-weight: bold;
            transition: background-color 0.3s, transform 0.1s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background-color: var(--primary-color);
        }

        .btn-secondary {
            background-color: var(--secondary-color);
        }

        .btn-accent {
            background-color: var(--accent-color);
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
            justify-content: space-between;
            align-items: center;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        select, input {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }
th {
    background-color: #800000; /* Maroon color */
    color: white; /* White text */
    padding: 12px;
    text-align: left;
    font-weight: bold;
}


        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f0f0f0;
        }

        @media (max-width: 768px) {
            .filters {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group {
                flex-direction: column;
                align-items: stretch;
            }

            table {
                font-size: 14px;
            }

            .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .modal-header h2 {
            margin: 0;
        }

        .close-btn {
            cursor: pointer;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .form-section {
            margin-top: 1rem;
        }

        .btn-accent {
            background-color: #e74c3c;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
        <h1>Users Management</h1>
        
        <div class="btn-group">
        <button class="btn btn-primary"  onclick="window.location.href='adduser_a.php'">Add User</button>
            <button class="btn btn-secondary" onclick="window.location.href='edituser.php'">Edit User</button>
            <button class="btn btn-accent" onclick="window.location.href='importexport.php'">Import/Export</button>
        </div>

        <h2>List of Users</h2>

        <div class="filters">
            <div class="filter-group">
                <label for="name-filter">Name:</label>
                <select id="name-filter">
                    <option>A-Z</option>
                    <option>Z-A</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="year-filter">Year Level:</label>
                <select id="year-filter">
                    <option>All</option>
                    <option>Grade 7</option>
                    <option>Grade 11</option>
                    <option>2nd Year College</option>
                    <option>3rd Year College</option>
                    <option>4th Year College</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="status-filter">Status:</label>
                <select id="status-filter">
                    <option>Active</option>
                    <option>Inactive</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="search">Search:</label>
                <input type="text" id="search" placeholder="Filter rows...">
            </div>
        </div>

        <table>
                <thead>
                    <tr>
                        <th>UserID</th>
                        <th>Full Name</th>
                        <th>Date of Birth</th>
                        <th>Phone No.</th>
                        <th>Year Level</th>
                        <th>Section</th>
                        <th>Account Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['userid']); ?></td>
                                <td><?php echo htmlspecialchars($row['Name']); ?></td>
                                <td><?php echo htmlspecialchars($row['dateofbirth']); ?></td>
                                <td><?php echo htmlspecialchars($row['phoneno']); ?></td>
                                <td><?php echo htmlspecialchars($row['yearlevel']); ?></td>
                                <td><?php echo htmlspecialchars($row['section']); ?></td>
                                <td><?php echo htmlspecialchars(!empty($row['accstatus']) ? $row['accstatus'] : 'Inactive'); ?></td>

                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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

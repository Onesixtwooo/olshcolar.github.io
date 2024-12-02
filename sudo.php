<?php
// Database connection details
$servername = "localhost";
$username = "root"; // default for XAMPP
$password = "";
$dbname = "olshcoslms"; // replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch all bookmarked researches (no filtering by userid)
$sql = "SELECT researchid, title, author, category FROM bookmarked";
$result = $conn->query($sql);

$sql_pending = "SELECT COUNT(*) AS pending_count FROM requests WHERE status = 'pending'";
$result_pending = $conn->query($sql_pending);
$pending_count = ($result_pending->num_rows > 0) ? $result_pending->fetch_assoc()['pending_count'] : 0;

// Query to count total users
$sql_users = "SELECT COUNT(*) AS user_count FROM userdetails";
$result_users = $conn->query($sql_users);
$user_count = ($result_users->num_rows > 0) ? $result_users->fetch_assoc()['user_count'] : 0;

// Query to count total researches
$sql_researches = "SELECT COUNT(*) AS research_count FROM research";
$result_researches = $conn->query($sql_researches);
$research_count = ($result_researches->num_rows > 0) ? $result_researches->fetch_assoc()['research_count'] : 0;

// Query to count user feedbacks
$sql_feedbacks = "SELECT COUNT(*) AS feedback_count FROM userfeedbacks";
$result_feedbacks = $conn->query($sql_feedbacks);
$feedback_count = ($result_feedbacks->num_rows > 0) ? $result_feedbacks->fetch_assoc()['feedback_count'] : 0;

// Query to count unique categories
$sql_categories = "SELECT COUNT(DISTINCT category) AS category_count FROM research";
$result_categories = $conn->query($sql_categories);
$category_count = ($result_categories->num_rows > 0) ? $result_categories->fetch_assoc()['category_count'] : 0;


$sqlyearlevel = "SELECT yearlevel, COUNT(*) as user_count 
        FROM userdetails 
        GROUP BY yearlevel";
$result = $conn->query($sqlyearlevel);

// Initialize year level counts
$year_levels = [
    'Grade 7' => 0,
    'Grade 8' => 0,
    'Grade 9' => 0,
    'Grade 10' => 0,
    'Grade 11' => 0,
    'Grade 12' => 0,
    '1st Year' => 0,
    '2nd Year' => 0,
    '3rd Year' => 0,
    '4th Year' => 0,
    '5th Year' => 0
];

// Fetch results and fill in year level counts
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $year_levels[$row['yearlevel']] = $row['user_count'];
    }
}



// Initialize array to store yearlevel counts
$data = [
    'Grade 7' => 0, 'Grade 8' => 0, 'Grade 9' => 0, 'Grade 10' => 0,
    'Grade 11' => 0, 'Grade 12' => 0, '1st Year' => 0, '2nd Year' => 0,
    '3rd Year' => 0, '4th Year' => 0, '5th Year' => 0, 'Faculty' => 0
];

// Fetch data from the database
$sql = "SELECT yearlevel, COUNT(*) as count FROM userdetails GROUP BY yearlevel";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $yearlevel = $row['yearlevel'];
        $count = (int)$row['count'];

        // Check if yearlevel exists in the data array, and if so, update count
        if (array_key_exists($yearlevel, $data)) {
            $data[$yearlevel] = $count;
        }
    }
}

// Pass PHP data array to JavaScript
echo "<script>const demographicsData = " . json_encode($data) . ";</script>";





// Fetch top 10 most-viewed titles from userlog table
$query = "
    SELECT title, COUNT(*) as view_count
    FROM userlog
    GROUP BY title
    ORDER BY view_count DESC
    LIMIT 10
";
$result = $conn->query($query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Dashboard</title>
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

        .grid {
      display: grid;
      gap: 2rem;
    }
    .lg\\:col-span-2 {
      grid-column: span 2;
    }
    .space-y-4 > * + * {
      margin-top: 1rem;
    }
    .space-y-8 > * + * {
      margin-top: 2rem;
    }

    /* Card styling */
    .card {
      background-color: #ffffff;
      border: 1px solid #e0e0e0;
      border-radius: 0.5rem;
      padding: 1rem;
    }
    .card-header {
      font-size: 1.25rem;
      font-weight: bold;
      margin-bottom: 1rem;
    }
    .table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
    }
    .table th, .table td {
      border: 1px solid #e0e0e0;
      padding: 0.5rem;
      text-align: left;
    }
    .table th {
      background-color: #f0f0f0;
      font-weight: bold;
    }

    /* Chart placeholder */
    .chart-placeholder {
      height: 200px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #6c757d;
    }

    .button-container {
            display: grid;
            grid-template-columns: repeat(5, 1fr); /* Adjust to your needs */
            gap: 1rem;
        }

        .rounded-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #007bff;
            color: white;
            padding: 1rem;
            border-radius: 1rem;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .rounded-button:hover {
            background-color: #0056b3;
        }

        .rounded-button .text-muted {
            font-size: 0.875rem;
            margin-top: 0.25rem;
            font-weight: normal;
            color: #e0e0e0;
        }
        a {
        color: inherit;
        text-decoration: none;
    }


    .grid {
            display: grid;
            gap: 20px;
            margin-bottom: 20px;
        }
        .grid-4 {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
        .grid-7 {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .card-title {
            font-size: 1.1em;
            font-weight: bold;
            margin: 0;
        }
        .card-content {
            font-size: 1.5em;
            font-weight: bold;
        }
        .card-content small {
            display: block;
            font-size: 0.6em;
            color: #7f8c8d;
            font-weight: normal;
        }
        .chart {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f2f2f2;
            border-radius: 8px;
            color: #7f8c8d;
            font-size: 1.2rem;
        }
        .pie-chart {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background-color: #f2f2f2;
            margin: 20px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #7f8c8d;
            font-size: 1.2rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .no-data {
            text-align: center;
            color: #7f8c8d;
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
                    <li><a href="requests.php">User Requests</a></li>
               
                    <li><a href="userfeedbacks.php">User Feedbacks</a></li>
                    <li><a href="logout.php">Logout</a></li>
                  
                </ul>
            </nav>
        </aside>

        <main class="main-content">
        <header class="mb-8">
      <h1 class="text-2xl font-bold">Admin Dashboard</h1>
    </header>

    <div class="grid lg:grid-cols-3">
      <!-- Main Content Area -->
      <section class="lg:col-span-2 space-y-8">
      <section class="space-y-8">
    <section class="card">
        <div class="card-header">Overview</div>
        <div class="button-container">
            <a href="userrequests.php"> <!-- Redirect to Pending Requests page -->
                <div class="rounded-button">
                    <?php echo $pending_count; ?>
                    <div class="text-muted">Pending Requests</div>
                </div>
            </a>
            <a href="addusers.php"> <!-- Redirect to Total Users page -->
                <div class="rounded-button">
                    <?php echo $user_count; ?>
                    <div class="text-muted">Total Users</div>
                </div>
            </a>
            <a href="addresearch.php"> <!-- Redirect to Total Researches page -->
                <div class="rounded-button">
                    <?php echo $research_count; ?>
                    <div class="text-muted">Total Researches</div>
                </div>
            </a>
            <a href="userfeedbacks.php"> <!-- Redirect to User Feedbacks page -->
                <div class="rounded-button">
                    <?php echo $feedback_count; ?>
                    <div class="text-muted">User Feedbacks</div>
                </div>
            </a>
            <a href=""> <!-- Redirect to Total Categories page -->
                <div class="rounded-button">
                    <?php echo $category_count; ?>
                    <div class="text-muted">Total Categories</div>
                </div>
            </a>
        </div>
    </section>
</section>

       
        <!-- Users by Year Level -->
        <section class="card">
        <div class="grid grid-7">
                <div class="card" style="grid-column: span 4;">
                    <h2 class="card-title">Monthly Research Viewed:</h2>
                    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>View Count</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['view_count']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='2' class='no-data'>No data available</td></tr>";
            }
            ?>
        </tbody>
    </table>
                </div>
                <div class="card" style="grid-column: span 4;">
                    <h2 class="card-title">Popular Categories</h2>
                    <div class="chart">
                        No data available
                    </div>
                </div>
            </div>

            <div class="grid grid-7">
                <div class="card" style="grid-column: span 4;">
                    <h2 class="card-title">User Demographics:</h2>
                    <div class="pie-chart">
                    <canvas id="demographicsChart"></canvas>
                    </div>
                </div>
                <div class="card" style="grid-column: span 4;">
                    <h2 class="card-title">Top Requested Researches:</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Checkouts</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="no-data">No data available</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        
      </section>


      
    </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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


document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('demographicsChart').getContext('2d');
    const labels = Object.keys(demographicsData);
    const dataValues = Object.values(demographicsData);

    // Create the pie chart
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'User Demographics',
                data: dataValues,
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                    '#9966FF', '#FF9F40', '#FF6384', '#36A2EB',
                    '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false // Remove the legend
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            return `${label}: ${value}`; // Show yearlevel and count on hover
                        }
                    }
                }
            }
        }
    });
});
    </script>
<?php
// Close connection
$conn->close();
?>

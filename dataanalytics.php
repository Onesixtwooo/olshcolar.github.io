
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
            align-items: flex-end;
            justify-content: space-around;
        }
        .chart-bar {
            width: 40px;
            background-color: #3498db;
            transition: height 0.3s ease;
        }
        .pie-chart {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: conic-gradient(
                #3498db 0deg 126deg,
                #e74c3c 126deg 252deg,
                #f1c40f 252deg 360deg
            );
            margin: 20px auto;
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
                    <li><a href="dataanalytics.php">Data Analytics</a></li>
                    <li><a href="userfeedbacks.php">User Feedbacks</a></li>
                    <li><a href="logout.php">Logout</a></li>
                  
                </ul>
            </nav>
        </aside>

        <main class="main-content">
        <h1>Library Analytics Dashboard</h1>

            <div class="grid grid-4">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Total Books</h2>
                        <span>📚</span>
                    </div>
                    <div class="card-content">
                        24,565
                        <small>+2% from last month</small>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Active Users</h2>
                        <span>👥</span>
                    </div>
                    <div class="card-content">
                        5,423
                        <small>+12% from last month</small>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Total Requests</h2>
                        <span>📖</span>
                    </div>
                    <div class="card-content">
                        3,721
                        <small>+8% from last month</small>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Total Feedbacks</h2>
                        <span>📈</span>
                    </div>
                    <div class="card-content">
                        452
                        <small>+15% from last month</small>
                    </div>
                </div>
            </div>

            <div class="grid grid-7">
                <div class="card" style="grid-column: span 4;">
                    <h2 class="card-title">Monthly Book Checkouts</h2>
                    <div class="chart">
                        No data available
                    </div>
                </div>
                <div class="card" style="grid-column: span 3;">
                    <h2 class="card-title">Popular Genres</h2>
                    <div class="chart">
                        No data available
                    </div>
                </div>
            </div>

            <div class="grid grid-7">
                <div class="card" style="grid-column: span 3;">
                    <h2 class="card-title">User Demographics</h2>
                    <div class="pie-chart">
                        No data available
                    </div>
                </div>
                <div class="card" style="grid-column: span 4;">
                    <h2 class="card-title">Top Borrowed Books</h2>
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
        </main>

    
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

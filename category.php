<?php
// Assuming you have a connection to the database (replace with your actual DB details)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "olshcoslms";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "
    SELECT TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(keywords, ',', n.n), ',', -1)) AS keyword
    FROM research
    JOIN (SELECT 1 AS n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
          UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 
          UNION ALL SELECT 9 UNION ALL SELECT 10) n  
    ON CHAR_LENGTH(keywords) - CHAR_LENGTH(REPLACE(keywords, ',', '')) >= n.n - 1
    ORDER BY keyword;
";

$result = $conn->query($sql);
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

        /* Grid for categories */
        .grid {
            display: grid;
            gap: 24px;
        }

        @media (min-width: 768px) {
            .grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        /* Buttons for Categories */
        .categories {
    display: grid;
    gap: 1rem;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); /* Ensures all buttons have consistent width */
}

.category-btn {
    text-align: center;
    background-color: #4B0082;
    color: white;
    border: none;
    padding: 1rem;
    font-size: 1rem;
    cursor: pointer;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: background-color 0.3s;
    width: 100%; /* Ensure buttons fill grid columns */
    margin-top:10px;
}

.category-btn:hover {
    background-color: #6A0DAD;
}

.category-btn i {
    font-size: 1.5rem;
}


        /* Responsive Styles */
        @media (max-width: 768px) {
            .category-btn {
                flex: 1 1 calc(50% - 1rem);
            }
        }

        @media (max-width: 480px) {
            .category-btn {
                flex: 1 1 100%;
            }
        }

        /* Section for Other Researches */
        .other-researches {
            margin-top: 2rem;
            background-color: white; /* Solid white background */
    padding: 1.5rem; /* Add some padding inside the section */
    border-radius: 12px; /* Rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .other-researches h2 {
            margin-bottom: 1rem;
        }
        .other-researches ul {
    display: flex; /* Flexbox for layout */
    flex-wrap: wrap; /* Allow items to wrap to the next row */
    gap: 1rem; /* Space between items */
    padding: 0;
}

.other-researches li {
    flex: 1 1 calc(20% - 1rem); /* 5 items per row (100% / 5 = 20%) */
    list-style: none; /* Remove default list styling */
    text-align: center; /* Center-align text */
}

.other-researches a {
    color: #4B0082; /* Link color */
    text-decoration: none; /* Remove underline */
    font-size: 1rem; /* Adjust font size */
    transition: color 0.3s ease; /* Smooth hover effect */
}

.other-researches a:hover {
    color: #6A0DAD; /* Change color on hover */
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
            <a href="search.php"><i class="fas solid fa-magnifying-glass"></i> Search</a>
    <a href="myaccount.php"><i class="fas fa-user"></i> My Profile</a>
    
    <a href="category.php"><i class="fa-solid fa-layer-group"></i> Categories</a>
    <a href="analytics.php"><i class="fas fa-chart-line"></i> Analytics</a>
    <a href="mylib.php"><i class="fas fa-book"></i> My Library</a>
</ul>

                   
            </nav>
        </aside>

        <main class="main-content">
            <h1>Research Categories</h1>
            <!-- Category Buttons -->
            <div class="categories">
            <button class="category-btn">
                    <i></i> Quantitative
                </button>
                <button class="category-btn">
                    <i></i> Qualitative
                </button>
                <button class="category-btn">
                    <i></i> Experimental
                </button>
                <button class="category-btn">
                    <i></i> Longitudinal
                </button>
                <button class="category-btn">
                    <i></i> Case Study
                </button>
                <button class="category-btn">
                    <i></i> Ethnographic
                </button>
                <button class="category-btn">
                    <i></i> Action Research
                </button>
                <button class="category-btn">
                    <i></i> Meta-Analysis
                </button>
                <button class="category-btn">
                    <i></i> Cross-Sectional
                </button>
                <button class="category-btn">
                    <i></i> Correlational
                </button>
                <button class="category-btn">
                    <i></i> Mixed-Methods
                </button>
            </div>

            <!-- Other Research Links -->
            <section class="other-researches">
                <h2>Other Research Areas</h2>
                <ul>
                <?php
// Assuming connection and SQL query setup is done above

// Loop through the results and generate the list items
if ($result->num_rows > 0) {
    // Loop through each keyword
    while($row = $result->fetch_assoc()) {
        // Clean the keyword to remove unwanted characters like \r\n
        $keyword = trim($row["keyword"]); // Removes leading/trailing spaces, newlines, and carriage returns
        $keyword = str_replace(array("\r", "\n"), '', $keyword); // Removes any \r or \n

        // Generate a link to search.php with the sanitized keyword
        echo "<li><a href='search.php?search=" . urlencode($keyword) . "'>" . htmlspecialchars($keyword) . "</a></li>";
    }
} else {
    echo "<li>No keywords found</li>";
}
?>

            </ul>
            </section>
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

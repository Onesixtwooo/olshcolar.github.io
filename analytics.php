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


// SQL query
$sql = "SELECT word, COUNT(*) AS frequency
        FROM (
            SELECT TRIM(BOTH ' ' FROM REGEXP_REPLACE(TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(keywords, ' ', n.n), ' ', -1)), '[[:punct:]]', '')) AS word
            FROM research
            CROSS JOIN (SELECT @rownum := @rownum + 1 AS n FROM 
                        (SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION 
                         SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION 
                         SELECT 9 UNION SELECT 10) n1,
                         (SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION 
                          SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION 
                          SELECT 9 UNION SELECT 10) n2,
                         (SELECT @rownum := 0) r) n
            WHERE CHAR_LENGTH(keywords) - CHAR_LENGTH(REPLACE(keywords, ' ', '')) >= n.n - 1
        ) AS words
        WHERE word != ''
        GROUP BY word
        ORDER BY frequency DESC
        LIMIT 10;";

// Execute query
$result = $conn->query($sql);

// Query to get distinct categories
$sql_categories = "SELECT category, COUNT(*) AS count FROM research GROUP BY category ORDER BY count DESC;";

// Execute the query for categories with count
$result_categories = $conn->query($sql_categories);

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholar Search</title>
    <style>
        :root {
            --color-primary: #800000;
            --color-primary-dark: #600000;
            --color-background: #f8f9fa;
            --color-text: #333;
            --color-text-light: #fff;
            --color-link: #1a0dab;
            --color-border: #dfe1e5;
        }
        body {
            font-family: Arial, sans-serif;
         
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            background-color: var(--color-background);
            color: var(--color-text);
        }
        .header {
    background-color: var(--color-primary);
    color: var(--color-text-light);
    padding: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    position: fixed;
    width: 90%;
    top: 0;
    z-index: 1000;
    gap: 10px; /* Add gap between elements */
}

.header-left {
    display: flex;
    align-items: center;
    gap: 10px; /* Ensure space between logo and toggle button */
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
        
.login-button {
    background-color: var(--color-text-light);
    color: var(--color-primary);
    border: none;
    padding: 8px 10px;
    font-size: 12px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
    margin-left: auto; /* Keep login button pushed to the right */
    margin-right:30px;
}

/* Make sure that the login button and toggle button don't overlap on smaller screens */
@media (max-width: 768px) {
  

    .header-left {
        align-self: flex-start; /* Ensure the logo and toggle button stay on the left */
    }
        
}
        .login-button:hover {
            background-color: var(--color-background);
        }
        .main-content {
        
            display: flex;
           
           
            padding: 20px;
        }
        .container {
           
            width: 100%;
          
            
        }
   
       
        .footer {
    background-color: var(--color-primary);
    color: var(--color-text-light);
    padding: 8px;
    text-align: center;
    display: flex; /* Add flexbox */
    justify-content: flex-end; /* Align items to the right */
    align-items: center;
}
.footer a {
    color: var(--color-text-light);
    text-decoration: none;
    margin: 0 15px;
    display: inline-block;
}
.footer a:hover {
    text-decoration: underline;
}


        @media (max-width: 768px) {
           
            .search-input {
                font-size: 12px;
            }
            .links {
                flex-direction: column;
                align-items: center;
            }
            .links a {
                margin: 5px 0;
            }
            .footer {
                padding: 10px;
            }
            .footer a {
                margin: 5px;
            }
        }


.toggle-sidebar {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
        }
        .controls {
    margin-top: 10px;
}

.header {
            background-color: var(--color-primary);
            color: var(--color-text-light);
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .main-content {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 80px 20px 20px; /* Add padding to accommodate the fixed header */
        }

        /* Sidebar styles */
        .sidebar {
            background-color: var(--color-primary-dark);
            color: var(--color-text-light);
            width: 250px;
            position: fixed;
            top: 60px; /* Start the sidebar below the header */
            left: -250px; /* Initially hidden off-screen */
            height: calc(100vh - 60px); /* Full height, minus the header height */
            padding: 20px;
            box-sizing: border-box;
            z-index: 900;
            transition: left 0.3s ease; /* Smooth animation for sliding */
            overflow-y: auto; /* Allow scrolling if content is taller than the viewport */
        }
        .sidebar.active {
            left: 0; /* Bring the sidebar into view when active */
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: var(--color-text-light);
            text-decoration: none;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        .sidebar a:hover {
            background-color: var(--color-primary);
        }

        /* For mobile screens */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%; /* Sidebar takes full width on mobile */
                left: -100%; /* Initially hidden off-screen */
            }
            .sidebar.active {
                left: 0; /* Slide in the sidebar on mobile */
            }
        }
        .logo img {
    width: 100%; /* Ensures the image takes up the full width of its container */
    max-width: 500px; /* Limits the maximum size of the image */
    height: auto; /* Maintains the aspect ratio */
}

@media (max-width: 768px) {
    .logo img {
        max-width: 300px; /* Smaller size for mobile screens */
    }
}

@media (max-width: 480px) {
    .logo img {
        max-width: 350px; /* Even smaller size for very narrow screens */
    }
}
.section {
            margin-bottom: 40px;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 5px;
        }

        .icon {
            width: 24px;
            height: 24px;
            background-color: #FFB800;
            display: inline-block;
        }

        h2 {
            color: #333;
            margin: 0;
            font-size: 1.2rem;
        }

        .subtitle {
            color: #666;
            margin: 0 0 20px 0;
            font-size: 0.9rem;
        }

        .learn-more {
            color: #666;
            text-decoration: none;
            margin-left: 5px;
        }

        .learn-more:hover {
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            text-align: left;
            padding: 10px;
            border-bottom: 2px solid #eee;
            color: #800000;
            font-weight: normal;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .publication-name, .research-area {
            color: #800000;
            text-decoration: none;
        }

        .publication-name:hover, .research-area:hover {
            text-decoration: underline;
        }

        .metric {
            color: #800000;
        }

        #most-cited-table {
            background-color: #f0f0f0;
            border: 2px solid #800000;  /* Example: add a red border to the Most Cited Publications table */
}

#research-areas-table {
    background-color: #f0f0f0;
    border: 2px solid #800000; /* Example: add a background color to the Research Areas table */
}

    </style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <button id="toggle-button" class="toggle-sidebar" aria-label="Toggle-button">☰</button>
            <img src="images/olshcolar.png" class="header-logo" alt="Olshcolar Logo" onclick="window.location.href='index.html';" >
        </div>
        <button class="login-button">Login</button>
    </header>

    <aside class="sidebar" id="sidebar">
    <a href="search.php"><i class="fas solid fa-magnifying-glass"></i> Search</a>
    <a href="myaccount.php"><i class="fas fa-user"></i> My Profile</a>
    
    <a href="category.php"><i class="fa-solid fa-layer-group"></i> Categories</a>
    <a href="analytics.php"><i class="fas fa-chart-line"></i> Analytics</a>
    <a href="mylib.php"><i class="fas fa-book"></i> My Library</a>
    </aside>
    <main class="main-content">
        <div class="container">
        <section class="section">
            <div class="section-header">
                <span class="icon"></span>
                <h2>Top publications</h2>
            </div>
            <p class="subtitle">
               Most Used Researched Types all over the Records.
           
            </p>

            <table id="most-researched-table">
        <thead>
            <tr>
                <th style="width: 5%"></th>
                <th style="width: 65%">Category</th>
                <th style="width: 15%">Count</th>
                
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if there are categories available
            if ($result_categories->num_rows > 0) {
                $rank = 1;
                // Output each category and its count
                while($row = $result_categories->fetch_assoc()) {
                    // Encode the category value to handle spaces and special characters
                    $category_value = urlencode($row["category"]);
                    echo "<tr>";
                    echo "<td>" . $rank . ".</td>";
                    echo "<td><a href='search.php?search=" . $category_value . "' class='research-area'>" . $row["category"] . "</a></td>";
                    echo "<td class='metric'>" . $row["count"] . "</td>"; // Research Count
              
                    echo "</tr>";
                    $rank++;
                }
            } else {
                echo "<tr><td colspan='4'>No categories found</td></tr>";
            }
            ?>
        </tbody>
    </table>
        </section>

        <section class="section">
            <div class="section-header">
                <span class="icon"></span>
                <h2>Top research areas</h2>
            </div>
            <p class="subtitle">
                Most researched areas over all the records.
           
            </p>

            <table id="research-areas-table">
        <thead>
            <tr>
                <th style="width: 5%"></th>
                <th style="width: 65%">Research Area</th>
                <th style="width: 15%">Count</th>
             
            </tr>
        </thead>
        <tbody>
    <?php
    // Check if there are results
    if ($result->num_rows > 0) {
        $rank = 1;
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            $search_value = urlencode($row["word"]); // URL encode the word for use in the query string
            echo "<tr>";
            echo "<td>" . $rank . ".</td>";
            echo "<td><a href='search.php?search=" . $search_value . "' class='research-area'>" . $row["word"] . "</a></td>";
            echo "<td class='metric'>" . $row["frequency"] . "</td>";
            echo "</tr>";
            $rank++;
        }
    } else {
        echo "<tr><td colspan='4'>No data available</td></tr>";
    }
    ?>
</tbody>

    </table>
        </section>



        </div>
         
    </main>

</body>

<script>

const toggleButton = document.getElementById('toggle-button');
        const sidebar = document.getElementById('sidebar');

        toggleButton.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    function submitSearch() {
        const query = document.querySelector('.search-input').value;
        if (query) {
            window.location.href = 'search.php?q=' + encodeURIComponent(query);
        }
    }
</script>
</html>
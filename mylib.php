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
$sql = "SELECT Title, Author, Category, bookmarkedDate FROM bookmarked  ORDER BY bookmarkedDate DESC  ";
$result = $conn->query($sql);
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
       
            <section class="research-section">
                <h2>Saved Articles</h2>
                <ul class="research-list">
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<li>";
                            echo "<a href='#'>" . htmlspecialchars($row['Title']) . "</a><br>";
                            echo "<small>Author: " . htmlspecialchars($row['Author']) . " | Category: " . htmlspecialchars($row['Category']) . " | Bookmarked on: " . htmlspecialchars($row['bookmarkedDate']) . "</small>";
                            echo "</li>";
                        }
                    } else {
                        // Show message when no bookmarks are found
                        echo "<li>No saved articles yet.</li>";
                        echo "<p>You haven't saved any articles yet. To save articles:</p>";
                        echo "<ol>";
                        echo "<li>Go to the <a href='search.php'>search page</a>.</li>";
                        echo "<li>Find an article you're interested in.</li>";
                        echo "<li>Click the <strong>'Save'</strong> button next to the article title.</li>";
                        echo "</ol>";
                    }
                    ?>
                </ul>
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
<?php
// Database connection
$host = 'localhost';
$db = 'olshcoslms'; // Change this to your database name
$user = 'root';     // Change this to your MySQL username
$pass = '';         // Change this to your MySQL password

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$results_per_page = 10; // Number of results per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
$offset = ($page - 1) * $results_per_page; // Calculate offset for SQL query

// Initialize search variable
$search_query = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Fetch total number of records based on search
if (!empty($search_query)) {
    $total_sql = "SELECT COUNT(*) as total FROM research WHERE 
                  title LIKE '%$search_query%' 
                  OR author LIKE '%$search_query%' 
                  OR keywords LIKE '%$search_query%' 
                  OR category LIKE '%$search_query%'";
} else {
    $total_sql = "SELECT COUNT(*) as total FROM research";
}

$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];

// Calculate total pages
$total_pages = ceil($total_records / $results_per_page);

// Fetch research data based on search
if (!empty($search_query)) {
    $sql = "SELECT researchid, title, author, datepublication, keywords FROM research 
            WHERE title LIKE '%$search_query%' 
            OR author LIKE '%$search_query%' 
            OR keywords LIKE '%$search_query%' 
            OR category LIKE '%$search_query%' 
            or researchid LIKE '%$search_query%'
            LIMIT $results_per_page OFFSET $offset";
} else {
    $sql = "SELECT researchid,title, author, datepublication, keywords FROM research LIMIT $results_per_page OFFSET $offset";
}

$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OLSHCO: Discover Research</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            justify-content: space-between;
            gap: 1rem;
        }
        .logo {
            cursor: pointer;
            width: auto;
            height: 30px;
            overflow: hidden;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
        }
        .container {
            display: flex;
            flex: 1;
            transition: all 0.3s ease;
        }
        .sidebar {
            width: 200px;
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

        .search-container {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
          
        }
        .search-container form {
    display: flex; /* Flexbox layout to align input and button */
    width: 100%; /* Ensure the form takes full width */
}
       .search-input {
    flex: 1; /* This ensures the input takes up all available space */
    padding: 12px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 100%; /* Ensures the input expands to fill its container */
}


        .search-button {
            padding: 12px 24px;
            background-color: #8B0000;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .results-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .results-count {
            color: #595959;
        }

        /* Dropdown container for sorting and filter */
        .filter-dropdown {
            position: relative;
            display: inline-block;
        }

        .filter-button {
            background-color: #f9f9f9;
            color: #333;
            padding: 10px 20px;
            border: 1px solid #ccc;
            cursor: pointer;
            font-size: 16px;
        }

        .filter-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 200px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
            z-index: 1;
            border: 1px solid #ddd;
            padding: 15px;
        }

        .filter-content input[type="range"] {
            width: 100%;
        }

        .filter-content label {
            display: block;
            margin-bottom: 8px;
            color: #666;
        }

        .filter-dropdown:hover .filter-content {
            display: block;
        }

        /* Sort Options with borders */
        .sort-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            margin-top: 10px;
        }

        .sort-options button {
            background-color: white;
            border: 1px solid #ddd;
            padding: 8px;
            cursor: pointer;
            font-size: 14px;
            text-align: left;
        }

        .sort-options button:hover {
            background-color: #f0f0f0;
        }

        .search-results {
            list-style: none;
            padding: 0;
        }

        .result-item {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .result-title {
            color: #8B0000;
            font-size: 18px;
            margin-bottom: 8px;
            text-decoration: none;
        }

        .result-title:hover {
            text-decoration: underline;
        }

        .result-meta {
            color: #666;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .keywords {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.keyword {
    background-color: #f0f0f0;
    padding: 6px 12px;
    border-radius: 16px;
    font-size: 14px;
    color: #666;
    border: 1px solid transparent;
    transition: background-color 0.3s ease, border 0.3s ease;
    cursor: pointer;
}

.keyword:hover {
    background-color: #8B0000;
    color: white;
    border: 1px solid #8B0000;
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

.cite-link, .save-link {
    color: #8B0000;
    text-decoration: none;
    font-size: 14px;
    margin-right: 20px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 8px 16px;
    border: 1px solid transparent;
    border-radius: 4px;
    transition: background-color 0.3s ease, border-color 0.3s ease;
}

.cite-link:hover, .save-link:hover {
    background-color: #a20000;
    color: white;
    border-color: #a20000;
}

.cite-link i, .save-link i {
    margin-right: 5px;
}
.pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .pagination-link {
            padding: 8px 12px;
            border: 1px solid #ccc;
            background-color: #f0f0f0;
            color: #333;
            text-decoration: none;
            font-size: 14px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .pagination-link:hover {
            background-color: #ddd;
        }
        .pagination-link.active {
            background-color: #8B0000;
            color: white;
            border-color: #8B0000;
        }

        .refine-results {
    margin-bottom: 20px;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.refine-results h2 {
    font-size: 1.5rem;
    color: #8B0000;
    margin-bottom: 15px;
}

.filter-options {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    width:auto;
}

.filter-group h3 {
    font-size: 1.2rem;
    color: #333;
    margin-bottom: 10px;
}

.filter-group ul {
    list-style: none;
    padding: 0;
}

.filter-group ul li {
    margin-bottom: 8px;
}

.filter-group ul li label {
    font-size: 14px;
    color: #595959;
}

@media (max-width: 768px) {
    .filter-options {
        flex-direction: column;
        width:50%;
    }
}

/* For small screens, move refine-results below pagination */
@media (max-width: 600px) {
    .refine-results {
        margin-top: 20px;
        order: 2; /* Ensure it appears below pagination */
    }

    .pagination {
        order: 1; /* Ensure pagination comes first */
    }
}
.login-button {
    background-color: #007BFF;
    color: white;
    border: none;
    padding: 6px 10px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
}

.login-button:hover {
    background-color: #0056b3;
}

/* Modal styles */
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Black with opacity */
}

.modal-content {
    background-color: white;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%; /* Could be more or less, depending on screen size */
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

.modal-overlay {
    display: none; /* Hidden by default */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.3);
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
        <button class="login-button">Login</button>
    </header>

    <div class="container">
        <aside class="sidebar closed">
            <nav>
            <ul class="sidebar-menu">
            <li><a href="search.php"><i class="fas solid fa-magnifying-glass"></i> Search</a></li>
    <li><a href="myaccount.php"><i class="fas fa-user"></i> My Profile</a></li>
    
    <li><a href="category.php"><i class="fa-solid fa-layer-group"></i> Categories</a></li>
    <li><a href="analytics.php"><i class="fas fa-chart-line"></i> Analytics</a></li>
    <li><a href="mylib.php"><i class="fas fa-book"></i> My Library</a></li>
</ul>

                   
            </nav>
        </aside>

        <main class="main-content">
        <div class="search-container">
                <form method="GET" action="">
                    <input type="text" name="search" class="search-input" placeholder="Search for scholarly articles..." aria-label="Search for scholarly articles" value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit" class="search-button" aria-label="Perform search">Search</button>
                </form>
            </div>

        <div class="refine-results">
    <h2>Refine Results</h2>
    <div class="filter-options">
        <div class="filter-group">
            <h3>Resource Type</h3>
            <ul>
                <li><input type="checkbox" id="article" name="resource_type" value="article">
                    <label for="article">Article</label>
                </li>
                <li><input type="checkbox" id="book" name="resource_type" value="book">
                    <label for="book">Book</label>
                </li>
                <li><input type="checkbox" id="thesis" name="resource_type" value="thesis">
                    <label for="thesis">Thesis</label>
                </li>
            </ul>
        </div>
        <div class="filter-group">
            <h3>Language</h3>
            <ul>
                <li><input type="checkbox" id="english" name="language" value="english">
                    <label for="english">English</label>
                </li>
                <li><input type="checkbox" id="filipino" name="language" value="filipino">
                    <label for="filipino">Filipino</label>
                </li>
               
            </ul>
        </div>
        <div class="filter-group">
            <h3>Subject</h3>
            <ul>
                <li><input type="checkbox" id="science" name="subject" value="science">
                    <label for="science">Science</label>
                </li>
                <li><input type="checkbox" id="arts" name="subject" value="arts">
                    <label for="arts">Arts</label>
                </li>
                <li><input type="checkbox" id="technology" name="subject" value="technology">
                    <label for="technology">Technology</label>
                </li>
            </ul>
        </div>
        <div class="filter-group">
            <h3>Year Level</h3>
            <ul>
                <li><input type="checkbox" id="first-year" name="year_level" value="1st year">
                    <label for="first-year">1st Year</label>
                </li>
                <li><input type="checkbox" id="second-year" name="year_level" value="2nd year">
                    <label for="second-year">2nd Year</label>
                </li>
                <li><input type="checkbox" id="third-year" name="year_level" value="3rd year">
                    <label for="third-year">3rd Year</label>
                </li>
            </ul>
        </div>
    </div>
</div>

            

            <div class="results-controls" style="border-bottom: 3px solid #ddd; padding-bottom: 10px;">
                <div class="results-count">
                <?php echo "About $total_records results"; ?>
                </div>
                <div class="filter-dropdown">
                    <button class="filter-button">Filter & Sort</button>
                    <div class="filter-content">
                        <label for="year-range">Year Published:</label>
                        <input type="range" id="year-range" name="year" min="2000" max="2024" value="2024" oninput="this.nextElementSibling.value = this.value">
                        <output>2024</output>

                        <div class="sort-options">
                            <label>Sort By:</label>
                            <button>Relevance</button>
                            <button>Date</button>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="search-results">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $keywords = explode(",", $row['keywords']); // Assuming keywords are comma-separated
                        echo '<li class="result-item">';
                        echo '<a href="details.php?researchid=' . urlencode($row['researchid']) . '" class="result-title">' . htmlspecialchars($row['title']) . '</a>';

                        echo '<div class="result-meta">Author: ' . htmlspecialchars($row['author']) . ' | Published: ' . htmlspecialchars($row['datepublication']) . '</div>';
                        echo '<div class="keywords">';
                        foreach ($keywords as $keyword) {
                            echo '<span class="keyword">' . htmlspecialchars(trim($keyword)) . '</span>';
                        }
                        echo '</div>';
                        echo '<div class="controls">';
                        echo '<a href="#" class="cite-link" data-title="' . htmlspecialchars($row['title']) . '" data-author="' . htmlspecialchars($row['author']) . '" data-date="' . htmlspecialchars($row['datepublication']) . '"><i class="fas fa-quote-right"></i> Cite</a>';

                        echo '<a href="#" class="save-link"><i class="fas fa-bookmark"></i> Save</a>';
                        echo '</div>';
                        echo '</li>';
                    }
                } else {
                    echo "<li>No research found</li>";
                }
                ?>
            </ul>

            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?search=<?php echo urlencode($search_query); ?>&page=1" class="pagination-link">First</a>
                    <a href="?search=<?php echo urlencode($search_query); ?>&page=<?php echo $page - 1; ?>" class="pagination-link">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?search=<?php echo urlencode($search_query); ?>&page=<?php echo $i; ?>" class="pagination-link <?php if ($i == $page) echo 'active'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="?search=<?php echo urlencode($search_query); ?>&page=<?php echo $page + 1; ?>" class="pagination-link">Next</a>
                    <a href="?search=<?php echo urlencode($search_query); ?>&page=<?php echo $total_pages; ?>" class="pagination-link">Last</a>
                <?php endif; ?>
            </div>
        </main>
    </div>
<!-- Modal for Citation -->
<div id="cite-modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeCiteModal()">&times;</span>
        <h3>Cite in Different Formats</h3>
        <div>
            <h4>MLA Format:</h4>
            <p id="mla-format"></p>
        </div>
        <div>
            <h4>APA Format:</h4>
            <p id="apa-format"></p>
        </div>
    </div>
</div>
<div id="modal-overlay" class="modal-overlay"></div>


    <script>
     


document.getElementById("toggle-button").addEventListener("click", function() {
        const sidebar = document.querySelector(".sidebar");
        sidebar.classList.toggle("closed");
    });


// Add event listeners for keyword clicks
document.querySelectorAll('.keyword').forEach(function(keywordElement) {
    keywordElement.addEventListener('click', function() {
        // Get the clicked keyword
        const keyword = this.textContent.trim();

        // Get the search input field
        const searchInput = document.querySelector('.search-input');

        // Clear the search input field before appending the keyword
        searchInput.value = '';

        // Set the search input to the clicked keyword
        searchInput.value = keyword;

        // Submit the form automatically after setting the keyword
        searchInput.form.submit();
    });
});

function openCiteModal(title, author, date) {
    document.getElementById('cite-modal').style.display = 'block';
    document.getElementById('mla-format').textContent = ` ${author}. ${title}. ${date}.`;
    document.getElementById('apa-format').textContent = ` ${author}. (${date}). ${title}.`;
    document.getElementById('modal-overlay').style.display = 'block';
}

function closeCiteModal() {
    document.getElementById('cite-modal').style.display = 'none';
    document.getElementById('modal-overlay').style.display = 'none';
}

// Add this listener to your cite link buttons
document.querySelectorAll('.cite-link').forEach(function(citeLink) {
    citeLink.addEventListener('click', function() {
        const title = citeLink.getAttribute('data-title');
        const author = citeLink.getAttribute('data-author');
        const date = citeLink.getAttribute('data-date');
        openCiteModal(title, author, date);
    });
});

// Close modal when clicking on the overlay
document.getElementById('modal-overlay').addEventListener('click', closeCiteModal);


    </script>
</body>
</html>

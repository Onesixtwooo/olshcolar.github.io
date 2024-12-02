<?php
// Database connection
$servername = "localhost"; // adjust with your DB details
$username = "root";
$password = "";
$dbname = "olshcoslms";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $researchid = $_POST['researchid'];

    // Query to fetch research data based on researchid
    $sql = "SELECT title, author, keywords, abstract, category FROM research WHERE researchid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $researchid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Return research details as JSON
        echo json_encode([
            'success' => true,
            'data' => [
                'title' => $row['title'],
                'author' => $row['author'],
                'keywords' => $row['keywords'],
                'abstract' => $row['abstract'],
                'category' => $row['category']
            ]
        ]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
}

$conn->close();
?>

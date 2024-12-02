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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $researchId = $_POST['researchId'];
    $title = $_POST['title'];
    $authors = $_POST['authors'];
    $keywords = $_POST['keywords'];
    $datePublication = $_POST['datePublication'];
    $category = $_POST['type'];  // 'type' from the select dropdown
    $abstract = $_POST['abstract'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO research (ResearchID, Title, Author, Keywords, Category, datePublication, Abstract) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $researchId, $title, $authors, $keywords, $category, $datePublication, $abstract);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>

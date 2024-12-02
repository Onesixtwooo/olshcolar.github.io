<?php
if (isset($_POST['research_id'])) {
    $researchID = $_POST['research_id'];

    // Database connection
    $conn = new mysqli("localhost", "username", "password", "olshcoslms");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch research data
    $sql = "SELECT title, authors, keywords, category, abstract FROM research WHERE researchID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $researchID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'title' => $row['title'],
            'authors' => $row['authors'],
            'keywords' => $row['keywords'],
            'category' => $row['category'],
            'abstract' => $row['abstract']
        ]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
    $conn->close();
}
?>

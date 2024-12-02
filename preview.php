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

$research_id = isset($_GET['researchid']) ? $_GET['researchid'] : null;

// Fetch PDF BLOB from the database
$sql = "SELECT SampleFile FROM researchfile WHERE ResearchID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $research_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($pdf_blob);

if ($stmt->num_rows > 0) {
    $stmt->fetch();
    
    // Set headers to serve PDF inline
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="document.pdf"');
    header('Content-Transfer-Encoding: binary');
    
    // Output the PDF content
    echo $pdf_blob;
    exit();
} else {
    echo "No PDF found for the given Research ID.";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview PDF</title>
    <style>
        body {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
        }
        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
    </style>
    <script>
        window.onload = function() {
            document.oncontextmenu = function() { return false; }; // Disable right-click
            window.print = function() { return false; }; // Disable print
        }
    </script>
</head>
<body>
    <iframe src="data:application/pdf;base64,<?php
        // Fetch and encode PDF data for inline display
        $research_id = isset($_GET['researchid']) ? $_GET['researchid'] : null;
        $sql = "SELECT SampleFile FROM researchfile WHERE ResearchID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $research_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($pdf_blob);
        
        if ($stmt->num_rows > 0) {
            $stmt->fetch();
            // Encode the BLOB data to base64
            echo base64_encode($pdf_blob);
        } else {
            echo ''; // Handle case when no PDF is found
        }
        $stmt->close();
        $conn->close();
    ?>" allowfullscreen></iframe>
</body>
</html>

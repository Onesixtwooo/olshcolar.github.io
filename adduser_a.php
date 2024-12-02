<?php
// adduser_a.php
// Database connection (adjust with your DB credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "olshcoslms"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gather personal details data from the form
    $userId = $_POST['userId'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $gradeLevel = $_POST['gradeLevel'];
    $department = $_POST['department'];
    $block = $_POST['block'];

    // Gather address data from the form
    $houseNo = $_POST['houseNo'];
    $street = $_POST['street'];
    $barangay = $_POST['barangay'];
    $townCity = $_POST['townCity'];
    $province = $_POST['province'];


    // Check if userId or email already exists in userdetails
    $sql_check = "SELECT userid FROM userdetails WHERE userid = ? OR email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $userId, $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // User ID or email already exists
        echo "<script>
                alert('User ID or email already exists.');
                window.location.href = 'adduser.php';
              </script>";
    } else {
        // Insert into userdetails table
        $sql_personal = "INSERT INTO userdetails (userid, fname, midname, lname, gender, dateofbirth, phoneno, email, yearlevel, department, block)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_personal = $conn->prepare($sql_personal);
        $stmt_personal->bind_param("sssssssssss", $userId, $firstName, $middleName, $lastName, $gender, $dob, $phone, $email, $gradeLevel, $department, $block);

        if ($stmt_personal->execute()) {
            // Insert address details
            $sql_address = "INSERT INTO useraddress (userid, houseno, street, barangay, citymunicipality, province)
                            VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_address = $conn->prepare($sql_address);
            $stmt_address->bind_param("ssssss", $userId, $houseNo, $street, $barangay, $townCity, $province);

            if ($stmt_address->execute()) {
                echo "<script>
                        alert('User and address details added successfully.');
                        window.location.href = 'adduser.php';
                      </script>";
            } else {
                echo "Error: Could not save address details. " . $stmt_address->error;
            }
            $stmt_address->close();
        } else {
            echo "Error: Could not save user details. " . $stmt_personal->error;
        }
        $stmt_personal->close();
    }
    $stmt_check->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Account - Sacred Heart College</title>
    <style>
        :root {
            --primary: #8b0000;
            --primary-dark: #6a0000;
            --secondary: #1a365d;
            --background: #f8fafc;
            --text: #1a1a1a;
            --border: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: var(--background);
            color: var(--text);
            line-height: 1.5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .back-link {
            display: inline-block;
            color: var(--primary);
            text-decoration: none;
            margin-bottom: 2rem;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .logo {
            width: 120px;
            height: 120px;
            margin-bottom: 1.5rem;
        }

        .title {
            color: var(--primary);
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .form-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .section-title {
            color: var(--secondary);
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--border);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        input, select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: 4px;
            font-size: 1rem;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(139, 0, 0, 0.1);
        }

        .submit-button {
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 4px;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            max-width: 200px;
            margin: 2rem auto 0;
            display: block;
            transition: background-color 0.2s;
        }

        .submit-button:hover {
            background-color: var(--primary-dark);
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .form-container {
                padding: 1.5rem;
            }

            .title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="adduser.php" class="back-link">← Back</a>
        
        <div class="header">
            <img src="logo.png?height=120&width=120" alt="Sacred Heart College Logo" class="logo">
            <h1 class="title">Add Account</h1>
        </div>

       <form class="form-container" action="adduser_a.php" method="post">

            <div class="form-grid">
                <div class="form-section">
                    <h2 class="section-title">Personal Details</h2>
                    <div class="form-group">
                        <label for="userId">User ID</label>
                        <input type="text" id="userId" name="userId">
                    </div>
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName" required>
                    </div>
                    <div class="form-group">
                        <label for="middleName">Middle Name</label>
                        <input type="text" id="middleName" name="middleName">
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth (yyyy-mm-dd)</label>
                        <input type="date" id="dob" name="dob" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" required>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-section">
                    <h2 class="section-title">Address</h2>
                    <div class="form-group">
                        <label for="houseNo">House No.</label>
                        <input type="text" id="houseNo" name="houseNo" required>
                    </div>
                    <div class="form-group">
                        <label for="street">Street</label>
                        <input type="text" id="street" name="street" required>
                    </div>
                    <div class="form-group">
                        <label for="barangay">Barangay</label>
                        <input type="text" id="barangay" name="barangay" required>
                    </div>
                    <div class="form-group">
                        <label for="townCity">Town/City</label>
                        <input type="text" id="townCity" name="townCity" required>
                    </div>
                    <div class="form-group">
                        <label for="province">Province</label>
                        <input type="text" id="province" name="province" required>
                    </div>
                </div>

                <div class="form-section">
                    <h2 class="section-title">Year-Level</h2>
                    <div class="form-group">
                        <label for="gradeLevel">Grade/Year Level</label>
                        <select id="gradeLevel" name="gradeLevel" required>
                            <option value="Grade 7">Grade 7</option>
                            <option value="Grade 8">Grade 8</option>
                            <option value="Grade 9">Grade 9</option>
                            <option value="Grade 10">Grade 10</option>
                            <option value="Grade 11">Grade 11</option>
                            <option value="Grade 12">Grade 12</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                            <option value="5th Year">5th Year</option>
                            <option value="Faculty">Faculty</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="department">Department</label>
                        <select id="department" name="department" required>
                            <option value="Junior High">Junior High</option>
                            <option value="Senior High">Senior High</option>
                            <option value="EDUC">EDUC</option>
                            <option value="CRIM">CRIM</option>
                            <option value="BSIT">BSIT</option>
                            <option value="BSOAD">BSOAD</option>
                            <option value="BSHM">BSHM</option>
                            <option value="TAMS">TAMS</option>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="block">Block/Section</label>
                        <select id="block" name="block" required>
                            <option value="St. John">St John</option>
                            <option value="St. Peter">St Peter</option>
                            <option value="St. Matthew">St Matthew</option>
                            <option value="St. Mark">St Mark</option>
                            <option value="Block A">Block A</option>
                            <option value="Block B">Block B</option>
                            <option value="Block C">Block C</option>
                            <option value="Block D">Block D</option>
                            <option value="Block E">Block E</option>
                            <option value=""></option>

                        </select>
                    </div>
                 
                </div>
            </div>

            <button type="submit" class="submit-button">Add User</button>
        </form>
    </div>

</body>
</html>
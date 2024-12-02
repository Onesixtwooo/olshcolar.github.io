<?php
// Start the session
session_start();

// Database connection details
$servername = "localhost";
$username = "root"; // change if necessary
$password = ""; // change if necessary
$dbname = "olshcoslms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize error message
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user ID and password from form
    $user_id = $conn->real_escape_string($_POST['user_id']);
    $input_password = $_POST['password']; // Store the input password

    // Query to get the user record
    $sql = "SELECT * FROM userdetails WHERE userid = '$user_id'";
    $result = $conn->query($sql);

    // Check if a user was found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the password using password_verify()
        if (password_verify($input_password, $row['password'])) {
         
          

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect, set error message
            $error = "Invalid User ID or Password.";
        }
    } else {
        // User not found, set error message
        $error = "Invalid User ID or Password.";
    }
}

// Close connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }
        .login-container h2 {
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 93%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .btn {
            width: 100%;
            background-color: maroon;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: darkred;
        }
        .logo {
            max-width: 100px;
            margin-bottom: 20px;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .forgot-password {
            display: block;
            margin-top: 10px;
            text-decoration: none;
            color: maroon;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="logo.png" alt="Logo" class="logo">
        <h2>Login</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="user_id">User ID:</label>
                <input type="text" id="user_id" name="user_id" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
            <a href="forgot_password.php" class="forgot-password">Forgot Password?</a>
        </form>
    </div>
</body>
</html>

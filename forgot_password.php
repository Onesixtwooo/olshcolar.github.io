<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
        .recovery-container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px; /* Fixed width for the container */
            text-align: center;
        }
        .recovery-container h2 {
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
            width: 93%; /* Full width for input fields */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .btn {
            width: 100%; /* Full width for button */
            background-color: maroon; /* Maroon color */
            color: white;
            padding: 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: darkred; /* Darker maroon on hover */
        }
    </style>
</head>
<body>
    <div class="recovery-container">
        <h2>Forgot Password</h2>
        <form method="POST" action="process_recovery.php"> <!-- Form action to handle recovery -->
            <div class="form-group">
                <label for="school_id">Enter your School ID:</label>
                <input type="text" id="school_id" name="school_id" required>
            </div>
            <button type="submit" class="btn">Submit</button>
        </form>
        <p><a href="login.php" style="text-decoration: none; color: maroon;">Back to Login</a></p>
    </div>
</body>
</html>

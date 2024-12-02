<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 768px;
            padding: 20px;
        }
        .card-header {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .close-button {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #800000;
        }
        .close-button:hover {
            color: #600000;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .radio-group {
            display: flex;
            gap: 20px;
        }
        .radio-group label {
            font-weight: normal;
        }
        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #800000;
            color: white;
        }
        .btn-primary:hover {
            background-color: #600000;
        }
        .btn-outline {
            background-color: white;
            color: #800000;
            border: 1px solid #800000;
        }
        .btn-outline:hover {
            background-color: #800000;
            color: white;
        }
        .grid {
            display: grid;
            gap: 20px;
        }
        @media (min-width: 768px) {
            .grid-2 {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .separator {
            border: none;
            border-top: 1px solid #e0e0e0;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h1 class="card-title">Edit Student</h1>
            <button class="close-button" aria-label="Close"  onclick="window.location.href='adduser.php'">&times;</button>
        </div>
        <form>
            <div class="form-group">
                <label for="userId">User ID</label>
                <div style="display: flex; gap: 10px;">
                    <input type="text" id="userId" placeholder="Enter user ID" style="flex-grow: 1;">
                    <button type="button" class="btn btn-primary">Search</button>
                </div>
            </div>

            <hr class="separator">

            <h2 class="section-title">Personal Details</h2>
            <div class="grid grid-2">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" placeholder="Enter first name">
                </div>
                <div class="form-group">
                    <label for="middleName">Middle Name</label>
                    <input type="text" id="middleName" placeholder="Enter middle name">
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" placeholder="Enter last name">
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <div class="radio-group">
                        <label>
                            <input type="radio" name="gender" value="male" checked> Male
                        </label>
                        <label>
                            <input type="radio" name="gender" value="female"> Female
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="birthdate">Birthdate</label>
                    <input type="date" id="birthdate">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" placeholder="Enter email address">
                </div>
                <div class="form-group">
                    <label for="phoneNumber">Phone Number</label>
                    <input type="tel" id="phoneNumber" placeholder="Enter phone number">
                </div>
            </div>

            <hr class="separator">

            <h2 class="section-title">Department Information</h2>
            <div class="grid grid-2">
                <div class="form-group">
                    <label for="yearLevel">Year Level</label>
                    <select id="yearLevel">
                        <option value="">Select year level</option>
                        <option value="1">First Year</option>
                        <option value="2">Second Year</option>
                        <option value="3">Third Year</option>
                        <option value="4">Fourth Year</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="department">Department</label>
                    <select id="department">
                        <option value="">Select department</option>
                        <option value="cs">Computer Science</option>
                        <option value="it">Information Technology</option>
                        <option value="is">Information Systems</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="section">Block/Section</label>
                    <input type="text" id="section" placeholder="Enter block/section">
                </div>
            </div>

            <hr class="separator">

            <h2 class="section-title">Address</h2>
            <div class="grid">
                <div class="form-group">
                    <label for="streetBuildingNo">Street/Building No.</label>
                    <input type="text" id="streetBuildingNo" placeholder="Enter street/building no.">
                </div>
                <div class="form-group">
                    <label for="barangay">Barangay</label>
                    <input type="text" id="barangay" placeholder="Enter barangay">
                </div>
                <div class="form-group">
                    <label for="cityMunicipality">City/Municipality</label>
                    <input type="text" id="cityMunicipality" placeholder="Enter city/municipality">
                </div>
                <div class="form-group">
                    <label for="province">Province</label>
                    <input type="text" id="province" placeholder="Enter province">
                </div>
                <div class="form-group">
                    <label for="postalCode">Postal Code</label>
                    <input type="text" id="postalCode" placeholder="Enter postal code">
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="btn btn-outline" onclick="window.location.href='adduser.php'">Cancel</button>
                <button type="submit" class="btn btn-primary">Confirm</button>
            </div>
        </form>
    </div>
</body>
</html>
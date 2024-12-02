

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OLSHCO: Discover Research</title>
    <style>
        /* General Styles */
        :root {
            --color-primary: #800000;
            --color-primary-dark: #600000;
            --color-background: #f8f9fa;
            --color-text: #333;
            --color-text-light: #fff;
            --color-link: #1a0dab;
            --color-border: #dfe1e5;
        }
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
            background-color: var(--color-primary);
            color: var(--color-text-light);
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap:10px;
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
        .logo {
            cursor: pointer;
            width: auto;
            height: 30px;
          
            overflow: hidden;
        }
   
        .container {
            display: flex;
            flex: 1;
            transition: all 0.3s ease;
        }
        .sidebar {
            width: 250px;
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
        .toggle-sidebar {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
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
        .grid {
            display: grid;
            gap: 20px;
        }
        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .card-header {
            background-color: #f8f8f8;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        .card-title {
            margin: 0;
            font-size: 1.5rem;
            color: #333;
        }
        .card-content {
            padding: 20px;
        }
        .profile-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .avatar {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .user-name {
            font-size: 1.7rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .user-info {
            color: #666;
            margin-bottom: 5px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 10px;
        }
        .info-label {
            font-weight: bold;
        }
        @media (min-width: 768px) {
            .grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        .edit-button {
    background-color: var(--color-primary);
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 1rem;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 15px;
    transition: background-color 0.3s ease;
}

.edit-button:hover {
    background-color: var(--color-primary-dark);
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    position: relative;
}

.close-button {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 1.5rem;
    color: #333;
    cursor: pointer;
}

h2 {
    margin-bottom: 15px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-group input {
    width: 100%;
    padding: 8px;
    font-size: 1rem;
    border: 1px solid var(--color-border);
    border-radius: 4px;
}

.confirm-button, .save-button {
    background-color: var(--color-primary);
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 1rem;
    border-radius: 5px;
    cursor: pointer;
}

.confirm-button:hover, .save-button:hover {
    background-color: var(--color-primary-dark);
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
       
    </header>

    <div class="container">
    <aside class="sidebar closed">
            <nav>
            <ul class="sidebar-menu">
            <li><a href="myaccount.php"><i class="fas fa-user"></i> My Profile</a></li>
    <li><a href="search.php"><i class="fas solid fa-magnifying-glass"></i> Search</a></li>
    <li><a href="category.php"><i class="fa-solid fa-layer-group"></i> Categories</a></li>
    <li><a href="analytics.php"><i class="fas fa-chart-line"></i> Analytics</a></li>
    <li><a href="mylib.php"><i class="fas fa-book"></i> My Library</a></li>
</ul>

                   
            </nav>
        </aside>

        <main class="main-content">
        <div class="grid">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">User Profile</h2>
                </div>
                <div class="card-content profile-section">
                    <div class="avatar">
                        <img src="images/profile.png?height=200&width=200" alt="User profile">
                    </div>
                    <h1 class="user-name">John Doe</h1>
                   
                    <!-- Edit Button -->
<button class="edit-button" onclick="openPasswordModal()">Edit</button>


                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Personal Information</h2>
                </div>
                <div class="card-content">
                    <div class="info-grid">
                        <span class="info-label">Full Name:</span>
                        <span>John Patrick Doe</span>
                        <span class="info-label">Gender:</span>
                        <span>Male</span>
                        <span class="info-label">Birthdate:</span>
                        <span>January 1, 1990</span>
                        <span class="info-label">Email:</span>
                        <span>john.doe@example.com</span>
                        <span class="info-label">Mobile:</span>
                        <span>+1 (555) 123-4567</span>
                        <span class="info-label">Address:</span>
                        <span>123 Main St, Anytown, USA 12345</span>
                    </div>
                </div>
            </div>
          
        </main>
    </div>
    <div id="passwordModal" class="modal">
    <div class="modal-content">
        <span class="close-button" onclick="closePasswordModal()">&times;</span>
        <h2>Password Confirmation</h2>
        <form id="passwordForm">
            <div class="form-group">
                <label for="password-input">Enter your password:</label>
                <input type="password" id="password-input" name="password" required>
            </div>
            <button type="submit" class="confirm-button">Confirm</button>
        </form>
    </div>
</div>
   <!-- Modal HTML Structure -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeModal()">&times;</span>
        <h2>Edit Personal Information</h2>
        <form id="editForm">
            <div class="form-group">
                <label for="edit-fullname">Full Name:</label>
                <input type="text" id="edit-fullname" name="fullname" value="John Patrick Doe">
            </div>
            <div class="form-group">
                <label for="edit-gender">Gender:</label>
                <select id="edit-gender" name="gender">
                    <option value="Male" selected>Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="edit-birthdate">Birthdate:</label>
                <input type="date" id="edit-birthdate" name="birthdate" value="1990-01-01">
            </div>
            <div class="form-group">
                <label for="edit-email">Email:</label>
                <input type="email" id="edit-email" name="email" value="john.doe@example.com">
            </div>
            <div class="form-group">
                <label for="edit-mobile">Mobile:</label>
                <input type="tel" id="edit-mobile" name="mobile" value="+1 (555) 123-4567">
            </div>
            <div class="form-group">
                <label for="edit-address">Address:</label>
                <input type="text" id="edit-address" name="address" value="123 Main St, Anytown, USA 12345">
            </div>
            <button type="submit" class="save-button">Save Changes</button>
        </form>
    </div>
</div>

    <script>
       document.getElementById("toggle-button").addEventListener("click", function() {
        const sidebar = document.querySelector(".sidebar");
        sidebar.classList.toggle("closed");
    });

// Function to open the password modal
function openPasswordModal() {
    document.getElementById('passwordModal').style.display = 'flex';
}

// Function to close the password modal
function closePasswordModal() {
    document.getElementById('passwordModal').style.display = 'none';
}

// Function to open the edit modal (after password confirmation)
function openEditModal() {
    document.getElementById('editModal').style.display = 'flex';
}

// Function to close the edit modal
function closeModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Function to handle password confirmation
document.getElementById('passwordForm').addEventListener('submit', function (e) {
    e.preventDefault();
    
    const enteredPassword = document.getElementById('password-input').value;
    const correctPassword = 'password123';  // Hardcoded password (replace this with server-side check)

    if (enteredPassword === correctPassword) {
        closePasswordModal();
        openEditModal();  // Open the edit modal if password is correct
    } else {
        alert('Incorrect password. Please try again.');
    }
});

// Close modal if user clicks outside the modal content
window.onclick = function (event) {
    const passwordModal = document.getElementById('passwordModal');
    const editModal = document.getElementById('editModal');
    
    if (event.target === passwordModal) {
        passwordModal.style.display = 'none';
    } else if (event.target === editModal) {
        editModal.style.display = 'none';
    }
};


  
    </script>
</body>
</html>

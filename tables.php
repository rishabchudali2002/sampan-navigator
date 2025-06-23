<?php
include('config.php');

ob_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Database Setup Status</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #2c3e50;
        }
        .message {
            padding: 10px 20px;
            margin-bottom: 10px;
            border-radius: 8px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>📋 Database Setup Status</h1>

<?php
function showMessage($success, $msg) {
    $class = $success ? 'success' : 'error';
    echo "<div class='message $class'>$msg</div>";
}

// Heritage Table
$sql = "CREATE TABLE IF NOT EXISTS heritage (
    heritage_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL
)";
showMessage(mysqli_query($conn, $sql), " Table 'heritage' created.");


// Users Table
$sql = "CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(150)  NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_guide BOOLEAN DEFAULT FALSE,
    is_admin BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    is_available BOOLEAN DEFAULT TRUE,
    date_joined TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
showMessage(mysqli_query($conn, $sql), " Table 'users' created.");
$insert_users = "
    INSERT INTO users (email, name, password, is_guide, is_admin, is_active, is_available) VALUES
    ('leo.messi@email.com', 'Lionel Messi', '12345', FALSE, TRUE, TRUE, FALSE),
    
";
showMessage(mysqli_query($conn, $insert_users), " Sample user data inserted.");


// User Profiles Table
$sql = "CREATE TABLE IF NOT EXISTS user_profiles (
    profile_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    phone_number VARCHAR(15),
    address VARCHAR(255),
    date_of_birth DATE,
    profile_picture VARCHAR(255),
    bio TEXT,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
)";
showMessage(mysqli_query($conn, $sql), " Table 'user_profiles' created.");


showMessage(mysqli_query($conn, $insert_profiles), " User profile data inserted.");

// Bookings Table
$sql = "CREATE TABLE IF NOT EXISTS bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tourist_guide_id INT NOT NULL,
    heritage_site_id INT NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (tourist_guide_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (heritage_site_id) REFERENCES heritage(heritage_id) ON DELETE CASCADE
)";
showMessage(mysqli_query($conn, $sql), " Table 'bookings' created.");

$insert_bookings = "
    INSERT INTO bookings (user_id, tourist_guide_id, heritage_site_id) VALUES
    (1, 3, 2),
    (2, 4, 1),
    (5, 6, 3),
    (3, 7, 2),
    (4, 5, 1),
    (6, 7, 3),
    (7, 3, 4)
";
showMessage(mysqli_query($conn, $insert_bookings), " Sample booking data inserted.");

mysqli_close($conn);
?>

</div>
</body>
</html>
<?php ob_end_flush(); ?>

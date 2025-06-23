<?php
session_start();
include('config.php');



// Get the guide's ID from session
$guide_id = intval($_SESSION['user_id']);

// Query to fetch bookings for the guide. We join the heritage table for additional details.
$query = "
    SELECT b.booking_id, b.booking_date, b.user_id, h.name AS heritage_name, h.location, h.description, h.image
    FROM bookings b
    JOIN heritage h ON b.heritage_site_id = h.heritage_id
    WHERE b.tourist_guide_id = $guide_id
    ORDER BY b.booking_date ASC
";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching bookings: " . mysqli_error($conn));
}

function getUsernameById($conn, $user_id) {
    $user_id = intval($user_id); // Sanitize input

    $query = "SELECT name FROM users WHERE user_id = $user_id";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        // If query fails, show the error for debugging
        die("Query error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        return $user['name'];
    } else {
        return "Unknown User";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guide Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f7fc;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #2A5D67;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card-content {
            padding: 15px;
        }
        .card-title {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: #2A5D67;
        }
        .card-location {
            font-size: 0.9rem;
            margin-bottom: 10px;
            color: #FF7F50;
        }
        .card-date {
            font-size: 0.9rem;
            margin-bottom: 5px;
            color: #333;
        }
        .empty-state {
            text-align: center;
            font-size: 1.2rem;
            color: #666;
            padding: 50px;
        }
        .logout-container {
            text-align: right;
            padding: 10px 20px;
        }
        .logout-btn {
            background-color: #e74c3c;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }
        .logout-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <!-- Header with guide dashboard title and logout button -->
    <div class="header">
        <h1>Guide Dashboard</h1>
        <div class="logout-container">
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="card-grid">
                <?php while ($booking = mysqli_fetch_assoc($result)): ?>
                    <div class="card">
                        <?php if (!empty($booking['image'])): ?>
                            <img src="<?= htmlspecialchars($booking['image']) ?>" alt="<?= htmlspecialchars($booking['heritage_name']) ?>">
                        <?php else: ?>
                            <img src="default.jpg" alt="No Image Available">
                        <?php endif; ?>
                        <div class="card-content">
                            <div class="card-title"><?= htmlspecialchars($booking['heritage_name']) ?></div>
                            <div class="card-location">Location: <?= htmlspecialchars($booking['location']) ?></div>
                            <div class="card-date">Booked Date: <?= htmlspecialchars($booking['booking_date']) ?></div>
                            
                            <div class="card-date">Booked By: <?= htmlspecialchars(getUsernameById($conn,$booking['user_id'])) ?></div>
                            <p><?= htmlspecialchars($booking['description']) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                No bookings found for you yet.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
mysqli_close($conn);
?>

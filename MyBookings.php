<?php
session_start();
include 'config.php';

// For development/debug only:
ini_set('display_errors', 1);
error_reporting(E_ALL);

// var_dump($_SESSION); // Debugging line to check session variables

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("User not logged in.");
}

// Fetch bookings with JOINs to get guide and heritage names
$query = "
    SELECT 
        b.booking_id,
        b.booking_date,
        h.name AS heritage_name,
        h.location,
        u.name AS guide_name
    FROM bookings b
    JOIN heritage h ON b.heritage_site_id = h.heritage_id
    JOIN users u ON b.tourist_guide_id = u.user_id
    WHERE b.user_id = $user_id
    ORDER BY b.booking_date DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f7f7f7;
            padding: 40px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        h2 {
            color: #2A5D67;
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f0f4f8;
            color: #333;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #2A5D67;
        }

        .action-buttons a {
            margin-right: 10px;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
        }

        .edit-btn {
            background-color: #2A5D67;
        }

        .delete-btn {
            background-color: #E74C3C;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📅 My Bookings</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Heritage Site</th>
                    <th>Location</th>
                    <th>Tourist Guide</th>
                    <th>Booking Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($booking = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($booking['heritage_name']) ?></td>
                        <td><?= htmlspecialchars($booking['location']) ?></td>
                        <td><?= htmlspecialchars($booking['guide_name']) ?></td>
                        <td><?= htmlspecialchars($booking['booking_date']) ?></td>
                        <td class="action-buttons">
                            <a href="edit_booking.php?id=<?= $booking['booking_id'] ?>" class="edit-btn">Edit</a>
                            <a href="delete_booking.php?id=<?= $booking['booking_id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this booking?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No bookings found.</p>
    <?php endif; ?>

    <a href="homepage.php" class="back-link">← Back to Home</a>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>

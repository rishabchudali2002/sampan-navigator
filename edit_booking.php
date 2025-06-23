<?php
session_start();
include 'config.php';

$booking_id = $_GET['id'] ?? null;
if (!$booking_id) {
    die("Booking not found.");
}

// Fetch booking details
$query = "
    SELECT b.booking_id, b.booking_date, b.tourist_guide_id, h.name AS heritage_name, u.name AS guide_name
    FROM bookings b
    JOIN heritage h ON b.heritage_site_id = h.heritage_id
    JOIN users u ON b.tourist_guide_id = u.user_id
    WHERE b.booking_id = $booking_id
";

$result = mysqli_query($conn, $query);
$booking = mysqli_fetch_assoc($result);

if (!$booking) {
    die("Booking not found.");
}

// Update logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $guide_id = $_POST['guide_id'] ?? null;
    $booking_date = $_POST['booking_date'] ?? null;

    if ($guide_id && $booking_date) {
        $update_query = "UPDATE bookings SET tourist_guide_id = $guide_id, booking_date = '$booking_date' WHERE booking_id = $booking_id";
        if (mysqli_query($conn, $update_query)) {
            // Redirect to the success page
            header("Location: updateBookingSuccessful.php");
            exit(); // Ensure no further code is executed
        } else {
            echo "❌ Error updating booking: " . mysqli_error($conn);
        }
    }
}

// Fetch available guides for the dropdown
$guides_query = "SELECT * FROM users WHERE is_guide = 1 AND is_available = 1";
$guides_result = mysqli_query($conn, $guides_query);

// Fetch heritage site name (just like in create booking page)
$heritage_query = "SELECT * FROM heritage WHERE heritage_id = (SELECT heritage_site_id FROM bookings WHERE booking_id = $booking_id)";
$heritage_result = mysqli_query($conn, $heritage_query);
$heritage = mysqli_fetch_assoc($heritage_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Booking</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            padding: 40px;
            border-radius: 14px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .heritage-details {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .heritage-details img {
            width: 100%;
            max-width: 400px;
            border-radius: 12px;
            object-fit: cover;
        }

        .heritage-info {
            flex: 1;
        }

        .heritage-info h2 {
            color: #2A5D67;
            margin-bottom: 15px;
        }

        .heritage-info p {
            margin: 10px 0;
            line-height: 1.6;
        }

        .form-section {
            margin-top: 40px;
        }

        form label {
            display: block;
            margin: 15px 0 5px;
            font-weight: 500;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        button {
            margin-top: 25px;
            background-color: #2A5D67;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #1c434c;
        }

        .back-link {
            display: inline-block;
            margin-top: 25px;
            text-decoration: none;
            color: #2A5D67;
        }

        .location {
            font-size: 0.95rem;
            color: #FF7F50;
        }

        @media (max-width: 768px) {
            .heritage-details {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>

<div class="wrapper">
    <div class="heritage-details">
        <img src="uploads/<?= htmlspecialchars($heritage['image']) ?>" alt="<?= htmlspecialchars($heritage['name']) ?>">
        <div class="heritage-info">
            <h2><?= htmlspecialchars($heritage['name']) ?></h2>
            <p class="location">📍 <?= htmlspecialchars($heritage['location']) ?></p>
            <p><?= htmlspecialchars($heritage['description']) ?></p>
        </div>
    </div>

    <div class="form-section">
        <h3>Edit Your Booking</h3>
        <form action="" method="POST">
            <label>Select a New Tourist Guide</label>
            <select name="guide_id" required>
                <option value="">-- Select Guide --</option>
                <?php while ($guide = mysqli_fetch_assoc($guides_result)): ?>
                    <option value="<?= $guide['user_id'] ?>" <?= $guide['user_id'] == $booking['tourist_guide_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($guide['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="booking_date">Booking Date</label>
            <input type="date" name="booking_date" value="<?= htmlspecialchars($booking['booking_date']) ?>" required>

            <button type="submit">Update Booking</button>
        </form>

        <a href="mybookings.php" class="back-link">← Back to My Bookings</a>
    </div>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>

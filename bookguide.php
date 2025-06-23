<?php
include('config.php');
session_start();
// if (isset($_SESSION['user_id'])) {
//     echo "User ID in session: " . $_SESSION['user_id'];
// } else {
//     echo "User ID is not set in session.";
// }

$heritage_id = isset($_GET['heritage_id']) ? intval($_GET['heritage_id']) : 0;

// Fetch heritage site details
$query = "SELECT * FROM heritage WHERE heritage_id = $heritage_id";
$result = mysqli_query($conn, $query);
$heritage = mysqli_fetch_assoc($result);

if (!$heritage) {
    die("Invalid heritage site.");
}

// Fetch available tourist guides
$guides = mysqli_query($conn, "SELECT * FROM users WHERE is_guide = 1 and is_available = 1");
// var_dump($guides); // Debugging line to check the guides data
if (!$guides) {
    // create a toast where guides are not found 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book a Tourist Guide</title>
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
        <img src="<?= htmlspecialchars($heritage['image']) ?>" alt="<?= htmlspecialchars($heritage['name']) ?>">
        <div class="heritage-info">
            <h2><?= htmlspecialchars($heritage['name']) ?></h2>
            <p class="location">📍 <?= htmlspecialchars($heritage['location']) ?></p>
            <p><?= htmlspecialchars($heritage['description']) ?></p>
        </div>
    </div>

    <div class="form-section">
        <h3>Book a Tourist Guide</h3>
        <form action="submitbooking.php" method="post">
            <input type="hidden" name="heritage_id" value="<?= $heritage_id ?>">

            <label >Select a Tourist Guide</label>
            <select name="guide_id" required>
                <option value="">-- Select Guide --</option>
                <?php while($guide = mysqli_fetch_assoc($guides)): 
                    // var_dump($guide);
                ?>
                    <option value="<?= $guide['user_id'] ?>">
                        <?= htmlspecialchars($guide['name']) ?> 
                    </option>
                <?php endwhile; ?>
            </select>


            <label for="booking_date">Booking Date</label>
            <input type="date" name="booking_date" min="<?= date('Y-m-d') ?>" required>
            <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?? null; ?>">
            <input type="hidden" name="heritage_id" value="<?= $heritage_id ?>">

            <button type="submit">Confirm Booking</button>
        </form>

        <a href="homepage.php" class="back-link">← Back to Home</a>
    </div>
</div>

</body>
</html>

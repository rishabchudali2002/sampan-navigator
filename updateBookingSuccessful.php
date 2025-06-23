<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Update Successful</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            max-width: 600px;
            margin: 100px auto;
            background: #fff;
            padding: 40px;
            border-radius: 14px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            text-align: center;
        }

        h2 {
            color: #2A5D67;
        }

        .success-message {
            font-size: 1.2rem;
            color: #28a745;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #2A5D67;
        }

        button {
            background-color: #2A5D67;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 25px;
            text-decoration: none;
        }

        button:hover {
            background-color: #1c434c;
        }
    </style>
</head>
<body>

<div class="wrapper">
    <h2>✅ Booking Updated Successfully!</h2>
    <p class="success-message">Your booking has been successfully updated.</p>
    <a href="mybookings.php" class="back-link">← Back to My Bookings</a>
</div>

</body>
</html>

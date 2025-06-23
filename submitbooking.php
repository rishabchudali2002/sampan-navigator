<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'] ?? null;
    $heritage_id = $_POST['heritage_id'] ?? null;
    $guide_id = $_POST['guide_id'] ?? null;
    $booking_date = $_POST['booking_date'] ?? null;

    // Simple validation
    if (!$user_id || !$heritage_id || !$guide_id || !$booking_date) {
        die("All fields are required.");
    }

    $query = "INSERT INTO bookings (user_id, heritage_site_id, tourist_guide_id, booking_date) 
              VALUES ($user_id, $heritage_id, $guide_id, '$booking_date')";


    if (mysqli_query($conn, $query)) {
        // Success response
        echo <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Booking Confirmed | Heritage Explorer</title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                    font-family: 'Segoe UI', system-ui, sans-serif;
                }

                body {
                    background: #f8fafc;
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 1.5rem;
                }

                .confirmation-card {
                    background: white;
                    max-width: 500px;
                    width: 100%;
                    padding: 2.5rem;
                    border-radius: 1rem;
                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
                    text-align: center;
                }

                .success-icon {
                    font-size: 3.5rem;
                    margin-bottom: 1.5rem;
                    color: #10b981;
                }

                h2 {
                    color: #1e293b;
                    margin-bottom: 1rem;
                    font-size: 1.5rem;
                }

                .booking-details {
                    background: #f1f5f9;
                    padding: 1.5rem;
                    border-radius: 0.5rem;
                    margin: 1.5rem 0;
                    text-align: left;
                }

                .detail-item {
                    margin-bottom: 0.75rem;
                    color: #334155;
                }

                .detail-item strong {
                    color: #64748b;
                    display: inline-block;
                    min-width: 100px;
                }

                .back-link {
                    display: inline-block;
                    background: #10b981;
                    color: white;
                    padding: 0.75rem 1.5rem;
                    border-radius: 0.5rem;
                    text-decoration: none;
                    transition: background 0.2s ease;
                    font-weight: 500;
                }

                .back-link:hover {
                    background: #059669;
                }

                @media (max-width: 480px) {
                    .confirmation-card {
                        padding: 1.5rem;
                    }
                }
            </style>
        </head>
        <body>
            <div class="confirmation-card">
                <div class="success-icon">✅</div>
                <h2>Booking Confirmed!</h2>
                
                <div class="booking-details">
                    <div class="detail-item">
                        <strong>Guide ID:</strong> 
                        <span>"$guide_id."</span>
                    </div>
                    <div class="detail-item">
                        <strong>Date:</strong> 
                        <span>"$booking_date."</span>
                    </div>
                </div>

                <a href="homepage.php" class="back-link">Back to Heritage List</a>
            </div>
        </body>
        </html>
        HTML;
    } else {
        // Error styling
        echo <<<HTML
        <style>
            .error-alert {
                background: #fee2e2;
                color: #dc2626;
                padding: 2rem;
                border-radius: 0.5rem;
                max-width: 500px;
                margin: 2rem auto;
                border: 1px solid #fca5a5;
                text-align: center;
            }
        </style>
        <div class="error-alert">
            ❌ Error: ".mysqli_error($conn)."
        </div>
        HTML;
    }

    mysqli_close($conn);
}
?>
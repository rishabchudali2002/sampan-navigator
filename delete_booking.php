<?php
session_start();
include 'config.php';

$booking_id = $_GET['id'] ?? null;
if (!$booking_id) {
    die("Booking not found.");
}

// Delete the booking
$delete_query = "DELETE FROM bookings WHERE booking_id = $booking_id";

if (mysqli_query($conn, $delete_query)) {
    echo "Booking deleted successfully.";
    header("Location: mybookings.php"); // Redirect after deletion
    exit();
} else {
    echo "Error deleting booking: " . mysqli_error($conn);
}

mysqli_close($conn);
?>

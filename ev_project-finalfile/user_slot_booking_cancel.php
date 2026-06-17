<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$booking_id = (int)$_POST['booking_id'];

// Fetch the booking
$q = mysqli_query($conn, "SELECT * FROM slots_bookings WHERE id='$booking_id' AND user_id='$user_id'");
$booking = mysqli_fetch_assoc($q);

if (!$booking) {
    echo "<script>alert('Invalid booking or unauthorized access.'); window.location='user_slot_view_bookings.php';</script>";
    exit;
}

// Allow cancel only if not completed or cancelled
if (in_array($booking['status'], ['Completed', 'Cancelled'])) {
    echo "<script>alert('Cannot cancel this booking.'); window.location='user_slot_view_bookings.php';</script>";
    exit;
}

// Cancel booking
mysqli_query($conn, "UPDATE slots_bookings SET status='Cancelled' WHERE id='$booking_id'");

// Make slot available again
$slot_id = $booking['slot_id'];
mysqli_query($conn, "UPDATE charging_slots SET status='Available' WHERE id='$slot_id'");

echo "<script>alert('❌ Booking cancelled successfully.'); window.location='user_slot_view_bookings.php';</script>";
exit;
?>

<?php
include 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

$id = $_POST['id'] ?? null;
if (!$id) { 
    echo "<script>alert('Invalid booking ID.'); window.history.back();</script>"; 
    exit; 
}

// 1️⃣ Fetch battery ID and quantity
$booking = $conn->query("SELECT battery_id FROM battery_bookings WHERE id='$id' AND status='Active'")->fetch_assoc();

if (!$booking) {
    echo "<script>alert('Booking not found or already cancelled.'); window.location.href='user_battery_bookings.php';</script>";
    exit;
}

$battery_id = $booking['battery_id'];

// 2️⃣ Cancel booking
$sql1 = "UPDATE battery_bookings SET status='Cancelled' WHERE id='$id'";

// 3️⃣ Increase stock count
$sql2 = "UPDATE battery_rentals SET stock_count = stock_count + 1 WHERE id='$battery_id'";


if (mysqli_query($conn, $sql1) && mysqli_query($conn, $sql2)) {
    echo "<script>alert('Booking cancelled successfully. Stock updated.'); window.location.href='user_battery_bookings.php';</script>";
} else {
    echo "<script>alert('Something went wrong.'); window.location.href='user_battery_bookings.php';</script>";
}
?>

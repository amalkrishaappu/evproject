<?php
include 'db.php';
session_start();

if (!isset($_SESSION['station_id'])) {
    header("Location: station_login.php");
    exit();
}

$booking_id = $_POST['booking_id'] ?? null;
$battery_id = $_POST['battery_id'] ?? null;

if (!$booking_id || !$battery_id) {
    echo "<script>alert('Invalid request'); window.location.href='station_battery_return_list.php';</script>";
    exit;
}

// 1️⃣ Update booking status
mysqli_query($conn, "UPDATE battery_bookings SET status='Returned' WHERE id='$booking_id'");

// 2️⃣ Increase battery stock by 1
mysqli_query($conn, "UPDATE battery_rentals SET stock_count = stock_count + 1 WHERE id='$battery_id'");

echo "<script>alert('Battery return approved and stock updated.'); window.location.href='station_battery_return_list.php';</script>";
?>

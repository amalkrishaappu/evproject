<?php
include 'db.php';
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

$sql = "UPDATE battery_bookings SET status='Return Requested' WHERE id='$id' AND status='Active'";

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Return request sent to station. Waiting for approval.'); window.location.href='user_battery_bookings.php';</script>";
} else {
    echo "<script>alert('Something went wrong while requesting return.'); window.location.href='user_battery_bookings.php';</script>";
}
?>

<?php
session_start();
include 'db.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to book a battery.'); window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$battery_id = $_POST['battery_id'];
$station_id = $_POST['station_id'];
$rental_days = $_POST['rental_days'];
$price_per_day = $_POST['price_per_day'];
$total_price = $rental_days * $price_per_day;

// Fetch stock
$stock_sql = "SELECT stock_count FROM battery_rentals WHERE id='$battery_id'";
$stock_res = mysqli_query($conn, $stock_sql);
$stock_row = mysqli_fetch_assoc($stock_res);

if ($stock_row && $stock_row['stock_count'] > 0) {

    // Calculate return due date
    $return_due = date('Y-m-d', strtotime("+$rental_days days"));

    // Insert booking
    $insert_sql = "INSERT INTO battery_bookings 
                    (user_id, station_id, battery_id, rental_days, total_price, return_due, status) 
                   VALUES 
                    ('$user_id', '$station_id', '$battery_id', '$rental_days', '$total_price', '$return_due', 'Active')";

    if (mysqli_query($conn, $insert_sql)) {

        // Reduce stock
        mysqli_query($conn, "UPDATE battery_rentals 
                             SET stock_count = stock_count - 1 
                             WHERE id='$battery_id'");

        // If stock becomes 0, mark it as 'Rented'
        mysqli_query($conn, "UPDATE battery_rentals 
                             SET status='Rented' 
                             WHERE id='$battery_id' AND stock_count <= 0");

        echo "<script>alert('Battery booked successfully!'); window.location.href='user_battery_view.php';</script>";
    } else {
        echo "<script>alert('Booking failed. Try again.'); window.location.href='user_battery_view.php';</script>";
    }
} else {
    echo "<script>alert('Out of stock!'); window.location.href='user_battery_view.php';</script>";
}
?>

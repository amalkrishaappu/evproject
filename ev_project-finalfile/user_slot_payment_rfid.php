<?php
include('db.php');
session_start();

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

if (!isset($_GET['booking_id']) || empty($_GET['booking_id'])) {
    die("Invalid access.");
}

$user_id = $_SESSION['user_id'];
$booking_id = (int)$_GET['booking_id'];

// Fetch booking details
$q = "SELECT b.*, s.station_name, c.slot_name 
      FROM slots_bookings b 
      JOIN stations s ON b.station_id = s.id 
      JOIN charging_slots c ON b.slot_id = c.id 
      WHERE b.id = '$booking_id' AND b.user_id = '$user_id'";
$res = mysqli_query($conn, $q);
$booking = mysqli_fetch_assoc($res);

if (!$booking) die("❌ Booking not found.");

// Simulate RFID card tap
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rfid_confirm'])) {
    $update = mysqli_query($conn, "UPDATE slots_bookings SET status='Paid', payment_date=NOW() WHERE id='$booking_id'");
    if ($update) {
        echo "<script>alert('✅ Payment confirmed via RFID card!'); window.location='user_bookings.php';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>RFID Payment - EV Charging</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-50 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md text-center">
  <h2 class="text-2xl font-bold text-green-800 mb-4">💳 RFID Payment</h2>

  <p><strong>Station:</strong> <?= htmlspecialchars($booking['station_name']); ?></p>
  <p><strong>Slot:</strong> <?= htmlspecialchars($booking['slot_name']); ?></p>
  <p><strong>Amount:</strong> ₹<?= htmlspecialchars($booking['estimated_amount']); ?></p>

  <div class="mt-6">
    <p class="text-lg text-gray-700">👉 Please tap your RFID card at the station reader to complete payment.</p>
  </div>


  <a href="user_slot_view_bookings.php" class="block mt-4 text-green-700 font-semibold hover:underline">⬅ Back to Bookings</a>
   <div class="text-center mt-6">
    <a href="user_search_station.php" class="text-green-700 font-semibold hover:underline">⬅ Back to Stations</a>
  </div>
</div>

</body>
</html>

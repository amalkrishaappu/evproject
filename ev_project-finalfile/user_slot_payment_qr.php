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

// Fetch booking details for this user only
$q = "SELECT b.*, s.station_name, c.slot_name 
      FROM slots_bookings b 
      JOIN stations s ON b.station_id = s.id 
      JOIN charging_slots c ON b.slot_id = c.id 
      WHERE b.id = '$booking_id' AND b.user_id = '$user_id'";
$res = mysqli_query($conn, $q);
$booking = mysqli_fetch_assoc($res);

if (!$booking) {
    die("❌ Booking not found or unauthorized access.");
}

// Handle payment confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_paid'])) {
    // Prevent multiple payments
    if ($booking['status'] === 'Paid') {
        echo "<script>alert('⚠️ Payment already completed!'); window.location='user_bookings.php';</script>";
        exit;
    }

    $update = mysqli_query($conn, "UPDATE slots_bookings SET status='Paid', payment_date=NOW() WHERE id='$booking_id'");

    if ($update) {
        echo "<script>
            alert('✅ Payment successful! Your slot has been booked.');
            window.location='user_bookings.php';
        </script>";
        exit;
    } else {
        echo "<script>alert('❌ Payment update failed. Try again.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Complete Payment - EV Charging</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
body {
  background: linear-gradient(to bottom right, #f1f8e9, #a5d6a7);
  font-family: 'Poppins', sans-serif;
}
.card {
  background: white;
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  width: 90%;
  max-width: 550px;
}
.processing {
  display: none;
  color: #2e7d32;
  font-weight: bold;
  text-align: center;
  margin-top: 15px;
}
.success {
  display: none;
  color: #1b5e20;
  font-weight: bold;
  text-align: center;
  margin-top: 15px;
}
button {
  background-color: #2e7d32;
  color: white;
  padding: 10px 20px;
  border-radius: 6px;
  cursor: pointer;
  transition: 0.3s;
}
button:hover { background-color: #1b5e20; }
</style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen">

<div class="card">
  <h2 class="text-2xl font-bold text-green-800 mb-4 text-center">💳 Complete Payment</h2>

  <div class="space-y-2">
    <p><strong>🔌 Station:</strong> <?= htmlspecialchars($booking['station_name']); ?></p>
    <p><strong>⚡ Slot:</strong> <?= htmlspecialchars($booking['slot_name']); ?></p>
    <p><strong>🚗 Vehicle:</strong> <?= htmlspecialchars($booking['vehicle_type']); ?></p>
    <p><strong>⏱ Duration:</strong> <?= htmlspecialchars($booking['duration']); ?> hr</p>
    <p><strong>💰 Amount:</strong> ₹<?= htmlspecialchars($booking['estimated_amount']); ?></p>
    <p><strong>Status:</strong> 
      <span class="<?= $booking['status'] === 'Paid' ? 'text-green-700' : 'text-yellow-600' ?>">
        <?= htmlspecialchars($booking['status']); ?>
      </span>
    </p>
  </div>

  <hr class="my-4">

  <?php if ($booking['status'] !== 'Paid'): ?>
    <div class="text-center">
      <p class="font-semibold text-green-800">📱 Scan this QR to Pay</p>
      <img src="img/qrcode.png" width="200" class="mx-auto mt-3 rounded">
      <p class="mt-2 text-gray-600 text-sm">Scan using UPI app (GPay, Paytm, PhonePe)</p>

      <a href="user_slot_view_bookings.php" class="inline-block mt-3 bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800">
        View My Bookings
      </a>
    </div>
  <?php endif; ?>

  <div class="text-center mt-6">
    <a href="user_search_station.php" class="text-green-700 font-semibold hover:underline">⬅ Back to Stations</a>
  </div>
</div>


</body>
</html>

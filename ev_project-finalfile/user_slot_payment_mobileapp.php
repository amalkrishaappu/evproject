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

$q = "SELECT b.*, s.station_name, c.slot_name 
      FROM slots_bookings b 
      JOIN stations s ON b.station_id = s.id 
      JOIN charging_slots c ON b.slot_id = c.id 
      WHERE b.id = '$booking_id' AND b.user_id = '$user_id'";
$res = mysqli_query($conn, $q);
$booking = mysqli_fetch_assoc($res);

if (!$booking) die("❌ Booking not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
    $update = mysqli_query($conn, "UPDATE slots_bookings SET status='Paid', payment_date=NOW() WHERE id='$booking_id'");
    if ($update) {
        echo "<script>alert('✅ Payment confirmed through App!'); window.location='user_bookings.php';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Mobile App Payment - EV Charging</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-50 flex items-center justify-center min-h-screen">

<div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md text-center">
  <h2 class="text-2xl font-bold text-green-800 mb-4">📲 Mobile App Payment</h2>

  <p><strong>Station:</strong> <?= htmlspecialchars($booking['station_name']); ?></p>
  <p><strong>Slot:</strong> <?= htmlspecialchars($booking['slot_name']); ?></p>
  <p><strong>Amount:</strong> ₹<?= htmlspecialchars($booking['estimated_amount']); ?></p>

  <div class="mt-6">
    <p class="text-lg text-gray-700">Click below to complete payment via the EV Charging mobile app.</p>

    <!-- Simulated App Link -->
    <a href="https://play.google.com/store/apps/details?id=com.revos.bolt.android&pli=1pay?booking=<?= $booking_id ?>" 
       class="inline-block bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800 mt-4">
       Open EV App 🚀
    </a>
  </div>

  <a href="user_slot_view_bookings.php" class="block mt-4 text-green-700 font-semibold hover:underline">⬅ Back to Bookings</a>
   <div class="text-center mt-6">
    <a href="user_search_station.php" class="text-green-700 font-semibold hover:underline">⬅ Back to Stations</a>
  </div>
</div>

</body>
</html>

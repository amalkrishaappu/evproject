<?php
include('db.php');
session_start();
date_default_timezone_set('Asia/Kolkata');

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ✅ Fetch all bookings for this user (latest first)
$q = "SELECT b.*, s.station_name, c.slot_name 
      FROM slots_bookings b 
      JOIN stations s ON b.station_id = s.id 
      JOIN charging_slots c ON b.slot_id = c.id 
      WHERE b.user_id = '$user_id'
      ORDER BY b.booking_time DESC";
$res = mysqli_query($conn, $q);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Bookings - EV Charging</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
body {
  background: linear-gradient(to bottom right, #e8f5e9, #a5d6a7);
  font-family: 'Poppins', sans-serif;
  margin: 0;
}
.navbar {
  background-color: #1b5e20;
  color: white;
  padding: 12px 0;
  text-align: right;
  position: sticky;
  top: 0;
  z-index: 50;
  box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.navbar a {
  color: white;
  text-decoration: none;
  font-weight: 600;
  margin: 0 15px;
  transition: color 0.3s;
}
.navbar a:hover {
  color: #a5d6a7;
}
.card {
  background: white;
  border-radius: 15px;
  padding: 20px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  transition: 0.3s;
}
.card:hover { transform: translateY(-4px); }
.status {
  padding: 4px 10px;
  border-radius: 8px;
  color: white;
  font-size: 14px;
}
.status.Pending { background-color: #f59e0b; }
.status.Booked { background-color: #2563eb; }
.status.Paid { background-color: #16a34a; }
.status.Cancelled { background-color: #dc2626; }
.status.Completed { background-color: #4b5563; }
.timer { font-weight: 600; color: #065f46; }
</style>
</head>
<body class="min-h-screen p-0">

<!-- 🌿 NAVBAR -->
<div class="navbar">
  <a href="user_home.php">Home</a>
  <a href="user_search_station.php">Stations</a>
  <a href="user_logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
</div>

<!-- PAGE TITLE -->
<h2 class="text-3xl font-bold text-green-800 mb-6 text-center mt-6">📋 My Bookings</h2>

<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 px-6">
<?php while ($row = mysqli_fetch_assoc($res)): 
    $booking_time = new DateTime($row['booking_time']);
    $due_time = clone $booking_time;
    $due_time->modify('+' . (int)$row['duration'] . ' hour');
    $now = new DateTime();

    // ---- AUTO STATUS MANAGEMENT ----
    $status_label = "";
    if ($now < $booking_time) {
        $interval = $now->diff($booking_time);
        $status_label = "⏳ Starts in " . $interval->h . "h " . $interval->i . "m";
    } elseif ($now >= $booking_time && $now < $due_time) {
        $interval = $now->diff($due_time);
        $status_label = "⚡ Time left: " . $interval->h . "h " . $interval->i . "m";
    } else {
        // Booking expired → mark as completed if not already
        if ($row['status'] != 'Completed' && $row['status'] != 'Cancelled') {
            mysqli_query($conn, "UPDATE slots_bookings SET status='Completed' WHERE id=" . $row['id']);
            mysqli_query($conn, "UPDATE charging_slots SET status='Available' WHERE id=" . $row['slot_id']);
            $row['status'] = 'Completed';
        }
        $status_label = "⛔ Expired / Completed";
    }
?>
  <div class="card">
    <h3 class="text-xl font-semibold text-green-800 mb-1">
      <?= htmlspecialchars($row['station_name']); ?>
    </h3>
    <p><strong>Slot:</strong> <?= htmlspecialchars($row['slot_name']); ?></p>
    <p><strong>Vehicle:</strong> <?= htmlspecialchars($row['vehicle_type']); ?></p>
    <p><strong>Duration:</strong> <?= htmlspecialchars($row['duration']); ?> hr</p>
    <p><strong>Amount:</strong> ₹<?= htmlspecialchars($row['estimated_amount']); ?></p>
    <p><strong>Booking Time:</strong> <?= htmlspecialchars($row['booking_time']); ?></p>
    <p><strong>Status:</strong> 
      <span class="status <?= $row['status']; ?>"><?= htmlspecialchars($row['status']); ?></span>
    </p>
    <p class="timer mt-2"><?= $status_label; ?></p>

    <div class="mt-4 flex flex-wrap gap-2">
      <?php if ($row['status'] == 'Booked' || $row['status'] == 'Pending'): ?>
        <form method="post" action="user_slot_booking_cancel.php" 
              onsubmit="return confirm('Are you sure you want to cancel this booking?');">
          <input type="hidden" name="booking_id" value="<?= $row['id']; ?>">
          <button type="submit" class="bg-red-700 text-white px-3 py-1 rounded hover:bg-red-800">Cancel</button>
        </form>
      <?php endif; ?>

      <?php if ($row['payment_status'] == 'Pending' && $row['status'] == 'Booked'): ?>
        <?php if ($row['auth_method'] == 'QR Code'): ?>
          <a href="user_slot_payment_qr.php?booking_id=<?= $row['id']; ?>" 
             class="bg-green-700 text-white px-3 py-1 rounded hover:bg-green-800">
             💳 Pay via QR
          </a>
        <?php elseif ($row['auth_method'] == 'RFID Card'): ?>
          <a href="user_slot_payment_rfid.php?booking_id=<?= $row['id']; ?>" 
             class="bg-blue-700 text-white px-3 py-1 rounded hover:bg-blue-800">
             💳 Pay via RFID
          </a>
        <?php elseif ($row['auth_method'] == 'Mobile App'): ?>
          <a href="user_slot_payment_mobileapp.php?booking_id=<?= $row['id']; ?>" 
             class="bg-yellow-600 text-white px-3 py-1 rounded hover:bg-yellow-700">
             📲 Pay via App
          </a>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
<?php endwhile; ?>
</div>

<div class="text-center mt-10 mb-6">
  <a href="user_search_station.php" class="text-green-700 font-semibold hover:underline">
    ⬅ Back to Search Stations
  </a>
</div>

</body>
</html>

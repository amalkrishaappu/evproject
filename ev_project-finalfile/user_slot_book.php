<?php
include('db.php');
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

if (!isset($_GET['station_id']) || empty($_GET['station_id'])) {
    die("Invalid station ID.");
}

$user_id = $_SESSION['user_id'];
$station_id = (int)$_GET['station_id'];

// Fetch station details
$station_res = mysqli_query($conn, "SELECT * FROM stations WHERE id = $station_id");
if (!$station_res || mysqli_num_rows($station_res) == 0) die("Station not found.");
$station = mysqli_fetch_assoc($station_res);

// Handle booking form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['slot_id'])) {
    $slot_id = (int)$_POST['slot_id'];
    $auth_method = mysqli_real_escape_string($conn, $_POST['auth_method']);
    $vehicle_type = mysqli_real_escape_string($conn, $_POST['vehicle_type']);
    $duration = (int)$_POST['duration'];

    // Fetch slot data
    $slot_data = mysqli_query($conn, "SELECT * FROM charging_slots WHERE id = $slot_id");
    if (!$slot_data || mysqli_num_rows($slot_data) == 0) {
        echo "<script>alert('❌ Invalid slot!'); window.location='user_slot_book.php?station_id=$station_id';</script>";
        exit;
    }

    $slot = mysqli_fetch_assoc($slot_data);
    $price_per_kwh = (float)$slot['price_per_kwh'];
    $estimated_amount = $duration * $price_per_kwh * 2; // example formula

    // Check if slot is already booked or occupied
    $check_booking = mysqli_query($conn, "SELECT * FROM slots_bookings WHERE slot_id = $slot_id AND status='Booked'");
    if (mysqli_num_rows($check_booking) > 0 || $slot['status'] == 'Occupied') {
        echo "<script>alert('⚠️ This slot is already booked and currently occupied!'); window.location='user_slot_book.php?station_id=$station_id';</script>";
        exit;
    }

    // Insert booking
    $booking_date = date('Y-m-d H:i:s');
    $sql = "INSERT INTO slots_bookings 
        (user_id, station_id, slot_id, vehicle_type, duration, estimated_amount, auth_method, status, booking_date) 
        VALUES 
        ('$user_id', '$station_id', '$slot_id', '$vehicle_type', '$duration', '$estimated_amount', '$auth_method', 'Booked', '$booking_date')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $booking_id = mysqli_insert_id($conn);

        // ✅ Update slot status to Occupied immediately
        mysqli_query($conn, "UPDATE charging_slots SET status='Occupied' WHERE id='$slot_id'");

        if ($auth_method === 'QR Code') {
            echo "<script>
                alert('✅ Slot booked successfully! Redirecting to Booking Details...');
                window.location='user_slot_view_bookings.php?booking_id=$booking_id';
            </script>";
        } elseif ($auth_method === 'RFID Card') {
            echo "<script>
                alert('✅ Slot booked! Please authenticate using your RFID card at the station.');
                window.location='user_slot_view_bookings.php';
            </script>";
        } elseif ($auth_method === 'Mobile App') {
            echo "<script>
                alert('✅ Slot booked! Redirecting to Booking Details...');
                window.location='user_slot_view_bookings.php?booking_id=$booking_id';
            </script>";
        } else {
            echo "<script>
                alert('✅ Slot booked successfully!');
                window.location='user_slot_view_bookings.php';
            </script>";
        }
        exit;
    } else {
        echo "<script>alert('❌ Booking failed! Please try again.');</script>";
    }
}

// ✅ Fetch available slots
$slots_res = mysqli_query($conn, "SELECT * FROM charging_slots WHERE station_id = $station_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book Slot - <?= htmlspecialchars($station['station_name']); ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
body { background: #f0fff4; font-family: 'Poppins', sans-serif; }
.card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
button { background-color: #2e7d32; color: white; padding: 10px; border-radius: 6px; cursor: pointer; transition: 0.3s; }
button:hover { background-color: #256528; }
.status-available { color: green; font-weight: bold; }
.status-occupied { color: red; font-weight: bold; }
</style>
</head>
<body class="p-8">

<h2 class="text-3xl font-bold text-green-900 mb-6 text-center">
⚡ Book Slot - <?= htmlspecialchars($station['station_name']); ?>
</h2>

<div class="card w-full max-w-6xl mx-auto">
  <?php if ($slots_res && mysqli_num_rows($slots_res) > 0): ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
      <?php while ($slot = mysqli_fetch_assoc($slots_res)): ?>
        <div class="border border-green-400 rounded-lg p-4 bg-green-50 text-center">
          <p><strong>Slot:</strong> <?= htmlspecialchars($slot['slot_name']) ?></p>
          <p><strong>Connector:</strong> <?= htmlspecialchars($slot['connector_type']) ?></p>
          <p><strong>Power:</strong> <?= htmlspecialchars($slot['power_kw']) ?> kW</p>
          <p><strong>Price:</strong> ₹<?= htmlspecialchars($slot['price_per_kwh']) ?>/kWh</p>
          <p><strong>Status:</strong> 

          <?php if ($slot['status'] == 'Available'): ?>
              <span class="status-available">Available</span>
          <?php else: ?>
              <?php
              // Fetch active booking for this slot
              $active_booking = mysqli_query($conn, "
                  SELECT booking_time, duration 
                  FROM slots_bookings 
                  WHERE slot_id = {$slot['id']} AND status = 'Booked' 
                  ORDER BY booking_time DESC LIMIT 1
              ");
              if ($active_booking && mysqli_num_rows($active_booking) > 0) {
                  $booking = mysqli_fetch_assoc($active_booking);
                  $end_time = date('Y-m-d H:i', strtotime($booking['booking_time'] . " + {$booking['duration']} hours"));
                  echo "<span class='status-occupied'>Occupied until $end_time</span>";
              } else {
                  echo "<span class='status-occupied'>Unavailable</span>";
              }
              ?>
          <?php endif; ?>
          </p>


          <?php if ($slot['status']=='Available'): ?>
            <form method="POST" class="mt-3 slot-form" data-price="<?= $slot['price_per_kwh'] ?>" data-power="<?= $slot['power_kw'] ?>">
              <input type="hidden" name="slot_id" value="<?= $slot['id'] ?>">
              
              <select name="vehicle_type" required class="w-full mt-2 border p-2 rounded">
                <option value="">Select Vehicle</option>
                <option>2 Wheeler</option>
                <option>3 Wheeler</option>
                <option>4 Wheeler</option>
              </select>

              <select name="auth_method" required class="w-full mt-2 border p-2 rounded">
                <option value="">Select Authentication</option>
                <option value="QR Code">QR Code</option>
                <option value="RFID Card">RFID Card</option>
                <option value="Mobile App">Mobile App</option>
              </select>

              <input type="number" name="duration" min="1" max="10" required placeholder="Duration (hrs)" class="w-full mt-2 border p-2 rounded duration-input">

              <p class="mt-2 text-green-800 font-semibold estimated-amount">Estimated: ₹0.00</p>

              <button type="submit" class="mt-3 w-full">Book Slot</button>
            </form>
          <?php else: ?>
            <p class="mt-3 text-sm text-gray-600">❌ This slot is currently Booked Another user,Please wait.</p>
          <?php endif; ?>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p class="text-gray-700">No slots available at this station.</p>
  <?php endif; ?>
</div>

<div class="text-center mt-8">
  <a href="user_search_station.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">⬅ Back to Stations</a>
</div>

<div class="text-center mt-8">
  <a href="user_slot_view_bookings.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">View Bookings</a>
</div>

<script>
document.querySelectorAll('.slot-form').forEach(form => {
  const price = parseFloat(form.dataset.price);
  const power = parseFloat(form.dataset.power);
  const durationInput = form.querySelector('.duration-input');
  const amountDisplay = form.querySelector('.estimated-amount');

  durationInput.addEventListener('input', () => {
    const hours = parseFloat(durationInput.value) || 0;
    const estimated = price * power * hours;
    amountDisplay.textContent = `Estimated: ₹${estimated.toFixed(2)}`;
  });
});
</script>
</body>
</html>

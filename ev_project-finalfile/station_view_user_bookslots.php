<?php
include('db.php');


session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$station_id = $_SESSION['station_id']; // station ID from login session

$sql = "
SELECT 
  sb.id,
  u.name AS user_name,
  u.email,
  cs.slot_name,
  cs.charger_type,
  cs.connector_type,
  cs.power_kw,
  sb.booking_date,
  sb.duration,
  sb.vehicle_type,
  sb.status,
  sb.payment_status,
  sb.auth_method,
  sb.estimated_amount
FROM slots_bookings sb
JOIN users u ON sb.user_id = u.id
JOIN charging_slots cs ON sb.slot_id = cs.id
WHERE sb.station_id = '$station_id'
ORDER BY sb.booking_date DESC
";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>⚡ Station Slot Bookings</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body {
  font-family: 'Poppins', sans-serif;
  background: #e8f5e9;
  color: #0f2e1f;
  margin: 0;
  padding: 0;
}

/* Navbar */
.navbar {
  background: #14532d;
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px 40px;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}
.navbar h1 {
  font-size: 22px;
  margin: 0;
}
.navbar a {
  color: white;
  text-decoration: none;
  margin-left: 20px;
  font-weight: 500;
  transition: 0.3s;
}
.navbar a:hover {
  color: #a7f3d0;
}

/* Table */
h2 {
  text-align: center;
  color: #14532d;
  margin: 25px 0 10px;
  font-weight: 600;
}
.table-container {
  width: 95%;
  margin: 20px auto;
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.1);
  overflow-x: auto;
}
table {
  width: 100%;
  border-collapse: collapse;
}
th, td {
  text-align: left;
  padding: 12px 14px;
  border-bottom: 1px solid #d1fae5;
}
th {
  background: #14532d;
  color: white;
  font-weight: 600;
}
tr:hover {
  background: #f0fdf4;
}
.status-booked { color: #15803d; font-weight: 600; }
.status-cancelled { color: #dc2626; font-weight: 600; }
.status-completed { color: #2563eb; font-weight: 600; }
.pay-paid { color: #16a34a; font-weight: 600; }
.pay-pending { color: #ca8a04; font-weight: 600; }
.pay-failed { color: #dc2626; font-weight: 600; }
</style>
</head>
<body>

<!-- ✅ Navbar -->
<div class="navbar">
  <h1>⚡ Station Dashboard</h1>
  <div>
    <a href="station_dashboard.php">🏠 Dashboard</a>
    <a href="station_view_user_bookslots.php">⚡ Slot Bookings</a>
    <a href="station_logout.php">🚪 Logout</a>
  </div>
</div>

<h2>⚡ Slot Bookings - User Details</h2>

<div class="table-container">
<table>
  <tr>
    <th>User</th>
    <th>Email</th>
    <th>Slot</th>
    <th>Charger Type</th>
    <th>Connector</th>
    <th>Power (kW)</th>
    <th>Booking Date</th>
    <th>Duration (hr)</th>
    <th>Vehicle</th>
    <th>Auth Method</th>
    <th>Est. Amount</th>
    <th>Payment</th>
    <th>Status</th>
  </tr>
  <?php
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $status_class = 'status-' . strtolower($row['status']);
      $pay_class = 'pay-' . strtolower($row['payment_status']);
      echo "<tr>
              <td>{$row['user_name']}</td>
              <td>{$row['email']}</td>
              <td>{$row['slot_name']}</td>
              <td>{$row['charger_type']}</td>
              <td>{$row['connector_type']}</td>
              <td>{$row['power_kw']}</td>
              <td>{$row['booking_date']}</td>
              <td>{$row['duration']}</td>
              <td>{$row['vehicle_type']}</td>
              <td>{$row['auth_method']}</td>
              <td>₹{$row['estimated_amount']}</td>
              <td class='$pay_class'>{$row['payment_status']}</td>
              <td class='$status_class'>{$row['status']}</td>
            </tr>";
    }
  } else {
    echo "<tr><td colspan='14' style='text-align:center;'>No bookings found for this station.</td></tr>";
  }
  ?>
</table>
</div>

</body>
</html>

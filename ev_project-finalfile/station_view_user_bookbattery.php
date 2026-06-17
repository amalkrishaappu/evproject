<?php
include('db.php');
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ✅ Ensure station is logged in
if (!isset($_SESSION['station_id'])) {
  header("Location: station_login.php");
  exit();
}

$station_id = $_SESSION['station_id'];

// ✅ Fetch bookings only for logged-in station
$sql_all = "
SELECT 
  br.id,
  u.name AS user_name,
  u.email,
  s.station_name,
  b.brand,
  b.model,
  b.capacity,
  b.voltage,
  br.rental_days,
  br.total_price,
  br.booked_on,
  br.return_due,
  br.status
FROM battery_bookings br
JOIN users u ON br.user_id = u.id
JOIN stations s ON br.station_id = s.id
JOIN battery_rentals b ON br.battery_id = b.id
WHERE br.station_id = $station_id
ORDER BY br.booked_on DESC";

$result_all = mysqli_query($conn, $sql_all);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>🔋 Station Battery Rentals</title>
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
.status-active { color: #16a34a; font-weight: 600; }
.status-cancelled { color: #dc2626; font-weight: 600; }
.status-returned { color: #2563eb; font-weight: 600; }

.view-btn {
  display: block;
  background: #14532d;
  color: white;
  border: none;
  padding: 12px 25px;
  margin: 25px auto;
  border-radius: 10px;
  text-decoration: none;
  font-size: 15px;
  font-weight: 500;
  transition: 0.3s;
  text-align: center;
  width: fit-content;
}
.view-btn:hover {
  background: #0d291b;
}
</style>
</head>
<body>

<!-- ✅ Navbar -->
<div class="navbar">
  <h1>⚡ Station Dashboard</h1>
  <div>
    <a href="station_dashboard.php">🏠 Dashboard</a>
    <a href="station_view_user_bookbattery.php">🔋 Rentals</a>
    <a href="station_battery_return_list.php">📦 Returned</a>
    <a href="station_logout.php">🚪 Logout</a>
  </div>
</div>

<h2>🔋 Battery Rental Bookings (Your Station)</h2>

<div class="table-container">
<table>
  <tr>
    <th>User</th>
    <th>Email</th>
    <th>Battery Brand</th>
    <th>Model</th>
    <th>Capacity</th>
    <th>Voltage</th>
    <th>Rental Days</th>
    <th>Total Price</th>
    <th>Booked On</th>
    <th>Return Due</th>
    <th>Status</th>
  </tr>
  <?php
  if (mysqli_num_rows($result_all) > 0) {
    while ($row = mysqli_fetch_assoc($result_all)) {
      $class = strtolower($row['status']);
      echo "<tr>
              <td>{$row['user_name']}</td>
              <td>{$row['email']}</td>
              <td>{$row['brand']}</td>
              <td>{$row['model']}</td>
              <td>{$row['capacity']} Ah</td>
              <td>{$row['voltage']} V</td>
              <td>{$row['rental_days']}</td>
              <td>₹{$row['total_price']}</td>
              <td>{$row['booked_on']}</td>
              <td>{$row['return_due']}</td>
              <td class='status-$class'>{$row['status']}</td>
            </tr>";
    }
  } else {
    echo "<tr><td colspan='11' style='text-align:center;'>No bookings for this station.</td></tr>";
  }
  ?>
</table>
</div>

<a href="station_battery_return_list.php" class="view-btn">📦 View Returned Rentals</a>

</body>
</html>

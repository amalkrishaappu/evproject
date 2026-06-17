<?php
include('db.php');
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

$station_id = $_SESSION['station_id']; // current station login

// 1️⃣ Total stations
$total_stations = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM charging_slots WHERE station_id = $station_id"))['c'];

// 2️⃣ Total batteries for this station
$total_batteries = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM battery_rentals WHERE station_id = $station_id"))['c'];

// 3️⃣ Total booked batteries
$total_booked_batteries = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM battery_bookings WHERE station_id = $station_id AND status = 'Active'"))['c'];

// 4️⃣ Total returned batteries
$total_returned_batteries = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM battery_bookings WHERE station_id = $station_id AND status = 'Returned'"))['c'];

// 5️⃣ Total cancelled batteries
$total_cancelled_batteries = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM battery_bookings WHERE station_id = $station_id AND status = 'Cancelled'"))['c'];

// 6️⃣ Total booked slots
$total_booked_slots = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM slots_bookings WHERE station_id = $station_id AND status = 'Booked'"))['c'];

// 7️⃣ Total cancelled slots
$total_cancelled_slots = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM slots_bookings WHERE station_id = $station_id AND status = 'Cancelled'"))['c'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Station Reports Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body {
  font-family: 'Poppins', sans-serif;
  background: #e8f5e9;
  margin: 0;
  padding: 0;
  color: #0f2e1f;
}

/* Navbar */
.navbar {
  background: #14532d;
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px 40px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.2);
  position: sticky;
  top: 0;
  z-index: 10;
}
.navbar h1 { font-size: 22px; margin: 0; }
.navbar a {
  color: white;
  text-decoration: none;
  margin-left: 20px;
  font-weight: 500;
  transition: 0.3s;
}
.navbar a:hover { color: #a7f3d0; }

/* Dashboard Boxes */
.dashboard {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 20px;
  margin: 40px auto;
  width: 90%;
}
.card {
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 6px 15px rgba(0,0,0,0.1);
  text-align: center;
  transition: 0.3s;
}
.card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 18px rgba(0,0,0,0.15);
}
.card h3 {
  color: #14532d;
  margin-bottom: 10px;
  font-size: 16px;
}
.card .count {
  font-size: 30px;
  font-weight: 700;
  color: #065f46;
}
.card.green { border-top: 5px solid #15803d; }
.card.blue { border-top: 5px solid #2563eb; }
.card.red { border-top: 5px solid #dc2626; }
.card.yellow { border-top: 5px solid #ca8a04; }
.card.gray { border-top: 5px solid #64748b; }

</style>
</head>
<body>

<!-- ✅ Navbar -->
<div class="navbar">
  <h1>⚡ Station Reports</h1>
  <div>
    <a href="station_dashboard.php">🏠 Dashboard</a>
    <a href="station_logout.php">🚪 Logout</a>
  </div>
</div>

<h2 style="text-align:center; margin-top:30px; color:#14532d;">📊 Station Summary Overview</h2>

<!-- ✅ Dashboard Boxes -->
<div class="dashboard">
  <div class="card green">
    <h3>Total Slots</h3>
    <div class="count"><?= $total_stations ?></div>
  </div>

  <div class="card blue">
    <h3>Total Batteries</h3>
    <div class="count"><?= $total_batteries ?></div>
  </div>

  <div class="card green">
    <h3>Booked Batteries</h3>
    <div class="count"><?= $total_booked_batteries ?></div>
  </div>

  <div class="card yellow">
    <h3>Returned Batteries</h3>
    <div class="count"><?= $total_returned_batteries ?></div>
  </div>

  <div class="card red">
    <h3>Cancelled Batteries</h3>
    <div class="count"><?= $total_cancelled_batteries ?></div>
  </div>

  <div class="card blue">
    <h3>Booked Slots</h3>
    <div class="count"><?= $total_booked_slots ?></div>
  </div>

  <div class="card red">
    <h3>Cancelled Slots</h3>
    <div class="count"><?= $total_cancelled_slots ?></div>
  </div>
</div>

</body>
</html>

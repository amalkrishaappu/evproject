<?php
include 'db.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['station_id'])) {
    header("Location: station_login.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Station Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
/* --- GLOBAL --- */
body {
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(135deg, #0f2027, #203a43, #2c7744);
  margin: 0;
  padding: 0;
  color: #e8f5e9;
}

/* --- NAVBAR --- */
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: linear-gradient(90deg, #145a32, #1d8348);
  padding: 15px 30px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.3);
  color: #fff;
  font-weight: 600;
}

.navbar a {
  text-decoration: none;
  color: #d5f5e3;
  padding: 8px 15px;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.navbar a:hover {
  background: #fff;
  color: #1d8348;
}

/* --- HEADER --- */
.dashboard-header {
  text-align: center;
  margin: 40px 0;
  font-size: 36px;
  font-weight: 700;
  background: linear-gradient(90deg, #00ff88, #a8ff78);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  animation: glow 2s ease-in-out infinite alternate;
}

@keyframes glow {
  from { text-shadow: 0 0 10px #00ff88; }
  to { text-shadow: 0 0 25px #a8ff78; }
}

/* --- CARDS CONTAINER --- */
.card-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 25px;
  padding: 20px;
}

/* --- INDIVIDUAL CARD --- */
.card {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border: 2px solid rgba(0, 255, 128, 0.4);
  border-radius: 15px;
  width: 260px;
  padding: 25px;
  text-align: center;
  color: #e8f5e9;
  box-shadow: 0 8px 20px rgba(0,255,128,0.15);
  transition: transform 0.3s, box-shadow 0.3s, border-color 0.3s;
  position: relative;
}

.card:hover {
  transform: translateY(-8px);
  border-color: #00ff88;
  box-shadow: 0 15px 30px rgba(0,255,128,0.4);
}

/* --- CARD TITLES --- */
.card h3 {
  font-size: 20px;
  margin-bottom: 15px;
  background: linear-gradient(90deg, #00ff88, #a8ff78);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* --- CARD BUTTON --- */
.card a {
  display: inline-block;
  padding: 10px 18px;
  margin-top: 10px;
  font-weight: 500;
  border-radius: 8px;
  text-decoration: none;
  color: #fff;
  background: linear-gradient(45deg, #00c853, #64dd17);
  box-shadow: 0 5px 15px rgba(0,255,128,0.3);
  transition: all 0.3s ease;
}

.card a:hover {
  background: linear-gradient(45deg, #00e676, #76ff03);
  box-shadow: 0 0 15px #00ff88;
}

/* --- RESPONSIVE --- */
@media(max-width:768px){
  .card{ width: 45%; }
}
@media(max-width:480px){
  .card{ width: 90%; }
}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
  <div>
    <?php
    // Display the station name dynamically
    if (isset($_SESSION['station_name'])) {
        echo "Welcome, " . htmlspecialchars($_SESSION['station_name']);
    } else {
        echo "Welcome, Station Admin";
    }
    ?>
  </div>

  <div>
    <a href="station_profile.php">Profile</a>
    <a href="station_logout.php">Logout</a>
  </div>
</div>


<!-- HEADER -->
<h2 class="dashboard-header">Station Dashboard</h2>

<!-- CARDS -->
<div class="card-container">

  <div class="card">
    <h3>Manage Slots</h3>
    <a href="station_slot_view.php">Go</a>
  </div>

  <div class="card">
    <h3>Manage Batteries</h3>
    <a href="station_battery_view.php">Go</a>
  </div>

  <div class="card">
    <h3>Users Booked Rental Details</h3>
     <a href="station_view_user_bookbattery.php">Go</a>
  </div>

    <div class="card">
    <h3>Users Booked Slot Details</h3>
     <a href="station_view_user_bookslots.php">Go</a>
  </div>

  <div class="card">
    <h3>Feedback</h3>
    <a href="station_view_user_feedback.php">Go</a>
  </div>

  <div class="card">
    <h3>Reports</h3>
    <a href="station_view_reports.php">Go</a>
  </div>

  <div class="card">
    <h3>Settings</h3>
    <a href="#">Go</a>
  </div>
</div>

</body>
</html>

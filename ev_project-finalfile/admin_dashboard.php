<?php
include('db.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// ====== TOTAL COUNTS ======
$total_stations = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM stations"))['c'];
$total_slots = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM charging_slots"))['c'];
$total_batteries = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM battery_rentals"))['c'];
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM users"))['c'];
$total_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM slots_bookings"))['c'];
$total_battery_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM battery_bookings"))['c'];
$total_feedback = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM feedback"))['c'];

$view = isset($_GET['view']) ? $_GET['view'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EV Admin Dashboard</title>
<style>
:root {
  --primary: #1b5e20;
  --secondary: #c8e6c9;
  --accent: #4caf50;
  --text-dark: #1b1b1b;
  --card-bg: #ffffff;
}
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
body {
  background: var(--secondary);
  color: var(--text-dark);
  display: flex;
  height: 100vh;
}

/* Sidebar */
.sidebar {
  width: 250px;
  background: var(--primary);
  color: white;
  display: flex;
  flex-direction: column;
  padding: 20px;
  box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
}
.sidebar h2 { text-align: center; margin-bottom: 30px; font-weight: 700; }
.sidebar a {
  text-decoration: none; color: white; padding: 12px; border-radius: 8px;
  margin-bottom: 10px; transition: background 0.3s; display: block;
}
.sidebar a:hover { background: rgba(255, 255, 255, 0.2); }
.sidebar a.active { background: white; color: var(--primary); font-weight: 600; }

/* Main */
.main { flex: 1; padding: 25px; overflow-y: auto; }
.header {
  display: flex; justify-content: space-between; align-items: center;
  background: var(--card-bg); padding: 15px 25px; border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}
.header h1 { font-size: 22px; color: var(--text-dark); }

.cards {
  display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px; margin-top: 30px;
}
.card {
  background: var(--card-bg); border-radius: 12px; padding: 20px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center;
  transition: transform 0.3s, background 0.3s; cursor: pointer;
}
.card:hover { transform: translateY(-5px); background: #f1f8e9; }
.card h3 { color: var(--accent); font-size: 18px; margin-bottom: 10px; }
.card span { font-size: 26px; font-weight: 700; color: var(--text-dark); }

/* Table Section */
.table-section {
  background: var(--card-bg); margin-top: 30px; border-radius: 12px;
  padding: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}
table { width: 100%; border-collapse: collapse; margin-top: 15px; }
th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
th { background: var(--secondary); color: var(--text-dark); font-weight: 600; }
tr:hover { background-color: #f9fcff; }
.status-active { color: green; font-weight: 600; }
.status-inactive { color: red; font-weight: 600; }
</style>
</head>
<body>

<div class="sidebar">
  <h2>⚡ EV Admin</h2>
  <a href="admin_dashboard.php" class="<?= $view==''?'active':'' ?>">Dashboard</a>
  <a href="?view=stations" class="<?= $view=='stations'?'active':'' ?>">Charging Stations</a>
  <a href="?view=slots" class="<?= $view=='slots'?'active':'' ?>">Charging Slots</a>
  <a href="?view=batteries" class="<?= $view=='batteries'?'active':'' ?>">Battery Rentals</a>
  <a href="?view=users" class="<?= $view=='users'?'active':'' ?>">Users</a>
  <a href="?view=bookings" class="<?= $view=='bookings'?'active':'' ?>">Slot Bookings</a>
  <a href="?view=batterybookings" class="<?= $view=='batterybookings'?'active':'' ?>">Battery Bookings</a>
  <a href="?view=feedback" class="<?= $view=='feedback'?'active':'' ?>">Feedback</a>
  <a href="admin_logout.php">Logout</a>
</div>

<div class="main">
  <div class="header"><h1>Welcome, Admin</h1></div>

  <?php if ($view == ''): ?>
  <!-- Dashboard -->
  <div class="cards">
    <div class="card" onclick="window.location='?view=stations'">
      <h3>Total Stations</h3><span><?= $total_stations ?></span><p>Registered Charging Points</p>
    </div>
    <div class="card" onclick="window.location='?view=slots'">
      <h3>Total Slots</h3><span><?= $total_slots ?></span><p>Available Charging Slots</p>
    </div>
    <div class="card" onclick="window.location='?view=batteries'">
      <h3>Battery Rentals</h3><span><?= $total_batteries ?></span><p>Active Battery Units</p>
    </div>
    <div class="card" onclick="window.location='?view=users'">
      <h3>Total Users</h3><span><?= $total_users ?></span><p>Registered Customers</p>
    </div>
    <div class="card" onclick="window.location='?view=bookings'">
      <h3>Slot Bookings</h3><span><?= $total_bookings ?></span><p>Active Charging Bookings</p>
    </div>
    <div class="card" onclick="window.location='?view=batterybookings'">
      <h3>Battery Bookings</h3><span><?= $total_battery_bookings ?></span><p>Battery Rental Records</p>
    </div>
    <div class="card" onclick="window.location='?view=feedback'">
      <h3>User Feedback</h3><span><?= $total_feedback ?></span><p>Complaints & Reviews</p>
    </div>
  </div>

  <?php elseif ($view == 'stations'): ?>
  <div class="table-section">
    <h3>Charging Stations</h3>
    <table>
      <tr><th>ID</th><th>Name</th><th>Owner</th><th>Email</th><th>Phone</th><th>District</th><th>Status</th></tr>
      <?php
      $res = mysqli_query($conn, "SELECT * FROM stations");
      while($r = mysqli_fetch_assoc($res)): ?>
      <tr>
        <td><?= $r['id'] ?></td>
        <td><?= htmlspecialchars($r['station_name']) ?></td>
        <td><?= htmlspecialchars($r['owner_name']) ?></td>
        <td><?= htmlspecialchars($r['email']) ?></td>
        <td><?= htmlspecialchars($r['phone']) ?></td>
        <td><?= htmlspecialchars($r['district']) ?></td>
        <td class="<?= $r['status']=='approved'?'status-active':'status-inactive' ?>">
          <?= htmlspecialchars($r['status']) ?>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>
    <a href="admin_view_stations.php" 
       style="background:linear-gradient(90deg,#22c55e,#16a34a); color:white; 
              padding:8px 16px; border-radius:6px; text-decoration:none; align-items:center
              font-weight:600; font-size:14px; box-shadow:0 3px 6px rgba(0,0,0,0.2);">
      ➕ Approve Station
    </a>
 

  </div>

  <?php elseif ($view == 'slots'): ?>
  <div class="table-section">

    <h3>Charging Slots</h3>
    <table>
      <tr><th>ID</th><th>Station</th><th>Slot Name</th><th>Charger Type</th><th>Connector</th><th>Status</th></tr>
      <?php
      $res = mysqli_query($conn, "SELECT c.*, s.station_name FROM charging_slots c JOIN stations s ON c.station_id=s.id");
      while($r = mysqli_fetch_assoc($res)): ?>
      <tr>
        <td><?= $r['id'] ?></td>
        <td><?= htmlspecialchars($r['station_name']) ?></td>
        <td><?= htmlspecialchars($r['slot_name']) ?></td>
        <td><?= htmlspecialchars($r['charger_type']) ?></td>
        <td><?= htmlspecialchars($r['connector_type']) ?></td>
        <td><?= htmlspecialchars($r['status']) ?></td>
      </tr>
      <?php endwhile; ?>
    </table>

  </div>

  <?php elseif ($view == 'batteries'): ?>
  <div class="table-section">
    <h3>Battery Rentals</h3>
    <table>
      <tr><th>ID</th><th>Station</th><th>Brand</th><th>Model</th><th>Capacity</th><th>Voltage</th><th>Condition</th><th>Status</th></tr>
      <?php
      $res = mysqli_query($conn, "SELECT b.*, s.station_name FROM battery_rentals b JOIN stations s ON b.station_id=s.id");
      while($r = mysqli_fetch_assoc($res)): ?>
      <tr>
        <td><?= $r['id'] ?></td>
        <td><?= htmlspecialchars($r['station_name']) ?></td>
        <td><?= htmlspecialchars($r['brand']) ?></td>
        <td><?= htmlspecialchars($r['model']) ?></td>
        <td><?= htmlspecialchars($r['capacity']) ?> Ah</td>
        <td><?= htmlspecialchars($r['voltage']) ?> V</td>
        <td><?= htmlspecialchars($r['condition']) ?></td>
        <td><?= htmlspecialchars($r['status']) ?></td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>

  <?php elseif ($view == 'users'): ?>
  <div class="table-section">
    <h3>Registered Users</h3>
    <table>
      <tr><th>ID</th><th>Name</th><th>Email</th><th>Username</th><th>Phone</th><th>Address</th><th>Registered_on</th></tr>
      <?php
      $res = mysqli_query($conn, "SELECT * FROM users");
      while($r = mysqli_fetch_assoc($res)): ?>
      <tr>
        <td><?= $r['id'] ?></td>
        <td><?= htmlspecialchars($r['name']) ?></td>
        <td><?= htmlspecialchars($r['email']) ?></td>
        <td><?= htmlspecialchars($r['username']) ?></td>
        <td><?= htmlspecialchars($r['phoneno']) ?></td>
        <td><?= htmlspecialchars($r['address']) ?></td>
        <td><?= htmlspecialchars($r['created_at']) ?></td>
      </tr>
      <?php endwhile; ?>
    </table>
    <div style="text-align:center; margin-top:25px;">
      <a href="admin_view_user_details.php" 
         style="background-color:#1b5e20; color:white; padding:10px 18px; border-radius:6px; text-decoration:none; font-weight:600; margin:0 10px; display:inline-block; transition:0.3s;">
         View Detailed User List
      </a>
      <a href="admin_view_userloggings.php" 
         style="background-color:#388e3c; color:white; padding:10px 18px; border-radius:6px; text-decoration:none; font-weight:600; margin:0 10px; display:inline-block; transition:0.3s;">
         View User Login Time
      </a>
    </div>


  </div>

  <?php elseif ($view == 'bookings'): ?>
  <div class="table-section">
    <h3>Slot Bookings</h3>
    <table>
      <tr><th>ID</th><th>User</th><th>Station</th><th>Slot</th><th>Status</th><th>Payment</th><th>Booking Time</th></tr>
      <?php
      $res = mysqli_query($conn, "SELECT b.*, u.name AS uname, s.station_name, c.slot_name 
                                  FROM slots_bookings b 
                                  JOIN users u ON b.user_id=u.id 
                                  JOIN stations s ON b.station_id=s.id
                                  JOIN charging_slots c ON b.slot_id=c.id");
      while($r = mysqli_fetch_assoc($res)): ?>
      <tr>
        <td><?= $r['id'] ?></td>
        <td><?= htmlspecialchars($r['uname']) ?></td>
        <td><?= htmlspecialchars($r['station_name']) ?></td>
        <td><?= htmlspecialchars($r['slot_name']) ?></td>
        <td><?= htmlspecialchars($r['status']) ?></td>
        <td><?= htmlspecialchars($r['payment_status']) ?></td>
        <td><?= htmlspecialchars($r['booking_time']) ?></td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>

  <?php elseif ($view == 'batterybookings'): ?>
  <div class="table-section">
    <h3>Battery Bookings</h3>
    <table>
      <tr><th>ID</th><th>User</th><th>Station</th><th>Battery</th><th>Days</th><th>Total Price</th><th>Status</th></tr>
      <?php
      $res = mysqli_query($conn, "SELECT b.*, u.name AS uname, s.station_name, r.brand, r.model 
                                  FROM battery_bookings b 
                                  JOIN users u ON b.user_id=u.id 
                                  JOIN stations s ON b.station_id=s.id 
                                  JOIN battery_rentals r ON b.battery_id=r.id");
      while($r = mysqli_fetch_assoc($res)): ?>
      <tr>
        <td><?= $r['id'] ?></td>
        <td><?= htmlspecialchars($r['uname']) ?></td>
        <td><?= htmlspecialchars($r['station_name']) ?></td>
        <td><?= htmlspecialchars($r['brand'].' '.$r['model']) ?></td>
        <td><?= htmlspecialchars($r['rental_days']) ?></td>
        <td>₹<?= htmlspecialchars($r['total_price']) ?></td>
        <td><?= htmlspecialchars($r['status']) ?></td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>

  <?php elseif ($view == 'feedback'): ?>
  <div class="table-section">
    <h3>User Feedback & Complaints</h3>
    <table>
      <tr><th>ID</th><th>User</th><th>Type</th><th>Title</th><th>Message</th><th>Rating</th><th>Date</th></tr>
      <?php
      $res = mysqli_query($conn, "SELECT f.*, u.name FROM feedback f JOIN users u ON f.user_id=u.id ORDER BY f.date DESC");
      while($r = mysqli_fetch_assoc($res)): ?>
      <tr>
        <td><?= $r['id'] ?></td>
        <td><?= htmlspecialchars($r['name']) ?></td>
        <td><?= htmlspecialchars(ucfirst($r['type'])) ?></td>
        <td><?= htmlspecialchars($r['title']) ?></td>
        <td><?= htmlspecialchars($r['message']) ?></td>
        <td><?= htmlspecialchars($r['rating']) ?></td>
        <td><?= htmlspecialchars($r['date']) ?></td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>
  <?php endif; ?>
</div>
</body>
</html>

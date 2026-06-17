<?php
include('db.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

$stations = [];
$selectedDistrict = "";

// When form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['district'])) {
    $selectedDistrict = mysqli_real_escape_string($conn, $_POST['district']);
    $query = "SELECT * FROM stations WHERE district = '$selectedDistrict' AND status = 'approved'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $stations = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        die("Database query failed: " . mysqli_error($conn));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>EV Charging Stations</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
body {
  background: linear-gradient(to bottom right, #e8f5e9, #a5d6a7);
  font-family: 'Poppins', sans-serif;
}

/* ✅ Navbar Styling */
.navbar {
  background-color: #14532d;
  color: white;
  padding: 15px 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 3px 8px rgba(0,0,0,0.2);
}
.navbar h1 {
  font-size: 20px;
  font-weight: 600;
  letter-spacing: 0.5px;
}
.navbar ul {
  list-style: none;
  display: flex;
  margin: 0;
  padding: 0;
  gap: 20px;
}
.navbar ul li a {
  color: white;
  text-decoration: none;
  font-weight: 500;
  padding: 8px 12px;
  border-radius: 8px;
  transition: 0.3s;
}
.navbar ul li a:hover,
.navbar ul li a.active {
  background-color: #1b5e20;
}

/* ✅ Card Styles */
.card {
  background: white;
  width: 400px;
  border-top: 6px solid #2e7d32;
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  transition: 0.3s;
}
.card:hover {
  transform: scale(1.02);
  box-shadow: 0 8px 16px rgba(0,0,0,0.2);
}
select, button {
  padding: 10px;
  border-radius: 8px;
  border: 2px solid #2e7d32;
  outline: none;
}
button {
  background-color: #2e7d32;
  color: white;
  font-weight: 600;
  cursor: pointer;
  transition: 0.3s;
}
button:hover {
  background-color: #1b5e20;
}
.station-img {
  width: 100%;
  height: 180px;
  object-fit: cover;
  border-radius: 10px;
  margin-bottom: 12px;
}
.map-btn, .book-btn {
  display: inline-block;
  padding: 8px 8px;
  border-radius: 8px;
  font-weight: 600;
  color: white;
  text-decoration: none;
  transition: 0.3s;
}
.map-btn { background-color: #7ebb81ff; }
.map-btn:hover { background-color: #82c193ff; }
.book-btn { background-color: #9cca9eff; }
.book-btn:hover { background-color: #6b896dff; }
.section-title {
  font-weight: 600;
  color: #2e7d32;
  margin-top: 15px;
  margin-bottom: 6px;
  border-bottom: 2px solid #a5d6a7;
  padding-bottom: 4px;
}
.sub-card {
  background: #f1f8e9;
  padding: 10px 15px;
  border-radius: 10px;
  margin-bottom: 10px;
}
</style>
</head>
<body class="min-h-screen flex flex-col">

<!-- ✅ NAVBAR -->
<div class="navbar">
  <h1>⚡ EcoCharge User</h1>
  <ul>
    <li><a href="user_home.php">🏠 Home</a></li>
    <li><a href="user_search_station.php" class="active">⚡ Stations</a></li>
    <li><a href="user_slot_view_bookings.php">📅 Booked Slots</a></li>
    <li><a href="user_battery_bookings.php">🔋 Booked Batteries</a></li>
    <li><a href="user_profile.php">👤 Profile</a></li>
    <li><a href="user_logout.php">🚪 Logout</a></li>
  </ul>
</div>

<!-- ✅ PAGE CONTENT -->
<div class="flex flex-col items-center justify-start p-8">

<h2 class="text-3xl font-bold text-green-900 mb-6 text-center">🔋 Find EV Charging Stations</h2>

<!-- District Selection -->
<div class="card w-full max-w-md text-center mb-8">
  <form method="POST" action="">
    <label class="text-green-900 font-semibold block mb-2">Select Your District</label>
    <select name="district" required>
      <option value="">-- Choose District --</option>
      <?php
      $districts = [
        "Thiruvananthapuram","Kollam","Pathanamthitta","Alappuzha","Kottayam","Idukki",
        "Ernakulam","Thrissur","Palakkad","Malappuram","Kozhikode","Wayanad","Kannur","Kasaragod"
      ];
      foreach ($districts as $dist) {
          $sel = ($selectedDistrict == $dist) ? "selected" : "";
          echo "<option value='$dist' $sel>$dist</option>";
      }
      ?>
    </select>
    <br><br>
    <button type="submit">🔍 Find Stations</button>
  </form>
</div>

<!-- Station Results -->
<?php if ($selectedDistrict): ?>
  <h3 class="text-2xl font-bold text-green-800 mb-4">
    Stations in <?php echo htmlspecialchars($selectedDistrict); ?>
  </h3>

  <?php if (!empty($stations)): ?>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($stations as $station): ?>
      <div class="card">
        <!-- Station Image -->
        <?php if (!empty($station['image_path'])): ?>
          <img src="<?php echo htmlspecialchars($station['image_path']); ?>" class="station-img" alt="Station Image">
        <?php endif; ?>

        <h3 class="text-xl font-semibold text-green-900 mb-1">
          <?php echo htmlspecialchars($station['station_name']); ?>
        </h3>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($station['phone']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($station['location']); ?></p>

        <!-- Map & Book Slot Buttons -->
        <div class="flex justify-between items-center mt-3">
          <?php 
            $map_query = urlencode($station['station_name'] . " " . $station['location'] . " " . $station['district']);
          ?>
          <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $map_query; ?>" target="_blank" class="map-btn">📍 View Map</a>
          <a href="user_slot_book.php?station_id=<?php echo $station['id']; ?>" class="book-btn">🕓 Book Slot</a>
        </div>

        <!-- Toggle Buttons -->
        <div class="mt-4 flex justify-between">
          <button type="button" onclick="toggleSection('slots-<?php echo $station['id']; ?>')" class="bg-green-700 text-white px-2 py-2 rounded-md hover:bg-green-800">⚡ View Slots</button>
          <button type="button" onclick="toggleSection('batteries-<?php echo $station['id']; ?>')" class="bg-green-700 text-white px-2 py-2 rounded-md hover:bg-green-800">🔋 View Batteries</button>
        </div>

        <!-- ⚡ Charging Slots -->
        <div id="slots-<?php echo $station['id']; ?>" class="hidden mt-4">
          <h4 class="section-title">⚡ Charging Slots</h4>
          <?php
          $station_id = (int)$station['id'];
          $slot_sql = "SELECT * FROM charging_slots WHERE station_id = $station_id";
          $slot_res = mysqli_query($conn, $slot_sql);
          if ($slot_res && mysqli_num_rows($slot_res) > 0) {
              while ($slot = mysqli_fetch_assoc($slot_res)) {
                  echo "<div class='sub-card'>
                    <p><strong>Slot:</strong> {$slot['slot_name']}</p>
                    <p><strong>Connector:</strong> {$slot['connector_type']}</p>
                    <p><strong>Power:</strong> {$slot['power_kw']} kW</p>
                    <p><strong>Price:</strong> ₹{$slot['price_per_kwh']} /kWh</p>
                    <p><strong>Status:</strong> <span style='color:" . 
                      (($slot['status']=='Available') ? "green" : "red") . "'>{$slot['status']}</span></p>
                  </div>";
              }
          } else {
              echo "<p class='text-gray-600'>No slots available.</p>";
          }
          ?>
        </div>

        <!-- 🔋 Battery Rentals -->
        <div id="batteries-<?php echo $station['id']; ?>" class="hidden mt-4">
          <h4 class="section-title">🔋 Battery Rentals</h4>
          <?php
          $bat_sql = "SELECT * FROM battery_rentals WHERE station_id = $station_id";
          $bat_res = mysqli_query($conn, $bat_sql);
          if ($bat_res && mysqli_num_rows($bat_res) > 0) {
              while ($bat = mysqli_fetch_assoc($bat_res)) {
                  echo "<div class='sub-card'>
                    <p><strong>Brand:</strong> {$bat['brand']}</p>
                    <p><strong>Model:</strong> {$bat['model']}</p>
                    <p><strong>Capacity:</strong> {$bat['capacity']} Ah</p>
                    <p><strong>Range:</strong> {$bat['range_km']} km</p>
                    <p><strong>Rent Price:</strong> ₹{$bat['rent_price']}</p>
                    <p><strong>Status:</strong> <span style='color:" . 
                      (($bat['status']=='Available') ? "green" : "red") . "'>{$bat['status']}</span></p>
                  </div>";
              }
          } else {
              echo "<p class='text-gray-600'>No batteries available.</p>";
          }
          ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
    <p class="text-gray-700 text-center">No approved stations found in this district.</p>
  <?php endif; ?>
<?php endif; ?>

</div>

<script>
function toggleSection(id) {
  document.getElementById(id).classList.toggle('hidden');
}
</script>

</body>
</html>

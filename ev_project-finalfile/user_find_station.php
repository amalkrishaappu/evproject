<?php
include("db.php"); 

$stations = [];
$message = "";

if (isset($_POST['use_live'])) {
    $lat = floatval($_POST['latitude']);
    $lon = floatval($_POST['longitude']);

    // ✅ Haversine formula (distance in km)
    $sql = "
      SELECT id, station_name, owner_name, email, phone, district, location,
             latitude, longitude, charger_type, battery_rental, image_path, status,
             (6371 * acos(
                 cos(radians($lat)) * cos(radians(latitude)) * 
                 cos(radians(longitude) - radians($lon)) + 
                 sin(radians($lat)) * sin(radians(latitude))
             )) AS distance
      FROM stations
      WHERE status = 'approved'
      HAVING distance < 50
      ORDER BY distance ASC
      LIMIT 20
    ";

    $res = mysqli_query($conn, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $stations[] = $row;
        }
    } else {
        $message = "⚠️ No nearby charging stations found within 50 km.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Nearest EV Charging Stations</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-50 to-green-100 min-h-screen">

  <!-- 🔍 Header Section -->
  <section class="text-center py-10 bg-green-700 text-white shadow-md">
    <h1 class="text-3xl font-extrabold">⚡ Find Nearest EV Charging Stations</h1>
    <p class="text-green-100 mt-2">Locate approved charging points near you instantly</p>
  </section>

  <!-- 📍 Location Search Section -->
  <section class="text-center py-8">
    <button onclick="getLocation()" 
            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg transition">
      📍 Use My Current Location
    </button>

    <!-- Hidden form for sending lat/lon -->
    <form id="locationForm" method="POST" style="display:none;">
      <input type="hidden" name="latitude" id="lat">
      <input type="hidden" name="longitude" id="lon">
      <input type="hidden" name="use_live" value="1">
    </form>
  </section>

  <!-- 🧭 Results Section -->
  <section class="max-w-5xl mx-auto px-6 mb-12">
    <?php
    if (!empty($message)) {
        echo "<p class='text-center text-red-600 font-medium mb-5'>$message</p>";
    }

    foreach ($stations as $s) {
        $img = !empty($s['image_path']) ? $s['image_path'] : 'default_station.jpg';

        echo "
        <div class='bg-white shadow-lg rounded-xl p-6 mb-6 border-l-4 border-green-600 flex gap-6 items-center'>
          <img src='$img' alt='Station Image' class='w-32 h-32 rounded-lg object-cover border'>
          
          <div class='flex-1'>
            <h2 class='text-xl font-bold text-green-700 mb-1'>{$s['station_name']}</h2>
            <p class='text-gray-700 mb-1'>👤 Owner: {$s['owner_name']}</p>
            <p class='text-gray-600 mb-1'>📞 {$s['phone']} | 📧 {$s['email']}</p>
            <p class='text-gray-600 mb-1'>📍 {$s['location']}, {$s['district']}</p>
            <p class='text-gray-600 mb-1'>🔌 Charger Type: {$s['charger_type']}</p>
            <p class='text-gray-600 mb-2'>🔋 Battery Rental: {$s['battery_rental']}</p>
            <p class='text-gray-800 font-semibold mb-3'>📏 Distance: " . round($s['distance'], 2) . " km</p>
            
            <div class='flex gap-3'>
              <a href='https://www.google.com/maps?q={$s['latitude']},{$s['longitude']}' 
                 target='_blank'
                 class='bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold shadow transition'>
                 📍 View Location
              </a>
              <a href='user_slot_book.php?station_id={$s['id']}'
                  class='bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold shadow transition'>
                  🔋 Book Slot
                </a>
            </div>
          </div>
        </div>
        ";
    }
    ?>
  </section>

  <script>
    // Get user location
    function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          document.getElementById('lat').value = position.coords.latitude;
          document.getElementById('lon').value = position.coords.longitude;
          document.getElementById('locationForm').submit();
        }, function(error) {
          alert("Unable to fetch location: " + error.message);
        });
      } else {
        alert("Geolocation is not supported by this browser.");
      }
    }
  </script>

</body>
</html>

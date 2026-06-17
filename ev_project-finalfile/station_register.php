<?php
include 'db.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];
    $name = trim($_POST['name']);
    $owner_name = trim($_POST['owner_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $district = trim($_POST['district']);
    $location = trim($_POST['location']);
    $latitude = trim($_POST['latitude']);
    $longitude = trim($_POST['longitude']);
    $charger_type = $_POST['charger_type'];
    $battery_rental = $_POST['battery_rental'];
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validations
    if (!preg_match("/^[A-Za-z ]+$/", $owner_name))
        $errors['owner_name'] = "Owner name must contain only letters and spaces.";
    if (!preg_match("/^[0-9]{10}$/", $phone))
        $errors['phone'] = "Phone number must be 10 digits.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors['email'] = "Invalid email format.";
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}$/", $password))
        $errors['password'] = "Password must have uppercase, lowercase, special char & 8+ chars.";

    // Image upload
    $image_path = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $folder = "uploads/";
        if (!is_dir($folder)) mkdir($folder, 0777, true);
        $image_path = $folder . time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    // Insert only if no validation errors
    if (empty($errors)) {
        $sql = "INSERT INTO stations 
            (station_name, owner_name, email, phone, district, location, latitude, longitude, charger_type, battery_rental, username, password, image_path, registered_on, status)
            VALUES 
            ('$name', '$owner_name', '$email', '$phone', '$district', '$location', NULLIF('$latitude', ''), NULLIF('$longitude', ''), '$charger_type', '$battery_rental', '$username', '$password', '$image_path', NOW(), 'pending')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>
            alert('✅ Station registered successfully! Waiting for admin approval.');
            window.location.href='station_login.php';
            </script>";
        } else {
            echo "<script>alert('Database error: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>EV Station Registration | EV Finder</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
  background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
  font-family: 'Segoe UI', sans-serif;
}
.form-container {
  max-width: 750px;
  background: #fff;
  border-radius: 15px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  padding: 40px 50px;
  margin: 40px auto;
}
.form-title {
  text-align: center;
  font-weight: 600;
  color: #198754;
  margin-bottom: 25px;
}
.error {
  color: red;
  font-size: 0.9em;
  margin-top: 4px;
}
.map-btn {
  background-color: #198754;
  color: white;
  border: none;
  border-radius: 8px;
  padding: 8px 16px;
  cursor: pointer;
  margin-top: 10px;
}
.map-btn:hover {
  background-color: #146c43;
}
</style>
</head>

<body>
<div class="form-container">
  <h3 class="form-title">🔋 EV Station Registration</h3>

  <form method="POST" enctype="multipart/form-data" novalidate>
    <div class="row g-3">

      <div class="col-md-6">
        <label>Station Name</label>
        <input type="text" name="name" class="form-control" placeholder="Enter station name" required>
      </div>

      <div class="col-md-6">
        <label>Owner Name</label>
        <input type="text" name="owner_name" class="form-control" placeholder="Letters & spaces only" required>
        <?php if (!empty($errors['owner_name'])) echo "<div class='error'>{$errors['owner_name']}</div>"; ?>
      </div>

      <div class="col-md-6">
        <label>Email</label>
        <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required>
        <?php if (!empty($errors['email'])) echo "<div class='error'>{$errors['email']}</div>"; ?>
      </div>

      <div class="col-md-6">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control" placeholder="10-digit mobile number" required>
        <?php if (!empty($errors['phone'])) echo "<div class='error'>{$errors['phone']}</div>"; ?>
      </div>

      <div class="col-md-6">
        <label>District</label>
        <select name="district" id="district" class="form-control" required>
          <option value="">Select District</option>
          <option>Thiruvananthapuram</option><option>Kollam</option>
          <option>Pathanamthitta</option><option>Alappuzha</option>
          <option>Kottayam</option><option>Idukki</option>
          <option>Ernakulam</option><option>Thrissur</option>
          <option>Palakkad</option><option>Malappuram</option>
          <option>Kozhikode</option><option>Wayanad</option>
          <option>Kannur</option><option>Kasaragod</option>
        </select>
      </div>

      <div class="col-md-6">
        <label>Location</label>
        <input type="text" id="locationInput" name="location" class="form-control" placeholder="Enter location" required>
      </div>

      <div class="col-md-6">
        <label>Latitude</label>
        <input type="text" id="latitude" name="latitude" class="form-control" placeholder="Optional">
      </div>
      <div class="col-md-6">
        <label>Longitude</label>
        <input type="text" id="longitude" name="longitude" class="form-control" placeholder="Optional">
      </div>

      <div class="col-md-12">
        <button type="button" class="map-btn" onclick="openMap()">📍 View Location in Google Maps</button>
      </div>

      <div class="col-md-6">
        <label>Charger Type</label>
        <select name="charger_type" class="form-control" required>
          <option value="">Select Type</option>
          <option>Fast Charger</option>
          <option>Normal Charger</option>
        </select>
      </div>

      <div class="col-md-6">
        <label>Battery Rental Available?</label>
        <select name="battery_rental" class="form-control" required>
          <option value="">Select Option</option>
          <option>No</option><option>Yes</option>
        </select>
      </div>

      <div class="col-md-6">
        <label>Username</label>
        <input type="text" name="username" class="form-control" placeholder="Create a username" required>
      </div>

      <div class="col-md-6">
        <label>Password</label>
        <input type="password" name="password" class="form-control" placeholder="Min 8 chars, 1 cap, 1 special" required>
        <?php if (!empty($errors['password'])) echo "<div class='error'>{$errors['password']}</div>"; ?>
      </div>

      <div class="col-md-12">
        <label>Upload Station Image</label>
        <input type="file" name="image" class="form-control" accept="image/*" required>
      </div>

      <div class="col-md-12 mt-3">
        <button type="submit" class="btn btn-success w-100">Register Station</button>
      </div>

    </div>
  </form>

  <p class="text-center mt-3">
    Already have an account? <a href="station_login.php" style="color:#198754;font-weight:600;">Login here</a>
  </p>
</div>

<script>
function openMap() {
  const district = document.getElementById('district').value.trim();
  const location = document.getElementById('locationInput').value.trim();
  const lat = document.getElementById('latitude').value.trim();
  const lon = document.getElementById('longitude').value.trim();

  let mapUrl = '';

  if (lat && lon) {
    mapUrl = `https://www.google.com/maps?q=${lat},${lon}`;
  } else if (location && district) {
    const query = encodeURIComponent(`${location}, ${district}`);
    mapUrl = `https://www.google.com/maps/search/?api=1&query=${query}`;
  } else if (location) {
    mapUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(location)}`;
  } else {
    alert('Please enter at least a location.');
    return;
  }

  window.open(mapUrl, '_blank');
}

// --- NEW: Location-District Validation Before Submit ---
document.querySelector("form").addEventListener("submit", function (e) {
  const district = document.getElementById('district').value.trim().toLowerCase();
  const location = document.getElementById('locationInput').value.trim().toLowerCase();

  if (district && location && !location.includes(district)) {
    e.preventDefault();
    alert("⚠️ Location must belong to the selected district.\nPlease check your input.");
    document.getElementById('locationInput').focus();
  }
});
</script>

</body>
</html>

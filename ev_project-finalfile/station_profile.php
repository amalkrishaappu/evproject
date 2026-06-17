<?php
session_start();
include "db.php";

// ✅ Check if logged in
if (!isset($_SESSION['station_id'])) {
    header("Location: station_login.php");
    exit();
}

$station_id = $_SESSION['station_id'];

// ✅ Fetch station details
$sql = "SELECT * FROM stations WHERE id = '$station_id'";
$result = mysqli_query($conn, $sql);
$station = mysqli_fetch_assoc($result);

if (!$station) {
    echo "<h3>Station not found!</h3>";
    exit;
}

// ✅ Assign variables
$station_name = $station['station_name'];


// Validate image file

$image_path = $station['image_path'];

// Use default image if not found
if (empty($image_path) || !file_exists($image_path)) {
    $image_path = "uploads/default_station.png";
}

$location = trim($station['location']); // ✅ New: use 'location' field
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Station Profile</title>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    margin: 0;
    padding: 0;
}

/* Navbar */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(90deg, #2e7d32, #66bb6a);
    padding: 15px 30px;
    color: white;
    font-weight: 600;
}
.navbar a {
    text-decoration: none;
    color: white;
    padding: 8px 15px;
    border-radius: 8px;
    transition: 0.3s;
}
.navbar a:hover {
    background: white;
    color: #2e7d32;
}

/* Profile Card */
.profile-container {
    max-width: 400px;
    margin: 50px auto;
    background: white;
    border-radius: 15px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    padding: 30px;
    text-align: center;
}

.profile-container img {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #2e7d32;
}

h2 {
    color: #2e7d32;
    margin-top: 15px;
}

.details {
    text-align: center;
    margin: 25px auto;
    display: inline-block;
}

.details p {
    font-size: 16px;
    margin: 10px 0;
    color: #333;
}

button {
    background-color: #2e7d32;
    color: white;
    padding: 10px 25px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    margin: 10px;
    transition: 0.3s;
}
button:hover {
    background-color: #1b5e20;
}
</style>
</head>

<body>

<!-- ✅ Navbar -->
<div class="navbar">
  <div>Welcome, <?php echo htmlspecialchars($_SESSION['station_name']); ?></div>
  <div>
    <a href="station_dashboard.php">Dashboard</a>
    <a href="station_logout.php">Logout</a>
  </div>
</div>

<!-- ✅ Profile -->
<div class="profile-container">
    <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Station Image" style="width:150px;height:150px;border-radius:50%;object-fit:cover;border:3px solid #2e7d32;">

    <h2><?php echo htmlspecialchars($station_name); ?></h2>

    <div class="details">
        <p><strong>Location:</strong> <?php echo htmlspecialchars($station['location']); ?></p>
        <p><strong>Owner Name:</strong> <?php echo htmlspecialchars($station['owner_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($station['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($station['phone']); ?></p>
        <p><strong>Charger Type:</strong> <?php echo htmlspecialchars($station['charger_type']); ?></p>
        <p><strong>Battery Rental:</strong> <?php echo htmlspecialchars($station['battery_rental']); ?></p>

    </div>

    <!-- ✅ View Location Button -->
    <?php if (!empty($location)) { ?>
        <a href="https://www.google.com/maps?q=<?php echo urlencode($location); ?>" target="_blank">
            <button>View Location</button>
        </a>
    <?php } else { ?>
        <button disabled>No Location Available</button>
    <?php } ?>

    <br>

    <a href="station_edit.php?id=<?php echo $station['id']; ?>">
        <button>Edit Profile</button>
    </a>
</div>

</body>
</html>

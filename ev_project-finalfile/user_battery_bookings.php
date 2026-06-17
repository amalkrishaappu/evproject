<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first.'); window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

$bookings = $conn->query("
    SELECT bb.*, br.brand, br.model, br.image, s.station_name
    FROM battery_bookings bb
    JOIN battery_rentals br ON bb.battery_id = br.id
    JOIN stations s ON bb.station_id = s.id
    WHERE bb.user_id='$user_id'
    ORDER BY bb.booked_on DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Battery Bookings</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body { 
    font-family:'Poppins',sans-serif; 
    background:#f3faf4; 
    padding:0; 
    margin:0; 
    color:#0f5132; 
}
.navbar {
  background-color: #1b5e20;
  color: white;
  padding: 12px 0;
  text-align: right;
  position: sticky;
  top: 0;
  z-index: 50;
  box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
.navbar a {
  color: white;
  text-decoration: none;
  font-weight: 600;
  margin: 0 15px;
  transition: color 0.3s;
}
.navbar a:hover {
  color: #a5d6a7;
}
.back-btn {
    background-color:white;
    color:#0f5132;
    border:none;
    padding:8px 16px;
    border-radius:6px;
    cursor:pointer;
    font-weight:600;
    transition:0.3s;
}
.back-btn:hover {
    background-color:#e6f4ea;
}
.container {
    padding:30px;
}
h2 { text-align:center; color:#0f5132; margin-bottom:30px; }
.card { 
    background:white; 
    border-radius:12px; 
    padding:15px; 
    box-shadow:0 2px 8px rgba(0,0,0,0.1); 
    margin-bottom:20px; 
    display:flex; 
    align-items:center; 
    gap:15px; 
}
.card img { width:100px; height:100px; border-radius:10px; object-fit:cover; }
.info { flex:1; }
.status { font-weight:bold; }
button { 
    background-color:#0f5132; 
    color:white; 
    border:none; 
    padding:6px 12px; 
    border-radius:6px; 
    cursor:pointer; 
    margin-right:6px; 
}
button:hover { background:#14532d; }
</style>
</head>
<body>


<!-- 🌿 NAVBAR -->
<div class="navbar">
  <a href="user_home.php">Home</a>
  <a href="user_battery_view.php">Battery Rentals</a>
  <a href="user_logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
</div>

    <h1 style="text-align:center;"> My Battery Bookings</h1>

<div class="container">
<?php if ($bookings->num_rows > 0): ?>
    <?php while($b = $bookings->fetch_assoc()): ?>
        <div class="card">
            <img src="uploads/<?php echo htmlspecialchars($b['image']); ?>" alt="Battery">
            <div class="info">
                <h3><?php echo htmlspecialchars($b['brand']." ".$b['model']); ?></h3>
                <p><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($b['station_name']); ?></p>
                <p><i class="fa-solid fa-calendar"></i> Booked: <?php echo htmlspecialchars($b['booked_on']); ?></p>
                <p><i class="fa-solid fa-clock"></i> Due: <?php echo htmlspecialchars($b['return_due']); ?></p>
                <p><strong>Total:</strong> ₹<?php echo htmlspecialchars($b['total_price']); ?></p>
                <p class="status">Status: 
                    <?php 
                        if ($b['status']=='Active') echo "<span style='color:green;'>Active</span>";
                        elseif ($b['status']=='Cancelled') echo "<span style='color:red;'>Cancelled</span>";
                        elseif ($b['status']=='Return Requested') echo "<span style='color:orange;'>Return Requested</span>";
                        else echo "<span style='color:blue;'>Returned</span>"; 
                    ?>
                </p>
            </div>

            <?php if ($b['status']=='Active'): ?>
                <form method="POST" action="user_battery_cancel.php" onsubmit="return confirm('Cancel this booking?');">
                    <input type="hidden" name="id" value="<?php echo $b['id']; ?>">
                    <button type="submit">Cancel</button>
                </form>

                <form method="POST" action="user_battery_return.php" onsubmit="return confirm('Send return request to station?');">
                    <input type="hidden" name="id" value="<?php echo $b['id']; ?>">
                    <button type="submit">Return</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p style="text-align:center;">No bookings yet.</p>
<?php endif; ?>
</div>

</body>
</html>

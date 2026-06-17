<?php
session_start();
include 'db.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

$user_id = $_SESSION['user_id'] ?? null;

// Handle popup messages passed via ?msg=...
$msg = $_GET['msg'] ?? null;
$msg_map = [
    'success' => '✅ Battery booked successfully! Redirecting to your bookings...',
    'out_of_stock' => '🚫 Booking failed: Out of stock.',
    'not_logged_in' => '⚠️ Please log in to book a battery.',
    'failed' => '❌ Booking failed. Please try again.'
];

// Get selected district from dropdown
$selected_district = $_GET['district'] ?? '';

// ✅ Kerala Districts (Static List)
$kerala_districts = [
    "Thiruvananthapuram","Kollam","Pathanamthitta","Alappuzha","Kottayam",
    "Idukki","Ernakulam","Thrissur","Palakkad","Malappuram",
    "Kozhikode","Wayanad","Kannur","Kasaragod"
];

// Build query conditionally
$sql = "
    SELECT b.*, s.station_name, s.district 
    FROM battery_rentals b 
    JOIN stations s ON b.station_id = s.id
";
if (!empty($selected_district)) {
    $sql .= " WHERE s.district = '" . mysqli_real_escape_string($conn, $selected_district) . "'";
}
$sql .= " ORDER BY b.id DESC";

$batteries = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Battery Rentals</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background-color: #e6f4ea;
    margin: 0;
    padding: 0;
    color: #0f5132;
}
.navbar {
    background: #0f5132;
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.navbar a {
    color: white;
    text-decoration: none;
    font-weight: 500;
    margin-right: 15px;
}
.navbar a:hover { text-decoration: underline; }

.container { max-width: 800px; margin: 40px auto; padding: 0 20px; }
h2 {
    text-align: center;
    color: #0f5132;
    font-size: 30px;
    margin-bottom: 20px;
}
.filter-box {
    text-align: center;
    margin-bottom: 25px;
}
select {
    padding: 8px 15px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 15px;
}
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
}
.card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    overflow: hidden;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}
.card:hover { transform: scale(1.03); box-shadow: 0 4px 14px rgba(0,0,0,0.25); }
.card img { width: 100%; border-bottom: 3px solid #0f5132; object-fit: cover; height: 270px; }
.card-content { padding: 15px; background: #f6fff8; flex: 1; display: flex; flex-direction: column; }
.card-content h3 { margin: 0 0 10px; font-size: 18px; color: #0f5132; }
.details p { margin: 4px 0; font-size: 14px; color: #1b4332; }
button {
    background-color: #0f5132;
    color: white;
    border: none;
    padding: 10px 22px;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.3s;
    margin-top: auto;
}
button:hover { background-color: #14532d; }
button:disabled {
    background-color: #ccc;
    color: #666;
    cursor: not-allowed;
}
.status-green { color: green; font-weight: bold; }
.status-red { color: red; font-weight: bold; }
.price { font-weight: bold; color: #14532d; display: block; margin-top: 10px; font-size: 16px; text-align:center; }
label { display:block; margin-top:6px; font-weight:600; text-align:center; }
input[type="number"] { width:70px; margin:6px auto; display:block; text-align:center; padding:6px; border-radius:6px; border:1px solid #cfe9d2; }
.small-note { font-size:12px; color:#4b5563; text-align:center; margin-top:6px; }
</style>
</head>
<body>

<!-- ✅ Navbar -->
<div class="navbar">
    <div><i class="fa-solid fa-bolt"></i> <strong>EcoCharge</strong></div>
    <div>
        <a href="user_home.php">Home</a>
        <a href="user_battery_bookings.php">My Bookings</a>
        <a href="user_logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h2><i class="fa-solid fa-battery-full"></i> Battery Rentals</h2>

    <!-- ✅ District Filter Dropdown -->
    <div class="filter-box">
        <form method="GET" action="">
            <label>Seach</label>
            <select name="district" onchange="this.form.submit()">
                <option value="">-- Select District --</option>
                <?php foreach ($kerala_districts as $dist): ?>
                    <option value="<?= $dist ?>" <?= ($dist == $selected_district ? 'selected' : '') ?>>
                        <?= htmlspecialchars($dist) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <div class="grid">
        <?php if ($batteries && $batteries->num_rows > 0): ?>
            <?php while($row = $batteries->fetch_assoc()): 
                $stock = (int)$row['stock_count'];
                $isAvailable = $stock > 0;
                $statusText = $isAvailable
                    ? "<span class='status-green'>Available ({$stock} in stock)</span>"
                    : "<span class='status-red'>Fully Booked / Unavailable</span>";
            ?>
                <div class="card">
                    <img src="uploads/<?php echo htmlspecialchars($row['image'] ?: 'placeholder.png'); ?>" alt="Battery">
                    <div class="card-content">
                        <h3><?php echo htmlspecialchars($row['brand'] . ' ' . $row['model']); ?></h3>
                        <div class="details">
                            <p><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($row['station_name']); ?> (<?php echo htmlspecialchars($row['district']); ?>)</p>
                            <p><i class="fa-solid fa-car-side"></i> <?php echo htmlspecialchars($row['compatibility']); ?></p>
                            <p><i class="fa-solid fa-bolt"></i> <?php echo htmlspecialchars($row['voltage']); ?>V, <?php echo htmlspecialchars($row['capacity']); ?>Ah</p>
                            <p><i class="fa-solid fa-layer-group"></i> <?php echo $statusText; ?></p>
                        </div>
                        <p class="price">₹<?php echo htmlspecialchars($row['rent_price']); ?> / day</p>

                        <form method="POST" action="user_battery_booking.php" style="text-align:center;">
                            <input type="hidden" name="battery_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="station_id" value="<?php echo $row['station_id']; ?>">
                            <input type="hidden" name="price_per_day" value="<?php echo $row['rent_price']; ?>">
                            <label>Days</label>
                            <input type="number" name="rental_days" min="1" value="1" required>
                            <div class="small-note">Total price computed on booking page.</div>
                            <button type="submit" <?php echo !$isAvailable ? 'disabled' : ''; ?>>
                                <?php echo $isAvailable ? 'Book Now' : 'Unavailable'; ?>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center;">🚫 No batteries available in this district.</p>
        <?php endif; ?>
    </div>
</div>

<script>
(function(){
    const params = new URLSearchParams(window.location.search);
    const msg = params.get('msg');
    if (!msg) return;

    const map = {
        'success': '✅ Battery booked successfully! Click OK to view your bookings.',
        'out_of_stock': '🚫 Booking failed: Out of stock.',
        'not_logged_in': '⚠️ Please log in to book a battery.',
        'failed': '❌ Booking failed. Please try again.'
    };
    const text = map[msg] || 'Status: ' + msg;
    alert(text);

    if (msg === 'success') {
        window.location.href = 'user_battery_bookings.php';
    } else {
        const url = new URL(window.location);
        url.searchParams.delete('msg');
        window.history.replaceState({}, document.title, url.toString());
    }
})();
</script>

</body>
</html>

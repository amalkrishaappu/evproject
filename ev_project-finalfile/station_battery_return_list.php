<?php
include 'db.php';
session_start();

// Check if station logged in
if (!isset($_SESSION['station_id'])) {
    header("Location: station_login.php");
    exit();
}

$station_id = $_SESSION['station_id'];

$query = "
    SELECT bb.id AS booking_id, 
           bb.battery_id,
           u.name AS user_name, 
           u.email, 
           u.phoneno, 
           b.brand, 
           b.model, 
           b.capacity, 
           b.rent_price, 
           bb.rental_days, 
           bb.total_price, 
           bb.status, 
           bb.booked_on, 
           bb.return_due
    FROM battery_bookings bb
    JOIN users u ON bb.user_id = u.id
    JOIN battery_rentals b ON bb.battery_id = b.id
    WHERE bb.station_id = '$station_id' 
      AND bb.status = 'Return Requested'
    ORDER BY bb.booked_on DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Return Requests - Station Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-50 text-gray-800">

    <!-- ✅ NAVBAR -->
    <nav class="bg-gradient-to-r from-green-700 to-green-500 text-white shadow-md py-4 px-8 flex justify-between items-center sticky top-0 z-50">
        <div class="text-xl font-semibold tracking-wide">
            ⚡ EV Station Panel
        </div>
        <div class="space-x-4">
            <a href="station_view_user_bookbattery.php" class="bg-white text-green-700 font-medium px-4 py-2 rounded-md hover:bg-green-100 transition">Back
            </a>
            <a href="station_dashboard.php" class="bg-white text-green-700 font-medium px-4 py-2 rounded-md hover:bg-green-100 transition">Dashboard
            </a>
            <a href="station_logout.php" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-md font-medium text-white transition">Logout
            </a>
            
        </div>
    </nav>

    <!-- ✅ MAIN CONTENT -->
    <div class="p-6 max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-green-700 mb-6">Battery Return Requests</h1>

        <?php if (mysqli_num_rows($result) > 0) { ?>
            <table class="w-full border border-green-200 bg-white shadow-md rounded-lg">
                <thead class="bg-green-100 text-green-800">
                    <tr>
                        <th class="p-3 border">User</th>
                        <th class="p-3 border">Battery</th>
                        <th class="p-3 border">Days</th>
                        <th class="p-3 border">Total Price</th>
                        <th class="p-3 border">Return Due</th>
                        <th class="p-3 border">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr class="hover:bg-green-50">
                            <td class="p-3 border">
                                <b><?= htmlspecialchars($row['user_name']); ?></b><br>
                                <?= htmlspecialchars($row['email']); ?><br>
                                <?= htmlspecialchars($row['phoneno']); ?>
                            </td>
                            <td class="p-3 border">
                                <?= htmlspecialchars($row['brand']); ?> - <?= htmlspecialchars($row['model']); ?><br>
                                <?= htmlspecialchars($row['capacity']); ?>Ah
                            </td>
                            <td class="p-3 border text-center"><?= $row['rental_days']; ?></td>
                            <td class="p-3 border text-center">₹<?= $row['total_price']; ?></td>
                            <td class="p-3 border text-center"><?= $row['return_due']; ?></td>
                            <td class="p-3 border text-center">
                                <form method="POST" action="station_battery_return_approve.php" onsubmit="return confirm('Approve this return?');">
                                    <input type="hidden" name="booking_id" value="<?= $row['booking_id']; ?>">
                                    <input type="hidden" name="battery_id" value="<?= $row['battery_id']; ?>">
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg transition">
                                        Approve
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="p-4 bg-green-100 text-green-800 rounded-md">No return requests found.</div>
        <?php } ?>
    </div>

</body>
</html>

<?php
include '../config/db.php';
session_start();
// Uncomment this if needed
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit;
// }

$station_id = $_GET['id'] ?? null;

if (!$station_id) {
    echo "❌ No station selected.";
    exit;
}

// Fetch station info (total and available slots)
$station = mysqli_fetch_assoc(mysqli_query($conn, "SELECT name, slot, available_slot FROM station WHERE id = '$station_id'"));

if (!$station) {
    echo "❌ Station not found.";
    exit;
}

echo "<h2>🔋 Station: " . htmlspecialchars($station['name']) . "</h2>";
echo "<p>Total Slots: <strong>{$station['slot']}</strong><br>";
echo "Live Available Slots: <strong>{$station['available_slot']}</strong></p>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'])) {
    $date = $_POST['date'];

    // Get all bookings for this station and date
    $sql = "SELECT booking_time FROM booking WHERE station_id='$station_id' AND booking_date='$date'";
    $result = mysqli_query($conn, $sql);

    $booked_slots = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $booked_slots[] = $row['booking_time'];
    }

    $booked_count = count($booked_slots);
    $available_count = $station['slot'] - $booked_count;

    echo "<h3>📅 Bookings for <strong>$date</strong>:</h3>";

    if ($booked_count > 0) {
        echo "<ul>";
        foreach ($booked_slots as $time) {
            echo "<li>⛔ $time</li>";
        }
        echo "</ul>";
    } else {
        echo "✅ No bookings yet for this date.<br>";
    }

    echo "<p><strong>Booked:</strong> $booked_count / {$station['slot']}<br>";
    echo "<strong>Available:</strong> $available_count slots</p>";

    echo "<a href='booking.php?id=$station_id&date=$date'>➡️ Book a Slot</a>";
} else {
    echo "<h3>Check Booked Slots</h3>";
    echo "<form method='POST'>";
    echo "📅 Select Date: <input type='date' name='date' required>";
    echo "<button type='submit'>Check Slots</button>";
    echo "</form>";
}

echo "<br><br><a href='stations.php'>⬅️ Back to Stations</a>";
?>


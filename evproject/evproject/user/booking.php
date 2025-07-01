<?php
include '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$station_id = $_GET['id'] ?? null;
$date = $_GET['date'] ?? date('Y-m-d');
echo "DEBUG: booking date = $date";
$message = "";

// ✅ Validate station ID
if (!$station_id) {
    die("Invalid request: No station selected.");
}

// ✅ Get correct station info (assuming table is `stations`)
$station = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM station WHERE id='$station_id'"));

if (!$station) {
    die("Station not found.");
}

// ✅ Booking logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
    $time = $_POST['time'];
    $user_id = $_SESSION['user_id'];
    $available = $station['available_slot']; // field name should match your DB

    if ($available <= 0) {
        $message = "<div class='alert alert-danger'>No slots available at this station.</div>";
    } else {
        // ✅ Check for duplicate time booking
        $check = mysqli_query($conn, "SELECT * FROM booking WHERE station_id='$station_id' AND booking_date='$date' AND booking_time='$time'");
        if (mysqli_num_rows($check) > 0) {
            $message = "<div class='alert alert-warning'>This time slot is already booked. Please choose another.</div>";
        } else {
            // ✅ Insert booking
            $sql = "INSERT INTO booking (booking_date, status, booking_time, user_id,station_id)
                    VALUES ('$date', 'booked', '$time', '$user_id', '$station_id')";
            if (mysqli_query($conn, $sql)) {
                // ✅ Decrease available slot
                mysqli_query($conn, "UPDATE station SET available_slot = available_slot - 1 WHERE id = '$station_id' AND available_slot > 0");

                // ✅ Refresh station data
                $station = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM station WHERE id='$station_id'"));

                $message = "<div class='alert alert-success'>✅ Booking successful for $date at $time.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Booking failed. Try again.</div>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book Charging Slot</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4>📅 Book a Charging Slot</h4>
        </div>

        <div class="card-body">

            <!-- Alert messages -->
            <?= $message ?>

            <!-- Station Info -->
            <h5 class="mb-3">
                🔌 <?= htmlspecialchars($station['name']) ?> <br>
                📍 <?= htmlspecialchars($station['location']) ?>
            </h5>
            <p>
                <strong>Available Slots:</strong> <?= $station['available_slot'] ?><br>
                <strong>Booking Date:</strong> <?= $date ?>
            </p>

            <!-- Booking Form -->
            <form method="POST">
                <div class="mb-3">
                    <label for="time" class="form-label">Select Time Slot</label>
                    <input type="time" name="time" id="time" class="form-control" required>
                </div>

                <button type="submit" name="book" class="btn btn-success">Book Slot</button>
                <a href="stations.php" class="btn btn-secondary ms-2">Back to Stations</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>

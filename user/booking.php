<?php
include '../config/db.php';
session_start();

// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit;
// }

$message = "";

// ✅ Fetch all stations for the form
$stations = mysqli_query($conn, "SELECT * FROM station");

// ✅ Handle booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
    $station_id = $_POST['station_id'] ?? null;
    $booking_date = $_POST['date'] ?? null;
    $time = $_POST['time'] ?? null;
    $user_id = $_SESSION['user_id'];
    if (!$station_id || !$booking_date || !$time) {
        $message = "<div class='alert alert-danger'>Please fill in all the fields.</div>";
    } else {
        $station = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM station WHERE id='$station_id'"));
        if (!$station) {
            $message = "<div class='alert alert-danger'>Invalid station selected.</div>";
        } elseif ($station['available_slot'] <= 0) {
            $message = "<div class='alert alert-danger'>No slots available at this station.</div>";
        } else {
            $check = mysqli_query($conn, "SELECT * FROM booking WHERE station_id='$station_id' AND booking_date='$booking_date' AND booking_time='$time'");
            if (mysqli_num_rows($check) > 0) {
                $message = "<div class='alert alert-warning'>Time slot already booked. Please choose another.</div>";
            } else {
                $insert = "INSERT INTO booking (booking_date, status, booking_time, user_id, station_id)
                           VALUES ('$booking_date', 'booked', '$time', '$user_id', '$station_id')";
                if (mysqli_query($conn, $insert)) {
                    mysqli_query($conn, "UPDATE station SET available_slot = available_slot - 1 WHERE id = '$station_id'");
                    $message = "<div class='alert alert-success'>✅ Slot booked for <strong>$booking_date at $time</strong>.</div>";
                } else {
                    $message = "<div class='alert alert-danger'>Booking failed. Please try again.</div>";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Charging Slot</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #eef1fc;
        }
        .card {
            border-radius: 20px;
        }
        .btn-gradient {
            background: linear-gradient(to right, #5f62f2, #a061d1);
            border: none;
            color: white;
            font-weight: 600;
        }
        .btn-gradient:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="card shadow p-4">
        <h3 class="text-center mb-4 text-primary">📅 Book a Charging Slot</h3>

        <!-- Message -->
        <?= $message ?>

        <!-- Booking Form -->
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Select Station:</label><br>
                <?php while ($row = mysqli_fetch_assoc($stations)) : ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="station_id" id="station<?= $row['id'] ?>" value="<?= $row['id'] ?>" required>
                        <label class="form-check-label" for="station<?= $row['id'] ?>">
                            🔌 <?= htmlspecialchars($row['name']) ?> — 📍 <?= htmlspecialchars($row['location']) ?> (<?= $row['available_slot'] ?> slots)
                        </label>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Select Date:</label>
                <input type="date" name="date" id="date" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="time" class="form-label">Select Time:</label>
                <input type="time" name="time" id="time" class="form-control" required>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" name="book" class="btn btn-gradient px-4">Book Slot</button>
                <a href="stations.php" class="btn btn-outline-secondary">← view stations</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>

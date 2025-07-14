<?php
include '../config/db.php';
session_start();
$user_id = $_SESSION['user_id'] ?? 1; // fallback demo user

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['return'])) {
    $rental_id = $_POST['rental_id'];
    $feedback = $_POST['feedback'] ?? '';
    $rating = $_POST['rating'] ?? 0;

    // ✅ Get battery_id from rental ID
    $res = mysqli_query($conn, "SELECT battery_id FROM battery_rental WHERE id = '$rental_id' AND user_id = '$user_id' AND return_time IS NULL");
    $data = mysqli_fetch_assoc($res);
    $battery_id = $data['battery_id'] ?? null;

    if ($battery_id) {
        // Mark battery as available
        mysqli_query($conn, "UPDATE battery SET is_rented = 0 WHERE battery_id = '$battery_id'");

        // Update rental record
        mysqli_query($conn, "UPDATE battery_rental 
                             SET return_time = NOW(), feedback = '$feedback', rating = '$rating' 
                             WHERE id = '$rental_id'");

        $msg = "✅ Battery returned successfully!";
    } else {
        $msg = "❌ Rental not found or already returned.";
    }
}

// ✅ Show currently rented batteries by this user
$query = "SELECT br.id AS rental_id, b.battery_id, b.brand, b.model, s.name AS station_name
          FROM battery_rental br
          JOIN battery b ON br.battery_id = b.battery_id
          JOIN station s ON b.station_id = s.id
          WHERE br.user_id = '$user_id' AND br.return_time IS NULL";

$result = mysqli_query($conn, $query);
?>

<h2>Return Rented Battery</h2>
<?= $msg ?? '' ?>

<?php if (mysqli_num_rows($result) > 0): ?>
<form method="POST">
    <label for="rental_id">🔋 Select Rental to Return:</label>
    <select name="rental_id" required>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <option value="<?= $row['rental_id'] ?>">
                <?= "Rental #{$row['rental_id']} - {$row['battery_id']} ({$row['brand']} {$row['model']} at {$row['station_name']})" ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label for="rating">⭐ Rate the Battery (1-5):</label>
    <input type="number" name="rating" min="1" max="5"><br><br>

    <label for="feedback">💬 Feedback:</label><br>
    <textarea name="feedback" rows="3" cols="40"></textarea><br><br>

    <button name="return">Return Battery</button>
</form>
<?php else: ?>
<p>🔍 No active rentals to return.</p>
<?php endif; ?>
